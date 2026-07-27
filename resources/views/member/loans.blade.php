@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Riwayat Peminjaman</h1>
            <p class="text-sm text-slate-500 mt-1">Semua riwayat transaksi peminjaman buku Anda.</p>
        </div>
    </div>

    {{-- Search Bar --}}
    <form action="{{ route('member.loans') }}" method="GET" class="flex gap-3">
        <div class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-3 flex items-center text-slate-400"><i class="bi bi-search"></i></span>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul buku..."
                class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 outline-none">
        </div>
        <button type="submit" class="btn-gradient-blue text-white text-sm font-bold px-5 py-2.5 rounded-xl">Cari</button>
        @if(request('q'))
        <a href="{{ route('member.loans') }}" class="flex items-center gap-1.5 px-4 py-2.5 text-sm text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
            <i class="bi bi-x"></i> Reset
        </a>
        @endif
    </form>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        @if($loans->isEmpty())
            <div class="text-center py-20">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center text-3xl mx-auto mb-5">
                    <i class="bi bi-clock-history"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700 mb-2">
                    {{ request('q') ? 'Tidak ada hasil untuk "' . request('q') . '"' : 'Belum Ada Riwayat' }}
                </h3>
                <p class="text-slate-400 text-sm">Anda belum memiliki riwayat peminjaman buku.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                            <th class="px-6 py-4">Buku</th>
                            <th class="px-6 py-4">Tgl Pinjam</th>
                            <th class="px-6 py-4">Tgl Kembali</th>
                            <th class="px-6 py-4">Denda</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($loans as $loan)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800 max-w-[240px] line-clamp-2">
                                    {{ $loan->bookItem?->book?->title ?? 'Judul tidak tersedia' }}
                                </div>
                                <div class="text-xs text-slate-400 mt-0.5">{{ $loan->transaction_code }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($loan->loan_date)->isoFormat('D MMM YYYY') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($loan->status === 'Dikembalikan' && $loan->return_date)
                                    <span class="text-emerald-600 font-medium">{{ \Carbon\Carbon::parse($loan->return_date)->isoFormat('D MMM YYYY') }}</span>
                                @elseif($loan->due_date)
                                    @php $daysLeft = now()->diffInDays($loan->due_date, false); @endphp
                                    <span class="{{ $daysLeft < 0 ? 'text-red-600 font-semibold' : 'text-slate-600' }}">
                                        {{ \Carbon\Carbon::parse($loan->due_date)->isoFormat('D MMM YYYY') }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($loan->fine_amount > 0)
                                    <div class="font-bold text-orange-600">Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}</div>
                                    <div class="text-xs {{ $loan->fine_paid ? 'text-emerald-600' : 'text-red-500' }}">{{ $loan->fine_paid ? 'Lunas' : 'Belum Lunas' }}</div>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($loan->status === 'Dipinjam')
                                    @php $overdue = now()->gt($loan->due_date); @endphp
                                    @if($overdue)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Terlambat
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Dipinjam
                                        </span>
                                    @endif
                                @elseif($loan->status === 'Dikembalikan')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                                        {{ $loan->status }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $loans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
