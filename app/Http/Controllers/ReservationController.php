<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    // ==========================================
    // MEMBER METHODS (OPAC)
    // ==========================================

    public function memberIndex()
    {
        $memberId = Auth::user()->member->id ?? 0;
        $reservations = Reservation::with('book')
            ->where('member_id', $memberId)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('member.reservations.index', compact('reservations'));
    }

    public function store(Request $request, Book $book)
    {
        // Must have member profile
        if (!Auth::user()->member) {
            return back()->with('error', 'Anda harus melengkapi profil anggota terlebih dahulu sebelum dapat meminjam buku.');
        }

        $memberId = Auth::user()->member->id;

        // Check if book has available copies
        if ($book->available_copies <= 0) {
            return back()->with('error', 'Maaf, semua eksemplar buku ini sedang dipinjam atau tidak tersedia.');
        }

        // Check active reservations limit (e.g., max 2 pending)
        $activeReservations = Reservation::where('member_id', $memberId)
            ->whereIn('status', ['Menunggu', 'Siap'])
            ->count();

        if ($activeReservations >= 2) {
            return back()->with('error', 'Anda telah mencapai batas maksimal reservasi (2 buku). Silakan ambil buku yang sudah direservasi terlebih dahulu.');
        }

        // Check if already reserved this book
        $alreadyReserved = Reservation::where('member_id', $memberId)
            ->where('book_id', $book->id)
            ->whereIn('status', ['Menunggu', 'Siap'])
            ->exists();

        if ($alreadyReserved) {
            return back()->with('warning', 'Anda sudah melakukan reservasi untuk buku ini.');
        }

        // Create reservation
        Reservation::create([
            'member_id' => $memberId,
            'book_id' => $book->id,
            'reserve_date' => Carbon::today(),
            'expired_date' => Carbon::today()->addDays(2),
            'status' => 'Menunggu',
        ]);

        return back()->with('success', 'Buku berhasil direservasi! Silakan ambil di perpustakaan sebelum ' . Carbon::today()->addDays(2)->format('d M Y') . '.');
    }

    public function destroy(Reservation $reservation)
    {
        // Only owner can cancel
        if ($reservation->member_id !== (Auth::user()->member->id ?? 0)) {
            abort(403);
        }

        if ($reservation->status === 'Menunggu' || $reservation->status === 'Siap') {
            $reservation->update(['status' => 'Dibatalkan']);
            return back()->with('success', 'Reservasi berhasil dibatalkan.');
        }

        return back()->with('error', 'Reservasi ini tidak dapat dibatalkan.');
    }

    // ==========================================
    // ADMIN METHODS
    // ==========================================

    public function index()
    {
        $reservations = Reservation::with(['member', 'book'])
            ->orderByRaw("FIELD(status, 'Menunggu', 'Siap', 'Dibatalkan', 'Selesai')")
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('reservations.index', compact('reservations'));
    }

    public function approve(Reservation $reservation)
    {
        if ($reservation->status === 'Menunggu') {
            $reservation->update(['status' => 'Siap']);
            return back()->with('success', 'Status reservasi diubah menjadi Siap Diambil.');
        }
        
        if ($reservation->status === 'Siap') {
            return back()->with('info', 'Reservasi ini sudah berstatus Siap Diambil. Lakukan proses "Peminjaman" di menu Sirkulasi saat buku diambil.');
        }

        return back()->with('error', 'Status tidak valid.');
    }

    public function reject(Reservation $reservation)
    {
        if ($reservation->status === 'Menunggu' || $reservation->status === 'Siap') {
            $reservation->update(['status' => 'Dibatalkan']);
            return back()->with('success', 'Reservasi berhasil dibatalkan/ditolak.');
        }

        return back()->with('error', 'Status tidak valid.');
    }
}
