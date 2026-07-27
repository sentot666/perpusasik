<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Circulation;
use Illuminate\Http\Request;

class MyBookController extends Controller
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
            ->paginate(12);

        return view('member.my_books', compact('activeLoans'));
    }
}
