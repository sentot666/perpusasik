@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1></h1>
    <p>Selamat datang, <strong>{{ auth()->user()->name }}</strong> — {{ now()->translatedFormat('l, d F Y') }}</p>
</div>

{{-- ── Stat Cards ─────────────────────────────────────────────────────────── --}}
<div class="flex flex-wrap -mx-3 mb-6">
    <div class="w-full lg:w-1/4 w-1/2 px-4">
        <div class="text-white rounded-xl shadow-md overflow-hidden border-none transform transition duration-300 hover:scale-105 mb-4 lg:mb-0" style="background: linear-gradient(to bottom right, #fbbf24, #f97316) !important;">
            <div class="items-center p-6 flex gap-5">
                <div class="stat-icon bg-white/20 text-white rounded-xl flex items-center justify-center w-14 h-14 text-2xl shadow-sm backdrop-blur-sm">
                    <i class="bi bi-journals"></i>
                </div>
                <div>
                    <div class="stat-value text-white font-bold text-2xl">{{ number_format($stats['total_books']) }}</div>
                    <div class="stat-label text-white/90 text-sm font-medium">Total Judul Buku</div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full lg:w-1/4 w-1/2 px-4">
        <div class="text-white rounded-xl shadow-md overflow-hidden border-none transform transition duration-300 hover:scale-105 mb-4 lg:mb-0" style="background: linear-gradient(to bottom right, #34d399, #16a34a) !important;">
            <div class="items-center p-6 flex gap-5">
                <div class="stat-icon bg-white/20 text-white rounded-xl flex items-center justify-center w-14 h-14 text-2xl shadow-sm backdrop-blur-sm">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="stat-value text-white font-bold text-2xl">{{ number_format($stats['total_members']) }}</div>
                    <div class="stat-label text-white/90 text-sm font-medium">Total Anggota</div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full lg:w-1/4 w-1/2 px-4">
        <div class="text-white rounded-xl shadow-md overflow-hidden border-none transform transition duration-300 hover:scale-105 mb-4 lg:mb-0" style="background: linear-gradient(to bottom right, #fb923c, #ef4444) !important;">
            <div class="items-center p-6 flex gap-5">
                <div class="stat-icon bg-white/20 text-white rounded-xl flex items-center justify-center w-14 h-14 text-2xl shadow-sm backdrop-blur-sm">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
                <div>
                    <div class="stat-value text-white font-bold text-2xl">{{ number_format($stats['active_loans']) }}</div>
                    <div class="stat-label text-white/90 text-sm font-medium">Sedang Dipinjam</div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full lg:w-1/4 w-1/2 px-4">
        <div class="text-white rounded-xl shadow-md overflow-hidden border-none transform transition duration-300 hover:scale-105 mb-4 lg:mb-0" style="background: linear-gradient(to bottom right, #38bdf8, #2563eb) !important;">
            <div class="items-center p-6 flex gap-5">
                <div class="stat-icon bg-white/20 text-white rounded-xl flex items-center justify-center w-14 h-14 text-2xl shadow-sm backdrop-blur-sm">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <div class="stat-value text-white font-bold text-2xl">{{ number_format($stats['overdue_loans']) }}</div>
                    <div class="stat-label text-white/90 text-sm font-medium">Terlambat</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── 2nd Row ─────────────────────────────────────────────────────────────── --}}
<div class="flex flex-wrap -mx-3 mb-6">
    <div class="w-full lg:w-1/4 w-1/2 px-4">
        <div class="text-white rounded-xl shadow-md overflow-hidden border-none transform transition duration-300 hover:scale-105 mb-4 lg:mb-0" style="background: linear-gradient(to bottom right, #94a3b8, #4b5563) !important;">
            <div class="items-center p-6 flex gap-5">
                <div class="stat-icon bg-white/20 text-white rounded-xl flex items-center justify-center w-14 h-14 text-2xl shadow-sm backdrop-blur-sm">
                    <i class="bi bi-collection"></i>
                </div>
                <div>
                    <div class="stat-value text-white font-bold text-2xl">{{ number_format($stats['total_items']) }}</div>
                    <div class="stat-label text-white/90 text-sm font-medium">Total Eksemplar</div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full lg:w-1/4 w-1/2 px-4">
        <div class="text-white rounded-xl shadow-md overflow-hidden border-none transform transition duration-300 hover:scale-105 mb-4 lg:mb-0" style="background: linear-gradient(to bottom right, #fbbf24, #f97316) !important;">
            <div class="items-center p-6 flex gap-5">
                <div class="stat-icon bg-white/20 text-white rounded-xl flex items-center justify-center w-14 h-14 text-2xl shadow-sm backdrop-blur-sm">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <div class="stat-value text-white font-bold text-2xl">{{ number_format($stats['available_items']) }}</div>
                    <div class="stat-label text-white/90 text-sm font-medium">Eksemplar Tersedia</div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full lg:w-1/4 w-1/2 px-4">
        <div class="text-white rounded-xl shadow-md overflow-hidden border-none transform transition duration-300 hover:scale-105 mb-4 lg:mb-0" style="background: linear-gradient(to bottom right, #34d399, #16a34a) !important;">
            <div class="items-center p-6 flex gap-5">
                <div class="stat-icon bg-white/20 text-white rounded-xl flex items-center justify-center w-14 h-14 text-2xl shadow-sm backdrop-blur-sm">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div>
                    <div class="stat-value text-white font-bold text-2xl">{{ number_format($stats['loans_today']) }}</div>
                    <div class="stat-label text-white/90 text-sm font-medium">Pinjam Hari Ini</div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full lg:w-1/4 w-1/2 px-4">
        <div class="text-white rounded-xl shadow-md overflow-hidden border-none transform transition duration-300 hover:scale-105 mb-4 lg:mb-0" style="background: linear-gradient(to bottom right, #fb923c, #ef4444) !important;">
            <div class="items-center p-6 flex gap-5">
                <div class="stat-icon bg-white/20 text-white rounded-xl flex items-center justify-center w-14 h-14 text-2xl shadow-sm backdrop-blur-sm">
                    <i class="bi bi-calendar-x"></i>
                </div>
                <div>
                    <div class="stat-value text-white font-bold text-2xl">{{ number_format($stats['returns_today']) }}</div>
                    <div class="stat-label text-white/90 text-sm font-medium">Kembali Hari Ini</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Tables Row ───────────────────────────────────────────────────────────── --}}
