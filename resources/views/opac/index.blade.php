@extends('layouts.opac')

@push('styles')
<style>
    #beranda {
        background: linear-gradient(135deg, #0a0f2e 0%, #0d1b4b 30%, #0e2260 55%, #091850 80%, #07122a 100%);
        position: relative;
        overflow: hidden;
    }
    #beranda::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 60% 50% at 20% 50%, rgba(37,99,235,0.18) 0%, transparent 70%),
            radial-gradient(ellipse 40% 60% at 75% 30%, rgba(139,92,246,0.12) 0%, transparent 60%);
        pointer-events: none;
    }

    /* Animated floating dots */
    .hero-dots {
        position: absolute;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
    }
    .hero-dots span {
        position: absolute;
        border-radius: 50%;
        background: rgba(96,165,250,0.15);
        animation: floatDot linear infinite;
    }
    @keyframes floatDot {
        0%   { transform: translateY(0) scale(1); opacity: 0.6; }
        50%  { transform: translateY(-30px) scale(1.2); opacity: 1; }
        100% { transform: translateY(0) scale(1); opacity: 0.6; }
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(96,165,250,0.35);
        border-radius: 999px;
        padding: 5px 14px;
        font-size: 0.78rem;
        color: #93c5fd;
        font-weight: 500;
        letter-spacing: 0.02em;
        backdrop-filter: blur(6px);
        margin-bottom: 1.2rem;
    }
    .hero-badge .dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: #60a5fa;
        animation: pulse 2s ease-in-out infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(0.8); }
    }

    .hero-headline {
        font-size: clamp(2rem, 5vw, 3.2rem);
        font-weight: 800;
        line-height: 1.15;
        color: #fff;
        margin-bottom: 1.1rem;
        letter-spacing: -0.02em;
    }
    .hero-headline .highlight {
        color: #60a5fa;
    }

    /* Old search bar CSS removed */

    .hero-tags {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 0.82rem;
    }
    .hero-tags .label { color: #94a3b8; }
    .hero-tag {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(96,165,250,0.2);
        border-radius: 999px;
        padding: 4px 14px;
        color: #93c5fd;
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s, color 0.2s;
        text-decoration: none;
    }
    .hero-tag:hover {
        background: rgba(59,130,246,0.2);
        border-color: #60a5fa;
        color: #fff;
        text-decoration: none;
    }

    .hero-illustration {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .hero-illustration img {
        width: 100%;
        max-width: 500px;
        border-radius: 20px;
        filter: drop-shadow(0 30px 60px rgba(37,99,235,0.4)) drop-shadow(0 0 80px rgba(139,92,246,0.2));
        animation: heroFloat 5s ease-in-out infinite;
    }
    @keyframes heroFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-12px); }
    }
    .hero-glow-ring {
        position: absolute;
        width: 420px;
        height: 420px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(37,99,235,0.15) 0%, transparent 70%);
        pointer-events: none;
        animation: glowPulse 4s ease-in-out infinite;
    }
    @keyframes glowPulse {
        0%, 100% { transform: scale(1); opacity: 0.8; }
        50% { transform: scale(1.1); opacity: 1; }
    }
</style>
@endpush

