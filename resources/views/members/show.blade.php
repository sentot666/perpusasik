@extends('layouts.app')

@section('title', 'Detail Anggota: ' . $member->name)
@section('page-title', 'Detail Anggota')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('members.index') }}">Daftar Anggota</a></li>
<li class="breadcrumb-item active">{{ $member->name }}</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Detail Anggota</h1>
        <p>Profil dan data transaksi peminjaman anggota</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('members.print-card', $member) }}" target="_blank" class="btn btn-warning text-dark fw-500">
            <i class="bi bi-card-image me-1"></i>Cetak Kartu
        </a>
        <a href="{{ route('members.edit', $member) }}" class="btn btn-outline-secondary">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- Left: Card Profil --}}
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body text-center">
                <div class="mx-auto mb-3" style="width:120px;height:120px;border-radius:50%;background:#f1f5f9;border:3px solid #e2e8f0;overflow:hidden;display:flex;align-items:center;justify-content:center;color:#64748b;font-size:3rem;font-weight:bold">
                    @if($member->photo)
                        <img src="{{ asset('storage/' . $member->photo) }}" style="width:100%;height:100%;object-fit:cover">
                    @else
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    @endif
                </div>
                <h4 class="fw-700 text-dark mb-1">{{ $member->name }}</h4>
                <div class="badge bg-light text-dark border mb-3">{{ $member->member_type }}</div>

                <div class="d-flex justify-content-around text-center border-top border-bottom py-2 my-3">
                    <div>
                        <div class="fw-700 text-primary fs-5">{{ $activeLoans->count() }}</div>
                        <div class="text-muted" style="font-size:0.7rem;text-transform:uppercase">Pinjam Aktif</div>
                    </div>
                    <div>
                        <div class="fw-700 text-muted fs-5">{{ $member->circulations()->count() }}</div>
                        <div class="text-muted" style="font-size:0.7rem;text-transform:uppercase">Total Pinjam</div>
                    </div>
                </div>

                <div class="text-start">
                    <div class="mb-2" style="font-size:0.82rem">
                        <span class="text-muted d-block" style="font-size:0.72rem">KODE ANGGOTA</span>
                        <code class="fw-600 fs-6">{{ $member->member_code }}</code>
                    </div>
                    <div class="mb-2" style="font-size:0.82rem">
                        <span class="text-muted d-block" style="font-size:0.72rem">STATUS</span>
                        <span class="badge bg-{{ $member->status_badge_class }}">{{ $member->status_label }}</span>
                    </div>
                    <div class="mb-2" style="font-size:0.82rem">
                        <span class="text-muted d-block" style="font-size:0.72rem">MASA BERLAKU</span>
                        <span class="{{ $member->is_expired ? 'text-danger fw-600' : '' }}">
                            {{ $member->expired_date ? $member->expired_date->format('d F Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Detail & Transaksi --}}
    <div class="col-lg-8">
        {{-- Profile details tab --}}
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-person-fill me-2"></i>Informasi Lengkap</div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6 col-md-4">
                        <div class="text-muted" style="font-size:0.72rem">EMAIL</div>
                        <div class="fw-500">{{ $member->email ?? '-' }}</div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="text-muted" style="font-size:0.72rem">TELEPON</div>
                        <div class="fw-500">{{ $member->phone ?? '-' }}</div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="text-muted" style="font-size:0.72rem">IDENTITAS</div>
                        <div class="fw-500">{{ $member->identity_type ?? 'KTP' }}: {{ $member->identity_number ?? '-' }}</div>
                    </div>
                    <div class="col-6 col-md-4 mt-3">
                        <div class="text-muted" style="font-size:0.72rem">JENIS KELAMIN</div>
                        <div class="fw-500">{{ $member->gender == 'L' ? 'Laki-laki' : ($member->gender == 'P' ? 'Perempuan' : '-') }}</div>
                    </div>
                    <div class="col-6 col-md-4 mt-3">
                        <div class="text-muted" style="font-size:0.72rem">KOTA & PROVINSI</div>
                        <div class="fw-500">{{ $member->city ?? '-' }}, {{ $member->province ?? '-' }}</div>
                    </div>
                    <div class="col-6 col-md-4 mt-3">
                        <div class="text-muted" style="font-size:0.72rem">TANGGAL DAFTAR</div>
                        <div class="fw-500">{{ $member->register_date ? $member->register_date->format('d/m/Y') : '-' }}</div>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="text-muted" style="font-size:0.72rem">ALAMAT</div>
                        <div class="fw-500">{{ $member->address ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Active Loans --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-arrow-left-right me-2 text-warning"></i>Buku Sedang Dipinjam</span>
                <a href="{{ route('members.history', $member) }}" class="btn btn-sm btn-outline-secondary">Riwayat Lengkap</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Barcode</th>
                                <th>Judul Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Jatuh Tempo</th>
                                <th>Denda (Est)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeLoans as $loan)
                            <tr>
                                <td><code>{{ $loan->bookItem->barcode }}</code></td>
                                <td class="fw-500">{{ $loan->bookItem->book->title }}</td>
                                <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="{{ $loan->is_overdue ? 'text-danger fw-600' : '' }}">
                                        {{ $loan->due_date->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>
                                    @if($loan->is_overdue)
                                    <span class="text-danger fw-600">Rp {{ number_format($loan->calculated_fine, 0, ',', '.') }}</span>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <form method="POST" action="{{ route('circulations.renew', $loan) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-secondary" title="Perpanjang" data-bs-toggle="tooltip">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('circulations.show', $loan) }}" class="btn btn-outline-primary" title="Detail Transaksi" data-bs-toggle="tooltip">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada peminjaman aktif</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
