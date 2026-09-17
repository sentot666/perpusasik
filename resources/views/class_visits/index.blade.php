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
<div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-xl flex items-center justify-between shadow-sm" role="alert">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
            <i class="bi bi-check-circle-fill text-lg"></i>
        </div>
        <div>
            <div class="font-semibold text-sm">{{ __('Berhasil!') }}</div>
            <div class="text-xs text-emerald-700">{{ session('success') }}</div>
        </div>
    </div>
    <button type="button" onclick="this.closest('[role=alert]').remove()" class="text-emerald-500 hover:text-emerald-800 p-1.5 rounded-lg hover:bg-emerald-100/50 transition-colors">
        <i class="bi bi-x-lg text-sm"></i>
    </button>
</div>
@endif

{{-- Page Header --}}
<div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-1 flex items-center gap-2.5">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="bi bi-calendar-check"></i>
            </div>
            <span>{{ __('Jadwal Kunjungan Kelas') }}</span>
        </h1>
        <p class="text-slate-500 text-xs sm:text-sm">{{ __('Kelola rutinitas dan agenda kunjungan literasi rombongan belajar / kelas ke perpustakaan') }}</p>
    </div>
    <div class="flex flex-wrap gap-2.5 w-full sm:w-auto">
        <a href="{{ route('agendas.index') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 shadow-sm gap-2 py-2.5 px-4 transition-all">
            <i class="bi bi-calendar-event text-slate-500"></i> {{ __('Agenda Kegiatan') }}
        </a>
        <a href="{{ route('class-visits.create') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg btn-gradient-blue shadow-md shadow-indigo-500/20 text-white gap-2 py-2.5 px-5 transition-all transform hover:-translate-y-0.5">
            <i class="bi bi-plus-lg text-base"></i> {{ __('Tambah Jadwal Kunjungan') }}
        </a>
    </div>
</div>

