<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ \App\Models\Setting::get('library_name', config('app.name', 'Makarya')) }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            /* Sembunyikan elemen UI yang tidak perlu dicetak */
            #sidebar, header, footer, .no-print, form, button, .pagination {
                display: none !important;
            }
            /* Hilangkan background dan margin utama */
            body, .min-h-screen, .bg-blue-50 {
                background: white !important;
                min-height: auto !important;
            }
            .lg\:ml-72 {
                margin-left: 0 !important;
            }
            /* Pastikan header cetak muncul */
            .print-header {
                display: block !important;
            }
            /* Hilangkan bayangan dan garis tepi luar agar bersih */
            .shadow-sm, .shadow-md, .shadow-lg, .border {
                box-shadow: none !important;
                border: none !important;
            }
            /* Reset overflows for printing */
            .overflow-hidden, .overflow-x-auto, .overflow-y-auto {
                overflow: visible !important;
            }
            .whitespace-nowrap {
                white-space: normal !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-blue-50 text-slate-800 antialiased" style="font-family: 'Inter', sans-serif;">

{{-- ═══ MOBILE OVERLAY ═══════════════════════════════════════════════════════ --}}
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden"></div>

{{-- ═══ SIDEBAR ══════════════════════════════════════════════════════════════ --}}
<nav id="sidebar" class="fixed top-0 left-0 z-40 w-72 h-screen flex flex-col bg-white border-r border-slate-200 -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">

    {{-- Brand --}}
    <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-8 border-b border-slate-200 no-underline flex-shrink-0 gap-6">
        <div class="w-10 h-10 flex items-center justify-center flex-shrink-0 bg-white rounded-lg p-0.5">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded-md">
        </div>
        <div>
            <div class="font-bold text-lg leading-tight text-slate-800">{{ \App\Models\Setting::get('library_name', config('app.name', 'Makarya')) }}</div>
            <div class="text-slate-500 text-xs font-normal">{{ __('Perpustakaan Digital') }}</div>
        </div>
    </a>

    {{-- Scrollable menu area --}}
    <div class="flex-1 overflow-y-auto overflow-x-hidden [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-thumb]:bg-slate-200 [&::-webkit-scrollbar-thumb]:rounded-full py-4">

        @php
            $navLink = 'flex items-center gap-4 mx-3 px-4 py-3 rounded-xl text-base text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200 group relative no-underline';
            $navLinkActive = 'flex items-center gap-4 mx-3 px-4 py-3 rounded-xl text-base btn-gradient-blue font-semibold shadow-lg shadow-indigo-500/20 relative no-underline';
            $menuLabel = 'text-xs uppercase tracking-widest text-slate-400 font-semibold px-6 pt-4 pb-1';
        @endphp

        @unlessrole('anggota')
        {{-- Dashboard Admin --}}
        @can('view-full-dashboard')
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? $navLinkActive : $navLink }}">
            <i class="bi bi-speedometer2 text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Dashboard Admin') }}</span>
        </a>
        @elsecan('process-loans')
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? $navLinkActive : $navLink }}">
            <i class="bi bi-speedometer2 text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Dashboard') }}</span>
        </a>
        @endcan

        {{-- Sirkulasi Buku --}}
        @can('process-loans')
        <p class="{{ $menuLabel }}">{{ __('Sirkulasi Buku') }}</p>
        <a href="{{ route('circulations.index') }}" class="{{ request()->routeIs('circulations.index') || request()->routeIs('circulations.show') ? $navLinkActive : $navLink }}">
            <i class="bi bi-arrow-left-right text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Transaksi Sirkulasi') }}</span>
        </a>
        <a href="{{ route('circulations.loan') }}" class="{{ request()->routeIs('circulations.loan') ? $navLinkActive : $navLink }}">
            <i class="bi bi-box-arrow-right text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Peminjaman') }}</span>
        </a>
        <a href="{{ route('circulations.return') }}" class="{{ request()->routeIs('circulations.return') ? $navLinkActive : $navLink }}">
            <i class="bi bi-box-arrow-in-left text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Pengembalian') }}</span>
        </a>
        @endcan

        {{-- Katalog & Koleksi --}}
        @can('view-books')
        <p class="{{ $menuLabel }}">{{ __('Katalog & Koleksi') }}</p>
        <a href="{{ route('books.index') }}" class="{{ request()->routeIs('books.*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-journals text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Master Buku') }}</span>
        </a>
        <a href="{{ route('authors.index') }}" class="{{ request()->routeIs('authors.*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-person-lines-fill text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Pengarang') }}</span>
        </a>
        <a href="{{ route('publishers.index') }}" class="{{ request()->routeIs('publishers.*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-building text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Penerbit') }}</span>
        </a>
        <a href="{{ route('subjects.index') }}" class="{{ request()->routeIs('subjects.*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-tags text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Subyek') }}</span>
        </a>
        <a href="{{ route('locations.index') }}" class="{{ request()->routeIs('locations.*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-geo-alt text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Lokasi/Rak') }}</span>
        </a>
        @endcan

        {{-- Keanggotaan --}}
        @can('view-members')
        <p class="{{ $menuLabel }}">{{ __('Keanggotaan') }}</p>
        <a href="{{ route('members.index') }}" class="{{ request()->routeIs('members.*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-people text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Data Anggota') }}</span>
        </a>
        @endcan

        {{-- Layanan & Agenda --}}
        <p class="{{ $menuLabel }}">{{ __('Layanan & Agenda') }}</p>
        <a href="{{ route('agendas.index') }}" class="{{ request()->routeIs('agendas.*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-calendar-event text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Agenda Perpustakaan') }}</span>
        </a>
        <a href="{{ route('guest-books.index') }}" class="{{ request()->routeIs('guest-books.index') ? $navLinkActive : $navLink }}">
            <i class="bi bi-journal-check text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Buku Tamu') }}</span>
        </a>
        @can('view-reports')
        <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-bar-chart-line text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Laporan') }}</span>
        </a>
        @endcan

        {{-- Administrasi --}}
        @canany(['manage-users', 'manage-settings'])
        <p class="{{ $menuLabel }}">{{ __('Administrasi') }}</p>
        @can('manage-users')
        <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-person-gear text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Manajemen User') }}</span>
        </a>
        @endcan
        @can('manage-settings')
        <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-gear text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Pengaturan') }}</span>
        </a>
        @endcan
        @endcanany
        @endunlessrole

        {{-- Portal Anggota --}}
        @role('anggota')
        <p class="{{ $menuLabel }}">{{ __('Portal Anggota') }}</p>
        <a href="{{ route('member.dashboard') }}" class="{{ request()->routeIs('member.dashboard') ? $navLinkActive : $navLink }}">
            <i class="bi bi-house-door text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Dashboard Anggota') }}</span>
        </a>
        <a href="{{ route('member.catalog') }}" class="{{ request()->routeIs('member.catalog*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-search text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Katalog Buku') }}</span>
        </a>
        <a href="{{ route('member.my-books') }}" class="{{ request()->routeIs('member.my-books') ? $navLinkActive : $navLink }}">
            <i class="bi bi-book-half text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Buku Saya') }}</span>
        </a>
        <a href="{{ route('member.loans') }}" class="{{ request()->routeIs('member.loans') ? $navLinkActive : $navLink }}">
            <i class="bi bi-clock-history text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Riwayat Pinjam') }}</span>
        </a>
        <a href="{{ route('member.wishlist') }}" class="{{ request()->routeIs('member.wishlist') ? $navLinkActive : $navLink }}">
            <i class="bi bi-heart text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Wishlist') }}</span>
        </a>
        <a href="{{ route('member.fines') }}" class="{{ request()->routeIs('member.fines') ? $navLinkActive : $navLink }}">
            <i class="bi bi-wallet2 text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Denda') }}</span>
        </a>
        <a href="{{ route('member.profile') }}" class="{{ request()->routeIs('member.profile*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-person-badge text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>{{ __('Profil & Kartu') }}</span>
        </a>
        @endrole

    </div>

    {{-- Sidebar Footer --}}
    <div class="px-6 border-t border-slate-200 text-xs text-slate-400 flex-shrink-0 py-4">
        v3.0.0 &copy; {{ date('Y') }} {{ \App\Models\Setting::get('library_name', config('app.name', 'Makarya')) }}
    </div>
