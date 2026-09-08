@extends('layouts.app')

@section('title', 'Buku Tamu & Aktivitas Harian')
@section('page-title', 'Buku Tamu')

@section('breadcrumb')
<li class="breadcrumb-item active">{{ __('Buku Tamu & Aktivitas Harian') }}</li>
@endsection

@push('styles')
<style>
    @media print {
        @page {
            margin: 0; /* Menghilangkan header dan footer default dari browser */
        }
        body {
            padding: 1.5cm; /* Memberikan jarak pinggir agar tulisan tidak mepet ujung kertas */
        }
    }
</style>
@endpush

@section('content')
<div x-data="{ selected: [], selectAll: false }">
    <div class="page-header print:hidden flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-1">{{ __('Buku Tamu & Aktivitas Harian') }}</h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-1">{{ __('Pendataan aktivitas harian tamu dan kunjungan institusi/kelompok di perpustakaan') }}</p>
        </div>
        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
            <a href="{{ route('guest-books.export', request()->all()) }}" 
               @click.prevent="
                   let url = new URL($el.href);
                   if (selected.length > 0) {
                       selected.forEach(id => url.searchParams.append('selected_ids[]', id));
                   }
                   window.location.href = url.toString();
               "
               class="inline-flex items-center justify-center text-xs sm:text-sm font-medium rounded-lg btn-gradient-green text-white transition-colors gap-1.5 py-2 px-4 shadow-sm">
                <i class="bi bi-file-earmark-excel"></i> {{ __('Export Excel') }} <span x-show="selected.length > 0" x-text="'(' + selected.length + ')'" class="ml-1 font-bold"></span>
            </a>
            <button type="button" onclick="window.print()" class="inline-flex items-center justify-center text-xs sm:text-sm font-medium rounded-lg bg-slate-800 hover:bg-slate-900 text-white transition-colors gap-1.5 py-2 px-4 shadow-sm">
                <i class="bi bi-printer"></i> {{ __('Cetak Laporan') }}
            </button>
            <a href="{{ route('guest-books.scan') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition-colors gap-1.5 py-2 px-4 shadow-sm">
                <i class="fas fa-barcode"></i> {{ __('Mode Scan Barcode') }}
            </a>
            <a href="{{ route('guest-books.create') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white gap-1.5 py-2 px-4 shadow-sm">
                <i class="bi bi-journal-plus"></i>{{ __('Catat Kunjungan Baru') }}
            </a>
        </div>
    </div>

    {{-- Print-only Header --}}
    <div class="hidden print-header mb-6">
        <h2 class="uppercase text-center font-bold" style="border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px;">
            {{ __('Laporan Kunjungan Tamu & Aktivitas Harian') }}<br>
            <span style="font-size: 1.1rem; font-weight: normal; text-transform: none;">
                {{ \App\Models\Setting::get('library_name', config('app.name', 'Makarya')) }}
            </span>
        </h2>
        @if($startDate || $endDate)
            <p class="text-center text-slate-500" style="margin-top: -10px;">
                {{ __('Periode:') }} 
                @if($startDate) <strong>{{ date('d/m/Y', strtotime($startDate)) }}</strong> @else {{ __('Awal') }} @endif
                s.d.
                @if($endDate) <strong>{{ date('d/m/Y', strtotime($endDate)) }}</strong> @else {{ __('Akhir') }} @endif
            </p>
        @endif
        <div class="text-center text-slate-800" style="margin-top: 10px; font-weight: 600; font-size: 0.95rem;">
            {{ __('Total Kunjungan:') }} {{ $activities->total() }} {{ __('kali') }} &nbsp;|&nbsp; {{ __('Total Peserta:') }} {{ $totalParticipants ?? 0 }} {{ __('orang') }}
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="p-4 sm:p-6">
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end" id="guestBookFilterForm">
                <div class="lg:col-span-4">
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">{{ __('Cari Tamu / Instansi') }}</label>
                    <div class="flex w-full text-sm">
                        <span class="flex items-center px-3 py-2 bg-slate-100 border border-slate-300 text-slate-600 rounded-l-lg"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="w-full rounded-r-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-3" placeholder="{{ __('Cari nama, instansi, tujuan...') }}" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="lg:col-span-3">
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">{{ __('Mulai Tanggal') }}</label>
                    <input type="date" name="start_date" class="w-full rounded-lg border border-slate-300 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white px-3" value="{{ $startDate }}">
                </div>
                <div class="lg:col-span-3">
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">{{ __('Sampai Tanggal') }}</label>
                    <input type="date" name="end_date" class="w-full rounded-lg border border-slate-300 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white px-3" value="{{ $endDate }}">
                </div>
                <div class="lg:col-span-2 flex items-center justify-between gap-2">
                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center justify-center gap-1 py-2 text-xs font-semibold rounded-lg btn-gradient-blue transition-colors text-white px-3"><i class="bi bi-funnel"></i> {{ __('Filter') }}</button>
                        <a href="{{ route('guest-books.index') }}" class="inline-flex items-center justify-center gap-1 py-2 text-xs font-medium rounded-lg text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors px-3"><i class="bi bi-x"></i> {{ __('Reset') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Main Table Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-0">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50">
                    <thead>
                        <tr>
                            <th class="text-center print:hidden" style="width: 40px;">
                                <input type="checkbox" x-model="selectAll" @change="selected = selectAll ? [{{ $activities->pluck('id')->join(',') }}] : []" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th style="width: 50px;">#</th>
                            <th>{{ __('Hari & Tanggal') }}</th>
                            <th>{{ __('Nama') }}</th>
                            <th>{{ __('Kunjungan Dari') }}</th>
                            <th>{{ __('Tujuan') }}</th>
                            <th>{{ __('Waktu Kunjungan') }}</th>
                            <th class="text-center">{{ __('Jumlah Peserta') }}</th>
                            <th class="text-center print:hidden" style="width: 100px;">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                        <tr :class="{'print:hidden': selected.length > 0 && !selected.includes('{{ $activity->id }}')}">
                            <td class="text-center print:hidden">
                                <input type="checkbox" value="{{ $activity->id }}" x-model="selected" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            </td>
                            <td class="text-slate-500">{{ $activities->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="font-medium text-slate-800">{{ $activity->visit_date ? $activity->visit_date->translatedFormat('l, d F Y') : '-' }}</div>
                            </td>
                            <td>
                                <div class="font-semibold text-slate-800">{{ $activity->name }}</div>
                            </td>
                            <td>{{ $activity->institution ?? '-' }}</td>
                            <td>{{ $activity->purpose ?? '-' }}</td>
                            <td>{{ $activity->visit_time ? date('H:i', strtotime($activity->visit_time)) : '-' }} WIB</td>
                            <td class="text-center font-bold text-indigo-600">{{ $activity->participants_count }} {{ __('Orang') }}</td>
                            <td class="text-center print:hidden">
                                <a href="{{ route('guest-books.edit', $activity) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="{{ __('Edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('guest-books.destroy', $activity) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus catatan kunjungan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="{{ __('Hapus') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-8 text-slate-500">
                                <i class="bi bi-journal-x text-3xl block mb-2 text-slate-300"></i>
                                {{ __('Belum ada catatan kunjungan') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($activities->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $activities->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
