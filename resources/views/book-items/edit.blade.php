@extends('layouts.app')

@section('title', 'Edit Eksemplar: ' . $item->barcode)
@section('page-title', 'Edit Eksemplar')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('books.index') }}">Master Buku</a></li>
<li class="breadcrumb-item"><a href="{{ route('books.show', $item->book) }}">{{ Str::limit($item->book->title, 20) }}</a></li>
<li class="breadcrumb-item active">Edit Eksemplar</li>
@endsection

@section('content')
<div class="page-header">
    <h1>Edit Eksemplar Fisik</h1>
    <p>Perbarui status dan penempatan untuk eksemplar: <code>{{ $item->barcode }}</code></p>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-pencil-square me-2 text-primary"></i>Form Perubahan Eksemplar</div>
    <div class="card-body">

        @if($errors->any())
        <div class="alert alert-danger border-0 py-2 mb-3" style="border-radius:8px">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            Periksa kembali form Anda.
        </div>
        @endif

        <form method="POST" action="{{ route('book-items.update', $item) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-500">Barcode / RFID <span class="text-danger">*</span></label>
                        <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode', $item->barcode) }}" required>
                        @error('barcode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Nomor Induk / No. Inventaris <span class="text-danger">*</span></label>
                        <input type="text" name="accession_number" class="form-control @error('accession_number') is-invalid @enderror" value="{{ old('accession_number', $item->accession_number) }}" required>
                        @error('accession_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Lokasi / Rak Penyimpanan</label>
                        <select name="location_id" class="form-select">
                            <option value="">Pilih Lokasi...</option>
                            @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ old('location_id', $item->location_id) == $loc->id ? 'selected' : '' }}>{{ $loc->name }} ({{ $loc->code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-500">Kondisi Buku</label>
                        <select name="condition" class="form-select">
                            <option value="Baik" {{ old('condition', $item->condition) == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak" {{ old('condition', $item->condition) == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                            <option value="Hilang" {{ old('condition', $item->condition) == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Status Ketersediaan <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="Tersedia" {{ old('status', $item->status) == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="Dipesan" {{ old('status', $item->status) == 'Dipesan' ? 'selected' : '' }}>Dipesan</option>
                            <option value="Perbaikan" {{ old('status', $item->status) == 'Perbaikan' ? 'selected' : '' }}>Perbaikan / Perawatan</option>
                            <option value="Hilang" {{ old('status', $item->status) == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                        </select>
                        <div class="form-text">Catatan: Status 'Dipinjam' dikontrol otomatis oleh transaksi sirkulasi</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Catatan Khusus</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes', $item->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <hr>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('books.show', $item->book) }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary fw-600">Simpan Perubahan</button>
            </div>
        </form>

    </div>
</div>
@endsection
