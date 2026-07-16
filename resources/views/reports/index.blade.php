@extends('layouts.app')

@section('title', 'Laporan Perpustakaan')
@section('page-title', 'Laporan')

@section('breadcrumb')
<li class="breadcrumb-item active">Laporan</li>
@endsection

@section('content')
<div class="page-header">
    <h1>Laporan & Statistik</h1>
    <p>Akses berbagai format laporan sirkulasi, koleksi buku, denda, dan data keanggotaan</p>
</div>

<div class="row g-3 mb-4">
    {{-- Summary card --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-light"><i class="bi bi-graph-up me-2"></i>Statistik Ringkas</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Judul Buku</span>
                    <strong class="text-dark">{{ number_format($stats['books_count']) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Eksemplar Fisik</span>
                    <strong class="text-dark">{{ number_format($stats['items_count']) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Anggota Terdaftar</span>
                    <strong class="text-dark">{{ number_format($stats['members_count']) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Transaksi Sirkulasi</span>
                    <strong class="text-dark">{{ number_format($stats['loans_count']) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Denda Terkumpul</span>
                    <strong class="text-success">Rp {{ number_format($stats['fines_total'], 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Report modules --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-light"><i class="bi bi-file-earmark-pdf me-2"></i>Modul Laporan</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100 d-flex flex-column">
                            <h6 class="fw-700 mb-1"><i class="bi bi-arrow-left-right text-warning me-2"></i>Laporan Sirkulasi</h6>
                            <p class="text-muted mb-3" style="font-size:0.78rem">Data peminjaman dan pengembalian buku berdasarkan filter rentang tanggal tertentu.</p>
                            <a href="{{ route('reports.circulation') }}" class="btn btn-sm btn-outline-primary mt-auto align-self-start">Buka Laporan</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100 d-flex flex-column">
                            <h6 class="fw-700 mb-1"><i class="bi bi-people text-primary me-2"></i>Laporan Anggota</h6>
                            <p class="text-muted mb-3" style="font-size:0.78rem">Daftar lengkap anggota perpustakaan yang terdaftar beserta status keaktifannya.</p>
                            <a href="{{ route('reports.members') }}" class="btn btn-sm btn-outline-primary mt-auto align-self-start">Buka Laporan</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100 d-flex flex-column">
                            <h6 class="fw-700 mb-1"><i class="bi bi-journal-album text-success me-2"></i>Laporan Koleksi Buku</h6>
                            <p class="text-muted mb-3" style="font-size:0.78rem">Detail katalog buku-buku perpustakaan beserta jumlah eksemplar fisik yang dimiliki.</p>
                            <a href="{{ route('reports.collection') }}" class="btn btn-sm btn-outline-primary mt-auto align-self-start">Buka Laporan</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100 d-flex flex-column">
                            <h6 class="fw-700 mb-1"><i class="bi bi-exclamation-octagon text-danger me-2"></i>Laporan Keterlambatan</h6>
                            <p class="text-muted mb-3" style="font-size:0.78rem">Daftar transaksi peminjaman aktif yang telah melewati batas jatuh tempo pengembalian.</p>
                            <a href="{{ route('reports.overdue') }}" class="btn btn-sm btn-outline-primary mt-auto align-self-start">Buka Laporan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
