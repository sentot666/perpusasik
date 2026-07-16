@extends('layouts.app')

@section('title', 'Tambah Buku Baru')
@section('page-title', 'Tambah Buku')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('books.index') }}">Master Buku</a></li>
<li class="breadcrumb-item active">Tambah Buku</li>
@endsection

@section('content')
<div class="page-header">
    <h1>Tambah Buku Baru</h1>
    <p>Masukkan data bibliografi buku baru ke katalog perpustakaan</p>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-plus-circle-fill me-2 text-primary"></i>Form Data Bibliografi</div>
    <div class="card-body">

        @if($errors->any())
        <div class="alert alert-danger border-0 py-2 mb-3" style="border-radius:8px">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            Periksa kembali form Anda. Ada beberapa input yang tidak valid.
        </div>
        @endif

        <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                {{-- Left Column --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-500">Judul Utama <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="Contoh: Pemrograman Web dengan Laravel">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Sub Judul</label>
                        <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle') }}" placeholder="Anak judul atau penjelasan judul (opsional)">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">ISBN</label>
                            <input type="text" name="isbn" class="form-control" value="{{ old('isbn') }}" placeholder="ISBN 10">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">ISBN 13</label>
                            <input type="text" name="isbn13" class="form-control" value="{{ old('isbn13') }}" placeholder="ISBN 13">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Nomor Panggil</label>
                            <input type="text" name="call_number" class="form-control" value="{{ old('call_number') }}" placeholder="Contoh: 005.3 WID p">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">DDC (Dewey Decimal)</label>
                            <input type="text" name="ddc" class="form-control" value="{{ old('ddc') }}" placeholder="Contoh: 005.3">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-500">Edisi</label>
                            <input type="text" name="edition" class="form-control" value="{{ old('edition') }}" placeholder="Contoh: Cet. 2">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-500">Bahasa</label>
                            <select name="language" class="form-select">
                                <option value="id" {{ old('language') == 'id' ? 'selected' : '' }}>Indonesia (id)</option>
                                <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>Inggris (en)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-500">Jenis Koleksi <span class="text-danger">*</span></label>
                            <select name="collection_type" class="form-select" required>
                                <option value="Buku Teks" {{ old('collection_type') == 'Buku Teks' ? 'selected' : '' }}>Buku Teks</option>
                                <option value="Referensi" {{ old('collection_type') == 'Referensi' ? 'selected' : '' }}>Referensi</option>
                                <option value="Majalah" {{ old('collection_type') == 'Majalah' ? 'selected' : '' }}>Majalah</option>
                                <option value="Kamus" {{ old('collection_type') == 'Kamus' ? 'selected' : '' }}>Kamus</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-500">Pengarang <span class="text-muted">(Pilih beberapa jika ada)</span></label>
                        <select name="authors[]" class="form-select @error('authors') is-invalid @enderror" multiple style="height:115px">
                            @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ is_array(old('authors')) && in_array($author->id, old('authors')) ? 'selected' : '' }}>{{ $author->name }} ({{ $author->type }})</option>
                            @endforeach
                        </select>
                        <div class="form-text">Tahan Ctrl untuk memilih lebih dari satu pengarang</div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Penerbit</label>
                            <select name="publisher_id" class="form-select">
                                <option value="">Pilih Penerbit...</option>
                                @foreach($publishers as $pub)
                                <option value="{{ $pub->id }}" {{ old('publisher_id') == $pub->id ? 'selected' : '' }}>{{ $pub->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Tahun Terbit</label>
                            <input type="text" name="publication_year" class="form-control" value="{{ old('publication_year') }}" placeholder="Contoh: 2023">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Tempat Terbit</label>
                            <input type="text" name="place_of_publication" class="form-control" value="{{ old('place_of_publication') }}" placeholder="Kota/Negara">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Jumlah Halaman</label>
                            <input type="number" name="pages" class="form-control" value="{{ old('pages') }}" min="1">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Subyek / Topik</label>
                        <select name="subjects[]" class="form-select" multiple style="height:78px">
                            @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}" {{ is_array(old('subjects')) && in_array($sub->id, old('subjects')) ? 'selected' : '' }}>{{ $sub->name }} @if($sub->ddc) ({{ $sub->ddc }}) @endif</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Cover Sampul Buku</label>
                        <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror" accept="image/*">
                        @error('cover_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Full width --}}
                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label fw-500">Abstrak / Deskripsi Singkat</label>
                        <textarea name="abstract" class="form-control" rows="3" placeholder="Deskripsi singkat isi buku...">{{ old('abstract') }}</textarea>
                    </div>
                </div>
            </div>

            <hr>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary fw-600">Simpan Buku</button>
            </div>
        </form>

    </div>
</div>
@endsection
