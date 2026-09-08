@extends('layouts.app')

@section('title', __('Agenda Perpustakaan'))
@section('page-title', __('Agenda Kegiatan'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item active">{{ __('Agenda Perpustakaan') }}</li>
@endsection

@section('content')
{{-- Page Header --}}
<div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-1 flex items-center gap-2">
            <i class="bi bi-calendar-event text-indigo-600"></i> {{ __('Agenda Perpustakaan') }}
        </h1>
        <p class="text-slate-500 text-xs sm:text-sm">{{ __('Jadwal acara, bedah buku, workshop, dan kegiatan literasi perpustakaan') }}</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('class-visits.index') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 shadow-sm gap-2 py-2.5 px-4 transition-all">
            <i class="bi bi-diagram-3"></i> {{ __('Kelola Jadwal Kunjungan') }}
        </a>
        <a href="{{ route('agendas.create') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg btn-gradient-blue shadow-md shadow-indigo-500/20 text-white gap-2 py-2.5 px-5 transition-all transform hover:-translate-y-0.5">
            <i class="bi bi-plus-lg text-base"></i> {{ __('Tambah Agenda Baru') }}
        </a>
    </div>
</div>

{{-- Stat Cards Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200 shadow-sm flex items-center gap-3 justify-between">
        <div class="min-w-0 flex-1">
            <div class="text-slate-500 text-[10px] sm:text-xs font-medium uppercase tracking-wider mb-1 truncate" title="{{ __('Total Agenda') }}">{{ __('Total Agenda') }}</div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 truncate">{{ $totalAgenda }}</div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold flex-shrink-0">
            <i class="bi bi-calendar-range"></i>
        </div>
    </div>

    <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200 shadow-sm flex items-center gap-3 justify-between">
        <div class="min-w-0 flex-1">
            <div class="text-slate-500 text-[10px] sm:text-xs font-medium uppercase tracking-wider mb-1 truncate" title="{{ __('Akan Datang') }}">{{ __('Akan Datang') }}</div>
            <div class="text-2xl sm:text-3xl font-extrabold text-sky-600 truncate">{{ $upcomingCount }}</div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl font-bold flex-shrink-0">
            <i class="bi bi-clock-history"></i>
        </div>
    </div>

    <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200 shadow-sm flex items-center gap-3 justify-between">
        <div class="min-w-0 flex-1">
            <div class="text-slate-500 text-[10px] sm:text-xs font-medium uppercase tracking-wider mb-1 truncate" title="{{ __('Hari Ini / Berlangsung') }}">{{ __('Hari Ini / Berlangsung') }}</div>
            <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600 truncate">{{ $ongoingCount }}</div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold flex-shrink-0">
            <i class="bi bi-record-circle"></i>
        </div>
    </div>

    <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200 shadow-sm flex items-center gap-3 justify-between">
        <div class="min-w-0 flex-1">
            <div class="text-slate-500 text-[10px] sm:text-xs font-medium uppercase tracking-wider mb-1 truncate" title="{{ __('Selesai / Arsip') }}">{{ __('Selesai / Arsip') }}</div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-600 truncate">{{ $pastCount }}</div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-xl font-bold flex-shrink-0">
            <i class="bi bi-check2-all"></i>
        </div>
    </div>
</div>

{{-- Filter & Search Section --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-5 mb-6">
    <form method="GET" action="{{ route('agendas.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
        {{-- Search Input --}}
        <div class="lg:col-span-5 relative">
            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Cari judul agenda, narasumber, lokasi...') }}" class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
        </div>

        {{-- Category Filter --}}
        <div class="lg:col-span-3">
            <select name="category" class="w-full py-2 px-3 text-xs sm:text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white">
                <option value="">{{ __('Semua Kategori') }}</option>
                @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        {{-- Status Filter --}}
        <div class="lg:col-span-2">
            <select name="status" class="w-full py-2 px-3 text-xs sm:text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white">
                <option value="">{{ __('Semua Status') }}</option>
                @foreach($statuses as $st)
                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </div>

        {{-- Buttons --}}
        <div class="lg:col-span-2 flex gap-2">
            <button type="submit" class="flex-1 py-2 px-3 text-xs sm:text-sm font-semibold rounded-lg btn-gradient-blue text-white transition-colors flex items-center justify-center gap-1">
                <i class="bi bi-funnel"></i> {{ __('Filter') }}
            </button>
            @if(request()->anyFilled(['search', 'category', 'status']))
            <a href="{{ route('agendas.index') }}" class="py-2 px-3 text-xs sm:text-sm font-medium rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors flex items-center justify-center" title="{{ __('Reset Filter') }}">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
            @endif
        </div>
    </form>
</div>

{{-- Agendas Grid Content --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    @forelse($agendas as $agenda)
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col justify-between hover:shadow-md transition-shadow group">
        <div>
            {{-- Category & Status Banner --}}
            <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50 flex items-center justify-between gap-2">
                @php
                    $catColors = [
                        'Workshop'  => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                        'Bedah Buku'=> 'bg-purple-100 text-purple-700 border-purple-200',
                        'Lomba'     => 'bg-amber-100 text-amber-800 border-amber-200',
                        'Pameran'   => 'bg-pink-100 text-pink-700 border-pink-200',
                        'Klub Baca' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    ];
                    $catClass = $catColors[$agenda->category] ?? 'bg-slate-100 text-slate-700 border-slate-200';

                    $statusColors = [
                        'Akan Datang' => 'bg-sky-500 text-white',
                        'Berlangsung' => 'bg-emerald-500 text-white',
                        'Selesai'    => 'bg-slate-400 text-white',
                        'Dibatalkan' => 'bg-red-500 text-white',
                    ];
                    $statusClass = $statusColors[$agenda->status] ?? 'bg-slate-400 text-white';
                @endphp
                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $catClass }}">
                    {{ $agenda->category }}
                </span>
                <span class="inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $statusClass }}">
                    {{ $agenda->status }}
                </span>
            </div>

            {{-- Body Content --}}
            <div class="p-5">
                {{-- Date & Time Badge --}}
                <div class="flex items-center gap-3 text-xs font-semibold text-indigo-600 mb-3 bg-indigo-50/60 px-3 py-2 rounded-lg border border-indigo-100">
                    <span class="flex items-center gap-1.5"><i class="bi bi-calendar3"></i> {{ $agenda->event_date->format('d M Y') }}</span>
                    <span class="text-indigo-300">•</span>
                    <span class="flex items-center gap-1.5"><i class="bi bi-clock"></i> {{ $agenda->formatted_time }}</span>
                </div>

                {{-- Title --}}
                <h3 class="text-base font-bold text-slate-800 group-hover:text-indigo-600 transition-colors mb-2 line-clamp-2">
                    {{ $agenda->title }}
                </h3>

                {{-- Description Excerpt --}}
                @if($agenda->description)
                <p class="text-slate-500 text-xs line-clamp-3 mb-4 leading-relaxed">
                    {{ $agenda->description }}
                </p>
                @endif

                {{-- Details Badges --}}
                <div class="space-y-1.5 text-xs text-slate-600 border-t border-slate-100 pt-3">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-geo-alt text-red-500 w-4 text-center"></i>
                        <span class="font-medium text-slate-700 truncate">{{ $agenda->location }}</span>
                    </div>
                    @if($agenda->speaker)
                    <div class="flex items-center gap-2">
                        <i class="bi bi-person-badge text-indigo-500 w-4 text-center"></i>
                        <span class="truncate">{{ __('Narasumber') }}: <strong class="text-slate-700">{{ $agenda->speaker }}</strong></span>
                    </div>
                    @endif
                    @if($agenda->target_audience)
                    <div class="flex items-center gap-2">
                        <i class="bi bi-people text-emerald-500 w-4 text-center"></i>
                        <span class="truncate">{{ __('Peserta') }}: {{ $agenda->target_audience }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Card Actions Footer --}}
        <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-xs">
            <span class="text-slate-400 text-[11px]">
                @if($agenda->quota)
                <i class="bi bi-ticket-perforated mr-1"></i>{{ $agenda->quota }} {{ __('Kuota') }}
                @else
                <i class="bi bi-check-circle mr-1"></i>{{ __('Terbuka') }}
                @endif
            </span>

            <div class="flex items-center gap-1">
                <a href="{{ route('agendas.edit', $agenda) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="{{ __('Edit Agenda') }}">
                    <i class="bi bi-pencil"></i>
                </a>
                <form method="POST" action="{{ route('agendas.destroy', $agenda) }}" onsubmit="return confirm('{{ __('Apakah Anda yakin ingin menghapus agenda kegiatan ini?') }}')" class="inline-block">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="{{ __('Hapus Agenda') }}">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-xl border border-slate-200 p-12 text-center text-slate-500">
        <i class="bi bi-calendar-x text-5xl text-slate-300 block mb-3"></i>
        <h4 class="text-base font-bold text-slate-700 mb-1">{{ __('Belum Ada Agenda Kegiatan') }}</h4>
        <p class="text-xs text-slate-400 mb-4">{{ __('Silakan tambah agenda kegiatan baru untuk membagikan acara perpustakaan.') }}</p>
        <a href="{{ route('agendas.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-lg btn-gradient-blue text-white shadow-sm transition-all">
            <i class="bi bi-plus-lg"></i> {{ __('Tambah Agenda Pertama') }}
        </a>
    </div>
    @endforelse
</div>

{{-- Pagination Links --}}
<div class="mt-4">
    {{ $agendas->links() }}
</div>

@endsection
