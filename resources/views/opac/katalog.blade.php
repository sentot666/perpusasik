@extends('layouts.opac')

@section('title', __('Katalog Buku'))

@section('content')
@if($tab == 'home')
    {{-- Hero Section --}}
    <div class="bg-gradient-to-br from-[#0a0f2e] to-[#091850] rounded-3xl mx-4 sm:mx-6 lg:mx-8 mt-6 mb-12 p-8 md:p-12 lg:p-16 relative shadow-2xl">
        <!-- Decorative background elements -->
        <div class="absolute inset-0 overflow-hidden rounded-3xl pointer-events-none">
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-blue-500 rounded-full blur-[100px] opacity-20"></div>
            <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-purple-500 rounded-full blur-[100px] opacity-20"></div>
        </div>
        
        <div class="relative z-10 max-w-3xl">
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4 leading-tight">
                Jelajahi Dunia <span class="text-blue-400">Pengetahuan</span><br>Tanpa Batas
            </h1>
            
            <p class="text-slate-300 text-sm md:text-base mb-8 leading-relaxed max-w-xl">
                Akses berbagai koleksi buku dan sumber informasi kapan saja dan di mana saja. Mudah, cepat, dan praktis.
            </p>

            {{-- Search Bar --}}
            <form action="{{ route('opac.katalog') }}" method="GET" class="mb-6 relative z-50" x-data="autocompleteSearch('{{ addslashes(request('q')) }}')" @click.away="isOpen = false">
                <input type="hidden" name="tab" value="koleksi">
                <div class="flex flex-col sm:flex-row items-stretch bg-white/10 border border-blue-400/30 rounded-xl overflow-visible backdrop-blur-md focus-within:border-blue-400 focus-within:ring-2 focus-within:ring-blue-400/20 transition-all shadow-lg relative">
                    <select name="search_by" class="bg-transparent border-0 sm:border-r border-slate-500/50 py-4 px-5 text-sm font-semibold text-blue-100 outline-none cursor-pointer appearance-none rounded-l-xl">
                        <option value="title" class="text-slate-800">Judul</option>
                        <option value="author" class="text-slate-800">Penulis</option>
                        <option value="subject" class="text-slate-800">Topik</option>
                    </select>
                    <div class="flex-1 flex items-center bg-transparent relative">
                        <span class="pl-5 text-slate-300 hidden sm:block"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" placeholder="Cari buku, penulis, atau topik..." class="w-full bg-transparent border-none py-4 px-3 text-white outline-none text-sm placeholder:text-slate-300/70" x-model="query" @input.debounce.300ms="fetchSuggestions" @focus="if(query.length > 1) isOpen = true" autocomplete="off">
                        
                        <!-- Autocomplete Dropdown -->
                        <div x-show="isOpen && suggestions.length > 0" x-transition.opacity class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-slate-100 overflow-hidden z-[100]" style="display: none;">
                            <template x-for="item in suggestions" :key="item.text + item.type">
                                <button type="button" @click="selectSuggestion(item.text)" class="w-full text-left px-4 py-3 hover:bg-slate-50 border-b border-slate-50 last:border-0 flex items-center justify-between group transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                            <i :class="'bi ' + item.icon"></i>
                                        </div>
                                        <span class="text-slate-700 font-medium text-sm truncate" x-text="item.text"></span>
                                    </div>
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 group-hover:text-indigo-400 flex-shrink-0 ml-2" x-text="item.type"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                    <button type="submit" x-ref="submitBtn" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 px-8 transition-colors whitespace-nowrap rounded-r-xl">
                        <i class="bi bi-search sm:hidden mr-2"></i>Cari
                    </button>
                </div>
            </form>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ route('opac.katalog', ['tab' => 'koleksi']) }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-[0_4px_14px_0_rgba(37,99,235,0.39)] transition-colors text-sm flex items-center gap-2 no-underline">
                    Lihat Koleksi <i class="bi bi-arrow-right"></i>
                </a>
                <a href="#" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl shadow-sm border border-white/10 transition-colors text-sm flex items-center gap-2 no-underline backdrop-blur-md">
                    <i class="bi bi-journal-text"></i> Panduan
                </a>
            </div>
        </div>
    </div>
