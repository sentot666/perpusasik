<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'OPAC - Online Public Access Catalog Perpustakaan')">
    <title>@hasSection('title')@yield('title') - @endif{{ \App\Models\Setting::get('library_name', config('app.name', 'Makarya')) }} OPAC</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-blue-50" style="font-family: 'Poppins', sans-serif;">

{{-- OPAC Navbar --}}
<nav x-data="{ mobileMenuOpen: false }" class="bg-white shadow-sm py-4 sticky top-0 z-50">
    <div class="w-full mx-auto flex flex-row items-center justify-between px-6 relative">
        
        <!-- Left: Logo -->
        <a class="flex flex-row items-center gap-4 text-slate-800 no-underline group" href="{{ route('opac.index') }}">
            <div style="width:80px;height:80px;display:flex;align-items:center;justify-content:center;" class="group-hover:scale-105 transition-transform duration-300">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" style="width:100%;height:100%;object-fit:contain;mix-blend-mode:multiply;">
            </div>
            <div class="flex flex-col text-left hidden sm:flex">
                <span class="text-xl md:text-2xl font-black text-slate-800 tracking-tight leading-none group-hover:text-indigo-600 transition-colors">{{ __('landing.school_library') }}</span>
                <span class="text-xs md:text-sm font-bold text-slate-500 uppercase tracking-widest mt-1">{{ __('landing.catholic_school') }}</span>
            </div>
        </a>

        <!-- Right: Links & Buttons (Desktop) -->
        <div class="hidden lg:flex items-center">
            <ul class="flex items-center gap-8 list-none pl-0 mb-0">
                @if(request()->routeIs('opac.katalog') || request()->routeIs('opac.show'))
                    <li><a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-600 transition-colors {{ request('tab', 'home') == 'home' && request()->routeIs('opac.katalog') ? 'text-indigo-600' : 'text-slate-600' }} no-underline" href="{{ route('opac.katalog', ['tab' => 'home']) }}">Home</a></li>
                    <li><a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-600 transition-colors {{ request('tab') == 'koleksi' ? 'text-indigo-600' : 'text-slate-600' }} no-underline" href="{{ route('opac.katalog', ['tab' => 'koleksi']) }}">Koleksi Buku</a></li>
                    <li><a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-600 transition-colors {{ request('tab') == 'digital' ? 'text-indigo-600' : 'text-slate-600' }} no-underline" href="{{ route('opac.katalog', ['tab' => 'digital']) }}">Buku Digital</a></li>
                @else
                    <li><a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-600 transition-colors text-slate-600 no-underline" href="{{ route('opac.index') }}#beranda">{{ __('landing.nav_home') }}</a></li>
                    <li><a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-600 transition-colors text-slate-600 no-underline" href="{{ route('opac.index') }}#layanan">{{ __('landing.nav_services') }}</a></li>
                    <li><a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-600 transition-colors text-slate-600 no-underline" href="{{ route('opac.index') }}#informasi">{{ __('landing.nav_stats') }}</a></li>
                    <li class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="text-sm font-bold uppercase tracking-wider hover:text-indigo-600 transition-colors text-slate-600 no-underline flex items-center gap-1 focus:outline-none">
                            Tentang Kami <i class="bi bi-chevron-down text-xs"></i>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute top-full left-0 mt-2 w-56 bg-white border border-slate-100 rounded-xl shadow-xl py-2 z-50" style="display: none;">
                            <a href="{{ route('opac.sejarah') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">Sejarah</a>
                            <a href="{{ route('opac.visi-misi') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">Visi dan Misi</a>
                            <a href="{{ route('opac.struktur-organisasi') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">Struktur Organisasi</a>
                            <a href="{{ route('opac.pustakawan') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">Pustakawan</a>
                            <a href="{{ route('opac.program-kerja') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">Program Kerja</a>
                            <a href="{{ route('opac.tata-tertib') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">Tata Tertib</a>
                            <a href="{{ route('opac.jam-layanan') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">Jam Layanan</a>
                        </div>
                    </li>
                @endif
                
                <!-- Language Switcher -->
                <li class="ml-2 flex items-center gap-2 border-l border-slate-200 pl-4">
                    <a href="{{ route('lang.switch', 'id') }}" class="text-sm font-bold {{ app()->getLocale() == 'id' ? 'text-indigo-600' : 'text-slate-400 hover:text-indigo-600' }} transition-colors no-underline">ID</a>
                    <span class="text-slate-300">|</span>
                    <a href="{{ route('lang.switch', 'en') }}" class="text-sm font-bold {{ app()->getLocale() == 'en' ? 'text-indigo-600' : 'text-slate-400 hover:text-indigo-600' }} transition-colors no-underline">EN</a>
                </li>
                
                <li class="ml-2">
                    @auth
                    <li class="relative ml-2" x-data="{ userMenu: false }">
                        <button @click="userMenu = !userMenu" @click.away="userMenu = false" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold text-sm uppercase tracking-wider py-2.5 px-6 rounded-full shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 no-underline flex items-center gap-2">
                            <i class="bi bi-person-circle text-lg"></i> {{ Auth::user()->name }} <i class="bi bi-chevron-down text-xs ml-1"></i>
                        </button>
                        <div x-show="userMenu" style="display: none;" class="absolute right-0 mt-2 w-56 bg-white border border-slate-100 rounded-xl shadow-xl py-2 z-50">
                            @if(Auth::user()->can('view-dashboard'))
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline"><i class="bi bi-speedometer2 mr-2"></i>Dashboard Admin</a>
                            @endif
                            <a href="{{ route('member.reservations.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline"><i class="bi bi-journal-bookmark mr-2"></i>Buku Saya (Reservasi)</a>
                            <hr class="my-2 border-slate-100">
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 no-underline"><i class="bi bi-box-arrow-right mr-2"></i>Logout</button>
                            </form>
                        </div>
                    </li>
                    @else
                    <a href="{{ route('login') }}" class="bg-gradient-to-r from-sky-400 to-blue-600 hover:from-sky-500 hover:to-blue-700 text-white font-bold text-sm uppercase tracking-wider py-2.5 px-6 rounded-full shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 no-underline flex items-center gap-2">
                        <i class="bi bi-box-arrow-in-right text-lg"></i> Login
                    </a>
                    @endauth
                </li>
            </ul>
        </div>
        
        <!-- Mobile menu button -->
        <div class="lg:hidden flex items-center">
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-slate-600 hover:text-indigo-600 focus:outline-none">
                <i class="bi bi-list text-3xl"></i>
            </button>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" class="lg:hidden bg-white border-t border-slate-100 mt-4" style="display: none;">
        <ul class="flex flex-col list-none pl-0 mb-0 py-4 px-4 space-y-4 text-center">
            @if(request()->routeIs('opac.katalog') || request()->routeIs('opac.show'))
                <li><a class="block font-bold {{ request('tab', 'home') == 'home' && request()->routeIs('opac.katalog') ? 'text-indigo-600' : 'text-slate-600' }} hover:text-indigo-600 no-underline" href="{{ route('opac.katalog', ['tab' => 'home']) }}">Home</a></li>
                <li><a class="block font-bold {{ request('tab') == 'koleksi' ? 'text-indigo-600' : 'text-slate-600' }} hover:text-indigo-600 no-underline" href="{{ route('opac.katalog', ['tab' => 'koleksi']) }}">Koleksi Buku</a></li>
                <li><a class="block font-bold {{ request('tab') == 'digital' ? 'text-indigo-600' : 'text-slate-600' }} hover:text-indigo-600 no-underline" href="{{ route('opac.katalog', ['tab' => 'digital']) }}">Buku Digital</a></li>
            @else
                <li><a class="block font-bold text-slate-600 hover:text-indigo-600 no-underline" href="{{ route('opac.index') }}#beranda">{{ __('landing.nav_home') }}</a></li>
                <li><a class="block font-bold text-slate-600 hover:text-indigo-600 no-underline" href="{{ route('opac.index') }}#layanan">{{ __('landing.nav_services') }}</a></li>
                <li><a class="block font-bold text-slate-600 hover:text-indigo-600 no-underline" href="{{ route('opac.index') }}#informasi">{{ __('landing.nav_stats') }}</a></li>
                <li x-data="{ open: false }" class="flex flex-col items-center w-full">
                    <button @click="open = !open" class="font-bold text-slate-600 hover:text-indigo-600 focus:outline-none flex items-center gap-1 justify-center">
                        Tentang <i class="bi bi-chevron-down text-sm transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <ul x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="flex flex-col list-none pl-0 mt-3 space-y-3 text-center bg-slate-50 w-full py-4 rounded-xl shadow-inner" style="display: none;">
                        <li><a href="{{ route('opac.sejarah') }}" class="block text-sm font-medium text-slate-600 hover:text-indigo-600 no-underline">Sejarah</a></li>
                        <li><a href="{{ route('opac.visi-misi') }}" class="block text-sm font-medium text-slate-600 hover:text-indigo-600 no-underline">Visi dan Misi</a></li>
                        <li><a href="{{ route('opac.struktur-organisasi') }}" class="block text-sm font-medium text-slate-600 hover:text-indigo-600 no-underline">Struktur Organisasi</a></li>
                        <li><a href="{{ route('opac.pustakawan') }}" class="block text-sm font-medium text-slate-600 hover:text-indigo-600 no-underline">Pustakawan</a></li>
                        <li><a href="{{ route('opac.program-kerja') }}" class="block text-sm font-medium text-slate-600 hover:text-indigo-600 no-underline">Program Kerja</a></li>
                        <li><a href="{{ route('opac.tata-tertib') }}" class="block text-sm font-medium text-slate-600 hover:text-indigo-600 no-underline">Tata Tertib</a></li>
                        <li><a href="{{ route('opac.jam-layanan') }}" class="block text-sm font-medium text-slate-600 hover:text-indigo-600 no-underline">Jam Layanan</a></li>
                    </ul>
                </li>
            @endif
            
            <li class="flex justify-center gap-4 py-2">
                <a href="{{ route('lang.switch', 'id') }}" class="font-bold {{ app()->getLocale() == 'id' ? 'text-indigo-600' : 'text-slate-400 hover:text-indigo-600' }} no-underline">ID</a>
                <span class="text-slate-300">|</span>
                <a href="{{ route('lang.switch', 'en') }}" class="font-bold {{ app()->getLocale() == 'en' ? 'text-indigo-600' : 'text-slate-400 hover:text-indigo-600' }} no-underline">EN</a>
            </li>
            <li class="pt-4 border-t border-slate-100">
                @auth
                <a href="{{ route('dashboard') }}" class="inline-block bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold py-2 px-6 rounded-lg no-underline text-center w-full shadow-md">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="inline-block bg-gradient-to-r from-sky-400 to-blue-600 text-white font-bold py-2 px-6 rounded-lg no-underline text-center w-full shadow-md">Login</a>
                @endauth
            </li>
        </ul>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer id="tentang" class="bg-[#1e3a5f] text-white/70 pt-16 pb-8 mt-16 scroll-mt-28">
    <div class="w-full mx-auto px-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 mb-12">
            <!-- Tentang -->
            <div class="lg:col-span-2">
                <div class="flex items-center gap-4 mb-6">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-12 h-12 object-contain rounded bg-white p-1">
                    <span class="font-bold text-white text-lg uppercase tracking-wider">{{ \App\Models\Setting::get('library_name', __('landing.school_library_full')) }}</span>
                </div>
                <p class="leading-relaxed mb-6 max-w-2xl text-sm">
                    {{ __('landing.about_desc') }}
                </p>
            </div>

            <!-- Informasi -->
            <div>
                <h3 class="text-white text-lg font-bold mb-6 uppercase tracking-wider">{{ __('landing.information') }}</h3>
                <ul class="space-y-4 text-sm list-none pl-0 m-0">
                    <li class="flex items-start gap-3">
                        <i class="bi bi-geo-alt text-lg text-amber-400 mt-0.5"></i>
                        <span>{{ \App\Models\Setting::get('library_address', 'Jl. Danau Agung 13 Blok E19, Sunter Agung Podomoro') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="bi bi-telephone text-lg text-amber-400"></i>
                        <span>{{ \App\Models\Setting::get('library_phone', '021-6459109') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="bi bi-envelope text-lg text-amber-400"></i>
                        <span>{{ \App\Models\Setting::get('library_email', 'yayasan@santopaulus.school') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="bi bi-instagram text-lg text-amber-400"></i>
                        <span>@perpustakaan_santopaulus</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="bi bi-clock-history text-lg text-amber-400 mt-0.5"></i>
                        <span>
                            <strong>{{ __('landing.operational_hours') }}</strong><br>
                            {{ __('landing.weekday_hours') }}<br>
                            {{ __('landing.weekend_hours') }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs">
            <div>
                &copy; {{ date('Y') }} {{ \App\Models\Setting::get('library_name', config('app.name', 'Makarya')) }} — {{ __('landing.system_name') }}. All rights reserved.
            </div>
            <div class="flex items-center gap-4">
                <a href="#" class="text-white/50 hover:text-white transition-colors no-underline">{{ __('landing.privacy_policy') }}</a>
                <a href="#" class="text-white/50 hover:text-white transition-colors no-underline">{{ __('landing.help') }}</a>
            </div>
        </div>
    </div>
</footer>

@stack('scripts')

    <x-toast />
</body>
</html>
