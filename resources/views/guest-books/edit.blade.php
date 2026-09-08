@extends('layouts.app')

@section('title', __('Edit Catatan Kunjungan'))
@section('page-title', __('Edit Kunjungan'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('guest-books.index') }}">{{ __('Buku Tamu') }}</a></li>
<li class="breadcrumb-item active">{{ __('Edit Kunjungan') }}</li>
@endsection

@section('content')
<div class="page-header mb-6">
    <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ __('Edit Catatan Kunjungan') }}</h1>
    <p class="text-slate-500">{{ __('Perbarui informasi aktivitas harian tamu di perpustakaan') }}</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden max-w-3xl">
    <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-pencil-square text-indigo-600 mr-2"></i>{{ __('Form Edit Kunjungan') }}</div>
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

        <form method="POST" action="{{ route('guest-books.update', $guestBook) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Tanggal') }} <span class="text-red-500">*</span></label>
                    <input type="date" name="visit_date" required value="{{ old('visit_date', $guestBook->visit_date ? $guestBook->visit_date->format('Y-m-d') : date('Y-m-d')) }}" class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Waktu') }} <span class="text-red-500">*</span></label>
                    <input type="time" name="visit_time" required value="{{ old('visit_time', $guestBook->visit_time ? \Carbon\Carbon::parse($guestBook->visit_time)->format('H:i') : date('H:i')) }}" class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Nama Lengkap Tamu') }} <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $guestBook->name) }}" required class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Instansi / Asal') }} <span class="text-red-500">*</span></label>
                <input type="text" name="institution" value="{{ old('institution', $guestBook->institution) }}" required class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Tujuan Kunjungan') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="purpose" value="{{ old('purpose', $guestBook->purpose) }}" required class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Jumlah Peserta') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="participants_count" value="{{ old('participants_count', $guestBook->participants_count) }}" min="1" required class="w-full py-2.5 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Catatan Tambahan') }}</label>
                <textarea name="notes" rows="3" class="w-full py-3 px-4 text-sm rounded-lg border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">{{ old('notes', $guestBook->notes) }}</textarea>
            </div>

            <hr class="my-6 border-slate-200">

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('guest-books.index') }}" class="px-6 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 transition-colors">{{ __('Batal') }}</a>
                <button type="submit" class="px-6 py-2.5 text-sm font-semibold rounded-lg btn-gradient-green text-white shadow-md shadow-emerald-500/20 transition-all hover:-translate-y-0.5"><i class="bi bi-check-circle-fill mr-2"></i>{{ __('Simpan Perubahan') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
