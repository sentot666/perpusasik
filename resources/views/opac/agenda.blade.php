@extends('layouts.opac')

@section('title', __('Agenda Kegiatan Perpustakaan'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">

    {{-- Banner Space Below Navbar --}}
    <div class="mb-8 rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 text-white p-6 sm:p-10 shadow-xl relative overflow-hidden border border-indigo-900/50">
        {{-- Background decorative shapes --}}
        <div class="absolute -right-10 -top-10 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -bottom-10 w-48 h-48 bg-rose-500/20 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/10 backdrop-blur-md text-amber-300 text-xs font-bold uppercase tracking-wider mb-4 border border-white/15">
                    <i class="bi bi-stars"></i> {{ __('Informasi Acara & Kegiatan') }}
                </div>
                <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight mb-3">
                    {{ __('Agenda Kegiatan Perpustakaan') }}
                </h1>
                <p class="text-slate-300 text-xs sm:text-sm leading-relaxed mb-6 font-normal">
                    {{ __('Temukan dan ikuti berbagai kegiatan menarik di perpustakaan, mulai dari workshop literasi digital, bedah buku populer, kompetisi resensi, hingga pameran koleksi buku terbaru.') }}
                </p>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('opac.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-slate-900 hover:bg-slate-100 font-bold text-xs sm:text-sm transition-all shadow-md no-underline">
                        <i class="bi bi-arrow-left"></i> {{ __('Kembali ke Beranda') }}
                    </a>
                    @auth
                    <a href="{{ route('agendas.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600/80 hover:bg-indigo-600 border border-indigo-400/40 text-white font-bold text-xs sm:text-sm transition-all shadow-md no-underline">
                        <i class="bi bi-gear-fill"></i> {{ __('Kelola Agenda (Admin)') }}
                    </a>
                    @endauth
                </div>
            </div>

            {{-- Right Banner Graphic / Badge --}}
            <div class="hidden md:flex flex-shrink-0 items-center justify-center w-44 h-44 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md p-6 text-center shadow-2xl transform rotate-2 hover:rotate-0 transition-transform">
                <div>
                    <i class="bi bi-calendar-event text-5xl text-amber-400 block mb-2"></i>
                    <span class="text-xs font-bold text-slate-200 block uppercase tracking-wider">{{ __('Events Agenda') }}</span>
                    <span class="text-[10px] text-slate-400 font-medium">{{ __('Perpustakaan Digital') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Section Title & Filter Summary --}}
    <div class="mb-6 flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-slate-800 flex items-center gap-2">
                <i class="bi bi-calendar-week text-indigo-600"></i> {{ __('Daftar Agenda Terdaftar') }}
            </h2>
            <p class="text-xs text-slate-500">{{ __('Jadwal kegiatan terbaru dan arsip acara perpustakaan') }}</p>
        </div>
    </div>

    {{-- Agendas List Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        @forelse($agendas as $agenda)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col justify-between hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div>
                {{-- Category & Status --}}
                <div class="p-5 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-indigo-50/40 flex items-center justify-between">
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700">
                        {{ $agenda->category }}
                    </span>
                    @php
                        $statusColors = [
                            'Akan Datang' => 'bg-sky-500 text-white',
                            'Berlangsung' => 'bg-emerald-500 text-white',
                            'Selesai'    => 'bg-slate-400 text-white',
                            'Dibatalkan' => 'bg-red-500 text-white',
                        ];
                        $statusClass = $statusColors[$agenda->status] ?? 'bg-slate-400 text-white';
                    @endphp
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $statusClass }}">
                        {{ $agenda->status }}
                    </span>
                </div>

                {{-- Content --}}
                <div class="p-6">
                    <div class="flex items-center gap-3 text-xs font-bold text-indigo-600 mb-3 bg-indigo-50/80 px-3 py-2 rounded-xl border border-indigo-100">
                        <span class="flex items-center gap-1.5"><i class="bi bi-calendar3"></i> {{ $agenda->event_date->format('d M Y') }}</span>
                        <span class="text-indigo-300">•</span>
                        <span class="flex items-center gap-1.5"><i class="bi bi-clock"></i> {{ $agenda->formatted_time }}</span>
                    </div>

                    <h3 class="text-lg font-bold text-slate-800 mb-2 line-clamp-2 leading-snug">
                        {{ $agenda->title }}
                    </h3>

                    @if($agenda->description)
                    <p class="text-slate-600 text-xs sm:text-sm line-clamp-3 mb-4 leading-relaxed">
                        {{ $agenda->description }}
                    </p>
                    @endif

                    <div class="space-y-2 text-xs text-slate-600 border-t border-slate-100 pt-4">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-geo-alt-fill text-rose-500 w-4 text-center"></i>
                            <span class="font-semibold text-slate-700">{{ $agenda->location }}</span>
                        </div>
                        @if($agenda->speaker)
                        <div class="flex items-center gap-2">
                            <i class="bi bi-person-badge-fill text-indigo-500 w-4 text-center"></i>
                            <span>{{ __('Narasumber') }}: <strong class="text-slate-800">{{ $agenda->speaker }}</strong></span>
                        </div>
                        @endif
                        @if($agenda->target_audience)
                        <div class="flex items-center gap-2">
                            <i class="bi bi-people-fill text-emerald-500 w-4 text-center"></i>
                            <span>{{ __('Peserta') }}: {{ $agenda->target_audience }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span class="font-medium">
                    @if($agenda->quota)
                    <i class="bi bi-ticket-perforated mr-1 text-indigo-500"></i>{{ $agenda->quota }} {{ __('Kuota Peserta') }}
                    @else
                    <i class="bi bi-check-circle mr-1 text-emerald-500"></i>{{ __('Terbuka Untuk Umum') }}
                    @endif
                </span>
                <span class="text-[11px] text-slate-400">
                    <i class="bi bi-info-circle mr-1"></i>{{ __('Kegiatan Resmi') }}
                </span>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-500 shadow-sm">
            <i class="bi bi-calendar-x text-5xl text-slate-300 block mb-3"></i>
            <h4 class="text-base font-bold text-slate-700 mb-1">{{ __('Belum Ada Agenda Kegiatan') }}</h4>
            <p class="text-xs text-slate-400">{{ __('Belum ada informasi agenda acara terbaru untuk saat ini.') }}</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $agendas->links() }}
    </div>

</div>
@endsection
