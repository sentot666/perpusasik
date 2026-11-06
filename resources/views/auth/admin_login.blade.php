@extends('layouts.auth')
@section('title', __('Login Administrator & Petugas'))

@push('styles')
<style>
    body {
        background: radial-gradient(circle at 50% 20%, #1e293b 0%, #0f172a 60%, #020617 100%) !important;
    }
</style>
@endpush

@section('content')
<div class="w-full max-w-md mx-auto">
    {{-- Card Container --}}
    <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-8 sm:p-10 shadow-2xl backdrop-blur-xl relative overflow-hidden">
        
        {{-- Subtle top glow accent --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-48 h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent"></div>
        <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-48 h-48 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>

        {{-- Header Section --}}
        <div class="text-center relative z-10 mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white p-2 mb-4 shadow-xl border border-slate-700/50">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded-xl">
            </div>

            <div class="flex items-center justify-center mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                    <i class="bi bi-shield-lock-fill"></i> RESTRICTED ACCESS
                </span>
            </div>

            <h1 class="text-2xl font-black text-white tracking-tight">{{ __('Konsol Administrator') }}</h1>
            <p class="text-xs text-slate-400 mt-1.5 leading-relaxed">
                {{ __('Portal autentikasi khusus Administrator & Petugas') }} {{ \App\Models\Setting::get('library_name', config('app.name', 'Makarya')) }}
            </p>
        </div>

        {{-- Status / Error Alerts --}}
        @if(session('status'))
        <div class="flex items-center gap-2.5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-xl text-xs mb-6 p-3.5">
            <i class="bi bi-check-circle-fill text-base flex-shrink-0"></i>
            <span>{{ session('status') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="flex items-center gap-2.5 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-xl text-xs mb-6 p-3.5">
            <i class="bi bi-x-circle-fill text-base flex-shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div class="flex items-start gap-2.5 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-xl text-xs mb-6 p-3.5">
            <i class="bi bi-exclamation-triangle-fill text-base flex-shrink-0 mt-0.5"></i>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('admin.login.submit') }}" id="adminLoginForm" class="relative z-10 space-y-5">
            @csrf
            
            {{-- Username / Email --}}
            <div>
                <label for="adminLogin" class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase tracking-wider">
                    {{ __('Username / Email Petugas') }}
                </label>
                <div class="flex w-full rounded-xl overflow-hidden border border-slate-700 bg-slate-950/70 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/30 transition-all shadow-inner">
                    <span class="flex items-center px-4 text-slate-400 bg-slate-900/50 border-r border-slate-800">
                        <i class="bi bi-person-badge text-base"></i>
                    </span>
                    <input
                        type="text"
                        name="login"
                        id="adminLogin"
                        class="flex-1 bg-transparent text-sm text-slate-100 placeholder-slate-500 outline-none py-3 px-3.5 font-medium"
                        placeholder="{{ __('Username atau email admin') }}"
                        value="{{ old('login') }}"
                        required
                        autofocus
                    >
                </div>
            </div>

            {{-- Password --}}
            <div>
                <label for="adminPassword" class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase tracking-wider">
                    {{ __('Password Administrator') }}
                </label>
                <div class="flex w-full rounded-xl overflow-hidden border border-slate-700 bg-slate-950/70 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500/30 transition-all shadow-inner">
                    <span class="flex items-center px-4 text-slate-400 bg-slate-900/50 border-r border-slate-800">
                        <i class="bi bi-key-fill text-base"></i>
                    </span>
                    <input
                        type="password"
                        name="password"
                        id="adminPassword"
                        class="flex-1 bg-transparent text-sm text-slate-100 placeholder-slate-500 outline-none py-3 px-3.5 font-medium"
                        placeholder="••••••••"
                        required
                    >
                    <button type="button" class="flex items-center px-4 text-slate-400 hover:text-indigo-400 transition-colors focus:outline-none cursor-pointer" id="toggleAdminPassword" title="{{ __('Lihat/Sembunyikan Password') }}">
                        <i class="bi bi-eye text-base" id="adminEyeIcon"></i>
                    </button>
                </div>
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded bg-slate-950 border-slate-700 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-900 cursor-pointer">
                    <span class="text-xs text-slate-400 font-medium hover:text-slate-300 transition-colors">{{ __('Ingat sesi ini') }}</span>
                </label>
            </div>

            {{-- Submit Button --}}
            <button type="submit" id="adminLoginBtn" class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 via-indigo-700 to-blue-700 hover:from-indigo-500 hover:to-blue-600 text-white font-bold text-sm rounded-xl py-3.5 shadow-lg shadow-indigo-600/30 transition-all transform hover:-translate-y-0.5 cursor-pointer">
                <i class="bi bi-shield-check text-lg"></i> {{ __('Masuk ke Dashboard Admin') }}
            </button>
        </form>

        {{-- Security Notice Footer --}}
        <div class="mt-8 pt-6 border-t border-slate-800/80 text-center text-[11px] text-slate-500 leading-relaxed">
            <i class="bi bi-shield-shaded text-slate-400 mr-1"></i>
            {{ __('Seluruh aktivitas login dipantau dan tercatat dalam audit log sistem demi keamanan data perpustakaan.') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('toggleAdminPassword').addEventListener('click', function() {
        const pwd = document.getElementById('adminPassword');
        const icon = document.getElementById('adminEyeIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.className = 'bi bi-eye-slash text-base text-indigo-400';
        } else {
            pwd.type = 'password';
            icon.className = 'bi bi-eye text-base text-slate-400';
        }
    });

    document.getElementById('adminLoginForm').addEventListener('submit', function() {
        const btn = document.getElementById('adminLoginBtn');
        btn.innerHTML = '<i class="bi bi-hourglass-split text-lg mr-2 animate-spin"></i>{{ __('Memproses Autentikasi...') }}';
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
    });
</script>
@endpush
