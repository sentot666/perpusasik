@extends('layouts.app')

@section('title', 'Master Lokasi / Rak')
@section('page-title', 'Master Lokasi')

@section('breadcrumb')
<li class="breadcrumb-item active">Lokasi</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Master Lokasi / Rak</h1>
        <p>Kelola data ruangan, lemari, atau rak penempatan buku fisik</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLocationModal">
            <i class="bi bi-plus-circle me-1"></i>Tambah Lokasi
        </button>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama lokasi atau kode..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i> Cari</button>
                <a href="{{ route('locations.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i> Reset</a>
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
                        <th>Kode Lokasi</th>
                        <th>Nama Lokasi / Rak</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $loc)
                    <tr>
                        <td style="width:50px">{{ $locations->firstItem() + $loop->index }}</td>
                        <td><code class="fw-600 fs-6">{{ $loc->code }}</code></td>
                        <td class="fw-600 text-dark">{{ $loc->name }}</td>
                        <td>{{ $loc->description ?? '-' }}</td>
                        <td class="text-center" style="width:150px">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editLocationModal{{ $loc->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('locations.destroy', $loc) }}" onsubmit="return confirm('Hapus lokasi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editLocationModal{{ $loc->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('locations.update', $loc) }}">
                                @csrf @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Lokasi</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-start">
                                        <div class="mb-3">
                                            <label class="form-label fw-500">Kode Lokasi</label>
                                            <input type="text" name="code" class="form-control" value="{{ $loc->code }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-500">Nama Lokasi / Rak</label>
                                            <input type="text" name="name" class="form-control" value="{{ $loc->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-500">Keterangan / Deskripsi</label>
                                            <textarea name="description" class="form-control" rows="3">{{ $loc->description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada data lokasi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($locations->hasPages())
    <div class="card-footer bg-white border-top py-2">
        {{ $locations->links() }}
    </div>
    @endif
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addLocationModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('locations.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Lokasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-500">Kode Lokasi</label>
                        <input type="text" name="code" class="form-control" placeholder="Contoh: RAK-01-A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500">Nama Lokasi / Rak</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Rak Komputer Utama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500">Keterangan / Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Opsional..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
