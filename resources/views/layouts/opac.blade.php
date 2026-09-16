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

@php
    $isTransparentNav = View::hasSection('transparent_nav') 
        || request()->routeIs('opac.index') 
        || (request()->routeIs('opac.katalog') && request('tab', 'home') == 'home');
@endphp

{{-- OPAC Navbar --}}
<nav x-data="{ mobileMenuOpen: false, atTop: true }" @scroll.window="atTop = (window.pageYOffset > 50 ? false : true)" 
     :class="{ 
        'bg-white shadow-md py-3': !atTop, 
        'bg-transparent py-5': atTop && {{ $isTransparentNav ? 'true' : 'false' }}, 
        'bg-white shadow-sm py-4': atTop && !{{ $isTransparentNav ? 'true' : 'false' }} 
     }" 
     class="fixed w-full top-0 z-50 transition-all duration-300">
    <div class="w-full mx-auto flex flex-row items-center justify-between px-6 relative">
        
        <!-- Left: Logo -->
        <a class="flex flex-row items-center gap-4 no-underline group" href="{{ route('opac.index') }}">
            <div style="width:80px;height:80px;display:flex;align-items:center;justify-content:center;" class="group-hover:scale-105 transition-transform duration-300 bg-white rounded-xl p-1 shadow-sm">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" style="width:100%;height:100%;object-fit:contain;mix-blend-mode:multiply;">
            </div>
            <div class="flex flex-col text-left hidden sm:flex" :class="{ 'text-white': atTop && {{ $isTransparentNav ? 'true' : 'false' }}, 'text-slate-800': !(atTop && {{ $isTransparentNav ? 'true' : 'false' }}) }">
                <span class="text-xl md:text-2xl font-black tracking-tight leading-none group-hover:text-indigo-400 transition-colors">{{ __('landing.school_library') }}</span>
                <span class="text-xs md:text-sm font-bold uppercase tracking-widest mt-1 opacity-80">{{ __('landing.catholic_school') }}</span>
            </div>
        </a>

        <!-- Right: Links & Buttons (Desktop) -->
        <div class="hidden lg:flex items-center">
            <ul class="flex items-center gap-8 list-none pl-0 mb-0">
                @if(request()->routeIs('opac.katalog'))
                @php $currentTab = request('tab', 'home'); @endphp
                <li>
                    <a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-400 transition-colors no-underline {{ $currentTab == 'home' ? 'text-indigo-400' : '' }}" 
                       :class="{ 'text-white/90': atTop && {{ $isTransparentNav ? 'true' : 'false' }}, 'text-slate-600': !(atTop && {{ $isTransparentNav ? 'true' : 'false' }}) }" 
                       href="{{ route('opac.katalog', ['tab' => 'home']) }}">Home</a>
                </li>
                <li>
                    <a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-400 transition-colors no-underline {{ $currentTab == 'koleksi' ? 'text-indigo-600 font-extrabold' : '' }}" 
                       :class="{ 'text-white/90': atTop && {{ $isTransparentNav ? 'true' : 'false' }}, 'text-slate-600': !(atTop && {{ $isTransparentNav ? 'true' : 'false' }}) }" 
                       href="{{ route('opac.katalog', ['tab' => 'koleksi']) }}">Koleksi Buku</a>
                </li>
                <li>
                    <a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-400 transition-colors no-underline {{ $currentTab == 'digital' ? 'text-indigo-600 font-extrabold' : '' }}" 
                       :class="{ 'text-white/90': atTop && {{ $isTransparentNav ? 'true' : 'false' }}, 'text-slate-600': !(atTop && {{ $isTransparentNav ? 'true' : 'false' }}) }" 
                       href="{{ route('opac.katalog', ['tab' => 'digital']) }}">Buku Digital</a>
                </li>
                @else
                <li><a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-400 transition-colors no-underline" :class="{ 'text-white/90': atTop && {{ $isTransparentNav ? 'true' : 'false' }}, 'text-slate-600': !(atTop && {{ $isTransparentNav ? 'true' : 'false' }}) }" href="{{ route('opac.index') }}">{{ __('landing.nav_home') }}</a></li>
                <li><a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-400 transition-colors no-underline" :class="{ 'text-white/90': atTop && {{ $isTransparentNav ? 'true' : 'false' }}, 'text-slate-600': !(atTop && {{ $isTransparentNav ? 'true' : 'false' }}) }" href="{{ route('opac.index') }}#layanan">{{ __('landing.nav_services') }}</a></li>
                <li><a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-400 transition-colors no-underline" :class="{ 'text-white/90': atTop && {{ $isTransparentNav ? 'true' : 'false' }}, 'text-slate-600': !(atTop && {{ $isTransparentNav ? 'true' : 'false' }}) }" href="{{ route('opac.index') }}#informasi">{{ __('landing.nav_stats') }}</a></li>
                <li class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="text-sm font-bold uppercase tracking-wider hover:text-indigo-400 transition-colors no-underline flex items-center gap-1 focus:outline-none" :class="{ 'text-white/90': atTop && {{ $isTransparentNav ? 'true' : 'false' }}, 'text-slate-600': !(atTop && {{ $isTransparentNav ? 'true' : 'false' }}) }">
                        Tentang Kami <i class="bi bi-chevron-down text-xs"></i>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute top-full left-0 mt-2 w-56 bg-white border border-slate-100 rounded-xl shadow-xl py-2 z-50" style="display: none;">
                        <a href="{{ route('opac.page', 'sejarah') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">Sejarah</a>
                        <a href="{{ route('opac.page', 'visi-misi') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">Visi dan Misi</a>
                        <a href="{{ route('opac.page', 'struktur-organisasi') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">Struktur Organisasi</a>
                        <a href="{{ route('opac.page', 'pustakawan') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">Pustakawan</a>
                        <a href="{{ route('opac.page', 'program-kerja') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">Program Kerja</a>
                        <a href="{{ route('opac.page', 'tata-tertib') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">Tata Tertib</a>
                        <a href="{{ route('opac.page', 'jam-layanan') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">Jam Layanan</a>
                        @php
                            $opacPages = \Illuminate\Support\Facades\Cache::rememberForever('opac_pages_menu', function () {
                                return \App\Models\Page::where('is_active', true)->whereNotIn('slug', ['sejarah', 'visi-misi', 'struktur-organisasi', 'pustakawan', 'program-kerja', 'tata-tertib', 'jam-layanan'])->orderBy('title')->get();
                            });
                        @endphp
                        @foreach($opacPages as $pageData)
                            <a href="{{ route('opac.page', $pageData->slug) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">{{ $pageData->title }}</a>
                        @endforeach
                    </div>
                </li>
                @endif
                
                <!-- Language Switcher Pill Toggle -->
                <li class="ml-1 flex items-center">
                    <div class="inline-flex items-center p-1 rounded-full border transition-all duration-300"
                         :class="{
                             'bg-white/10 border-white/20 backdrop-blur-md shadow-sm': atTop && {{ $isTransparentNav ? 'true' : 'false' }},
                             'bg-slate-100 border-slate-200/80 shadow-inner': !(atTop && {{ $isTransparentNav ? 'true' : 'false' }})
                         }">
                        <span class="pl-2 pr-1.5 flex items-center text-xs"
                              :class="{
                                  'text-white/70': atTop && {{ $isTransparentNav ? 'true' : 'false' }},
                                  'text-slate-400': !(atTop && {{ $isTransparentNav ? 'true' : 'false' }})
                              }">
                            <i class="bi bi-globe2"></i>
                        </span>

                        <a href="{{ route('lang.switch', 'id') }}" 
                           class="px-2.5 py-1 rounded-full text-xs font-bold transition-all duration-200 no-underline tracking-wider"
                           :class="{
                               'bg-white text-indigo-900 shadow-sm font-extrabold': '{{ app()->getLocale() }}' == 'id' && (atTop && {{ $isTransparentNav ? 'true' : 'false' }}),
                               'bg-indigo-600 text-white shadow-sm font-extrabold': '{{ app()->getLocale() }}' == 'id' && !(atTop && {{ $isTransparentNav ? 'true' : 'false' }}),
                               'text-white/80 hover:text-white hover:bg-white/10': '{{ app()->getLocale() }}' != 'id' && (atTop && {{ $isTransparentNav ? 'true' : 'false' }}),
                               'text-slate-500 hover:text-slate-800 hover:bg-white/60': '{{ app()->getLocale() }}' != 'id' && !(atTop && {{ $isTransparentNav ? 'true' : 'false' }})
                           }">
                            ID
                        </a>

                        <a href="{{ route('lang.switch', 'en') }}" 
                           class="px-2.5 py-1 rounded-full text-xs font-bold transition-all duration-200 no-underline tracking-wider"
                           :class="{
                               'bg-white text-indigo-900 shadow-sm font-extrabold': '{{ app()->getLocale() }}' == 'en' && (atTop && {{ $isTransparentNav ? 'true' : 'false' }}),
                               'bg-indigo-600 text-white shadow-sm font-extrabold': '{{ app()->getLocale() }}' == 'en' && !(atTop && {{ $isTransparentNav ? 'true' : 'false' }}),
                               'text-white/80 hover:text-white hover:bg-white/10': '{{ app()->getLocale() }}' != 'en' && (atTop && {{ $isTransparentNav ? 'true' : 'false' }}),
                               'text-slate-500 hover:text-slate-800 hover:bg-white/60': '{{ app()->getLocale() }}' != 'en' && !(atTop && {{ $isTransparentNav ? 'true' : 'false' }})
                           }">
                            EN
                        </a>
                    </div>
                </li>
                
                @auth
                <li class="relative ml-2" x-data="{ userMenu: false }">
                    <button @click="userMenu = !userMenu" @click.away="userMenu = false" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold text-sm uppercase tracking-wider py-2.5 px-6 rounded-full shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 no-underline flex items-center gap-2">
                        <i class="bi bi-person-circle text-lg"></i> {{ Auth::user()->name }} <i class="bi bi-chevron-down text-xs ml-1"></i>
                    </button>
                    <div x-show="userMenu" style="display: none;" class="absolute right-0 mt-2 w-56 bg-white border border-slate-100 rounded-xl shadow-xl py-2 z-50">
                        @if(Auth::user()->member_id)
                        <a href="{{ route('member.reservations.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline"><i class="bi bi-journal-bookmark mr-2"></i>Buku Saya (Reservasi)</a>
                        @else
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline"><i class="bi bi-speedometer2 mr-2"></i>Dashboard</a>
                        @endif
                        <hr class="my-2 border-slate-100">
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 no-underline"><i class="bi bi-box-arrow-right mr-2"></i>Logout</button>
                        </form>
                    </div>
                </li>
                @else
                <li class="ml-2">
                    <a href="{{ route('login') }}" class="bg-gradient-to-r from-sky-400 to-blue-600 hover:from-sky-500 hover:to-blue-700 text-white font-bold text-sm uppercase tracking-wider py-2.5 px-6 rounded-full shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 no-underline flex items-center gap-2">
                        <i class="bi bi-box-arrow-in-right text-lg"></i> Login
                    </a>
                </li>
                @endauth
            </ul>
        </div>
        
        <!-- Mobile menu button -->
        <div class="lg:hidden flex items-center">
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="focus:outline-none transition-colors" :class="{ 'text-white hover:text-indigo-200': atTop && {{ $isTransparentNav ? 'true' : 'false' }}, 'text-slate-600 hover:text-indigo-600': !(atTop && {{ $isTransparentNav ? 'true' : 'false' }}) }">
                <i class="bi bi-list text-3xl"></i>
            </button>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" class="lg:hidden bg-white border-t border-slate-100 mt-4" style="display: none;">
        <ul class="flex flex-col list-none pl-0 mb-0 py-4 px-4 space-y-4 text-center">
            @if(request()->routeIs('opac.katalog'))
            @php $currentTab = request('tab', 'home'); @endphp
            <li><a class="block font-bold {{ $currentTab == 'home' ? 'text-indigo-600' : 'text-slate-600 hover:text-indigo-600' }} no-underline" href="{{ route('opac.katalog', ['tab' => 'home']) }}">Home</a></li>
            <li><a class="block font-bold {{ $currentTab == 'koleksi' ? 'text-indigo-600' : 'text-slate-600 hover:text-indigo-600' }} no-underline" href="{{ route('opac.katalog', ['tab' => 'koleksi']) }}">Koleksi Buku</a></li>
            <li><a class="block font-bold {{ $currentTab == 'digital' ? 'text-indigo-600' : 'text-slate-600 hover:text-indigo-600' }} no-underline" href="{{ route('opac.katalog', ['tab' => 'digital']) }}">Buku Digital</a></li>
            @else
            <li><a class="block font-bold text-slate-600 hover:text-indigo-600 no-underline" href="{{ route('opac.index') }}">{{ __('landing.nav_home') }}</a></li>
            <li><a class="block font-bold text-slate-600 hover:text-indigo-600 no-underline" href="{{ route('opac.index') }}#layanan">{{ __('landing.nav_services') }}</a></li>
            <li><a class="block font-bold text-slate-600 hover:text-indigo-600 no-underline" href="{{ route('opac.index') }}#informasi">{{ __('landing.nav_stats') }}</a></li>
            <li x-data="{ open: false }" class="flex flex-col items-center w-full">
                <button @click="open = !open" class="font-bold text-slate-600 hover:text-indigo-600 focus:outline-none flex items-center gap-1 justify-center">
                    Tentang <i class="bi bi-chevron-down text-sm transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>
                <ul x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="flex flex-col list-none pl-0 mt-3 space-y-3 text-center bg-slate-50 w-full py-4 rounded-xl shadow-inner" style="display: none;">
                    <li><a href="{{ route('opac.page', 'sejarah') }}" class="block text-sm font-medium text-slate-600 hover:text-indigo-600 no-underline">Sejarah</a></li>
                    <li><a href="{{ route('opac.page', 'visi-misi') }}" class="block text-sm font-medium text-slate-600 hover:text-indigo-600 no-underline">Visi dan Misi</a></li>
                    <li><a href="{{ route('opac.page', 'struktur-organisasi') }}" class="block text-sm font-medium text-slate-600 hover:text-indigo-600 no-underline">Struktur Organisasi</a></li>
                    <li><a href="{{ route('opac.page', 'pustakawan') }}" class="block text-sm font-medium text-slate-600 hover:text-indigo-600 no-underline">Pustakawan</a></li>
                    <li><a href="{{ route('opac.page', 'program-kerja') }}" class="block text-sm font-medium text-slate-600 hover:text-indigo-600 no-underline">Program Kerja</a></li>
                    <li><a href="{{ route('opac.page', 'tata-tertib') }}" class="block text-sm font-medium text-slate-600 hover:text-indigo-600 no-underline">Tata Tertib</a></li>
                    <li><a href="{{ route('opac.page', 'jam-layanan') }}" class="block text-sm font-medium text-slate-600 hover:text-indigo-600 no-underline">Jam Layanan</a></li>
                    @php
                        $opacPages = \Illuminate\Support\Facades\Cache::rememberForever('opac_pages_menu', function () {
                            return \App\Models\Page::where('is_active', true)->whereNotIn('slug', ['sejarah', 'visi-misi', 'struktur-organisasi', 'pustakawan', 'program-kerja', 'tata-tertib', 'jam-layanan'])->orderBy('title')->get();
                        });
                    @endphp
                    @foreach($opacPages as $pageData)
                        <li><a href="{{ route('opac.page', $pageData->slug) }}" class="block text-sm font-medium text-slate-600 hover:text-indigo-600 no-underline">{{ $pageData->title }}</a></li>
                    @endforeach
                </ul>
            </li>
            @endif
            
            <!-- Mobile Language Switcher Pill Toggle -->
            <li class="flex justify-center py-2">
                <div class="inline-flex items-center p-1 rounded-full bg-slate-100 border border-slate-200 shadow-inner">
                    <span class="pl-2.5 pr-2 flex items-center text-xs text-slate-400">
                        <i class="bi bi-globe2"></i>
                    </span>
                    <a href="{{ route('lang.switch', 'id') }}" 
                       class="px-3 py-1 rounded-full text-xs font-bold transition-all no-underline tracking-wider {{ app()->getLocale() == 'id' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                        ID
                    </a>
                    <a href="{{ route('lang.switch', 'en') }}" 
                       class="px-3 py-1 rounded-full text-xs font-bold transition-all no-underline tracking-wider {{ app()->getLocale() == 'en' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                        EN
                    </a>
                </div>
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

