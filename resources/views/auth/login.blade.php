@extends('layouts.auth')
@section('title', __('Login'))

@section('content')
<div class="w-full max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[550px]">
    
    {{-- Left side: Image and Animation (Hidden on mobile) --}}
    <div class="w-full md:w-1/2 bg-gradient-to-br from-[#2b6cb0] to-[#1e3a5f] relative overflow-hidden flex-col items-center justify-center p-8 hidden md:flex">
        
        {{-- Animated background blob elements --}}
        <div class="absolute inset-0 z-0 opacity-40">
            <div class="absolute w-48 h-48 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob top-10 left-10"></div>
            <div class="absolute w-48 h-48 bg-teal-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000 top-20 right-10"></div>
            <div class="absolute w-48 h-48 bg-indigo-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000 bottom-10 left-1/2 -translate-x-1/2"></div>
        </div>

        {{-- Content --}}
        <div class="z-10 relative flex flex-col items-center w-full">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white p-1 mb-6 shadow-xl hover:scale-105 transition-transform duration-300">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded-xl mix-blend-multiply">
            </div>
            
            <img src="{{ asset('images/library-illustration.png') }}" alt="Library Illustration" class="w-full max-w-[260px] mx-auto hover:scale-105 transition-transform duration-500 rounded-2xl shadow-[0_0_20px_rgba(0,0,0,0.3)] border-4 border-white/20 mb-6 object-cover bg-white">
            
            <div class="text-center">
                <h1 class="text-2xl lg:text-3xl font-extrabold leading-tight text-white">{{ \App\Models\Setting::get('library_name', config('app.name', 'Makarya')) }}</h1>
                <p class="text-white/80 text-sm mt-2 font-medium">{{ __('Sistem Informasi Perpustakaan') }}</p>
            </div>
        </div>
    </div>

    {{-- Right side: Login Form --}}
    <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-white relative z-10">
        
        {{-- Mobile Header (Visible only on small screens) --}}
        <div class="md:hidden flex flex-col items-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white p-1 mb-3 shadow border border-slate-100">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded-xl">
            </div>
            <h1 class="text-xl font-extrabold text-slate-800">{{ \App\Models\Setting::get('library_name', config('app.name', 'Makarya')) }}</h1>
            <p class="text-slate-500 text-xs mt-1">{{ __('Sistem Informasi Perpustakaan') }}</p>
        </div>

        <h5 class="font-bold text-center text-slate-800 mb-1 text-xl md:text-2xl">{{ __('Fasilitas Layanan SIP') }}</h5>
        <p class="text-center text-slate-500 text-sm mb-8">{{ __('Sistem Informasi Perpustakaan') }}</p>

        @if(session('status'))
        <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-xs mb-6 py-2 px-4">
            <i class="bi bi-check-circle-fill flex-shrink-0 text-base"></i>
            {{ session('status') }}
        </div>
        @endif

        @if($errors->any())
        <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 rounded-lg text-xs mb-6 py-2 px-4">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0 text-base"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            <div class="mb-5">
                <label class="block text-xs font-semibold text-slate-700 mb-1.5 uppercase tracking-wide">{{ __('Username / Email') }}</label>
                <div class="flex w-full rounded-lg overflow-hidden border border-slate-300 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-200 transition-all shadow-sm">
                    <span class="flex items-center px-3.5 bg-slate-50 border-r border-slate-300 text-slate-500"><i class="bi bi-person text-lg"></i></span>
                    <input
                        type="text"
                        name="login"
                        id="login"
                        class="flex-1 bg-white text-sm outline-none py-2.5 px-3 text-slate-700 font-medium"
                        placeholder="{{ __('Username atau email') }}"
                        value="{{ old('login') }}"
                        required
                        autofocus
                    >
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-semibold text-slate-700 mb-1.5 uppercase tracking-wide">{{ __('Password') }}</label>
                <div class="flex w-full rounded-lg overflow-hidden border border-slate-300 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-200 transition-all shadow-sm">
                    <span class="flex items-center px-3.5 bg-slate-50 border-r border-slate-300 text-slate-500"><i class="bi bi-lock text-lg"></i></span>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="flex-1 bg-white text-sm outline-none py-2.5 px-3 text-slate-700 font-medium"
                        placeholder="{{ __('Masukkan password') }}"
                        required
                    >
                    <button type="button" class="flex items-center px-3.5 bg-slate-50 border-l border-slate-300 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" id="togglePassword">
                        <i class="bi bi-eye text-lg" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-2 cursor-pointer group">
                    <input class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer" type="checkbox" name="remember" id="remember">
                    <label class="text-sm text-slate-600 font-medium cursor-pointer group-hover:text-slate-800 transition-colors" for="remember">{{ __('Ingat saya') }}</label>
                </div>
                <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 font-medium hover:text-indigo-800 transition-colors">{{ __('Lupa password?') }}</a>
            </div>

            <button type="submit" class="w-full flex items-center justify-center gap-2 btn-gradient-blue text-white font-bold text-sm rounded-lg py-3 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5" id="loginBtn">
                <i class="bi bi-box-arrow-in-right text-lg"></i> {{ __('Masuk ke Dashboard') }}
            </button>
        </form>

        <div class="text-center mt-8 pt-6 border-t border-slate-100">
            <a href="javascript:history.back()" class="inline-flex items-center text-slate-500 hover:text-indigo-600 font-medium transition-colors text-sm">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('Kembali') }}
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

