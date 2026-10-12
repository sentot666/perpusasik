@extends('layouts.opac')

@section('title', __('Katalog Perpustakaan'))

@push('styles')
<style>
    #katalog-hero {
        position: relative;
        background: linear-gradient(135deg, #0a0f2e 0%, #152664 50%, #0d1b4b 100%);
        overflow: hidden;
    }
    
    .katalog-particles-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        opacity: 0.5;
        background-image: 
            radial-gradient(circle at 15% 50%, rgba(255,255,255,0.08) 0%, transparent 50%),
            radial-gradient(circle at 85% 30%, rgba(255,255,255,0.08) 0%, transparent 50%);
    }

    .katalog-mask-a {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 80px;
        background-color: #eff6ff; /* Matches bg-blue-50 of body */
        clip-path: ellipse(100% 100% at 50% 100%);
        z-index: 5;
    }
</style>
@endpush

@section('content')
@if($tab == 'home')
    {{-- Hero Section (Pusdiklat & UBSI Inspired, matching Beranda style) --}}
    {{-- Hero Section (Meta Hero Band Style) --}}
    <div id="katalog-hero" class="pt-32 pb-36 md:pt-40 md:pb-44 relative bg-[#0a1317] text-white overflow-hidden">
        {{-- Ambient Glows --}}
        <div class="absolute -right-24 -top-24 w-96 h-96 bg-[#0064e0]/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-[#0457cb]/20 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 w-full px-6 sm:px-8 lg:px-12 flex flex-col lg:flex-row items-center justify-between gap-12 lg:gap-8">
            
            {{-- Left: Text, Pill Search, and CTA Buttons --}}
            <div class="w-full lg:w-7/12 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md text-white text-xs font-bold uppercase tracking-wider mb-5 border border-white/15 shadow-sm">
                    <i class="bi bi-book-half text-[#0091ff]"></i> Katalog Digital Perpustakaan
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-[1.18] tracking-tight mb-4">
                    Jelajahi Koleksi <br class="hidden sm:inline">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#0091ff] via-sky-200 to-white">Katalog Tanpa Batas</span>
                </h1>
                
                <p class="text-[#ced0d4] text-sm sm:text-base md:text-lg mb-8 leading-relaxed max-w-xl">
                    Akses ribuan koleksi buku cetak, referensi belajar, dan e-book perpustakaan kapan saja dan di mana saja.
                </p>

                {{-- Pill Search Bar (Meta Signature Capsule) --}}
                <form action="{{ route('opac.katalog') }}" method="GET" class="w-full max-w-xl mb-8 relative z-30 mx-auto lg:mx-0" x-data="autocompleteSearch('{{ addslashes(request('q')) }}')" @click.away="isOpen = false">
                    <input type="hidden" name="tab" value="koleksi">
                    <div class="bg-white rounded-full p-1.5 sm:p-2 flex items-center shadow-2xl border border-white/20 relative">
                        <span class="pl-4 text-[#8595a4]">
                            <i class="bi bi-search text-base"></i>
                        </span>
                        <input type="text" name="q" placeholder="Cari buku, penulis, topik, atau ISBN..." class="flex-1 min-w-[120px] px-3 sm:px-4 py-2 sm:py-2.5 text-[#1c1e21] text-sm outline-none border-0 rounded-full placeholder:text-[#8595a4]" x-model="query" @input.debounce.300ms="fetchSuggestions" @focus="if(query.length > 1) isOpen = true" autocomplete="off">
                        
                        <div class="relative flex items-center border-l border-[#dee3e9] pl-2 pr-1 hidden sm:flex">
                            <select name="search_by" class="px-3 py-2 bg-transparent text-[#1c1e21] outline-none border-0 text-xs font-bold cursor-pointer appearance-none pr-6">
                                <option value="title">Judul</option>
                                <option value="author">Penulis</option>
                                <option value="subject">Topik</option>
                            </select>
                            <i class="bi bi-chevron-down text-[10px] text-[#8595a4] absolute right-2 pointer-events-none"></i>
                        </div>

                        <button type="submit" x-ref="submitBtn" class="bg-[#0064e0] hover:bg-[#0457cb] text-white w-10 h-10 sm:w-11 sm:h-11 rounded-full flex items-center justify-center transition-all duration-200 hover:scale-105 shadow-md flex-shrink-0 cursor-pointer" title="Cari Buku">
                            <i class="bi bi-arrow-right text-lg"></i>
                        </button>
                    </div>

                    {{-- Autocomplete Dropdown --}}
                    <div x-show="isOpen && suggestions.length > 0" x-transition.opacity class="absolute top-full left-0 right-0 mt-3 bg-white rounded-2xl shadow-2xl border border-[#dee3e9] overflow-hidden z-[100]" style="display: none;">
                        <template x-for="item in suggestions" :key="item.text + item.type">
                            <button type="button" @click="selectSuggestion(item)" class="w-full text-left px-5 py-3 hover:bg-[#f1f4f7] border-b border-[#dee3e9]/50 last:border-0 flex items-center justify-between group transition-colors cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[#f1f4f7] text-[#0064e0] flex items-center justify-center group-hover:bg-[#0064e0] group-hover:text-white transition-colors">
                                        <i :class="'bi ' + item.icon"></i>
                                    </div>
                                    <span class="text-[#1c1e21] font-semibold text-sm truncate" x-text="item.text"></span>
                                </div>
                                <span class="text-[10px] uppercase font-bold tracking-wider text-[#8595a4] group-hover:text-[#0064e0] flex-shrink-0 ml-2" x-text="item.type"></span>
                            </button>
                        </template>
                    </div>
                </form>

                {{-- CTA Buttons (Meta Dual Pill System) --}}
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4">
                    <a href="{{ route('opac.katalog', ['tab' => 'koleksi']) }}" class="bg-white text-[#0a1317] hover:bg-[#dee3e9] font-bold py-3 px-8 rounded-full shadow-lg transition-all text-sm flex items-center gap-2 no-underline hover:scale-[1.02]">
                        <i class="bi bi-grid-fill"></i> Jelajahi Koleksi
                    </a>
                    <a href="{{ route('opac.page', 'tata-tertib') }}" class="border-2 border-white/70 text-white hover:bg-white/10 hover:border-white font-bold py-3 px-8 rounded-full transition-all text-sm flex items-center gap-2 no-underline">
                        <i class="bi bi-journal-text"></i> Tata Tertib
                    </a>
                </div>
            </div>

            {{-- Right: 3D/Isometric Book & Device Mockup (UBSI Signature) --}}
            <div class="w-full lg:w-5/12 flex justify-center items-center relative">
                <div class="relative w-full max-w-sm sm:max-w-md">
                    {{-- Main Digital Tablet/Book Frame --}}
                    <div class="relative bg-gradient-to-br from-slate-800 to-slate-900 rounded-3xl p-5 sm:p-6 shadow-2xl border border-slate-700/60 transform hover:rotate-1 transition-transform duration-500">
                        {{-- Screen View --}}
                        <div class="bg-slate-50 rounded-2xl p-4 sm:p-5 shadow-inner relative overflow-hidden border border-slate-200" style="aspect-ratio: 4/3;">
                            {{-- Header in mockup screen --}}
                            <div class="flex items-center justify-between border-b border-slate-200/80 pb-3 mb-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-rose-500"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Perpustakaan Online</span>
                            </div>

                            {{-- Book Reader representation --}}
                            <div class="flex gap-3 h-[calc(100%-36px)]">
                                {{-- Left page --}}
                                <div class="w-1/2 bg-white rounded-xl p-3 shadow-sm border border-slate-100 flex flex-col justify-between">
                                    <div class="space-y-2">
                                        <div class="h-2.5 bg-indigo-100 rounded w-4/5"></div>
                                        <div class="h-1.5 bg-slate-200 rounded w-full"></div>
                                        <div class="h-1.5 bg-slate-200 rounded w-5/6"></div>
                                        <div class="h-1.5 bg-slate-200 rounded w-3/4"></div>
                                    </div>
                                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[9px] text-slate-400">
                                        <span>Hal. 12</span>
                                        <i class="bi bi-bookmark-fill text-indigo-500"></i>
                                    </div>
                                </div>

                                {{-- Right page --}}
                                <div class="w-1/2 bg-white rounded-xl p-3 shadow-sm border border-slate-100 flex flex-col justify-between">
                                    <div class="space-y-2">
                                        <div class="h-1.5 bg-slate-200 rounded w-full"></div>
                                        <div class="h-1.5 bg-slate-200 rounded w-4/5"></div>
                                        <div class="h-1.5 bg-slate-200 rounded w-5/6"></div>
                                        <div class="h-1.5 bg-slate-200 rounded w-2/3"></div>
                                    </div>
                                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[9px] text-slate-400">
                                        <span class="text-indigo-600 font-bold">Santo Paulus</span>
                                        <span>Hal. 13</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Floating Badges around the mockup (UBSI Style) --}}
                    <div class="absolute -top-5 -right-3 sm:-right-5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl p-3 shadow-xl border border-white/20 transform rotate-6 hover:rotate-0 transition-transform duration-300 flex items-center gap-2">
                        <i class="bi bi-book-half text-lg"></i>
                        <span class="text-xs font-extrabold tracking-wide uppercase">E-Book Digital</span>
                    </div>

                    <div class="absolute -bottom-5 -left-3 sm:-left-5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-2xl p-3 shadow-xl border border-white/20 transform -rotate-6 hover:rotate-0 transition-transform duration-300 flex items-center gap-2">
                        <i class="bi bi-journal-bookmark-fill text-lg"></i>
                        <span class="text-xs font-extrabold tracking-wide uppercase">Buku Cetak</span>
                    </div>

                    <div class="absolute top-1/2 -right-5 sm:-right-7 bg-white text-slate-800 rounded-2xl px-3 py-2 shadow-2xl border border-slate-100 transform translate-y-4 hover:scale-105 transition-transform duration-300 flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xs font-bold">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <span class="text-[11px] font-extrabold tracking-wide">Koleksi Terkini</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

