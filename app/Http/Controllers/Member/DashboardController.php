<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Circulation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $memberId = auth()->user()->member_id;

        if (!$memberId) {
            abort(403, 'Anda tidak memiliki profil anggota yang terhubung.');
        }

        $activeLoans = Circulation::with(['bookItem.book'])
            ->where('member_id', $memberId)
            ->where('status', 'Dipinjam')
            ->orderBy('due_date')
            ->get();

        $overdueLoans = Circulation::where('member_id', $memberId)
            ->where('status', 'Dipinjam')
            ->where('due_date', '<', today())
            ->get();

        $totalLoans = Circulation::where('member_id', $memberId)->count();

        $totalFines = Circulation::where('member_id', $memberId)
            ->where('fine_paid', false)
            ->sum('fine_amount');

        return view('member.dashboard', compact('activeLoans', 'overdueLoans', 'totalLoans', 'totalFines'));
    }
}