<main class="{{ $isTransparentNav ? '' : 'pt-28 md:pt-32' }} relative z-10 min-h-[calc(100vh-250px)]">
    @yield('content')
</main>

@if(request()->routeIs('opac.index'))
<footer id="tentang" class="bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 text-white/80 pt-16 pb-8 mt-16 scroll-mt-28 relative overflow-hidden">
    <!-- Mask Shape Overlay -->
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at center, white 1px, transparent 1px); background-size: 24px 24px; pointer-events: none;"></div>
    
    <div class="w-full mx-auto px-6 sm:px-6 lg:px-12 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
            <!-- Col 1: Logo & Address -->
            <div class="lg:col-span-1">
                <a href="{{ route('opac.index') }}" class="flex items-center gap-3 mb-6 no-underline">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-12 h-12 object-contain rounded bg-white p-1">
                    <span class="font-bold text-white text-lg uppercase tracking-wider leading-tight">
                        {{ \App\Models\Setting::get('library_name', __('landing.school_library_full')) }}
                    </span>
                </a>
                <p class="text-sm leading-relaxed mb-6">
                    {{ \App\Models\Setting::get('library_address', 'Jl. Danau Agung 13 Blok E19, Sunter Agung Podomoro') }}
                </p>
                <p class="text-xs text-white/50">
                    Semua aset pada website ini terikat pada perundangan yang berlaku.
                </p>
            </div>

            <!-- Col 2: Layanan Publik -->
            <div>
                <h6 class="text-white text-sm font-bold mb-6 uppercase tracking-wider">Layanan Publik</h6>
                <ul class="space-y-3 text-sm list-none pl-0 m-0">
                    <li><a href="{{ route('opac.katalog') }}" class="text-white/70 hover:text-white transition-colors no-underline">Katalog Buku</a></li>
                    <li><a href="{{ route('opac.katalog', ['tab' => 'digital']) }}" class="text-white/70 hover:text-white transition-colors no-underline">Buku Digital</a></li>
                    <li><a href="{{ route('opac.agenda') }}" class="text-white/70 hover:text-white transition-colors no-underline">Agenda Kegiatan</a></li>
                    <li><a href="{{ route('guest-books.visitor') }}" class="text-white/70 hover:text-white transition-colors no-underline">Buku Tamu</a></li>
                    <li><a href="{{ route('opac.page', 'tata-tertib') }}" class="text-white/70 hover:text-white transition-colors no-underline">Tata Tertib</a></li>
                    <li><a href="{{ route('opac.page', 'jam-layanan') }}" class="text-white/70 hover:text-white transition-colors no-underline">Jam Layanan</a></li>
                </ul>
            </div>

            <!-- Col 3: Website Terkait -->
            <div>
                <h6 class="text-white text-sm font-bold mb-6 uppercase tracking-wider">Website Terkait</h6>
                <ul class="space-y-3 text-sm list-none pl-0 m-0">
                    <li><a href="#" class="text-white/70 hover:text-white transition-colors no-underline">Sekolah Utama</a></li>
                    <li><a href="#" class="text-white/70 hover:text-white transition-colors no-underline">Portal Siswa</a></li>
                    <li><a href="#" class="text-white/70 hover:text-white transition-colors no-underline">E-Learning</a></li>
                    <li><a href="https://perpusnas.go.id" target="_blank" class="text-white/70 hover:text-white transition-colors no-underline">Perpusnas RI</a></li>
                    <li><a href="https://ipusnas.id" target="_blank" class="text-white/70 hover:text-white transition-colors no-underline">iPusnas</a></li>
                </ul>
            </div>

            <!-- Col 4: Ikuti Kami -->
            <div>
                <h6 class="text-white text-sm font-bold mb-6 uppercase tracking-wider">Ikuti Kami & Kontak</h6>
                <ul class="space-y-3 text-sm list-none pl-0 m-0">
                    <li class="flex items-center gap-3">
                        <i class="bi bi-telephone text-lg text-white/50 w-5 text-center"></i>
                        <span>{{ \App\Models\Setting::get('library_phone', '021-6459109') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="bi bi-envelope text-lg text-white/50 w-5 text-center"></i>
                        <span>{{ \App\Models\Setting::get('library_email', 'yayasan@santopaulus.school') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="bi bi-facebook text-lg text-white/50 w-5 text-center"></i>
                        <a href="#" class="text-white/70 hover:text-white transition-colors no-underline">Facebook</a>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="bi bi-instagram text-lg text-white/50 w-5 text-center"></i>
                        <a href="#" class="text-white/70 hover:text-white transition-colors no-underline">Instagram</a>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="bi bi-youtube text-lg text-white/50 w-5 text-center"></i>
                        <a href="#" class="text-white/70 hover:text-white transition-colors no-underline">Youtube</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs mt-8">
            <div>
                Copyright &copy; {{ date('Y') }} {{ \App\Models\Setting::get('library_name', config('app.name', 'Makarya')) }}. All rights reserved.
            </div>
            <div class="flex items-center gap-4">
                <a href="#" class="text-white/50 hover:text-white transition-colors no-underline">{{ __('landing.privacy_policy') }}</a>
                <a href="#" class="text-white/50 hover:text-white transition-colors no-underline">{{ __('landing.help') }}</a>
            </div>
        </div>
    </div>
</footer>
@endif

@stack('scripts')

    <x-toast />
</body>
</html>
