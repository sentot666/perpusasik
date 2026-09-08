@extends('layouts.app')

@section('title', __('Catat Kunjungan Baru'))
@section('page-title', __('Catat Kunjungan'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('guest-books.index') }}">{{ __('Buku Tamu') }}</a></li>
<li class="breadcrumb-item active">{{ __('Catat Kunjungan') }}</li>
@endsection

@section('content')
<div class="page-header mb-6">
    <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ __('Catat Kunjungan Baru') }}</h1>
    <p class="text-slate-500">{{ __('Pendataan aktivitas harian tamu di perpustakaan') }}</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden max-w-3xl">
    <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-journal-plus text-indigo-600 mr-2"></i>{{ __('Form Kunjungan Baru') }}</div>
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

        <form method="POST" action="{{ route('guest-books.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Tanggal') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="visit_date" required value="{{ old('visit_date', date('Y-m-d')) }}" class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Waktu') }} <span class="text-red-500">*</span></label>
                    <input type="time" name="visit_time" required value="{{ old('visit_time', date('H:i')) }}" class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Nama Lengkap Tamu') }} <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="{{ __('Ketik nama lengkap...') }}" class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Instansi / Asal') }} <span class="text-red-500">*</span></label>
                <input type="text" name="institution" value="{{ old('institution') }}" required placeholder="{{ __('Ketik instansi asal atau kelas...') }}" class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Tujuan Kunjungan') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="purpose" value="{{ old('purpose') }}" required placeholder="{{ __('Tujuan kunjungan...') }}" class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Jumlah Peserta') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="participants_count" value="{{ old('participants_count', 1) }}" min="1" required class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Catatan Tambahan') }}</label>
                <textarea name="notes" rows="3" placeholder="{{ __('Opsional...') }}" class="w-full py-3 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">{{ old('notes') }}</textarea>
            </div>

            <hr class="my-6 border-slate-200">

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('guest-books.index') }}" class="px-6 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 transition-colors">{{ __('Batal') }}</a>
                <button type="submit" class="px-6 py-2.5 text-sm font-semibold rounded-lg btn-gradient-green text-white shadow-md shadow-emerald-500/20 transition-all hover:-translate-y-0.5"><i class="bi bi-check-circle-fill mr-2"></i>{{ __('Simpan Catatan') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
