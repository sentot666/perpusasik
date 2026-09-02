@extends('layouts.app')

@section('title', 'Buku Saya')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-800">Buku Saya</h1>
        <p class="text-sm text-slate-500 mt-1">Daftar buku yang sedang Anda pinjam saat ini.</p>
    </div>



    @if($activeLoans->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-200 border-dashed py-20 text-center">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-3xl text-slate-300 mx-auto mb-5">
            <i class="bi bi-book"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-700 mb-2">Belum Ada Buku Dipinjam</h3>
        <p class="text-slate-400 text-sm mb-6">Anda belum meminjam buku apapun saat ini.</p>
        <a href="{{ route('member.catalog') }}" class="btn-gradient-blue text-white font-bold py-2.5 px-6 rounded-xl inline-flex items-center gap-2">
            <i class="bi bi-search"></i> Cari Buku
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($activeLoans as $loan)
        @php
            $daysLeft = now()->diffInDays($loan->due_date, false);
            $isOverdue = $daysLeft < 0;
            $isUrgent = !$isOverdue && $daysLeft <= 3;
        @endphp
        <div class="bg-white rounded-2xl shadow-sm border {{ $isOverdue ? 'border-red-300' : ($isUrgent ? 'border-amber-300' : 'border-slate-200') }} overflow-hidden group">
            <div class="flex gap-4 p-5">
                {{-- Cover --}}
                <div class="w-16 flex-shrink-0 aspect-[3/4] rounded-lg overflow-hidden bg-slate-100 flex items-center justify-center">
                    @if($loan->bookItem?->book?->cover_image)
                        <img src="{{ asset('storage/' . $loan->bookItem->book->cover_image) }}" class="w-full h-full object-cover">
                    @else
                        @php
                            $title = $loan->bookItem?->book?->title ?? 'BK';
                            $words = explode(' ', $title);
                            $init = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                            $colors = ['from-blue-500 to-indigo-600', 'from-emerald-400 to-teal-600', 'from-orange-400 to-red-500', 'from-purple-500 to-pink-600'];
                            $grad = $colors[crc32($title) % count($colors)];
                        @endphp
                        <div class="w-full h-full bg-gradient-to-br {{ $grad }} flex items-center justify-center text-white font-bold text-sm">{{ $init }}</div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-slate-800 leading-snug line-clamp-2 text-sm mb-1">
                        {{ $loan->bookItem?->book?->title ?? 'Judul tidak tersedia' }}
                    </h3>
                    <p class="text-xs text-slate-500 mb-3">{{ $loan->bookItem?->book?->main_author ?? '-' }}</p>

                    <div class="space-y-1">
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-400">Dipinjam</span>
                            <span class="text-slate-600 font-medium">{{ \Carbon\Carbon::parse($loan->loan_date)->isoFormat('D MMM YYYY') }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-400">Jatuh Tempo</span>
                            <span class="{{ $isOverdue ? 'text-red-600 font-bold' : ($isUrgent ? 'text-amber-600 font-bold' : 'text-slate-600 font-medium') }}">
                                {{ \Carbon\Carbon::parse($loan->due_date)->isoFormat('D MMM YYYY') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Status Banner --}}
            @if($isOverdue)
            <div class="bg-red-50 border-t border-red-200 px-5 py-2.5 flex items-center gap-2">
                <i class="bi bi-exclamation-circle-fill text-red-500 text-sm"></i>
                <span class="text-xs font-bold text-red-700">Terlambat {{ abs($daysLeft) }} Hari</span>
            </div>
            @elseif($isUrgent)
            <div class="bg-amber-50 border-t border-amber-200 px-5 py-2.5 flex items-center gap-2">
                <i class="bi bi-clock-fill text-amber-500 text-sm"></i>
                <span class="text-xs font-bold text-amber-700">Segera dikembalikan — {{ $daysLeft }} hari lagi</span>
            </div>
            @else
            <div class="bg-slate-50 border-t border-slate-100 px-5 py-2.5 flex items-center gap-2">
                <i class="bi bi-check-circle text-emerald-400 text-sm"></i>
                <span class="text-xs font-medium text-slate-500">{{ $daysLeft }} hari tersisa</span>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $activeLoans->links() }}</div>
    @endif

</div>
@endsection
