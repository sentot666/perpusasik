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
    <div>
        <button onclick="openModal('createAgendaModal')" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg btn-gradient-blue shadow-md shadow-indigo-500/20 text-white gap-2 py-2.5 px-5 transition-all transform hover:-translate-y-0.5">
            <i class="bi bi-plus-lg text-base"></i> {{ __('Tambah Agenda Baru') }}
        </button>
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
                <button onclick="editAgenda({{ json_encode($agenda) }})" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="{{ __('Edit Agenda') }}">
                    <i class="bi bi-pencil"></i>
                </button>
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
        <button onclick="openModal('createAgendaModal')" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-lg btn-gradient-blue text-white shadow-sm transition-all">
            <i class="bi bi-plus-lg"></i> {{ __('Tambah Agenda Pertama') }}
        </button>
    </div>
    @endforelse
</div>

{{-- Pagination Links --}}
<div class="mt-4">
    {{ $agendas->links() }}
</div>

{{-- ═══ MODAL TAMBAH AGENDA ═══════════════════════════════════════════════════ --}}
<div id="createAgendaModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-2xl overflow-hidden transform transition-all my-8">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <i class="bi bi-calendar-plus text-indigo-600"></i> {{ __('Tambah Agenda Kegiatan') }}
            </h3>
            <button onclick="closeModal('createAgendaModal')" class="text-slate-400 hover:text-slate-600"><i class="bi bi-x-lg"></i></button>
        </div>

        <form method="POST" action="{{ route('agendas.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Judul Acara / Kegiatan') }} <span class="text-red-500">*</span></label>
                <input type="text" name="title" required placeholder="{{ __('Contoh: Bedah Buku & Literasi Digital') }}" class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Kategori') }} <span class="text-red-500">*</span></label>
                    <select name="category" required class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white">
                        @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Status') }} <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white">
                        @foreach($statuses as $st)
                        <option value="{{ $st }}">{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Tanggal Acara') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="event_date" required value="{{ date('Y-m-d') }}" class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Jam Mulai') }} <span class="text-red-500">*</span></label>
                    <input type="time" name="start_time" required value="09:00" class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Jam Selesai') }}</label>
                    <input type="time" name="end_time" value="12:00" class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Lokasi / Ruangan') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="location" required placeholder="{{ __('Ruang Baca Lt. 2 / Zoom') }}" class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Narasumber / Pembicara') }}</label>
                    <input type="text" name="speaker" placeholder="{{ __('Nama narasumber (opsional)') }}" class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Target Peserta') }}</label>
                    <input type="text" name="target_audience" placeholder="{{ __('Siswa / Guru / Umum') }}" class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Kuota Peserta') }}</label>
                    <input type="number" name="quota" placeholder="{{ __('Jumlah kuota (opsional)') }}" class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Deskripsi Ringkas Kegiatan') }}</label>
                <textarea name="description" rows="3" placeholder="{{ __('Jelaskan detail singkat acara kegiatan perpustakaan...') }}" class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeModal('createAgendaModal')" class="px-5 py-2.5 text-xs font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors">{{ __('Batal') }}</button>
                <button type="submit" class="px-5 py-2.5 text-xs font-semibold rounded-lg btn-gradient-green text-white shadow-md transition-all">{{ __('Simpan Agenda') }}</button>
            </div>
        </form>
    </div>
</div>

{{-- ═══ MODAL EDIT AGENDA ════════════════════════════════════════════════════ --}}
<div id="editAgendaModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-2xl overflow-hidden transform transition-all my-8">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <i class="bi bi-pencil-square text-indigo-600"></i> {{ __('Edit Agenda Kegiatan') }}
            </h3>
            <button onclick="closeModal('editAgendaModal')" class="text-slate-400 hover:text-slate-600"><i class="bi bi-x-lg"></i></button>
        </div>

        <form id="editAgendaForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Judul Acara / Kegiatan') }} <span class="text-red-500">*</span></label>
                <input type="text" id="edit_title" name="title" required class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Kategori') }} <span class="text-red-500">*</span></label>
                    <select id="edit_category" name="category" required class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white">
                        @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Status') }} <span class="text-red-500">*</span></label>
                    <select id="edit_status" name="status" required class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white">
                        @foreach($statuses as $st)
                        <option value="{{ $st }}">{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Tanggal Acara') }} <span class="text-red-500">*</span></label>
                    <input type="date" id="edit_event_date" name="event_date" required class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Jam Mulai') }} <span class="text-red-500">*</span></label>
                    <input type="time" id="edit_start_time" name="start_time" required class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Jam Selesai') }}</label>
                    <input type="time" id="edit_end_time" name="end_time" class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Lokasi / Ruangan') }} <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_location" name="location" required class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Narasumber / Pembicara') }}</label>
                    <input type="text" id="edit_speaker" name="speaker" class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Target Peserta') }}</label>
                    <input type="text" id="edit_target_audience" name="target_audience" class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Kuota Peserta') }}</label>
                    <input type="number" id="edit_quota" name="quota" class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Deskripsi Ringkas Kegiatan') }}</label>
                <textarea id="edit_description" name="description" rows="3" class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeModal('editAgendaModal')" class="px-5 py-2.5 text-xs font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors">{{ __('Batal') }}</button>
                <button type="submit" class="px-5 py-2.5 text-xs font-semibold rounded-lg btn-gradient-green text-white shadow-md transition-all">{{ __('Simpan Perubahan') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function editAgenda(agenda) {
        document.getElementById('editAgendaForm').action = '/agendas/' + agenda.id;
        document.getElementById('edit_title').value = agenda.title || '';
        document.getElementById('edit_category').value = agenda.category || '';
        document.getElementById('edit_status').value = agenda.status || '';
        
        if (agenda.event_date) {
            let date = new Date(agenda.event_date);
            let formattedDate = date.toISOString().split('T')[0];
            document.getElementById('edit_event_date').value = formattedDate;
        }

        document.getElementById('edit_start_time').value = agenda.start_time ? agenda.start_time.substring(0, 5) : '';
        document.getElementById('edit_end_time').value = agenda.end_time ? agenda.end_time.substring(0, 5) : '';
        document.getElementById('edit_location').value = agenda.location || '';
        document.getElementById('edit_speaker').value = agenda.speaker || '';
        document.getElementById('edit_target_audience').value = agenda.target_audience || '';
        document.getElementById('edit_quota').value = agenda.quota || '';
        document.getElementById('edit_description').value = agenda.description || '';

        openModal('editAgendaModal');
    }
</script>
@endsection
