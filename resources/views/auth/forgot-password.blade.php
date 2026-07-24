@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
<div class="w-full max-w-[420px] mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden">
    {{-- Header --}}
    <div class="bg-gradient-to-br from-[#2b6cb0] to-[#1e3a5f] px-8 py-10 text-center text-white">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white p-1 mb-4 shadow-lg">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded-xl">
        </div>
        <h1 class="text-2xl font-extrabold leading-tight">Lupa Password</h1>
        <p class="text-white/70 text-xs mt-1">Sistem Informasi Perpustakaan</p>
    </div>

    <div class="px-8 py-8">
        <p class="text-center text-slate-500 text-sm mb-6">Masukkan alamat email Anda untuk menerima tautan reset password.</p>

        @if(session('status'))
        <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-xs mb-6 py-2 px-4">
            <i class="bi bi-check-circle-fill flex-shrink-0"></i>
            {{ session('status') }}
        </div>
        @endif

        @if($errors->any())
        <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 rounded-lg text-xs mb-6 py-2 px-4">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" id="forgotForm">
            @csrf
            <div class="mb-6">
                <label class="block text-xs font-medium text-slate-700 mb-1">Email</label>
                <div class="flex w-full rounded-lg overflow-hidden border border-slate-300 focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 transition">
                    <span class="flex items-center px-3 bg-slate-50 border-r border-slate-300 text-slate-500"><i class="bi bi-envelope"></i></span>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="flex-1 bg-white text-sm outline-none py-2 px-3"
                        placeholder="contoh@email.com"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                </div>
            </div>

            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-lg py-2.5 transition-colors" id="btnSubmit">
                <i class="bi bi-send"></i> Kirim Tautan Reset
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-slate-500 hover:text-slate-700 transition-colors text-xs">
                <i class="bi bi-arrow-left mr-1"></i>Kembali ke Halaman Login
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('forgotForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnSubmit');
    btn.innerHTML = '<i class="bi bi-hourglass-split mr-2 animate-spin"></i>Memproses...';
    btn.disabled = true;
});
</script>
@endpush
@endsection
