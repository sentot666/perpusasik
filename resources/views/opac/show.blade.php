@extends('layouts.opac')

@section('title', $book->title . ' - OPAC Detail')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6 px-6">
    {{-- Back button --}}
    <a href="{{ route('opac.index') }}" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors mb-6 px-4">
        <i class="bi bi-arrow-left mr-1"></i>{{ __('Kembali ke Pencarian') }}
    </a>

    <div class="flex flex-wrap -mx-4">
        {{-- Left: cover and details --}}
        <div class="w-full lg:w-2/3 px-4">
            <div class="shadow-sm border-0 mb-6 bg-white rounded-xl border border-slate-200 overflow-hidden" style="border-radius:12px">
                <div class="p-8 p-6">
                    <div class="flex flex-wrap -mx-3">
                        <div class="text-center w-full md:w-1/3 px-4">
                            <div class="shadow-sm border border-slate-200" style="width:100%;max-width:180px;aspect-ratio:3/4;background:#f8fafc;border-radius:8px;overflow:hidden;margin:0 auto;display:flex;align-items:center;justify-content:center;color:#b2bec3">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" style="width:100%;height:100%;object-fit:cover">
                                @else
                                    <i class="bi bi-book text-4xl font-bold"></i>
                                @endif
                            </div>
                        </div>
                        <div class="w-full md:w-2/3 px-4">
                            <h2 class="fw-800 text-slate-800 mb-1">{{ $book->title }}</h2>
                            @if($book->subtitle)
                            <h5 class="text-slate-500 mb-6">{{ $book->subtitle }}</h5>
                            @endif

                            <div class="mb-6">
                                <span class="inline-flex py-1 text-xs font-medium rounded-md bg-amber-100 text-amber-800 font-semibold px-2">{{ $book->collection_type }}</span>
                                <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-slate-50 border border-slate-200 text-slate-800">{{ $book->language == 'id' ? __('Bahasa Indonesia') : __('Bahasa Asing') }}</span>
                            </div>

                            <table class="border-0 text-sm w-full text-left text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 mb-0" style="font-size:0.875rem">
                                <tr>
                                    <td style="width:130px" class="text-slate-500">{{ __('PENGARANG') }}</td>
                                    <td>:
                                        @forelse($book->authors as $author)
                                            <strong class="text-indigo-600">{{ $author->name }}</strong>{{ !$loop->last ? ', ' : '' }}
                                        @empty
                                            <span class="text-slate-500">-</span>
                                        @endforelse
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-slate-500">{{ __('PENERBIT') }}</td>
                                    <td>: {{ $book->publisher?->name ?? '-' }} ({{ $book->publisher?->city ?? '' }})</td>
                                </tr>
                                <tr>
                                    <td class="text-slate-500">{{ __('TAHUN TERBIT') }}</td>
                                    <td>: {{ $book->publication_year ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-slate-500">{{ __('EDISI / SERI') }}</td>
                                    <td>: {{ $book->edition ?? '-' }} @if($book->series_title) ({{ __('Seri') }}: {{ $book->series_title }} #{{ $book->series_number }}) @endif</td>
                                </tr>
                                <tr>
                                    <td class="text-slate-500">ISBN</td>
                                    <td>: {{ $book->isbn ?? '-' }} @if($book->isbn13) / {{ $book->isbn13 }} @endif</td>
                                </tr>
                                <tr>
                                    <td class="text-slate-500">{{ __('KLASIFIKASI') }}</td>
                                    <td>: {{ __('DDC') }} {{ $book->ddc ?? '-' }} / {{ __('No. Panggil') }}: <code>{{ $book->call_number ?? '-' }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-slate-500">{{ __('DESKRIPSI FISIK') }}</td>
                                    <td>: {{ $book->pages ?? '-' }} {{ __('hlm') }}; {{ $book->dimensions ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-slate-500">{{ __('SUBYEK') }}</td>
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
                    <div style="font-size:0.875rem">
                        <h6 class="fw-700 mb-2">{{ __('Abstrak / Catatan Singkat:') }}</h6>
                        <p class="text-slate-500 mb-0" style="line-height:1.6">{{ $book->abstract }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Copies status table --}}
            <div class="shadow-sm border-0 mb-6 bg-white rounded-xl border border-slate-200 overflow-hidden" style="border-radius:12px">
                <div class="bg-white px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 font-bold py-4"><i class="bi bi-upc-scan text-indigo-600 mr-2"></i>{{ __('Ketersediaan Eksemplar Fisik') }}</div>
                <div class="p-0">
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 mb-0" style="font-size:0.85rem">
                            <thead>
                                <tr class="bg-slate-50">
                                    <th>{{ __('Nomor Barcode') }}</th>
                                    <th>{{ __('Nomor Induk') }}</th>
                                    <th>{{ __('Lokasi / Ruang') }}</th>
                                    <th>{{ __('Kondisi') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($book->items as $item)
                                <tr>
                                    <td><code>{{ $item->barcode }}</code></td>
                                    <td>{{ $item->accession_number }}</td>
                                    <td>
                                        @if($item->location)
                                        <i class="bi bi-geo-alt-fill mr-1 text-red-600"></i>{{ $item->location->name }}
                                        @else
                                        <span class="text-slate-500">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->condition }}</td>
                                    <td>
                                        @if($item->status === 'Tersedia')
                                        <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-success-subtle border border-slate-200 border-success-subtle text-emerald-600">{{ __('Tersedia') }}</span>
                                        @elseif($item->status === 'Dipinjam')
                                        <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-warning-subtle border border-slate-200 border-warning-subtle text-amber-500 text-slate-800">{{ __('Dipinjam') }} ({{ __('Jatuh tempo:') }} {{ $item->activeCirculation?->due_date?->format('d/m/Y') ?? '-' }})</span>
                                        @else
                                        <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-secondary-subtle text-slate-600 border border-slate-200 border-secondary-subtle">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center text-slate-500 py-4">{{ __('Tidak ada data eksemplar fisik') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: sidebar (related books) --}}
        <div class="w-full lg:w-1/3 px-4">
            
            {{-- Box Informasi Rak --}}
            @php
                $distinctLocations = $book->items->pluck('location')->filter()->unique('id');
                $availableCount = $book->items->where('status', 'Tersedia')->count();
                $totalCount = $book->items->count();
            @endphp
            @if($distinctLocations->count() > 0)
            <div class="shadow-sm border-0 bg-white rounded-xl border border-indigo-200 overflow-hidden mb-6" style="border-radius:12px">
                <div class="bg-indigo-50 px-8 border-b border-indigo-100 font-bold text-indigo-700 py-4 flex items-center justify-between">
                    <span><i class="bi bi-geo-alt-fill mr-2"></i>{{ __('Lokasi di Rak') }}</span>
                    @if($availableCount > 0)
                        <span class="bg-emerald-100 text-emerald-700 text-xs px-2 py-0.5 rounded">{{ __('Tersedia') }}</span>
                    @else
                        <span class="bg-rose-100 text-rose-700 text-xs px-2 py-0.5 rounded">{{ __('Dipinjam') }}</span>
                    @endif
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <div class="text-xs text-slate-500 font-bold uppercase mb-1">Nomor Panggil (Call Number)</div>
                        <div class="text-xl font-bold text-slate-800 font-mono bg-slate-100 inline-block px-3 py-1 rounded">{{ $book->call_number ?? '-' }}</div>
                    </div>
                    <div class="mb-4">
                        <div class="text-xs text-slate-500 font-bold uppercase mb-1">Kategori Rak</div>
                        @foreach($distinctLocations as $loc)
                            <div class="text-sm font-medium text-slate-700 mb-1"><i class="bi bi-bookshelf text-indigo-500 mr-2"></i>{{ $loc->name }}</div>
                        @endforeach
                    </div>
                    <div class="text-sm text-slate-600 mb-6">
                        Tersedia <strong>{{ $availableCount }}</strong> dari total <strong>{{ $totalCount }}</strong> eksemplar.
                    </div>
                    <button type="button" onclick="document.getElementById('mapModal').classList.remove('hidden')" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2 shadow-md shadow-indigo-600/30">
                        <i class="bi bi-map"></i> Lihat Denah Rak
                    </button>
                </div>
            </div>
            @endif

            <div class="shadow-sm border-0 bg-white rounded-xl border border-slate-200 overflow-hidden mb-6" style="border-radius:12px">
                <div class="bg-white px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 font-bold py-4"><i class="bi bi-bookmarks-fill text-amber-500 mr-2"></i>{{ __('Koleksi Terkait') }}</div>
                <div class="p-0">
                    <ul class="divide-y divide-slate-100 flex flex-col" style="border-radius:12px">
                        @forelse($relatedBooks as $rel)
                        <a href="{{ route('opac.show', $rel) }}" class="list-group-item-action border-0 block py-4 px-4">
                            <div class="fw-700 text-slate-800" style="font-size:0.875rem;line-height:1.2">{{ Str::limit($rel->title, 55) }}</div>
                            <div class="text-slate-500 mt-1" style="font-size:0.75rem">{{ $rel->main_author ?? '-' }} ({{ $rel->publication_year }})</div>
                        </a>
                        @empty
                        <li class="border-0 block text-center text-slate-500 py-6">{{ __('Belum ada koleksi sejenis') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

{{-- Peta Denah Rak Modal --}}
@if(isset($distinctLocations) && $distinctLocations->count() > 0)
<div id="mapModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="font-bold text-lg text-slate-800"><i class="bi bi-map mr-2 text-indigo-600"></i>Denah Perpustakaan</h3>
            <button type="button" onclick="document.getElementById('mapModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors bg-transparent border-0 text-2xl leading-none">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto bg-slate-100 flex-1 relative">
            {{-- Legend --}}
            <div class="bg-white px-4 py-3 rounded-xl shadow-sm inline-flex flex-wrap items-center gap-4 mb-6 text-sm font-medium border border-slate-200">
                <div class="flex items-center gap-2"><div class="w-4 h-4 bg-indigo-500 rounded ring-4 ring-indigo-200 animate-pulse"></div> Lokasi Buku Ini</div>
                <div class="flex items-center gap-2"><div class="w-4 h-4 bg-slate-300 rounded"></div> Rak Lainnya</div>
                <div class="flex items-center gap-2"><div class="w-4 h-4 bg-amber-200 border-2 border-amber-400 rounded"></div> Meja Layanan</div>
            </div>

            {{-- Floor Plan Container --}}
            <div class="bg-white border-2 border-slate-300 rounded-lg p-8 shadow-inner min-w-[700px] max-w-[800px] mx-auto relative" style="height: 500px;">
                <!-- Meja Layanan -->
                <div class="absolute bottom-8 left-1/2 -translate-x-1/2 w-48 h-16 bg-amber-100 border-2 border-amber-400 rounded-lg flex items-center justify-center text-amber-800 font-bold shadow-sm">
                    Meja Layanan / Sirkulasi
                </div>
                <!-- Pintu Masuk -->
                <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-32 h-2 bg-slate-300 rounded-t-lg flex justify-center -mb-2">
                    <span class="text-[10px] font-bold text-slate-500 -mt-4 uppercase bg-white px-1">Pintu Masuk</span>
                </div>

                @php
                    $highlightCodes = $distinctLocations->pluck('code')->toArray();
                    
                    $renderShelf = function($code, $label, $top, $left, $width = '140px', $height = '60px') use ($highlightCodes) {
                        $isHighlight = in_array($code, $highlightCodes);
                        $bgClass = $isHighlight ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/40 ring-4 ring-indigo-200 z-10' : 'bg-slate-200 text-slate-600 border border-slate-300 shadow-sm';
                        $pulseClass = $isHighlight ? 'animate-pulse' : '';
                        
                        return '<div class="absolute flex flex-col items-center justify-center rounded-sm font-bold text-sm transition-all duration-300 text-center px-1 ' . $bgClass . ' ' . $pulseClass . '" style="top: '.$top.'; left: '.$left.'; width: '.$width.'; height: '.$height.';">
                            <span class="text-[10px] opacity-80 font-normal leading-tight">'.$code.'</span>
                            <span class="text-xs leading-tight">'.$label.'</span>
                        </div>';
                    };

                    $renderShelfRight = function($code, $label, $top, $right, $width = '140px', $height = '60px') use ($highlightCodes) {
                        $isHighlight = in_array($code, $highlightCodes);
                        $bgClass = $isHighlight ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/40 ring-4 ring-indigo-200 z-10' : 'bg-slate-200 text-slate-600 border border-slate-300 shadow-sm';
                        $pulseClass = $isHighlight ? 'animate-pulse' : '';
                        
                        return '<div class="absolute flex flex-col items-center justify-center rounded-sm font-bold text-sm transition-all duration-300 text-center px-1 ' . $bgClass . ' ' . $pulseClass . '" style="top: '.$top.'; right: '.$right.'; width: '.$width.'; height: '.$height.';">
                            <span class="text-[10px] opacity-80 font-normal leading-tight">'.$code.'</span>
                            <span class="text-xs leading-tight">'.$label.'</span>
                        </div>';
                    };
                @endphp

                <!-- Rak Sisi Kiri (000 - 400) -->
                {!! $renderShelf('000', 'Karya Umum', '40px', '40px') !!}
                {!! $renderShelf('100', 'Filsafat & Psikologi', '120px', '40px') !!}
                {!! $renderShelf('200', 'Agama', '200px', '40px') !!}
                {!! $renderShelf('300', 'Ilmu Sosial', '280px', '40px') !!}
                {!! $renderShelf('400', 'Bahasa', '360px', '40px') !!}

                <!-- Rak Sisi Kanan (500 - 900) -->
                {!! $renderShelfRight('500', 'Ilmu Murni', '40px', '40px') !!}
                {!! $renderShelfRight('600', 'Ilmu Terapan', '120px', '40px') !!}
                {!! $renderShelfRight('700', 'Seni & Olahraga', '200px', '40px') !!}
                {!! $renderShelfRight('800', 'Kesusastraan', '280px', '40px') !!}
                {!! $renderShelfRight('900', 'Sejarah & Geografi', '360px', '40px') !!}
                
                <!-- Kategori Tambahan di Tengah (F, R, SL) -->
                {!! $renderShelf('F', 'Fiksi', '80px', '300px', '160px', '40px') !!}
                {!! $renderShelf('R', 'Referensi', '160px', '300px', '160px', '40px') !!}
                {!! $renderShelf('SL', 'Buku Paket', '240px', '300px', '160px', '40px') !!}
            </div>
        </div>
    </div>
</div>
@endif

@endsection
