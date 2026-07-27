@extends('layouts.auth')
@section('title', __('Reset Password'))

@section('content')
<div class="w-full max-w-[420px] mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden">
    {{-- Header --}}
    <div class="bg-gradient-to-br from-[#2b6cb0] to-[#1e3a5f] px-8 py-10 text-center text-white">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white p-1 mb-4 shadow-lg">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded-xl">
        </div>
        <h1 class="text-2xl font-extrabold leading-tight">{{ __('Buat Password Baru') }}</h1>
        <p class="text-white/70 text-xs mt-1">{{ __('Sistem Informasi Perpustakaan') }}</p>
    </div>

    <div class="px-8 py-8">
        <p class="text-center text-slate-500 text-sm mb-6">{{ __('Silakan masukkan password baru untuk akun Anda.') }}</p>

        @if($errors->any())
        <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 rounded-lg text-xs mb-6 py-2 px-4">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" id="resetForm">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ request('email') ?? old('email') }}">

            <div class="mb-4">
                <label class="block text-xs font-medium text-slate-700 mb-1">{{ __('Password Baru') }}</label>
                <div class="flex w-full rounded-lg overflow-hidden border border-slate-300 focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 transition">
                    <span class="flex items-center px-3 bg-slate-50 border-r border-slate-300 text-slate-500"><i class="bi bi-lock"></i></span>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="flex-1 bg-white text-sm outline-none py-2 px-3"
                        placeholder="{{ __('Minimal 8 karakter') }}"
                        required
                        autofocus
                    >
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-medium text-slate-700 mb-1">{{ __('Konfirmasi Password') }}</label>
                <div class="flex w-full rounded-lg overflow-hidden border border-slate-300 focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 transition">
                    <span class="flex items-center px-3 bg-slate-50 border-r border-slate-300 text-slate-500"><i class="bi bi-shield-check"></i></span>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        class="flex-1 bg-white text-sm outline-none py-2 px-3"
                        placeholder="{{ __('Ulangi password baru') }}"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="w-full flex items-center justify-center gap-2 btn-gradient-blue text-white font-semibold text-sm rounded-lg py-2.5 transition-colors" id="btnSubmit">
                <i class="bi bi-check2-circle"></i> {{ __('Simpan Password Baru') }}
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('resetForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnSubmit');
    btn.innerHTML = '<i class="bi bi-hourglass-split mr-2 animate-spin"></i>{{ __('Menyimpan...') }}';
    btn.disabled = true;
});
</script>
@endpush
@endsection

