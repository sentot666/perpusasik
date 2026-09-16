@extends('layouts.member')

@section('title', 'Status Denda')

@section('content')
<div class="space-y-6 pb-8">

    {{-- Header --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">
                Status Denda Keterlambatan
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Informasi administrasi jika terdapat keterlambatan dalam pengembalian buku.
            </p>
        </div>

        <a href="{{ route('member.my-books') }}" 
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition-colors flex-shrink-0 no-underline">
            <i class="bi bi-book"></i>
            <span>Cek Buku Saya</span>
        </a>
    </div>

    @if($fines->isEmpty())
        {{-- Clean Zero Fines Status --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center max-w-md mx-auto">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mx-auto mb-3">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-sm">Tidak Ada Denda</h3>
            <p class="text-xs text-slate-500 mt-1 mb-4">
                Kamu tidak memiliki tunggakan denda keterlambatan. Terima kasih sudah selalu mengembalikan buku tepat waktu!
            </p>
            <a href="{{ route('member.catalog') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors no-underline">
                Cari Buku Lain
            </a>
        </div>
    @else
        {{-- Total Fine Card --}}
        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-rose-700">Total Denda Belum Selesai</p>
                <p class="text-2xl font-bold text-rose-800 mt-0.5">Rp {{ number_format($totalFines, 0, ',', '.') }}</p>
                <p class="text-[11px] text-rose-600 mt-1">Silakan hubungi petugas di meja perpustakaan untuk menyelesaikan administrasi.</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-lg flex-shrink-0">
                <i class="bi bi-exclamation-circle-fill"></i>
            </div>
        </div>

        {{-- Fines Table --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">Buku</th>
                            <th class="py-3.5 px-4">Keterlambatan</th>
                            <th class="py-3.5 px-4">Jumlah Denda</th>
                            <th class="py-3.5 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($fines as $fine)
                            @php
                                $book = $fine->bookItem?->book;
                                $returnDate = $fine->return_date ?? now();
                                $daysLate = \Carbon\Carbon::parse($fine->due_date)->diffInDays($returnDate);
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3.5 px-4 sm:px-6">
                                    <p class="font-bold text-slate-800">{{ $book?->title ?? 'Judul Buku' }}</p>
                                    <p class="text-[11px] text-slate-400 font-mono">{{ $fine->transaction_code }}</p>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-rose-600 font-semibold">
                                    {{ $daysLate }} hari
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap font-bold text-slate-800">
                                    Rp {{ number_format($fine->fine_amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center text-[11px] font-semibold text-rose-700 bg-rose-50 px-2.5 py-0.5 rounded-full">
                                        Belum Dibayar
                                    </span>
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
