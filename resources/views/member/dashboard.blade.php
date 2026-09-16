@extends('layouts.member')

@section('title', 'Beranda')

@section('content')
<div class="space-y-6 pb-8">

    {{-- 1. Clean Welcoming Search Banner --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7">
        <div class="max-w-2xl">
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight mb-1">
                Selamat Datang, {{ $member->name }}
            </h1>
            <p class="text-slate-500 text-xs sm:text-sm mb-5 leading-relaxed">
                Cari buku cerita, ensiklopedia, atau buku pelajaran yang ingin kamu baca hari ini.
            </p>

            {{-- Clean Search Bar --}}
            <form action="{{ route('member.catalog') }}" method="GET" class="flex gap-2">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                        <i class="bi bi-search text-sm"></i>
                    </span>
                    <input type="text" 
                           name="q" 
                           placeholder="Ketik judul buku, pengarang, atau topik pelajaran..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                </div>
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs sm:text-sm px-5 py-2.5 rounded-xl transition-colors cursor-pointer flex-shrink-0">
                    Cari Buku
                </button>
            </form>
        </div>
    </div>

    {{-- 2. Compact Clean Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        {{-- Stat 1: Pinjaman Aktif --}}
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 mb-1">Buku Dipinjam</p>
                <p class="text-xl sm:text-2xl font-bold text-slate-800">{{ $activeLoans->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg flex-shrink-0">
                <i class="bi bi-book"></i>
            </div>
        </div>

        {{-- Stat 2: Terlambat / Status Waktu --}}
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 mb-1">Perlu Dikembalikan</p>
                <p class="text-xl sm:text-2xl font-bold {{ $overdueLoans->count() > 0 ? 'text-rose-600' : 'text-slate-800' }}">
                    {{ $overdueLoans->count() }}
                </p>
            </div>
            <div class="w-10 h-10 rounded-xl {{ $overdueLoans->count() > 0 ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center text-lg flex-shrink-0">
                <i class="bi bi-clock"></i>
            </div>
        </div>

        {{-- Stat 3: Reservasi / Pesanan Aktif --}}
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 mb-1">Buku Dipesan</p>
                <p class="text-xl sm:text-2xl font-bold text-slate-800">{{ $activeReservations->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg flex-shrink-0">
                <i class="bi bi-bookmark"></i>
            </div>
        </div>

        {{-- Stat 4: Wishlist / Favorit --}}
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 mb-1">Buku Favorit</p>
                <p class="text-xl sm:text-2xl font-bold text-slate-800">{{ $wishlistCount }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg flex-shrink-0">
                <i class="bi bi-heart"></i>
            </div>
        </div>
    </div>

    {{-- 3. Buku yang Sedang Dipinjam --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
            <div>
                <h2 class="font-bold text-slate-800 text-sm sm:text-base">
                    Buku yang Sedang Dipinjam
                </h2>
                <p class="text-xs text-slate-500">Daftar buku yang perlu kamu jaga dan kembalikan tepat waktu.</p>
            </div>

            @if($activeLoans->isNotEmpty())
                <a href="{{ route('member.my-books') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 no-underline flex items-center gap-1">
                    <span>Lihat Semua</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            @endif
        </div>

        @if($activeLoans->isEmpty())
            <div class="py-8 text-center">
                <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center text-xl mx-auto mb-2">
                    <i class="bi bi-journal"></i>
                </div>
                <p class="text-xs font-semibold text-slate-600">Saat ini tidak ada buku yang sedang dipinjam.</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Yuk cari cerita menarik di katalog!</p>
                <div class="mt-3">
                    <a href="{{ route('member.catalog') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-xs font-semibold text-slate-700 transition-colors no-underline">
                        <i class="bi bi-search"></i>
                        <span>Buka Katalog Buku</span>
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($activeLoans as $loan)
                    @php
                        $book = $loan->bookItem?->book;
                        $daysLeft = (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($loan->due_date)->startOfDay(), false);
                        $isOverdue = $daysLeft < 0;
                        $isUrgent = !$isOverdue && $daysLeft <= 2;
                    @endphp
                    <div class="p-3.5 rounded-xl border {{ $isOverdue ? 'border-rose-200 bg-rose-50/20' : ($isUrgent ? 'border-amber-200 bg-amber-50/20' : 'border-slate-200') }} flex gap-3.5 items-center">
                        <div class="w-14 aspect-[3/4] rounded-lg overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0">
                            @if($book && $book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-500 text-xs font-bold">
                                    BK
                                </div>
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            <h3 class="font-bold text-xs text-slate-800 truncate leading-snug">
                                {{ $book?->title ?? 'Judul Buku' }}
                            </h3>
                            <p class="text-[11px] text-slate-500 truncate mt-0.5">
                                {{ $book?->main_author ?? '-' }}
                            </p>
                            
                            <div class="mt-2 flex items-center gap-2">
                                @if($isOverdue)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <span>Terlambat {{ abs($daysLeft) }} hari</span>
                                    </span>
                                @elseif($isUrgent)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md">
                                        <i class="bi bi-clock-fill"></i>
                                        <span>Sisa {{ $daysLeft == 0 ? 'hari ini' : $daysLeft . ' hari' }}</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] font-medium text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md">
                                        <span>Sisa {{ $daysLeft }} hari</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- 4. Rekomendasi Buku Terkini --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
            <div>
                <h2 class="font-bold text-slate-800 text-sm sm:text-base">
                    Buku Pilihan Terbaru
                </h2>
                <p class="text-xs text-slate-500">Koleksi baru yang siap dipinjam di perpustakaan.</p>
            </div>
            <a href="{{ route('member.catalog') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 no-underline flex items-center gap-1">
                <span>Lihat Semua Buku</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3 sm:gap-4">
            @foreach($recommendedBooks as $book)
                <a href="{{ route('member.catalog.show', $book) }}" class="group block no-underline">
                    <div class="aspect-[3/4] rounded-xl overflow-hidden bg-slate-100 border border-slate-200 mb-2 relative group-hover:border-indigo-300 transition-colors">
                        @if($book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400 text-xs font-bold p-2 text-center">
                                {{ strtoupper(substr($book->title, 0, 2)) }}
                            </div>
                        @endif

                        @if($book->isDigital())
                            <span class="absolute top-1.5 left-1.5 bg-indigo-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">
                                E-Book
                            </span>
                        @endif
                    </div>
                    <h3 class="font-semibold text-xs text-slate-800 line-clamp-1 group-hover:text-indigo-600 transition-colors leading-tight">
                        {{ $book->title }}
                    </h3>
                    <p class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">
                        {{ $book->main_author ?? 'Penulis' }}
                    </p>
                </a>
            @endforeach
        </div>
    </div>

</div>
@endsection
