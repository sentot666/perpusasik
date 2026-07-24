@extends('layouts.app')

@section('title', 'Tambah Eksemplar: ' . $book->title)
@section('page-title', 'Tambah Eksemplar')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('books.index') }}">Master Buku</a></li>
<li class="breadcrumb-item"><a href="{{ route('books.show', $book) }}">{{ Str::limit($book->title, 20) }}</a></li>
<li class="breadcrumb-item active">Tambah Eksemplar</li>
@endsection

@section('content')
<div class="page-header">
    <h1>Tambah Eksemplar Fisik</h1>
    <p>Daftarkan eksemplar fisik/kopi buku baru untuk judul: <strong>{{ $book->title }}</strong></p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-plus-circle-fill text-indigo-600 mr-2"></i>Form Registrasi Eksemplar</div>
    <div class="p-8">

        @if($errors->any())
        <div class="alert alert-danger border-0 mb-6 py-2" style="border-radius:8px">
            <i class="bi bi-exclamation-triangle-fill mr-1"></i>
            Periksa kembali form Anda.
        </div>
        @endif

        <form method="POST" action="{{ route('books.items.store', $book) }}">
            @csrf

            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/2 px-4">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Barcode / RFID <span class="text-red-600">*</span></label>
                        {{-- Quick helper: let's generate a unique barcode suggestion based on timestamp + random --}}
                        @php
                            $barcodeSuggestion = 'B' . now()->format('ymdHis') . rand(10, 99);
                        @endphp
                        <input type="text" name="barcode" class="@error('barcode') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" value="{{ old('barcode', $barcodeSuggestion) }}" required placeholder="Scan barcode atau ketik manual">
                        @error('barcode')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                        <div class="form-text">Harus unik di seluruh sistem perpustakaan</div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Induk / No. Inventaris <span class="text-red-600">*</span></label>
                        <input type="text" name="accession_number" class="@error('accession_number') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" value="{{ old('accession_number', $accessionNumber) }}" required>
                        @error('accession_number')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Lokasi / Rak Penyimpanan</label>
                        <select name="location_id" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4">
                            <option value="">Pilih Lokasi...</option>
                            @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }} ({{ $loc->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kondisi Buku</label>
                        <select name="condition" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4">
                            <option value="Baik" {{ old('condition') == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ old('condition') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ old('condition') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>
                </div>

                <div class="w-full md:w-1/2 px-4">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Perolehan</label>
                        <input type="date" name="acquisition_date" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('acquisition_date', date('Y-m-d')) }}">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Harga Perolehan (Rp)</label>
                        <input type="number" name="acquisition_price" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('acquisition_price', 0) }}" min="0">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Sumber Perolehan</label>
                        <select name="acquisition_source" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4">
                            <option value="Beli" {{ old('acquisition_source') == 'Beli' ? 'selected' : '' }}>Pembelian</option>
                            <option value="Hibah" {{ old('acquisition_source') == 'Hibah' ? 'selected' : '' }}>Hibah / Sumbangan</option>
                            <option value="Droping" {{ old('acquisition_source') == 'Droping' ? 'selected' : '' }}>Bantuan / Dropping</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Catatan Khusus</label>
                        <textarea name="notes" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" rows="2" placeholder="Keterangan tambahan untuk eksemplar ini...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <hr>

            <div class="justify-end flex gap-2">
                <a href="{{ route('books.show', $book) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2 px-6">Batal</a>
                <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 transition-colors text-white font-semibold gap-2 py-2 px-6">Simpan Eksemplar</button>
            </div>
        </form>

    </div>
</div>
@endsection
