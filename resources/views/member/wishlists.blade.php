@extends('layouts.member')

@section('title', 'Buku Favorit')

@section('content')
<div class="space-y-6 pb-8">

    {{-- Header --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">
                Buku Favorit
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Daftar buku yang telah kamu simpan untuk dibaca atau dipinjam nanti.
            </p>
        </div>

        <a href="{{ route('member.catalog') }}" 
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition-colors flex-shrink-0 no-underline">
            <i class="bi bi-search"></i>
            <span>Cari Buku Lain</span>
        </a>
    </div>

    {{-- Wishlist Grid / Empty --}}
    @if($wishlists->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center max-w-md mx-auto">
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl mx-auto mb-2">
                <i class="bi bi-heart"></i>
            </div>
            <h3 class="font-bold text-slate-700 text-sm">Daftar Favorit Kosong</h3>
            <p class="text-xs text-slate-400 mt-1 mb-4">Belum ada buku yang kamu simpan ke daftar favorit.</p>
            <a href="{{ route('member.catalog') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold hover:bg-slate-200 transition-colors no-underline">
                Jelajahi Katalog
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            @foreach($wishlists as $wishlist)
                @php 
                    $book = $wishlist->book;
                    $isDigital = $book?->isDigital();
                    $available = $book?->available_items_count > 0;
                @endphp

                @if($book)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col justify-between hover:border-indigo-300 hover:shadow-sm transition-all group">
                    <div>
                        {{-- Cover Image --}}
                        <div class="aspect-[3/4] bg-slate-100 relative overflow-hidden border-b border-slate-100">
                            @if($book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400 text-xs font-bold p-2 text-center">
                                    {{ strtoupper(substr($book->title, 0, 2)) }}
                                </div>
                            @endif

                            {{-- Remove Button --}}
                            <div class="absolute top-2 right-2">
                                <form action="{{ route('member.wishlist.destroy', $book) }}" method="POST"
                                      onsubmit="return confirm('Hapus dari favorit?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-7 h-7 rounded-full bg-white/90 shadow-sm flex items-center justify-center text-rose-500 hover:bg-rose-500 hover:text-white transition-colors cursor-pointer" 
                                            title="Hapus">
                                        <i class="bi bi-x-lg text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

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
                                        <span>Tersedia</span>
                                    </span>
                                @elseif($isDigital)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-medium text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">
                                        <span>E-Book</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                                        <span>Dipinjam</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
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
                @endif
            @endforeach
        </div>

        @if($wishlists->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $wishlists->links() }}
            </div>
        @endif
    @endif

</div>
@endsection
