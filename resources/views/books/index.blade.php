@extends('layouts.app')

@section('title', 'Master Buku')
@section('page-title', 'Master Buku')

@section('breadcrumb')
<li class="breadcrumb-item active">Master Buku</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Master Buku</h1>
        <p>Kelola koleksi katalog bibliografi perpustakaan</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('books.create') }}" class="btn btn-primary" id="addBookBtn">
            <i class="bi bi-plus-circle me-1"></i>Tambah Buku
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center" id="filterForm">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari judul, pengarang, ISBN, no. panggil..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="collection_type" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Jenis</option>
                    @foreach($collectionTypes as $type)
                    <option value="{{ $type }}" {{ request('collection_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i> Reset</a>
            </div>
            <div class="col-auto ms-auto text-muted" style="font-size:0.8rem">
                {{ $books->total() }} judul ditemukan
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
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Penerbit</th>
                        <th>Tahun</th>
                        <th>No. Panggil</th>
                        <th>Jenis</th>
                        <th class="text-center">Eks.</th>
                        <th class="text-center">Tersedia</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                    <tr>
                        <td class="text-muted">{{ $books->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-500">
                                <a href="{{ route('books.show', $book) }}" class="text-decoration-none text-dark">
                                    {{ Str::limit($book->title, 60) }}
                                </a>
                            </div>
                            @if($book->isbn)
                            <div class="text-muted" style="font-size:0.72rem">ISBN: {{ $book->isbn }}</div>
                            @endif
                        </td>
                        <td>{{ $book->main_author ?? '-' }}</td>
                        <td>{{ $book->publisher?->name ?? '-' }}</td>
                        <td>{{ $book->publication_year ?? '-' }}</td>
                        <td><code style="font-size:0.75rem">{{ $book->call_number ?? '-' }}</code></td>
                        <td><span class="badge bg-light text-dark border">{{ $book->collection_type }}</span></td>
                        <td class="text-center fw-600">{{ $book->items_count }}</td>
                        <td class="text-center">
                            @if($book->available_items_count > 0)
                            <span class="badge bg-success">{{ $book->available_items_count }}</span>
                            @else
                            <span class="badge bg-secondary">0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('books.show', $book) }}" class="btn btn-outline-primary" title="Detail" data-bs-toggle="tooltip">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('books.edit', $book) }}" class="btn btn-outline-secondary" title="Edit" data-bs-toggle="tooltip">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('books.destroy', $book) }}" onsubmit="return confirm('Hapus buku ini?')">
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
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="bi bi-journals fs-1 d-block mb-2 opacity-25"></i>
                            Belum ada data buku. <a href="{{ route('books.create') }}">Tambah buku pertama</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($books->hasPages())
    <div class="card-footer bg-white border-top py-2">
        {{ $books->links() }}
    </div>
    @endif
</div>
@endsection
