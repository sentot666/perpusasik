@extends('layouts.app')

@section('title', 'Laporan Keterlambatan')
@section('page-title', 'Laporan Keterlambatan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Laporan</a></li>
<li class="breadcrumb-item active">Keterlambatan</li>
@endsection

@section('content')
<div class="page-header justify-between items-start flex">
    <div>
        <h1>Laporan Keterlambatan Pengembalian</h1>
        <p>Seluruh transaksi peminjaman aktif yang sudah melewati jatuh tempo</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('reports.export', ['type' => 'overdue'] + request()->all()) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition-colors gap-2 py-2 px-6 shadow-sm">
            <i class="bi bi-file-earmark-excel text-base"></i> Export Spreadsheet
        </a>
        <button onclick="window.print()" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-slate-800 text-white hover:bg-slate-900 transition-colors gap-2 py-2 px-6 shadow-sm"><i class="bi bi-printer"></i> Cetak Laporan</button>
        <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors gap-2 py-2 px-6 shadow-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-0">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode TRX</th>
                        <th>Anggota</th>
                        <th>Judul Buku (Barcode)</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Keterlambatan</th>
                        <th>Estimasi Denda</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($overdueLoans as $loan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><code>{{ $loan->transaction_code }}</code></td>
                        <td class="font-semibold">{{ $loan->member->name }} ({{ $loan->member->member_code }})</td>
                        <td>{{ $loan->bookItem->book->title }} (<code>{{ $loan->bookItem->barcode }}</code>)</td>
                        <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                        <td class="text-red-600 font-semibold">{{ $loan->due_date->format('d/m/Y') }}</td>
                        <td><span class="inline-flex py-1 text-xs font-medium rounded-md bg-red-100 text-red-700 px-2">{{ $loan->days_overdue }} Hari</span></td>
                        <td class="text-red-600 font-semibold">Rp {{ number_format($loan->calculated_fine, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('circulations.show', $loan) }}" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg text-indigo-600 border border-slate-200 border-indigo-600 hover:bg-indigo-50 transition-colors px-4"><i class="bi bi-eye"></i> Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-emerald-600 text-center py-6"><i class="bi bi-check-circle-fill mr-1"></i> Tidak ada peminjaman terlambat saat ini! Semua buku kembali tepat waktu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
