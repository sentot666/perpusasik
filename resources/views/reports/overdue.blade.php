@extends('layouts.app')

@section('title', 'Laporan Keterlambatan')
@section('page-title', 'Laporan Keterlambatan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Laporan</a></li>
<li class="breadcrumb-item active">Keterlambatan</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Laporan Keterlambatan Pengembalian</h1>
        <p>Seluruh transaksi peminjaman aktif yang sudah melewati jatuh tempo</p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary"><i class="bi bi-printer"></i> Cetak Laporan</button>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
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
                        <td class="fw-600">{{ $loan->member->name }} ({{ $loan->member->member_code }})</td>
                        <td>{{ $loan->bookItem->book->title }} (<code>{{ $loan->bookItem->barcode }}</code>)</td>
                        <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                        <td class="text-danger fw-600">{{ $loan->due_date->format('d/m/Y') }}</td>
                        <td><span class="badge bg-danger">{{ $loan->days_overdue }} Hari</span></td>
                        <td class="fw-600 text-danger">Rp {{ number_format($loan->calculated_fine, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('circulations.show', $loan) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-success"><i class="bi bi-check-circle-fill me-1"></i> Tidak ada peminjaman terlambat saat ini! Semua buku kembali tepat waktu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
