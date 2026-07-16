@extends('layouts.app')

@section('title', 'Riwayat Sirkulasi - ' . $member->name)
@section('page-title', 'Riwayat Sirkulasi')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('members.index') }}">Daftar Anggota</a></li>
<li class="breadcrumb-item"><a href="{{ route('members.show', $member) }}">{{ $member->name }}</a></li>
<li class="breadcrumb-item active">Riwayat Sirkulasi</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Riwayat Sirkulasi</h1>
        <p>Seluruh riwayat peminjaman dan pengembalian buku oleh <strong>{{ $member->name }}</strong></p>
    </div>
    <div>
        <a href="{{ route('members.show', $member) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Detail
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Transaksi</th>
                        <th>Barcode</th>
                        <th>Judul Buku</th>
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
                        <td class="text-muted">{{ $circulations->firstItem() + $loop->index }}</td>
                        <td><code class="fw-600">{{ $c->transaction_code }}</code></td>
                        <td><code>{{ $c->bookItem->barcode }}</code></td>
                        <td class="fw-500">{{ $c->bookItem->book->title }}</td>
                        <td>{{ $c->loan_date->format('d/m/Y') }}</td>
                        <td>{{ $c->due_date->format('d/m/Y') }}</td>
                        <td>{{ $c->return_date ? $c->return_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($c->status === 'Dikembalikan')
                            <span class="badge bg-success">Selesai</span>
                            @elseif($c->is_overdue)
                            <span class="badge bg-danger">Terlambat</span>
                            @else
                            <span class="badge bg-warning text-dark">Dipinjam</span>
                            @endif
                        </td>
                        <td>
                            @if($c->fine_amount > 0)
                            <div class="fw-600 text-danger">Rp {{ number_format($c->fine_amount, 0, ',', '.') }}</div>
                            <div class="text-muted" style="font-size:0.72rem">{{ $c->fine_paid ? 'Lunas' : 'Belum Lunas' }}</div>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-5 text-muted">Belum ada riwayat transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($circulations->hasPages())
    <div class="card-footer bg-white border-top py-2">
        {{ $circulations->links() }}
    </div>
    @endif
</div>
@endsection
