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
        'bg-white shadow-md py-2.5 sm:py-3': !atTop, 
        'bg-slate-900/95 backdrop-blur-md shadow-lg py-3 sm:py-4': atTop && mobileMenuOpen && {{ $isTransparentNav ? 'true' : 'false' }}, 
        'bg-transparent py-3 sm:py-5': atTop && !mobileMenuOpen && {{ $isTransparentNav ? 'true' : 'false' }}, 
        'bg-white shadow-sm py-2.5 sm:py-4': atTop && !{{ $isTransparentNav ? 'true' : 'false' }} 
     }" 
     class="fixed w-full top-0 z-50 transition-all duration-300">
    <div class="w-full mx-auto flex flex-row items-center justify-between px-4 sm:px-6 relative">
        
        <!-- Left: Logo & Title -->
        <a class="flex flex-row items-center gap-2.5 sm:gap-4 no-underline group min-w-0" href="{{ route('opac.index') }}">
            <div class="w-11 h-11 sm:w-14 sm:h-14 md:w-16 md:h-16 flex items-center justify-center group-hover:scale-105 transition-transform duration-300 bg-white rounded-xl p-1 sm:p-1.5 shadow-sm flex-shrink-0">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain" style="mix-blend-mode:multiply;">
            </div>
            <div class="flex flex-col text-left min-w-0" :class="{ 'text-white': (atTop && {{ $isTransparentNav ? 'true' : 'false' }}), 'text-slate-800': !(atTop && {{ $isTransparentNav ? 'true' : 'false' }}) }">
                <span class="text-sm sm:text-lg md:text-xl font-black tracking-tight leading-tight group-hover:text-indigo-400 transition-colors truncate">{{ __('landing.school_library') }}</span>
                <span class="text-[9px] sm:text-xs md:text-sm font-bold uppercase tracking-wider opacity-80 leading-none mt-0.5 truncate">{{ __('landing.catholic_school') }}</span>
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
                       href="{{ route('opac.katalog', ['tab' => 'home']) }}">{{ __('Home') }}</a>
                </li>
                <li>
                    <a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-400 transition-colors no-underline {{ $currentTab == 'koleksi' ? 'text-indigo-600 font-extrabold' : '' }}" 
                       :class="{ 'text-white/90': atTop && {{ $isTransparentNav ? 'true' : 'false' }}, 'text-slate-600': !(atTop && {{ $isTransparentNav ? 'true' : 'false' }}) }" 
                       href="{{ route('opac.katalog', ['tab' => 'koleksi']) }}">{{ __('Koleksi Buku') }}</a>
                </li>
                <li>
                    <a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-400 transition-colors no-underline {{ $currentTab == 'digital' ? 'text-indigo-600 font-extrabold' : '' }}" 
                       :class="{ 'text-white/90': atTop && {{ $isTransparentNav ? 'true' : 'false' }}, 'text-slate-600': !(atTop && {{ $isTransparentNav ? 'true' : 'false' }}) }" 
                       href="{{ route('opac.katalog', ['tab' => 'digital']) }}">{{ __('Buku Digital') }}</a>
                </li>
                @else
                <li><a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-400 transition-colors no-underline" :class="{ 'text-white/90': atTop && {{ $isTransparentNav ? 'true' : 'false' }}, 'text-slate-600': !(atTop && {{ $isTransparentNav ? 'true' : 'false' }}) }" href="{{ route('opac.index') }}">{{ __('landing.nav_home') }}</a></li>
                <li><a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-400 transition-colors no-underline" :class="{ 'text-white/90': atTop && {{ $isTransparentNav ? 'true' : 'false' }}, 'text-slate-600': !(atTop && {{ $isTransparentNav ? 'true' : 'false' }}) }" href="{{ route('opac.index') }}#layanan">{{ __('landing.nav_services') }}</a></li>
                <li><a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-400 transition-colors no-underline" :class="{ 'text-white/90': atTop && {{ $isTransparentNav ? 'true' : 'false' }}, 'text-slate-600': !(atTop && {{ $isTransparentNav ? 'true' : 'false' }}) }" href="{{ route('opac.index') }}#informasi">{{ __('landing.nav_stats') }}</a></li>
                <li class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="text-sm font-bold uppercase tracking-wider hover:text-indigo-400 transition-colors no-underline flex items-center gap-1 focus:outline-none" :class="{ 'text-white/90': atTop && {{ $isTransparentNav ? 'true' : 'false' }}, 'text-slate-600': !(atTop && {{ $isTransparentNav ? 'true' : 'false' }}) }">
                        {{ __('Tentang Kami') }} <i class="bi bi-chevron-down text-xs"></i>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute top-full left-0 mt-2 w-56 bg-white border border-slate-100 rounded-xl shadow-xl py-2 z-50" style="display: none;">
                        <a href="{{ route('opac.page', 'sejarah') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">{{ __('Sejarah') }}</a>
                        <a href="{{ route('opac.page', 'visi-misi') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">{{ __('Visi dan Misi') }}</a>
                        <a href="{{ route('opac.page', 'struktur-organisasi') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">{{ __('Struktur Organisasi') }}</a>
                        <a href="{{ route('opac.page', 'pustakawan') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">{{ __('Pustakawan') }}</a>
                        <a href="{{ route('opac.page', 'program-kerja') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">{{ __('Program Kerja') }}</a>
                        <a href="{{ route('opac.page', 'tata-tertib') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">{{ __('Tata Tertib') }}</a>
                        <a href="{{ route('opac.page', 'jam-layanan') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline">{{ __('Jam Layanan') }}</a>
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
                        <a href="{{ route('member.reservations.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline"><i class="bi bi-journal-bookmark mr-2"></i>{{ __('Buku Saya (Reservasi)') }}</a>
                        @else
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 no-underline"><i class="bi bi-speedometer2 mr-2"></i>{{ __('Dashboard') }}</a>
                        @endif
                        <hr class="my-2 border-slate-100">
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 no-underline"><i class="bi bi-box-arrow-right mr-2"></i>{{ __('Logout') }}</button>
                        </form>
                    </div>
                </li>
                @endauth
            </ul>
        </div>
        
        <!-- Mobile menu toggle button -->
        <div class="lg:hidden flex items-center">
            <button @click="mobileMenuOpen = !mobileMenuOpen" 
                    type="button"
                    class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200 focus:outline-none" 
                    :class="{ 
                        'bg-white/10 hover:bg-white/20 text-white backdrop-blur-md border border-white/20': (atTop && {{ $isTransparentNav ? 'true' : 'false' }}), 
                        'bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200': !(atTop && {{ $isTransparentNav ? 'true' : 'false' }}) 
                    }"
                    aria-label="Toggle navigation">
                <i class="bi transition-transform duration-200" :class="mobileMenuOpen ? 'bi-x-lg text-lg' : 'bi-list text-2xl'"></i>
            </button>
        </div>
    </div>
    
    <!-- Mobile Menu Floating Card -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0 -translate-y-3 scale-[0.98]"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-3 scale-[0.98]"
         @click.away="mobileMenuOpen = false"
         class="lg:hidden px-4 pt-2 pb-4 max-h-[calc(100vh-80px)] overflow-y-auto" 
         style="display: none;">
        
        <div class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-slate-200/80 p-3.5 space-y-1.5 text-left">
            @if(request()->routeIs('opac.katalog'))
                @php $currentTab = request('tab', 'home'); @endphp
                <a href="{{ route('opac.katalog', ['tab' => 'home']) }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all no-underline {{ $currentTab == 'home' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-indigo-600' }}">
                    <i class="bi bi-house-door text-base"></i>
                    <span>{{ __('Home') }}</span>
                </a>
                <a href="{{ route('opac.katalog', ['tab' => 'koleksi']) }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all no-underline {{ $currentTab == 'koleksi' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-indigo-600' }}">
                    <i class="bi bi-book text-base"></i>
                    <span>{{ __('Koleksi Buku Fisik') }}</span>
                </a>
                <a href="{{ route('opac.katalog', ['tab' => 'digital']) }}" 
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all no-underline {{ $currentTab == 'digital' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-indigo-600' }}">
                    <span class="flex items-center gap-3">
                        <i class="bi bi-file-earmark-pdf text-base"></i>
                        <span>{{ __('Buku Digital') }}</span>
                    </span>
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full {{ $currentTab == 'digital' ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-700' }}">
                        E-Book
                    </span>
                </a>
            @else
                <a href="{{ route('opac.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-100 hover:text-indigo-600 transition-all no-underline">
                    <i class="bi bi-house-door text-base text-indigo-600"></i>
                    <span>{{ __('landing.nav_home') }}</span>
                </a>
                <a href="{{ route('opac.index') }}#layanan" @click="mobileMenuOpen = false" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-100 hover:text-indigo-600 transition-all no-underline">
                    <i class="bi bi-grid text-base text-indigo-600"></i>
                    <span>{{ __('landing.nav_services') }}</span>
                </a>
                <a href="{{ route('opac.index') }}#informasi" @click="mobileMenuOpen = false" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-100 hover:text-indigo-600 transition-all no-underline">
                    <i class="bi bi-bar-chart-line text-base text-indigo-600"></i>
                    <span>{{ __('landing.nav_stats') }}</span>
                </a>
                
                {{-- Accordion Tentang Kami --}}
                <div x-data="{ openTentang: false }" class="border-t border-slate-100 pt-1">
                    <button @click="openTentang = !openTentang" 
                            type="button"
                            class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-bold text-slate-700 hover:bg-slate-100 hover:text-indigo-600 transition-all focus:outline-none">
                        <span class="flex items-center gap-3">
                            <i class="bi bi-info-circle text-base text-indigo-600"></i>
                            <span>{{ __('Tentang Kami') }}</span>
                        </span>
                        <i class="bi bi-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': openTentang }"></i>
                    </button>
                    
                    <div x-show="openTentang" 
                         x-transition:enter="transition ease-out duration-150" 
                         x-transition:enter-start="opacity-0 -translate-y-1" 
                         x-transition:enter-end="opacity-100 translate-y-0" 
                         x-transition:leave="transition ease-in duration-100" 
                         x-transition:leave-start="opacity-100 translate-y-0" 
                         x-transition:leave-end="opacity-0 -translate-y-1" 
                         class="pl-6 pr-2 py-2 mt-1 space-y-1 bg-slate-50 rounded-xl" 
                         style="display: none;">
                        <a href="{{ route('opac.page', 'sejarah') }}" class="block px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-indigo-600 no-underline">{{ __('Sejarah') }}</a>
                        <a href="{{ route('opac.page', 'visi-misi') }}" class="block px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-indigo-600 no-underline">{{ __('Visi dan Misi') }}</a>
                        <a href="{{ route('opac.page', 'struktur-organisasi') }}" class="block px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-indigo-600 no-underline">{{ __('Struktur Organisasi') }}</a>
                        <a href="{{ route('opac.page', 'pustakawan') }}" class="block px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-indigo-600 no-underline">{{ __('Pustakawan') }}</a>
                        <a href="{{ route('opac.page', 'program-kerja') }}" class="block px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-indigo-600 no-underline">{{ __('Program Kerja') }}</a>
                        <a href="{{ route('opac.page', 'tata-tertib') }}" class="block px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-indigo-600 no-underline">{{ __('Tata Tertib') }}</a>
                        <a href="{{ route('opac.page', 'jam-layanan') }}" class="block px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-indigo-600 no-underline">{{ __('Jam Layanan') }}</a>
                        @php
                            $opacPages = \Illuminate\Support\Facades\Cache::rememberForever('opac_pages_menu', function () {
                                return \App\Models\Page::where('is_active', true)->whereNotIn('slug', ['sejarah', 'visi-misi', 'struktur-organisasi', 'pustakawan', 'program-kerja', 'tata-tertib', 'jam-layanan'])->orderBy('title')->get();
                            });
                        @endphp
                        @foreach($opacPages as $pageData)
                            <a href="{{ route('opac.page', $pageData->slug) }}" class="block px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-indigo-600 no-underline">{{ $pageData->title }}</a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Row Bahasa / Language --}}
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between px-2 py-1">
                <span class="text-xs font-semibold text-slate-500 flex items-center gap-1.5">
                    <i class="bi bi-globe2 text-indigo-500"></i> {{ __('Bahasa / Language') }}
                </span>
                <div class="inline-flex items-center p-0.5 rounded-lg bg-slate-100 border border-slate-200">
                    <a href="{{ route('lang.switch', 'id') }}" 
                       class="px-2.5 py-1 rounded-md text-xs font-bold transition-all no-underline tracking-wider {{ app()->getLocale() == 'id' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                        ID
                    </a>
                    <a href="{{ route('lang.switch', 'en') }}" 
                       class="px-2.5 py-1 rounded-md text-xs font-bold transition-all no-underline tracking-wider {{ app()->getLocale() == 'en' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                        EN
                    </a>
                </div>
            </div>

            {{-- User Authentication Section --}}
            @auth
            <div class="pt-2 border-t border-slate-100">
                <div class="p-3 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl border border-indigo-100">
                    <div class="flex items-center justify-between mb-2.5">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-xs flex-shrink-0">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] text-slate-500 capitalize leading-none">{{ Auth::user()->role ?? 'Pengguna' }}</div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="m-0 flex-shrink-0">
                            @csrf
                            <button type="submit" class="text-xs text-red-600 hover:text-red-700 font-bold px-2 py-1 rounded-md hover:bg-red-50 transition-colors flex items-center gap-1" title="{{ __('Logout') }}">
                                <i class="bi bi-box-arrow-right"></i> {{ __('Keluar') }}
                            </button>
                        </form>
                    </div>
                    @if(Auth::user()->member_id)
                        <a href="{{ route('member.reservations.index') }}" class="w-full inline-flex items-center justify-center gap-2 py-2 px-3 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition-colors no-underline">
                            <i class="bi bi-journal-bookmark"></i> {{ __('Buku Saya (Reservasi)') }}
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="w-full inline-flex items-center justify-center gap-2 py-2 px-3 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition-colors no-underline">
                            <i class="bi bi-speedometer2"></i> {{ __('Buka Dashboard') }}
                        </a>
                    @endif
                </div>
            </div>
            @else
            <div class="pt-2 border-t border-slate-100">
                <a href="{{ route('login.member') }}" class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-200 transition-all no-underline">
                    <i class="bi bi-box-arrow-in-right"></i> {{ __('Masuk Anggota / Siswa') }}
                </a>
            </div>
            @endauth

        </div>
    </div>
</nav>

<main class="{{ $isTransparentNav ? '' : 'pt-28 md:pt-32' }} relative min-h-[calc(100vh-250px)]">
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
                    {{ __('Semua aset pada website ini terikat pada perundangan yang berlaku.') }}
                </p>
            </div>

            <!-- Col 2: Layanan Publik -->
            <div>
                <h6 class="text-white text-sm font-bold mb-6 uppercase tracking-wider">{{ __('Layanan Publik') }}</h6>
                <ul class="space-y-3 text-sm list-none pl-0 m-0">
                    <li><a href="{{ route('opac.katalog') }}" class="text-white/70 hover:text-white transition-colors no-underline">{{ __('Katalog Buku') }}</a></li>
                    <li><a href="{{ route('opac.katalog', ['tab' => 'digital']) }}" class="text-white/70 hover:text-white transition-colors no-underline">{{ __('Buku Digital') }}</a></li>
                    <li><a href="{{ route('opac.agenda') }}" class="text-white/70 hover:text-white transition-colors no-underline">{{ __('Agenda Kegiatan') }}</a></li>
                    <li><a href="{{ route('guest-books.visitor') }}" class="text-white/70 hover:text-white transition-colors no-underline">{{ __('Buku Tamu') }}</a></li>
                    <li><a href="{{ route('opac.page', 'tata-tertib') }}" class="text-white/70 hover:text-white transition-colors no-underline">{{ __('Tata Tertib') }}</a></li>
                    <li><a href="{{ route('opac.page', 'jam-layanan') }}" class="text-white/70 hover:text-white transition-colors no-underline">{{ __('Jam Layanan') }}</a></li>
                </ul>
            </div>

            <!-- Col 3: Website Terkait -->
            <div>
                <h6 class="text-white text-sm font-bold mb-6 uppercase tracking-wider">{{ __('Website Terkait') }}</h6>
                <ul class="space-y-3 text-sm list-none pl-0 m-0">
                    <li><a href="#" class="text-white/70 hover:text-white transition-colors no-underline">{{ __('Sekolah Utama') }}</a></li>
                    <li><a href="#" class="text-white/70 hover:text-white transition-colors no-underline">{{ __('Portal Siswa') }}</a></li>
                    <li><a href="#" class="text-white/70 hover:text-white transition-colors no-underline">{{ __('E-Learning') }}</a></li>
                    <li><a href="https://perpusnas.go.id" target="_blank" class="text-white/70 hover:text-white transition-colors no-underline">Perpusnas RI</a></li>
                    <li><a href="https://ipusnas.id" target="_blank" class="text-white/70 hover:text-white transition-colors no-underline">iPusnas</a></li>
                </ul>
            </div>

            <!-- Col 4: Ikuti Kami -->
            <div>
                <h6 class="text-white text-sm font-bold mb-6 uppercase tracking-wider">{{ __('Ikuti Kami & Kontak') }}</h6>
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

@stack('modals')
@stack('scripts')

    <x-toast />

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Universal Modern Confirmation Interceptor for Forms with data-confirm
            document.addEventListener('submit', function (e) {
                const form = e.target;
                const confirmMsg = form.dataset.confirm;
                if (!confirmMsg || form.dataset.confirmed === 'true') return;

                e.preventDefault();
                const title = form.dataset.confirmTitle || '{{ __('Konfirmasi Tindakan') }}';
                const subtitle = form.dataset.confirmSubtitle || '';
                const confirmBtnText = form.dataset.confirmButton || '{{ __('Ya, Lanjutkan') }}';
                const cancelBtnText = form.dataset.confirmCancel || '{{ __('Batal') }}';
                const isDanger = form.dataset.confirmDanger === 'true';
                const icon = form.dataset.confirmIcon || (isDanger ? 'warning' : 'question');

                let htmlContent = `<div class="text-xs sm:text-sm text-slate-600 leading-relaxed">${confirmMsg}</div>`;
                if (subtitle) {
                    htmlContent += `<div class="text-[11px] sm:text-xs text-slate-500 mt-2.5 bg-slate-50 p-2.5 rounded-xl border border-slate-100 text-left">${subtitle}</div>`;
                }

                if (window.Swal) {
                    Swal.fire({
                        title: title,
                        html: htmlContent,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonText: confirmBtnText,
                        cancelButtonText: cancelBtnText,
                        confirmButtonColor: isDanger ? '#e11d48' : '#e50914',
                        cancelButtonColor: '#94a3b8',
                        reverseButtons: true,
                        focusConfirm: true,
                        customClass: {
                            popup: 'rounded-3xl p-6 shadow-2xl border border-slate-100 max-w-sm sm:max-w-md',
                            title: 'text-slate-900 text-lg sm:text-xl font-bold pt-1',
                            confirmButton: 'rounded-xl font-semibold text-xs sm:text-sm px-5 py-2.5 shadow-sm',
                            cancelButton: 'rounded-xl font-semibold text-xs sm:text-sm px-5 py-2.5 shadow-sm'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.dataset.confirmed = 'true';
                            Swal.fire({
                                title: '{{ __('Memproses...') }}',
                                text: '{{ __('Mohon tunggu sebentar.') }}',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            form.submit();
                        }
                    });
                } else if (confirm(confirmMsg)) {
                    form.dataset.confirmed = 'true';
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>
