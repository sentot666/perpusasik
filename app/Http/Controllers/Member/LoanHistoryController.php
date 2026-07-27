<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Circulation;
use Illuminate\Http\Request;

class LoanHistoryController extends Controller
{
    public function __invoke(Request $request)
    {
        $memberId = auth()->user()->member_id;

        if (!$memberId) {
            abort(403, 'Anda tidak memiliki profil anggota yang terhubung.');
        }

        $query = Circulation::with(['bookItem.book'])
            ->where('member_id', $memberId);

        if ($search = $request->q) {
            $query->whereHas('bookItem.book', fn($q) => $q->where('title', 'like', "%{$search}%"));
        }

        $loans = $query->latest()->paginate(15)->withQueryString();

        return view('member.loans', compact('loans'));
    }
}
