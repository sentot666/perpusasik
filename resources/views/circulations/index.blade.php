@extends('layouts.app')

@section('title', 'Transaksi Sirkulasi')
@section('page-title', 'Transaksi Sirkulasi')

@section('breadcrumb')
<li class="breadcrumb-item active">{{ __('Sirkulasi') }}</li>
@endsection

@section('content')
<div class="page-header justify-between items-start flex">
    <div>
        <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ __('Daftar Transaksi Sirkulasi') }}</h1>
        <p>{{ __('Pantau semua transaksi peminjaman, pengembalian, dan denda buku') }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('reports.export', ['type' => 'circulation'] + request()->all()) }}" class="gap-1 inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-green text-white transition-colors flex gap-2 py-2 px-6 shadow-sm">
            <i class="bi bi-file-earmark-excel text-base"></i> {{ __('Export Spreadsheet') }}
        </a>
        <a href="{{ route('circulations.loan') }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white gap-2 py-2 px-4">
            <i class="bi bi-box-arrow-right"></i>{{ __('Peminjaman Baru') }}
        </a>
        <a href="{{ route('circulations.return') }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white gap-2 py-2 px-4">
            <i class="bi bi-box-arrow-in-left"></i>{{ __('Pengembalian Baru') }}
        </a>
    </div>
</div>

