<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\BookItem;
use App\Models\Circulation;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'books_count'   => Book::count(),
            'items_count'   => BookItem::count(),
            'members_count' => Member::count(),
            'loans_count'   => Circulation::count(),
            'overdue_count' => Circulation::overdue()->count(),
            'fines_total'   => Circulation::where('fine_paid', true)->sum('fine_amount'),
        ];
        return view('reports.index', compact('stats'));
    }

    public function circulation(Request $request)
    {
        $startDate = $request->start_date ? date('Y-m-d', strtotime($request->start_date)) : today()->startOfMonth()->toDateString();
        $endDate   = $request->end_date ? date('Y-m-d', strtotime($request->end_date)) : today()->endOfMonth()->toDateString();

        $circulations = Circulation::with(['member', 'bookItem.book'])
            ->whereBetween('loan_date', [$startDate, $endDate])
            ->latest()
            ->get();

        return view('reports.circulation', compact('circulations', 'startDate', 'endDate'));
    }

    public function members(Request $request)
    {
        $members = Member::latest()->get();
        return view('reports.members', compact('members'));
    }

    public function collection(Request $request)
    {
        $books = Book::with(['publisher', 'authors'])->withCount('items')->get();
        return view('reports.collection', compact('books'));
    }

    public function overdue()
    {
        $overdueLoans = Circulation::with(['member', 'bookItem.book'])
            ->overdue()
            ->orderBy('due_date')
            ->get();

        return view('reports.overdue', compact('overdueLoans'));
    }
}
