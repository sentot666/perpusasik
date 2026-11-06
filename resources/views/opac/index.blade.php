@extends('layouts.opac')

@push('styles')
<style>
    /* Hero Banner Background (Vibrant Blue/Cyan Vector Aesthetic) */
    #beranda {
        position: relative;
        background: linear-gradient(130deg, #0099ff 0%, #006be6 28%, #1435a8 60%, #08113b 100%);
        overflow: hidden;
    }
    
    .hero-svg-bg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 10;
    }

    /* Feature Cards */
    .feature-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        background: white;
        border: 1px solid #f1f5f9;
    }
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: #e2e8f0;
    }
    
    /* Blog/Book Cards */
    .blog-card {
        border-radius: 12px;
        overflow: hidden;
        background: white;
        transition: all 0.3s ease;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')

{{-- Hero Section (Vibrant Vector Wave Style) --}}
<div id="beranda" class="pt-32 pb-36 md:pt-40 md:pb-44">
    <!-- SVG Vector Background matching reference design -->
    <svg class="hero-svg-bg" viewBox="0 0 1440 600" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <!-- Top-Left Ambient Light Glow -->
            <radialGradient id="heroCyanGlow" cx="20%" cy="30%" r="55%">
                <stop offset="0%" stop-color="#00f0ff" stop-opacity="0.45" />
                <stop offset="50%" stop-color="#0084ff" stop-opacity="0.12" />
                <stop offset="100%" stop-color="#0084ff" stop-opacity="0" />
            </radialGradient>
            
            <!-- Curved Glass Ribbon Gradient -->
            <linearGradient id="heroGlassWave" x1="0" y1="0" x2="600" y2="500" gradientUnits="userSpaceOnUse">
                <stop offset="0%" stop-color="#ffffff" stop-opacity="0.22" />
                <stop offset="40%" stop-color="#00f2fe" stop-opacity="0.12" />
                <stop offset="100%" stop-color="#0077ff" stop-opacity="0" />
            </linearGradient>

            <!-- Cyan Accent Wave Gradient -->
            <linearGradient id="heroAccentCyan" x1="0" y1="300" x2="800" y2="600" gradientUnits="userSpaceOnUse">
                <stop offset="0%" stop-color="#00f0ff" stop-opacity="0.45" />
                <stop offset="50%" stop-color="#0099ff" stop-opacity="0.22" />
                <stop offset="100%" stop-color="#0055ff" stop-opacity="0" />
            </linearGradient>

            <!-- Filters for glowing orbs -->
            <filter id="heroSoftBlur" x="-50%" y="-50%" width="200%" height="200%">
                <feGaussianBlur stdDeviation="35" />
            </filter>
            <filter id="heroBokehBlur" x="-50%" y="-50%" width="200%" height="200%">
                <feGaussianBlur stdDeviation="16" />
            </filter>
        </defs>

        <!-- Ambient Cyan Glow in Left Half -->
        <rect width="1440" height="600" fill="url(#heroCyanGlow)" />

        <!-- Soft Glowing Bokeh Orbs -->
        <circle cx="280" cy="220" r="160" fill="#00d8ff" opacity="0.32" filter="url(#heroSoftBlur)" />
        <circle cx="480" cy="180" r="130" fill="#38bdf8" opacity="0.22" filter="url(#heroSoftBlur)" />
        <circle cx="160" cy="380" r="110" fill="#00f0ff" opacity="0.35" filter="url(#heroSoftBlur)" />

        <!-- Translucent Bokeh Bubbles -->
        <circle cx="360" cy="240" r="55" fill="white" opacity="0.12" filter="url(#heroBokehBlur)" />
        <circle cx="220" cy="180" r="40" fill="white" opacity="0.15" filter="url(#heroBokehBlur)" />
        <circle cx="460" cy="320" r="70" fill="white" opacity="0.08" filter="url(#heroBokehBlur)" />

        <!-- Sweeping Curved Glass Ribbon from top-left -->
        <path d="M 0 0 L 420 0 C 370 180, 260 320, 0 440 Z" fill="url(#heroGlassWave)" />
        <path d="M 0 60 C 260 120, 340 280, 220 500 C 140 600, 0 600, 0 600 Z" fill="white" opacity="0.06" />

        <!-- Accent Cyan Ribbon above bottom-left wave -->
        <path d="M 0 350 C 120 380, 280 490, 500 550 C 700 600, 1050 600, 1440 600 L 0 600 Z" fill="url(#heroAccentCyan)" />

        <!-- Connected White Bubble Motif (top-left) -->
        <g opacity="0.85">
            <circle cx="130" cy="90" r="24" fill="white" />
            <circle cx="108" cy="72" r="12" fill="white" />
            <circle cx="152" cy="104" r="8" fill="white" opacity="0.8" />
        </g>

        <!-- Modern Geometric / Tech Accent Marks (Plus, Corner brackets) -->
        <g opacity="0.22" fill="white" stroke="white" stroke-width="2">
            <path d="M 90 220 L 102 220 M 96 214 L 96 226" />
            <path d="M 380 120 L 392 120 M 386 114 L 386 126" />
            <path d="M 520 280 L 532 280 M 526 274 L 526 286" />
            <path d="M 1250 140 L 1262 140 M 1256 134 L 1256 146" />
            <path d="M 1120 340 L 1132 340 M 1126 334 L 1126 346" />
            
            <path d="M 80 140 L 80 148 L 88 148" fill="none" stroke-width="2" />
            <path d="M 320 80 L 320 88 L 328 88" fill="none" stroke-width="2" />
            <path d="M 1180 80 L 1180 88 L 1188 88" fill="none" stroke-width="2" />
            <path d="M 1320 240 L 1320 248 L 1328 248" fill="none" stroke-width="2" />
        </g>

        <!-- Main Swooping Bottom Wave matching the reference image bottom-left curve into page content -->
        <path d="M 0 400 C 100 450, 260 550, 520 585 C 720 605, 1100 600, 1440 600 L 0 600 Z" fill="#eff6ff" />
    </svg>

    <div class="hero-content w-full max-w-4xl mx-auto px-6 sm:px-8 text-center flex flex-col items-center justify-center">
        
        <h1 class="text-3xl md:text-5xl lg:text-5xl font-bold text-white leading-tight mb-5" data-aos="fade-up">
            {{ __('Selamat Datang di') }}<br />
            <span class="text-cyan-200">{{ \App\Models\Setting::get('library_name', __('landing.school_library_full')) }}</span>
        </h1>
        <p class="text-white/80 text-base md:text-lg mb-8 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            {{ __('Temukan koleksi buku cetak, e-book interaktif, dan referensi akademik terbaik.') }}
        </p>

        {{-- Search Bar Integrated --}}
        <div data-aos="fade-up" data-aos-delay="200" class="w-full max-w-2xl mx-auto">
            <form action="{{ route('opac.katalog') }}" method="GET" class="relative z-50 w-full" x-data="autocompleteSearch('{{ addslashes(request('q')) }}')" @click.away="isOpen = false">
                <input type="hidden" name="tab" value="koleksi">
                <div class="flex flex-col sm:flex-row items-stretch bg-white rounded-xl overflow-visible shadow-2xl relative border border-white/20">
                    <select name="search_by" class="bg-transparent border-0 sm:border-r border-slate-200 py-3.5 px-4 text-sm font-semibold text-slate-600 outline-none cursor-pointer appearance-none rounded-t-xl sm:rounded-none sm:rounded-l-xl">
                        <option value="title">{{ __('Judul') }}</option>
                        <option value="author">{{ __('Penulis') }}</option>
                        <option value="subject">{{ __('Topik') }}</option>
                    </select>
                    <div class="flex-1 flex items-center bg-transparent relative">
                        <span class="pl-4 text-slate-400 hidden sm:block"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" placeholder="{{ __('Cari buku, penulis, atau topik...') }}" class="w-full bg-transparent border-none py-3.5 px-3 text-slate-700 outline-none text-sm" x-model="query" @input.debounce.300ms="fetchSuggestions" @focus="if(query.length > 1) isOpen = true" autocomplete="off">
                        
                        <!-- Autocomplete Dropdown -->
                        <div x-show="isOpen && suggestions.length > 0" x-transition.opacity class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-slate-100 overflow-hidden z-[100] text-left" style="display: none;">
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
                    <button type="submit" x-ref="submitBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-7 transition-colors whitespace-nowrap rounded-b-xl sm:rounded-none sm:rounded-r-xl">
                        <i class="bi bi-search sm:hidden mr-2"></i>{{ __('Cari') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="w-full px-6 sm:px-8 lg:px-12 mt-12 md:mt-20 relative z-20">

    {{-- Section: Inovasi / Layanan Cepat --}}
    <div id="layanan" class="text-center mb-12 scroll-mt-[140px]">
        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-2 uppercase">{{ __('Layanan Cepat') }}</h2>
        <div class="w-16 h-1 bg-indigo-600 mx-auto rounded-full mb-4"></div>
        <p class="text-slate-500">{{ __('Akses cepat ke berbagai fitur utama perpustakaan.') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-20">
        <!-- Katalog -->
        <a href="{{ route('opac.katalog') }}" class="feature-card p-6 text-center group no-underline">
            <div class="w-20 h-20 mx-auto bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                <i class="bi bi-journals text-3xl"></i>
            </div>
            <h4 class="text-lg font-bold text-slate-800 mb-2 group-hover:text-indigo-600">{{ __('Katalog Online') }}</h4>
            <p class="text-sm text-slate-500 m-0">{{ __('Telusuri seluruh koleksi buku cetak dan e-book kami.') }}</p>
        </a>

        <!-- Keanggotaan -->
        <a href="{{ route('login.member') }}" class="feature-card p-6 text-center group no-underline">
            <div class="w-20 h-20 mx-auto bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                <i class="bi bi-people-fill text-3xl"></i>
            </div>
            <h4 class="text-lg font-bold text-slate-800 mb-2 group-hover:text-blue-600">{{ __('Keanggotaan') }}</h4>
            <p class="text-sm text-slate-500 m-0">{{ __('Masuk sebagai anggota untuk meminjam dan mengunduh buku.') }}</p>
        </a>

        <!-- Buku Tamu -->
        <a href="{{ route('guest-books.visitor') }}" class="feature-card p-6 text-center group no-underline">
            <div class="w-20 h-20 mx-auto bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                <i class="bi bi-person-lines-fill text-3xl"></i>
            </div>
            <h4 class="text-lg font-bold text-slate-800 mb-2 group-hover:text-emerald-600">{{ __('Buku Tamu') }}</h4>
            <p class="text-sm text-slate-500 m-0">{{ __('Isi daftar hadir kunjungan Anda ke perpustakaan fisik.') }}</p>
        </a>

        <!-- Agenda -->
        <a href="{{ route('opac.agenda') }}" class="feature-card p-6 text-center group no-underline">
            <div class="w-20 h-20 mx-auto bg-amber-50 text-amber-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
                <i class="bi bi-calendar-event text-3xl"></i>
            </div>
            <h4 class="text-lg font-bold text-slate-800 mb-2 group-hover:text-amber-600">{{ __('Agenda Kegiatan') }}</h4>
            <p class="text-sm text-slate-500 m-0">{{ __('Jadwal kegiatan, literasi, dan acara perpustakaan.') }}</p>
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

    {{-- Section: Tata Cara (Split Screen Style) --}}
    <div class="mb-20 bg-white rounded-2xl p-8 md:p-12 overflow-hidden border border-slate-100 shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Content Left -->
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-3 uppercase">{{ __('landing.how_to_borrow') }}</h2>
                <div class="w-16 h-1 bg-indigo-600 rounded-full mb-6"></div>
                <p class="text-slate-600 mb-8 leading-relaxed">
                    {{ __('landing.how_to_borrow_desc') }}
                </p>
                
                <ul class="space-y-6 list-none pl-0 m-0">
                    <li class="flex gap-4">
                        <div class="w-10 h-10 shrink-0 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-bold text-lg">1</div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-800 mb-1 mt-1">{{ __('landing.step1_title') }}</h4>
                            <p class="text-slate-500 text-sm m-0">{{ __('landing.step1_desc') }}</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <div class="w-10 h-10 shrink-0 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-bold text-lg">2</div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-800 mb-1 mt-1">{{ __('landing.step2_title') }}</h4>
                            <p class="text-slate-500 text-sm m-0">{{ __('landing.step2_desc') }}</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <div class="w-10 h-10 shrink-0 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-bold text-lg">3</div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-800 mb-1 mt-1">{{ __('landing.step3_title') }}</h4>
                            <p class="text-slate-500 text-sm m-0">{{ __('landing.step3_desc') }}</p>
                        </div>
                    </li>
                </ul>
            </div>
            
            <!-- Video/Image Right -->
            <div class="relative group cursor-pointer lg:pl-10">
                <div class="absolute inset-0 bg-indigo-600/5 rounded-2xl transform rotate-3 group-hover:rotate-1 transition-transform duration-300"></div>
                <img src="{{ asset('images/borrow-illustration.png') }}" alt="Tata Cara" loading="lazy" class="relative z-10 w-full h-auto rounded-2xl shadow-lg bg-slate-50 object-cover object-center p-8 border border-slate-100">
            </div>
        </div>
    </div>

    {{-- Section: Berita / Buku Terbaru --}}
    <div id="buku-terbaru" class="text-center mb-12 scroll-mt-[140px]">
        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-2 uppercase">{{ __('Buku Terbaru') }}</h2>
        <div class="w-16 h-1 bg-indigo-600 mx-auto rounded-full mb-4"></div>
        <p class="text-slate-500">{{ __('Koleksi buku terbaru yang baru saja ditambahkan ke perpustakaan.') }}</p>
    </div>

    @php
        $latestBooks = \App\Models\Book::with('authors')->where('is_active', true)->latest()->take(3)->get();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        @forelse($latestBooks as $book)
            <div class="blog-card flex flex-col h-full">
                <div class="h-48 bg-slate-100 flex items-center justify-center overflow-hidden border-b border-slate-100 relative">
                    @if($book->cover_image && Storage::disk('public')->exists($book->cover_image))
                        <img src="{{ Storage::url($book->cover_image) }}" alt="Cover" loading="lazy" class="w-full h-full object-cover">
                    @else
                        <i class="bi bi-book text-5xl text-slate-300"></i>
                    @endif
                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur text-xs font-bold px-2 py-1 rounded text-indigo-600 shadow-sm">
                        {{ $book->collection_type }}
                    </div>
                </div>
                <div class="p-6 flex-1 flex flex-col">
                    <div class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-2">
                        {{ $book->created_at->format('d M Y') }}
                    </div>
                    <h4 class="text-lg font-bold text-slate-800 mb-3 line-clamp-2">
                        <a href="{{ route('opac.show', $book) }}" class="hover:text-indigo-600 transition-colors no-underline text-inherit">
                            {{ $book->title }}
                        </a>
                    </h4>
                    <p class="text-sm text-slate-500 line-clamp-2 mb-4 flex-1">
                        {{ $book->abstract ?? __('Tidak ada abstrak.') }}
                    </p>
                    <div class="text-xs text-slate-500 border-t border-slate-100 pt-3 mt-auto">
                        <i class="bi bi-person mr-1"></i> 
                        {{ $book->authors->pluck('name')->join(', ') ?: __('Anonim') }}
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-slate-500">
                {{ __('Belum ada buku terbaru.') }}
            </div>
        @endforelse
    </div>
    
    <div class="text-center mb-16">
        <a href="{{ route('opac.katalog') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-8 rounded-full shadow-md transition-colors no-underline text-sm uppercase tracking-wider">
            {{ __('Lihat Semua Koleksi') }}
        </a>
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
