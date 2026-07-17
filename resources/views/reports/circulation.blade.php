@extends('layouts.app')

@section('title', 'Laporan Sirkulasi')
@section('page-title', 'Laporan Sirkulasi')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Laporan</a></li>
<li class="breadcrumb-item active">Sirkulasi</li>
@endsection

@section('content')
{{-- Print-only Header --}}
<div class="d-none print-header mb-4">
    <h2 class="text-center fw-bold text-uppercase" style="border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px;">
        Laporan Transaksi Sirkulasi Buku<br>
        <span style="font-size: 1.1rem; font-weight: normal; text-transform: none;">
            {{ config('app.name', 'Makarya') }}
        </span>
    </h2>
    <p class="text-center text-muted" style="margin-top: -10px;">
        Rentang Tanggal: <strong>{{ date('d/m/Y', strtotime($startDate)) }}</strong> s/d <strong>{{ date('d/m/Y', strtotime($endDate)) }}</strong>
    </p>
</div>

<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Laporan Sirkulasi Buku</h1>
        <p>Rentang tanggal: <strong>{{ date('d/m/Y', strtotime($startDate)) }}</strong> s/d <strong>{{ date('d/m/Y', strtotime($endDate)) }}</strong></p>
    </div>
    <div>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3 col-6">
                <label class="form-label" style="font-size:0.75rem;font-weight:600">MULAI TANGGAL</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label" style="font-size:0.75rem;font-weight:600">SAMPAI TANGGAL</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                <button type="button" onclick="window.print()" class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i> Cetak</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
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
                            <span class="badge bg-success">Kembali</span>
                            @elseif($c->is_overdue)
                            <span class="badge bg-danger">Terlambat</span>
                            @else
                            <span class="badge bg-warning text-dark">Dipinjam</span>
                            @endif
                        </td>
                        <td>
                            @if($c->fine_amount > 0)
                            <span class="text-danger fw-600">Rp {{ number_format($c->fine_amount, 0, ',', '.') }}</span>
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">Tidak ada data transaksi pada rentang tanggal ini</td></tr>
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
