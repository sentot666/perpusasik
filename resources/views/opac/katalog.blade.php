@extends('layouts.opac')

@section('title', __('Katalog Buku'))

@section('content')
@if($tab == 'home')
    {{-- Hero Section --}}
    <div class="bg-gradient-to-br from-[#0a0f2e] to-[#091850] rounded-3xl mx-4 sm:mx-6 lg:mx-8 mt-6 mb-12 p-8 md:p-12 lg:p-16 relative overflow-hidden shadow-2xl">
        <!-- Decorative background elements -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-blue-500 rounded-full blur-[100px] opacity-20 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-purple-500 rounded-full blur-[100px] opacity-20 pointer-events-none"></div>
        
        <div class="relative z-10 max-w-3xl">
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4 leading-tight">
                Jelajahi Dunia <span class="text-blue-400">Pengetahuan</span><br>Tanpa Batas
            </h1>
            
            <p class="text-slate-300 text-sm md:text-base mb-8 leading-relaxed max-w-xl">
                Akses berbagai koleksi buku dan sumber informasi kapan saja dan di mana saja. Mudah, cepat, dan praktis.
            </p>

            {{-- Search Bar --}}
            <form action="{{ route('opac.katalog') }}" method="GET" class="mb-6">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="flex flex-col sm:flex-row items-stretch bg-white/10 border border-blue-400/30 rounded-xl overflow-hidden backdrop-blur-md focus-within:border-blue-400 focus-within:ring-2 focus-within:ring-blue-400/20 transition-all shadow-lg">
                    <select name="search_by" class="bg-transparent border-0 sm:border-r border-slate-500/50 py-4 px-5 text-sm font-semibold text-blue-100 outline-none cursor-pointer appearance-none">
                        <option value="title" class="text-slate-800">Judul</option>
                        <option value="author" class="text-slate-800">Penulis</option>
                        <option value="subject" class="text-slate-800">Topik</option>
                    </select>
                    <div class="flex-1 flex items-center bg-transparent">
                        <span class="pl-5 text-slate-300 hidden sm:block"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" placeholder="Cari buku, penulis, atau topik..." class="w-full bg-transparent border-none py-4 px-3 text-white outline-none text-sm placeholder:text-slate-300/70" value="{{ request('q') }}">
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 px-8 transition-colors whitespace-nowrap">
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
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-6 mt-8 mb-4">
        {{-- Search Bar --}}
        <div class="mb-4 flex flex-col md:flex-row gap-4 items-center">
            <form action="{{ route('opac.katalog') }}" method="GET" class="w-full flex-1 shadow-sm rounded-xl overflow-hidden flex bg-white border border-slate-200 p-1">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <span class="flex items-center px-4 bg-white text-slate-400"><i class="bi bi-search text-xl"></i></span>
                <input type="text" name="q" class="w-full border-0 focus:ring-0 text-slate-700 py-3 px-2 text-base outline-none" placeholder="{{ __('Ketik kata kunci judul, pengarang, penerbit, atau ISBN...') }}" value="{{ request('q') }}">
                <button type="submit" class="btn-gradient-blue text-white font-bold py-3 px-8 rounded-lg transition-colors whitespace-nowrap">{{ __('Cari') }}</button>
            </form>
        </div>
    </div>
@endif

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-6 mb-16">

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
        <div class="flex flex-wrap -mx-4">
            {{-- Left side: Filters --}}
            <div class="w-full lg:w-1/4 px-4">
                <div class="shadow-sm border-0 bg-white rounded-xl border border-slate-200 overflow-hidden mb-6" style="border-radius:12px">
                    <div class="bg-white px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 font-bold py-4"><i class="bi bi-funnel mr-2"></i>{{ __('Filter Pencarian') }}</div>
                    <div class="p-8">
                        <form action="{{ route('opac.katalog') }}" method="GET" id="opacFilterForm">
                            <input type="hidden" name="tab" value="{{ $tab }}">
                            
                            @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                            @endif

                            @if($tab != 'digital')
                            <div class="mb-6">
                                <label class="text-slate-500 block text-sm font-medium text-slate-700 mb-1" style="font-size:0.75rem;font-weight:600;text-transform:uppercase">{{ __('Jenis Koleksi') }}</label>
                                <select name="collection_type" class="w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white px-4" onchange="this.form.submit()">
                                    <option value="">{{ __('Semua Jenis') }}</option>
                                    @foreach($collectionTypes as $type)
                                    <option value="{{ $type }}" {{ request('collection_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div class="mb-6">
                                <label class="text-slate-500 block text-sm font-medium text-slate-700 mb-1" style="font-size:0.75rem;font-weight:600;text-transform:uppercase">{{ __('Tahun Terbit') }}</label>
                                <select name="year" class="w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white px-4" onchange="this.form.submit()">
                                    <option value="">{{ __('Semua Tahun') }}</option>
                                    @foreach($years as $yr)
                                    <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-6">
                                <label class="text-slate-500 block text-sm font-medium text-slate-700 mb-1" style="font-size:0.75rem;font-weight:600;text-transform:uppercase">{{ __('Bahasa') }}</label>
                                <select name="language" class="w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white px-4" onchange="this.form.submit()">
                                    <option value="">{{ __('Semua Bahasa') }}</option>
                                    <option value="id" {{ request('language') == 'id' ? 'selected' : '' }}>{{ __('Indonesia') }}</option>
                                    <option value="en" {{ request('language') == 'en' ? 'selected' : '' }}>{{ __('Inggris') }}</option>
                                </select>
                            </div>

                            <div class="mb-6">
                                <label class="text-slate-500 block text-sm font-medium text-slate-700 mb-1" style="font-size:0.75rem;font-weight:600;text-transform:uppercase">{{ __('Lokasi Rak') }}</label>
                                <select name="location_id" class="w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white px-4" onchange="this.form.submit()">
                                    <option value="">{{ __('Semua Rak') }}</option>
                                    @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->code }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <a href="{{ route('opac.katalog', ['tab' => $tab]) }}" class="w-full inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors px-4"><i class="bi bi-x"></i> {{ __('Bersihkan Filter') }}</a>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Right side: Book grid --}}
            <div class="w-full lg:w-3/4 px-4">
                @if(request('q') || request('collection_type') || request('year') || request('language'))
                    <div class="alert alert-light border border-slate-200 shadow-sm justify-between items-center flex mb-6 py-2 px-4" style="border-radius:10px;font-size:0.85rem">
                        <div>
                            {{ __('Menampilkan hasil pencarian untuk:') }}
                            @if(request('q')) <strong>"{{ request('q') }}"</strong> @endif
                            @if(request('collection_type')) <span class="ml-1 inline-flex py-1 text-xs font-medium rounded-md bg-indigo-100 text-indigo-700 px-2">{{ request('collection_type') }}</span> @endif
                            @if(request('year')) <span class="ml-1 inline-flex py-1 text-xs font-medium rounded-md bg-slate-100 text-slate-700 px-2">{{ request('year') }}</span> @endif
                            @if(request('language')) <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-sky-500 text-white ml-1">{{ request('language') == 'id' ? __('Indonesia') : __('Inggris') }}</span> @endif
                        </div>
                        <span class="text-slate-500">{{ $books->total() }} {{ __('judul ditemukan') }}</span>
                    </div>
                @endif

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
@endsection
