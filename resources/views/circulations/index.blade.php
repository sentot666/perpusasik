@extends('layouts.app')

@section('title', 'Transaksi Sirkulasi')
@section('page-title', 'Transaksi Sirkulasi')

@section('breadcrumb')
<li class="breadcrumb-item active">{{ __('Sirkulasi') }}</li>
@endsection

@section('content')
<div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-1">{{ __('Daftar Transaksi Sirkulasi') }}</h1>
        <p class="text-slate-500 text-xs sm:text-sm">{{ __('Pantau semua transaksi peminjaman, pengembalian, dan denda buku') }}</p>
    </div>
    <div class="flex flex-wrap gap-2 w-full sm:w-auto">
        <a href="{{ route('reports.export', ['type' => 'circulation'] + request()->all()) }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-medium rounded-lg btn-gradient-green text-white transition-colors gap-1.5 py-2 px-4 shadow-sm">
            <i class="bi bi-file-earmark-excel"></i> {{ __('Export Excel') }}
        </a>
        <a href="{{ route('circulations.loan') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white gap-1.5 py-2 px-4">
            <i class="bi bi-box-arrow-right"></i>{{ __('Peminjaman Baru') }}
        </a>
        <a href="{{ route('circulations.return') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white gap-1.5 py-2 px-4">
            <i class="bi bi-box-arrow-in-left"></i>{{ __('Pengembalian Baru') }}
        </a>
    </div>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="btn-gradient-yellow text-slate-900 border-0 shadow-sm rounded-xl overflow-hidden p-5">
        <div class="justify-between items-center flex">
            <div>
                <div class="text-2xl font-extrabold text-slate-800">{{ number_format($stats['active']) }}</div>
                <small class="uppercase text-xs font-semibold text-slate-700 tracking-wider">{{ __('Peminjaman Aktif') }}</small>
            </div>
            <i class="bi bi-arrow-left-right text-3xl font-bold opacity-30 text-slate-800"></i>
        </div>
    </div>
    <div class="btn-gradient-red text-white border-0 shadow-sm rounded-xl overflow-hidden p-5">
        <div class="justify-between items-center flex">
            <div>
                <div class="text-2xl font-extrabold">{{ number_format($stats['overdue']) }}</div>
                <small class="uppercase text-xs font-semibold tracking-wider">{{ __('Terlambat Kembali') }}</small>
            </div>
            <i class="bi bi-exclamation-triangle-fill text-3xl font-bold opacity-30"></i>
        </div>
    </div>
    <div class="btn-gradient-green text-white border-0 shadow-sm rounded-xl overflow-hidden p-5">
        <div class="justify-between items-center flex">
            <div>
                <div class="text-2xl font-extrabold">{{ number_format($stats['returned']) }}</div>
                <small class="uppercase text-xs font-semibold tracking-wider">{{ __('Kembali Hari Ini') }}</small>
            </div>
            <i class="bi bi-check-circle-fill text-3xl font-bold opacity-30"></i>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
    <div class="p-4 sm:p-6">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center" id="circFilterForm">
            <div class="lg:col-span-5">
                <div class="flex w-full text-sm">
                    <span class="flex items-center px-3 py-2 bg-slate-100 border border-slate-300 text-slate-600 rounded-l-lg"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="w-full rounded-r-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-3" placeholder="{{ __('Cari kode transaksi, nama atau kode anggota...') }}" value="{{ request('search') }}">
                </div>
            </div>
            <div class="lg:col-span-3">
                <select name="status" class="w-full rounded-lg border border-slate-300 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white px-3" onchange="this.form.submit()">
                    <option value="">{{ __('Semua Status') }}</option>
                    <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>{{ __('Dipinjam') }}</option>
                    <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>{{ __('Dikembalikan') }}</option>
                </select>
            </div>
            <div class="lg:col-span-2 flex items-center gap-2">
                <input class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500" type="checkbox" name="overdue" id="overdueCheckbox" value="1" {{ request('overdue') ? 'checked' : '' }} onchange="this.form.submit()">
                <label class="text-xs text-slate-700 font-medium cursor-pointer" for="overdueCheckbox">{{ __('Hanya Terlambat') }}</label>
            </div>
            <div class="lg:col-span-2 flex items-center gap-2">
                <button type="submit" class="inline-flex items-center justify-center gap-1 py-2 text-xs font-semibold rounded-lg btn-gradient-blue transition-colors text-white px-3"><i class="bi bi-funnel"></i> {{ __('Filter') }}</button>
                <a href="{{ route('circulations.index') }}" class="inline-flex items-center justify-center gap-1 py-2 text-xs font-medium rounded-lg text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors px-3"><i class="bi bi-x"></i> {{ __('Reset') }}</a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-0">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Kode Transaksi') }}</th>
                        <th>{{ __('Anggota') }}</th>
                        <th>{{ __('Buku') }}</th>
                        <th>{{ __('Tgl Pinjam') }}</th>
                        <th>{{ __('Jatuh Tempo') }}</th>
                        <th>{{ __('Tgl Kembali') }}</th>
                        <th>{{ __('Denda') }}</th>
                        <th class="text-center">{{ __('Status') }}</th>
                        <th class="text-center">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($circulations as $circ)
                    <tr>
                        <td class="text-slate-500">{{ $circulations->firstItem() + $loop->index }}</td>
                        <td><code class="font-bold text-slate-800 bg-slate-100 px-1.5 py-0.5 rounded text-xs">{{ $circ->circulation_code }}</code></td>
                        <td>
                            <div class="font-medium text-slate-800">{{ $circ->member->name }}</div>
                            <div class="text-slate-500 text-xs">{{ $circ->member->member_code }}</div>
                        </td>
                        <td>
                            <div class="font-medium text-slate-800 truncate max-w-[200px]" title="{{ $circ->bookItem?->book?->title }}">
                                {{ $circ->bookItem?->book?->title ?? '-' }}
                            </div>
                            <div class="text-slate-500 text-xs">Barcode: {{ $circ->bookItem?->item_code ?? '-' }}</div>
                        </td>
                        <td>{{ $circ->loan_date->format('d/m/Y') }}</td>
                        <td>
                            <span class="{{ $circ->is_overdue ? 'text-red-600 font-semibold' : '' }}">
                                {{ $circ->due_date->format('d/m/Y') }}
                            </span>
                        </td>
                        <td>{{ $circ->return_date ? $circ->return_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($circ->fine_amount > 0)
                            <span class="font-semibold text-red-600">Rp {{ number_format($circ->fine_amount, 0, ',', '.') }}</span>
                            @else
                            <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($circ->is_overdue)
                            <span class="inline-flex py-0.5 text-xs font-medium rounded-md bg-red-100 text-red-700 px-2">{{ __('Terlambat') }} {{ $circ->days_overdue }}h</span>
                            @elseif($circ->status === 'Dikembalikan')
                            <span class="inline-flex py-0.5 text-xs font-medium rounded-md bg-emerald-100 text-emerald-700 px-2">{{ __('Dikembalikan') }}</span>
                            @else
                            <span class="inline-flex py-0.5 text-xs font-medium rounded-md bg-amber-100 text-amber-800 px-2">{{ __('Dipinjam') }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('circulations.show', $circ) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="{{ __('Detail') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($circ->status === 'Dipinjam')
                                <a href="{{ route('circulations.return') }}?code={{ $circ->circulation_code }}" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="{{ __('Proses Pengembalian') }}">
                                    <i class="bi bi-box-arrow-in-left"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-8 text-slate-500">
                            <i class="bi bi-arrow-left-right text-3xl block mb-2 text-slate-300"></i>
                            {{ __('Tidak ada data transaksi sirkulasi') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($circulations->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $circulations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
