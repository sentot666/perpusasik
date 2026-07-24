@extends('layouts.app')

@section('title', 'Detail Buku: ' . $book->title)
@section('page-title', 'Detail Buku')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('books.index') }}">Master Buku</a></li>
<li class="breadcrumb-item active">{{ Str::limit($book->title, 30) }}</li>
@endsection

@section('content')
<div class="page-header justify-between items-start flex">
    <div>
        <h1>Detail Buku</h1>
        <p>Detail bibliografi dan eksemplar fisik buku</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('books.barcode', $book) }}" target="_blank" class="btn btn-warning text-slate-800 font-medium">
            <i class="bi bi-upc-scan mr-1"></i>Cetak Barcode
        </a>
        <a href="{{ route('books.edit', $book) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2 px-6">
            <i class="bi bi-pencil mr-1"></i>Edit
        </a>
    </div>
</div>

<div class="flex flex-wrap -mx-3">
    {{-- Left side: Bibliografi detail --}}
    <div class="w-full lg:w-2/3 px-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-journal-text mr-2"></i>Informasi Bibliografi</div>
            <div class="p-8">
                <div class="flex flex-wrap -mx-3">
                    <div class="text-center w-full md:w-1/4 px-4">
                        <div style="width:100%;max-width:130px;aspect-ratio:3/4;background:#f1f5f9;border-radius:8px;border:1px solid #e2e8f0;overflow:hidden;margin:0 auto;display:flex;align-items:center;justify-content:center;color:#94a3b8">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" style="width:100%;height:100%;object-fit:cover">
                            @else
                                <i class="bi bi-book text-4xl font-bold"></i>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-9">
                        <h3 class="fw-700 text-slate-800 mb-1">{{ $book->title }}</h3>
                        @if($book->subtitle)
                        <h5 class="text-slate-500 mb-2">{{ $book->subtitle }}</h5>
                        @endif

                        <div class="mb-6">
                            <span class="inline-flex py-1 text-xs font-medium rounded-md bg-indigo-100 text-indigo-700 px-2">{{ $book->collection_type }}</span>
                            <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-slate-50 border border-slate-200 text-slate-800">{{ $book->language == 'id' ? 'Bahasa Indonesia' : 'Bahasa Asing' }}</span>
                        </div>

                        <table class="border-0 text-sm w-full text-left text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 mb-0" style="font-size:0.85rem">
                            <tr>
                                <td style="width:120px" class="text-slate-500">PENGARANG</td>
                                <td>:
                                    @forelse($book->authors as $author)
                                        <span class="font-medium">{{ $author->name }}</span>{{ !$loop->last ? ', ' : '' }}
                                    @empty
                                        <span class="text-slate-500">-</span>
                                    @endforelse
                                </td>
                            </tr>
                            <tr>
                                <td class="text-slate-500">PENERBIT</td>
                                <td>: {{ $book->publisher?->name ?? '-' }} ({{ $book->publisher?->city ?? '' }})</td>
                            </tr>
                            <tr>
                                <td class="text-slate-500">TAHUN TERBIT</td>
                                <td>: {{ $book->publication_year ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-slate-500">EDISI / SERI</td>
                                <td>: {{ $book->edition ?? '-' }} @if($book->series_title) (Seri: {{ $book->series_title }} #{{ $book->series_number }}) @endif</td>
                            </tr>
                            <tr>
                                <td class="text-slate-500">ISBN</td>
                                <td>: {{ $book->isbn ?? '-' }} @if($book->isbn13) / {{ $book->isbn13 }} @endif</td>
                            </tr>
                            <tr>
                                <td class="text-slate-500">KLASIFIKASI</td>
                                <td>: DDC {{ $book->ddc ?? '-' }} / No. Panggil: <code>{{ $book->call_number ?? '-' }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-slate-500">FISIK</td>
                                <td>: {{ $book->pages ?? '-' }} hlm; {{ $book->dimensions ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-slate-500">SUBYEK</td>
                                <td>:
                                    @forelse($book->subjects as $sub)
                                        <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-slate-50 border border-slate-200 text-slate-800">{{ $sub->name }}</span>
                                    @empty
                                        <span class="text-slate-500">-</span>
                                    @endforelse
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($book->abstract)
                <hr>
                <div style="font-size:0.85rem">
                    <h6 class="font-semibold mb-2">Abstrak/Catatan Ringkas:</h6>
                    <p class="text-slate-500 mb-0" style="line-height:1.6">{{ $book->abstract }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right side: Copies summary --}}
    <div class="w-full lg:w-1/3 px-4">
        <div class="text-center bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-info-circle mr-2"></i>Ketersediaan</div>
            <div class="p-8">
                <div class="justify-content-around items-center flex">
                    <div>
                        <div class="text-3xl font-bold fw-800 text-indigo-600">{{ $book->items()->count() }}</div>
                        <small class="text-slate-500" style="font-size:0.65rem;text-transform:uppercase">Total Eksemplar</small>
                    </div>
                    <div style="border-left:1px solid #e2e8f0;height:40px"></div>
                    <div>
                        <div class="text-3xl font-bold fw-800 text-emerald-600">{{ $book->items()->where('status', 'Tersedia')->count() }}</div>
                        <small class="text-slate-500" style="font-size:0.65rem;text-transform:uppercase">Tersedia</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-geo-alt mr-2"></i>Rak Penyimpanan</div>
            <div class="px-8 py-2">
                @php
                $itemLocations = $book->items()->with('location')->get()->pluck('location.name')->unique()->filter();
                @endphp
                @forelse($itemLocations as $loc)
                    <div class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-slate-50 border border-slate-200 p-2 w-full text-left text-slate-800 mb-1">
                        <i class="bi bi-geo-alt-fill mr-1 text-red-600"></i>{{ $loc }}
                    </div>
                @empty
                    <span class="text-slate-500" style="font-size:0.82rem">Belum ada penempatan lokasi eksemplar</span>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Eksemplar Section --}}
<div class="mt-6 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="justify-between items-center px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 flex py-4">
        <span><i class="bi bi-upc-scan text-indigo-600 mr-2"></i>Daftar Eksemplar Fisik</span>
        <a href="{{ route('books.items.create', $book) }}" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 transition-colors text-white px-4">
            <i class="bi bi-plus-circle mr-1"></i>Tambah Eksemplar
        </a>
    </div>
    <div class="p-0">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50">
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
                        <td><code class="font-semibold" style="font-size:0.85rem">{{ $item->barcode }}</code></td>
                        <td>{{ $item->accession_number }}</td>
                        <td>
                            @if($item->location)
                            <span class="font-medium"><i class="bi bi-geo-alt mr-1 text-red-600"></i>{{ $item->location->name }}</span>
                            @else
                            <span class="text-slate-500">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-{{ $item->condition == 'Baik' ? 'success' : ($item->condition 'Rusak' 'danger' 'warning') }}">
                                {{ $item->condition }}
                            </span>
                        </td>
                        <td>{{ $item->acquisition_date ? $item->acquisition_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-{{ $item->status == 'Tersedia' ? 'success' : ($item->status 'Dipinjam' 'warning text-dark' 'secondary') }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="inline-flex rounded-md shadow-sm rounded-lg">
                                <a href="{{ route('book-items.edit', $item) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2 px-6" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('book-items.destroy', $item) }}" onsubmit="return confirm('Hapus eksemplar ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-red-600 border border-slate-200 border-red-600 hover:bg-red-50 transition-colors gap-2 py-2 px-6" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-slate-500 py-6">
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
