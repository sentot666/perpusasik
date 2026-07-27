@extends('layouts.app')

@section('title', 'Wishlist / Favorit')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">❤️ Wishlist Saya</h1>
        <p class="text-sm text-slate-500 mt-1">Buku-buku favorit yang ingin Anda pinjam.</p>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl flex items-center gap-3">
        <i class="bi bi-check-circle-fill text-emerald-500"></i>
        {{ session('success') }}
    </div>
    @endif

    @if($wishlists->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-200 border-dashed py-20 text-center">
        <div class="w-20 h-20 bg-pink-50 rounded-full flex items-center justify-center text-3xl text-pink-300 mx-auto mb-5">
            <i class="bi bi-heart"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-700 mb-2">Wishlist Masih Kosong</h3>
        <p class="text-slate-400 text-sm mb-6">Temukan buku yang menarik dan simpan ke favorit Anda!</p>
        <a href="{{ route('member.catalog') }}" class="btn-gradient-blue text-white font-bold py-2.5 px-6 rounded-xl inline-flex items-center gap-2">
            <i class="bi bi-search"></i> Jelajahi Katalog
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($wishlists as $wishlist)
        @php $book = $wishlist->book; @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden group flex flex-col">
            {{-- Cover --}}
            <a href="{{ route('member.catalog.show', $book) }}" class="block aspect-[16/7] overflow-hidden bg-slate-100 relative">
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    @php
                        $colors = ['from-blue-500 to-indigo-600', 'from-emerald-400 to-teal-600', 'from-orange-400 to-red-500', 'from-purple-500 to-pink-600'];
                        $gradient = $colors[crc32($book->title) % count($colors)];
                        $words = explode(' ', $book->title);
                        $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                    @endphp
                    <div class="w-full h-full bg-gradient-to-br {{ $gradient }} flex items-center justify-center text-white font-bold text-3xl group-hover:scale-105 transition-transform duration-500">
                        {{ $initials }}
                    </div>
                @endif

                {{-- Remove from wishlist overlay --}}
                <div class="absolute top-3 right-3">
                    <form action="{{ route('member.wishlist.destroy', $book) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500 hover:bg-red-50 shadow transition-colors" title="Hapus dari Wishlist">
                            <i class="bi bi-heart-fill text-sm"></i>
                        </button>
                    </form>
                </div>
            </a>

            {{-- Info --}}
            <div class="p-5 flex flex-col flex-grow">
                <h3 class="font-bold text-slate-800 line-clamp-2 leading-snug mb-1 text-sm">
                    <a href="{{ route('member.catalog.show', $book) }}" class="hover:text-indigo-600 transition-colors">{{ $book->title }}</a>
                </h3>
                <p class="text-xs text-slate-500 mb-4">{{ $book->main_author ?? '-' }}</p>

                <div class="mt-auto">
                    @php $available = $book->availableItems?->count() ?? 0; @endphp
                    @if($available > 0)
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md mb-3">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> Tersedia {{ $available }}
                        </div>
                    @else
                        <div class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-400 bg-slate-50 px-2.5 py-1 rounded-md mb-3">
                            <div class="w-1.5 h-1.5 rounded-full bg-slate-400"></div> Semua Dipinjam
                        </div>
                    @endif

                    <a href="{{ route('member.catalog.show', $book) }}" class="w-full btn-gradient-blue text-white text-xs font-bold py-2.5 rounded-xl text-center block transition-colors">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $wishlists->links() }}</div>
    @endif
</div>
@endsection