</nav>

{{-- ═══ MAIN WRAPPER ══════════════════════════════════════════════════════════ --}}
<div class="lg:ml-72 min-h-screen flex flex-col">

    {{-- Topbar --}}
    <header class="sticky top-0 z-20 h-16 sm:h-20 bg-white border-b border-slate-200 shadow-sm flex items-center gap-2 sm:gap-4 px-3 sm:px-6">
        {{-- Mobile toggle --}}
        <button id="sidebarToggle" class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors">
            <i class="bi bi-list text-2xl"></i>
        </button>

        <div class="flex items-center ml-auto gap-2">

            {{-- Notification Bell (for members) --}}
            @role('anggota')
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false"
                    class="relative w-10 h-10 flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors">
                    <i class="bi bi-bell text-xl"></i>
                    @php
                        $pendingNotifCount = 0;
                        $memberForNotif = auth()->user()?->member;
                        if ($memberForNotif) {
                            $pendingNotifCount = \App\Models\Circulation::where('member_id', $memberForNotif->id)
                                ->where('status', 'Dipinjam')
                                ->where('due_date', '<=', now()->addDays(3))
                                ->count();
                        }
                    @endphp
                    @if($pendingNotifCount > 0)
                    <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full ring-2 ring-white"></span>
                    @endif
                </button>

                {{-- Notification Dropdown --}}
                <div x-show="open" x-cloak x-transition
                    class="absolute right-0 top-12 w-80 bg-white rounded-2xl shadow-xl border border-slate-200 z-50 overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 font-bold text-slate-700 flex items-center gap-2">
                        <i class="bi bi-bell text-indigo-500"></i> Notifikasi
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        @php
                            $notifLoans = [];
                            if ($memberForNotif) {
                                $notifLoans = \App\Models\Circulation::with('bookItem.book')
                                    ->where('member_id', $memberForNotif->id)
                                    ->where('status', 'Dipinjam')
                                    ->where('due_date', '<=', now()->addDays(3))
                                    ->get();
                            }
                        @endphp
                        @forelse($notifLoans as $notifLoan)
                        @php
                            $daysLeft = now()->diffInDays($notifLoan->due_date, false);
                            $isOverdueNotif = $daysLeft < 0;
                        @endphp
                        <div class="px-5 py-4 border-b border-slate-50 hover:bg-slate-50 transition-colors flex gap-3 items-start">
                            <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center {{ $isOverdueNotif ? 'bg-red-100 text-red-500' : 'bg-amber-100 text-amber-600' }}">
                                <i class="bi bi-{{ $isOverdueNotif ? 'exclamation-circle-fill' : 'clock-fill' }} text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-800 line-clamp-1">{{ $notifLoan->bookItem?->book?->title }}</p>
                                <p class="text-xs {{ $isOverdueNotif ? 'text-red-600 font-bold' : 'text-amber-600' }} mt-0.5">
                                    {{ $isOverdueNotif ? 'Terlambat ' . abs($daysLeft) . ' hari!' : 'Jatuh tempo ' . ($daysLeft == 0 ? 'hari ini!' : 'dalam ' . $daysLeft . ' hari') }}
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="py-10 text-center">
                            <div class="text-3xl mb-2">🎉</div>
                            <p class="text-sm font-semibold text-slate-700">Semua aman!</p>
                            <p class="text-xs text-slate-400 mt-1">Tidak ada buku yang akan jatuh tempo.</p>
                        </div>
                        @endforelse
                    </div>
                    <div class="px-5 py-3 border-t border-slate-100">
                        <a href="{{ route('member.my-books') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Lihat Buku Saya →</a>
                    </div>
                </div>
            </div>
            @endrole

            {{-- Auto Refresh Widget --}}
            <div id="autoRefreshContainer" class="hidden sm:flex items-center gap-2" x-data="autoRefreshWidget()">
                <div class="relative">
                    <button @click="open = !open" @click.outside="open = false"
                        class="flex items-center gap-1 px-3 py-2 text-sm text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors border border-slate-200">
                        <i class="bi bi-arrow-clockwise"></i>
                        <span x-text="label">{{ __('Auto Refresh: Off') }}</span>
                        <i class="bi bi-chevron-down text-xs"></i>
                    </button>
                    <div x-show="open" x-transition
                        class="absolute right-0 w-36 bg-white rounded-xl shadow-lg border border-slate-200 border-slate-100 py-1 z-50 mt-1">
                        <template x-for="opt in options" :key="opt.value">
                            <button @click="setInterval(opt.value); open = false"
                                class="w-full text-left py-1.5 text-xs hover:bg-slate-50 transition-colors px-4"
                                :class="opt.value === interval ? 'text-indigo-600 font-semibold' : 'text-slate-600'"
                                x-text="opt.label">
                            </button>
                        </template>
                    </div>
                </div>
                {{-- Progress bar --}}
                <div x-show="interval > 0" class="w-10 h-0.5 bg-slate-200 rounded-full overflow-hidden">
                    <div class="h-full btn-gradient-blue rounded-full transition-all duration-1000"
                        :style="'width:' + progress + '%'"></div>
                </div>
            </div>

            {{-- User dropdown --}}
            @auth
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false"
                    class="flex items-center py-1.5 rounded-lg hover:bg-slate-100 transition-colors gap-2 px-2">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-sm font-bold flex-shrink-0 text-white">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <span class="hidden md:block text-base font-semibold text-slate-700 max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                    <i class="bi bi-chevron-down text-slate-400 text-xs"></i>
                </button>
                <div x-show="open" x-transition
                    class="absolute right-0 w-52 bg-white rounded-xl shadow-lg border border-slate-200 border-slate-100 py-1 z-50 mt-1">
                    <div class="text-xs text-slate-400 truncate border-b border-slate-100 py-2 px-6">{{ auth()->user()->email }}</div>
                    <a href="{{ route('profile.edit') }}" class="flex items-center text-sm text-slate-700 hover:bg-slate-50 transition-colors no-underline gap-2 py-2 px-6">
                        <i class="bi bi-person"></i> {{ __('Profil') }}
                    </a>
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center text-sm text-red-500 hover:bg-red-50 transition-colors gap-2 py-2 px-6">
                            <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="flex items-center gap-2">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 transition-colors no-underline">
                    <i class="bi bi-box-arrow-in-right mr-1.5"></i> {{ __('Masuk / Login') }}
                </a>
            </div>
            @endauth

        </div>
    </header>

    {{-- Content --}}
    <main class="flex-1 p-3.5 sm:p-6 content-area">


        {{-- Flash messages --}}
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition
            class="flex items-center bg-emerald-50 border border-slate-200 border-emerald-200 text-emerald-800 rounded-xl text-sm shadow-sm gap-6 mb-6 py-4 px-6">
            <i class="bi bi-check-circle-fill text-emerald-500 flex-shrink-0"></i>
            <span class="flex-1">{{ session('success') }}</span>
            <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors"><i class="bi bi-x text-base"></i></button>
        </div>
        @endif
        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition
            class="flex items-center bg-red-50 border border-slate-200 border-red-200 text-red-800 rounded-xl text-sm shadow-sm gap-6 mb-6 py-4 px-6">
            <i class="bi bi-exclamation-triangle-fill text-red-500 flex-shrink-0"></i>
            <span class="flex-1">{{ session('error') }}</span>
            <button @click="show = false" class="text-red-400 hover:text-red-600 transition-colors"><i class="bi bi-x text-base"></i></button>
        </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer (visible only for members) --}}
    @role('anggota')
    <footer class="bg-[#1e3a5f] border-t border-slate-700 mt-auto text-slate-300">
        <div class="max-w-full px-6 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                {{-- Library Info --}}
                <div>
                    <h4 class="font-bold text-white text-sm mb-3 flex items-center gap-2">
                        <i class="bi bi-building text-sky-400"></i> {{ \App\Models\Setting::get('library_name', config('app.name', 'Perpustakaan')) }}
                    </h4>
                    <p class="text-xs text-slate-400 leading-relaxed">Selamat datang di perpustakaan digital kami. Temukan ribuan koleksi buku pilihan untuk mendukung kegiatan belajar Anda.</p>
                    <div class="flex gap-3 mt-4">
                        <a href="#" class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center text-white hover:bg-white/20 transition-colors" title="Facebook"><i class="bi bi-facebook text-sm"></i></a>
                        <a href="#" class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center text-white hover:bg-white/20 transition-colors" title="Instagram"><i class="bi bi-instagram text-sm"></i></a>
                        <a href="#" class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center text-white hover:bg-white/20 transition-colors" title="Twitter"><i class="bi bi-twitter-x text-sm"></i></a>
                        <a href="#" class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center text-white hover:bg-white/20 transition-colors" title="YouTube"><i class="bi bi-youtube text-sm"></i></a>
                    </div>
                </div>

                {{-- Jam Operasional --}}
                <div>
                    <h4 class="font-bold text-white text-sm mb-3 flex items-center gap-2">
                        <i class="bi bi-clock text-sky-400"></i> Jam Operasional
                    </h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-400">Senin – Jumat</span>
                            <span class="font-semibold text-slate-200">08.00 – 16.00 WIB</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-400">Sabtu</span>
                            <span class="font-semibold text-slate-200">08.00 – 12.00 WIB</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-400">Minggu & Hari Libur</span>
                            <span class="font-semibold text-red-400">Tutup</span>
                        </div>
                    </div>
                </div>

                {{-- Kontak & Alamat --}}
                <div>
                    <h4 class="font-bold text-white text-sm mb-3 flex items-center gap-2">
                        <i class="bi bi-geo-alt text-sky-400"></i> Kontak & Alamat
                    </h4>
                    <div class="space-y-2">
                        <div class="flex items-start gap-2 text-xs text-slate-400">
                            <i class="bi bi-geo-alt-fill text-slate-500 mt-0.5"></i>
                            <span>{{ \App\Models\Setting::get('library_address', 'Jl. Contoh No. 1, Kota, Provinsi 12345') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-400">
                            <i class="bi bi-telephone-fill text-slate-500"></i>
                            <a href="tel:{{ \App\Models\Setting::get('library_phone', '+628000000000') }}" class="hover:text-sky-400 text-slate-400">{{ \App\Models\Setting::get('library_phone', '(021) 000-0000') }}</a>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-400">
                            <i class="bi bi-envelope-fill text-slate-500"></i>
                            <a href="mailto:{{ \App\Models\Setting::get('library_email', 'info@perpustakaan.ac.id') }}" class="hover:text-sky-400 text-slate-400">{{ \App\Models\Setting::get('library_email', 'info@perpustakaan.ac.id') }}</a>
                        </div>
                    </div>
                </div>

            </div>
            <div class="border-t border-white/10 mt-6 pt-4 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} {{ \App\Models\Setting::get('library_name', config('app.name', 'Perpustakaan Digital')) }} — Hak cipta dilindungi.
            </div>
        </div>
    </footer>
    @endrole

</div>{{-- end main-wrapper --}}

@stack('scripts')

<script>
// Alpine.js Auto Refresh Component
document.addEventListener('alpine:init', () => {
    Alpine.data('autoRefreshWidget', () => ({
        open: false,
        interval: 0,
        progress: 100,
        label: 'Auto Refresh: Off',
        timer: null,
        timeLeft: 0,
        options: [
            { value: 0,   label: '{{ __('Off') }}' },
            { value: 10,  label: '{{ __('10 detik') }}' },
            { value: 30,  label: '{{ __('30 detik') }}' },
            { value: 60,  label: '{{ __('1 menit') }}' },
            { value: 300, label: '{{ __('5 menit') }}' },
        ],

        init() {
            const currentPath = window.location.pathname.toLowerCase().replace(/\/$/, '');
            const allowed = ['/dashboard', '/sirkulasi', '/users', '/members'];
            const isAllowed = allowed.some(p => currentPath.endsWith(p));

            const container = document.getElementById('autoRefreshContainer');
            if (!isAllowed) {
                if (container) container.classList.add('hidden');
                return;
            }

            try {
                this.interval = parseInt(localStorage.getItem('auto_refresh_interval') || '0');
            } catch(e) { this.interval = 0; }

            this.updateLabel();
            this.startTimer();
        },

        setInterval(val) {
            this.interval = val;
            try { localStorage.setItem('auto_refresh_interval', val); } catch(e) {}
            this.updateLabel();
            this.startTimer();
        },

        updateLabel() {
            const opt = this.options.find(o => o.value === this.interval);
            this.label = opt ? (this.interval === 0 ? '{{ __('Auto Refresh: Off') }}' : `Auto: ${opt.label}`) : '{{ __('Auto Refresh: Off') }}';
        },

        startTimer() {
            if (this.timer) clearInterval(this.timer);
            if (this.interval === 0) { this.progress = 100; return; }

            this.timeLeft = this.interval;
            this.progress = 100;

            this.timer = setInterval(() => {
                const active = document.activeElement;
                if (active && ['INPUT', 'SELECT', 'TEXTAREA'].includes(active.tagName)) {
                    this.timeLeft = this.interval;
                    this.progress = 100;
                    return;
                }
                this.timeLeft--;
                this.progress = (this.timeLeft / this.interval) * 100;
                if (this.timeLeft <= 0) {
                    clearInterval(this.timer);
                    this.refreshContent();
                }
            }, 1000);
        },

        refreshContent() {
            fetch(window.location.href)
                .then(r => r.text())
                .then(html => {
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const cur = document.querySelector('.content-area');
                    const next = doc.querySelector('.content-area');
                    if (cur && next) cur.innerHTML = next.innerHTML;
                })
                .catch(e => console.error('[AutoRefresh]', e))
                .finally(() => this.startTimer());
        }
    }));
});
</script>

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

            // Mencegah scanner barcode (yang mengirim kombinasi tertentu) membuka tab console
            window.addEventListener('keydown', function(e) {
                // F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+Shift+C, Ctrl+U
                if (
                    e.key === 'F12' || e.keyCode === 123 ||
                    (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'i' || e.keyCode === 73)) ||
                    (e.ctrlKey && e.shiftKey && (e.key === 'J' || e.key === 'j' || e.keyCode === 74)) ||
                    (e.ctrlKey && e.shiftKey && (e.key === 'C' || e.key === 'c' || e.keyCode === 67)) ||
                    (e.ctrlKey && (e.key === 'U' || e.key === 'u' || e.keyCode === 85))
                ) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            }, true);
        });
    </script>
</body>
</html>


