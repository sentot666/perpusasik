@extends('layouts.app')

@section('title', __('Tambah Agenda Baru'))
@section('page-title', __('Tambah Agenda'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('agendas.index') }}">{{ __('Agenda Kegiatan') }}</a></li>
<li class="breadcrumb-item active">{{ __('Tambah Agenda') }}</li>
@endsection

@section('content')
<div class="page-header mb-6">
    <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ __('Tambah Agenda Baru') }}</h1>
    <p class="text-slate-500">{{ __('Jadwalkan acara atau kegiatan baru di perpustakaan') }}</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-calendar-plus text-indigo-600 mr-2"></i>{{ __('Form Data Agenda') }}</div>
    <div class="p-8">

        @if($errors->any())
        <div class="bg-red-50 text-red-600 rounded-lg p-4 mb-6 text-sm border border-red-100">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('agendas.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Judul Acara / Kegiatan') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="{{ __('Contoh: Bedah Buku & Literasi Digital') }}" class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Kategori') }} <span class="text-red-500">*</span></label>
                        <select name="category" required class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white transition-colors">
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Status') }} <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white transition-colors">
                            @foreach($statuses as $st)
                            <option value="{{ $st }}" {{ old('status', 'Akan Datang') == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Tanggal Acara') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="event_date" required value="{{ old('event_date', date('Y-m-d')) }}" class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Jam Mulai') }} <span class="text-red-500">*</span></label>
                    <input type="time" name="start_time" required value="{{ old('start_time', '09:00') }}" class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Jam Selesai') }}</label>
                    <input type="time" name="end_time" value="{{ old('end_time', '12:00') }}" class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Lokasi / Ruangan') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="location" value="{{ old('location') }}" required placeholder="{{ __('Ruang Baca Lt. 2 / Zoom') }}" class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Narasumber / Pembicara') }}</label>
                    <input type="text" name="speaker" value="{{ old('speaker') }}" placeholder="{{ __('Nama narasumber (opsional)') }}" class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Target Peserta') }}</label>
                    <input type="text" name="target_audience" value="{{ old('target_audience') }}" placeholder="{{ __('Siswa / Guru / Umum') }}" class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Kuota Peserta') }}</label>
                    <input type="number" name="quota" value="{{ old('quota') }}" placeholder="{{ __('Jumlah kuota (opsional)') }}" class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Poster / Foto Kegiatan (Opsional)') }}</label>
                <input type="file" name="poster_image" accept="image/*" class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 bg-white">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Deskripsi Ringkas Kegiatan') }}</label>
                <textarea name="description" rows="4" placeholder="{{ __('Jelaskan detail singkat acara kegiatan perpustakaan...') }}" class="w-full py-3 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">{{ old('description') }}</textarea>
            </div>

            <hr class="my-6 border-slate-200">

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('agendas.index') }}" class="px-6 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 transition-colors">{{ __('Batal') }}</a>
                <button type="submit" class="px-6 py-2.5 text-sm font-semibold rounded-lg btn-gradient-green text-white shadow-md shadow-emerald-500/20 transition-all hover:-translate-y-0.5"><i class="bi bi-check-circle-fill mr-2"></i>{{ __('Simpan Agenda') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
