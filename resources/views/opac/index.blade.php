@extends('layouts.opac')

@section('title', 'Katalog Online (OPAC)')

@section('content')
{{-- Hero Search Section --}}
<div style="background: linear-gradient(135deg, #1e3a5f 0%, #2d5282 100%); color: #fff; padding: 4rem 1rem; text-align: center; margin-bottom: 2rem;">
    <div class="container">
        <h1 class="fw-800 mb-2">Cari Koleksi Perpustakaan</h1>
        <p class="lead text-white-50 mb-4" style="font-size: 1.1rem;">Temukan buku, jurnal, kamus, dan referensi lainnya secara online</p>

        <form action="{{ route('opac.index') }}" method="GET" style="max-width: 650px; margin: 0 auto;">
            <div class="input-group input-group-lg shadow">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="q" class="form-control border-start-0" placeholder="Ketik kata kunci judul, pengarang, penerbit, atau ISBN..." value="{{ request('q') }}" autofocus>
                <button type="submit" class="btn btn-warning fw-700 px-4">Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="container">
    <div class="row g-4">
        {{-- Left side: Filters --}}
        <div class="col-lg-3">
            <div class="card shadow-sm border-0 mb-3" style="border-radius:12px">
                <div class="card-header bg-white fw-bold"><i class="bi bi-funnel me-2"></i>Filter Pencarian</div>
                <div class="card-body">
                    <form action="{{ route('opac.index') }}" method="GET" id="opacFilterForm">
                        @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif

                        <div class="mb-3">
                            <label class="form-label text-muted" style="font-size:0.75rem;font-weight:600;text-transform:uppercase">Jenis Koleksi</label>
                            <select name="collection_type" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Semua Jenis</option>
                                @foreach($collectionTypes as $type)
                                <option value="{{ $type }}" {{ request('collection_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted" style="font-size:0.75rem;font-weight:600;text-transform:uppercase">Tahun Terbit</label>
                            <select name="year" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Semua Tahun</option>
                                @foreach($years as $yr)
                                <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted" style="font-size:0.75rem;font-weight:600;text-transform:uppercase">Bahasa</label>
                            <select name="language" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Semua Bahasa</option>
                                <option value="id" {{ request('language') == 'id' ? 'selected' : '' }}>Indonesia</option>
                                <option value="en" {{ request('language') == 'en' ? 'selected' : '' }}>Inggris</option>
                            </select>
                        </div>

                        <a href="{{ route('opac.index') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-x"></i> Bersihkan Filter</a>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right side: Book grid --}}
        <div class="col-lg-9">
            @if(request('q') || request('collection_type') || request('year') || request('language'))
                <div class="alert alert-light border shadow-sm py-2 px-3 mb-3 d-flex justify-content-between align-items-center" style="border-radius:10px;font-size:0.85rem">
                    <div>
                        Menampilkan hasil pencarian untuk:
                        @if(request('q')) <strong>"{{ request('q') }}"</strong> @endif
                        @if(request('collection_type')) <span class="badge bg-primary ms-1">{{ request('collection_type') }}</span> @endif
                        @if(request('year')) <span class="badge bg-secondary ms-1">{{ request('year') }}</span> @endif
                        @if(request('language')) <span class="badge bg-info ms-1">{{ request('language') == 'id' ? 'Indonesia' : 'Inggris' }}</span> @endif
                    </div>
                    <span class="text-muted">{{ $books->total() }} judul ditemukan</span>
                </div>
            @endif

            <div class="row g-3">
                @forelse($books as $book)
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0 hover-card" style="border-radius:12px;overflow:hidden;transition:transform 0.2s">
                        <div style="aspect-ratio:3/4;background:#f8fafc;display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative;border-bottom:1px solid #e9edf4">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" style="width:100%;height:100%;object-fit:cover">
                            @else
                                <i class="bi bi-book text-muted opacity-25" style="font-size:4rem"></i>
                            @endif
                            <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2 fw-600" style="font-size:0.68rem">{{ $book->collection_type }}</span>
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <h6 class="fw-700 text-dark mb-1 text-truncate-2" style="height:38px;line-height:1.2">
                                <a href="{{ route('opac.show', $book) }}" class="text-decoration-none text-dark">{{ $book->title }}</a>
                            </h6>
                            <div class="text-muted text-truncate mb-2" style="font-size:0.75rem">{{ $book->main_author ?? 'Pengarang tidak terdaftar' }}</div>
                            <div class="mt-auto d-flex justify-content-between align-items-center border-top pt-2">
                                <span style="font-size:0.7rem" class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ $book->publication_year ?? '-' }}</span>
                                @if($book->available_items_count > 0)
                                <span class="badge bg-success-subtle text-success border border-success-subtle fw-500" style="font-size:0.68rem">Tersedia {{ $book->available_items_count }}</span>
                                @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-500" style="font-size:0.68rem">Dipinjam</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="bi bi-journals fs-1 d-block mb-3 opacity-25"></i>
                    Buku tidak ditemukan. Coba masukkan kata kunci pencarian yang lain.
                </div>
                @endforelse
            </div>

            @if($books->hasPages())
            <div class="mt-4">
                {{ $books->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.hover-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.08)!important;
}
.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
