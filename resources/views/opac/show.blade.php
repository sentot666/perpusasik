@extends('layouts.opac')

@section('title', $book->title . ' - OPAC Detail')

@push('styles')
<style>
    .book-detail-bg {
        background: linear-gradient(135deg, #fdfbf7 0%, #f6f0ea 100%);
    }
    .author-font {
        font-family: 'Georgia', serif;
    }
</style>
@endpush

@section('content')
<div class="book-detail-bg min-h-screen pb-16 pt-8">
    <div class="w-full mx-auto sm:px-6 lg:px-8 px-6">
        
        {{-- Back button --}}
        @php
            $backUrl = url()->previous();
            if ($backUrl == url()->current() || !str_contains($backUrl, url('/'))) {
                $backUrl = route('opac.katalog');
            }
        @endphp
        <a href="{{ $backUrl }}" class="inline-flex items-center text-slate-500 hover:text-slate-800 font-medium text-sm mb-8 transition-colors no-underline">
            <i class="bi bi-arrow-left mr-2"></i>Kembali
        </a>

        <div class="flex flex-col lg:flex-row gap-12">
            
            {{-- Left: Cover --}}
            <div class="w-full lg:w-1/3 xl:w-1/4 relative">
                <div class="sticky top-28 flex flex-col gap-4">
                    <div class="w-full aspect-[2/3] rounded-2xl overflow-hidden shadow-[0_20px_40px_-15px_rgba(0,0,0,0.3)] bg-white">
                        @if($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-full h-full object-cover" alt="Cover {{ $book->title }}">
                        @else
                            @php
                                $colors = ['from-blue-500 to-indigo-600', 'from-emerald-400 to-teal-600', 'from-orange-400 to-red-500', 'from-purple-500 to-pink-600', 'from-cyan-500 to-blue-600'];
                                $gradient = $colors[crc32($book->title) % count($colors)];
                                $initials = strtoupper(substr($book->title, 0, 2));
                            @endphp
                            <div class="w-full h-full bg-gradient-to-br {{ $gradient }} flex items-center justify-center text-white">
                                <span class="text-6xl font-bold opacity-80">{{ $initials }}</span>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Action Buttons --}}
                    @auth
                        <form action="{{ route('member.reservations.store', $book->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-[#e50914] hover:bg-red-700 text-white font-bold py-3 rounded-xl transition-colors shadow-sm text-center no-underline flex items-center justify-center gap-2" {{ $availableCount <= 0 ? 'disabled' : '' }} onclick="return confirm('Apakah Anda yakin ingin memesan/mereservasi buku ini?')">
                                @if($availableCount > 0)
                                    <i class="bi bi-book"></i> Pinjam / Pesan Buku
                                @elseif($totalCount == 0)
                                    <i class="bi bi-x-circle"></i> Stok Kosong
                                @else
                                    <i class="bi bi-clock-history"></i> Buku Habis Dipinjam
                                @endif
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="w-full bg-[#e50914] hover:bg-red-700 text-white font-bold py-3 rounded-xl transition-colors shadow-sm text-center no-underline">
                            Login untuk meminjam buku
                        </a>
                    @endauth

                    <div class="flex gap-3">
                        <button class="flex-1 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold py-2.5 rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                            <i class="bi bi-bookmark"></i> Favorit
                        </button>
                        <button class="flex-1 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold py-2.5 rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                            <i class="bi bi-share"></i> Bagikan
                        </button>
                    </div>
                </div>
            </div>

            {{-- Right: Details --}}
            <div class="w-full lg:w-2/3 xl:w-3/4 flex flex-col">
                
                {{-- Badges --}}
                <div class="flex items-center gap-3 mb-4">
                    @if($book->subjects->count() > 0)
                        <span class="bg-[#0066cc] text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm shadow-blue-500/20">
                            {{ $book->subjects->first()->name }}
                        </span>
                    @else
                        <span class="bg-[#0066cc] text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm shadow-blue-500/20">
                            {{ $book->collection_type }}
                        </span>
                    @endif

                    @php
                        $availableCount = $book->items->where('status', 'Tersedia')->count();
                        $totalCount = $book->items->count();
                    @endphp
                    
                    @if($availableCount > 0)
                        <span class="bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-bold px-3 py-1.5 rounded-full">
                            {{ $availableCount }} Tersedia
                        </span>
                    @elseif($totalCount == 0)
                        <span class="bg-slate-100 text-slate-600 border border-slate-200 text-xs font-bold px-3 py-1.5 rounded-full">
                            Stok Kosong
                        </span>
                    @else
                        <span class="bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold px-3 py-1.5 rounded-full">
                            Dipinjam
                        </span>
                    @endif
                </div>

                {{-- Title --}}
                <h1 class="text-3xl md:text-4xl lg:text-[40px] font-black text-[#1a1f36] mb-3 tracking-tight uppercase" style="line-height: 1.15;">
                    {{ $book->title }}
                </h1>
                @if($book->subtitle)
                    <h2 class="text-xl text-slate-500 mb-2 font-medium">{{ $book->subtitle }}</h2>
                @endif

                {{-- Author --}}
                <div class="text-lg text-slate-500 mb-6 author-font mt-2">
                    Oleh <span class="italic text-slate-800 font-semibold text-xl ml-1">{{ $book->authors->count() > 0 ? $book->authors->pluck('name')->join(', ') : 'Penulis Tidak Diketahui' }}</span>
                </div>

                {{-- Rating --}}
                <div class="flex items-center gap-2 mb-8">
                    <div class="flex text-slate-200 text-lg">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <span class="font-bold text-slate-800 text-lg ml-2">0.0</span>
                    <span class="text-slate-400 text-sm">(0 ulasan)</span>
                </div>

                <hr class="border-slate-200 mb-8 opacity-60">

                {{-- 4 Stat Cards --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-10">
                    <!-- Card 1 -->
                    <div class="bg-white rounded-2xl border border-slate-100/50 p-4 text-center shadow-sm flex flex-col items-center justify-center h-[120px] hover:shadow-md transition-shadow">
                        <i class="bi bi-book text-[#0066cc] text-[22px] mb-2 opacity-80"></i>
                        <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase mb-1">Halaman</span>
                        <span class="text-lg font-black text-slate-800">{{ $book->pages ?? '-' }}</span>
                    </div>
                    <!-- Card 2 -->
                    <div class="bg-white rounded-2xl border border-slate-100/50 p-4 text-center shadow-sm flex flex-col items-center justify-center h-[120px] hover:shadow-md transition-shadow">
                        <i class="bi bi-calendar-event text-[#0066cc] text-[22px] mb-2 opacity-80"></i>
                        <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase mb-1">Tahun Terbit</span>
                        <span class="text-lg font-black text-slate-800">{{ $book->publication_year ?? '-' }}</span>
                    </div>
                    <!-- Card 3 -->
                    <div class="bg-white rounded-2xl border border-slate-100/50 p-4 text-center shadow-sm flex flex-col items-center justify-center h-[120px] hover:shadow-md transition-shadow">
                        <i class="bi bi-arrow-repeat text-[#0066cc] text-[22px] mb-2 opacity-80"></i>
                        <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase mb-1">Stok Tersedia</span>
                        <span class="text-lg font-black text-slate-800">{{ $availableCount }} / {{ $totalCount }}</span>
                    </div>
                    <!-- Card 4 -->
                    <div class="bg-white rounded-2xl border border-slate-100/50 p-4 text-center shadow-sm flex flex-col items-center justify-center h-[120px] hover:shadow-md transition-shadow">
                        <i class="bi bi-star-fill text-[#0066cc] text-[22px] mb-2 opacity-80"></i>
                        <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase mb-1">Rating</span>
                        <span class="text-lg font-black text-slate-800">0.0<span class="text-sm text-slate-400 font-medium">/5</span></span>
                    </div>
                </div>

                {{-- Tabs for Sinopsis / Detail --}}
                <div x-data="{ tab: 'sinopsis', expanded: false }" class="mb-12">
                    {{-- Segmented Control --}}
                    <div class="bg-slate-100/70 p-1.5 rounded-xl flex mb-8 max-w-2xl border border-slate-200/50">
                        <button @click="tab = 'sinopsis'" :class="{'bg-white text-slate-800 shadow-sm': tab === 'sinopsis', 'text-slate-500 hover:text-slate-700': tab !== 'sinopsis'}" class="flex-1 py-2.5 font-bold text-sm rounded-lg transition-all outline-none">Sinopsis</button>
                        <button @click="tab = 'detail'" :class="{'bg-white text-slate-800 shadow-sm': tab === 'detail', 'text-slate-500 hover:text-slate-700': tab !== 'detail'}" class="flex-1 py-2.5 font-bold text-sm rounded-lg transition-all outline-none">Data Buku</button>
                    </div>

                    {{-- Tab Content --}}
                    <div class="text-slate-600 min-h-[200px]">
                        <div x-show="tab === 'sinopsis'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                            <h2 class="text-[22px] font-bold text-slate-800 mb-4">Sinopsis</h2>
                            @if($book->abstract)
                                <div class="relative">
                                    <div class="text-slate-600 leading-relaxed text-justify text-base overflow-hidden transition-all duration-300" :class="{'line-clamp-4 max-h-[110px]': !expanded, 'max-h-[1000px]': expanded}">
                                        <p class="m-0">{{ $book->abstract }}</p>
                                    </div>
                                    <div x-show="!expanded" class="absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-[#f6f0ea] to-transparent pointer-events-none"></div>
                                </div>
                                <button @click="expanded = !expanded" class="text-[#0066cc] font-bold text-sm mt-3 flex items-center hover:text-blue-800 transition-colors">
                                    <span x-text="expanded ? 'Tutup' : 'Lihat selengkapnya'"></span> 
                                    <i class="bi bi-chevron-down ml-1 transition-transform" :class="{'rotate-180': expanded}"></i>
                                </button>
                            @else
                                <p class="text-slate-400 italic m-0">Tidak ada sinopsis/abstrak untuk buku ini.</p>
                            @endif
                        </div>

                        <div x-show="tab === 'detail'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                            <h2 class="text-[22px] font-bold text-slate-800 mb-4">Data Buku</h2>
                            <table class="w-full text-left text-slate-600 m-0">
                                <tbody class="divide-y divide-slate-200/60">
                                    <tr>
                                        <td class="py-3 font-semibold w-1/3 sm:w-1/4">Penerbit</td>
                                        <td class="py-3">{{ $book->publisher?->name ?? '-' }} {{ $book->publisher?->city ? '('.$book->publisher->city.')' : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 font-semibold">ISBN</td>
                                        <td class="py-3 font-mono font-bold text-indigo-600">{{ $book->isbn ?? '-' }} @if($book->isbn13) / {{ $book->isbn13 }} @endif</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 font-semibold">Bahasa</td>
                                        <td class="py-3">{{ $book->language == 'id' ? 'Indonesia' : 'Asing' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 font-semibold">Edisi / Seri</td>
                                        <td class="py-3">{{ $book->edition ?? '-' }} @if($book->series_title) (Seri: {{ $book->series_title }} #{{ $book->series_number }}) @endif</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 font-semibold">Deskripsi Fisik</td>
                                        <td class="py-3">{{ $book->pages ?? '-' }} halaman; {{ $book->dimensions ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 font-semibold">Klasifikasi (DDC)</td>
                                        <td class="py-3">{{ $book->ddc ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 font-semibold">Nomor Panggil</td>
                                        <td class="py-3"><code class="bg-slate-100 px-2 py-1 rounded text-slate-800">{{ $book->call_number ?? '-' }}</code></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Lokasi Section --}}
                @php
                    $distinctLocations = $book->items->pluck('location')->filter()->unique('id');
                @endphp
                
                @if($distinctLocations->count() > 0)
                <div class="bg-gradient-to-br from-[#0066cc] to-indigo-700 rounded-2xl shadow-md p-6 text-white text-center relative overflow-hidden flex flex-col justify-center min-h-[160px] max-w-2xl">
                    <i class="bi bi-map absolute -right-4 -bottom-4 text-[120px] text-white/10 rotate-[-15deg]"></i>
                    <div class="relative z-10">
                        <h4 class="font-bold text-lg mb-1">Cari Buku di Rak</h4>
                        <p class="text-blue-100 text-[13px] mb-4 leading-relaxed">Lihat denah rak perpustakaan untuk memudahkan pencarian.</p>
                        <button type="button" onclick="document.getElementById('mapModal').classList.remove('hidden')" class="w-full max-w-[200px] mx-auto bg-white text-[#0066cc] font-bold py-2.5 rounded-xl hover:bg-slate-50 transition-colors shadow-sm text-sm flex items-center justify-center">
                            <i class="bi bi-geo-alt-fill mr-1.5"></i> Lihat Denah
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Koleksi Serupa --}}
        @if($relatedBooks->count() > 0)
        <div class="mt-16 pt-10 border-t border-slate-200/60">
            <h3 class="text-[22px] font-bold text-slate-800 mb-8 flex items-center gap-2"><i class="bi bi-bookmarks-fill text-amber-500"></i>Koleksi Serupa</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                @foreach($relatedBooks as $rel)
                    <x-book-card :book="$rel" />
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Peta Denah Rak Modal --}}
@if(isset($distinctLocations) && $distinctLocations->count() > 0)
<div id="mapModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="font-bold text-lg text-slate-800"><i class="bi bi-map mr-2 text-[#0066cc]"></i>Denah Perpustakaan</h3>
            <button type="button" onclick="document.getElementById('mapModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors bg-transparent border-0 text-2xl leading-none">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto bg-slate-100 flex-1 relative">
            {{-- Legend --}}
            <div class="bg-white px-4 py-3 rounded-xl shadow-sm inline-flex flex-wrap items-center gap-4 mb-6 text-sm font-medium border border-slate-200">
                <div class="flex items-center gap-2"><div class="w-4 h-4 bg-[#0066cc] rounded ring-4 ring-blue-200 animate-pulse"></div> Lokasi Buku Ini</div>
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
                        $bgClass = $isHighlight ? 'bg-[#0066cc] text-white shadow-lg shadow-blue-500/40 ring-4 ring-blue-200 z-10' : 'bg-slate-200 text-slate-600 border border-slate-300 shadow-sm';
                        $pulseClass = $isHighlight ? 'animate-pulse' : '';
                        
                        return '<div class="absolute flex flex-col items-center justify-center rounded-sm font-bold text-sm transition-all duration-300 text-center px-1 ' . $bgClass . ' ' . $pulseClass . '" style="top: '.$top.'; left: '.$left.'; width: '.$width.'; height: '.$height.';">
                            <span class="text-[10px] opacity-80 font-normal leading-tight">'.$code.'</span>
                            <span class="text-xs leading-tight">'.$label.'</span>
                        </div>';
                    };

                    $renderShelfRight = function($code, $label, $top, $right, $width = '140px', $height = '60px') use ($highlightCodes) {
                        $isHighlight = in_array($code, $highlightCodes);
                        $bgClass = $isHighlight ? 'bg-[#0066cc] text-white shadow-lg shadow-blue-500/40 ring-4 ring-blue-200 z-10' : 'bg-slate-200 text-slate-600 border border-slate-300 shadow-sm';
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