<div class="flex flex-wrap -mx-3">

    {{-- Recent Loans --}}
    <div class="w-full lg:w-7/12 px-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 h-full overflow-hidden">
            <div class="justify-between items-center px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 flex py-4">
                <span><i class="bi bi-arrow-left-right text-amber-500 mr-2"></i>Peminjaman Terkini</span>
                <a href="{{ route('circulations.index') }}" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors px-4">Lihat Semua</a>
            </div>
            <div class="p-0">
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50">
                        <thead>
                            <tr>
                                <th>Anggota</th>
                                <th>Judul Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLoans as $loan)
                            <tr>
                                <td>
                                    <div class="font-medium">{{ $loan->member->name }}</div>
                                    <div class="text-slate-500" style="font-size:0.72rem">{{ $loan->member->member_code }}</div>
                                </td>
                                <td class="truncate" style="max-width:160px" title="{{ $loan->bookItem->book->title }}">
                                    {{ $loan->bookItem->book->title }}
                                </td>
                                <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="{{ $loan->is_overdue ? 'text-danger fw-600' : '' }}">
                                        {{ $loan->due_date->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>
                                    @if($loan->is_overdue)
                                        <span class="inline-flex py-1 text-xs font-medium rounded-md bg-red-100 text-red-700 px-2">Terlambat {{ $loan->days_overdue }}h</span>
                                    @elseif($loan->status === 'Dikembalikan')
                                        <span class="inline-flex py-1 text-xs font-medium rounded-md bg-emerald-100 text-emerald-700 px-2">Dikembalikan</span>
                                    @else
                                        <span class="inline-flex py-1 text-xs font-medium rounded-md bg-amber-100 text-amber-800 px-2">Dipinjam</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-slate-500 py-6">Belum ada transaksi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions + Overdue --}}
    <div class="w-full lg:w-5/12 px-4">
        {{-- Quick Actions --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-lightning-charge-fill text-amber-500 mr-2"></i>Aksi Cepat</div>
            <div class="p-8">
                <div class="grid gap-2">
                    <a href="{{ route('circulations.loan') }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 transition-colors text-white gap-2 py-2 px-6">
                        <i class="bi bi-box-arrow-right mr-2"></i>Proses Peminjaman
                    </a>
                    <a href="{{ route('circulations.return') }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-emerald-600 hover:bg-emerald-700 transition-colors text-white gap-2 py-2 px-6">
                        <i class="bi bi-box-arrow-in-left mr-2"></i>Proses Pengembalian
                    </a>
                    <a href="{{ route('members.create') }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-indigo-600 border border-slate-200 border-indigo-600 hover:bg-indigo-50 transition-colors gap-2 py-2 px-6">
                        <i class="bi bi-person-plus mr-2"></i>Tambah Anggota
                    </a>
                    <a href="{{ route('books.create') }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2 px-6">
                        <i class="bi bi-plus-circle mr-2"></i>Tambah Buku
                    </a>
                </div>
            </div>
        </div>

        {{-- Overdue List --}}
        @if($overdueLoans->count() > 0)
        <div class="border-danger bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden" style="border-color:#fee2e2!important">
            <div class="bg-red-600 text-white px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4">
                <i class="bi bi-exclamation-triangle-fill mr-2"></i>Terlambat ({{ $overdueLoans->count() }})
            </div>
            <div class="p-0">
                <ul class="divide-y divide-slate-100 flex flex-col">
                    @foreach($overdueLoans->take(5) as $loan)
                    <li class="block py-2 px-4">
                        <div class="justify-between items-start flex">
                            <div>
                                <div class="truncate font-medium" style="max-width:170px;font-size:0.82rem">{{ $loan->member->name }}</div>
                                <div class="text-slate-500" style="font-size:0.72rem">{{ $loan->bookItem->book->title }}</div>
                            </div>
                            <span class="inline-flex py-1 text-xs font-medium rounded-md bg-red-100 text-red-700 ml-2 px-2">{{ $loan->days_overdue }}h</span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
