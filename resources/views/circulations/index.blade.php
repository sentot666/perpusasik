@extends('layouts.app')

@section('title', 'Transaksi Sirkulasi')
@section('page-title', 'Transaksi Sirkulasi')

@section('breadcrumb')
<li class="breadcrumb-item active">Sirkulasi</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Daftar Transaksi Sirkulasi</h1>
        <p>Pantau semua transaksi peminjaman, pengembalian, dan denda buku</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('circulations.loan') }}" class="btn btn-primary">
            <i class="bi bi-box-arrow-right me-1"></i>Peminjaman Baru
        </a>
        <a href="{{ route('circulations.return') }}" class="btn btn-success">
            <i class="bi bi-box-arrow-in-left me-1"></i>Pengembalian Baru
        </a>
    </div>
</div>

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card bg-warning text-dark border-0 shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="fs-4 fw-800">{{ number_format($stats['active']) }}</div>
                    <small class="text-uppercase" style="font-size:0.7rem;letter-spacing:0.5px;font-weight:600">Peminjaman Aktif</small>
                </div>
                <i class="bi bi-arrow-left-right fs-1 opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white border-0 shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="fs-4 fw-800">{{ number_format($stats['overdue']) }}</div>
                    <small class="text-uppercase" style="font-size:0.7rem;letter-spacing:0.5px;font-weight:600">Terlambat Kembali</small>
                </div>
                <i class="bi bi-exclamation-triangle-fill fs-1 opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white border-0 shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="fs-4 fw-800">{{ number_format($stats['returned']) }}</div>
                    <small class="text-uppercase" style="font-size:0.7rem;letter-spacing:0.5px;font-weight:600">Kembali Hari Ini</small>
                </div>
                <i class="bi bi-check-circle-fill fs-1 opacity-25"></i>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center" id="circFilterForm">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari kode transaksi, nama atau kode anggota..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </div>
            <div class="col-md-2 form-check form-check-inline m-0 ps-4">
                <input class="form-check-input" type="checkbox" name="overdue" id="overdueCheckbox" value="1" {{ request('overdue') ? 'checked' : '' }} onchange="this.form.submit()">
                <label class="form-check-label fw-500" for="overdueCheckbox" style="font-size:0.82rem">Hanya Terlambat</label>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                <a href="{{ route('circulations.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i> Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Kode TRX</th>
                        <th>Anggota</th>
                        <th>Buku (Barcode)</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($circulations as $c)
                    <tr>
                        <td><code class="fw-600" style="font-size:0.85rem">{{ $c->transaction_code }}</code></td>
                        <td>
                            <div class="fw-500">{{ $c->member->name }}</div>
                            <div class="text-muted" style="font-size:0.72rem">{{ $c->member->member_code }}</div>
                        </td>
                        <td>
                            <div class="text-truncate" style="max-width:180px" title="{{ $c->bookItem->book->title }}">{{ $c->bookItem->book->title }}</div>
                            <code style="font-size:0.72rem">{{ $c->bookItem->barcode }}</code>
                        </td>
                        <td>{{ $c->loan_date->format('d/m/Y') }}</td>
                        <td>
                            <span class="{{ $c->is_overdue ? 'text-danger fw-600' : '' }}">
                                {{ $c->due_date->format('d/m/Y') }}
                            </span>
                        </td>
                        <td>{{ $c->return_date ? $c->return_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($c->status === 'Dikembalikan')
                            <span class="badge bg-success">Dikembalikan</span>
                            @elseif($c->is_overdue)
                            <span class="badge bg-danger">Terlambat {{ $c->days_overdue }} hari</span>
                            @else
                            <span class="badge bg-warning text-dark">Dipinjam</span>
                            @endif
                        </td>
                        <td>
                            @if($c->fine_amount > 0)
                            <div class="fw-600 text-danger">Rp {{ number_format($c->fine_amount, 0, ',', '.') }}</div>
                            <span class="badge bg-{{ $c->fine_paid ? 'success' : 'warning text-dark' }}">{{ $c->fine_paid ? 'Lunas' : 'Belum Lunas' }}</span>
                            @elseif($c->is_overdue)
                            <div class="fw-600 text-danger" title="Estimasi denda saat ini">Rp {{ number_format($c->calculated_fine, 0, ',', '.') }}</div>
                            <span class="badge bg-light text-muted" style="font-size:0.65rem">Belum kembali</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('circulations.show', $c) }}" class="btn btn-outline-primary" title="Detail" data-bs-toggle="tooltip">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-5 text-muted">Belum ada data transaksi sirkulasi</td></tr>
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
