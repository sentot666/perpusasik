@extends('layouts.app')

@section('title', 'Edit Jadwal Kunjungan')
@section('page-title', 'Edit Jadwal Kunjungan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('class-visits.index') }}">{{ __('Jadwal Kunjungan') }}</a></li>
<li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection

@section('content')
<div class="page-header mb-6">
    <h1 class="text-3xl font-bold text-slate-800 mb-1">Edit Jadwal Kunjungan</h1>
    <p class="text-slate-500 text-sm">Ubah data rutinitas kunjungan kelas perpustakaan.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-12">
    <div class="px-6 sm:px-8 border-b border-slate-200 bg-slate-50 font-semibold text-slate-700 py-4 flex items-center">
        <i class="bi bi-pencil-square text-indigo-600 mr-3 text-lg"></i> Form Edit Jadwal Kunjungan
    </div>
    
    <div class="p-6 sm:p-8">
        <form action="{{ route('class-visits.update', $classVisit->id) }}" method="POST" class="max-w-4xl">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5 mb-8">
                <div>
                    <label for="level" class="block text-sm font-semibold text-slate-700 mb-1.5">Tingkat / Level <span class="text-red-500">*</span></label>
                    <select name="level" id="level" class="w-full rounded-lg border border-slate-300 py-2.5 px-4 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors" required>
                        <option value="">-- Pilih Tingkat --</option>
                        <option value="sd" {{ old('level', $classVisit->level) == 'sd' ? 'selected' : '' }}>Sekolah Dasar (SD)</option>
                        <option value="smp" {{ old('level', $classVisit->level) == 'smp' ? 'selected' : '' }}>Menengah Pertama (SMP)</option>
                        <option value="sma" {{ old('level', $classVisit->level) == 'sma' ? 'selected' : '' }}>Menengah Atas (SMA)</option>
                    </select>
                    @error('level') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="day" class="block text-sm font-semibold text-slate-700 mb-1.5">Hari <span class="text-red-500">*</span></label>
                    <input type="text" name="day" id="day" value="{{ old('day', $classVisit->day) }}" class="w-full rounded-lg border border-slate-300 py-2.5 px-4 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors" placeholder="Contoh: Senin" required>
                    @error('day') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="date" class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Spesifik <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <input type="date" name="date" id="date" value="{{ old('date', $classVisit->date ? $classVisit->date->format('Y-m-d') : '') }}" class="w-full rounded-lg border border-slate-300 py-2.5 px-4 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    <p class="text-[11px] text-slate-500 mt-1.5"><i class="bi bi-info-circle mr-1"></i>Kosongkan jika jadwal ini berlaku rutin setiap minggu.</p>
                    @error('date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="time" class="block text-sm font-semibold text-slate-700 mb-1.5">Waktu / Jam <span class="text-red-500">*</span></label>
                    <input type="text" name="time" id="time" value="{{ old('time', $classVisit->time) }}" class="w-full rounded-lg border border-slate-300 py-2.5 px-4 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors" placeholder="Contoh: 08:00 - 08:45" required>
                    @error('time') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="class_name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Kelas/Rombel <span class="text-red-500">*</span></label>
                    <input type="text" name="class_name" id="class_name" value="{{ old('class_name', $classVisit->class_name) }}" class="w-full rounded-lg border border-slate-300 py-2.5 px-4 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors" placeholder="Contoh: Kelas 1A" required>
                    @error('class_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="teacher_name" class="block text-sm font-semibold text-slate-700 mb-1.5">Guru Pendamping <span class="text-red-500">*</span></label>
                    <input type="text" name="teacher_name" id="teacher_name" value="{{ old('teacher_name', $classVisit->teacher_name) }}" class="w-full rounded-lg border border-slate-300 py-2.5 px-4 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors" placeholder="Contoh: Bpk. Budi Santoso" required>
                    @error('teacher_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('class-visits.index') }}" class="inline-flex items-center justify-center text-sm font-semibold rounded-lg border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 shadow-sm py-2 px-5 transition-all">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center text-sm font-semibold rounded-lg btn-gradient-blue shadow-md shadow-indigo-500/20 text-white py-2 px-6 transition-all transform hover:-translate-y-0.5">
                    <i class="bi bi-save mr-2"></i> Update Jadwal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
