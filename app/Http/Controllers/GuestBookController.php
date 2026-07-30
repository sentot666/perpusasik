<?php

namespace App\Http\Controllers;

use App\Models\GuestBook;
use App\Models\Member;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GuestBookController extends Controller
{
    /**
     * Display a listing of guest book entries.
     */
    public function index(Request $request)
    {
        $query = GuestBook::query()->latest('visit_date')->latest('visit_time');

        // Apply filters
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('institution', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%");
            });
        }

        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        if ($startDate && $endDate) {
            $query->whereBetween('visit_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('visit_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('visit_date', '<=', $endDate);
        }

        $totalParticipants = (clone $query)->sum('participants_count');
        $activities = $query->paginate(20)->withQueryString();

        return view('guest-books.index', compact('activities', 'startDate', 'endDate', 'totalParticipants'));
    }

    /**
     * Show barcode scanning form for members.
     */
    public function scanForm()
    {
        return view('guest-books.scan');
    }

    /**
     * Handle barcode submission via AJAX.
     */
    public function scanSubmit(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $barcode = $request->barcode;

        // Cari member berdasarkan barcode atau member_code
        $member = Member::where('barcode', $barcode)
            ->orWhere('member_code', $barcode)
            ->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Anggota tidak ditemukan. Pastikan barcode benar.'
            ], 404);
        }

        // Cek apakah member aktif
        if (!$member->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu Anggota ini tidak aktif.'
            ], 400);
        }

        // Simpan ke buku tamu
        GuestBook::create([
            'visit_date' => now()->toDateString(),
            'visit_time' => now()->toTimeString(),
            'name' => $member->name,
            'institution' => $member->member_type ?? 'Anggota',
            'purpose' => Setting::get('default_guest_purpose', 'Membaca'),
            'participants_count' => 1,
            'notes' => 'via Scan Barcode'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Selamat datang, ' . $member->name . '!',
            'member' => [
                'name' => $member->name,
                'code' => $member->member_code,
                'type' => $member->member_type,
                'photo' => $member->photo ? asset('storage/' . $member->photo) : null,
            ]
        ]);
    }

    /**
     * Export guest book entries to CSV spreadsheet.
     */
    public function export(Request $request)
    {
        $query = GuestBook::query()->latest('visit_date')->latest('visit_time');

        // Apply filters
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('institution', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%");
            });
        }

        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        if ($startDate && $endDate) {
            $query->whereBetween('visit_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('visit_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('visit_date', '<=', $endDate);
        }

        if ($request->has('selected_ids') && is_array($request->selected_ids)) {
            $query->whereIn('id', $request->selected_ids);
        }

        $activities = $query->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan-buku-tamu-' . date('Y-m-d') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($activities) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV Headers
            fputcsv($file, [
                'No', 
                'Hari dan Tanggal', 
                'Nama', 
                'Kunjungan Dari', 
                'Tujuan', 
                'Waktu Kunjungan', 
                'Jumlah Peserta',
                'Catatan'
            ], ';');

            foreach ($activities as $index => $activity) {
                fputcsv($file, [
                    $index + 1,
                    $activity->formatted_date,
                    $activity->name,
                    $activity->institution,
                    $activity->purpose,
                    $activity->formatted_time,
                    $activity->participants_count,
                    $activity->notes ?? '-'
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Store a newly created guest book entry in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'visit_date'         => 'required|date',
            'name'               => 'required|string|max:200',
            'institution'        => 'required|string|max:200', // Kunjungan dari
            'purpose'            => 'required|string|max:200',     // Tujuan
            'visit_time'         => 'required|string',             // Waktu kunjungan (e.g. HH:MM)
            'participants_count' => 'required|integer|min:1',       // Jumlah peserta
            'notes'              => 'nullable|string',
        ]);

        GuestBook::create($validated);

        return redirect()->route('guest-books.index')
            ->with('success', 'Kunjungan tamu berhasil dicatat.');
    }

    /**
     * Update the specified guest book entry in storage.
     */
    public function update(Request $request, GuestBook $guestBook)
    {
        $validated = $request->validate([
            'visit_date'         => 'required|date',
            'name'               => 'required|string|max:200',
            'institution'        => 'required|string|max:200',
            'purpose'            => 'required|string|max:200',
            'visit_time'         => 'required|string',
            'participants_count' => 'required|integer|min:1',
            'notes'              => 'nullable|string',
        ]);

        $guestBook->update($validated);

        return redirect()->route('guest-books.index')
            ->with('success', 'Kunjungan tamu berhasil diperbarui.');
    }

    /**
     * Remove the specified guest book entry from storage.
     */
    public function destroy(GuestBook $guestBook)
    {
        $guestBook->delete();
        return back()->with('success', 'Catatan kunjungan tamu berhasil dihapus.');
    }

    /**
     * Display public visitor self-service guest book form.
     */
    public function visitorForm()
    {
        return view('guest-books.visitor');
    }

    /**
     * Store visitor entry from public self-service form.
     */
    public function visitorStore(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:200',
            'institution'        => 'required|string|max:200',
            'purpose'            => 'required|string|max:200',
            'participants_count' => 'required|integer|min:1',
            'notes'              => 'nullable|string',
        ]);

        try {
            $validated['visit_date'] = Carbon::now()->toDateString();
            $validated['visit_time'] = Carbon::now()->format('H:i');

            GuestBook::create($validated);

            return redirect()->route('guest-books.visitor')
                ->with('success', 'Kunjungan Anda berhasil dicatat. Terima kasih!');
        } catch (\Exception $e) {
            return redirect()->route('guest-books.visitor')
                ->withInput()
                ->with('error', 'Terjadi kesalahan, kunjungan gagal dicatat. Silakan coba lagi.');
        }
    }
}
