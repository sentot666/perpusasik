@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="login-card card mx-auto">
    <div class="login-header">
        <div class="login-logo">
            <i class="bi bi-book-half"></i>
        </div>
        <h1>INLIS Lite 3</h1>
        <p>Sistem Informasi Perpustakaan</p>
    </div>

    <div class="login-body">
        <h5 class="fw-700 text-center mb-1" style="color:#1a202c">Selamat Datang</h5>
        <p class="text-center text-muted mb-4" style="font-size:0.8rem">Masuk untuk mengelola perpustakaan</p>

        @if($errors->any())
        <div class="alert alert-danger border-0 py-2 px-3 mb-3" style="font-size:0.82rem;border-radius:8px">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-500 text-muted" style="font-size:0.8rem">Username / Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                    <input
                        type="text"
                        name="login"
                        id="login"
                        class="form-control border-start-0 bg-light @error('login') is-invalid @enderror"
                        placeholder="Username atau email"
                        value="{{ old('login') }}"
                        required
                        autofocus
                    >
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-500 text-muted" style="font-size:0.8rem">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control border-start-0 bg-light border-end-0"
                        placeholder="Masukkan password"
                        required
                    >
                    <button type="button" class="input-group-text bg-light" id="togglePassword">
                        <i class="bi bi-eye text-muted" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label text-muted" for="remember" style="font-size:0.8rem">Ingat saya</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-600" id="loginBtn">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('opac.index') }}" class="text-muted text-decoration-none" style="font-size:0.78rem">
                <i class="bi bi-search me-1"></i>Cari Buku (OPAC)
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.className = 'bi bi-eye-slash text-muted';
    } else {
        pwd.type = 'password';
        icon.className = 'bi bi-eye text-muted';
    }
});

document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    btn.disabled = true;
});
</script>
@endpush

@endsection
