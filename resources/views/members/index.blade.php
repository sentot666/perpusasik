@extends('layouts.app')

@section('title', 'Data Anggota')
@section('page-title', 'Data Anggota')

@section('breadcrumb')
<li class="breadcrumb-item active">Data Anggota</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Daftar Anggota</h1>
        <p>Kelola data anggota perpustakaan digital</p>
    </div>
    <div>
        <a href="{{ route('members.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i>Tambah Anggota
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center" id="memberFilterForm">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, kode, email, no. identitas..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="member_type" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Tipe</option>
                    @foreach($memberTypes as $type)
                    <option value="{{ $type }}" {{ request('member_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                <a href="{{ route('members.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i> Reset</a>
            </div>
            <div class="col-auto ms-auto text-muted" style="font-size:0.8rem">
                {{ $members->total() }} anggota terdaftar
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
                        <th>#</th>
                        <th>Kode Anggota</th>
                        <th>Nama</th>
                        <th>Kontak</th>
                        <th>Tipe</th>
                        <th>Tgl Daftar</th>
                        <th>Masa Berlaku</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    <tr>
                        <td class="text-muted">{{ $members->firstItem() + $loop->index }}</td>
                        <td><code class="fw-600" style="font-size:0.85rem">{{ $member->member_code }}</code></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:30px;height:30px;border-radius:50%;background:#e2e8f0;overflow:hidden;display:flex;align-items:center;justify-content:center;color:#718096;font-weight:bold;font-size:0.75rem">
                                    @if($member->photo)
                                        <img src="{{ asset('storage/' . $member->photo) }}" style="width:100%;height:100%;object-fit:cover">
                                    @else
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div class="fw-500">
                                    <a href="{{ route('members.show', $member) }}" class="text-decoration-none text-dark">
                                        {{ $member->name }}
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>{{ $member->phone ?? '-' }}</div>
                            <div class="text-muted" style="font-size:0.72rem">{{ $member->email ?? '-' }}</div>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $member->member_type }}</span></td>
                        <td>{{ $member->register_date ? $member->register_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            <span class="{{ $member->is_expired ? 'text-danger fw-600' : '' }}">
                                {{ $member->expired_date ? $member->expired_date->format('d/m/Y') : '-' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $member->status_badge_class }}">{{ $member->status_label }}</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('members.show', $member) }}" class="btn btn-outline-primary" title="Detail" data-bs-toggle="tooltip">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('members.edit', $member) }}" class="btn btn-outline-secondary" title="Edit" data-bs-toggle="tooltip">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('members.print-card', $member) }}" target="_blank" class="btn btn-outline-warning text-dark" title="Cetak Kartu" data-bs-toggle="tooltip">
                                    <i class="bi bi-card-image"></i>
                                </a>
                                <form method="POST" action="{{ route('members.destroy', $member) }}" onsubmit="return confirm('Hapus anggota ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus" data-bs-toggle="tooltip">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-2 opacity-25"></i>
                            Belum ada data anggota. <a href="{{ route('members.create') }}">Tambah anggota pertama</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($members->hasPages())
    <div class="card-footer bg-white border-top py-2">
        {{ $members->links() }}
    </div>
    @endif
</div>
@endsection
