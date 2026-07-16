@extends('layouts.app')

@section('title', 'Master Penerbit')
@section('page-title', 'Master Penerbit')

@section('breadcrumb')
<li class="breadcrumb-item active">Penerbit</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Master Penerbit</h1>
        <p>Kelola data penerbit buku</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPublisherModal">
            <i class="bi bi-plus-circle me-1"></i>Tambah Penerbit
        </button>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama penerbit..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i> Cari</button>
                <a href="{{ route('publishers.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i> Reset</a>
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
                        <th>Nama Penerbit</th>
                        <th>Kota</th>
                        <th>Negara</th>
                        <th>Website</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($publishers as $pub)
                    <tr>
                        <td style="width:50px">{{ $publishers->firstItem() + $loop->index }}</td>
                        <td class="fw-600">{{ $pub->name }}</td>
                        <td>{{ $pub->city ?? '-' }}</td>
                        <td>{{ $pub->country ?? '-' }}</td>
                        <td>
                            @if($pub->website)
                            <a href="{{ $pub->website }}" target="_blank" class="text-decoration-none"><i class="bi bi-globe me-1"></i>Link</a>
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-center" style="width:150px">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editPublisherModal{{ $pub->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('publishers.destroy', $pub) }}" onsubmit="return confirm('Hapus penerbit ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editPublisherModal{{ $pub->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('publishers.update', $pub) }}">
                                @csrf @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Penerbit</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-start">
                                        <div class="mb-3">
                                            <label class="form-label fw-500">Nama Penerbit</label>
                                            <input type="text" name="name" class="form-control" value="{{ $pub->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-500">Kota</label>
                                            <input type="text" name="city" class="form-control" value="{{ $pub->city }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-500">Negara</label>
                                            <input type="text" name="country" class="form-control" value="{{ $pub->country }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-500">Website</label>
                                            <input type="url" name="website" class="form-control" value="{{ $pub->website }}" placeholder="https://example.com">
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
                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data penerbit</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($publishers->hasPages())
    <div class="card-footer bg-white border-top py-2">
        {{ $publishers->links() }}
    </div>
    @endif
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addPublisherModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('publishers.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Penerbit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-500">Nama Penerbit</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Balai Pustaka" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500">Kota</label>
                        <input type="text" name="city" class="form-control" placeholder="Contoh: Jakarta">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500">Negara</label>
                        <input type="text" name="country" class="form-control" placeholder="Contoh: Indonesia" value="Indonesia">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500">Website</label>
                        <input type="url" name="website" class="form-control" placeholder="Contoh: https://balaipustaka.co.id">
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
