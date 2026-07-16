<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookItem;
use App\Models\Circulation;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        if (auth()->user()->hasRole('anggota')) {
            return redirect()->route('opac.index');
        }

        $stats = [
            'total_books'     => Book::count(),
            'total_members'   => Member::where('is_active', true)->count(),
            'active_loans'    => Circulation::where('status', 'Dipinjam')->count(),
            'overdue_loans'   => Circulation::overdue()->count(),
            'total_items'     => BookItem::count(),
            'available_items' => BookItem::where('status', 'Tersedia')->count(),
            'loans_today'     => Circulation::whereDate('loan_date', today())->count(),
            'returns_today'   => Circulation::whereDate('return_date', today())->count(),
        ];

        $recentLoans = Circulation::with(['member', 'bookItem.book'])
            ->latest()
            ->limit(8)
            ->get();

        $overdueLoans = Circulation::with(['member', 'bookItem.book'])
            ->overdue()
            ->orderBy('due_date')
            ->limit(10)
            ->get();

        return view('dashboard', compact('stats', 'recentLoans', 'overdueLoans'));
    }
}
