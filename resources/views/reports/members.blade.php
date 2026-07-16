@extends('layouts.app')

@section('title', 'Laporan Anggota')
@section('page-title', 'Laporan Anggota')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Laporan</a></li>
<li class="breadcrumb-item active">Anggota</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Laporan Data Anggota</h1>
        <p>Seluruh daftar anggota perpustakaan yang terdaftar</p>
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
                        <th>Kode Anggota</th>
                        <th>Nama Anggota</th>
                        <th>Jenis Kelamin</th>
                        <th>Tipe Anggota</th>
                        <th>No. Telepon / Email</th>
                        <th>Kota</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $m)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><code>{{ $m->member_code }}</code></td>
                        <td class="fw-600">{{ $m->name }}</td>
                        <td>{{ $m->gender == 'L' ? 'Laki-laki' : ($m->gender == 'P' ? 'Perempuan' : '-') }}</td>
                        <td>{{ $m->member_type }}</td>
                        <td>{{ $m->phone ?? '-' }} / {{ $m->email ?? '-' }}</td>
                        <td>{{ $m->city ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $m->status_badge_class }}">{{ $m->status_label }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data anggota</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
