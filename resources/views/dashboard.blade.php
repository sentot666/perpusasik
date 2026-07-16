@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1>Dashboard</h1>
    <p>Selamat datang, <strong>{{ auth()->user()->name }}</strong> — {{ now()->translatedFormat('l, d F Y') }}</p>
</div>

{{-- ── Stat Cards ─────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#dbeafe;color:#2563eb">
                    <i class="bi bi-journals"></i>
                </div>
                <div>
                    <div class="stat-value text-primary">{{ number_format($stats['total_books']) }}</div>
                    <div class="stat-label">Total Judul Buku</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#dcfce7;color:#16a34a">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="stat-value text-success">{{ number_format($stats['total_members']) }}</div>
                    <div class="stat-label">Total Anggota</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fef9c3;color:#ca8a04">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
                <div>
                    <div class="stat-value text-warning">{{ number_format($stats['active_loans']) }}</div>
                    <div class="stat-label">Sedang Dipinjam</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fee2e2;color:#dc2626">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <div class="stat-value text-danger">{{ number_format($stats['overdue_loans']) }}</div>
                    <div class="stat-label">Terlambat</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── 2nd Row ─────────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#f3e8ff;color:#9333ea">
                    <i class="bi bi-collection"></i>
                </div>
                <div>
                    <div class="stat-value" style="color:#9333ea">{{ number_format($stats['total_items']) }}</div>
                    <div class="stat-label">Total Eksemplar</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#e0f2fe;color:#0284c7">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <div class="stat-value" style="color:#0284c7">{{ number_format($stats['available_items']) }}</div>
                    <div class="stat-label">Eksemplar Tersedia</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fce7f3;color:#db2777">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div>
                    <div class="stat-value" style="color:#db2777">{{ number_format($stats['loans_today']) }}</div>
                    <div class="stat-label">Pinjam Hari Ini</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#ecfdf5;color:#059669">
                    <i class="bi bi-calendar-x"></i>
                </div>
                <div>
                    <div class="stat-value" style="color:#059669">{{ number_format($stats['returns_today']) }}</div>
                    <div class="stat-label">Kembali Hari Ini</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Tables Row ───────────────────────────────────────────────────────────── --}}
<div class="row g-3">

    {{-- Recent Loans --}}
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-arrow-left-right me-2 text-warning"></i>Peminjaman Terkini</span>
                <a href="{{ route('circulations.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Anggota</th>
                                <th>Judul Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLoans as $loan)
                            <tr>
                                <td>
                                    <div class="fw-500">{{ $loan->member->name }}</div>
                                    <div class="text-muted" style="font-size:0.72rem">{{ $loan->member->member_code }}</div>
                                </td>
                                <td class="text-truncate" style="max-width:160px" title="{{ $loan->bookItem->book->title }}">
                                    {{ $loan->bookItem->book->title }}
                                </td>
                                <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="{{ $loan->is_overdue ? 'text-danger fw-600' : '' }}">
                                        {{ $loan->due_date->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>
                                    @if($loan->is_overdue)
                                        <span class="badge bg-danger">Terlambat {{ $loan->days_overdue }}h</span>
                                    @elseif($loan->status === 'Dikembalikan')
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Dipinjam</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada transaksi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions + Overdue --}}
    <div class="col-lg-5">
        {{-- Quick Actions --}}
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-lightning-charge-fill me-2 text-warning"></i>Aksi Cepat</div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('circulations.loan') }}" class="btn btn-primary">
                        <i class="bi bi-box-arrow-right me-2"></i>Proses Peminjaman
                    </a>
                    <a href="{{ route('circulations.return') }}" class="btn btn-success">
                        <i class="bi bi-box-arrow-in-left me-2"></i>Proses Pengembalian
                    </a>
                    <a href="{{ route('members.create') }}" class="btn btn-outline-primary">
                        <i class="bi bi-person-plus me-2"></i>Tambah Anggota
                    </a>
                    <a href="{{ route('books.create') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Buku
                    </a>
                </div>
            </div>
        </div>

        {{-- Overdue List --}}
        @if($overdueLoans->count() > 0)
        <div class="card border-danger" style="border-color:#fee2e2!important">
            <div class="card-header bg-danger text-white">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>Terlambat ({{ $overdueLoans->count() }})
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($overdueLoans->take(5) as $loan)
                    <li class="list-group-item py-2 px-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-500 text-truncate" style="max-width:170px;font-size:0.82rem">{{ $loan->member->name }}</div>
                                <div class="text-muted" style="font-size:0.72rem">{{ $loan->bookItem->book->title }}</div>
                            </div>
                            <span class="badge bg-danger ms-2">{{ $loan->days_overdue }}h</span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
