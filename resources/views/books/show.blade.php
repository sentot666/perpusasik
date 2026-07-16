@extends('layouts.app')

@section('title', 'Detail Buku: ' . $book->title)
@section('page-title', 'Detail Buku')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('books.index') }}">Master Buku</a></li>
<li class="breadcrumb-item active">{{ Str::limit($book->title, 30) }}</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Detail Buku</h1>
        <p>Detail bibliografi dan eksemplar fisik buku</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('books.barcode', $book) }}" target="_blank" class="btn btn-warning text-dark fw-500">
            <i class="bi bi-upc-scan me-1"></i>Cetak Barcode
        </a>
        <a href="{{ route('books.edit', $book) }}" class="btn btn-outline-secondary">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- Left side: Bibliografi detail --}}
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-journal-text me-2"></i>Informasi Bibliografi</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3 text-center">
                        <div style="width:100%;max-width:130px;aspect-ratio:3/4;background:#f1f5f9;border-radius:8px;border:1px solid #e2e8f0;overflow:hidden;margin:0 auto;display:flex;align-items:center;justify-content:center;color:#94a3b8">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" style="width:100%;height:100%;object-fit:cover">
                            @else
                                <i class="bi bi-book fs-1"></i>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-9">
                        <h3 class="fw-700 text-dark mb-1">{{ $book->title }}</h3>
                        @if($book->subtitle)
                        <h5 class="text-muted mb-2">{{ $book->subtitle }}</h5>
                        @endif

                        <div class="mb-3">
                            <span class="badge bg-primary">{{ $book->collection_type }}</span>
                            <span class="badge bg-light text-dark border">{{ $book->language == 'id' ? 'Bahasa Indonesia' : 'Bahasa Asing' }}</span>
                        </div>

                        <table class="table table-borderless table-sm mb-0" style="font-size:0.85rem">
                            <tr>
                                <td style="width:120px" class="text-muted">PENGARANG</td>
                                <td>:
                                    @forelse($book->authors as $author)
                                        <span class="fw-500">{{ $author->name }}</span>{{ !$loop->last ? ', ' : '' }}
                                    @empty
                                        <span class="text-muted">-</span>
                                    @endforelse
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">PENERBIT</td>
                                <td>: {{ $book->publisher?->name ?? '-' }} ({{ $book->publisher?->city ?? '' }})</td>
                            </tr>
                            <tr>
                                <td class="text-muted">TAHUN TERBIT</td>
                                <td>: {{ $book->publication_year ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">EDISI / SERI</td>
                                <td>: {{ $book->edition ?? '-' }} @if($book->series_title) (Seri: {{ $book->series_title }} #{{ $book->series_number }}) @endif</td>
                            </tr>
                            <tr>
                                <td class="text-muted">ISBN</td>
                                <td>: {{ $book->isbn ?? '-' }} @if($book->isbn13) / {{ $book->isbn13 }} @endif</td>
                            </tr>
                            <tr>
                                <td class="text-muted">KLASIFIKASI</td>
                                <td>: DDC {{ $book->ddc ?? '-' }} / No. Panggil: <code>{{ $book->call_number ?? '-' }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted">FISIK</td>
                                <td>: {{ $book->pages ?? '-' }} hlm; {{ $book->dimensions ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">SUBYEK</td>
                                <td>:
                                    @forelse($book->subjects as $sub)
                                        <span class="badge bg-light text-dark border">{{ $sub->name }}</span>
                                    @empty
                                        <span class="text-muted">-</span>
                                    @endforelse
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($book->abstract)
                <hr>
                <div style="font-size:0.85rem">
                    <h6 class="fw-600 mb-2">Abstrak/Catatan Ringkas:</h6>
                    <p class="text-muted mb-0" style="line-height:1.6">{{ $book->abstract }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right side: Copies summary --}}
    <div class="col-lg-4">
        <div class="card mb-3 text-center">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i>Ketersediaan</div>
            <div class="card-body">
                <div class="d-flex justify-content-around align-items-center">
                    <div>
                        <div class="fs-2 fw-800 text-primary">{{ $book->items()->count() }}</div>
                        <small class="text-muted" style="font-size:0.65rem;text-transform:uppercase">Total Eksemplar</small>
                    </div>
                    <div style="border-left:1px solid #e2e8f0;height:40px"></div>
                    <div>
                        <div class="fs-2 fw-800 text-success">{{ $book->items()->where('status', 'Tersedia')->count() }}</div>
                        <small class="text-muted" style="font-size:0.65rem;text-transform:uppercase">Tersedia</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="bi bi-geo-alt me-2"></i>Rak Penyimpanan</div>
            <div class="card-body py-2">
                @php
                $itemLocations = $book->items()->with('location')->get()->pluck('location.name')->unique()->filter();
                @endphp
                @forelse($itemLocations as $loc)
                    <div class="badge bg-light text-dark border p-2 mb-1 w-100 text-start">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $loc }}
                    </div>
                @empty
                    <span class="text-muted" style="font-size:0.82rem">Belum ada penempatan lokasi eksemplar</span>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Eksemplar Section --}}
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-upc-scan me-2 text-primary"></i>Daftar Eksemplar Fisik</span>
        <a href="{{ route('books.items.create', $book) }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Tambah Eksemplar
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Barcode / RFID</th>
                        <th>Nomor Induk</th>
                        <th>Lokasi / Rak</th>
                        <th>Kondisi</th>
                        <th>Tanggal Perolehan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($book->items as $item)
                    <tr>
                        <td><code class="fw-600" style="font-size:0.85rem">{{ $item->barcode }}</code></td>
                        <td>{{ $item->accession_number }}</td>
                        <td>
                            @if($item->location)
                            <span class="fw-500"><i class="bi bi-geo-alt me-1 text-danger"></i>{{ $item->location->name }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $item->condition == 'Baik' ? 'success' : ($item->condition == 'Rusak' ? 'danger' : 'warning') }}">
                                {{ $item->condition }}
                            </span>
                        </td>
                        <td>{{ $item->acquisition_date ? $item->acquisition_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $item->status == 'Tersedia' ? 'success' : ($item->status == 'Dipinjam' ? 'warning text-dark' : 'secondary') }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('book-items.edit', $item) }}" class="btn btn-outline-secondary" title="Edit" data-bs-toggle="tooltip">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('book-items.destroy', $item) }}" onsubmit="return confirm('Hapus eksemplar ini?')">
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
                        <td colspan="7" class="text-center py-4 text-muted">
                            Belum ada eksemplar fisik untuk buku ini. <a href="{{ route('books.items.create', $book) }}">Tambah eksemplar pertama</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
