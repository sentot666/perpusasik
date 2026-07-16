<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Circulation;
use App\Models\BookItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CirculationController extends Controller
{
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

        $stats = [
            'active'   => Circulation::where('status', 'Dipinjam')->count(),
            'overdue'  => Circulation::overdue()->count(),
            'returned' => Circulation::whereDate('return_date', today())->count(),
        ];

        return view('circulations.index', compact('circulations', 'stats'));
    }

    public function loanForm()
    {
        return view('circulations.loan');
    }

    public function storeLoan(Request $request)
    {
        $request->validate([
            'member_id'    => 'required|exists:members,id',
            'book_item_id' => 'required|exists:book_items,id',
        ]);

        $member   = Member::findOrFail($request->member_id);
        $bookItem = BookItem::findOrFail($request->book_item_id);

        // Validations
        if (! $member->is_active) {
            return back()->with('error', 'Anggota tidak aktif atau sudah kedaluwarsa.');
        }
        if ($member->is_expired) {
            return back()->with('error', 'Keanggotaan sudah kedaluwarsa. Perbarui keanggotaan terlebih dahulu.');
        }
        if ($bookItem->status !== 'Tersedia') {
            return back()->with('error', 'Eksemplar buku sedang tidak tersedia untuk dipinjam.');
        }

        $maxItems = (int) Setting::get('max_loan_items', 3);
        if ($member->activeCirculations()->count() >= $maxItems) {
            return back()->with('error', "Anggota sudah mencapai batas maksimal peminjaman ({$maxItems} eksemplar).");
        }

        $loanDays = (int) Setting::get('loan_duration', 14);

        DB::transaction(function () use ($member, $bookItem, $loanDays, $request) {
            $circulation = Circulation::create([
                'transaction_code' => Circulation::generateCode(),
                'member_id'        => $member->id,
                'book_item_id'     => $bookItem->id,
                'user_id'          => auth()->id(),
                'loan_date'        => today(),
                'due_date'         => today()->addDays($loanDays),
                'status'           => 'Dipinjam',
                'notes'            => $request->notes,
            ]);

            $bookItem->update(['status' => 'Dipinjam']);
        });

        return redirect()->route('circulations.loan')
            ->with('success', "Peminjaman berhasil diproses. Jatuh tempo: " . today()->addDays($loanDays)->format('d/m/Y'));
    }

    public function returnForm()
    {
        return view('circulations.return');
    }

    public function processReturn(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $bookItem = BookItem::where('barcode', $request->barcode)->first();

        if (! $bookItem) {
            return back()->with('error', 'Barcode tidak ditemukan dalam sistem.');
        }

        $circulation = Circulation::where('book_item_id', $bookItem->id)
            ->where('status', 'Dipinjam')
            ->with(['member', 'bookItem.book'])
            ->first();

        if (! $circulation) {
            return back()->with('error', 'Buku ini tidak sedang dalam status dipinjam.');
        }

        $finePerDay  = (float) Setting::get('fine_per_day', 1000);
        $daysOverdue = $circulation->is_overdue ? $circulation->days_overdue : 0;
        $fineAmount  = $daysOverdue * $finePerDay;

        DB::transaction(function () use ($circulation, $bookItem, $fineAmount) {
            $circulation->update([
                'return_date' => today(),
                'status'      => 'Dikembalikan',
                'fine_amount' => $fineAmount,
                'fine_paid'   => $fineAmount == 0,
            ]);

            $bookItem->update(['status' => 'Tersedia']);
        });

        $msg = "Pengembalian berhasil diproses.";
        if ($fineAmount > 0) {
            $msg .= " Denda: Rp " . number_format($fineAmount, 0, ',', '.');
        }

        return redirect()->route('circulations.return')
            ->with('success', $msg)
            ->with('returned_circulation', $circulation->id);
    }

    public function show(Circulation $circulation)
    {
        $circulation->load(['member', 'bookItem.book', 'user']);
        return view('circulations.show', compact('circulation'));
    }

    public function renew(Request $request, Circulation $circulation)
    {
        $maxRenewals = (int) Setting::get('max_renewals', 2);
        $loanDays    = (int) Setting::get('loan_duration', 14);

        if ($circulation->status !== 'Dipinjam') {
            return back()->with('error', 'Hanya peminjaman aktif yang bisa diperpanjang.');
        }
        if ($circulation->renewal_count >= $maxRenewals) {
            return back()->with('error', "Sudah mencapai batas maksimal perpanjangan ({$maxRenewals}x).");
        }

        $circulation->update([
            'due_date'      => $circulation->due_date->addDays($loanDays),
            'renewal_count' => $circulation->renewal_count + 1,
        ]);

        return back()->with('success', 'Peminjaman berhasil diperpanjang.');
    }

    public function payFine(Request $request, Circulation $circulation)
    {
        $circulation->update([
            'fine_paid'    => true,
            'fine_paid_at' => now(),
        ]);

        return back()->with('success', 'Denda berhasil dibayar.');
    }
}
