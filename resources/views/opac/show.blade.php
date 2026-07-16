@extends('layouts.opac')

@section('title', $book->title . ' - OPAC Detail')

@section('content')
<div class="container mt-4">
    {{-- Back button --}}
    <a href="{{ route('opac.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Pencarian
    </a>

    <div class="row g-4">
        {{-- Left: cover and details --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4" style="border-radius:12px">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4 text-center">
                            <div class="shadow-sm border" style="width:100%;max-width:180px;aspect-ratio:3/4;background:#f8fafc;border-radius:8px;overflow:hidden;margin:0 auto;display:flex;align-items:center;justify-content:center;color:#b2bec3">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" style="width:100%;height:100%;object-fit:cover">
                                @else
                                    <i class="bi bi-book fs-1"></i>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h2 class="fw-800 text-dark mb-1">{{ $book->title }}</h2>
                            @if($book->subtitle)
                            <h5 class="text-muted mb-3">{{ $book->subtitle }}</h5>
                            @endif

                            <div class="mb-3">
                                <span class="badge bg-warning text-dark fw-600">{{ $book->collection_type }}</span>
                                <span class="badge bg-light text-dark border">{{ $book->language == 'id' ? 'Bahasa Indonesia' : 'Bahasa Asing' }}</span>
                            </div>

                            <table class="table table-borderless table-sm mb-0" style="font-size:0.875rem">
                                <tr>
                                    <td style="width:130px" class="text-muted">PENGARANG</td>
                                    <td>:
                                        @forelse($book->authors as $author)
                                            <strong class="text-primary">{{ $author->name }}</strong>{{ !$loop->last ? ', ' : '' }}
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
                                    <td class="text-muted">DESKRIPSI FISIK</td>
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
                    <div style="font-size:0.875rem">
                        <h6 class="fw-700 mb-2">Abstrak / Catatan Singkat:</h6>
                        <p class="text-muted mb-0" style="line-height:1.6">{{ $book->abstract }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Copies status table --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius:12px">
                <div class="card-header bg-white fw-bold"><i class="bi bi-upc-scan me-2 text-primary"></i>Ketersediaan Eksemplar Fisik</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0" style="font-size:0.85rem">
                            <thead>
                                <tr class="bg-light">
                                    <th>Nomor Barcode</th>
                                    <th>Nomor Induk</th>
                                    <th>Lokasi / Ruang</th>
                                    <th>Kondisi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($book->items as $item)
                                <tr>
                                    <td><code>{{ $item->barcode }}</code></td>
                                    <td>{{ $item->accession_number }}</td>
                                    <td>
                                        @if($item->location)
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $item->location->name }}
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->condition }}</td>
                                    <td>
                                        @if($item->status === 'Tersedia')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle fw-500">Tersedia</span>
                                        @elseif($item->status === 'Dipinjam')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle fw-500 text-dark">Dipinjam (Jatuh tempo: {{ $item->activeCirculation?->due_date?->format('d/m/Y') ?? '-' }})</span>
                                        @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle fw-500">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center text-muted py-3">Tidak ada data eksemplar fisik</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: sidebar (related books) --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-3" style="border-radius:12px">
                <div class="card-header bg-white fw-bold"><i class="bi bi-bookmarks-fill me-2 text-warning"></i>Koleksi Terkait</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush" style="border-radius:12px">
                        @forelse($relatedBooks as $rel)
                        <a href="{{ route('opac.show', $rel) }}" class="list-group-item list-group-item-action py-3 px-3 border-0">
                            <div class="fw-700 text-dark" style="font-size:0.875rem;line-height:1.2">{{ Str::limit($rel->title, 55) }}</div>
                            <div class="text-muted mt-1" style="font-size:0.75rem">{{ $rel->main_author ?? '-' }} ({{ $rel->publication_year }})</div>
                        </a>
                        @empty
                        <li class="list-group-item text-center text-muted py-4 border-0">Belum ada koleksi sejenis</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
