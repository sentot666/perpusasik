@extends('layouts.app')

@section('title', __('Edit Eksemplar') . ': ' . $item->barcode)
@section('page-title', __('Edit Eksemplar'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('books.index') }}">{{ __('Master Buku') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('books.show', $item->book) }}">{{ Str::limit($item->book->title, 20) }}</a></li>
<li class="breadcrumb-item active">{{ __('Edit Eksemplar') }}</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ __('Edit Eksemplar Fisik') }}</h1>
    <p>{{ __('Perbarui status dan penempatan untuk eksemplar') }}: <code>{{ $item->barcode }}</code></p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-pencil-square text-indigo-600 mr-2"></i>{{ __('Form Perubahan Eksemplar') }}</div>
    <div class="p-8">

        @if($errors->any())
        <div class="alert alert-danger border-0 mb-6 py-2" style="border-radius:8px">
            <i class="bi bi-exclamation-triangle-fill mr-1"></i>
            {{ __('Periksa kembali form Anda.') }}
        </div>
        @endif

        <form method="POST" action="{{ route('items.update', $item) }}">
            @csrf
            @method('PUT')

            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/2 px-4">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Barcode / RFID') }} <span class="text-red-600">*</span></label>
                        <input type="text" name="barcode" class="@error('barcode') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" value="{{ old('barcode', $item->barcode) }}" required>
                        @error('barcode')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Nomor Induk / No. Inventaris') }} <span class="text-red-600">*</span></label>
                        <input type="text" name="accession_number" class="@error('accession_number') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" value="{{ old('accession_number', $item->accession_number) }}" required>
                        @error('accession_number')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Lokasi / Rak Penyimpanan') }}</label>
                        <select name="location_id" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4">
                            <option value="">{{ __('Pilih Lokasi...') }}</option>
                            @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ old('location_id', $item->location_id) == $loc->id ? 'selected' : '' }}>{{ $loc->name }} ({{ $loc->code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="w-full md:w-1/2 px-4">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Kondisi Buku') }}</label>
                        <select name="condition" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4">
                            <option value="Baik" {{ old('condition', $item->condition) == 'Baik' ? 'selected' : '' }}>{{ __('Baik') }}</option>
                            <option value="Rusak" {{ old('condition', $item->condition) == 'Rusak' ? 'selected' : '' }}>{{ __('Rusak') }}</option>
                            <option value="Hilang" {{ old('condition', $item->condition) == 'Hilang' ? 'selected' : '' }}>{{ __('Hilang') }}</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Status Ketersediaan') }} <span class="text-red-600">*</span></label>
                        <select name="status" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4" required>
                            <option value="Tersedia" {{ old('status', $item->status) == 'Tersedia' ? 'selected' : '' }}>{{ __('Tersedia') }}</option>
                            <option value="Dipesan" {{ old('status', $item->status) == 'Dipesan' ? 'selected' : '' }}>{{ __('Dipesan') }}</option>
                            <option value="Perbaikan" {{ old('status', $item->status) == 'Perbaikan' ? 'selected' : '' }}>{{ __('Perbaikan / Perawatan') }}</option>
                            <option value="Hilang" {{ old('status', $item->status) == 'Hilang' ? 'selected' : '' }}>{{ __('Hilang') }}</option>
                        </select>
                        <div class="form-text">{{ __('Catatan: Status \'Dipinjam\' dikontrol otomatis oleh transaksi sirkulasi') }}</div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Catatan Khusus') }}</label>
                        <textarea name="notes" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" rows="2">{{ old('notes', $item->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <hr>

            <div class="justify-end flex gap-2">
                <a href="{{ route('books.show', $item->book) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2 px-6">{{ __('Batal') }}</a>
                <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white font-semibold gap-2 py-2 px-6">{{ __('Simpan Perubahan') }}</button>
            </div>
        </form>

    </div>
</div>
@endsection


