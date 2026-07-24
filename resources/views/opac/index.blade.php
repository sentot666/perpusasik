@extends('layouts.opac')

@section('title', 'Katalog Online (OPAC)')

@section('content')
{{-- Hero Carousel Section --}}
<div id="beranda" class="relative mb-8 scroll-mt-28">
    <div class="swiper heroSwiper w-full h-[400px] md:h-[550px]">
        <div class="swiper-wrapper">
            <!-- Slide 1 -->
            <div class="swiper-slide">
                <div class="w-full h-full flex flex-col items-center justify-center text-white px-4" style="background: linear-gradient(rgba(15, 94, 197, 0.7), rgba(45, 82, 130, 0.7)), url('{{ asset('images/carousel-1.jpg') }}'); background-size: cover; background-position: center;">
                    <h1 class="text-3xl md:text-5xl font-bold mb-4 text-center">Selamat Datang di Perpustakaan Sekolah Santo Paulus</h1>
                    <p class="text-lg md:text-xl text-white/80 max-w-2xl text-center">Eksplorasi ribuan koleksi buku, jurnal, dan referensi digital perpustakaan kami.</p>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="swiper-slide">
                <div class="w-full h-full flex flex-col items-center justify-center text-white px-4" style="background: linear-gradient(rgba(15, 94, 197, 0.7), rgba(45, 82, 130, 0.7)), url('{{ asset('images/carousel-2.jpg') }}'); background-size: cover; background-position: center;">
                    <h1 class="text-3xl md:text-5xl font-bold mb-4 text-center"></h1>
                    <p class="text-lg md:text-xl text-white/80 max-w-2xl text-center"></p>
                </div>
            </div>
            <!-- Slide 3 -->
            <div class="swiper-slide">
                <div class="w-full h-full flex flex-col items-center justify-center text-white px-4" style="background: linear-gradient(rgba(15, 94, 197, 0.7), rgba(45, 82, 130, 0.7)), url('{{ asset('images/carousel-3.jpg') }}'); background-size: cover; background-position: center;">
                    <h1 class="text-3xl md:text-5xl font-bold mb-4 text-center"></h1>
                    <p class="text-lg md:text-xl text-white/80 max-w-2xl text-center"></p>
                </div>
            </div>
        </div>
        <!-- Pagination & Navigation -->
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next text-white/50 hover:text-white"></div>
        <div class="swiper-button-prev text-white/50 hover:text-white"></div>
    </div>

    

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-6">

    {{-- Section Heading --}}
    <div id="layanan" class="text-center mt-10 mb-8 scroll-mt-[140px]">
        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-2">Layanan Cepat</h2>
        <p class="text-slate-500">Pilih menu layanan di bawah ini untuk akses lebih cepat</p>
    </div>

    {{-- Quick Menu Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
        <!-- Katalog -->
        <a href="{{ route('opac.katalog') }}" class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 py-12 px-8 md:px-10 min-h-[180px] flex items-center text-left no-underline border border-slate-100 group transform hover:-translate-y-1">
            <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mr-6 group-hover:scale-110 transition-transform duration-300 shadow-sm shrink-0">
                <i class="bi bi-search text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl md:text-2xl font-bold text-slate-800 mb-2">Katalog</h3>
                <p class="text-sm text-slate-500 font-medium m-0">Cari & temukan koleksi buku</p>
            </div>
        </a>

        <!-- Keanggotaan -->
        <a href="#" class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 py-12 px-8 md:px-10 min-h-[180px] flex items-center text-left no-underline border border-slate-100 group transform hover:-translate-y-1">
            <div class="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mr-6 group-hover:scale-110 transition-transform duration-300 shadow-sm shrink-0">
                <i class="bi bi-person-vcard text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl md:text-2xl font-bold text-slate-800 mb-2">Keanggotaan</h3>
                <p class="text-sm text-slate-500 font-medium m-0">Daftar & info keanggotaan</p>
            </div>
        </a>

        <!-- Buku Kunjungan -->
        <a href="{{ route('guest-books.visitor') }}" class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 py-12 px-8 md:px-10 min-h-[180px] flex items-center text-left no-underline border border-slate-100 group transform hover:-translate-y-1">
            <div class="w-20 h-20 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mr-6 group-hover:scale-110 transition-transform duration-300 shadow-sm shrink-0">
                <i class="bi bi-pen text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl md:text-2xl font-bold text-slate-800 mb-2">Buku Kunjungan</h3>
                <p class="text-sm text-slate-500 font-medium m-0">Isi daftar hadir pengunjung</p>
            </div>
        </a>

        <!-- Agenda Kegiatan -->
        <a href="#" class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 py-12 px-8 md:px-10 min-h-[180px] flex items-center text-left no-underline border border-slate-100 group transform hover:-translate-y-1">
            <div class="w-20 h-20 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mr-6 group-hover:scale-110 transition-transform duration-300 shadow-sm shrink-0">
                <i class="bi bi-calendar-event text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl md:text-2xl font-bold text-slate-800 mb-2">Agenda Kegiatan</h3>
                <p class="text-sm text-slate-500 font-medium m-0">Informasi acara perpustakaan</p>
            </div>
        </a>
    </div>

    {{-- Section Heading for Stats --}}
    <div id="informasi" class="text-left mt-14 mb-6 scroll-mt-[140px]">
        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-2">Statistik Perpustakaan</h2>
        <p class="text-slate-500">Informasi ringkas mengenai koleksi dan aktivitas</p>
    </div>

    {{-- Statistics Section (Redesigned) --}}
    <div class="bg-gradient-to-r from-teal-500 to-blue-700 rounded-2xl p-6 md:p-8 shadow-lg mb-16">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            <!-- Total Buku -->
            <div class="bg-amber-500 text-white rounded-xl py-8 px-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                <i class="bi bi-journals text-4xl mb-3 hover:scale-110 transition-transform duration-300"></i>
                <span class="font-bold text-sm md:text-base tracking-wide mt-1 text-white/90">Total Judul Buku</span>
                <span class="font-bold text-2xl md:text-3xl mt-1">{{ number_format($stats['total_books'] ?? 0) }}</span>
            </div>

            <!-- Total Eksemplar -->
            <div class="bg-emerald-500 text-white rounded-xl py-8 px-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                <i class="bi bi-collection text-4xl mb-3 hover:scale-110 transition-transform duration-300"></i>
                <span class="font-bold text-sm md:text-base tracking-wide mt-1 text-white/90">Total Eksemplar</span>
                <span class="font-bold text-2xl md:text-3xl mt-1">{{ number_format($stats['total_items'] ?? 0) }}</span>
            </div>

            <!-- Total Anggota -->
            <div class="bg-rose-500 text-white rounded-xl py-8 px-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                <i class="bi bi-people-fill text-4xl mb-3 hover:scale-110 transition-transform duration-300"></i>
                <span class="font-bold text-sm md:text-base tracking-wide mt-1 text-white/90">Total Anggota</span>
                <span class="font-bold text-2xl md:text-3xl mt-1">{{ number_format($stats['total_members'] ?? 0) }}</span>
            </div>

            <!-- Total Pengunjung -->
            <div class="bg-blue-500 text-white rounded-xl py-8 px-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                <i class="bi bi-person-check-fill text-4xl mb-3 hover:scale-110 transition-transform duration-300"></i>
                <span class="font-bold text-sm md:text-base tracking-wide mt-1 text-white/90">Total Pengunjung</span>
                <span class="font-bold text-2xl md:text-3xl mt-1">{{ number_format($stats['total_visitors'] ?? 0) }}</span>
            </div>
        </div>
    </div>

    {{-- Tata Cara Peminjaman Section --}}
    <div class="mb-16 mt-8 bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <!-- Image Left -->
            <div class="bg-indigo-50/40 p-8 md:p-12 flex items-center justify-center relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-100/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <img src="{{ asset('images/borrow-illustration.png') }}" alt="Ilustrasi Meminjam Buku" class="relative z-10 max-w-full h-auto rounded-xl drop-shadow-xl transform group-hover:scale-[1.02] transition-transform duration-500" style="max-height: 380px;">
            </div>
            <!-- Content Right -->
            <div class="p-8 md:p-14 flex flex-col justify-center">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-3">Tata Cara Meminjam Buku</h2>
                <p class="text-slate-500 mb-10">Ikuti langkah-langkah mudah berikut untuk meminjam koleksi buku di perpustakaan kami.</p>
                
                <ul class="space-y-8 relative border-l-2 border-indigo-100 ml-4 list-none pl-0 m-0">
                    <li class="pl-10 relative">
                        <div class="w-10 h-10 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold absolute -left-[21px] top-0 border-4 border-white shadow-sm text-lg">1</div>
                        <h4 class="text-xl font-bold text-slate-800 mb-2 mt-1">Cari Buku</h4>
                        <p class="text-slate-500 text-sm leading-relaxed m-0">Gunakan fitur Katalog di website ini untuk mencari buku yang Anda inginkan dan catat lokasi raknya.</p>
                    </li>
                    <li class="pl-10 relative">
                        <div class="w-10 h-10 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold absolute -left-[21px] top-0 border-4 border-white shadow-sm text-lg">2</div>
                        <h4 class="text-xl font-bold text-slate-800 mb-2 mt-1">Ambil di Rak</h4>
                        <p class="text-slate-500 text-sm leading-relaxed m-0">Datang ke perpustakaan dan ambil buku langsung dari rak sesuai dengan informasi lokasi.</p>
                    </li>
                    <li class="pl-10 relative">
                        <div class="w-10 h-10 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold absolute -left-[21px] top-0 border-4 border-white shadow-sm text-lg">3</div>
                        <h4 class="text-xl font-bold text-slate-800 mb-2 mt-1">Serahkan ke Petugas</h4>
                        <p class="text-slate-500 text-sm leading-relaxed m-0">Bawa buku beserta Kartu Anggota Perpustakaan Anda ke meja pelayanan sirkulasi.</p>
                    </li>
                    <li class="pl-10 relative">
                        <div class="w-10 h-10 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold absolute -left-[21px] top-0 border-4 border-white shadow-sm text-lg">4</div>
                        <h4 class="text-xl font-bold text-slate-800 mb-2 mt-1">Proses Selesai</h4>
                        <p class="text-slate-500 text-sm leading-relaxed m-0">Petugas akan memproses peminjaman. Selamat membaca dan ingat batas waktu pengembalian!</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .heroSwiper {
        --swiper-theme-color: #fff;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const swiper = new Swiper('.heroSwiper', {
            slidesPerView: 1,
            loop: true,
            speed: 1200,
            effect: 'fade',
            fadeEffect: { crossFade: true },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    });
</script>
@endpush
@endsection