@else
    {{-- Search bar moved inside the main content area below --}}
@endif

<div class="w-full mx-auto sm:px-6 lg:px-8 px-6 mb-16">

    @if($tab == 'home')
        {{-- HOME TAB: Buku Populer & Buku Terbaru --}}
        
        {{-- Buku Populer --}}
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-slate-800 m-0"><i class="bi bi-fire text-rose-500 mr-2"></i>Buku Populer</h2>
                <a href="{{ route('opac.katalog', ['tab' => 'koleksi']) }}" class="text-indigo-600 font-semibold text-sm hover:underline">Lihat Semua <i class="bi bi-arrow-right"></i></a>
            </div>
            
            <div class="flex flex-wrap -mx-3">
                @forelse($popularBooks as $book)
                <div class="w-1/2 sm:w-1/3 lg:w-1/4 xl:w-1/5 px-3 mb-6">
                    @include('components.book-card', ['book' => $book])
                </div>
                @empty
                <div class="w-full px-3 text-center py-8 text-slate-500">Belum ada data buku populer.</div>
                @endforelse
            </div>
        </div>
        
        {{-- Buku Terbaru --}}
        <div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-slate-800 m-0"><i class="bi bi-stars text-amber-500 mr-2"></i>Buku Terbaru</h2>
                <a href="{{ route('opac.katalog', ['tab' => 'koleksi']) }}" class="text-indigo-600 font-semibold text-sm hover:underline">Lihat Semua <i class="bi bi-arrow-right"></i></a>
            </div>
            
            <div class="flex flex-wrap -mx-3">
                @forelse($newestBooks as $book)
                <div class="w-1/2 sm:w-1/3 lg:w-1/4 xl:w-1/5 px-3 mb-6">
                    @include('components.book-card', ['book' => $book])
                </div>
                @empty
                <div class="w-full px-3 text-center py-8 text-slate-500">Belum ada data buku terbaru.</div>
                @endforelse
            </div>
        </div>

    @else
        {{-- KOLEKSI & DIGITAL TAB: Filter & Grid --}}
        <div class="flex flex-wrap -mx-4 pt-8">
            {{-- Left side: Kategori --}}
            <div class="w-full lg:w-1/4 px-4 mb-6">
                <div class="bg-white rounded-xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-slate-100 overflow-hidden" x-data="{ searchCategory: '' }">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                        <i class="bi bi-funnel text-slate-500 text-lg"></i>
                        <h3 class="font-bold text-slate-800 m-0 text-lg tracking-tight">Kategori</h3>
                    </div>
                    <div class="p-4 border-b border-slate-50 bg-slate-50/50">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" x-model="searchCategory" placeholder="Cari Kategori..." class="w-full pl-9 pr-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors shadow-sm">
                        </div>
                    </div>
                    <div class="p-3 max-h-[60vh] overflow-y-auto custom-scrollbar">
                        <a href="{{ route('opac.katalog', ['tab' => $tab, 'q' => request('q')]) }}" 
                           class="block px-4 py-3 rounded-xl text-base font-semibold transition-all mb-1 {{ !request('subject_id') ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-600' }}">
                            Semua Buku
                        </a>
                        
                        @foreach($categories as $category)
                        <a href="{{ route('opac.katalog', ['tab' => $tab, 'subject_id' => $category->id, 'q' => request('q')]) }}" 
                           x-show="searchCategory === '' || '{{ strtolower(addslashes($category->name)) }}'.includes(searchCategory.toLowerCase())"
                           class="block px-4 py-3 rounded-xl text-base font-semibold transition-all mb-1 flex items-center gap-3 {{ request('subject_id') == $category->id ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-600' }}">
                            <i class="bi bi-book text-base {{ request('subject_id') == $category->id ? 'text-white/80' : 'text-slate-400' }}"></i>
                            <span class="truncate">{{ $category->name }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right side: Content --}}
            <div class="w-full lg:w-3/4 px-4">
                {{-- Search Bar --}}
                <div class="mb-8">
                    <form action="{{ route('opac.katalog') }}" method="GET" class="w-full flex shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] rounded-xl overflow-visible bg-white border border-slate-100 p-1.5 relative items-center" x-data="autocompleteSearch('{{ addslashes(request('q')) }}')" @click.away="isOpen = false">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        @if(request('subject_id'))
                        <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                        @endif

                        <span class="pl-4 text-slate-400">
                            <i class="bi bi-search text-lg"></i>
                        </span>
                        <input type="text" name="q" placeholder="Cari buku, penulis, ISBN..." class="w-full bg-transparent border-none py-3 px-3 text-slate-700 outline-none text-base placeholder:text-slate-400" x-model="query" @input.debounce.300ms="fetchSuggestions" @focus="if(query.length > 1) isOpen = true" autocomplete="off">
                        
                        <!-- Autocomplete Dropdown -->
                        <div x-show="isOpen && suggestions.length > 0" x-transition.opacity class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-slate-100 overflow-hidden z-[100]" style="display: none;">
                            <template x-for="item in suggestions" :key="item.text + item.type">
                                <button type="button" @click="selectSuggestion(item.text)" class="w-full text-left px-4 py-3 hover:bg-slate-50 border-b border-slate-50 last:border-0 flex items-center justify-between group transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                            <i :class="'bi ' + item.icon"></i>
                                        </div>
                                        <span class="text-slate-700 font-medium text-sm truncate" x-text="item.text"></span>
                                    </div>
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 group-hover:text-indigo-400 flex-shrink-0 ml-2" x-text="item.type"></span>
                                </button>
                            </template>
                        </div>

                        <select name="search_by" class="bg-transparent border-0 border-l border-slate-200 py-3 px-4 text-sm font-medium text-slate-600 outline-none cursor-pointer appearance-none hidden sm:block">
                            <option value="title">Judul</option>
                            <option value="author">Penulis</option>
                            <option value="subject">Topik</option>
                        </select>
                        <button type="submit" x-ref="submitBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition-colors whitespace-nowrap ml-2 shadow-sm">{{ __('Cari') }}</button>
                    </form>
                </div>

                {{-- Hasil Dari Title --}}
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-slate-500 text-base font-medium m-0">Hasil dari <span class="font-bold text-slate-800">"{{ $activeCategory ? $activeCategory->name : 'Semua Buku' }}"</span></h2>
                    @if(request('q'))
                    <span class="text-slate-500 text-sm bg-slate-100 px-3 py-1 rounded-full font-medium">{{ $books->total() }} {{ __('judul ditemukan') }}</span>
                    @endif
                </div>

                <div class="flex flex-wrap -mx-3">
                    @forelse($books as $book)
                    <div class="w-1/2 sm:w-1/3 lg:w-1/4 px-3 mb-6">
                        @include('components.book-card', ['book' => $book])
                    </div>
                    @empty
                    <div class="py-8 text-center text-slate-500 w-full px-4">
                        <i class="bi bi-journals text-4xl font-bold block opacity-25 mb-6"></i>
                        {{ __('Buku tidak ditemukan. Coba masukkan kata kunci pencarian yang lain.') }}
                    </div>
                    @endforelse
                </div>

                @if($books->hasPages())
                <div class="mt-6">
                    {{ $books->links() }}
                </div>
                @endif
            </div>
        </div>
    @endif
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
            selectSuggestion(text) {
                this.query = text;
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
