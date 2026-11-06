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
<div class="book-detail-bg min-h-screen pb-16 pt-8">
    <div class="w-full mx-auto sm:px-6 lg:px-8 px-6">
        
        {{-- Back button & Breadcrumb Navigation --}}
        @php
            $previousUrl = url()->previous();
            
            // Validasi agar backUrl tidak mengarah ke dirinya sendiri, ke reader (/read), atau URL eksternal
            $isValidPrevious = !empty($previousUrl) 
                && $previousUrl !== url()->current() 
                && !str_contains($previousUrl, '/read') 
                && str_starts_with($previousUrl, url('/'));

            if ($isValidPrevious) {
                session(['opac_last_valid_back_url' => $previousUrl]);
            }

            $backUrl = session('opac_last_valid_back_url');

            // Fallback jika session kosong, invalid, atau mengarah ke /read
            if (empty($backUrl) || $backUrl === url()->current() || str_contains($backUrl, '/read') || !str_starts_with($backUrl, url('/'))) {
                $backUrl = session('opac_katalog_url', route('opac.katalog'));
            }

            // Tentukan label tombol kembali secara kontekstual
            $backLabel = __('Kembali ke Katalog');
            if (str_contains($backUrl, '/member')) {
                $backLabel = __('Kembali ke Portal');
            } elseif ($backUrl === route('opac.index') || rtrim($backUrl, '/') === rtrim(url('/'), '/')) {
                $backLabel = __('Kembali ke Beranda');
            }
        @endphp

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-8 pb-4 border-b border-slate-200/70">
            <a href="{{ $backUrl }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-indigo-600 font-semibold text-sm transition-colors no-underline group">
                <span class="w-8 h-8 rounded-lg bg-white shadow-xs border border-slate-200 flex items-center justify-center text-slate-500 group-hover:bg-indigo-50 group-hover:text-indigo-600 group-hover:border-indigo-200 transition-all">
                    <i class="bi bi-arrow-left text-base"></i>
                </span>
                <span>{{ $backLabel }}</span>
            </a>

            {{-- Breadcrumb trail --}}
            <nav class="flex items-center gap-2 text-xs font-medium text-slate-400 flex-wrap">
                <a href="{{ route('opac.index') }}" class="hover:text-slate-700 transition-colors no-underline">{{ __('Beranda') }}</a>
                <i class="bi bi-chevron-right text-[10px] text-slate-300"></i>
                <a href="{{ route('opac.katalog') }}" class="hover:text-slate-700 transition-colors no-underline">{{ __('Katalog') }}</a>
                <i class="bi bi-chevron-right text-[10px] text-slate-300"></i>
                <span class="text-slate-700 font-semibold truncate max-w-[200px] sm:max-w-xs">{{ $book->title }}</span>
            </nav>
        </div>

        <div class="flex flex-col lg:flex-row gap-12">
            
            {{-- Left: Cover --}}
            <div class="w-full lg:w-1/3 xl:w-1/4 relative">
                <div class="sticky top-28 flex flex-col gap-4">
                    <div class="w-full aspect-[2/3] rounded-2xl overflow-hidden shadow-[0_20px_40px_-15px_rgba(0,0,0,0.3)] bg-white">
                        @if($book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
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
                    @if($book->digital_file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->digital_file_path))
                        <a href="{{ route('opac.read', $book) }}" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition-colors shadow-sm text-center no-underline flex items-center justify-center gap-2 mb-3">
                            <i class="bi bi-book-half"></i> {{ __('Baca E-book / PDF') }}
                        </a>
                    @endif

                    @if($book->collection_type !== 'E-book')
                        @auth
                            <form action="{{ route('member.reservations.store', $book->id) }}" method="POST"
                                  data-confirm="{{ __('Pesan buku ini untuk disiapkan dan diambil di perpustakaan?') }}"
                                  data-confirm-title="{{ __('Pesan Buku Ini?') }}"
                                  data-confirm-subtitle="{{ __('Buku akan disiapkan oleh petugas perpustakaan dan dapat diambil di meja sirkulasi.') }}"
                                  data-confirm-button="{{ __('Ya, Pesan Buku') }}"
                                  data-confirm-cancel="{{ __('Batal') }}"
                                  data-confirm-icon="question">
                                @csrf
                                <button type="submit" class="w-full bg-[#e50914] hover:bg-red-700 text-white font-bold py-3 rounded-xl transition-colors shadow-sm text-center no-underline flex items-center justify-center gap-2" {{ $availableCount <= 0 ? 'disabled' : '' }}>
                                    @if($availableCount > 0)
                                        <i class="bi bi-book"></i> {{ __('Pinjam / Pesan Buku') }}
                                    @elseif($totalCount == 0)
                                        <i class="bi bi-x-circle"></i> {{ __('Stok Kosong') }}
                                    @else
                                        <i class="bi bi-clock-history"></i> {{ __('Buku Habis Dipinjam') }}
                                    @endif
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login.member') }}" class="w-full bg-[#e50914] hover:bg-red-700 text-white font-bold py-3 rounded-xl transition-colors shadow-sm text-center no-underline">
                                {{ __('Login untuk meminjam buku') }}
                            </a>
                        @endauth
                    @endif

                    <div class="flex gap-3">
                        <button class="flex-1 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold py-2.5 rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                            <i class="bi bi-bookmark"></i> {{ __('Favorit') }}
                        </button>
                        <button class="flex-1 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold py-2.5 rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                            <i class="bi bi-share"></i> {{ __('Bagikan') }}
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


                    
                    @if($book->collection_type !== 'E-book')
                        @if($availableCount > 0)
                            <span class="bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-bold px-3 py-1.5 rounded-full">
                                {{ $availableCount }} {{ __('Tersedia') }}
                            </span>
                        @elseif($totalCount == 0)
                            <span class="bg-slate-100 text-slate-600 border border-slate-200 text-xs font-bold px-3 py-1.5 rounded-full">
                                {{ __('Stok Kosong') }}
                            </span>
                        @else
                            <span class="bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold px-3 py-1.5 rounded-full">
                                {{ __('Dipinjam') }}
                            </span>
                        @endif
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
                    {{ __('Oleh') }} <span class="italic text-slate-800 font-semibold text-xl ml-1">{{ $book->authors->count() > 0 ? $book->authors->pluck('name')->join(', ') : __('Penulis Tidak Diketahui') }}</span>
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
                    <span class="text-slate-400 text-sm">({{ __('0 ulasan') }})</span>
                </div>

                <hr class="border-slate-200 mb-8 opacity-60">

                {{-- 4 Stat Cards --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-10">
                    <!-- Card 1 -->
                    <div class="bg-white rounded-2xl border border-slate-100/50 p-4 text-center shadow-sm flex flex-col items-center justify-center h-[120px] hover:shadow-md transition-shadow">
                        <i class="bi bi-book text-[#0066cc] text-[22px] mb-2 opacity-80"></i>
                        <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase mb-1">{{ __('Halaman') }}</span>
                        <span class="text-lg font-black text-slate-800">{{ $book->pages ?? '-' }}</span>
                    </div>
                    <!-- Card 2 -->
                    <div class="bg-white rounded-2xl border border-slate-100/50 p-4 text-center shadow-sm flex flex-col items-center justify-center h-[120px] hover:shadow-md transition-shadow">
                        <i class="bi bi-calendar-event text-[#0066cc] text-[22px] mb-2 opacity-80"></i>
                        <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase mb-1">{{ __('Tahun Terbit') }}</span>
                        <span class="text-lg font-black text-slate-800">{{ $book->publication_year ?? '-' }}</span>
                    </div>
                    <!-- Card 3 -->
                    @if($book->collection_type === 'E-book')
                    <div class="bg-white rounded-2xl border border-slate-100/50 p-4 text-center shadow-sm flex flex-col items-center justify-center h-[120px] hover:shadow-md transition-shadow">
                        <i class="bi bi-cloud-check text-[#0066cc] text-[22px] mb-2 opacity-80"></i>
                        <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase mb-1">{{ __('Akses') }}</span>
                        <span class="text-lg font-black text-slate-800">{{ __('Online') }}</span>
                    </div>
                    @else
                    <div class="bg-white rounded-2xl border border-slate-100/50 p-4 text-center shadow-sm flex flex-col items-center justify-center h-[120px] hover:shadow-md transition-shadow">
                        <i class="bi bi-arrow-repeat text-[#0066cc] text-[22px] mb-2 opacity-80"></i>
                        <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase mb-1">{{ __('Stok Tersedia') }}</span>
                        <span class="text-lg font-black text-slate-800">{{ $availableCount }} / {{ $totalCount }}</span>
                    </div>
                    @endif
                    <!-- Card 4 -->
                    <div class="bg-white rounded-2xl border border-slate-100/50 p-4 text-center shadow-sm flex flex-col items-center justify-center h-[120px] hover:shadow-md transition-shadow">
                        <i class="bi bi-star-fill text-[#0066cc] text-[22px] mb-2 opacity-80"></i>
                        <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase mb-1">{{ __('Rating') }}</span>
                        <span class="text-lg font-black text-slate-800">0.0<span class="text-sm text-slate-400 font-medium">/5</span></span>
                    </div>
                </div>

                {{-- Tabs for Sinopsis / Detail --}}
                <div x-data="{ tab: 'sinopsis', expanded: false }" class="mb-12">
                    {{-- Segmented Control --}}
                    <div class="bg-slate-100/70 p-1.5 rounded-xl flex mb-8 max-w-2xl border border-slate-200/50">
                        <button @click="tab = 'sinopsis'" :class="{'bg-white text-slate-800 shadow-sm': tab === 'sinopsis', 'text-slate-500 hover:text-slate-700': tab !== 'sinopsis'}" class="flex-1 py-2.5 font-bold text-sm rounded-lg transition-all outline-none">{{ __('Sinopsis') }}</button>
                        <button @click="tab = 'detail'" :class="{'bg-white text-slate-800 shadow-sm': tab === 'detail', 'text-slate-500 hover:text-slate-700': tab !== 'detail'}" class="flex-1 py-2.5 font-bold text-sm rounded-lg transition-all outline-none">{{ __('Data Buku') }}</button>
                    </div>

                    {{-- Tab Content --}}
                    <div class="text-slate-600 min-h-[200px]">
                        <div x-show="tab === 'sinopsis'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                            <h2 class="text-[22px] font-bold text-slate-800 mb-4">{{ __('Sinopsis') }}</h2>
                            @if($book->abstract)
                                <div class="relative">
                                    <div class="text-slate-600 leading-relaxed text-justify text-base overflow-hidden transition-all duration-300" :class="{'line-clamp-4 max-h-[110px]': !expanded, 'max-h-[1000px]': expanded}">
                                        <p class="m-0">{{ $book->abstract }}</p>
                                    </div>
                                    <div x-show="!expanded" class="absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-[#f6f0ea] to-transparent pointer-events-none"></div>
                                </div>
                                <button @click="expanded = !expanded" class="text-[#0066cc] font-bold text-sm mt-3 flex items-center hover:text-blue-800 transition-colors">
                                    <span x-text="expanded ? '{{ __('Tutup') }}' : '{{ __('Lihat selengkapnya') }}'"></span> 
                                    <i class="bi bi-chevron-down ml-1 transition-transform" :class="{'rotate-180': expanded}"></i>
                                </button>
                            @else
                                <p class="text-slate-400 italic m-0">{{ __('Tidak ada sinopsis/abstrak untuk buku ini.') }}</p>
                            @endif
                        </div>

                        <div x-show="tab === 'detail'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                            <h2 class="text-[22px] font-bold text-slate-800 mb-4">{{ __('Data Buku') }}</h2>
                            <table class="w-full text-left text-slate-600 m-0">
                                <tbody class="divide-y divide-slate-200/60">
                                    <tr>
                                        <td class="py-3 font-semibold w-1/3 sm:w-1/4">{{ __('Penerbit') }}</td>
                                        <td class="py-3">{{ $book->publisher?->name ?? '-' }} {{ $book->publisher?->city ? '('.$book->publisher->city.')' : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 font-semibold">{{ __('ISBN') }}</td>
                                        <td class="py-3 font-mono font-bold text-indigo-600">{{ $book->isbn ?? '-' }} @if($book->isbn13) / {{ $book->isbn13 }} @endif</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 font-semibold">{{ __('Bahasa') }}</td>
                                        <td class="py-3">{{ $book->language == 'id' ? __('Bahasa Indonesia') : __('Bahasa Asing') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 font-semibold">{{ __('Edisi / Seri') }}</td>
                                        <td class="py-3">{{ $book->edition ?? '-' }} @if($book->series_title) ({{ __('Seri') }}: {{ $book->series_title }} #{{ $book->series_number }}) @endif</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 font-semibold">{{ __('Deskripsi Fisik') }}</td>
                                        <td class="py-3">{{ $book->pages ?? '-' }} {{ __('halaman') }}; {{ $book->dimensions ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 font-semibold">{{ __('Klasifikasi (DDC)') }}</td>
                                        <td class="py-3">{{ $book->ddc ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 font-semibold">{{ __('Nomor Panggil') }}</td>
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
                
                @if($distinctLocations->count() > 0 && $book->collection_type !== 'E-book')
                <div class="bg-gradient-to-br from-[#0066cc] to-indigo-700 rounded-2xl shadow-md p-6 text-white text-center relative overflow-hidden flex flex-col justify-center min-h-[160px] max-w-2xl">
                    <i class="bi bi-map absolute -right-4 -bottom-4 text-[120px] text-white/10 rotate-[-15deg]"></i>
                    <div class="relative z-10">
                        <h4 class="font-bold text-lg mb-1">{{ __('Cari Buku di Rak') }}</h4>
                        <p class="text-blue-100 text-[13px] mb-4 leading-relaxed">{{ __('Lihat denah rak perpustakaan untuk memudahkan pencarian.') }}</p>
                        <button type="button" onclick="document.getElementById('mapModal').classList.remove('hidden')" class="w-full max-w-[200px] mx-auto bg-white text-[#0066cc] font-bold py-2.5 rounded-xl hover:bg-slate-50 transition-colors shadow-sm text-sm flex items-center justify-center">
                            <i class="bi bi-geo-alt-fill mr-1.5"></i> {{ __('Lihat Denah') }}
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Koleksi Serupa --}}
        @if($relatedBooks->count() > 0)
        <div class="mt-16 pt-10 border-t border-slate-200/60">
            <h3 class="text-[22px] font-bold text-slate-800 mb-8 flex items-center gap-2"><i class="bi bi-bookmarks-fill text-amber-500"></i>{{ __('Koleksi Serupa') }}</h3>
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
@push('modals')
@if(isset($distinctLocations) && $distinctLocations->count() > 0)
<div id="mapModal" class="fixed inset-0 z-[100] hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 pt-20 sm:pt-6 overflow-y-auto" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[88vh] my-auto border border-slate-200" onclick="event.stopPropagation()">
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/90 shrink-0">
            <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2 mb-0">
                <i class="bi bi-map mr-1 text-[#0066cc]"></i>{{ __('Denah Lokasi Rak Perpustakaan') }}
            </h3>
            <button type="button" onclick="document.getElementById('mapModal').classList.add('hidden')" class="w-9 h-9 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200/70 transition-colors flex items-center justify-center text-xl" title="{{ __('Tutup') }}">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-6 overflow-y-auto bg-slate-100 flex-1 relative">
            {{-- Legend --}}
            <div class="bg-white px-4 py-3 rounded-xl shadow-sm inline-flex flex-wrap items-center gap-4 mb-6 text-sm font-medium border border-slate-200">
                <div class="flex items-center gap-2"><div class="w-4 h-4 bg-[#0066cc] rounded ring-4 ring-blue-200 animate-pulse"></div> {{ __('Lokasi Buku Ini') }}</div>
                <div class="flex items-center gap-2"><div class="w-4 h-4 bg-slate-300 rounded"></div> {{ __('Rak Lainnya') }}</div>
                <div class="flex items-center gap-2"><div class="w-4 h-4 bg-amber-200 border-2 border-amber-400 rounded"></div> {{ __('Meja Layanan') }}</div>
            </div>

            {{-- Floor Plan Container --}}
            <div class="overflow-x-auto pb-2">
                <div class="bg-white border-2 border-slate-300 rounded-lg p-8 shadow-inner min-w-[700px] max-w-[800px] mx-auto relative" style="height: 500px;">
                    <!-- Meja Layanan -->
                    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 w-48 h-16 bg-amber-100 border-2 border-amber-400 rounded-lg flex items-center justify-center text-amber-800 font-bold shadow-sm">
                        {{ __('Meja Layanan / Sirkulasi') }}
                    </div>
                    <!-- Pintu Masuk -->
                    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-32 h-2 bg-slate-300 rounded-t-lg flex justify-center -mb-2">
                        <span class="text-[10px] font-bold text-slate-500 -mt-4 uppercase bg-white px-1">{{ __('Pintu Masuk') }}</span>
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
                    {!! $renderShelf('000', __('Karya Umum'), '40px', '40px') !!}
                    {!! $renderShelf('100', __('Filsafat & Psikologi'), '120px', '40px') !!}
                    {!! $renderShelf('200', __('Agama'), '200px', '40px') !!}
                    {!! $renderShelf('300', __('Ilmu Sosial'), '280px', '40px') !!}
                    {!! $renderShelf('400', __('Bahasa'), '360px', '40px') !!}

                    <!-- Rak Sisi Kanan (500 - 900) -->
                    {!! $renderShelfRight('500', __('Ilmu Murni'), '40px', '40px') !!}
                    {!! $renderShelfRight('600', __('Ilmu Terapan'), '120px', '40px') !!}
                    {!! $renderShelfRight('700', __('Seni & Olahraga'), '200px', '40px') !!}
                    {!! $renderShelfRight('800', __('Kesusastraan'), '280px', '40px') !!}
                    {!! $renderShelfRight('900', __('Sejarah & Geografi'), '360px', '40px') !!}
                    
                    <!-- Kategori Tambahan di Tengah (F, R, SL) -->
                    {!! $renderShelf('F', __('Fiksi'), '80px', '300px', '160px', '40px') !!}
                    {!! $renderShelf('R', __('Referensi'), '160px', '300px', '160px', '40px') !!}
                    {!! $renderShelf('SL', __('Buku Paket'), '240px', '300px', '160px', '40px') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endpush

@push('scripts')
<script>
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('mapModal');
            if (modal && !modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
            }
        }
    });
</script>
@endpush

@endsection
