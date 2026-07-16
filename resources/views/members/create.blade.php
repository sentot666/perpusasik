@extends('layouts.app')

@section('title', 'Tambah Anggota Baru')
@section('page-title', 'Tambah Anggota')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('members.index') }}">Daftar Anggota</a></li>
<li class="breadcrumb-item active">Tambah Anggota</li>
@endsection

@section('content')
<div class="page-header">
    <h1>Tambah Anggota Baru</h1>
    <p>Daftarkan anggota perpustakaan baru ke sistem</p>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-person-plus-fill me-2 text-primary"></i>Form Data Anggota</div>
    <div class="card-body">

        @if($errors->any())
        <div class="alert alert-danger border-0 py-2 mb-3" style="border-radius:8px">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            Periksa kembali form Anda. Ada beberapa input yang tidak valid.
        </div>
        @endif

        <form method="POST" action="{{ route('members.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                {{-- Left side --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-500">Kode Anggota <span class="text-danger">*</span></label>
                        <input type="text" name="member_code" class="form-control @error('member_code') is-invalid @enderror" value="{{ old('member_code', $memberCode) }}" required>
                        @error('member_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Nama lengkap sesuai identitas">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Tipe Identitas</label>
                            <select name="identity_type" class="form-select">
                                <option value="KTP" {{ old('identity_type') == 'KTP' ? 'selected' : '' }}>KTP</option>
                                <option value="SIM" {{ old('identity_type') == 'SIM' ? 'selected' : '' }}>SIM</option>
                                <option value="Kartu Pelajar" {{ old('identity_type') == 'Kartu Pelajar' ? 'selected' : '' }}>Kartu Pelajar</option>
                                <option value="KTM" {{ old('identity_type') == 'KTM' ? 'selected' : '' }}>KTM (Mahasiswa)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">No. Identitas</label>
                            <input type="text" name="identity_number" class="form-control" value="{{ old('identity_number') }}" placeholder="NIK / NIM / NIS">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Jenis Kelamin</label>
                            <select name="gender" class="form-select">
                                <option value="">Pilih...</option>
                                <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Tipe Anggota <span class="text-danger">*</span></label>
                            <select name="member_type" class="form-select" required>
                                <option value="Umum" {{ old('member_type') == 'Umum' ? 'selected' : '' }}>Umum</option>
                                <option value="Pelajar" {{ old('member_type') == 'Pelajar' ? 'selected' : '' }}>Pelajar</option>
                                <option value="Mahasiswa" {{ old('member_type') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                <option value="Pegawai" {{ old('member_type') == 'Pegawai' ? 'selected' : '' }}>Pegawai</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Foto Anggota</label>
                        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Format: JPG, PNG. Maks 2MB</div>
                    </div>
                </div>

                {{-- Right side --}}
                <div class="col-md-6">
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="contoh@domain.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">No. Telepon</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Alamat Lengkap</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Nama jalan, RT/RW, Dusun...">{{ old('address') }}</textarea>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Kota/Kabupaten</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Provinsi</label>
                            <input type="text" name="province" class="form-control" value="{{ old('province') }}">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Tanggal Daftar</label>
                            <input type="date" name="register_date" class="form-control" value="{{ old('register_date', date('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Tanggal Kedaluwarsa</label>
                            <input type="date" name="expired_date" class="form-control" value="{{ old('expired_date') }}">
                            <div class="form-text">Biarkan kosong untuk otomatis 1 tahun</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Catatan Lain</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Informasi tambahan...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <hr>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary fw-600">Simpan Anggota</button>
            </div>
        </form>

    </div>
</div>
@endsection
