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
        } elseif ($type === 'members') {
            $members = \App\Models\Member::latest()->get();

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="laporan-anggota-' . date('Y-m-d') . '.csv"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function() use ($members) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($file, [
                    'No', 'Kode Anggota', 'Nama Anggota', 'Jenis Kelamin', 'Tipe Anggota', 
                    'No. Telepon', 'Email', 'Alamat', 'Kota', 'Status'
                ], ';');

                foreach ($members as $index => $m) {
                    $gender = $m->gender == 'L' ? 'Laki-laki' : ($m->gender == 'P' ? 'Perempuan' : '-');
                    $status = $m->is_active ? 'Aktif' : 'Non-aktif';
                    
                    fputcsv($file, [
                        $index + 1,
                        $m->member_code,
                        $m->name,
                        $gender,
                        $m->member_type,
                        $m->phone ?? '-',
                        $m->email ?? '-',
                        $m->address ?? '-',
                        $m->city ?? '-',
                        $status
                    ], ';');
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } elseif ($type === 'collection') {
            $books = \App\Models\Book::with(['publisher', 'authors'])->withCount('items')->get();

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="laporan-koleksi-buku-' . date('Y-m-d') . '.csv"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function() use ($books) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($file, [
                    'No', 'Judul Buku', 'ISBN', 'Penerbit', 'Pengarang', 
                    'Tahun Terbit', 'Jumlah Eksemplar'
                ], ';');

                foreach ($books as $index => $b) {
                    $authors = $b->authors->pluck('name')->join(', ');
                    fputcsv($file, [
                        $index + 1,
                        $b->title,
                        $b->isbn ?? '-',
                        $b->publisher ? $b->publisher->name : '-',
                        $authors ?: '-',
                        $b->publication_year ?? '-',
                        $b->items_count
                    ], ';');
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } elseif ($type === 'overdue') {
            $overdueLoans = \App\Models\Circulation::with(['member', 'bookItem.book'])
                ->overdue()
                ->orderBy('due_date')
                ->get();

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="laporan-keterlambatan-' . date('Y-m-d') . '.csv"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function() use ($overdueLoans) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($file, [
                    'No', 'Kode TRX', 'Nama Anggota', 'Judul Buku', 'Tanggal Pinjam', 
                    'Jatuh Tempo', 'Terlambat (Hari)', 'Estimasi Denda'
                ], ';');

                foreach ($overdueLoans as $index => $c) {
                    $days = $c->due_date->diffInDays(now(), false);
                    if ($days < 0) $days = 0;
                    $fine = $days * 1000; // Asumsi denda 1000 per hari, bisa disesuaikan
                    
                    fputcsv($file, [
                        $index + 1,
                        $c->transaction_code,
                        $c->member->name,
                        $c->bookItem->book->title,
                        $c->loan_date->format('d/m/Y'),
                        $c->due_date->format('d/m/Y'),
                        $days,
                        $fine
                    ], ';');
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        abort(404);
    }
}
