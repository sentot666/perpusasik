@extends('layouts.app')

@section('title', 'Laporan Sirkulasi')
@section('page-title', 'Laporan Sirkulasi')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Laporan</a></li>
<li class="breadcrumb-item active">Sirkulasi</li>
@endsection

@section('content')
{{-- Print-only Header --}}
<div class="hidden print-header mb-6">
    <h2 class="uppercase text-center font-bold" style="border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px;">
        Laporan Transaksi Sirkulasi Buku<br>
        <span style="font-size: 1.1rem; font-weight: normal; text-transform: none;">
            {{ config('app.name', 'Makarya') }}
        </span>
    </h2>
    <p class="text-center text-slate-500" style="margin-top: -10px;">
        Rentang Tanggal: <strong>{{ date('d/m/Y', strtotime($startDate)) }}</strong> s/d <strong>{{ date('d/m/Y', strtotime($endDate)) }}</strong>
    </p>
</div>

<div class="page-header justify-between items-start flex">
    <div>
        <h1>Laporan Sirkulasi Buku</h1>
        <p>Rentang tanggal: <strong>{{ date('d/m/Y', strtotime($startDate)) }}</strong> s/d <strong>{{ date('d/m/Y', strtotime($endDate)) }}</strong></p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('reports.export', ['type' => 'circulation'] + request()->all()) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition-colors gap-2 py-2 px-6 shadow-sm">
            <i class="bi bi-file-earmark-excel text-base"></i> Export Spreadsheet
        </a>
        <button type="button" onclick="window.print()" class="gap-1 inline-flex items-center justify-center text-sm font-medium rounded-lg bg-slate-800 text-white hover:bg-slate-900 transition-colors flex gap-2 py-2 px-6 shadow-sm">
            <i class="bi bi-printer text-base"></i> Cetak Laporan
        </button>
        <a href="{{ route('reports.index') }}" class="gap-1 inline-flex items-center justify-center text-sm font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors flex gap-2 py-2 px-6 shadow-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
    <div class="px-8 py-2">
        <form method="GET" class="items-end flex flex-wrap -mx-2">
            <div class="w-full md:w-1/4 w-1/2 px-4">
                <label class="block text-sm font-medium text-slate-700 mb-1" style="font-size:0.75rem;font-weight:600">MULAI TANGGAL</label>
                <input type="date" name="start_date" class="w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none px-4" value="{{ $startDate }}">
            </div>
            <div class="w-full md:w-1/4 w-1/2 px-4">
                <label class="block text-sm font-medium text-slate-700 mb-1" style="font-size:0.75rem;font-weight:600">SAMPAI TANGGAL</label>
                <input type="date" name="end_date" class="w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none px-4" value="{{ $endDate }}">
            </div>
            <div class="w-auto px-4">
                <button type="submit" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 transition-colors text-white px-4"><i class="bi bi-funnel"></i> Filter</button>
            </div>
        </form>
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
                        <th>Buku (Barcode)</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($circulations as $c)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><code>{{ $c->transaction_code }}</code></td>
                        <td>{{ $c->member->name }} ({{ $c->member->member_code }})</td>
                        <td>{{ $c->bookItem->book->title }} ({{ $c->bookItem->barcode }})</td>
                        <td>{{ $c->loan_date->format('d/m/Y') }}</td>
                        <td>{{ $c->due_date->format('d/m/Y') }}</td>
                        <td>{{ $c->return_date ? $c->return_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($c->status === 'Dikembalikan')
                            <span class="inline-flex py-1 text-xs font-medium rounded-md bg-emerald-100 text-emerald-700 px-2">Kembali</span>
                            @elseif($c->is_overdue)
                            <span class="inline-flex py-1 text-xs font-medium rounded-md bg-red-100 text-red-700 px-2">Terlambat</span>
                            @else
                            <span class="inline-flex py-1 text-xs font-medium rounded-md bg-amber-100 text-amber-800 px-2">Dipinjam</span>
                            @endif
                        </td>
                        <td>
                            @if($c->fine_amount > 0)
                            <span class="text-red-600 font-semibold">Rp {{ number_format($c->fine_amount, 0, ',', '.') }}</span>
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center text-slate-500 py-6">Tidak ada data transaksi pada rentang tanggal ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    /* Hide layout elements */
    #sidebar, .topbar, .card:has(form), .btn, nav[aria-label="breadcrumb"], footer, .modal, .pagination, .page-header {
        display: none !important;
    }
    /* Show print header */
    .print-header {
        display: block !important;
    }
    .main-wrapper {
        margin-left: 0 !important;
        padding: 0 !important;
    }
    .content-area {
        padding: 0 !important;
        margin: 0 !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    /* Format table for print */
    .table {
        width: 100% !important;
        border-collapse: collapse !important;
        font-size: 0.85rem !important;
    }
    .table th, .table td {
        border: 1px solid #000 !important;
        padding: 6px 8px !important;
        color: #000 !important;
    }
    .table-light {
        background-color: #f8f9fa !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .badge {
        border: none !important;
        background: transparent !important;
        color: #000 !important;
        padding: 0 !important;
        font-size: 0.85rem !important;
    }
    code {
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
        color: #000 !important;
    }
}
</style>
@endpush
