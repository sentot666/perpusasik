@extends('layouts.app')

@section('title', 'Denda')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Denda Keterlambatan</h1>
        <p class="text-sm text-slate-500 mt-1">Rincian denda yang perlu Anda bayarkan.</p>
    </div>

    @if($fines->isEmpty())
    <div class="bg-white rounded-2xl border border-emerald-200 py-20 text-center">
        <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center text-4xl mx-auto mb-5">
            🎉
        </div>
        <h3 class="text-xl font-bold text-emerald-700 mb-2">Tidak Ada Denda!</h3>
        <p class="text-slate-400 text-sm">Anda tidak memiliki tunggakan denda. Terima kasih sudah tepat waktu.</p>
    </div>
    @else
    {{-- Total Card --}}
    <div class="bg-gradient-to-r from-red-500 to-rose-600 rounded-2xl p-6 text-white shadow-lg shadow-red-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm font-medium mb-1">Total Denda Belum Dibayar</p>
                <p class="text-4xl font-extrabold">Rp {{ number_format($totalFines, 0, ',', '.') }}</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-3xl">
                💰
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-red-400/50">
            <p class="text-red-100 text-xs">Silakan hubungi petugas perpustakaan untuk membayar denda Anda.</p>
        </div>
    </div>

    {{-- Fines Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
            <i class="bi bi-list-ul text-slate-400"></i>
            <h3 class="font-bold text-slate-700">Rincian Denda</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                        <th class="px-6 py-4">Buku</th>
                        <th class="px-6 py-4">Keterlambatan</th>
                        <th class="px-6 py-4">Nominal</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($fines as $fine)
                    @php
                        $returnDate = $fine->return_date ?? now();
                        $daysLate = \Carbon\Carbon::parse($fine->due_date)->diffInDays($returnDate);
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-800 max-w-[220px] line-clamp-2">
                                {{ $fine->bookItem?->book?->title ?? 'Judul tidak tersedia' }}
                            </div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $fine->transaction_code }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600 bg-red-50 px-2.5 py-1 rounded-md">
                                <i class="bi bi-clock-fill"></i> {{ $daysLate }} hari
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-red-600">
                            Rp {{ number_format($fine->fine_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($fine->fine_paid)
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Lunas
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-700 bg-red-50 px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Belum Dibayar
                                </span>
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
