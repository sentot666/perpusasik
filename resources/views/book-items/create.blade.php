@extends('layouts.app')

@section('title', __('Tambah Eksemplar') . ': ' . $book->title)
@section('page-title', __('Tambah Eksemplar'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('books.index') }}">{{ __('Master Buku') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('books.show', $book) }}">{{ Str::limit($book->title, 20) }}</a></li>
<li class="breadcrumb-item active">{{ __('Tambah Eksemplar') }}</li>
@endsection

@section('content')
<div class="page-header">
    <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ __('Tambah Eksemplar Fisik') }}</h1>
    <p>{{ __('Daftarkan eksemplar fisik/kopi buku baru untuk judul') }}: <strong>{{ $book->title }}</strong></p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-plus-circle-fill text-indigo-600 mr-2"></i>{{ __('Form Registrasi Eksemplar') }}</div>
    <div class="p-8">

        @php
            $nextCopyNum = $book->items()->count() + 1;
            $nextCopyCode = 'c' . $nextCopyNum;
        @endphp

        <div class="bg-indigo-50/70 border border-indigo-100 rounded-xl p-4 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-lg shadow-sm">
                    {{ $nextCopyCode }}
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 text-sm md:text-base">Mendaftarkan Eksemplar ke-{{ $nextCopyNum }} (Kode Kopi: <span class="text-indigo-600 font-bold">{{ $nextCopyCode }}</span>)</h4>
                    <p class="text-xs text-slate-500 mt-0.5">No. Panggil: <span class="font-semibold text-slate-700">{{ $book->call_number ?? ($book->ddc ? $book->ddc : '-') }}</span> &bull; Total Eksemplar Saat Ini: {{ $book->items()->count() }}</p>
                </div>
            </div>
            <div class="text-xs text-slate-600 bg-white px-3 py-1.5 rounded-lg border border-indigo-200 shadow-xs self-start sm:self-auto">
                <span class="font-medium text-slate-500">Label Punggung:</span> 
                <span class="font-bold text-indigo-700">{{ $book->call_number ?? ($book->ddc ? $book->ddc : '-') }} {{ $nextCopyCode }}</span>
            </div>
        </div>

        @if($errors->any())
        <div class="alert alert-danger border-0 mb-6 py-2" style="border-radius:8px">
            <i class="bi bi-exclamation-triangle-fill mr-1"></i>
            {{ __('Periksa kembali form Anda.') }}
        </div>
        @endif

        <form method="POST" action="{{ route('books.items.store', $book) }}">
            @csrf

            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/2 px-4">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Barcode / RFID') }} <span class="text-red-600">*</span></label>
                        {{-- Generate a sequential barcode suggestion like B002524 --}}
                        @php
                            $maxId = \App\Models\BookItem::max('id') ?? 0;
                            $barcodeSuggestion = 'B' . str_pad($maxId + 1, 6, '0', STR_PAD_LEFT);
                        @endphp
                        <input type="text" name="barcode" class="@error('barcode') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" value="{{ old('barcode', $barcodeSuggestion) }}" required placeholder="{{ __('Scan barcode atau ketik manual') }}">
                        @error('barcode')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                        <div class="form-text">{{ __('Harus unik di seluruh sistem perpustakaan') }}</div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Nomor Induk / No. Inventaris') }} <span class="text-red-600">*</span></label>
                        <input type="text" name="accession_number" class="@error('accession_number') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" value="{{ old('accession_number', $accessionNumber) }}" required>
                        @error('accession_number')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Lokasi / Rak Penyimpanan') }}</label>
                        <select name="location_id" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4">
                            <option value="">{{ __('Pilih Lokasi...') }}</option>
                            @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }} ({{ $loc->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Kondisi Buku') }}</label>
                        <select name="condition" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4">
                            <option value="Baik" {{ old('condition') == 'Baik' ? 'selected' : '' }}>{{ __('Baik') }}</option>
                            <option value="Rusak Ringan" {{ old('condition') == 'Rusak Ringan' ? 'selected' : '' }}>{{ __('Rusak Ringan') }}</option>
                            <option value="Rusak Berat" {{ old('condition') == 'Rusak Berat' ? 'selected' : '' }}>{{ __('Rusak Berat') }}</option>
                        </select>
                    </div>
                </div>

                <div class="w-full md:w-1/2 px-4">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Tanggal Perolehan') }}</label>
                        <input type="date" name="acquisition_date" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('acquisition_date', date('Y-m-d')) }}">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Harga Perolehan (Rp)') }}</label>
                        <input type="number" name="acquisition_price" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('acquisition_price', 0) }}" min="0">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Sumber Perolehan') }}</label>
                        <select name="acquisition_source" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4">
                            <option value="Beli" {{ old('acquisition_source') == 'Beli' ? 'selected' : '' }}>{{ __('Pembelian') }}</option>
                            <option value="Hibah" {{ old('acquisition_source') == 'Hibah' ? 'selected' : '' }}>{{ __('Hibah / Sumbangan') }}</option>
                            <option value="Droping" {{ old('acquisition_source') == 'Droping' ? 'selected' : '' }}>{{ __('Bantuan / Dropping') }}</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Catatan Khusus') }}</label>
                        <textarea name="notes" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" rows="2" placeholder="{{ __('Keterangan tambahan untuk eksemplar ini...') }}">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <hr>

            <div class="justify-end flex gap-2">
                <a href="{{ route('books.show', $book) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2 px-6">{{ __('Batal') }}</a>
                <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white font-semibold gap-2 py-2 px-6">{{ __('Simpan Eksemplar') }}</button>
            </div>
        </form>

    </div>
</div>
@endsection