@else
    {{-- Search bar moved inside the main content area below for koleksi/digital tab --}}
@endif

<div class="w-full px-6 sm:px-8 lg:px-12 mb-20 relative z-20">

    @if($tab == 'home')
        {{-- HOME TAB: Buku Populer & Buku Terbaru --}}
        
        {{-- Buku Populer (UBSI Signature Section) --}}
        {{-- Buku Populer (Meta Showcase Section) --}}
        <div class="mb-16">
            <div class="flex items-center justify-between mb-8 flex-row gap-4">
                <div>
                    <span class="text-xs font-bold text-[#0064e0] uppercase tracking-wider">Rekomendasi</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0a1317] m-0 tracking-tight leading-none mt-1 mb-1">
                        Buku Populer
                    </h2>
                    <p class="text-xs sm:text-sm text-[#5d6c7b] m-0">Koleksi buku dengan minat peminjaman tertinggi minggu ini.</p>
                </div>
                <a href="{{ route('opac.katalog', ['tab' => 'koleksi']) }}" class="text-xs font-bold text-[#1c1e21] px-5 py-2 border border-[#ced0d4] rounded-full hover:bg-slate-100 transition-all no-underline flex items-center gap-1.5 shadow-sm">
                    <span>Lihat Semua</span> <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-5 gap-6 sm:gap-8">
                @forelse($popularBooks as $book)
                    @include('components.book-card', ['book' => $book])
                @empty
                    <div class="col-span-full text-center py-12 text-[#8595a4]">
                        <i class="bi bi-journal-x text-4xl block mb-2 opacity-50"></i>
                        Belum ada data buku populer.
                    </div>
                @endforelse
            </div>
        </div>
        
        {{-- Buku Terbaru (Meta Showcase Section) --}}
        <div class="mt-8 mb-16">
            <div class="flex items-center justify-between mb-8 flex-row gap-4">
                <div>
                    <span class="text-xs font-bold text-[#0064e0] uppercase tracking-wider">Terbaru</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0a1317] m-0 tracking-tight leading-none mt-1 mb-1">
                        Buku Terbaru
                    </h2>
                    <p class="text-xs sm:text-sm text-[#5d6c7b] m-0">Jelajahi judul-judul paling segar di perpustakaan.</p>
                </div>
                <a href="{{ route('opac.katalog', ['tab' => 'koleksi']) }}" class="text-xs font-bold text-[#1c1e21] px-5 py-2 border border-[#ced0d4] rounded-full hover:bg-slate-100 transition-all no-underline flex items-center gap-1.5 shadow-sm">
                    <span>Lihat Semua</span> <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-5 gap-6 sm:gap-8">
                @forelse($newestBooks as $book)
                    @include('components.book-card', ['book' => $book])
                @empty
                    <div class="col-span-full text-center py-12 text-[#8595a4]">
                        <i class="bi bi-journal-x text-4xl block mb-2 opacity-50"></i>
                        Belum ada data buku terbaru.
                    </div>
                @endforelse
            </div>
        </div>

    @else
        {{-- KOLEKSI & DIGITAL TAB: Filter & Grid --}}
        <div class="flex flex-col lg:flex-row gap-8 pt-4">
            {{-- Left side: Kategori (Sidebar Meta) --}}
            <div class="w-full lg:w-72 xl:w-80 flex-shrink-0 mb-6 lg:mb-0">
                <div class="bg-white rounded-3xl shadow-sm border border-[#dee3e9] overflow-hidden sticky top-28 p-2" x-data="{ searchCategory: '' }">
                    <div class="px-4 py-3 border-b border-[#dee3e9] flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-funnel text-[#0064e0] text-base"></i>
                            <h3 class="font-bold text-[#0a1317] m-0 text-sm tracking-tight">Kategori Subjek</h3>
                        </div>
                    </div>
                    <div class="p-3 border-b border-[#dee3e9]/60">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-[#8595a4]">
                                <i class="bi bi-search text-xs"></i>
                            </span>
                            <input type="text" x-model="searchCategory" placeholder="Cari Kategori..." class="w-full pl-9 pr-3 py-2 bg-[#f1f4f7] border border-transparent rounded-full text-xs focus:outline-none focus:border-[#0064e0] focus:bg-white transition-all">
                        </div>
                    </div>
                    <div class="p-2 max-h-[60vh] overflow-y-auto custom-scrollbar space-y-1">
                        <a href="{{ route('opac.katalog', ['tab' => $tab, 'q' => request('q')]) }}" 
                           class="block px-4 py-2 rounded-full text-xs font-bold transition-all {{ !request('subject_id') ? 'bg-[#0a1317] text-white shadow-sm' : 'text-[#5d6c7b] hover:bg-[#f1f4f7] hover:text-[#0064e0]' }} no-underline">
                            Semua Kategori
                        </a>
                        
                        @foreach($categories as $category)
                        <a href="{{ route('opac.katalog', ['tab' => $tab, 'subject_id' => $category->id, 'q' => request('q')]) }}" 
                           x-show="searchCategory === '' || '{{ strtolower(addslashes($category->name)) }}'.includes(searchCategory.toLowerCase())"
                           class="block px-4 py-2 rounded-full text-xs font-bold transition-all flex items-center gap-2 no-underline {{ request('subject_id') == $category->id ? 'bg-[#0a1317] text-white shadow-sm' : 'text-[#5d6c7b] hover:bg-[#f1f4f7] hover:text-[#0064e0]' }}">
                            <i class="bi bi-book text-xs {{ request('subject_id') == $category->id ? 'text-white/80' : 'text-[#8595a4]' }}"></i>
                            <span class="truncate">{{ $category->name }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right side: Content & Grid --}}
            <div class="flex-1 min-w-0">
                {{-- Search Bar (Meta Pill Search) --}}
                <div class="mb-6">
                    <form action="{{ route('opac.katalog') }}" method="GET" class="w-full flex shadow-sm rounded-full overflow-visible bg-white border border-[#dee3e9] p-1.5 relative items-center" x-data="autocompleteSearch('{{ addslashes(request('q')) }}')" @click.away="isOpen = false">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        @if(request('subject_id'))
                        <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                        @endif

                        <span class="pl-4 text-[#8595a4]">
                            <i class="bi bi-search text-base"></i>
                        </span>
                        <input type="text" name="q" placeholder="Cari buku, penulis, topik, ISBN..." class="w-full bg-transparent border-none py-2 px-3 text-[#1c1e21] outline-none text-sm placeholder:text-[#8595a4]" x-model="query" @input.debounce.300ms="fetchSuggestions" @focus="if(query.length > 1) isOpen = true" autocomplete="off">
                        
                        {{-- Autocomplete Dropdown --}}
                        <div x-show="isOpen && suggestions.length > 0" x-transition.opacity class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-[#dee3e9] overflow-hidden z-[100]" style="display: none;">
                            <template x-for="item in suggestions" :key="item.text + item.type">
                                <button type="button" @click="selectSuggestion(item)" class="w-full text-left px-5 py-3 hover:bg-[#f1f4f7] border-b border-[#dee3e9]/50 last:border-0 flex items-center justify-between group transition-colors cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#f1f4f7] text-[#0064e0] flex items-center justify-center group-hover:bg-[#0064e0] group-hover:text-white transition-colors">
                                            <i :class="'bi ' + item.icon"></i>
                                        </div>
                                        <span class="text-[#1c1e21] font-semibold text-sm truncate" x-text="item.text"></span>
                                    </div>
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-[#8595a4] group-hover:text-[#0064e0] flex-shrink-0 ml-2" x-text="item.type"></span>
                                </button>
                            </template>
                        </div>

                        <div class="relative flex items-center border-l border-[#dee3e9] pl-2 pr-1 hidden sm:flex">
                            <select name="search_by" class="bg-transparent text-[#1c1e21] outline-none border-0 text-xs font-bold cursor-pointer appearance-none pr-6">
                                <option value="title" {{ request('search_by') == 'title' ? 'selected' : '' }}>Judul</option>
                                <option value="author" {{ request('search_by') == 'author' ? 'selected' : '' }}>Penulis</option>
                                <option value="subject" {{ request('search_by') == 'subject' ? 'selected' : '' }}>Topik</option>
                            </select>
                            <i class="bi bi-chevron-down text-[10px] text-[#8595a4] absolute right-2 pointer-events-none"></i>
                        </div>
                        <button type="submit" x-ref="submitBtn" class="bg-[#0064e0] hover:bg-[#0457cb] text-white font-bold py-2.5 px-6 rounded-full transition-colors whitespace-nowrap ml-2 shadow-sm text-xs uppercase tracking-wider cursor-pointer">{{ __('Cari') }}</button>
                    </form>
                </div>

                {{-- Hasil Filter Header --}}
                <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-[#5d6c7b] text-sm sm:text-base font-medium m-0">
                        Hasil dari <span class="font-bold text-[#0a1317]">"{{ $activeCategory ? $activeCategory->name : ($tab == 'digital' ? 'Semua Koleksi Digital' : 'Semua Buku') }}"</span>
                    </h2>
                    <span class="text-[#0064e0] text-xs bg-[#0064e0]/10 border border-[#0064e0]/20 px-3.5 py-1 rounded-full font-bold">
                        {{ $books->count() }} {{ __('judul buku') }}
                    </span>
                </div>

                {{-- Book Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-4 sm:gap-6">
                    @forelse($books as $book)
                        @include('components.book-card', ['book' => $book])
                    @empty
                    <div class="col-span-full py-16 text-center text-slate-400 bg-white rounded-2xl border border-slate-100 p-8">
                        <i class="bi bi-journal-x text-5xl font-bold block opacity-30 mb-4"></i>
                        <p class="text-slate-600 font-semibold mb-1">{{ __('Buku tidak ditemukan.') }}</p>
                        <p class="text-xs text-slate-400">{{ __('Coba masukkan kata kunci pencarian yang lain atau pilih kategori yang berbeda.') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function autocompleteSearch(initialQuery = '') {
        return {
            query: initialQuery,
            suggestions: [],
            isOpen: false,
            fetchSuggestions() {
                if (this.query.length < 2) {
                    this.suggestions = [];
                    this.isOpen = false;
                    return;
                }
                
                fetch(`/opac/autocomplete?q=${encodeURIComponent(this.query)}`)
                    .then(res => res.json())
                    .then(data => {
                        this.suggestions = data;
                        this.isOpen = data.length > 0;
                    })
                    .catch(err => {
                        console.error('Error fetching autocomplete:', err);
                    });
            },
            selectSuggestion(item) {
                if (item.url) {
                    window.location.href = item.url;
                    return;
                }
                
                this.query = item.text;
                this.isOpen = false;
                
                this.$nextTick(() => {
                    this.$refs.submitBtn.click();
                });
            }
        }
    }
</script>
@endpush
@endsection
