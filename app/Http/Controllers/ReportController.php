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

    /**
     * Export report data to CSV spreadsheet.
     */
    public function export($type, Request $request)
    {
        if ($type === 'circulation') {
            $startDate = $request->start_date ? date('Y-m-d', strtotime($request->start_date)) : today()->startOfMonth()->toDateString();
            $endDate   = $request->end_date ? date('Y-m-d', strtotime($request->end_date)) : today()->endOfMonth()->toDateString();

            $circulations = Circulation::with(['member', 'bookItem.book'])
                ->whereBetween('loan_date', [$startDate, $endDate])
                ->latest()
                ->get();

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="laporan-sirkulasi-' . $startDate . '-to-' . $endDate . '.csv"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function() use ($circulations) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($file, [
                    'No', 'Kode TRX', 'Nama Anggota', 'Kode Anggota', 'Judul Buku', 'Barcode Buku', 
                    'Tanggal Pinjam', 'Jatuh Tempo', 'Tanggal Kembali', 'Status', 'Jumlah Denda'
                ], ';');

                foreach ($circulations as $index => $c) {
                    fputcsv($file, [
                        $index + 1,
                        $c->transaction_code,
                        $c->member->name,
                        $c->member->member_code,
                        $c->bookItem->book->title,
                        $c->bookItem->barcode,
                        $c->loan_date->format('d/m/Y'),
                        $c->due_date->format('d/m/Y'),
                        $c->return_date ? $c->return_date->format('d/m/Y') : '-',
                        $c->status,
                        (float) $c->fine_amount
                    ], ';');
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        abort(404);
    }
}
