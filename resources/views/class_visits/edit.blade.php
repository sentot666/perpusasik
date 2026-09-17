@extends('layouts.app')

@section('title', __('Edit Jadwal Kunjungan'))
@section('page-title', __('Edit Jadwal Kunjungan'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('class-visits.index') }}">{{ __('Jadwal Kunjungan') }}</a></li>
<li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection

@section('content')
<div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 mb-1">{{ __('Edit Jadwal Kunjungan') }}</h1>
        <p class="text-slate-500 text-xs sm:text-sm">{{ __('Perbarui informasi rutinitas kunjungan kelas perpustakaan') }}</p>
    </div>
    <div>
        <a href="{{ route('class-visits.index') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 shadow-sm gap-2 py-2 px-4 transition-all">
            <i class="bi bi-arrow-left"></i> {{ __('Kembali') }}
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden max-w-4xl mb-12">
    <div class="px-6 sm:px-8 border-b border-slate-200 bg-slate-50 font-semibold text-slate-700 py-4 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <i class="bi bi-pencil text-indigo-600 text-lg"></i>
            <span>{{ __('Form Edit Jadwal Kunjungan Kelas') }}</span>
        </div>
        <span class="text-xs font-normal text-slate-500"><span class="text-red-500 font-bold">*</span> Wajib diisi</span>
    </div>
    
    <div class="p-6 sm:p-8">
        @if(isset($errors) && $errors->any())
        <div class="bg-red-50 text-red-700 rounded-xl p-4 mb-6 text-sm border border-red-200 flex items-start gap-3">
            <div class="w-6 h-6 rounded-lg bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="bi bi-exclamation-triangle-fill text-xs"></i>
            </div>
            <div>
                <div class="font-bold mb-1">{{ __('Terjadi kesalahan pengisian:') }}</div>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-red-600">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <form action="{{ route('class-visits.update', $classVisit->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5 mb-8">
                {{-- Tingkat / Level --}}
                <div>
                    <label for="level" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        {{ __('Tingkat / Jenjang') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="level" id="level" class="w-full rounded-lg border border-slate-300 py-2.5 px-3.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors bg-white appearance-none" required>
                            <option value="">-- {{ __('Pilih Jenjang Sekolah') }} --</option>
                            <option value="sd" {{ old('level', $classVisit->level) == 'sd' ? 'selected' : '' }}>{{ __('Sekolah Dasar (SD)') }}</option>
                            <option value="smp" {{ old('level', $classVisit->level) == 'smp' ? 'selected' : '' }}>{{ __('Menengah Pertama (SMP)') }}</option>
                            <option value="sma" {{ old('level', $classVisit->level) == 'sma' ? 'selected' : '' }}>{{ __('Menengah Atas (SMA)') }}</option>
                        </select>
                        <i class="bi bi-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                    </div>
                    @error('level') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Hari --}}
                <div>
                    <label for="day" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        {{ __('Hari') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="day" id="day" value="{{ old('day', $classVisit->day) }}" class="w-full rounded-lg border border-slate-300 py-2.5 pl-3.5 pr-9 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors" placeholder="{{ __('Contoh: Senin / Selasa') }}" required>
                        <i class="bi bi-calendar-event absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-sm"></i>
                    </div>
                    @error('day') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Tanggal Spesifik (Opsional) --}}
                <div>
                    <label for="date" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        {{ __('Tanggal Spesifik') }} <span class="text-slate-400 font-normal">({{ __('Opsional') }})</span>
                    </label>
                    <div class="relative">
                        <input type="date" name="date" id="date" value="{{ old('date', $classVisit->date ? $classVisit->date->format('Y-m-d') : '') }}" class="w-full rounded-lg border border-slate-300 py-2.5 px-3.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors bg-white">
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1.5 flex items-center gap-1">
                        <i class="bi bi-info-circle text-indigo-500"></i>
                        <span>{{ __('Kosongkan jika jadwal ini berlaku rutin berulang setiap minggu.') }}</span>
                    </p>
                    @error('date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Waktu / Jam --}}
                <div>
                    <label for="time" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        {{ __('Waktu / Jam Kunjungan') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="time" id="time" value="{{ old('time', $classVisit->time) }}" class="w-full rounded-lg border border-slate-300 py-2.5 pl-3.5 pr-9 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors" placeholder="{{ __('Contoh: 08:00 - 08:45 WIB') }}" required>
                        <i class="bi bi-clock absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-sm"></i>
                    </div>
                    @error('time') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Nama Kelas / Rombel --}}
                <div>
                    <label for="class_name" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        {{ __('Nama Kelas / Rombel') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="class_name" id="class_name" value="{{ old('class_name', $classVisit->class_name) }}" class="w-full rounded-lg border border-slate-300 py-2.5 pl-3.5 pr-9 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors" placeholder="{{ __('Contoh: Kelas 1A / Kelas 7B / Kelas 10-IPA') }}" required>
                        <i class="bi bi-door-closed absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-sm"></i>
                    </div>
                    @error('class_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Guru Pendamping --}}
                <div>
                    <label for="teacher_name" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        {{ __('Guru Pendamping') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="teacher_name" id="teacher_name" value="{{ old('teacher_name', $classVisit->teacher_name) }}" class="w-full rounded-lg border border-slate-300 py-2.5 pl-3.5 pr-9 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors" placeholder="{{ __('Contoh: Bpk. Budi Santoso, S.Pd') }}" required>
                        <i class="bi bi-person absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-sm"></i>
                    </div>
                    @error('teacher_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end items-center gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('class-visits.index') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 shadow-sm py-2.5 px-5 transition-all">
                    {{ __('Batal') }}
                </a>
                <button type="submit" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg btn-gradient-blue shadow-md shadow-indigo-500/20 text-white py-2.5 px-6 transition-all transform hover:-translate-y-0.5 gap-2">
                    <i class="bi bi-save text-base"></i>
                    <span>{{ __('Perbarui Jadwal Kunjungan') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
