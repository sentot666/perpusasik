@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil')

@section('breadcrumb')
<li class="breadcrumb-item active">Profil</li>
@endsection

@section('content')
<div class="page-header">
    <h1>Pengaturan Akun</h1>
    <p>Perbarui data profil dan password akun Anda</p>
</div>

<div class="row g-3">
    {{-- Edit Profile --}}
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-person-fill-gear me-2 text-primary"></i>Informasi Profil</div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-500">Username</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->username }}" readonly>
                        <div class="form-text">Username tidak dapat diubah</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Alamat Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary fw-600 w-100">Simpan Profil</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Password --}}
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-shield-lock-fill me-2 text-danger"></i>Ganti Password</div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-500">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required placeholder="Masukkan password sekarang">
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Password Baru</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Min. 8 karakter">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" required placeholder="Ulangi password baru">
                    </div>

                    <button type="submit" class="btn btn-danger fw-600 w-100">Ubah Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
