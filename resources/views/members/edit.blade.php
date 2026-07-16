@extends('layouts.app')

@section('title', 'Edit Anggota')
@section('page-title', 'Edit Anggota')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('members.index') }}">Daftar Anggota</a></li>
<li class="breadcrumb-item"><a href="{{ route('members.show', $member) }}">{{ $member->name }}</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <h1>Edit Data Anggota</h1>
    <p>Perbarui informasi detail untuk anggota perpustakaan</p>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-pencil-square me-2 text-primary"></i>Form Edit Anggota</div>
    <div class="card-body">

        @if($errors->any())
        <div class="alert alert-danger border-0 py-2 mb-3" style="border-radius:8px">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            Periksa kembali form Anda. Ada beberapa input yang tidak valid.
        </div>
        @endif

        <form method="POST" action="{{ route('members.update', $member) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                {{-- Left side --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-500">Kode Anggota <span class="text-danger">*</span></label>
                        <input type="text" name="member_code" class="form-control @error('member_code') is-invalid @enderror" value="{{ old('member_code', $member->member_code) }}" required>
                        @error('member_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $member->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Tipe Identitas</label>
                            <select name="identity_type" class="form-select">
                                <option value="KTP" {{ old('identity_type', $member->identity_type) == 'KTP' ? 'selected' : '' }}>KTP</option>
                                <option value="SIM" {{ old('identity_type', $member->identity_type) == 'SIM' ? 'selected' : '' }}>SIM</option>
                                <option value="Kartu Pelajar" {{ old('identity_type', $member->identity_type) == 'Kartu Pelajar' ? 'selected' : '' }}>Kartu Pelajar</option>
                                <option value="KTM" {{ old('identity_type', $member->identity_type) == 'KTM' ? 'selected' : '' }}>KTM (Mahasiswa)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">No. Identitas</label>
                            <input type="text" name="identity_number" class="form-control" value="{{ old('identity_number', $member->identity_number) }}">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Jenis Kelamin</label>
                            <select name="gender" class="form-select">
                                <option value="">Pilih...</option>
                                <option value="L" {{ old('gender', $member->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender', $member->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Tipe Anggota <span class="text-danger">*</span></label>
                            <select name="member_type" class="form-select" required>
                                <option value="Umum" {{ old('member_type', $member->member_type) == 'Umum' ? 'selected' : '' }}>Umum</option>
                                <option value="Pelajar" {{ old('member_type', $member->member_type) == 'Pelajar' ? 'selected' : '' }}>Pelajar</option>
                                <option value="Mahasiswa" {{ old('member_type', $member->member_type) == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                <option value="Pegawai" {{ old('member_type', $member->member_type) == 'Pegawai' ? 'selected' : '' }}>Pegawai</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Foto Anggota</label>
                        @if($member->photo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $member->photo) }}" style="width:60px;height:60px;object-fit:cover;border-radius:8px">
                        </div>
                        @endif
                        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Right side --}}
                <div class="col-md-6">
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $member->email) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">No. Telepon</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $member->phone) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Alamat Lengkap</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $member->address) }}</textarea>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Kota/Kabupaten</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $member->city) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Provinsi</label>
                            <input type="text" name="province" class="form-control" value="{{ old('province', $member->province) }}">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Tanggal Daftar</label>
                            <input type="date" name="register_date" class="form-control" value="{{ old('register_date', $member->register_date ? $member->register_date->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Tanggal Kedaluwarsa</label>
                            <input type="date" name="expired_date" class="form-control" value="{{ old('expired_date', $member->expired_date ? $member->expired_date->format('Y-m-d') : '') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Status Aktif</label>
                        <div class="form-check form-switch mt-2">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActiveSwitch" value="1" {{ old('is_active', $member->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label text-muted" for="isActiveSwitch" style="font-size:0.8rem">Aktifkan Anggota</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('members.show', $member) }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary fw-600">Simpan Perubahan</button>
            </div>
        </form>

    </div>
</div>
@endsection
