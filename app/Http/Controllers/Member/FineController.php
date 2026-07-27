<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Circulation;
use Illuminate\Http\Request;

class FineController extends Controller
{
    public function __invoke(Request $request)
    {
        $memberId = auth()->user()->member_id;

        if (!$memberId) {
            abort(403, 'Anda tidak memiliki profil anggota yang terhubung.');
        }

        $fines = Circulation::with('bookItem.book')
            ->where('member_id', $memberId)
            ->where('fine_amount', '>', 0)
            ->where('fine_paid', false)
            ->orderByDesc('created_at')
            ->get();

        $totalFines = $fines->sum('fine_amount');

        return view('member.fines', compact('fines', 'totalFines'));
    }
}
