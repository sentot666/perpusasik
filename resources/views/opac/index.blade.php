@extends('layouts.opac')


@section('content')
{{-- Hero Carousel Section --}}
<div id="beranda" class="relative mb-8 scroll-mt-28">
    <div class="swiper heroSwiper w-full h-[400px] md:h-[550px]">
        <div class="swiper-wrapper">
            <!-- Slide 1 -->
            <div class="swiper-slide">
                <div class="w-full h-full flex items-center bg-cover bg-no-repeat bg-center relative" style="background-image: url('{{ asset('images/carousel-1.jpg') }}?v={{ @filemtime(public_path('images/carousel-1.jpg')) }}');">
                    <div class="relative z-10 w-full px-8 md:px-16 lg:px-24">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-4 text-left text-white tracking-tighter leading-tight drop-shadow-[0_5px_5px_rgba(0,0,0,0.8)]">{{ \App\Models\Setting::get('library_name', 'Sekolah Katolik Santo Paulus') }}</h1>
                        <p class="text-xs md:text-sm lg:text-base text-white font-bold max-w-2xl text-left uppercase tracking-wider drop-shadow-[0_3px_3px_rgba(0,0,0,0.8)]">SUMBER PENGETAHUAN DAN INSPIRASI. TEMUKAN DUNIA BARU MELALUI BUKU!</p>
                    </div>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="swiper-slide">
                <div class="w-full h-full flex items-center bg-cover bg-no-repeat bg-center relative" style="background-image: url('{{ asset('images/carousel-2.jpg') }}?v={{ @filemtime(public_path('images/carousel-2.jpg')) }}');">
                    <div class="relative z-10 w-full px-8 md:px-16 lg:px-24">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-4 text-left text-white tracking-tighter leading-tight drop-shadow-[0_5px_5px_rgba(0,0,0,0.8)]">{{ \App\Models\Setting::get('library_name', 'Sekolah Katolik Santo Paulus') }}</h1>
                        <p class="text-xs md:text-sm lg:text-base text-white font-bold max-w-2xl text-left uppercase tracking-wider drop-shadow-[0_3px_3px_rgba(0,0,0,0.8)]">SUMBER PENGETAHUAN DAN INSPIRASI. TEMUKAN DUNIA BARU MELALUI BUKU!</p>
                    </div>
                </div>
            </div>
            <!-- Slide 3 -->
            <div class="swiper-slide">
                <div class="w-full h-full flex items-center bg-cover bg-no-repeat bg-center relative" style="background-image: url('{{ asset('images/carousel-3.jpg') }}?v={{ @filemtime(public_path('images/carousel-3.jpg')) }}');">
                    <div class="relative z-10 w-full px-8 md:px-16 lg:px-24">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-4 text-left text-white tracking-tighter leading-tight drop-shadow-[0_5px_5px_rgba(0,0,0,0.8)]">{{ \App\Models\Setting::get('library_name', 'Sekolah Katolik Santo Paulus') }}</h1>
                        <p class="text-xs md:text-sm lg:text-base text-white font-bold max-w-2xl text-left uppercase tracking-wider drop-shadow-[0_3px_3px_rgba(0,0,0,0.8)]">SUMBER PENGETAHUAN DAN INSPIRASI. TEMUKAN DUNIA BARU MELALUI BUKU!</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pagination -->
        <div class="swiper-pagination"></div>
    </div>

    

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-6">

    {{-- Section Heading --}}
    <div id="layanan" class="text-center mt-10 mb-8 scroll-mt-[140px]">
        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-2">{{ __('landing.quick_services') }}</h2>
        <p class="text-slate-500">{{ __('landing.quick_services_desc') }}</p>
    </div>

    {{-- Quick Menu Cards (Minimalist Circular) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12 mb-16 px-4 md:px-12">
        <!-- Katalog -->
        <a href="{{ route('opac.katalog') }}" class="flex flex-col items-center justify-center group no-underline text-center">
            <div class="w-24 h-24 md:w-28 md:h-28 rounded-full border-[3px] border-blue-600 text-blue-700 flex items-center justify-center mb-4 group-hover:bg-blue-50 group-hover:scale-105 transition-all duration-300 shadow-sm group-hover:shadow-md">
                <i class="bi bi-journals text-4xl md:text-5xl"></i>
            </div>
            <h3 class="text-base md:text-lg font-medium text-blue-700 group-hover:text-blue-800 transition-colors">{{ __('landing.catalog') }}</h3>
        </a>

        <!-- Keanggotaan -->
        <a href="{{ route('login.member') }}" class="flex flex-col items-center justify-center group no-underline text-center">
            <div class="w-24 h-24 md:w-28 md:h-28 rounded-full border-[3px] border-blue-600 text-blue-700 flex items-center justify-center mb-4 group-hover:bg-blue-50 group-hover:scale-105 transition-all duration-300 shadow-sm group-hover:shadow-md">
                <i class="bi bi-people-fill text-4xl md:text-5xl"></i>
            </div>
            <h3 class="text-base md:text-lg font-medium text-blue-700 group-hover:text-blue-800 transition-colors">{{ __('landing.membership') }}</h3>
        </a>

        <!-- Buku Kunjungan -->
        <a href="{{ route('guest-books.visitor') }}" class="flex flex-col items-center justify-center group no-underline text-center">
            <div class="w-24 h-24 md:w-28 md:h-28 rounded-full border-[3px] border-blue-600 text-blue-700 flex items-center justify-center mb-4 group-hover:bg-blue-50 group-hover:scale-105 transition-all duration-300 shadow-sm group-hover:shadow-md">
                <i class="bi bi-book text-4xl md:text-5xl"></i>
            </div>
            <h3 class="text-base md:text-lg font-medium text-blue-700 group-hover:text-blue-800 transition-colors">{{ __('landing.guestbook') }}</h3>
        </a>

        <!-- Agenda Kegiatan -->
        <a href="{{ route('opac.agenda') }}" class="flex flex-col items-center justify-center group no-underline text-center">
            <div class="w-24 h-24 md:w-28 md:h-28 rounded-full border-[3px] border-blue-600 text-blue-700 flex items-center justify-center mb-4 group-hover:bg-blue-50 group-hover:scale-105 transition-all duration-300 shadow-sm group-hover:shadow-md">
                <i class="bi bi-calendar-event text-4xl md:text-5xl"></i>
            </div>
            <h3 class="text-base md:text-lg font-medium text-blue-700 group-hover:text-blue-800 transition-colors">{{ __('landing.agenda') }}</h3>
        </a>
    </div>



    {{-- Section Heading for Stats --}}
    <div id="informasi" class="text-left mt-14 mb-6 scroll-mt-[140px]">
        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-2">{{ __('landing.stats_title') }}</h2>
        <p class="text-slate-500">{{ __('landing.stats_desc') }}</p>
    </div>

    {{-- Statistics Section (Redesigned) --}}
    <div class="bg-gradient-to-r from-teal-500 to-blue-700 rounded-2xl p-6 md:p-8 shadow-lg mb-16">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            <!-- Total Buku -->
            <div class="btn-gradient-yellow text-white rounded-xl py-8 px-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                <i class="bi bi-journals text-4xl mb-3 hover:scale-110 transition-transform duration-300"></i>
                <span class="font-bold text-sm md:text-base tracking-wide mt-1 text-white/90">{{ __('landing.total_books') }}</span>
                <span class="font-bold text-2xl md:text-3xl mt-1">{{ number_format($stats['total_books'] ?? 0) }}</span>
            </div>

            <!-- Total Eksemplar -->
            <div class="btn-gradient-green text-white rounded-xl py-8 px-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                <i class="bi bi-collection text-4xl mb-3 hover:scale-110 transition-transform duration-300"></i>
                <span class="font-bold text-sm md:text-base tracking-wide mt-1 text-white/90">{{ __('landing.total_items') }}</span>
                <span class="font-bold text-2xl md:text-3xl mt-1">{{ number_format($stats['total_items'] ?? 0) }}</span>
            </div>

            <!-- Total Anggota -->
            <div class="bg-rose-500 text-white rounded-xl py-8 px-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                <i class="bi bi-people-fill text-4xl mb-3 hover:scale-110 transition-transform duration-300"></i>
                <span class="font-bold text-sm md:text-base tracking-wide mt-1 text-white/90">{{ __('landing.total_members') }}</span>
                <span class="font-bold text-2xl md:text-3xl mt-1">{{ number_format($stats['total_members'] ?? 0) }}</span>
            </div>

            <!-- Total Pengunjung -->
            <div class="btn-gradient-blue text-white rounded-xl py-8 px-4 flex flex-col items-center justify-center text-center shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                <i class="bi bi-person-check-fill text-4xl mb-3 hover:scale-110 transition-transform duration-300"></i>
                <span class="font-bold text-sm md:text-base tracking-wide mt-1 text-white/90">{{ __('landing.total_visitors') }}</span>
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
                <img src="{{ asset('images/borrow-illustration.png') }}" alt="{{ __('Ilustrasi Meminjam Buku') }}" class="relative z-10 max-w-full h-auto rounded-xl drop-shadow-xl transform group-hover:scale-[1.02] transition-transform duration-500" style="max-height: 380px;">
            </div>
            <!-- Content Right -->
            <div class="p-8 md:p-14 flex flex-col justify-center">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-3">{{ __('landing.how_to_borrow') }}</h2>
                <p class="text-slate-500 mb-10">{{ __('landing.how_to_borrow_desc') }}</p>
                
                <ul class="space-y-8 relative border-l-2 border-indigo-100 ml-4 list-none pl-0 m-0">
                    <li class="pl-10 relative">
                        <div class="w-10 h-10 btn-gradient-blue text-white rounded-full flex items-center justify-center font-bold absolute -left-[21px] top-0 border-4 border-white shadow-sm text-lg">1</div>
                        <h4 class="text-xl font-bold text-slate-800 mb-2 mt-1">{{ __('landing.step1_title') }}</h4>
                        <p class="text-slate-500 text-sm leading-relaxed m-0">{{ __('landing.step1_desc') }}</p>
                    </li>
                    <li class="pl-10 relative">
                        <div class="w-10 h-10 btn-gradient-blue text-white rounded-full flex items-center justify-center font-bold absolute -left-[21px] top-0 border-4 border-white shadow-sm text-lg">2</div>
                        <h4 class="text-xl font-bold text-slate-800 mb-2 mt-1">{{ __('landing.step2_title') }}</h4>
                        <p class="text-slate-500 text-sm leading-relaxed m-0">{{ __('landing.step2_desc') }}</p>
                    </li>
                    <li class="pl-10 relative">
                        <div class="w-10 h-10 btn-gradient-blue text-white rounded-full flex items-center justify-center font-bold absolute -left-[21px] top-0 border-4 border-white shadow-sm text-lg">3</div>
                        <h4 class="text-xl font-bold text-slate-800 mb-2 mt-1">{{ __('landing.step3_title') }}</h4>
                        <p class="text-slate-500 text-sm leading-relaxed m-0">{{ __('landing.step3_desc') }}</p>
                    </li>
                    <li class="pl-10 relative">
                        <div class="w-10 h-10 btn-gradient-blue text-white rounded-full flex items-center justify-center font-bold absolute -left-[21px] top-0 border-4 border-white shadow-sm text-lg">4</div>
                        <h4 class="text-xl font-bold text-slate-800 mb-2 mt-1">{{ __('landing.step4_title') }}</h4>
                        <p class="text-slate-500 text-sm leading-relaxed m-0">{{ __('landing.step4_desc') }}</p>
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
                pauseOnMouseEnter: true,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    });
</script>
@endpush
@endsection

