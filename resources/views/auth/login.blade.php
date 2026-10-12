@extends('layouts.auth')
@section('title', __('Login'))

@section('content')
<div class="w-full max-w-4xl mx-auto bg-white rounded-3xl shadow-2xl border border-[#dee3e9] overflow-hidden flex flex-col md:flex-row min-h-[540px]">
    
    {{-- Left side: Meta Dark Brand Showcase (Hidden on mobile) --}}
    <div class="w-full md:w-1/2 bg-[#0a1317] text-white relative overflow-hidden flex-col items-center justify-center p-8 hidden md:flex border-r border-[#dee3e9]/20">
        {{-- Subtle Blue Ambient Glow --}}
        <div class="absolute -top-20 -left-20 w-72 h-72 bg-[#0064e0]/25 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-[#0457cb]/25 rounded-full blur-3xl pointer-events-none"></div>

        {{-- Content --}}
        <div class="z-10 relative flex flex-col items-center w-full text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white p-2 mb-6 shadow-xl">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded-xl mix-blend-multiply">
            </div>
            
            <img src="{{ asset('images/library-illustration.png') }}" alt="Library Illustration" class="w-full max-w-[240px] mx-auto rounded-2xl shadow-xl border border-white/10 mb-6 object-cover bg-white">
            
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-white leading-tight">{{ \App\Models\Setting::get('library_name', config('app.name', 'Makarya')) }}</h1>
                <p class="text-[#ced0d4] text-xs mt-1.5 font-medium">{{ __('Sistem Informasi & Katalog Perpustakaan') }}</p>
            </div>
        </div>
    </div>

    {{-- Right side: Login Form --}}
    <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-white relative z-10">
        
        {{-- Mobile Header (Visible only on small screens) --}}
        <div class="md:hidden flex flex-col items-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-white p-1 mb-3 shadow border border-[#dee3e9]">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded-xl">
            </div>
            <h1 class="text-lg font-extrabold text-[#0a1317]">{{ \App\Models\Setting::get('library_name', config('app.name', 'Makarya')) }}</h1>
            <p class="text-[#5d6c7b] text-xs mt-0.5">{{ __('Sistem Informasi Perpustakaan') }}</p>
        </div>

        <div class="text-center mb-6">
            <span class="text-xs font-bold text-[#0064e0] uppercase tracking-wider">Autentikasi Staf & Admin</span>
            <h2 class="font-extrabold text-[#0a1317] mt-1 text-xl md:text-2xl tracking-tight">{{ __('Masuk ke Portal') }}</h2>
            <p class="text-[#5d6c7b] text-xs mt-1">{{ __('Gunakan akun terdaftar Anda untuk melanjutkan.') }}</p>
        </div>

        @if(session('status'))
        <div class="flex items-center gap-2 bg-[#31a24c]/10 border border-[#31a24c]/30 text-[#31a24c] rounded-xl text-xs mb-5 py-2.5 px-4 font-semibold">
            <i class="bi bi-check-circle-fill flex-shrink-0 text-base"></i>
            {{ session('status') }}
        </div>
        @endif

        @if($errors->any())
        <div class="flex items-center gap-2 bg-[#e41e3f]/10 border border-[#e41e3f]/30 text-[#e41e3f] rounded-xl text-xs mb-5 py-2.5 px-4 font-semibold">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0 text-base"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-bold text-[#1c1e21] mb-1.5 uppercase tracking-wide">{{ __('Username / Email') }}</label>
                <div class="flex w-full rounded-xl overflow-hidden border border-[#ced0d4] focus-within:border-[#0064e0] focus-within:ring-2 focus-within:ring-[#0064e0]/20 transition-all shadow-sm bg-white">
                    <span class="flex items-center px-3.5 bg-[#f1f4f7] border-r border-[#ced0d4] text-[#5d6c7b]"><i class="bi bi-person text-base"></i></span>
                    <input
                        type="text"
                        name="login"
                        id="login"
                        class="flex-1 bg-white text-sm outline-none py-2.5 px-3.5 text-[#1c1e21] font-medium"
                        placeholder="{{ __('Username atau email') }}"
                        value="{{ old('login') }}"
                        required
                        autofocus
                    >
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-[#1c1e21] mb-1.5 uppercase tracking-wide">{{ __('Password') }}</label>
                <div class="flex w-full rounded-xl overflow-hidden border border-[#ced0d4] focus-within:border-[#0064e0] focus-within:ring-2 focus-within:ring-[#0064e0]/20 transition-all shadow-sm bg-white">
                    <span class="flex items-center px-3.5 bg-[#f1f4f7] border-r border-[#ced0d4] text-[#5d6c7b]"><i class="bi bi-lock text-base"></i></span>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="flex-1 bg-white text-sm outline-none py-2.5 px-3.5 text-[#1c1e21] font-medium"
                        placeholder="{{ __('Masukkan password') }}"
                        required
                    >
                    <button type="button" class="flex items-center px-3.5 bg-[#f1f4f7] border-l border-[#ced0d4] text-[#5d6c7b] hover:text-[#0064e0] transition-colors cursor-pointer" id="togglePassword">
                        <i class="bi bi-eye text-base" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2 cursor-pointer group">
                    <input class="w-4 h-4 text-[#0064e0] border-[#ced0d4] rounded focus:ring-[#0064e0] cursor-pointer" type="checkbox" name="remember" id="remember">
                    <label class="text-xs text-[#5d6c7b] font-medium cursor-pointer group-hover:text-[#1c1e21] transition-colors" for="remember">{{ __('Ingat saya') }}</label>
                </div>
                <a href="{{ route('password.request') }}" class="text-xs text-[#0064e0] font-bold hover:underline transition-colors no-underline">{{ __('Lupa password?') }}</a>
            </div>

            {{-- Meta Cobalt CTA Button --}}
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-[#0064e0] hover:bg-[#0457cb] active:bg-[#0344a1] text-white font-bold text-xs uppercase tracking-wider rounded-full py-3.5 shadow-md shadow-blue-500/20 transition-all cursor-pointer" id="loginBtn">
                <i class="bi bi-box-arrow-in-right text-base"></i> {{ __('Masuk ke Dashboard') }}
            </button>
        </form>

        <div class="text-center mt-6 pt-5 border-t border-[#dee3e9]">
            @php
                $backUrl = url()->previous();
                if (
                    $backUrl == url()->current() || 
                    !str_contains($backUrl, url('/')) ||
                    str_contains($backUrl, '/password')
                ) {
                    $backUrl = route('opac.katalog');
                }
            @endphp
            <a href="{{ $backUrl }}" class="inline-flex items-center gap-1.5 text-xs text-[#5d6c7b] hover:text-[#0064e0] font-bold transition-colors no-underline">
                <i class="bi bi-arrow-left"></i> {{ __('Kembali ke Beranda') }}
            </a>
        </div>
    </div>
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
        icon.className = 'bi bi-eye-slash text-lg text-indigo-600';
    } else {
        pwd.type = 'password';
        icon.className = 'bi bi-eye text-lg text-slate-500';
    }
});

document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = '<i class="bi bi-hourglass-split text-lg mr-2 animate-spin"></i>{{ __('Memproses...') }}';
    btn.disabled = true;
    btn.classList.add('opacity-75', 'cursor-not-allowed');
});
</script>
@endpush
@endsection

