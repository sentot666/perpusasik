@extends('layouts.member')

@section('title', __('Pemesanan Buku'))

@section('content')
<div class="space-y-6 pb-8">

    {{-- Header --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">
                {{ __('Pemesanan Buku (Reservasi)') }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                {{ __('Buku yang kamu pesan akan disiapkan oleh petugas perpustakaan untuk diambil saat jam istirahat.') }}
            </p>
        </div>

        <a href="{{ route('member.catalog') }}" 
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition-colors flex-shrink-0 no-underline">
            <i class="bi bi-search"></i>
            <span>{{ __('Pesan Buku Lain') }}</span>
        </a>
    </div>

    {{-- Reservations List / Empty --}}
    @if($reservations->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center max-w-md mx-auto">
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl mx-auto mb-2">
                <i class="bi bi-bookmark"></i>
            </div>
            <h3 class="font-bold text-slate-700 text-sm">{{ __('Belum Ada Pemesanan') }}</h3>
            <p class="text-xs text-slate-400 mt-1 mb-4">{{ __('Kamu belum memesan buku apapun untuk disiapkan di perpustakaan.') }}</p>
            <a href="{{ route('member.catalog') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold hover:bg-slate-200 transition-colors no-underline">
                {{ __('Cari Buku di Katalog') }}
            </a>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">{{ __('Buku') }}</th>
                            <th class="py-3.5 px-4">{{ __('Tanggal Pesan') }}</th>
                            <th class="py-3.5 px-4">{{ __('Batas Ambil') }}</th>
                            <th class="py-3.5 px-4">{{ __('Status') }}</th>
                            <th class="py-3.5 px-4 text-right">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($reservations as $res)
                            @php
                                $book = $res->book;
                                $isWaiting = $res->status == 'Menunggu';
                                $isReady = $res->status == 'Siap';
                                $isDone = $res->status == 'Selesai';
                                $isCancelled = $res->status == 'Dibatalkan';
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
                                            <p class="font-bold text-slate-800 truncate">{{ $book?->title ?? __('Buku') }}</p>
                                            <p class="text-[11px] text-slate-400 truncate">{{ $book?->main_author ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-600">
                                    {{ $res->reserve_date ? $res->reserve_date->format('d M Y') : '-' }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-slate-600">
                                    {{ $res->expired_date ? $res->expired_date->format('d M Y') : '-' }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    @if($isReady)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                                            <i class="bi bi-check-circle-fill"></i>
                                            <span>{{ __('Siap Diambil') }}</span>
                                        </span>
                                    @elseif($isWaiting)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-medium text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-full">
                                            <span>{{ __('Menunggu Petugas') }}</span>
                                        </span>
                                    @elseif($isDone)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-medium text-slate-600 bg-slate-100 px-2.5 py-0.5 rounded-full">
                                            <span>{{ __('Sudah Diambil') }}</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[11px] font-medium text-slate-400 bg-slate-100 px-2.5 py-0.5 rounded-full">
                                            <span>{{ __('Dibatalkan') }}</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    @if($isWaiting || $isReady)
                                        <form action="{{ route('member.reservations.destroy', $res->id) }}" method="POST" class="inline"
                                              data-confirm="{{ __('Apakah kamu yakin ingin membatalkan pesanan buku ini?') }}"
                                              data-confirm-title="{{ __('Batalkan Pesanan?') }}"
                                              data-confirm-subtitle="{{ __('Pesanan yang dibatalkan tidak akan disiapkan oleh petugas perpustakaan.') }}"
                                              data-confirm-button="{{ __('Ya, Batalkan') }}"
                                              data-confirm-cancel="{{ __('Kembali') }}"
                                              data-confirm-danger="true">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold cursor-pointer text-xs">
                                                {{ __('Batalkan') }}
                                            </button>
                                        </form>
                                    @elseif($book)
                                        <a href="{{ route('member.catalog.show', $book) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold no-underline">
                                            {{ __('Lihat') }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
