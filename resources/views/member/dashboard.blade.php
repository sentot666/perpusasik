@extends('layouts.app')

@section('title', __('Dashboard Anggota'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Halo, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
            <p class="text-sm text-slate-500 mt-1">Selamat Datang di Perpustakaan Digital</p>
        </div>
        <div>
            <a href="{{ route('opac.index') }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue text-white px-5 py-2.5 transition-all shadow-sm">
                <i class="bi bi-search mr-2"></i> Cari Buku
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Sedang Dipinjam -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-6 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xl flex-shrink-0">
                📚
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Sedang Dipinjam</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $activeLoans->count() }}</h3>
            </div>
        </div>

        <!-- Total Dipinjam -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-6 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0">
                📖
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Dipinjam</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $totalLoans }}</h3>
            </div>
        </div>

        <!-- Jatuh Tempo -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-6 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="w-12 h-12 rounded-lg bg-red-50 text-red-600 flex items-center justify-center text-xl flex-shrink-0">
                ⏰
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Jatuh Tempo</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $overdueLoans->count() }}</h3>
            </div>
        </div>

        <!-- Denda -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-6 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="w-12 h-12 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center text-xl flex-shrink-0">
                💰
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Denda</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">Rp{{ number_format($totalFines, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Active Loans Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-lg font-bold text-slate-800">Buku yang Sedang Dipinjam</h2>
        </div>
        <div class="p-0">
            @if($activeLoans->isEmpty())
                <div class="text-center py-12">
                    <div class="text-4xl mx-auto mb-4 opacity-50">📚</div>
                    <p class="text-slate-500 font-medium">Anda tidak sedang meminjam buku apapun.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 text-slate-500 text-xs uppercase tracking-wider border-y border-slate-200">
                                <th class="px-6 py-4 font-medium w-16">Cover</th>
                                <th class="px-6 py-4 font-medium">Judul Buku</th>
                                <th class="px-6 py-4 font-medium">Tanggal Pinjam</th>
                                <th class="px-6 py-4 font-medium">Jatuh Tempo</th>
                                <th class="px-6 py-4 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach($activeLoans as $loan)
                                @php
                                    $book = $loan->bookItem->book;
                                    $daysRemaining = now()->startOfDay()->diffInDays($loan->due_date->startOfDay(), false);
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <!-- Cover Buku -->
                                    <td class="px-6 py-4">
                                        @if($book->cover_image)
                                            <img src="{{ Storage::url($book->cover_image) }}" alt="Cover" class="w-12 h-16 object-cover rounded shadow-sm border border-slate-200">
                                        @else
                                            <div class="w-12 h-16 bg-slate-100 rounded flex items-center justify-center text-slate-400 border border-slate-200">
                                                <i class="bi bi-book"></i>
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <!-- Judul Buku -->
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800">{{ $book->title }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5">Kode Item: {{ $loan->bookItem->item_code }}</div>
                                    </td>
                                    
                                    <!-- Tanggal Pinjam -->
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $loan->loan_date->format('d M Y') }}
                                    </td>
                                    
                                    <!-- Jatuh Tempo & Badge Peringatan -->
                                    <td class="px-6 py-4">
                                        <div class="text-slate-800 font-medium">{{ $loan->due_date->format('d M Y') }}</div>
                                        
                                        @if($daysRemaining < 0)
                                            <div class="mt-1 text-xs font-semibold text-red-600 flex items-center gap-1">
                                                <i class="bi bi-exclamation-circle-fill"></i> Terlambat {{ abs($daysRemaining) }} hari
                                            </div>
                                        @elseif($daysRemaining <= 3)
                                            <div class="mt-1 text-xs font-semibold text-orange-600 flex items-center gap-1">
                                                ⚠️ Jatuh tempo {{ $daysRemaining == 0 ? 'hari ini' : $daysRemaining . ' hari lagi' }}
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <!-- Status -->
                                    <td class="px-6 py-4">
                                        @if($daysRemaining < 0)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                                                Terlambat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                                                Dipinjam
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
