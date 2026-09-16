@extends('layouts.member')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="space-y-6 pb-8">

    {{-- Header --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">
                Riwayat Peminjaman Buku
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Catatan semua transaksi peminjaman buku yang pernah kamu lakukan.
            </p>
        </div>

        <a href="{{ route('member.catalog') }}" 
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition-colors flex-shrink-0 no-underline">
            <i class="bi bi-search"></i>
            <span>Cari Buku Baru</span>
        </a>
    </div>

    {{-- Search Bar --}}
    <div class="bg-white rounded-xl border border-slate-200 p-3">
        <form action="{{ route('member.loans') }}" method="GET" class="flex gap-2">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-3 flex items-center text-slate-400">
                    <i class="bi bi-search text-xs"></i>
                </span>
                <input type="text" 
                       name="q" 
                       value="{{ request('q') }}" 
                       placeholder="Cari judul buku dalam riwayat..."
                       class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-indigo-500 outline-none">
            </div>

            <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition-colors cursor-pointer">
                Cari
            </button>

            @if(request('q'))
                <a href="{{ route('member.loans') }}" class="px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-medium transition-colors no-underline">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Loans List --}}
    @if($loans->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center max-w-md mx-auto">
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl mx-auto mb-2">
                <i class="bi bi-clock-history"></i>
            </div>
            <h3 class="font-bold text-slate-700 text-sm">Belum Ada Riwayat</h3>
            <p class="text-xs text-slate-400 mt-1">Kamu belum pernah meminjam buku atau pencarian tidak cocok.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">Buku</th>
                            <th class="py-3.5 px-4">Tgl Pinjam</th>
                            <th class="py-3.5 px-4">Tgl Kembali</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($loans as $loan)
                            @php
                                $book = $loan->bookItem?->book;
                                $isReturned = $loan->status === 'Dikembalikan';
                                $isLoaned = $loan->status === 'Dipinjam';
                                $daysLeft = now()->diffInDays($loan->due_date, false);
                                $isOverdue = $isLoaned && $daysLeft < 0;
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-13 rounded bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200">
                                            @if($book && $book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
                                                <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-slate-200 flex items-center justify-center text-[10px] text-slate-400 font-bold">BK</div>
                                            @endif
                                        </div>
                                        <div class="min-w-0 max-w-xs sm:max-w-md">
                                            <p class="font-bold text-slate-800 truncate">{{ $book?->title ?? 'Buku' }}</p>
                                            <p class="text-[11px] text-slate-400 truncate">{{ $book?->main_author ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-600">
                                    {{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-600">
                                    @if($isReturned && $loan->return_date)
                                        <span class="text-emerald-700 font-semibold">{{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y') }}</span>
                                    @else
                                        <span class="{{ $isOverdue ? 'text-rose-600 font-bold' : 'text-slate-600' }}">
                                            {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($isReturned)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                                            <span>Selesai</span>
                                        </span>
                                    @elseif($isOverdue)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-rose-700 bg-rose-50 px-2.5 py-0.5 rounded-full">
                                            <span>Terlambat</span>
                                        </span>
                                    @elseif($isLoaned)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-full">
                                            <span>Dipinjam</span>
                                        </span>
                                    @else
                                        <span class="text-slate-500">{{ $loan->status }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    @if($book)
                                        <a href="{{ route('member.catalog.show', $book) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold no-underline">
                                            Lihat Buku
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($loans->hasPages())
                <div class="p-4 border-t border-slate-100 flex justify-center">
                    {{ $loans->links() }}
                </div>
            @endif
        </div>
    @endif

</div>
@endsection
