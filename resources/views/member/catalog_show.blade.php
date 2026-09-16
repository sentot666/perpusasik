@extends('layouts.member')

@section('title', $book->title . ' - Detail Buku')

@section('content')
<div class="space-y-6 pb-8">

    {{-- Breadcrumb --}}
    <div>
        <a href="{{ route('member.catalog') }}" 
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors no-underline">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Katalog</span>
        </a>
    </div>

    {{-- Main Detail Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="flex flex-col md:flex-row">

            {{-- Cover Left --}}
            <div class="md:w-64 lg:w-72 p-6 bg-slate-50 border-b md:border-b-0 md:border-r border-slate-200 flex flex-col items-center justify-center flex-shrink-0">
                <div class="w-44 aspect-[3/4] rounded-xl overflow-hidden shadow-sm bg-white border border-slate-200">
                    @if($book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400 text-lg font-bold">
                            {{ strtoupper(substr($book->title, 0, 2)) }}
                        </div>
                    @endif
                </div>

                @if($book->collection_type)
                    <span class="mt-4 px-3 py-1 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-600">
                        {{ $book->collection_type }}
                    </span>
                @endif
            </div>

            {{-- Info Right --}}
            <div class="flex-1 p-6 sm:p-8 flex flex-col justify-between">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 leading-tight mb-1.5">
                        {{ $book->title }}
                    </h1>
                    
                    <p class="text-xs sm:text-sm text-slate-500 font-medium mb-4">
                        Penulis: <strong class="text-slate-700">{{ $book->main_author ?? '-' }}</strong>
                    </p>

                    @php
                        $isDigital = $book->isDigital();
                        $availableItems = $book->items->where('status', 'Tersedia');
                        $totalAvailable = $availableItems->count();
                    @endphp

                    {{-- Availability status pill --}}
                    <div class="mb-5">
                        @if($totalAvailable > 0)
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-lg">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span>Tersedia {{ $totalAvailable }} eksemplar fisik</span>
                            </span>
                        @elseif($isDigital)
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 px-3 py-1 rounded-lg">
                                <i class="bi bi-book text-xs"></i>
                                <span>Koleksi Digital (E-Book)</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 bg-slate-100 border border-slate-200 px-3 py-1 rounded-lg">
                                <span>Semua eksemplar sedang dipinjam</span>
                            </span>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-wrap items-center gap-2.5 mb-6">
                        @if($isDigital)
                            <a href="{{ route('opac.read', $book) }}" target="_blank"
                               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs sm:text-sm px-5 py-2.5 rounded-xl transition-colors no-underline">
                                <i class="bi bi-book-half"></i>
                                <span>Baca E-Book</span>
                            </a>
                        @endif

                        @if($totalAvailable > 0)
                            <form action="{{ route('member.reservations.store', $book->id) }}" method="POST"
                                  onsubmit="return confirm('Pesan buku ini untuk diambil di perpustakaan?')">
                                @csrf
                                <button type="submit" 
                                        class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm px-5 py-2.5 rounded-xl transition-colors cursor-pointer">
                                    <i class="bi bi-bookmark-plus"></i>
                                    <span>Pesan Buku Ini</span>
                                </button>
                            </form>
                        @endif

                        {{-- Wishlist --}}
                        @if($inWishlist)
                            <form action="{{ route('member.wishlist.destroy', $book) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold text-xs sm:text-sm border border-rose-200 transition-colors cursor-pointer">
                                    <i class="bi bi-heart-fill"></i>
                                    <span>Tersimpan di Favorit</span>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('member.wishlist.store', $book) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold text-xs sm:text-sm border border-slate-200 transition-colors cursor-pointer">
                                    <i class="bi bi-heart"></i>
                                    <span>Tambah ke Favorit</span>
                                </button>
                            </form>
                        @endif
                    </div>

                    {{-- Synopsis --}}
                    @if($book->description)
                        <div class="mb-6">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Sinopsis</h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                                {{ $book->description }}
                            </p>
                        </div>
                    @endif

                    {{-- Specs Metadata --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 py-4 border-t border-b border-slate-100 text-xs">
                        <div>
                            <span class="text-slate-400 block mb-0.5">Penerbit</span>
                            <span class="font-semibold text-slate-700">{{ $book->publisher?->name ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">Tahun</span>
                            <span class="font-semibold text-slate-700">{{ $book->publication_year ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">Bahasa</span>
                            <span class="font-semibold text-slate-700">{{ $book->language == 'id' ? 'Indonesia' : ($book->language == 'en' ? 'Inggris' : ($book->language ?? 'Indonesia')) }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block mb-0.5">ISBN</span>
                            <span class="font-semibold text-slate-700 font-mono">{{ $book->isbn ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Location on Shelf --}}
                @if($book->items->isNotEmpty())
                    <div class="mt-6">
                        <h3 class="text-xs font-bold text-slate-700 mb-2.5">Lokasi Rak di Perpustakaan:</h3>
                        <div class="space-y-1.5">
                            @foreach($book->items as $item)
                                <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-200/80 text-xs">
                                    <div class="flex items-center gap-2 text-slate-700">
                                        <i class="bi bi-bookshelf text-slate-400"></i>
                                        <span class="font-semibold">{{ $item->location?->name ?? 'Rak Perpustakaan' }}</span>
                                        @if($item->call_number || $book->call_number)
                                            <span class="font-mono text-slate-500">({{ $item->call_number ?? $book->call_number }})</span>
                                        @endif
                                    </div>

                                    @if($item->status === 'Tersedia')
                                        <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">Tersedia</span>
                                    @else
                                        <span class="text-[11px] font-semibold text-slate-400">Dipinjam</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>

</div>
@endsection
