@extends('layouts.member')

@section('title', 'Buku Saya')

@section('content')
<div class="space-y-6 pb-8">

    {{-- Header --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">
                Buku yang Sedang Dipinjam
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Total {{ $activeLoans->total() }} buku sedang kamu bawa. Pastikan dikembalikan sebelum tanggal jatuh tempo ya.
            </p>
        </div>

        <a href="{{ route('member.catalog') }}" 
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition-colors flex-shrink-0 no-underline">
            <i class="bi bi-search"></i>
            <span>Cari Buku Lain</span>
        </a>
    </div>

    {{-- Books Grid / Empty State --}}
    @if($activeLoans->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center max-w-lg mx-auto">
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl mx-auto mb-3">
                <i class="bi bi-journal-check"></i>
            </div>
            <h3 class="font-bold text-slate-700 text-sm">Tidak Ada Peminjaman Aktif</h3>
            <p class="text-xs text-slate-500 mt-1 mb-4">Kamu sedang tidak meminjam buku perpustakaan saat ini.</p>
            <a href="{{ route('member.catalog') }}" 
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 text-xs font-semibold transition-colors no-underline">
                <i class="bi bi-compass"></i>
                <span>Jelajahi Koleksi Buku</span>
            </a>
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

                <div class="bg-white rounded-2xl border {{ $isOverdue ? 'border-rose-300' : ($isUrgent ? 'border-amber-300' : 'border-slate-200') }} p-4 sm:p-5 flex flex-col justify-between">
                    <div>
                        {{-- Status Pill Top --}}
                        <div class="flex items-center justify-between gap-2 mb-3">
                            @if($isOverdue)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <span>Terlambat {{ abs($daysLeft) }} hari</span>
                                </span>
                            @elseif($isUrgent)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                    <i class="bi bi-clock-fill"></i>
                                    <span>{{ $daysLeft == 0 ? 'Hari ini terakhir' : $daysLeft . ' hari lagi' }}</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <i class="bi bi-check-circle"></i>
                                    <span>Sisa {{ $daysLeft }} hari</span>
                                </span>
                            @endif

                            <span class="text-[10px] font-mono text-slate-400">
                                #{{ $loan->bookItem?->item_code ?? 'EKSEMPLAR' }}
                            </span>
                        </div>

                        {{-- Book Info --}}
                        <div class="flex gap-3.5 items-start">
                            <div class="w-16 aspect-[3/4] rounded-lg overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0">
                                @if($book && $book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-500 text-xs font-bold">
                                        BK
                                    </div>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-xs sm:text-sm text-slate-800 line-clamp-2 leading-snug">
                                    {{ $book?->title ?? 'Judul Buku' }}
                                </h3>
                                <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">
                                    {{ $book?->main_author ?? '-' }}
                                </p>

                                <div class="mt-2.5 text-[11px] space-y-0.5 text-slate-500">
                                    <div>Pinjam: <span class="font-medium text-slate-700">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</span></div>
                                    <div>Batas Kembali: <span class="font-bold {{ $isOverdue ? 'text-rose-600' : ($isUrgent ? 'text-amber-700' : 'text-slate-700') }}">{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-2">
                        @if($book)
                            <a href="{{ route('member.catalog.show', $book) }}" 
                               class="flex-1 text-center py-2 px-3 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors no-underline">
                                Detail Buku
                            </a>

                            @if($book->isDigital())
                                <a href="{{ route('opac.read', $book) }}" target="_blank"
                                   class="py-2 px-3 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold text-xs transition-colors no-underline flex items-center gap-1">
                                    <i class="bi bi-book"></i>
                                    <span>E-Book</span>
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($activeLoans->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $activeLoans->links() }}
            </div>
        @endif
    @endif

</div>
@endsection
