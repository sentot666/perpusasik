@extends('layouts.opac')

@section('title', $book->title . ' - OPAC Detail')
@section('no_footer', true)

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
<div class="bg-[#f1f4f7] min-h-screen pb-20 pt-8" 
     x-data="{ 
         mapModalOpen: false,
         openMap() {
             this.mapModalOpen = true;
             document.body.classList.add('map-modal-active');
             window.dispatchEvent(new CustomEvent('map-modal-toggle', { detail: { open: true } }));
         },
         closeMap() {
             this.mapModalOpen = false;
             document.body.classList.remove('map-modal-active');
             window.dispatchEvent(new CustomEvent('map-modal-toggle', { detail: { open: false } }));
         }
     }"
     x-init="$watch('mapModalOpen', val => { if(val) openMap(); else closeMap(); })">
    <div class="w-full mx-auto sm:px-6 lg:px-8 px-6">
        
        {{-- Back button (Meta Pill Ghost) --}}
        @php
            $backUrl = url()->previous();
            if ($backUrl == url()->current() || !str_contains($backUrl, url('/'))) {
                $backUrl = route('opac.katalog');
            }
        @endphp
        <a href="{{ $backUrl }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white text-[#1c1e21] border border-[#dee3e9] hover:bg-[#dee3e9] text-xs font-bold transition-all shadow-sm mb-8 no-underline">
            <i class="bi bi-arrow-left"></i> Kembali ke Katalog
        </a>

        <div class="flex flex-col lg:flex-row gap-10">
            
            {{-- Left: Cover & Action Rail (Meta PDP Left Rail) --}}
            <div class="w-full lg:w-1/3 xl:w-1/4 relative">
                <div class="sticky top-28 flex flex-col gap-4">
                    <div class="bg-white rounded-3xl border border-[#dee3e9] p-4 shadow-sm">
                        <div class="w-full aspect-[2/3] rounded-2xl overflow-hidden shadow-md bg-white border border-[#dee3e9]/60">
                            @if($book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
                                <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-full h-full object-cover" alt="Cover {{ $book->title }}">
                            @else
                                @php
                                    $colors = ['from-blue-600 to-indigo-800', 'from-emerald-500 to-teal-800', 'from-orange-500 to-red-700', 'from-purple-600 to-pink-800', 'from-cyan-600 to-blue-800'];
                                    $gradient = $colors[crc32($book->title) % count($colors)];
                                    $initials = strtoupper(substr($book->title, 0, 2));
                                @endphp
                                <div class="w-full h-full bg-gradient-to-br {{ $gradient }} flex items-center justify-center text-white">
                                    <span class="text-6xl font-black opacity-80">{{ $initials }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Action Buttons (Meta Pill CTA System) --}}
                    @if($book->digital_file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->digital_file_path))
                        <a href="{{ route('opac.read', $book) }}" class="w-full bg-black hover:bg-[#444950] text-white font-bold py-3.5 px-6 rounded-full transition-all shadow-sm text-center no-underline flex items-center justify-center gap-2 mb-2 text-sm cursor-pointer">
                            <i class="bi bi-book-half"></i> Baca E-book / PDF
                        </a>
                    @endif

                    @if($book->collection_type !== 'E-book')
                        @auth
                            <form action="{{ route('member.reservations.store', $book->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-[#0064e0] hover:bg-[#0457cb] active:bg-[#0344a1] text-white font-bold py-3.5 px-6 rounded-full transition-all shadow-md shadow-blue-500/20 text-center no-underline flex items-center justify-center gap-2 text-sm cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" {{ $availableCount <= 0 ? 'disabled' : '' }} onclick="return confirm('Apakah Anda yakin ingin memesan/mereservasi buku ini?')">
                                    @if($availableCount > 0)
                                        <i class="bi bi-bookmark-plus"></i> Pinjam / Pesan Buku
                                    @elseif($totalCount == 0)
                                        <i class="bi bi-x-circle"></i> Stok Kosong
                                    @else
                                        <i class="bi bi-clock-history"></i> Buku Habis Dipinjam
                                    @endif
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="w-full bg-[#0064e0] hover:bg-[#0457cb] text-white font-bold py-3.5 px-6 rounded-full transition-all shadow-md shadow-blue-500/20 text-center no-underline flex items-center justify-center gap-2 text-sm">
                                <i class="bi bi-box-arrow-in-right"></i> Login untuk Meminjam
                            </a>
                        @endauth
                    @endif

                    {{-- Utility Actions --}}
                    <div class="flex gap-2">
                        <button class="flex-1 bg-white border border-[#ced0d4] hover:bg-[#f1f4f7] text-[#1c1e21] font-bold py-2.5 rounded-full transition-all shadow-sm flex items-center justify-center gap-2 text-xs cursor-pointer">
                            <i class="bi bi-bookmark"></i> Favorit
                        </button>
                        <button class="flex-1 bg-white border border-[#ced0d4] hover:bg-[#f1f4f7] text-[#1c1e21] font-bold py-2.5 rounded-full transition-all shadow-sm flex items-center justify-center gap-2 text-xs cursor-pointer">
                            <i class="bi bi-share"></i> Bagikan
                        </button>
                    </div>
                </div>
            </div>

            {{-- Right: Details & Metadata (Meta PDP Detail) --}}
            <div class="w-full lg:w-2/3 xl:w-3/4 flex flex-col">
                
                {{-- Badges --}}
                <div class="flex flex-wrap items-center gap-2.5 mb-4">
                    @if($book->subjects->count() > 0)
                        <span class="bg-[#0064e0] text-white text-[11px] font-bold px-3.5 py-1 rounded-full uppercase tracking-wider shadow-sm">
                            {{ $book->subjects->first()->name }}
                        </span>
                    @else
                        <span class="bg-[#0064e0] text-white text-[11px] font-bold px-3.5 py-1 rounded-full uppercase tracking-wider shadow-sm">
                            {{ $book->collection_type }}
                        </span>
                    @endif

                    @if($book->collection_type !== 'E-book')
                        @if($availableCount > 0)
                            <span class="bg-[#31a24c] text-white text-[11px] font-bold px-3.5 py-1 rounded-full uppercase tracking-wider shadow-sm">
                                <i class="bi bi-check-circle mr-1"></i> {{ $availableCount }} Tersedia
                            </span>
                        @elseif($totalCount == 0)
                            <span class="bg-[#f7b928] text-[#0a1317] text-[11px] font-bold px-3.5 py-1 rounded-full uppercase tracking-wider shadow-sm">
                                Stok Kosong
                            </span>
                        @else
                            <span class="bg-[#e41e3f] text-white text-[11px] font-bold px-3.5 py-1 rounded-full uppercase tracking-wider shadow-sm">
                                Sedang Dipinjam
                            </span>
                        @endif
                    @endif
                </div>

                {{-- Title (Optimistic / Inter Display) --}}
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#0a1317] mb-2 tracking-tight leading-tight">
                    {{ $book->title }}
                </h1>
                @if($book->subtitle)
                    <h2 class="text-lg text-[#5d6c7b] mb-2 font-normal">{{ $book->subtitle }}</h2>
                @endif

                {{-- Author Byline --}}
                <div class="text-sm sm:text-base text-[#5d6c7b] mb-6">
                    Penulis: <span class="font-bold text-[#0a1317] ml-1">{{ $book->authors->count() > 0 ? $book->authors->pluck('name')->join(', ') : 'Penulis Tidak Diketahui' }}</span>
                </div>

                <hr class="border-[#dee3e9] mb-8">

                {{-- 4 Stat Tiles (Meta card-icon-feature) --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <!-- Halaman -->
                    <div class="bg-white rounded-2xl border border-[#dee3e9] p-4 text-center shadow-sm flex flex-col items-center justify-center h-[110px] hover:shadow-md transition-all">
                        <i class="bi bi-book text-[#0064e0] text-xl mb-1.5"></i>
                        <span class="text-[10px] text-[#8595a4] font-bold tracking-wider uppercase mb-1">Halaman</span>
                        <span class="text-base font-extrabold text-[#0a1317]">{{ $book->pages ?? '-' }}</span>
                    </div>
                    <!-- Tahun -->
                    <div class="bg-white rounded-2xl border border-[#dee3e9] p-4 text-center shadow-sm flex flex-col items-center justify-center h-[110px] hover:shadow-md transition-all">
                        <i class="bi bi-calendar-event text-[#0064e0] text-xl mb-1.5"></i>
                        <span class="text-[10px] text-[#8595a4] font-bold tracking-wider uppercase mb-1">Tahun Terbit</span>
                        <span class="text-base font-extrabold text-[#0a1317]">{{ $book->publication_year ?? '-' }}</span>
                    </div>
                    <!-- Stok / Akses -->
                    @if($book->collection_type === 'E-book')
                    <div class="bg-white rounded-2xl border border-[#dee3e9] p-4 text-center shadow-sm flex flex-col items-center justify-center h-[110px] hover:shadow-md transition-all">
                        <i class="bi bi-cloud-check text-[#31a24c] text-xl mb-1.5"></i>
                        <span class="text-[10px] text-[#8595a4] font-bold tracking-wider uppercase mb-1">Akses</span>
                        <span class="text-base font-extrabold text-[#0a1317]">Online Digital</span>
                    </div>
                    @else
                    <div class="bg-white rounded-2xl border border-[#dee3e9] p-4 text-center shadow-sm flex flex-col items-center justify-center h-[110px] hover:shadow-md transition-all">
                        <i class="bi bi-collection text-[#0064e0] text-xl mb-1.5"></i>
                        <span class="text-[10px] text-[#8595a4] font-bold tracking-wider uppercase mb-1">Stok Eksemplar</span>
                        <span class="text-base font-extrabold text-[#0a1317]">{{ $availableCount }} / {{ $totalCount }}</span>
                    </div>
                    @endif
                    <!-- Rating / Status -->
                    <div class="bg-white rounded-2xl border border-[#dee3e9] p-4 text-center shadow-sm flex flex-col items-center justify-center h-[110px] hover:shadow-md transition-all">
                        <i class="bi bi-check-circle text-[#31a24c] text-xl mb-1.5"></i>
                        <span class="text-[10px] text-[#8595a4] font-bold tracking-wider uppercase mb-1">Status</span>
                        <span class="text-base font-extrabold text-[#0a1317]">{{ $book->is_active ? 'Katalog Aktif' : 'Arsip' }}</span>
                    </div>
                </div>

                {{-- Tabs for Sinopsis / Tech Specs Table (Meta Pill Tabs) --}}
                <div x-data="{ tab: 'sinopsis', expanded: false }" class="mb-10 bg-white rounded-3xl border border-[#dee3e9] p-6 md:p-8 shadow-sm">
                    {{-- Segmented Pill Tabs --}}
                    <div class="bg-[#f1f4f7] p-1 rounded-full inline-flex mb-6 border border-[#dee3e9]">
                        <button @click="tab = 'sinopsis'" :class="{'bg-[#0a1317] text-white shadow-sm': tab === 'sinopsis', 'text-[#5d6c7b] hover:text-[#0a1317]': tab !== 'sinopsis'}" class="px-5 py-2 font-bold text-xs uppercase tracking-wider rounded-full transition-all outline-none cursor-pointer">Sinopsis</button>
                        <button @click="tab = 'detail'" :class="{'bg-[#0a1317] text-white shadow-sm': tab === 'detail', 'text-[#5d6c7b] hover:text-[#0a1317]': tab !== 'detail'}" class="px-5 py-2 font-bold text-xs uppercase tracking-wider rounded-full transition-all outline-none cursor-pointer">Data Teknis & Spesifikasi</button>
                    </div>

                    {{-- Tab Content --}}
                    <div class="text-[#1c1e21]">
                        <div x-show="tab === 'sinopsis'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <h3 class="text-lg font-bold text-[#0a1317] mb-3">Sinopsis & Ringkasan</h3>
                            @if($book->abstract)
                                <div class="relative">
                                    <div class="text-[#5d6c7b] leading-relaxed text-sm overflow-hidden transition-all duration-300" :class="{'line-clamp-4 max-h-[110px]': !expanded, 'max-h-[1000px]': expanded}">
                                        <p class="m-0">{{ $book->abstract }}</p>
                                    </div>
                                </div>
                                <button @click="expanded = !expanded" class="text-[#0064e0] font-bold text-xs mt-3 flex items-center hover:underline cursor-pointer">
                                    <span x-text="expanded ? 'Tutup ringkasan' : 'Lihat selengkapnya'"></span> 
                                    <i class="bi bi-chevron-down ml-1 transition-transform" :class="{'rotate-180': expanded}"></i>
                                </button>
                            @else
                                <p class="text-[#8595a4] italic text-sm m-0">Tidak ada sinopsis/abstrak untuk buku ini.</p>
                            @endif
                        </div>

                        {{-- Tech Specs Table (DESIGN.md tech-specs-table) --}}
                        <div x-show="tab === 'detail'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <h3 class="text-lg font-bold text-[#0a1317] mb-3">Spesifikasi Bibliografi</h3>
                            <div class="border border-[#dee3e9] rounded-2xl overflow-hidden">
                                <table class="w-full text-left text-sm m-0">
                                    <tbody class="divide-y divide-[#dee3e9]">
                                        <tr class="bg-[#f1f4f7]/50">
                                            <td class="py-3 px-4 font-bold text-[#0a1317] w-1/3">Penerbit</td>
                                            <td class="py-3 px-4 text-[#5d6c7b]">{{ $book->publisher?->name ?? '-' }} {{ $book->publisher?->city ? '('.$book->publisher->city.')' : '' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-3 px-4 font-bold text-[#0a1317]">ISBN</td>
                                            <td class="py-3 px-4 font-mono font-bold text-[#0064e0]">{{ $book->isbn ?? '-' }} @if($book->isbn13) / {{ $book->isbn13 }} @endif</td>
                                        </tr>
                                        <tr class="bg-[#f1f4f7]/50">
                                            <td class="py-3 px-4 font-bold text-[#0a1317]">Bahasa</td>
                                            <td class="py-3 px-4 text-[#5d6c7b]">{{ $book->language == 'id' ? 'Bahasa Indonesia' : 'Bahasa Asing' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-3 px-4 font-bold text-[#0a1317]">Edisi / Seri</td>
                                            <td class="py-3 px-4 text-[#5d6c7b]">{{ $book->edition ?? '-' }} @if($book->series_title) (Seri: {{ $book->series_title }} #{{ $book->series_number }}) @endif</td>
                                        </tr>
                                        <tr class="bg-[#f1f4f7]/50">
                                            <td class="py-3 px-4 font-bold text-[#0a1317]">Deskripsi Fisik</td>
                                            <td class="py-3 px-4 text-[#5d6c7b]">{{ $book->pages ?? '-' }} halaman; {{ $book->dimensions ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-3 px-4 font-bold text-[#0a1317]">Klasifikasi (DDC)</td>
                                            <td class="py-3 px-4 text-[#5d6c7b]">{{ $book->ddc ?? '-' }}</td>
                                        </tr>
                                        <tr class="bg-[#f1f4f7]/50">
                                            <td class="py-3 px-4 font-bold text-[#0a1317]">Nomor Panggil</td>
                                            <td class="py-3 px-4"><code class="bg-white border border-[#dee3e9] px-2.5 py-1 rounded-md text-[#0a1317] font-bold text-xs">{{ $book->call_number ?? '-' }}</code></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Lokasi Section (Meta Promo Strip Style) --}}
                @php
                    $distinctLocations = $book->items->pluck('location')->filter()->unique('id');
                @endphp
                
                @if($distinctLocations->count() > 0 && $book->collection_type !== 'E-book')
                <div class="bg-[#0a1317] rounded-3xl p-6 md:p-8 text-white relative overflow-hidden flex flex-col sm:flex-row items-center justify-between gap-6 shadow-md border border-[#dee3e9]">
                    <div class="relative z-10 text-center sm:text-left">
                        <span class="text-xs font-bold text-[#0091ff] uppercase tracking-wider">Lokasi Fisik</span>
                        <h4 class="font-extrabold text-xl text-white mt-1 mb-1">Cari Buku di Rak Perpustakaan</h4>
                        <p class="text-[#ced0d4] text-xs max-w-md m-0">Lihat denah rak dan letak spesifik eksemplar buku ini secara visual.</p>
                    </div>
                    <button type="button" @click="openMap()" class="btn-pill-buy whitespace-nowrap shrink-0 cursor-pointer">
                        <i class="bi bi-geo-alt-fill"></i> Buka Denah Rak
                    </button>
                </div>
                @endif
            </div>
        </div>

        {{-- Koleksi Serupa --}}
        @if($relatedBooks->count() > 0)
        <div class="mt-16 pt-10 border-t border-[#dee3e9]">
            <h3 class="text-xl font-extrabold text-[#0a1317] mb-8 flex items-center gap-2">
                <i class="bi bi-bookmarks-fill text-[#0064e0]"></i> Koleksi Serupa
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                @foreach($relatedBooks as $rel)
                    <x-book-card :book="$rel" />
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Peta Denah Rak Modal --}}
    @if(isset($distinctLocations) && $distinctLocations->count() > 0)
    <div id="mapModal"
         x-show="mapModalOpen"
         x-cloak
         style="display: none;"
         class="fixed inset-0 z-[100] bg-black/70 backdrop-blur-md flex items-center justify-center p-4 sm:p-6"
         @click.self="closeMap()"
         @keydown.escape.window="closeMap()">
        
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[85vh] border border-[#dee3e9] animate-in fade-in zoom-in-95 duration-200">
            <!-- Modal Header -->
            <div class="px-6 py-5 border-b border-[#dee3e9] flex items-center justify-between bg-white shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#0064e0]/10 text-[#0064e0] flex items-center justify-center font-bold text-lg">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-[#0a1317] m-0">Denah Rak Perpustakaan</h3>
                        <p class="text-xs text-[#5d6c7b] m-0">Panduan letak spesifik rak fisik buku ini</p>
                    </div>
                </div>
                <button type="button" @click="closeMap()" class="w-9 h-9 rounded-full bg-[#f1f4f7] hover:bg-[#dee3e9] text-[#5d6c7b] hover:text-[#0a1317] flex items-center justify-center transition-colors cursor-pointer border-0">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto bg-[#f1f4f7]/40 flex-1 relative">
                {{-- Legend --}}
                <div class="bg-white px-5 py-3 rounded-full shadow-sm inline-flex flex-wrap items-center gap-5 mb-6 text-xs font-bold text-[#1c1e21] border border-[#dee3e9]">
                    <div class="flex items-center gap-2">
                        <div class="w-3.5 h-3.5 bg-[#0064e0] rounded ring-4 ring-blue-100 animate-pulse"></div> 
                        <span>Lokasi Buku Ini</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3.5 h-3.5 bg-[#dee3e9] rounded"></div> 
                        <span class="text-[#5d6c7b]">Rak Lainnya</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3.5 h-3.5 bg-amber-200 border border-amber-400 rounded"></div> 
                        <span class="text-[#5d6c7b]">Meja Layanan</span>
                    </div>
                </div>

                {{-- Floor Plan Container --}}
                <div class="overflow-x-auto pb-4">
                    <div class="bg-white border border-[#dee3e9] rounded-2xl p-8 shadow-sm min-w-[720px] max-w-[800px] mx-auto relative" style="height: 480px;">
                        <!-- Meja Layanan -->
                        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 w-48 h-14 bg-amber-50 border-2 border-amber-300 rounded-xl flex items-center justify-center text-amber-900 font-bold text-xs shadow-sm">
                            <i class="bi bi-person-workspace mr-1.5"></i> Meja Layanan / Sirkulasi
                        </div>
                        <!-- Pintu Masuk -->
                        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-32 h-1 bg-[#0a1317] rounded-t flex justify-center">
                            <span class="text-[9px] font-extrabold text-[#5d6c7b] -mt-4 uppercase bg-white px-2 tracking-wider">Pintu Masuk</span>
                        </div>

                        @php
                            $highlightCodes = $distinctLocations->pluck('code')->toArray();
                            
                            $renderShelf = function($code, $label, $top, $left, $width = '140px', $height = '54px') use ($highlightCodes) {
                                $isHighlight = in_array($code, $highlightCodes);
                                $bgClass = $isHighlight ? 'bg-[#0064e0] text-white shadow-lg shadow-blue-500/30 ring-4 ring-blue-200 z-10' : 'bg-[#f1f4f7] text-[#5d6c7b] border border-[#dee3e9] shadow-xs';
                                $pulseClass = $isHighlight ? 'animate-pulse' : '';
                                
                                return '<div class="absolute flex flex-col items-center justify-center rounded-xl font-bold text-sm transition-all duration-300 text-center px-2 ' . $bgClass . ' ' . $pulseClass . '" style="top: '.$top.'; left: '.$left.'; width: '.$width.'; height: '.$height.';">
                                    <span class="text-[9px] uppercase tracking-wider opacity-75 font-semibold leading-tight">Rak '.$code.'</span>
                                    <span class="text-xs font-bold leading-tight truncate w-full">'.$label.'</span>
                                </div>';
                            };

                            $renderShelfRight = function($code, $label, $top, $right, $width = '140px', $height = '54px') use ($highlightCodes) {
                                $isHighlight = in_array($code, $highlightCodes);
                                $bgClass = $isHighlight ? 'bg-[#0064e0] text-white shadow-lg shadow-blue-500/30 ring-4 ring-blue-200 z-10' : 'bg-[#f1f4f7] text-[#5d6c7b] border border-[#dee3e9] shadow-xs';
                                $pulseClass = $isHighlight ? 'animate-pulse' : '';
                                
                                return '<div class="absolute flex flex-col items-center justify-center rounded-xl font-bold text-sm transition-all duration-300 text-center px-2 ' . $bgClass . ' ' . $pulseClass . '" style="top: '.$top.'; right: '.$right.'; width: '.$width.'; height: '.$height.';">
                                    <span class="text-[9px] uppercase tracking-wider opacity-75 font-semibold leading-tight">Rak '.$code.'</span>
                                    <span class="text-xs font-bold leading-tight truncate w-full">'.$label.'</span>
                                </div>';
                            };
                        @endphp

                        <!-- Rak Sisi Kiri (000 - 400) -->
                        {!! $renderShelf('000', 'Karya Umum', '30px', '30px') !!}
                        {!! $renderShelf('100', 'Filsafat & Psikologi', '100px', '30px') !!}
                        {!! $renderShelf('200', 'Agama', '170px', '30px') !!}
                        {!! $renderShelf('300', 'Ilmu Sosial', '240px', '30px') !!}
                        {!! $renderShelf('400', 'Bahasa', '310px', '30px') !!}

                        <!-- Rak Sisi Kanan (500 - 900) -->
                        {!! $renderShelfRight('500', 'Ilmu Murni', '30px', '30px') !!}
                        {!! $renderShelfRight('600', 'Ilmu Terapan', '100px', '30px') !!}
                        {!! $renderShelfRight('700', 'Seni & Olahraga', '170px', '30px') !!}
                        {!! $renderShelfRight('800', 'Kesusastraan', '240px', '30px') !!}
                        {!! $renderShelfRight('900', 'Sejarah & Geografi', '310px', '30px') !!}
                        
                        <!-- Kategori Tambahan di Tengah (F, R, SL) -->
                        {!! $renderShelf('F', 'Fiksi', '70px', '280px', '160px', '46px') !!}
                        {!! $renderShelf('R', 'Referensi', '140px', '280px', '160px', '46px') !!}
                        {!! $renderShelf('SL', 'Buku Paket', '210px', '280px', '160px', '46px') !!}
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-white border-t border-[#dee3e9] flex justify-end shrink-0">
                <button type="button" @click="closeMap()" class="btn-pill-secondary text-xs cursor-pointer">
                    Tutup Peta
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
