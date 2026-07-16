@extends('layouts.app')

@section('title', 'Master Subyek')
@section('page-title', 'Master Subyek')

@section('breadcrumb')
<li class="breadcrumb-item active">Subyek</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Master Subyek</h1>
        <p>Kelola klasifikasi subyek / topik buku (DDC)</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
            <i class="bi bi-plus-circle me-1"></i>Tambah Subyek
        </button>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama subyek atau kode DDC..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i> Cari</button>
                <a href="{{ route('subjects.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i> Reset</a>
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
                        <th>Klasifikasi DDC</th>
                        <th>Nama Subyek</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $sub)
                    <tr>
                        <td style="width:50px">{{ $subjects->firstItem() + $loop->index }}</td>
                        <td><code class="fw-600 fs-6">{{ $sub->ddc ?? '-' }}</code></td>
                        <td class="fw-600 text-dark">{{ $sub->name }}</td>
                        <td class="text-center" style="width:150px">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editSubjectModal{{ $sub->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('subjects.destroy', $sub) }}" onsubmit="return confirm('Hapus subyek ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editSubjectModal{{ $sub->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('subjects.update', $sub) }}">
                                @csrf @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Subyek</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-start">
                                        <div class="mb-3">
                                            <label class="form-label fw-500">Nama Subyek / Topik</label>
                                            <input type="text" name="name" class="form-control" value="{{ $sub->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-500">Klasifikasi DDC</label>
                                            <input type="text" name="ddc" class="form-control" value="{{ $sub->ddc }}" placeholder="Contoh: 005.3">
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
                    <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data subyek</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($subjects->hasPages())
    <div class="card-footer bg-white border-top py-2">
        {{ $subjects->links() }}
    </div>
    @endif
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('subjects.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Subyek</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-500">Nama Subyek / Topik</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Teknologi Informasi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500">Klasifikasi DDC</label>
                        <input type="text" name="ddc" class="form-control" placeholder="Contoh: 005">
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
