<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'OPAC - Online Public Access Catalog Perpustakaan')">
    <title>@yield('title', 'Katalog') - {{ config('app.name', 'Makarya') }} OPAC</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-blue-50" style="font-family: 'Poppins', sans-serif;">

{{-- OPAC Navbar --}}
<nav x-data="{ mobileMenuOpen: false }" class="bg-white shadow-sm py-4 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex flex-row items-center justify-between px-6 relative">
        
        <!-- Left: Logo -->
        <a class="flex flex-row items-center gap-4 text-slate-800 no-underline group" href="{{ route('opac.index') }}">
            <div style="width:80px;height:80px;display:flex;align-items:center;justify-content:center;" class="group-hover:scale-105 transition-transform duration-300">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" style="width:100%;height:100%;object-fit:contain;mix-blend-mode:multiply;">
            </div>
            <div class="flex flex-col text-left hidden sm:flex">
                <span class="text-xl md:text-2xl font-black text-slate-800 tracking-tight leading-none group-hover:text-indigo-600 transition-colors">Perpustakaan Sekolah</span>
                <span class="text-xs md:text-sm font-bold text-slate-500 uppercase tracking-widest mt-1">Katolik Santo Paulus</span>
            </div>
        </a>

        <!-- Right: Links & Buttons (Desktop) -->
        <div class="hidden lg:flex items-center">
            <ul class="flex items-center gap-8 list-none pl-0 mb-0">
                <li><a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-600 transition-colors text-slate-600 no-underline" href="{{ route('opac.index') }}#beranda">Beranda</a></li>
                <li><a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-600 transition-colors text-slate-600 no-underline" href="{{ route('opac.index') }}#layanan">Layanan</a></li>
                <li><a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-600 transition-colors text-slate-600 no-underline" href="{{ route('opac.index') }}#informasi">Informasi</a></li>
                <li><a class="text-sm font-bold uppercase tracking-wider hover:text-indigo-600 transition-colors text-slate-600 no-underline" href="#tentang">Tentang</a></li>
                
                <li class="ml-2">
                    @auth
                    <a href="{{ route('dashboard') }}" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold text-sm uppercase tracking-wider py-2.5 px-6 rounded-full shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 no-underline flex items-center gap-2">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
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
            <li><a class="block font-bold text-slate-600 hover:text-indigo-600 no-underline" href="{{ route('opac.index') }}#beranda">Beranda</a></li>
            <li><a class="block font-bold text-slate-600 hover:text-indigo-600 no-underline" href="{{ route('opac.index') }}#layanan">Layanan</a></li>
            <li><a class="block font-bold text-slate-600 hover:text-indigo-600 no-underline" href="{{ route('opac.index') }}#informasi">Informasi</a></li>
            <li><a class="block font-bold text-slate-600 hover:text-indigo-600 no-underline" href="#tentang">Tentang</a></li>
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
    <div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 mb-12">
            <!-- Tentang -->
            <div class="lg:col-span-2">
                <div class="flex items-center gap-4 mb-6">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-12 h-12 object-contain rounded bg-white p-1">
                    <span class="font-bold text-white text-lg uppercase tracking-wider">Perpustakaan Katolik Santo Paulus</span>
                </div>
                <p class="leading-relaxed mb-6 max-w-2xl text-sm">
                    Perpustakaan Sekolah Katolik Santo Paulus adalah pusat sumber belajar yang menyediakan berbagai koleksi cetak maupun digital. Kami berkomitmen untuk mendukung proses belajar mengajar, serta menumbuhkan budaya literasi dan minat baca yang kuat di lingkungan sekolah.
                </p>
            </div>

            <!-- Informasi -->
            <div>
                <h3 class="text-white text-lg font-bold mb-6 uppercase tracking-wider">Informasi</h3>
                <ul class="space-y-4 text-sm list-none pl-0 m-0">
                    <li class="flex items-start gap-3">
                        <i class="bi bi-geo-alt text-lg text-amber-400 mt-0.5"></i>
                        <span>Jl. Danau Agung 13 Blok E19, <br>Sunter Agung Podomoro</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="bi bi-telephone text-lg text-amber-400"></i>
                        <span>021-6459109</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="bi bi-envelope text-lg text-amber-400"></i>
                        <span>yayasan@santopaulus.school</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="bi bi-instagram text-lg text-amber-400"></i>
                        <span>@perpustakaan_santopaulus</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="bi bi-clock-history text-lg text-amber-400 mt-0.5"></i>
                        <span>
                            <strong>Jam Operasional:</strong><br>
                            Senin - Jumat: 07:00 - 15:00 WIB<br>
                            Sabtu, Minggu & Hari Libur: Tutup
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs">
            <div>
                &copy; {{ date('Y') }} {{ config('app.name', 'Makarya') }} — Sistem Informasi Perpustakaan. All rights reserved.
            </div>
            <div class="flex items-center gap-4">
                <a href="#" class="text-white/50 hover:text-white transition-colors no-underline">Kebijakan Privasi</a>
                <a href="#" class="text-white/50 hover:text-white transition-colors no-underline">Bantuan</a>
            </div>
        </div>
    </div>
</footer>

@stack('scripts')

    <!-- SweetAlert2 Global Toast Handler -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if(session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            @if(session('error'))
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            @endif

            @if(session('warning'))
                Toast.fire({
                    icon: 'warning',
                    title: "{{ session('warning') }}"
                });
            @endif
        });
    </script>
</body>
</html>
