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

<div class="card">
    <div class="card-header"><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Form Registrasi Eksemplar</div>
    <div class="card-body">

        @if($errors->any())
        <div class="alert alert-danger border-0 py-2 mb-3" style="border-radius:8px">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            Periksa kembali form Anda.
        </div>
        @endif

        <form method="POST" action="{{ route('books.items.store', $book) }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-500">Barcode / RFID <span class="text-danger">*</span></label>
                        {{-- Quick helper: let's generate a unique barcode suggestion based on timestamp + random --}}
                        @php
                            $barcodeSuggestion = 'B' . now()->format('ymdHis') . rand(10, 99);
                        @endphp
                        <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode', $barcodeSuggestion) }}" required placeholder="Scan barcode atau ketik manual">
                        @error('barcode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Harus unik di seluruh sistem perpustakaan</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Nomor Induk / No. Inventaris <span class="text-danger">*</span></label>
                        <input type="text" name="accession_number" class="form-control @error('accession_number') is-invalid @enderror" value="{{ old('accession_number', $accessionNumber) }}" required>
                        @error('accession_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Lokasi / Rak Penyimpanan</label>
                        <select name="location_id" class="form-select">
                            <option value="">Pilih Lokasi...</option>
                            @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }} ({{ $loc->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Kondisi Buku</label>
                        <select name="condition" class="form-select">
                            <option value="Baik" {{ old('condition') == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ old('condition') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ old('condition') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-500">Tanggal Perolehan</label>
                        <input type="date" name="acquisition_date" class="form-control" value="{{ old('acquisition_date', date('Y-m-d')) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Harga Perolehan (Rp)</label>
                        <input type="number" name="acquisition_price" class="form-control" value="{{ old('acquisition_price', 0) }}" min="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Sumber Perolehan</label>
                        <select name="acquisition_source" class="form-select">
                            <option value="Beli" {{ old('acquisition_source') == 'Beli' ? 'selected' : '' }}>Pembelian</option>
                            <option value="Hibah" {{ old('acquisition_source') == 'Hibah' ? 'selected' : '' }}>Hibah / Sumbangan</option>
                            <option value="Droping" {{ old('acquisition_source') == 'Droping' ? 'selected' : '' }}>Bantuan / Dropping</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Catatan Khusus</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Keterangan tambahan untuk eksemplar ini...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <hr>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('books.show', $book) }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary fw-600">Simpan Eksemplar</button>
            </div>
        </form>

    </div>
</div>
@endsection
