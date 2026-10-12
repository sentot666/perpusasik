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

{{-- Hero Section (Meta Hero Band Style) --}}
<div id="beranda" class="pt-32 pb-24 md:pt-40 md:pb-28 relative bg-[#0a1317] text-white overflow-hidden">
    {{-- Soft Ambient Glows --}}
    <div class="absolute -right-24 -top-24 w-96 h-96 bg-[#0064e0]/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-[#0457cb]/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full px-6 sm:px-8 lg:px-12 flex flex-col-reverse lg:flex-row items-center justify-between gap-12 lg:gap-8 relative z-10">
        
        {{-- Left: Text, Dual CTA & Pill Search --}}
        <div class="w-full lg:w-7/12 text-center lg:text-left">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md text-white text-xs font-bold uppercase tracking-wider mb-6 border border-white/15 shadow-sm">
                <i class="bi bi-book-half text-[#0091ff]"></i> Katalog Resmi Perpustakaan
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-[1.18] tracking-tight mb-5">
                Katalog Online & Literasi <br class="hidden sm:inline">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#0091ff] via-sky-200 to-white">{{ \App\Models\Setting::get('library_name', 'Perpustakaan Makarya') }}</span>
            </h1>
            
            <p class="text-[#ced0d4] text-base md:text-lg mb-8 leading-relaxed max-w-xl">
                Telusuri ribuan buku cetak, karya referensi, dan koleksi digital dengan cepat dan mudah.
            </p>

            {{-- Dual CTA (Meta Signature: Primary White Pill + Secondary Ghost Pill) --}}
            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 mb-8">
                <a href="{{ route('opac.katalog') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full bg-white text-[#0a1317] font-bold text-sm hover:bg-[#dee3e9] transition-all no-underline shadow-lg hover:scale-[1.02]">
                    <i class="bi bi-search"></i> Jelajahi Koleksi
                </a>
                <a href="{{ route('guest-books.visitor') }}" class="inline-flex items-center gap-2 px-7 py-3 rounded-full border-2 border-white/60 text-white font-bold text-sm hover:bg-white/10 hover:border-white transition-all no-underline">
                    <i class="bi bi-pencil-square"></i> Isi Buku Tamu
                </a>
            </div>

            {{-- Meta Pill Search Bar --}}
            <div class="max-w-xl mx-auto lg:mx-0">
                <form action="{{ route('opac.katalog') }}" method="GET" class="relative z-50 w-full" x-data="autocompleteSearch('{{ addslashes(request('q')) }}')" @click.away="isOpen = false">
                    <input type="hidden" name="tab" value="koleksi">
                    <div class="bg-white rounded-full p-1.5 flex items-center shadow-2xl border border-white/20 relative">
                        <select name="search_by" class="bg-transparent border-0 border-r border-[#dee3e9] py-2.5 pl-4 pr-3 text-xs sm:text-sm font-bold text-[#1c1e21] outline-none cursor-pointer appearance-none rounded-l-full">
                            <option value="title">Judul</option>
                            <option value="author">Penulis</option>
                            <option value="subject">Topik</option>
                        </select>
                        <div class="flex-1 flex items-center bg-transparent relative">
                            <span class="pl-3 text-[#8595a4]"><i class="bi bi-search"></i></span>
                            <input type="text" name="q" placeholder="Cari judul buku, penulis, atau topik..." class="w-full bg-transparent border-none py-2.5 px-3 text-[#1c1e21] outline-none text-sm placeholder:text-[#8595a4]" x-model="query" @input.debounce.300ms="fetchSuggestions" @focus="if(query.length > 1) isOpen = true" autocomplete="off">
                            
                            <!-- Autocomplete Dropdown -->
                            <div x-show="isOpen && suggestions.length > 0" x-transition.opacity class="absolute top-full left-0 right-0 mt-3 bg-white rounded-2xl shadow-2xl border border-[#dee3e9] overflow-hidden z-[100]" style="display: none;">
                                <template x-for="item in suggestions" :key="item.text + item.type">
                                    <button type="button" @click="selectSuggestion(item)" class="w-full text-left px-5 py-3 hover:bg-[#f1f4f7] border-b border-[#dee3e9]/50 last:border-0 flex items-center justify-between group transition-colors cursor-pointer">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-[#f1f4f7] text-[#0064e0] flex items-center justify-center group-hover:bg-[#0064e0] group-hover:text-white transition-colors">
                                                <i :class="'bi ' + item.icon"></i>
                                            </div>
                                            <span class="text-[#1c1e21] font-semibold text-sm truncate" x-text="item.text"></span>
                                        </div>
                                        <span class="text-[10px] uppercase font-bold tracking-wider text-[#8595a4] group-hover:text-[#0064e0] flex-shrink-0 ml-2" x-text="item.type"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                        <button type="submit" x-ref="submitBtn" class="bg-[#0064e0] hover:bg-[#0457cb] text-white font-bold py-2.5 px-6 rounded-full transition-all whitespace-nowrap shadow-md cursor-pointer text-xs uppercase tracking-wider">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right: Illustration / Photo Showcase --}}
        <div class="w-full lg:w-5/12 flex justify-center lg:justify-end">
            <div class="relative w-full max-w-md p-4 bg-white/5 backdrop-blur-sm rounded-[32px] border border-white/10 shadow-2xl">
                <img src="{{ asset('images/borrow-illustration.png') }}" alt="Library Illustration" loading="lazy" class="w-full h-auto drop-shadow-2xl rounded-2xl object-cover">
            </div>
        </div>
    </div>