{{-- Stat Cards Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    {{-- Total Jadwal --}}
    <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200 shadow-sm flex items-center gap-3 justify-between">
        <div class="min-w-0 flex-1">
            <div class="text-slate-500 text-[10px] sm:text-xs font-medium uppercase tracking-wider mb-1 truncate" title="{{ __('Total Jadwal') }}">
                {{ __('Total Jadwal') }}
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 truncate">{{ $totalVisits }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">{{ __('Semua tingkatan') }}</div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold flex-shrink-0">
            <i class="bi bi-calendar2-range"></i>
        </div>
    </div>

    {{-- SD --}}
    <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200 shadow-sm flex items-center gap-3 justify-between">
        <div class="min-w-0 flex-1">
            <div class="text-slate-500 text-[10px] sm:text-xs font-medium uppercase tracking-wider mb-1 truncate" title="{{ __('Kunjungan SD') }}">
                {{ __('Sekolah Dasar (SD)') }}
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-amber-600 truncate">{{ $sdCount }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">{{ __('Jadwal tingkat SD') }}</div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold flex-shrink-0">
            <i class="bi bi-backpack2"></i>
        </div>
    </div>

    {{-- SMP --}}
    <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200 shadow-sm flex items-center gap-3 justify-between">
        <div class="min-w-0 flex-1">
            <div class="text-slate-500 text-[10px] sm:text-xs font-medium uppercase tracking-wider mb-1 truncate" title="{{ __('Kunjungan SMP') }}">
                {{ __('Menengah Pertama (SMP)') }}
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600 truncate">{{ $smpCount }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">{{ __('Jadwal tingkat SMP') }}</div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold flex-shrink-0">
            <i class="bi bi-book-half"></i>
        </div>
    </div>

    {{-- SMA --}}
    <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200 shadow-sm flex items-center gap-3 justify-between">
        <div class="min-w-0 flex-1">
            <div class="text-slate-500 text-[10px] sm:text-xs font-medium uppercase tracking-wider mb-1 truncate" title="{{ __('Kunjungan SMA') }}">
                {{ __('Menengah Atas (SMA)') }}
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-purple-600 truncate">{{ $smaCount }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">{{ __('Jadwal tingkat SMA') }}</div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-bold flex-shrink-0">
            <i class="bi bi-award"></i>
        </div>
    </div>
</div>

{{-- Filters & Search Section --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-5 mb-6">
    <form method="GET" action="{{ route('class-visits.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
        {{-- Search Input --}}
        <div class="lg:col-span-6 relative">
            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Cari nama kelas, guru pendamping, atau hari...') }}" class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
        </div>

        {{-- Level Filter Dropdown --}}
        <div class="lg:col-span-4">
            <select name="level" class="w-full py-2 px-3 text-xs sm:text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white">
                <option value="">{{ __('Semua Tingkatan (SD / SMP / SMA)') }}</option>
                <option value="sd" {{ request('level') == 'sd' ? 'selected' : '' }}>{{ __('Sekolah Dasar (SD)') }}</option>
                <option value="smp" {{ request('level') == 'smp' ? 'selected' : '' }}>{{ __('Menengah Pertama (SMP)') }}</option>
                <option value="sma" {{ request('level') == 'sma' ? 'selected' : '' }}>{{ __('Menengah Atas (SMA)') }}</option>
            </select>
        </div>

        {{-- Filter Buttons --}}
        <div class="lg:col-span-2 flex gap-2">
            <button type="submit" class="flex-1 py-2 px-3 text-xs sm:text-sm font-semibold rounded-lg btn-gradient-blue text-white transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                <i class="bi bi-funnel"></i> {{ __('Filter') }}
            </button>
            @if(request()->anyFilled(['search', 'level']))
            <a href="{{ route('class-visits.index') }}" class="py-2 px-3 text-xs sm:text-sm font-medium rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors flex items-center justify-center" title="{{ __('Reset Filter') }}">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
            @endif
        </div>
    </form>

    {{-- Quick Level Filter Pills --}}
    <div class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap items-center gap-2">
        <span class="text-xs font-semibold text-slate-500 mr-1">{{ __('Filter Cepat:') }}</span>
        <a href="{{ route('class-visits.index', array_merge(request()->except('level', 'page'), ['level' => ''])) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request('level') == '' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            <span>{{ __('Semua') }}</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ request('level') == '' ? 'bg-indigo-500 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $totalVisits }}</span>
        </a>
        <a href="{{ route('class-visits.index', array_merge(request()->except('level', 'page'), ['level' => 'sd'])) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request('level') == 'sd' ? 'bg-amber-600 text-white shadow-sm' : 'bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100' }}">
            <i class="bi bi-backpack2"></i>
            <span>{{ __('SD') }}</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ request('level') == 'sd' ? 'bg-amber-500 text-white' : 'bg-amber-200/80 text-amber-800' }}">{{ $sdCount }}</span>
        </a>
        <a href="{{ route('class-visits.index', array_merge(request()->except('level', 'page'), ['level' => 'smp'])) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request('level') == 'smp' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' }}">
            <i class="bi bi-book-half"></i>
            <span>{{ __('SMP') }}</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ request('level') == 'smp' ? 'bg-emerald-500 text-white' : 'bg-emerald-200/80 text-emerald-800' }}">{{ $smpCount }}</span>
        </a>
        <a href="{{ route('class-visits.index', array_merge(request()->except('level', 'page'), ['level' => 'sma'])) }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ request('level') == 'sma' ? 'bg-purple-600 text-white shadow-sm' : 'bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100' }}">
            <i class="bi bi-award"></i>
            <span>{{ __('SMA') }}</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ request('level') == 'sma' ? 'bg-purple-500 text-white' : 'bg-purple-200/80 text-purple-800' }}">{{ $smaCount }}</span>
        </a>
    </div>
</div>

