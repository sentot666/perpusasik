@extends('layouts.opac')

@push('styles')
<style>
    /* Particles Background */
    #beranda {
        position: relative;
        background: linear-gradient(135deg, #0a0f2e 0%, #152664 50%, #0d1b4b 100%);
        overflow: hidden;
    }
    
    .particles-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        opacity: 0.5;
        background-image: 
            radial-gradient(circle at 15% 50%, rgba(255,255,255,0.08) 0%, transparent 50%),
            radial-gradient(circle at 85% 30%, rgba(255,255,255,0.08) 0%, transparent 50%);
    }

    /* Mask Shape at bottom of hero */
    .mask-a {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 80px;
        background-color: #eff6ff; /* Matches bg-blue-50 of body */
        clip-path: ellipse(100% 100% at 50% 100%);
        z-index: 5;
    }

    .hero-content {
        position: relative;
        z-index: 10;
    }

    .hero-img {
        animation: floatImg 6s ease-in-out infinite;
    }
    
    @keyframes floatImg {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
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

{{-- Hero Section (Pusdiklat Style) --}}
<div id="beranda" class="pt-32 pb-40 md:pt-40 md:pb-48">
    <div class="particles-bg"></div>
    <div class="mask-a"></div>

    <div class="hero-content max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 flex flex-col-reverse lg:flex-row items-center gap-12 lg:gap-8">
        
        {{-- Left: Text & Search --}}
        <div class="w-full lg:w-1/2 text-center lg:text-left">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-6" data-aos="fade-up">
                Selamat Datang di Katalog Online<br />
                <span class="text-indigo-300">{{ \App\Models\Setting::get('library_name', 'Perpustakaan Sekolah Katolik Santo Paulus') }}</span>
            </h1>
            <p class="text-white/80 text-lg mb-8" data-aos="fade-up" data-aos-delay="100">
                Temukan koleksi buku cetak, e-book interaktif, dan referensi akademik terbaik.
            </p>

            {{-- Search Bar Integrated --}}
            <div data-aos="fade-up" data-aos-delay="200" class="max-w-xl mx-auto lg:mx-0">
                <form action="{{ route('opac.katalog') }}" method="GET" class="relative z-50 w-full" x-data="autocompleteSearch('{{ addslashes(request('q')) }}')" @click.away="isOpen = false">
                    <input type="hidden" name="tab" value="koleksi">
                    <div class="flex flex-col sm:flex-row items-stretch bg-white rounded-xl overflow-visible shadow-lg relative border border-white/20">
                        <select name="search_by" class="bg-transparent border-0 sm:border-r border-slate-200 py-3 px-4 text-sm font-semibold text-slate-600 outline-none cursor-pointer appearance-none rounded-t-xl sm:rounded-none sm:rounded-l-xl">
                            <option value="title">Judul</option>
                            <option value="author">Penulis</option>
                            <option value="subject">Topik</option>
                        </select>
                        <div class="flex-1 flex items-center bg-transparent relative">
                            <span class="pl-4 text-slate-400 hidden sm:block"><i class="bi bi-search"></i></span>
                            <input type="text" name="q" placeholder="Cari buku, penulis, atau topik..." class="w-full bg-transparent border-none py-3 px-3 text-slate-700 outline-none text-sm" x-model="query" @input.debounce.300ms="fetchSuggestions" @focus="if(query.length > 1) isOpen = true" autocomplete="off">
                            
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
                        <button type="submit" x-ref="submitBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 transition-colors whitespace-nowrap rounded-b-xl sm:rounded-none sm:rounded-r-xl">
                            <i class="bi bi-search sm:hidden mr-2"></i>Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right: Illustration --}}
        <div class="w-full lg:w-1/2 flex justify-center lg:justify-end" data-aos="fade-left">
            <img src="{{ asset('images/borrow-illustration.png') }}" alt="Library Illustration" loading="lazy" class="hero-img w-full max-w-md drop-shadow-2xl mix-blend-screen opacity-90 rounded-2xl">
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-6 -mt-10 relative z-20">

    {{-- Section: Inovasi / Layanan Cepat --}}
    <div id="layanan" class="text-center mb-12 scroll-mt-[140px]">
        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-2 uppercase">Layanan Cepat</h2>
        <div class="w-16 h-1 bg-indigo-600 mx-auto rounded-full mb-4"></div>
        <p class="text-slate-500">Akses cepat ke berbagai fitur utama perpustakaan.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-20">
        <!-- Katalog -->
        <a href="{{ route('opac.katalog') }}" class="feature-card p-6 text-center group no-underline">
            <div class="w-20 h-20 mx-auto bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                <i class="bi bi-journals text-3xl"></i>
            </div>
            <h4 class="text-lg font-bold text-slate-800 mb-2 group-hover:text-indigo-600">Katalog Online</h4>
            <p class="text-sm text-slate-500 m-0">Telusuri seluruh koleksi buku cetak dan e-book kami.</p>
        </a>

        <!-- Keanggotaan -->
        <a href="{{ route('login.member') }}" class="feature-card p-6 text-center group no-underline">
            <div class="w-20 h-20 mx-auto bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                <i class="bi bi-people-fill text-3xl"></i>
            </div>
            <h4 class="text-lg font-bold text-slate-800 mb-2 group-hover:text-blue-600">Keanggotaan</h4>
            <p class="text-sm text-slate-500 m-0">Masuk sebagai anggota untuk meminjam dan mengunduh buku.</p>
        </a>

        <!-- Buku Tamu -->
        <a href="{{ route('guest-books.visitor') }}" class="feature-card p-6 text-center group no-underline">
            <div class="w-20 h-20 mx-auto bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                <i class="bi bi-person-lines-fill text-3xl"></i>
            </div>
            <h4 class="text-lg font-bold text-slate-800 mb-2 group-hover:text-emerald-600">Buku Tamu</h4>
            <p class="text-sm text-slate-500 m-0">Isi daftar hadir kunjungan Anda ke perpustakaan fisik.</p>
        </a>

        <!-- Agenda -->
        <a href="{{ route('opac.agenda') }}" class="feature-card p-6 text-center group no-underline">
            <div class="w-20 h-20 mx-auto bg-amber-50 text-amber-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
                <i class="bi bi-calendar-event text-3xl"></i>
            </div>
            <h4 class="text-lg font-bold text-slate-800 mb-2 group-hover:text-amber-600">Agenda Kegiatan</h4>
            <p class="text-sm text-slate-500 m-0">Jadwal kegiatan, literasi, dan acara perpustakaan.</p>
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
                <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-3 uppercase">Tata Cara Peminjaman</h2>
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
        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-2 uppercase">Buku Terbaru</h2>
        <div class="w-16 h-1 bg-indigo-600 mx-auto rounded-full mb-4"></div>
        <p class="text-slate-500">Koleksi buku terbaru yang baru saja ditambahkan ke perpustakaan.</p>
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
                        {{ $book->abstract ?? 'Tidak ada abstrak.' }}
                    </p>
                    <div class="text-xs text-slate-500 border-t border-slate-100 pt-3 mt-auto">
                        <i class="bi bi-person mr-1"></i> 
                        {{ $book->authors->pluck('name')->join(', ') ?: 'Anonim' }}
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-slate-500">
                Belum ada buku terbaru.
            </div>
        @endforelse
    </div>
    
    <div class="text-center mb-16">
        <a href="{{ route('opac.katalog') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-8 rounded-full shadow-md transition-colors no-underline text-sm uppercase tracking-wider">
            Lihat Semua Koleksi
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