{{-- Stat cards --}}
<div class="flex flex-wrap -mx-3 mb-6">
    <div class="w-full md:w-1/3 px-4">
        <div class="btn-gradient-yellow text-slate-900 border-0 shadow-sm text-slate-800 bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="justify-between items-center p-8 flex">
                <div>
                    <div class="text-xl font-semibold fw-800">{{ number_format($stats['active']) }}</div>
                    <small class="uppercase" style="font-size:0.7rem;letter-spacing:0.5px;font-weight:600">{{ __('Peminjaman Aktif') }}</small>
                </div>
                <i class="bi bi-arrow-left-right text-4xl font-bold opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="w-full md:w-1/3 px-4">
        <div class="btn-gradient-red text-white border-0 shadow-sm bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="justify-between items-center p-8 flex">
                <div>
                    <div class="text-xl font-semibold fw-800">{{ number_format($stats['overdue']) }}</div>
                    <small class="uppercase" style="font-size:0.7rem;letter-spacing:0.5px;font-weight:600">{{ __('Terlambat Kembali') }}</small>
                </div>
                <i class="bi bi-exclamation-triangle-fill text-4xl font-bold opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="w-full md:w-1/3 px-4">
        <div class="btn-gradient-green text-white border-0 shadow-sm bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="justify-between items-center p-8 flex">
                <div>
                    <div class="text-xl font-semibold fw-800">{{ number_format($stats['returned']) }}</div>
                    <small class="uppercase" style="font-size:0.7rem;letter-spacing:0.5px;font-weight:600">{{ __('Kembali Hari Ini') }}</small>
                </div>
                <i class="bi bi-check-circle-fill text-4xl font-bold opacity-25"></i>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
    <div class="px-8 py-2">
        <form method="GET" class="items-center flex flex-wrap -mx-2" id="circFilterForm">
            <div class="col-md-5">
                <div class="flex w-full text-sm">
                    <span class="flex items-center px-3 py-2 bg-slate-100 border border-slate-300 text-slate-600"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="{{ __('Cari kode transaksi, nama atau kode anggota...') }}" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="status" class="w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white px-4" onchange="this.form.submit()">
                    <option value="">{{ __('Semua Status') }}</option>
                    <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>{{ __('Dipinjam') }}</option>
                    <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>{{ __('Dikembalikan') }}</option>
                </select>
            </div>
            <div class="col-md-2 form-check-inline m-0 pl-6 flex items-center gap-2">
                <input class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500" type="checkbox" name="overdue" id="overdueCheckbox" value="1" {{ request('overdue') ? 'checked' : '' }} onchange="this.form.submit()">
                <label class="text-sm text-slate-700 font-medium" for="overdueCheckbox" style="font-size:0.82rem">{{ __('Hanya Terlambat') }}</label>
            </div>
            <div class="w-auto px-4">
                <button type="submit" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg btn-gradient-blue transition-colors text-white px-4"><i class="bi bi-funnel"></i> {{ __('Filter') }}</button>
                <a href="{{ route('circulations.index') }}" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors px-4"><i class="bi bi-x"></i> {{ __('Reset') }}</a>
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
                        <th>{{ __('Kode TRX') }}</th>
                        <th>{{ __('Anggota') }}</th>
                        <th>{{ __('Buku (Barcode)') }}</th>
                        <th>{{ __('Tgl Pinjam') }}</th>
                        <th>{{ __('Jatuh Tempo') }}</th>
                        <th>{{ __('Tgl Kembali') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Denda') }}</th>
                        <th class="text-center">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($circulations as $c)
                    <tr>
                        <td><code class="font-semibold" style="font-size:0.85rem">{{ $c->transaction_code }}</code></td>
                        <td>
                            <div class="font-medium">{{ $c->member->name }}</div>
                            <div class="text-slate-500" style="font-size:0.72rem">{{ $c->member->member_code }}</div>
                        </td>
                        <td>
                            <div class="truncate" style="max-width:180px" title="{{ $c->bookItem->book->title }}">{{ $c->bookItem->book->title }}</div>
                            <code style="font-size:0.72rem">{{ $c->bookItem->barcode }}</code>
                        </td>
                        <td>{{ $c->loan_date->format('d/m/Y') }}</td>
                        <td>
                            <span class="{{ $c->is_overdue ? 'text-danger fw-600' : '' }}">
                                {{ $c->due_date->format('d/m/Y') }}
                            </span>
                        </td>
                        <td>{{ $c->return_date ? $c->return_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($c->status === 'Dikembalikan')
                            <span class="inline-flex py-1 text-xs font-medium rounded-md bg-emerald-100 text-emerald-700 px-2">{{ __('Dikembalikan') }}</span>
                            @elseif($c->is_overdue)
                            <span class="inline-flex py-1 text-xs font-medium rounded-md bg-red-100 text-red-700 px-2">{{ __('Terlambat') }} {{ $c->days_overdue }} {{ __('hari') }}</span>
                            @else
                            <span class="inline-flex py-1 text-xs font-medium rounded-md bg-amber-100 text-amber-800 px-2">{{ __('Dipinjam') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($c->fine_amount > 0)
                            <div class="text-red-600 font-semibold">Rp {{ number_format($c->fine_amount, 0, ',', '.') }}</div>
                            <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-{{ $c->fine_paid ? 'success' : 'warning text-dark' }}">{{ $c->fine_paid ? __('Lunas') : __('Belum Lunas') }}</span>
                            @elseif($c->is_overdue)
                            <div class="text-red-600 font-semibold" title="{{ __('Estimasi denda saat ini') }}">Rp {{ number_format($c->calculated_fine, 0, ',', '.') }}</div>
                            <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-slate-50 text-slate-500" style="font-size:0.65rem">{{ __('Belum kembali') }}</span>
                            @else
                            <span class="text-slate-500">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="inline-flex rounded-md shadow-sm rounded-lg">
                                <a href="{{ route('circulations.show', $c) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-indigo-600 border border-slate-200 border-indigo-600 hover:bg-indigo-50 transition-colors gap-2 py-2 px-6" title="{{ __('Detail') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="py-8 text-center text-slate-500">{{ __('Belum ada data transaksi sirkulasi') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($circulations->hasPages())
    <div class="bg-white border-t border-slate-200 px-8 bg-slate-50 py-2 py-4">
        {{ $circulations->links() }}
    </div>
    @endif
</div>
@endsection