{{-- Main Table Card --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-0">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-5 [&>thead>tr>th]:py-3.5 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-5 [&>tbody>tr>td]:py-4 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50/70 transition-colors">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 140px;">{{ __('Tingkat / Level') }}</th>
                        <th>{{ __('Hari & Tanggal') }}</th>
                        <th>{{ __('Waktu (WIB)') }}</th>
                        <th>{{ __('Kelas / Rombel') }}</th>
                        <th>{{ __('Guru Pendamping') }}</th>
                        <th class="text-right" style="width: 130px;">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($visits as $visit)
                    <tr>
                        <td class="text-slate-400 font-mono text-xs">
                            {{ $visits->firstItem() + $loop->index }}
                        </td>
                        <td>
                            @if(strtolower($visit->level) === 'sd')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wide">
                                    <i class="bi bi-backpack2"></i> SD
                                </span>
                            @elseif(strtolower($visit->level) === 'smp')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wide">
                                    <i class="bi bi-book-half"></i> SMP
                                </span>
                            @elseif(strtolower($visit->level) === 'sma')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200 uppercase tracking-wide">
                                    <i class="bi bi-award"></i> SMA
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 uppercase tracking-wide">
                                    {{ $visit->level }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="font-bold text-slate-800 text-sm flex items-center gap-1.5">
                                <i class="bi bi-calendar-day text-slate-400"></i>
                                {{ $visit->day }}
                            </div>
                            @if($visit->date)
                            <div class="text-xs text-indigo-600 font-medium mt-0.5 flex items-center gap-1">
                                <i class="bi bi-calendar3"></i>
                                {{ $visit->date->format('d M Y') }}
                            </div>
                            @else
                            <div class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                                <i class="bi bi-arrow-repeat"></i>
                                {{ __('Rutin Tiap Minggu') }}
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-semibold">
                                <i class="bi bi-clock text-slate-400"></i>
                                {{ $visit->time }}
                            </div>
                        </td>
                        <td>
                            <div class="font-semibold text-slate-800 flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 text-xs">
                                    <i class="bi bi-door-closed"></i>
                                </div>
                                <span class="text-sm">{{ $visit->class_name }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="text-slate-700 flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center flex-shrink-0 text-xs">
                                    <i class="bi bi-person"></i>
                                </div>
                                <span class="text-sm font-medium">{{ $visit->teacher_name }}</span>
                            </div>
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('class-visits.edit', $visit->id) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors inline-flex items-center gap-1" title="{{ __('Edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('class-visits.destroy', $visit->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal kunjungan kelas {{ $visit->class_name }} ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors inline-flex items-center gap-1" title="{{ __('Hapus') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-14 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-3xl mb-3">
                                    <i class="bi bi-calendar-x"></i>
                                </div>
                                <h3 class="text-base font-bold text-slate-800 mb-1">{{ __('Belum Ada Jadwal Kunjungan') }}</h3>
                                <p class="text-xs sm:text-sm text-slate-500 max-w-md mb-5">
                                    {{ request()->anyFilled(['search', 'level']) 
                                        ? __('Tidak ada jadwal kunjungan kelas yang sesuai dengan kriteria filter pencarian Anda.') 
                                        : __('Belum ada rutinitas jadwal kunjungan kelas ke perpustakaan. Mulai dengan menambahkan jadwal baru.') }}
                                </p>
                                @if(request()->anyFilled(['search', 'level']))
                                <a href="{{ route('class-visits.index') }}" class="inline-flex items-center justify-center text-xs font-semibold rounded-lg border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 py-2 px-4 shadow-sm transition-all gap-1.5">
                                    <i class="bi bi-arrow-counterclockwise"></i> {{ __('Reset Filter') }}
                                </a>
                                @else
                                <a href="{{ route('class-visits.create') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg btn-gradient-blue text-white py-2 px-4 shadow-sm transition-all gap-1.5">
                                    <i class="bi bi-plus-lg"></i> {{ __('Tambah Jadwal Pertama') }}
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($visits->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $visits->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
