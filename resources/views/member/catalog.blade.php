@extends('layouts.app')

@section('title', __('Katalog Buku'))

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Katalog Buku</h1>
            <p class="text-sm text-slate-500 mt-1">Cari dan temukan buku favorit Anda.</p>
        </div>
    </div>

    {{-- Search Bar --}}
    <form action="{{ route('member.catalog') }}" method="GET" class="w-full shadow-sm rounded-xl overflow-hidden flex bg-white border border-slate-200 p-1">
        <span class="flex items-center px-4 bg-white text-slate-400"><i class="bi bi-search text-xl"></i></span>
        <input type="text" name="q" class="w-full border-0 focus:ring-0 text-slate-700 py-3 px-2 text-base outline-none" placeholder="{{ __('Ketik judul buku, pengarang, penerbit...') }}" value="{{ request('q') }}">
        <button type="submit" class="btn-gradient-blue text-white font-bold py-3 px-8 rounded-lg transition-colors whitespace-nowrap">{{ __('Cari') }}</button>
    </form>

    <div class="flex flex-wrap -mx-4 mt-6">
        {{-- Left side: Filters --}}
        <div class="w-full lg:w-1/4 px-4">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 font-bold text-slate-800 flex items-center">
                    <i class="bi bi-funnel mr-2 text-indigo-500"></i> {{ __('Filter Pencarian') }}
                </div>
                <div class="p-6">
                    <form action="{{ route('member.catalog') }}" method="GET" id="filterForm">
                        @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif

                        <div class="mb-5">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ __('Kategori') }}</label>
                            <select name="category" class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border bg-white" onchange="this.form.submit()">
                                <option value="">{{ __('Semua Kategori') }}</option>
                                @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ __('Urutkan') }}</label>
                            <select name="sort" class="w-full rounded-lg border-slate-300 py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border bg-white" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Terbaru') }}</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>{{ __('Terlama') }}</option>
                                <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>{{ __('Judul (A-Z)') }}</option>
                                <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>{{ __('Judul (Z-A)') }}</option>
                            </select>
                        </div>

                        <a href="{{ route('member.catalog') }}" class="w-full flex items-center justify-center gap-2 py-2.5 text-sm font-semibold rounded-lg text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                            <i class="bi bi-arrow-counterclockwise"></i> {{ __('Reset Filter') }}
                        </a>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right side: Book grid --}}
        <div class="w-full lg:w-3/4 px-4">
            @if(request('q') || request('category'))
                <div class="bg-indigo-50/50 border border-indigo-100 rounded-xl px-5 py-3 mb-6 flex justify-between items-center">
                    <div class="text-sm text-slate-600">
                        {{ __('Menampilkan hasil untuk:') }}
                        @if(request('q')) <strong class="text-slate-800">"{{ request('q') }}"</strong> @endif
                        @if(request('category')) <span class="ml-1 inline-flex py-0.5 px-2 text-xs font-semibold rounded-md bg-white text-indigo-600 border border-indigo-200">{{ request('category') }}</span> @endif
                    </div>
                    <span class="text-sm font-medium text-slate-500">{{ $books->total() }} {{ __('buku ditemukan') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($books as $book)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-slate-200/60 overflow-hidden group flex flex-col h-full">
                    
                    <!-- Cover -->
                    <div class="aspect-[3/4] bg-slate-100 relative overflow-hidden flex items-center justify-center border-b border-slate-100">
                        @if($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            @php
                                $colors = ['from-blue-500 to-indigo-600', 'from-emerald-400 to-teal-600', 'from-orange-400 to-red-500', 'from-purple-500 to-pink-600', 'from-cyan-500 to-blue-600'];
                                $gradient = $colors[crc32($book->title) % count($colors)];
                                $words = explode(' ', $book->title);
                                $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                            @endphp
                            <div class="w-full h-full bg-gradient-to-br {{ $gradient }} flex flex-col items-center justify-center text-white p-4 text-center group-hover:scale-105 transition-transform duration-500">
                                <span class="text-6xl font-bold opacity-90 drop-shadow-md mb-2">{{ $initials }}</span>
                            </div>
                        @endif
                        
                        <!-- Category Badge overlay -->
                        @if($book->collection_type)
                        <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-2.5 py-1 rounded-md text-[10px] font-bold text-slate-700 shadow-sm uppercase tracking-wide">
                            {{ $book->collection_type }}
                        </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-5 flex flex-col flex-grow">
                        <h3 class="font-bold text-slate-800 text-lg leading-tight mb-1 line-clamp-2">
                            {{ $book->title }}
                        </h3>
                        <p class="text-sm text-slate-500 mb-2">{{ $book->main_author ?? 'Pengarang Tidak Diketahui' }}</p>
                        
                        <!-- Static Stars -->
                        <div class="flex text-amber-400 text-xs mb-4 gap-0.5">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                        
                        <!-- Spacer to push footer down -->
                        <div class="mt-auto"></div>
                        
                        <!-- Stock Info -->
                        <div class="mb-4">
                            @if($book->available_items_count > 0)
                                <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                    Tersedia {{ $book->available_items_count }}
                                </div>
                            @else
                                <div class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600 bg-red-50 px-2.5 py-1 rounded-md">
                                    <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div>
                                    Dipinjam Semua
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2 w-full mt-2">
                            <a href="{{ route('member.catalog.show', $book) }}" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold py-2.5 rounded-lg transition-colors text-center border border-slate-200/50">
                                Detail
                            </a>
                            <button type="button" class="flex-1 btn-gradient-blue text-white text-xs font-bold py-2.5 rounded-lg transition-colors text-center shadow-sm" onclick="alert('Silakan lihat detail buku untuk meminjam.')">
                                Pinjam
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-slate-200 border-dashed">
                    <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                        <i class="bi bi-search"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700 mb-1">Buku Tidak Ditemukan</h3>
                    <p class="text-slate-500">Coba gunakan kata kunci atau filter pencarian yang lain.</p>
                </div>
                @endforelse
            </div>

            @if($books->hasPages())
            <div class="mt-8">
                {{ $books->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
