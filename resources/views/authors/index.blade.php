@extends('layouts.app')

@section('title', 'Master Pengarang')
@section('page-title', 'Master Pengarang')

@section('breadcrumb')
<li class="breadcrumb-item active">Pengarang</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Master Pengarang</h1>
        <p>Kelola data pengarang/penulis buku</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAuthorModal">
            <i class="bi bi-plus-circle me-1"></i>Tambah Pengarang
        </button>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama pengarang..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i> Cari</button>
                <a href="{{ route('authors.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i> Reset</a>
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
                        <th>Nama Pengarang</th>
                        <th>Tipe</th>
                        <th>Biografi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($authors as $author)
                    <tr>
                        <td style="width:50px">{{ $authors->firstItem() + $loop->index }}</td>
                        <td class="fw-600">{{ $author->name }}</td>
                        <td>
                            <span class="badge bg-{{ $author->type == 'personal' ? 'info' : 'secondary' }}">
                                {{ $author->type == 'personal' ? 'Perorangan' : 'Lembaga/Badan' }}
                            </span>
                        </td>
                        <td class="text-truncate" style="max-width:250px" title="{{ $author->biography }}">{{ $author->biography ?? '-' }}</td>
                        <td class="text-center" style="width:150px">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editAuthorModal{{ $author->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('authors.destroy', $author) }}" onsubmit="return confirm('Hapus pengarang ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editAuthorModal{{ $author->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('authors.update', $author) }}">
                                @csrf @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Pengarang</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-start">
                                        <div class="mb-3">
                                            <label class="form-label fw-500">Nama Pengarang</label>
                                            <input type="text" name="name" class="form-control" value="{{ $author->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-500">Tipe</label>
                                            <select name="type" class="form-select" required>
                                                <option value="personal" {{ $author->type == 'personal' ? 'selected' : '' }}>Perorangan</option>
                                                <option value="organization" {{ $author->type == 'organization' ? 'selected' : '' }}>Lembaga/Badan</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-500">Biografi Ringkas</label>
                                            <textarea name="biography" class="form-control" rows="3">{{ $author->biography }}</textarea>
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
                    <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada data pengarang</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($authors->hasPages())
    <div class="card-footer bg-white border-top py-2">
        {{ $authors->links() }}
    </div>
    @endif
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addAuthorModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('authors.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pengarang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-500">Nama Pengarang</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Andrea Hirata" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500">Tipe</label>
                        <select name="type" class="form-select" required>
                            <option value="personal">Perorangan</option>
                            <option value="organization">Lembaga/Badan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500">Biografi Ringkas</label>
                        <textarea name="biography" class="form-control" rows="3" placeholder="Opsional..."></textarea>
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
