@extends('layouts.app')

@section('title', $book->title . ' - Detail Buku')

@section('content')
<div class="space-y-6">

    {{-- Back Button --}}
    <div>
        <a href="{{ route('member.catalog') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors group">
            <i class="bi bi-arrow-left transition-transform group-hover:-translate-x-1"></i>
            Kembali ke Katalog
        </a>
    </div>

    {{-- Main Detail Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="flex flex-col lg:flex-row">

            {{-- Left: Cover --}}
            <div class="lg:w-64 xl:w-80 flex-shrink-0 p-8 flex items-center justify-center bg-slate-50 border-b lg:border-b-0 lg:border-r border-slate-100">
                <div class="w-full max-w-[220px]">
                    @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-full rounded-xl shadow-xl object-cover">
                    @else
                        @php
                            $colors = ['from-blue-500 to-indigo-600', 'from-emerald-400 to-teal-600', 'from-orange-400 to-red-500', 'from-purple-500 to-pink-600', 'from-cyan-500 to-blue-600'];
                            $gradient = $colors[crc32($book->title) % count($colors)];
                            $words = explode(' ', $book->title);
                            $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                        @endphp
                        <div class="w-full aspect-[3/4] rounded-xl shadow-xl bg-gradient-to-br {{ $gradient }} flex flex-col items-center justify-center text-white">
                            <span class="text-6xl font-bold drop-shadow-md">{{ $initials }}</span>
                        </div>
                    @endif

                    {{-- Rating stars below cover --}}
                    <div class="flex justify-center text-amber-400 text-lg mt-4 gap-1">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <p class="text-center text-xs text-slate-400 mt-1">Rating Perpustakaan</p>
                </div>
            </div>

            {{-- Right: Info --}}
            <div class="flex-1 p-8 lg:p-10">

                {{-- Category Badge --}}
                @if($book->collection_type)
                <div class="mb-3">
                    <span class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase tracking-wider">
                        {{ $book->collection_type }}
                    </span>
                </div>
                @endif

                <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 leading-tight mb-2">{{ $book->title }}</h1>

                @if($book->main_author)
                <p class="text-lg text-slate-600 mb-6">oleh <span class="font-semibold text-slate-800">{{ $book->main_author }}</span></p>
                @endif

                {{-- Book Metadata Grid --}}
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8 py-6 border-t border-b border-slate-100">
                    @if($book->publisher)
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Penerbit</p>
                        <p class="text-sm font-semibold text-slate-700">{{ $book->publisher->name }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tahun Terbit</p>
                        <p class="text-sm font-semibold text-slate-700">{{ $book->publication_year ?? '-' }}</p>
                    </div>
                    @if($book->isbn)
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">ISBN</p>
                        <p class="text-sm font-semibold text-slate-700 font-mono">{{ $book->isbn }}</p>
                    </div>
                    @endif
                    @if($book->language)
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Bahasa</p>
                        <p class="text-sm font-semibold text-slate-700">{{ $book->language == 'id' ? 'Indonesia' : ($book->language == 'en' ? 'Inggris' : $book->language) }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Eksemplar</p>
                        <p class="text-sm font-semibold text-slate-700">{{ $book->items_count ?? $book->items->count() }} buku</p>
                    </div>
                </div>

                {{-- Description --}}
                @if($book->description)
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-3">Deskripsi</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">{{ $book->description }}</p>
                </div>
                @endif

                {{-- Availability & Location --}}
                @php
                    $availableItems = $book->items->where('status', 'Tersedia');
                    $totalAvailable = $availableItems->count();
                @endphp
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-3">Ketersediaan & Lokasi</h3>
                    @if($book->items->isNotEmpty())
                        <div class="space-y-2">
                            @foreach($book->items->take(5) as $item)
                            <div class="flex items-center justify-between bg-slate-50 rounded-lg px-4 py-3 border border-slate-100">
                                <div class="flex items-center gap-3">
                                    <i class="bi bi-bookshelf text-slate-400"></i>
                                    <span class="text-sm text-slate-700">{{ $item->location->name ?? 'Rak Umum' }}</span>
                                    @if($item->call_number)
                                    <span class="text-xs text-slate-400 font-mono">{{ $item->call_number }}</span>
                                    @endif
                                </div>
                                @if($item->status === 'Tersedia')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md">
                                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> Tersedia
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-500 bg-red-50 px-2.5 py-1 rounded-md">
                                        <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div> Dipinjam
                                    </span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-400 italic">Tidak ada data eksemplar.</p>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap gap-3">
                    @if($totalAvailable > 0)
                        <form action="{{ route('member.reservations.store', $book->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-gradient-blue text-white font-bold py-3 px-8 rounded-xl transition-colors shadow-sm flex items-center gap-2" onclick="return confirm('Apakah Anda yakin ingin meminjam/memesan buku ini?')">
                                <i class="bi bi-book"></i> Pinjam / Pesan Buku
                            </button>
                        </form>
                    @elseif(isset($book->items_count) && $book->items_count == 0)
                        <button type="button" class="bg-slate-100 text-slate-500 font-bold py-3 px-8 rounded-xl border border-slate-200 flex items-center gap-2 cursor-not-allowed">
                            <i class="bi bi-x-circle"></i> Stok Kosong
                        </button>
                    @else
                        <button type="button" class="bg-amber-100 text-amber-800 font-bold py-3 px-8 rounded-xl border border-amber-200 flex items-center gap-2 cursor-not-allowed">
                            <i class="bi bi-clock"></i> Buku Habis Dipinjam
                        </button>
                    @endif

                    {{-- Wishlist Toggle --}}
                    @if($inWishlist)
                    <form action="{{ route('member.wishlist.destroy', $book) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 font-bold py-3 px-6 rounded-xl transition-colors border border-red-200 flex items-center gap-2">
                            <i class="bi bi-heart-fill"></i> Hapus dari Favorit
                        </button>
                    </form>
                    @else
                    <form action="{{ route('member.wishlist.store', $book) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-pink-50 hover:bg-pink-100 text-pink-600 font-bold py-3 px-6 rounded-xl transition-colors border border-pink-200 flex items-center gap-2">
                            <i class="bi bi-heart"></i> ❤️ Simpan
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
