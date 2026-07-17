<?php

namespace App\Http\Controllers;

use App\Models\GuestBook;
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

        $activities = $query->paginate(20)->withQueryString();

        return view('guest-books.index', compact('activities', 'startDate', 'endDate'));
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
     * Remove the specified guest book entry from storage.
     */
    public function destroy(GuestBook $guestBook)
    {
        $guestBook->delete();
        return back()->with('success', 'Catatan kunjungan tamu berhasil dihapus.');
    }
}