</div>

<div class="w-full px-6 sm:px-8 lg:px-12 pt-16 md:pt-20 relative z-20">

    {{-- Section: Layanan Cepat (Meta card-product-feature with 32px rounding) --}}
    <div id="layanan" class="text-center mb-12 scroll-mt-[140px]">
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-[#0064e0]/10 text-[#0064e0] text-xs font-bold uppercase tracking-wider mb-3">
            <i class="bi bi-compass"></i> Navigasi Utama
        </span>
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-[#0a1317] mb-3 tracking-tight">Layanan Cepat Perpustakaan</h2>
        <p class="text-[#5d6c7b] text-sm md:text-base max-w-xl mx-auto leading-relaxed m-0">Akses instan ke fasilitas dan layanan terbaik untuk kenyamanan literasi Anda.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
        <!-- Katalog -->
        <a href="{{ route('opac.katalog') }}" class="card-meta p-7 text-center group no-underline hover:-translate-y-1 hover:shadow-xl cursor-pointer">
            <div class="w-16 h-16 mx-auto bg-[#f1f4f7] text-[#0a1317] rounded-full flex items-center justify-center mb-5 group-hover:bg-[#0064e0] group-hover:text-white transition-all duration-300 shadow-sm">
                <i class="bi bi-journals text-2xl"></i>
            </div>
            <h4 class="text-base font-bold text-[#0a1317] mb-2 group-hover:text-[#0064e0] transition-colors">Katalog Online</h4>
            <p class="text-xs leading-relaxed text-[#5d6c7b] m-0">Telusuri seluruh koleksi buku cetak dan e-book kami.</p>
        </a>

        <!-- Keanggotaan -->
        <a href="{{ route('login.member') }}" class="card-meta p-7 text-center group no-underline hover:-translate-y-1 hover:shadow-xl cursor-pointer">
            <div class="w-16 h-16 mx-auto bg-[#f1f4f7] text-[#0a1317] rounded-full flex items-center justify-center mb-5 group-hover:bg-[#0064e0] group-hover:text-white transition-all duration-300 shadow-sm">
                <i class="bi bi-person-badge text-2xl"></i>
            </div>
            <h4 class="text-base font-bold text-[#0a1317] mb-2 group-hover:text-[#0064e0] transition-colors">Keanggotaan</h4>
            <p class="text-xs leading-relaxed text-[#5d6c7b] m-0">Masuk sebagai anggota untuk meminjam dan reservasi buku.</p>
        </a>

        <!-- Buku Tamu -->
        <a href="{{ route('guest-books.visitor') }}" class="card-meta p-7 text-center group no-underline hover:-translate-y-1 hover:shadow-xl cursor-pointer">
            <div class="w-16 h-16 mx-auto bg-[#f1f4f7] text-[#0a1317] rounded-full flex items-center justify-center mb-5 group-hover:bg-[#0064e0] group-hover:text-white transition-all duration-300 shadow-sm">
                <i class="bi bi-person-lines-fill text-2xl"></i>
            </div>
            <h4 class="text-base font-bold text-[#0a1317] mb-2 group-hover:text-[#0064e0] transition-colors">Buku Tamu</h4>
            <p class="text-xs leading-relaxed text-[#5d6c7b] m-0">Isi daftar hadir kunjungan Anda ke perpustakaan fisik.</p>
        </a>

        <!-- Agenda -->
        <a href="{{ route('opac.agenda') }}" class="card-meta p-7 text-center group no-underline hover:-translate-y-1 hover:shadow-xl cursor-pointer">
            <div class="w-16 h-16 mx-auto bg-[#f1f4f7] text-[#0a1317] rounded-full flex items-center justify-center mb-5 group-hover:bg-[#0064e0] group-hover:text-white transition-all duration-300 shadow-sm">
                <i class="bi bi-calendar-event text-2xl"></i>
            </div>
            <h4 class="text-base font-bold text-[#0a1317] mb-2 group-hover:text-[#0064e0] transition-colors">Agenda Kegiatan</h4>
            <p class="text-xs leading-relaxed text-[#5d6c7b] m-0">Jadwal kegiatan literasi, workshop, dan acara perpustakaan.</p>
        </a>
    </div>

    {{-- Section Heading for Stats --}}
    <div id="informasi" class="text-left mt-10 mb-6 scroll-mt-[140px]">
        <span class="text-xs font-bold text-[#0064e0] uppercase tracking-wider">Statistik</span>
        <h2 class="text-2xl md:text-3xl font-extrabold text-[#0a1317] mt-1 mb-2 tracking-tight">{{ __('landing.stats_title') }}</h2>
        <p class="text-[#5d6c7b] text-sm">{{ __('landing.stats_desc') }}</p>
    </div>

    {{-- Statistics Section (Meta card-icon-feature tiles) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-16">
        <!-- Total Buku -->
        <div class="bg-white rounded-2xl p-6 border border-[#dee3e9] shadow-sm flex flex-col items-center justify-center text-center hover:shadow-md transition-all">
            <div class="w-12 h-12 rounded-full bg-[#f1f4f7] text-[#0064e0] flex items-center justify-center text-xl mb-3">
                <i class="bi bi-journals"></i>
            </div>
            <span class="text-xs font-bold uppercase tracking-wider text-[#8595a4]">{{ __('landing.total_books') }}</span>
            <span class="text-2xl md:text-3xl font-black text-[#0a1317] mt-1">{{ number_format($stats['total_books'] ?? 0) }}</span>
        </div>

        <!-- Total Eksemplar -->
        <div class="bg-white rounded-2xl p-6 border border-[#dee3e9] shadow-sm flex flex-col items-center justify-center text-center hover:shadow-md transition-all">
            <div class="w-12 h-12 rounded-full bg-[#f1f4f7] text-[#31a24c] flex items-center justify-center text-xl mb-3">
                <i class="bi bi-collection"></i>
            </div>
            <span class="text-xs font-bold uppercase tracking-wider text-[#8595a4]">{{ __('landing.total_items') }}</span>
            <span class="text-2xl md:text-3xl font-black text-[#0a1317] mt-1">{{ number_format($stats['total_items'] ?? 0) }}</span>
        </div>

        <!-- Total Anggota -->
        <div class="bg-white rounded-2xl p-6 border border-[#dee3e9] shadow-sm flex flex-col items-center justify-center text-center hover:shadow-md transition-all">
            <div class="w-12 h-12 rounded-full bg-[#f1f4f7] text-[#e41e3f] flex items-center justify-center text-xl mb-3">
                <i class="bi bi-people-fill"></i>
            </div>
            <span class="text-xs font-bold uppercase tracking-wider text-[#8595a4]">{{ __('landing.total_members') }}</span>
            <span class="text-2xl md:text-3xl font-black text-[#0a1317] mt-1">{{ number_format($stats['total_members'] ?? 0) }}</span>
        </div>

        <!-- Total Pengunjung -->
        <div class="bg-white rounded-2xl p-6 border border-[#dee3e9] shadow-sm flex flex-col items-center justify-center text-center hover:shadow-md transition-all">
            <div class="w-12 h-12 rounded-full bg-[#f1f4f7] text-[#f7b928] flex items-center justify-center text-xl mb-3">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <span class="text-xs font-bold uppercase tracking-wider text-[#8595a4]">{{ __('landing.total_visitors') }}</span>
            <span class="text-2xl md:text-3xl font-black text-[#0a1317] mt-1">{{ number_format($stats['total_visitors'] ?? 0) }}</span>
        </div>
    </div>

    {{-- Section: Tata Cara (Meta 32px Split Container) --}}
    <div class="mb-16 bg-white rounded-3xl p-8 md:p-12 border border-[#dee3e9] shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Content Left -->
            <div>
                <span class="text-xs font-bold text-[#0064e0] uppercase tracking-wider">Panduan Peminjaman</span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-[#0a1317] mt-1 mb-4 tracking-tight">Tata Cara Peminjaman Buku</h2>
                <p class="text-[#5d6c7b] text-sm mb-8 leading-relaxed">
                    {{ __('landing.how_to_borrow_desc') }}
                </p>
                
                <ul class="space-y-6 list-none pl-0 m-0">
                    <li class="flex gap-4 items-start">
                        <div class="w-9 h-9 shrink-0 bg-black text-white rounded-full flex items-center justify-center font-bold text-sm shadow-sm">1</div>
                        <div>
                            <h4 class="text-base font-bold text-[#0a1317] mb-1">{{ __('landing.step1_title') }}</h4>
                            <p class="text-[#5d6c7b] text-xs leading-relaxed m-0">{{ __('landing.step1_desc') }}</p>
                        </div>
                    </li>
                    <li class="flex gap-4 items-start">
                        <div class="w-9 h-9 shrink-0 bg-black text-white rounded-full flex items-center justify-center font-bold text-sm shadow-sm">2</div>
                        <div>
                            <h4 class="text-base font-bold text-[#0a1317] mb-1">{{ __('landing.step2_title') }}</h4>
                            <p class="text-[#5d6c7b] text-xs leading-relaxed m-0">{{ __('landing.step2_desc') }}</p>
                        </div>
                    </li>
                    <li class="flex gap-4 items-start">
                        <div class="w-9 h-9 shrink-0 bg-black text-white rounded-full flex items-center justify-center font-bold text-sm shadow-sm">3</div>
                        <div>
                            <h4 class="text-base font-bold text-[#0a1317] mb-1">{{ __('landing.step3_title') }}</h4>
                            <p class="text-[#5d6c7b] text-xs leading-relaxed m-0">{{ __('landing.step3_desc') }}</p>
                        </div>
                    </li>
                </ul>
            </div>
            
            <!-- Illustration Right -->
            <div class="relative group cursor-pointer lg:pl-6">
                <div class="p-6 bg-[#f1f4f7] rounded-3xl border border-[#dee3e9] flex items-center justify-center">
                    <img src="{{ asset('images/borrow-illustration.png') }}" alt="Tata Cara" loading="lazy" class="w-full max-w-sm h-auto rounded-2xl drop-shadow-md object-contain">
                </div>
            </div>
        </div>
    </div>

    {{-- Section: Buku Terbaru --}}
    <div id="buku-terbaru" class="text-center mb-10 scroll-mt-[140px]">
        <span class="text-xs font-bold text-[#0064e0] uppercase tracking-wider">Koleksi Baru</span>
        <h2 class="text-2xl md:text-3xl font-extrabold text-[#0a1317] mt-1 mb-2 tracking-tight">Koleksi Buku Terbaru</h2>
        <p class="text-[#5d6c7b] text-sm">Temukan buku-buku yang baru saja tiba di perpustakaan kami.</p>
    </div>

    @php
        $latestBooks = \App\Models\Book::with('authors')->where('is_active', true)->latest()->take(3)->get();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        @forelse($latestBooks as $book)
            <div class="bg-white rounded-3xl border border-[#dee3e9] overflow-hidden flex flex-col h-full hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                <div class="h-56 bg-[#f1f4f7] flex items-center justify-center overflow-hidden border-b border-[#dee3e9] relative p-4">
                    @if($book->cover_image && Storage::disk('public')->exists($book->cover_image))
                        <img src="{{ Storage::url($book->cover_image) }}" alt="Cover" loading="lazy" class="max-h-full w-auto object-contain rounded-lg shadow-sm group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-20 h-28 bg-white border border-[#dee3e9] rounded-lg shadow-sm flex items-center justify-center text-[#8595a4]">
                            <i class="bi bi-book text-3xl"></i>
                        </div>
                    @endif
                    <div class="absolute top-3 right-3 bg-black/80 backdrop-blur text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                        {{ $book->collection_type }}
                    </div>
                </div>
                <div class="p-6 flex-1 flex flex-col">
                    <div class="text-[11px] text-[#8595a4] font-semibold uppercase tracking-wider mb-2">
                        {{ $book->created_at->format('d M Y') }}
                    </div>
                    <h4 class="text-base font-bold text-[#0a1317] mb-2 line-clamp-2 group-hover:text-[#0064e0] transition-colors leading-snug">
                        <a href="{{ route('opac.show', $book) }}" class="no-underline text-inherit">
                            {{ $book->title }}
                        </a>
                    </h4>
                    <p class="text-xs text-[#5d6c7b] line-clamp-2 mb-4 flex-1 leading-relaxed">
                        {{ $book->abstract ?? 'Buku ini tersedia di perpustakaan. Klik untuk melihat detail eksemplar.' }}
                    </p>
                    <div class="text-xs text-[#8595a4] border-t border-[#dee3e9] pt-3 mt-auto flex items-center justify-between">
                        <span class="truncate pr-2"><i class="bi bi-person mr-1"></i> {{ $book->authors->pluck('name')->join(', ') ?: 'Anonim' }}</span>
                        <a href="{{ route('opac.show', $book) }}" class="text-[#0064e0] font-bold hover:underline shrink-0 text-xs no-underline">Detail &rarr;</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-[#8595a4]">
                Belum ada buku terbaru.
            </div>
        @endforelse
    </div>
    
    <div class="text-center mb-16">
        <a href="{{ route('opac.katalog') }}" class="btn-pill-primary inline-flex">
            <i class="bi bi-grid-3x3-gap"></i> Lihat Semua Koleksi
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
