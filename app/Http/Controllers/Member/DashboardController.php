<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Circulation;
use App\Models\Reservation;
use App\Models\Wishlist;
use App\Models\Book;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $member = $user->member;
        $memberId = $user->member_id;

        if (!$memberId || !$member) {
            abort(403, 'Profil anggota belum terhubung ke akun Anda.');
        }

        // Active loans with book details
        $activeLoans = Circulation::with(['bookItem.book.authors'])
            ->where('member_id', $memberId)
            ->where('status', 'Dipinjam')
            ->orderBy('due_date', 'asc')
            ->get();

        // Overdue loans count
        $overdueLoans = Circulation::where('member_id', $memberId)
            ->where('status', 'Dipinjam')
            ->where('due_date', '<', today())
            ->get();

        // Total books borrowed all time
        $totalLoans = Circulation::where('member_id', $memberId)->count();

        // Total books successfully read & returned (reading achievements)
        $totalReturned = Circulation::where('member_id', $memberId)
            ->where('status', 'Dikembalikan')
            ->count();

        // Unpaid fines
        $totalFines = Circulation::where('member_id', $memberId)
            ->where('fine_paid', false)
            ->sum('fine_amount');

        // Active reservations (orders)
        $activeReservations = Reservation::with(['book.authors'])
            ->where('member_id', $memberId)
            ->whereIn('status', ['Menunggu', 'Siap'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Wishlist count
        $wishlistCount = Wishlist::where('user_id', $user->id)->count();

        // Fun Recommended Books for kids exploration (latest active books)
        $recommendedBooks = Book::with('authors')
            ->where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        return view('member.dashboard', compact(
            'member',
            'activeLoans',
            'overdueLoans',
            'totalLoans',
            'totalReturned',
            'totalFines',
            'activeReservations',
            'wishlistCount',
            'recommendedBooks'
        ));
    }
}