@section('content')
{{-- Hero Section --}}
<div id="beranda" class="relative scroll-mt-28 py-16 md:py-24">
    <div class="hero-dots">
        <span style="width:8px;height:8px;left:10%;top:20%;animation-duration:6s;animation-delay:0s;"></span>
        <span style="width:5px;height:5px;left:25%;top:70%;animation-duration:8s;animation-delay:1s;"></span>
        <span style="width:10px;height:10px;left:80%;top:15%;animation-duration:7s;animation-delay:2s;"></span>
        <span style="width:6px;height:6px;left:60%;top:80%;animation-duration:5s;animation-delay:0.5s;"></span>
        <span style="width:4px;height:4px;left:45%;top:40%;animation-duration:9s;animation-delay:3s;"></span>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="max-w-4xl mx-auto flex flex-col items-center text-center">

            {{-- Text Content --}}
            <div class="w-full">
                <div class="hero-badge mx-auto">
                    <span class="dot"></span>
                    <i class="bi bi-stars"></i>
                    Katalog Online OPAC & E-Book Terintegrasi
                </div>

                <h1 class="hero-headline mx-auto">
                    Jelajahi Dunia <span class="highlight">Pengetahuan</span><br>Tanpa Batas
                </h1>

                <p class="text-slate-400 text-sm md:text-base mb-8 leading-relaxed max-w-2xl mx-auto">
                    Temukan koleksi buku cetak, e-book interaktif, dan referensi akademik terbaik di
                    <strong class="text-slate-200">{{ \App\Models\Setting::get('library_name', 'Perpustakaan Sekolah Katolik Santo Paulus') }}</strong>.
                </p>

                {{-- Search Bar --}}
                <form action="{{ route('opac.katalog') }}" method="GET" class="mb-6 relative z-50 w-full" x-data="autocompleteSearch('{{ addslashes(request('q')) }}')" @click.away="isOpen = false">
                    <input type="hidden" name="tab" value="koleksi">
                    <div class="flex flex-col sm:flex-row items-stretch bg-white/10 border border-blue-400/30 rounded-xl overflow-visible backdrop-blur-md focus-within:border-blue-400 focus-within:ring-2 focus-within:ring-blue-400/20 transition-all shadow-lg relative">
                        <select name="search_by" class="bg-transparent border-0 sm:border-r border-slate-500/50 py-4 px-5 text-sm font-semibold text-blue-100 outline-none cursor-pointer appearance-none rounded-l-xl">
                            <option value="title" class="text-slate-800">Judul</option>
                            <option value="author" class="text-slate-800">Penulis</option>
                            <option value="subject" class="text-slate-800">Topik</option>
                        </select>
                        <div class="flex-1 flex items-center bg-transparent relative">
                            <span class="pl-5 text-slate-300 hidden sm:block"><i class="bi bi-search"></i></span>
                            <input type="text" name="q" placeholder="Cari buku, penulis, atau topik..." class="w-full bg-transparent border-none py-4 px-3 text-white outline-none text-sm placeholder:text-slate-300/70" x-model="query" @input.debounce.300ms="fetchSuggestions" @focus="if(query.length > 1) isOpen = true" autocomplete="off">
                            
                            <!-- Autocomplete Dropdown -->
                            <div x-show="isOpen && suggestions.length > 0" x-transition.opacity class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-slate-100 overflow-hidden z-[100]" style="display: none;">
                                <template x-for="item in suggestions" :key="item.text + item.type">
                                    <button type="button" @click="selectSuggestion(item)" class="w-full text-left px-4 py-3 hover:bg-slate-50 border-b border-slate-50 last:border-0 flex items-center justify-between group transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                                <i :class="'bi ' + item.icon"></i>
                                            </div>
                                            <span class="text-slate-700 font-medium text-sm truncate" x-text="item.text"></span>
                                        </div>
                                        <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 group-hover:text-indigo-400 flex-shrink-0 ml-2" x-text="item.type"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                        <button type="submit" x-ref="submitBtn" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 px-8 transition-colors whitespace-nowrap rounded-r-xl">
                            <i class="bi bi-search sm:hidden mr-2"></i>Cari
                        </button>
                    </div>
                </form>

                {{-- Popular Tags --}}
                <div class="hero-tags mt-4">
                    <span class="label">Pencarian Populer:</span>
                    <a href="{{ route('opac.katalog', ['q' => 'Novel', 'tab' => 'koleksi']) }}" class="hero-tag">Novel</a>
                    <a href="{{ route('opac.katalog', ['q' => 'Pendidikan', 'tab' => 'koleksi']) }}" class="hero-tag">Pendidikan</a>
                    <a href="{{ route('opac.katalog', ['q' => 'Sains', 'tab' => 'koleksi']) }}" class="hero-tag">Sains</a>
                    <a href="{{ route('opac.katalog', ['q' => 'Sejarah', 'tab' => 'koleksi']) }}" class="hero-tag">Sejarah</a>
                    <a href="{{ route('opac.katalog', ['q' => 'Agama', 'tab' => 'koleksi']) }}" class="hero-tag">Agama</a>
                </div>
            </div>
        </div>
    </div>
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

@endsection

@push('scripts')
<script>
    function autocompleteSearch(initialQuery = '') {
        return {
            query: initialQuery,
            suggestions: [],
            isOpen: false,
            fetchSuggestions() {
                if (this.query.length < 2) {
                    this.suggestions = [];
                    this.isOpen = false;
                    return;
                }
                
                fetch(`/opac/autocomplete?q=${encodeURIComponent(this.query)}`)
                    .then(res => res.json())
                    .then(data => {
                        this.suggestions = data;
                        this.isOpen = data.length > 0;
                    })
                    .catch(err => {
                        console.error('Error fetching autocomplete:', err);
                    });
            },
            selectSuggestion(item) {
                if (item.url) {
                    window.location.href = item.url;
                    return;
                }
                
                this.query = item.text;
                this.isOpen = false;
                
                this.$nextTick(() => {
                    this.$refs.submitBtn.click();
                });
            }
        }
    }
</script>
@endpush
