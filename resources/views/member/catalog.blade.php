@extends('layouts.member')

@section('title', 'Katalog Buku')

@section('content')
<div class="space-y-6 pb-8">

    {{-- Search & Header --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6">
        <div class="max-w-2xl mb-4">
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">
                Katalog Buku Perpustakaan
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Cari dan temukan koleksi buku cetak maupun buku digital sekolah.
            </p>
        </div>

        {{-- Search Form --}}
        <form action="{{ route('member.catalog') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
            @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                    <i class="bi bi-search text-sm"></i>
                </span>
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}"
                       placeholder="Cari berdasarkan judul buku, nama pengarang, atau ISBN..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
            </div>

            <button type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs sm:text-sm px-5 py-2.5 rounded-xl transition-colors cursor-pointer flex-shrink-0">
                Cari
            </button>

            @if(request('q') || request('category'))
                <a href="{{ route('member.catalog') }}" 
                   class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold flex items-center justify-center transition-colors no-underline flex-shrink-0">
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    {{-- Category Filter Pills --}}
    @if($categories->isNotEmpty())
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
            <a href="{{ route('member.catalog', array_filter(['q' => request('q'), 'sort' => request('sort')])) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors no-underline {{ !request('category') ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Semua Kategori
            </a>

            @foreach($categories as $cat)
                @php $isSelected = request('category') === $cat; @endphp
                <a href="{{ route('member.catalog', array_filter(['category' => $cat, 'q' => request('q'), 'sort' => request('sort')])) }}" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors no-underline {{ $isSelected ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Filter Meta & Sorter --}}
    <div class="flex items-center justify-between text-xs text-slate-500 px-1">
        <div>
            Menampilkan <strong class="text-slate-800 font-bold">{{ $books->total() }}</strong> buku
            @if(request('q')) untuk pencarian <span class="font-bold text-slate-800">"{{ request('q') }}"</span> @endif
        </div>

        <form action="{{ route('member.catalog') }}" method="GET" class="flex items-center gap-2">
            @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
            
            <label for="sortSelect" class="text-slate-400 hidden sm:inline">Urutkan:</label>
            <select id="sortSelect" 
                    name="sort" 
                    onchange="this.form.submit()" 
                    class="bg-white border border-slate-200 text-slate-700 text-xs font-medium rounded-lg px-2.5 py-1.5 outline-none focus:border-indigo-500 cursor-pointer">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Judul (A - Z)</option>
                <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Judul (Z - A)</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
            </select>
        </form>
    </div>

    {{-- Book Grid / Empty --}}
    @if($books->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center max-w-md mx-auto">
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl mx-auto mb-2">
                <i class="bi bi-search"></i>
            </div>
            <h3 class="font-bold text-slate-700 text-sm">Buku Tidak Ditemukan</h3>
            <p class="text-xs text-slate-400 mt-1 mb-4">Coba cari dengan kata kunci yang lebih umum atau periksa ejaan.</p>
            <a href="{{ route('member.catalog') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold hover:bg-slate-200 transition-colors no-underline">
                Lihat Semua Koleksi
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            @foreach($books as $book)
                @php
                    $isDigital = $book->isDigital();
                    $available = $book->available_items_count > 0;
                @endphp
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col justify-between hover:border-indigo-300 hover:shadow-sm transition-all group">
                    <div>
                        {{-- Cover Image --}}
                        <a href="{{ route('member.catalog.show', $book) }}" class="block aspect-[3/4] bg-slate-100 relative overflow-hidden border-b border-slate-100">
                            @if($book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400 text-xs font-bold p-2 text-center">
                                    {{ strtoupper(substr($book->title, 0, 2)) }}
                                </div>
                            @endif

                            @if($isDigital)
                                <span class="absolute top-2 left-2 bg-indigo-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">
                                    E-Book
                                </span>
                            @endif
                        </a>

                        {{-- Info --}}
                        <div class="p-3">
                            <a href="{{ route('member.catalog.show', $book) }}" class="block no-underline">
                                <h3 class="font-bold text-xs text-slate-800 line-clamp-2 leading-tight group-hover:text-indigo-600 transition-colors mb-1">
                                    {{ $book->title }}
                                </h3>
                            </a>
                            <p class="text-[11px] text-slate-400 line-clamp-1">
                                {{ $book->main_author ?? '-' }}
                            </p>

                            <div class="mt-2">
                                @if($available)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                                        <span>Tersedia ({{ $book->available_items_count }})</span>
                                    </span>
                                @elseif($isDigital)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-medium text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">
                                        <span>Baca Digital</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                                        <span>Dipinjam</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Action --}}
                    <div class="p-3 pt-0 flex gap-1.5">
                        <a href="{{ route('member.catalog.show', $book) }}" 
                           class="flex-1 py-1.5 text-center rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[11px] transition-colors no-underline">
                            Detail
                        </a>
                        @if($isDigital)
                            <a href="{{ route('opac.read', $book) }}" target="_blank" 
                               class="py-1.5 px-2.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-[11px] transition-colors no-underline" 
                               title="Baca E-Book">
                                <i class="bi bi-book"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($books->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $books->links() }}
            </div>
        @endif
    @endif

</div>
@endsection
