<?php

namespace App\Http\Controllers;

use App\Models\Circulation;
use App\Models\Setting;
use App\Services\CirculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CirculationController extends Controller
{
    /**
     * @var CirculationService
     */
    protected $circulationService;

    /**
     * Create a new controller instance.
     *
     * @param CirculationService $circulationService
     */
    public function __construct(CirculationService $circulationService)
    {
        $this->circulationService = $circulationService;
    }

    /**
     * Display a listing of circulations.
     */
    public function index(Request $request)
    {
        $query = Circulation::with(['member', 'bookItem.book', 'user'])
            ->latest();

        if ($status = $request->status) {
            $query->where('status', $status);
        }
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('member', fn($m) => $m->where('name', 'like', "%{$search}%")
                    ->orWhere('member_code', 'like', "%{$search}%"))
                  ->orWhere('transaction_code', 'like', "%{$search}%");
            });
        }
        if ($request->overdue) {
            $query->overdue();
        }

        $circulations = $query->paginate(20)->withQueryString();

        $classLoans = \App\Models\ClassLoan::latest()->paginate(10, ['*'], 'class_page')->withQueryString();

        $stats = [
            'active'   => Circulation::where('status', 'Dipinjam')->count(),
            'overdue'  => Circulation::overdue()->count(),
            'returned' => Circulation::whereDate('return_date', today())->count(),
        ];

        return view('circulations.index', compact('circulations', 'classLoans', 'stats'));
    }

    /**
     * Show loan form.
     */
    public function loanForm()
    {
        return view('circulations.loan');
    }

    /**
     * Store a newly created loan.
     */
    public function storeLoan(Request $request)
    {
        $request->validate([
            'member_id'       => 'required|exists:members,id',
            'book_item_ids'   => 'required|array|min:1',
            'book_item_ids.*' => 'exists:book_items,id',
            'notes'           => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->book_item_ids as $bookItemId) {
                    $this->circulationService->loanBook(
                        (int) $request->member_id,
                        (int) $bookItemId,
                        $request->notes
                    );
                }
            });

            $loanDays = (int) Setting::get('loan_duration', 14);
            $count = count($request->book_item_ids);

            return redirect()->route('circulations.loan')
                ->with('success', "Peminjaman {$count} buku berhasil diproses. Jatuh tempo: " . today()->addDays($loanDays)->format('d/m/Y'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show return form.
     */
    public function returnForm()
    {
        $activeClassLoans = \App\Models\ClassLoan::where('status', 'Dipinjam')->latest()->get();
        return view('circulations.return', compact('activeClassLoans'));
    }

    /**
     * Process book return.
     */
    public function processReturn(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
            'notes'   => 'nullable|string',
        ]);

        try {
            $circulation = $this->circulationService->returnBook(
                $request->barcode,
                $request->notes
            );

            $msg = "Pengembalian berhasil diproses.";
            if ($circulation->fine_amount > 0) {
                $msg .= " Denda: Rp " . number_format($circulation->fine_amount, 0, ',', '.');
            }

            return redirect()->route('circulations.return')
                ->with('success', $msg)
                ->with('returned_circulation', $circulation->id);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display circulation details.
     */
    public function show(Circulation $circulation)
    {
        $circulation->load(['member', 'bookItem.book', 'user']);
        return view('circulations.show', compact('circulation'));
    }

    /**
     * Renew loan duration.
     */
    public function renew(Request $request, Circulation $circulation)
    {
        try {
            $this->circulationService->renewLoan($circulation->id);
            return back()->with('success', 'Peminjaman berhasil diperpanjang.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Pay circulation fine.
     */
    public function payFine(Request $request, Circulation $circulation)
    {
        try {
            $this->circulationService->payFine($circulation->id);
            return back()->with('success', 'Denda berhasil dibayar.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created class loan.
     */
    public function storeClassLoan(Request $request)
    {
        $validated = $request->validate([
            'borrower_name' => 'required|string|max:255',
            'origin'        => 'required|string|max:255',
            'book_type'     => 'required|string|max:255',
            'quantity'      => 'required|integer|min:1',
            'notes'         => 'nullable|string',
        ]);

        $validated['loan_date'] = today();
        $validated['status'] = 'Dipinjam';
        $validated['user_id'] = auth()->id();

        \App\Models\ClassLoan::create($validated);

        return redirect()->route('circulations.loan')->with('success', 'Peminjaman kelas berhasil disimpan.');
    }

    /**
     * Process return for class loan.
     */
    public function returnClassLoan(Request $request, \App\Models\ClassLoan $classLoan)
    {
        if ($classLoan->status === 'Dikembalikan') {
            return back()->with('error', 'Peminjaman ini sudah dikembalikan.');
        }

        $classLoan->update([
            'status' => 'Dikembalikan',
            'return_date' => today(),
        ]);

        return redirect()->route('circulations.return')->with('success', 'Pengembalian peminjaman kelas berhasil diproses.');
    }
    /**
     * Process return for class loan via form dropdown.
     */
    public function processClassReturnForm(Request $request)
    {
        $request->validate([
            'class_loan_id' => 'required|exists:class_loans,id',
        ]);

        $classLoan = \App\Models\ClassLoan::findOrFail($request->class_loan_id);

        if ($classLoan->status === 'Dikembalikan') {
            return back()->with('error', 'Peminjaman ini sudah dikembalikan.');
        }

        $classLoan->update([
            'status' => 'Dikembalikan',
            'return_date' => today(),
        ]);

        return redirect()->route('circulations.return')->with('success', 'Pengembalian peminjaman kelas berhasil diproses.');
    }
}
