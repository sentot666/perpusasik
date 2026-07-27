<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookItem;
use App\Models\Circulation;
use App\Models\Member;
use App\Models\GuestBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        if (auth()->user()->hasRole('anggota')) {
            return redirect()->route('member.dashboard');
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

        // Chart Data: Visitors per day for last 14 days
        $startDate = Carbon::today()->subDays(13);
        $visitsData = GuestBook::where('visit_date', '>=', $startDate)
            ->select('visit_date', DB::raw('SUM(participants_count) as total'))
            ->groupBy('visit_date')
            ->orderBy('visit_date')
            ->get()
            ->keyBy(function($item) {
                return Carbon::parse($item->visit_date)->format('Y-m-d');
            });

        $chartLabels = [];
        $chartData = [];
        
        for ($i = 0; $i < 14; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateStr = $date->format('Y-m-d');
            
            $chartLabels[] = $date->format('d M');
            $chartData[] = isset($visitsData[$dateStr]) ? (int) $visitsData[$dateStr]->total : 0;
        }

        return view('dashboard', compact('stats', 'recentLoans', 'overdueLoans', 'chartLabels', 'chartData'));
    }
}
