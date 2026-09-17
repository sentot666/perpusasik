@extends('layouts.app')

@section('title', __('Jadwal Kunjungan Kelas'))
@section('page-title', __('Jadwal Kunjungan Kelas'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item active">{{ __('Jadwal Kunjungan Kelas') }}</li>
@endsection

@section('content')
{{-- Flash Success Alert --}}
@if (session('success'))
<div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl flex items-center justify-between" role="alert">
    <div class="flex items-center gap-2.5">
        <i class="bi bi-check-circle-fill text-emerald-600"></i>
        <span>{{ session('success') }}</span>
    </div>
    <button type="button" onclick="this.closest('[role=alert]').remove()" class="text-emerald-500 hover:text-emerald-700">
        <i class="bi bi-x-lg text-xs"></i>
    </button>
</div>
@endif

{{-- Page Header --}}
<div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 mb-1">{{ __('Jadwal Kunjungan Kelas') }}</h1>
        <p class="text-slate-500 text-xs sm:text-sm">{{ __('Kelola jadwal dan agenda kunjungan rombongan belajar / kelas ke perpustakaan') }}</p>
    </div>
    <div>
        <a href="{{ route('class-visits.create') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg btn-gradient-blue shadow-sm text-white gap-2 py-2 px-4 transition-all">
            <i class="bi bi-plus-lg"></i> {{ __('Tambah Jadwal') }}
        </a>
    </div>
</div>

{{-- Unified Clean Filter & Search Bar --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-3 sm:p-4 mb-5">
    <form method="GET" action="{{ route('class-visits.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
        {{-- Level Filter Tabs --}}
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0">
            <a href="{{ route('class-visits.index', array_merge(request()->except('level', 'page'), ['level' => ''])) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ request('level') == '' ? 'bg-indigo-600 text-white' : 'text-slate-600 bg-slate-100 hover:bg-slate-200' }}">
                {{ __('Semua') }} <span class="opacity-75">({{ $totalVisits }})</span>
            </a>
            <a href="{{ route('class-visits.index', array_merge(request()->except('level', 'page'), ['level' => 'sd'])) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ request('level') == 'sd' ? 'bg-indigo-600 text-white' : 'text-slate-600 bg-slate-100 hover:bg-slate-200' }}">
                {{ __('SD') }} <span class="opacity-75">({{ $sdCount }})</span>
            </a>
            <a href="{{ route('class-visits.index', array_merge(request()->except('level', 'page'), ['level' => 'smp'])) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ request('level') == 'smp' ? 'bg-indigo-600 text-white' : 'text-slate-600 bg-slate-100 hover:bg-slate-200' }}">
                {{ __('SMP') }} <span class="opacity-75">({{ $smpCount }})</span>
            </a>
            <a href="{{ route('class-visits.index', array_merge(request()->except('level', 'page'), ['level' => 'sma'])) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors {{ request('level') == 'sma' ? 'bg-indigo-600 text-white' : 'text-slate-600 bg-slate-100 hover:bg-slate-200' }}">
                {{ __('SMA') }} <span class="opacity-75">({{ $smaCount }})</span>
            </a>
        </div>

        {{-- Search Input & Action --}}
        <div class="flex items-center gap-2">
            @if(request('level'))
                <input type="hidden" name="level" value="{{ request('level') }}">
            @endif
            <div class="relative flex-1 sm:w-64">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Cari kelas, guru, hari...') }}" class="w-full pl-8 pr-3 py-1.5 text-xs rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-slate-50 focus:bg-white transition-colors">
            </div>
            <button type="submit" class="py-1.5 px-3 text-xs font-medium rounded-lg bg-slate-800 text-white hover:bg-slate-900 transition-colors whitespace-nowrap">
                {{ __('Cari') }}
            </button>
            @if(request()->anyFilled(['search', 'level']))
            <a href="{{ route('class-visits.index') }}" class="py-1.5 px-2.5 text-xs font-medium rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors" title="{{ __('Reset Filter') }}">
                <i class="bi bi-x-lg"></i>
            </a>
            @endif
        </div>
    </form>
</div>

{{-- Main Table Card --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-0">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50/70 transition-colors">
                <thead>
                    <tr>
                        <th style="width: 45px;">#</th>
                        <th style="width: 90px;">{{ __('Jenjang') }}</th>
                        <th>{{ __('Hari & Tanggal') }}</th>
                        <th>{{ __('Waktu') }}</th>
                        <th>{{ __('Kelas') }}</th>
                        <th>{{ __('Guru Pendamping') }}</th>
                        <th class="text-right" style="width: 80px;">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($visits as $visit)
                    <tr>
                        <td class="text-slate-400 text-xs font-mono">
                            {{ $visits->firstItem() + $loop->index }}
                        </td>
                        <td>
                            @if(strtolower($visit->level) === 'sd')
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">SD</span>
                            @elseif(strtolower($visit->level) === 'smp')
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">SMP</span>
                            @elseif(strtolower($visit->level) === 'sma')
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">SMA</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-700 uppercase">{{ $visit->level }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="font-semibold text-slate-800">{{ $visit->day }}</span>
                            @if($visit->date)
                                <span class="text-xs text-slate-400 block">{{ $visit->date->format('d/m/Y') }}</span>
                            @endif
                        </td>
                        <td class="text-slate-600 text-xs">
                            {{ $visit->time }}
                        </td>
                        <td class="font-semibold text-slate-800">
                            {{ $visit->class_name }}
                        </td>
                        <td class="text-slate-600">
                            {{ $visit->teacher_name }}
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="inline-flex items-center justify-end gap-1">
                                <a href="{{ route('class-visits.edit', $visit->id) }}" class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="{{ __('Edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('class-visits.destroy', $visit->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus jadwal kunjungan kelas {{ $visit->class_name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="{{ __('Hapus') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            <i class="bi bi-calendar-x text-2xl block mb-2 opacity-40"></i>
                            <div class="text-sm font-medium text-slate-600">{{ __('Belum ada jadwal kunjungan') }}</div>
                            <div class="text-xs text-slate-400 mt-1">
                                {{ request()->anyFilled(['search', 'level']) ? __('Tidak ada data yang sesuai filter.') : __('Silakan tambahkan jadwal kunjungan kelas baru.') }}
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($visits->hasPages())
        <div class="p-3.5 border-t border-slate-200">
            {{ $visits->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
