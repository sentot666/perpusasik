@extends('layouts.auth')
@section('title', __('Login Anggota'))

@section('content')
<div class="w-full max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[550px]">
    
    {{-- Left side: Image and Branding for Member Portal --}}
    <div class="w-full md:w-1/2 bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 relative overflow-hidden flex-col items-center justify-center p-8 hidden md:flex">
        
        {{-- Animated background blob elements --}}
        <div class="absolute inset-0 z-0 opacity-30">
            <div class="absolute w-56 h-56 bg-emerald-300 rounded-full mix-blend-overlay filter blur-2xl opacity-70 animate-blob top-5 left-5"></div>
            <div class="absolute w-56 h-56 bg-teal-300 rounded-full mix-blend-overlay filter blur-2xl opacity-70 animate-blob animation-delay-2000 bottom-10 right-5"></div>
            <div class="absolute w-48 h-48 bg-cyan-300 rounded-full mix-blend-overlay filter blur-2xl opacity-70 animate-blob animation-delay-4000 top-1/2 left-1/2 -translate-x-1/2"></div>
        </div>

        {{-- Content --}}
        <div class="z-10 relative flex flex-col items-center text-center w-full">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white/95 p-2 mb-6 shadow-xl hover:scale-105 transition-transform duration-300 backdrop-blur">
                <i class="bi bi-person-vcard text-4xl text-emerald-600"></i>
            </div>
            
            <div class="w-24 h-1 bg-white/30 rounded-full mb-6"></div>
            
            <h1 class="text-2xl lg:text-3xl font-black leading-tight text-white tracking-tight">Portal Anggota</h1>
            <p class="text-emerald-100 text-sm mt-3 font-medium max-w-xs leading-relaxed">
                Akses layanan peminjaman buku, katalog favorit, dan informasi keanggotaan Anda.
            </p>

            <div class="mt-8 flex items-center gap-3 bg-white/10 backdrop-blur-md px-5 py-3 rounded-xl border border-white/20 text-xs text-white">
                <i class="bi bi-shield-check text-emerald-300 text-base"></i>
                <span>Masuk dengan Email / Username terdaftar</span>
            </div>
        </div>
    </div>

    {{-- Right side: Login Form --}}
    <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-white relative z-10">
        
        {{-- Mobile Header --}}
        <div class="md:hidden flex flex-col items-center mb-6 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-50 p-3 mb-3 text-emerald-600 shadow-sm border border-emerald-100">
                <i class="bi bi-person-vcard text-3xl"></i>
            </div>
            <h1 class="text-xl font-extrabold text-slate-800">Portal Anggota</h1>
            <p class="text-slate-500 text-xs mt-1">{{ __('Sistem Informasi Perpustakaan') }}</p>
        </div>

        <div class="mb-8">
            <h5 class="font-extrabold text-slate-800 mb-1.5 text-2xl tracking-tight">Login Anggota 👋</h5>
            <p class="text-slate-500 text-sm">Masukkan kredensial akun anggota Anda untuk melanjutkan.</p>
        </div>

        @if(session('status'))
        <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs mb-6 py-3 px-4">
            <i class="bi bi-check-circle-fill flex-shrink-0 text-base"></i>
            {{ session('status') }}
        </div>
        @endif

        @if($errors->any())
        <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs mb-6 py-3 px-4">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0 text-base"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="memberLoginForm">
            @csrf
            <div class="mb-5">
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">{{ __('Nama Lengkap / NIS / Username') }}</label>
                <div class="flex w-full rounded-xl overflow-hidden border border-slate-300 focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-200 transition-all shadow-sm">
                    <span class="flex items-center px-4 bg-slate-50 border-r border-slate-200 text-slate-400"><i class="bi bi-person text-lg"></i></span>
                    <input
                        type="text"
                        name="login"
                        id="login"
                        class="flex-1 bg-white text-sm outline-none py-3 px-3 text-slate-800 font-medium"
                        placeholder="{{ __('Masukkan nama lengkap atau NIS') }}"
                        value="{{ old('login') }}"
                        required
                        autofocus
                    >
                </div>
            </div>

            <div class="mb-5">
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('Password (Tanggal Lahir)') }}</label>
                    <span class="text-[11px] text-emerald-600 font-semibold">Format: YYYY-MM-DD</span>
                </div>
                <div class="flex w-full rounded-xl overflow-hidden border border-slate-300 focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-200 transition-all shadow-sm">
                    <span class="flex items-center px-4 bg-slate-50 border-r border-slate-200 text-slate-400"><i class="bi bi-lock text-lg"></i></span>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="flex-1 bg-white text-sm outline-none py-3 px-3 text-slate-800 font-medium"
                        placeholder="{{ __('Contoh: 2012-08-17') }}"
                        required
                    >
                    <button type="button" class="flex items-center px-4 bg-slate-50 border-l border-slate-200 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors" id="togglePassword">
                        <i class="bi bi-eye text-lg" id="eyeIcon"></i>
                    </button>
                </div>
                <p class="text-[11px] text-slate-400 mt-1.5">Masukkan tanggal lahir Anda sesuai format (Tahun-Bulan-Hari, contoh: <code>2012-08-17</code>).</p>
            </div>

            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-2 cursor-pointer group">
                    <input class="w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500 cursor-pointer" type="checkbox" name="remember" id="remember">
                    <label class="text-sm text-slate-600 font-medium cursor-pointer group-hover:text-slate-800 transition-colors select-none" for="remember">{{ __('Ingat saya') }}</label>
                </div>
                <a href="{{ route('password.request') }}" class="text-sm text-emerald-600 font-semibold hover:text-emerald-800 transition-colors">{{ __('Lupa password?') }}</a>
            </div>

            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold text-sm rounded-xl py-3.5 shadow-lg shadow-emerald-200 hover:shadow-emerald-300 transition-all transform hover:-translate-y-0.5" id="loginBtn">
                <i class="bi bi-box-arrow-in-right text-lg"></i> {{ __('Masuk ke Portal Anggota') }}
            </button>
        </form>

        <div class="text-center mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
            <a href="{{ route('opac.index') }}" class="inline-flex items-center text-slate-500 hover:text-emerald-600 font-semibold transition-colors text-xs">
                <i class="bi bi-arrow-left mr-1.5"></i>{{ __('Kembali ke Beranda') }}
            </a>
            <a href="{{ route('login') }}" class="inline-flex items-center text-slate-400 hover:text-slate-600 transition-colors text-xs">
                {{ __('Login Petugas') }} <i class="bi bi-chevron-right ml-1"></i>
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
</style>
@endpush

@push('scripts')
<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.className = 'bi bi-eye-slash text-lg text-emerald-600';
    } else {
        pwd.type = 'password';
        icon.className = 'bi bi-eye text-lg text-slate-400';
    }
});

document.getElementById('memberLoginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = '<i class="bi bi-hourglass-split text-lg mr-2 animate-spin"></i>{{ __('Memproses...') }}';
    btn.disabled = true;
    btn.classList.add('opacity-75', 'cursor-not-allowed');
});
</script>
@endpush
@endsection
