@extends('layouts.opac')

@section('title', __('Agenda Kegiatan Perpustakaan'))

@section('content')
<div class="w-full px-6 sm:px-8 lg:px-12 py-8">

    {{-- Meta Hero Band: Agenda Kegiatan --}}
    <div class="mb-12 rounded-3xl bg-[#0a1317] text-white p-8 sm:p-12 shadow-xl relative overflow-hidden border border-[#dee3e9]/20">
        {{-- Soft Ambient Glows --}}
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-[#0064e0]/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-[#0457cb]/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-2xl text-center md:text-left">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md text-[#0091ff] text-xs font-bold uppercase tracking-wider mb-4 border border-white/15 shadow-sm">
                    <i class="bi bi-calendar-event-fill"></i> {{ __('Informasi Acara & Kegiatan') }}
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight mb-4">
                    {{ __('Agenda Kegiatan Perpustakaan') }}
                </h1>
                <p class="text-[#ced0d4] text-sm sm:text-base leading-relaxed mb-8 max-w-xl">
                    {{ __('Temukan dan ikuti berbagai kegiatan menarik di perpustakaan, mulai dari workshop literasi digital, bedah buku populer, kompetisi resensi, hingga pameran koleksi buku terbaru.') }}
                </p>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                    <a href="{{ route('opac.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white text-[#0a1317] hover:bg-[#dee3e9] font-bold text-xs uppercase tracking-wider transition-all shadow-md no-underline">
                        <i class="bi bi-arrow-left"></i> {{ __('Kembali ke Beranda') }}
                    </a>
                    @auth
                    <a href="{{ route('agendas.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-[#0064e0] hover:bg-[#0457cb] text-white font-bold text-xs uppercase tracking-wider transition-all shadow-md shadow-blue-500/20 no-underline">
                        <i class="bi bi-gear-fill"></i> {{ __('Kelola Agenda (Admin)') }}
                    </a>
                    @endauth
                </div>
            </div>

            {{-- Right Banner Graphic / Badge (Meta 32px Card) --}}
            <div class="hidden lg:flex flex-shrink-0 items-center justify-center w-52 h-52 rounded-3xl bg-white/5 border border-white/10 backdrop-blur-md p-6 text-center shadow-2xl">
                <div>
                    <div class="w-16 h-16 mx-auto rounded-full bg-[#0064e0]/20 text-[#0091ff] flex items-center justify-center text-3xl mb-3">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                    <span class="text-xs font-bold text-white block uppercase tracking-wider mb-1">{{ __('Events Agenda') }}</span>
                    <span class="text-[11px] text-[#ced0d4] font-medium">{{ __('Perpustakaan Resmi') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Program Kunjungan Kelas --}}
    <div class="mb-14">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-[#0064e0] uppercase tracking-wider">Jadwal Terjadwal</span>
                <h2 class="text-xl sm:text-2xl font-extrabold text-[#0a1317] mt-1 mb-1 tracking-tight flex items-center gap-2">
                    <i class="bi bi-diagram-3 text-[#0064e0]"></i> {{ __('Program Kunjungan Perpustakaan') }}
                </h2>
                <p class="text-xs text-[#5d6c7b] m-0">{{ __('Jadwal rutin kunjungan wajib perpustakaan untuk setiap jenjang kelas') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- SD Grid -->
            <a href="{{ route('opac.jadwal-kunjungan', 'sd') }}" class="card-meta p-7 text-center group no-underline hover:-translate-y-1 hover:shadow-xl cursor-pointer">
                <div class="w-16 h-16 mx-auto bg-red-50 text-red-500 rounded-full flex items-center justify-center text-3xl mb-4 group-hover:scale-110 group-hover:bg-red-500 group-hover:text-white transition-all shadow-sm">
                    <i class="bi bi-backpack"></i>
                </div>
                <h3 class="text-lg font-bold text-[#0a1317] mb-1 group-hover:text-[#0064e0] transition-colors">Sekolah Dasar (SD)</h3>
                <p class="text-xs text-[#5d6c7b] m-0">Lihat Jadwal Kunjungan Kelas SD</p>
            </a>

            <!-- SMP Grid -->
            <a href="{{ route('opac.jadwal-kunjungan', 'smp') }}" class="card-meta p-7 text-center group no-underline hover:-translate-y-1 hover:shadow-xl cursor-pointer">
                <div class="w-16 h-16 mx-auto bg-blue-50 text-[#0064e0] rounded-full flex items-center justify-center text-3xl mb-4 group-hover:scale-110 group-hover:bg-[#0064e0] group-hover:text-white transition-all shadow-sm">
                    <i class="bi bi-book-half"></i>
                </div>
                <h3 class="text-lg font-bold text-[#0a1317] mb-1 group-hover:text-[#0064e0] transition-colors">Menengah Pertama (SMP)</h3>
                <p class="text-xs text-[#5d6c7b] m-0">Lihat Jadwal Kunjungan Kelas SMP</p>
            </a>

            <!-- SMA Grid -->
            <a href="{{ route('opac.jadwal-kunjungan', 'sma') }}" class="card-meta p-7 text-center group no-underline hover:-translate-y-1 hover:shadow-xl cursor-pointer">
                <div class="w-16 h-16 mx-auto bg-[#f1f4f7] text-[#0a1317] rounded-full flex items-center justify-center text-3xl mb-4 group-hover:scale-110 group-hover:bg-[#0a1317] group-hover:text-white transition-all shadow-sm">
                    <i class="bi bi-mortarboard"></i>
                </div>
                <h3 class="text-lg font-bold text-[#0a1317] mb-1 group-hover:text-[#0064e0] transition-colors">Menengah Atas (SMA)</h3>
                <p class="text-xs text-[#5d6c7b] m-0">Lihat Jadwal Kunjungan Kelas SMA</p>
            </a>
        </div>
    </div>

    {{-- Section Title: Daftar Agenda Terdaftar --}}
    <div class="mb-8 flex items-center justify-between border-b border-[#dee3e9] pb-4">
        <div>
            <span class="text-xs font-bold text-[#0064e0] uppercase tracking-wider">Aktivitas & Acara</span>
            <h2 class="text-xl sm:text-2xl font-extrabold text-[#0a1317] mt-1 mb-1 tracking-tight flex items-center gap-2">
                <i class="bi bi-calendar-week text-[#0064e0]"></i> {{ __('Daftar Agenda Terdaftar') }}
            </h2>
            <p class="text-xs text-[#5d6c7b] m-0">{{ __('Jadwal kegiatan terbaru dan arsip acara perpustakaan') }}</p>
        </div>
    </div>

    {{-- Agendas List Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        @forelse($agendas as $agenda)
        <div class="bg-white rounded-3xl shadow-sm border border-[#dee3e9] overflow-hidden flex flex-col justify-between hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div>
                {{-- Category & Status Bar --}}
                <div class="p-5 border-b border-[#dee3e9] bg-[#f1f4f7]/40 flex items-center justify-between">
                    <span class="inline-flex px-3.5 py-1 rounded-full text-xs font-bold bg-white text-[#0064e0] border border-[#dee3e9] shadow-xs">
                        {{ $agenda->category }}
                    </span>
                    @php
                        $statusColors = [
                            'Akan Datang' => 'bg-[#0064e0] text-white',
                            'Berlangsung' => 'bg-[#31a24c] text-white',
                            'Selesai'    => 'bg-[#8595a4] text-white',
                            'Dibatalkan' => 'bg-[#e41e3f] text-white',
                        ];
                        $statusClass = $statusColors[$agenda->status] ?? 'bg-[#8595a4] text-white';
                    @endphp
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $statusClass }}">
                        {{ $agenda->status }}
                    </span>
                </div>

                {{-- Content Body --}}
                <div class="p-6">
                    <div class="flex items-center gap-3 text-xs font-bold text-[#0064e0] mb-3 bg-[#0064e0]/5 px-3.5 py-2 rounded-full border border-[#0064e0]/15 w-fit">
                        <span class="flex items-center gap-1.5"><i class="bi bi-calendar3"></i> {{ $agenda->event_date->format('d M Y') }}</span>
                        <span class="text-[#0064e0]/40">•</span>
                        <span class="flex items-center gap-1.5"><i class="bi bi-clock"></i> {{ $agenda->formatted_time }}</span>
                    </div>

                    <h3 class="text-lg font-bold text-[#0a1317] mb-2 line-clamp-2 leading-snug">
                        {{ $agenda->title }}
                    </h3>

                    @if($agenda->poster_image)
                    <div class="mb-4 rounded-2xl overflow-hidden shadow-xs border border-[#dee3e9] bg-[#f1f4f7] aspect-video relative">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($agenda->poster_image) }}" alt="Poster {{ $agenda->title }}" class="w-full h-full object-cover">
                    </div>
                    @endif

                    @if($agenda->description)
                    <p class="text-[#5d6c7b] text-xs sm:text-sm line-clamp-3 mb-4 leading-relaxed">
                        {{ $agenda->description }}
                    </p>
                    @endif

                    <div class="space-y-2 text-xs text-[#5d6c7b] border-t border-[#dee3e9] pt-4">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-geo-alt-fill text-[#0064e0] w-4 text-center"></i>
                            <span class="font-bold text-[#0a1317]">{{ $agenda->location }}</span>
                        </div>
                        @if($agenda->speaker)
                        <div class="flex items-center gap-2">
                            <i class="bi bi-person-badge-fill text-[#0064e0] w-4 text-center"></i>
                            <span>{{ __('Narasumber') }}: <strong class="text-[#0a1317]">{{ $agenda->speaker }}</strong></span>
                        </div>
                        @endif
                        @if($agenda->target_audience)
                        <div class="flex items-center gap-2">
                            <i class="bi bi-people-fill text-[#31a24c] w-4 text-center"></i>
                            <span>{{ __('Peserta') }}: {{ $agenda->target_audience }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Card Footer --}}
            <div class="px-6 py-3.5 bg-[#f1f4f7]/50 border-t border-[#dee3e9] flex items-center justify-between text-xs text-[#5d6c7b]">
                <span class="font-medium">
                    @if($agenda->quota)
                    <i class="bi bi-ticket-perforated mr-1 text-[#0064e0]"></i>{{ $agenda->quota }} {{ __('Kuota Peserta') }}
                    @else
                    <i class="bi bi-check-circle mr-1 text-[#31a24c]"></i>{{ __('Terbuka Untuk Umum') }}
                    @endif
                </span>
                <span class="text-[11px] text-[#8595a4] font-semibold">
                    <i class="bi bi-info-circle mr-1"></i>{{ __('Kegiatan Resmi') }}
                </span>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-3xl border border-[#dee3e9] p-12 text-center text-[#5d6c7b] shadow-sm">
            <i class="bi bi-calendar-x text-5xl text-[#ced0d4] block mb-3"></i>
            <h4 class="text-base font-bold text-[#0a1317] mb-1">{{ __('Belum Ada Agenda Kegiatan') }}</h4>
            <p class="text-xs text-[#8595a4] m-0">{{ __('Belum ada informasi agenda acara terbaru untuk saat ini.') }}</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8 flex justify-center">
        {{ $agendas->links() }}
    </div>

</div>
@endsection
