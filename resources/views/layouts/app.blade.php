<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Makarya') }}</title>
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
            <div class="font-bold text-lg leading-tight text-slate-800">{{ config('app.name', 'Makarya') }}</div>
            <div class="text-slate-500 text-xs font-normal">Perpustakaan Digital</div>
        </div>
    </a>

    {{-- Scrollable menu area --}}
    <div class="flex-1 overflow-y-auto overflow-x-hidden [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-thumb]:bg-slate-200 [&::-webkit-scrollbar-thumb]:rounded-full py-4">

        @php
            $navLink = 'flex items-center gap-4 mx-3 px-4 py-3 rounded-xl text-base text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200 group relative no-underline';
            $navLinkActive = 'flex items-center gap-4 mx-3 px-4 py-3 rounded-xl text-base bg-indigo-600 text-white font-semibold shadow-lg shadow-indigo-500/20 relative no-underline';
            $menuLabel = 'text-xs uppercase tracking-widest text-slate-400 font-semibold px-6 pt-4 pb-1';
        @endphp

        {{-- Dashboard --}}
        @can('view-full-dashboard')
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? $navLinkActive : $navLink }}">
            <i class="bi bi-speedometer2 text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>Dashboard</span>
        </a>
        @endcan

        {{-- Layanan Mandiri --}}
        <p class="{{ $menuLabel }}">Layanan Mandiri</p>
        <a href="{{ route('opac.index') }}" target="_blank" class="{{ $navLink }}">
            <i class="bi bi-search text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>Cari Buku (OPAC)</span>
            <i class="bi bi-box-arrow-up-right text-xs ml-auto opacity-50"></i>
        </a>
        <a href="{{ route('guest-books.visitor') }}" target="_blank" class="{{ $navLink }}">
            <i class="bi bi-journal-plus text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>Buku Tamu Mandiri</span>
            <i class="bi bi-box-arrow-up-right text-xs ml-auto opacity-50"></i>
        </a>

        {{-- Sirkulasi Buku --}}
        @can('process-loans')
        <p class="{{ $menuLabel }}">Sirkulasi Buku</p>
        <a href="{{ route('circulations.index') }}" class="{{ request()->routeIs('circulations.index') || request()->routeIs('circulations.show') ? $navLinkActive : $navLink }}">
            <i class="bi bi-arrow-left-right text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>Transaksi Sirkulasi</span>
        </a>
        <a href="{{ route('circulations.loan') }}" class="{{ request()->routeIs('circulations.loan') ? $navLinkActive : $navLink }}">
            <i class="bi bi-box-arrow-right text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>Peminjaman</span>
        </a>
        <a href="{{ route('circulations.return') }}" class="{{ request()->routeIs('circulations.return') ? $navLinkActive : $navLink }}">
            <i class="bi bi-box-arrow-in-left text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>Pengembalian</span>
        </a>
        @endcan

        {{-- Katalog & Koleksi --}}
        @can('view-books')
        <p class="{{ $menuLabel }}">Katalog & Koleksi</p>
        <a href="{{ route('books.index') }}" class="{{ request()->routeIs('books.*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-journals text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>Master Buku</span>
        </a>
        <a href="{{ route('authors.index') }}" class="{{ request()->routeIs('authors.*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-person-lines-fill text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>Pengarang</span>
        </a>
        <a href="{{ route('publishers.index') }}" class="{{ request()->routeIs('publishers.*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-building text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>Penerbit</span>
        </a>
        <a href="{{ route('subjects.index') }}" class="{{ request()->routeIs('subjects.*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-tags text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>Subyek</span>
        </a>
        <a href="{{ route('locations.index') }}" class="{{ request()->routeIs('locations.*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-geo-alt text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>Lokasi/Rak</span>
        </a>
        @endcan

        {{-- Keanggotaan --}}
        @can('view-members')
        <p class="{{ $menuLabel }}">Keanggotaan</p>
        <a href="{{ route('members.index') }}" class="{{ request()->routeIs('members.*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-people text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>Data Anggota</span>
        </a>
        @endcan

        {{-- Laporan & Log --}}
        <p class="{{ $menuLabel }}">Laporan & Log</p>
        <a href="{{ route('guest-books.index') }}" class="{{ request()->routeIs('guest-books.index') ? $navLinkActive : $navLink }}">
            <i class="bi bi-journal-text text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>Log Buku Tamu</span>
        </a>
        @can('view-reports')
        <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-file-earmark-bar-graph text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>Laporan Transaksi</span>
        </a>
        @endcan

        {{-- Administrasi --}}
        @canany(['manage-users', 'manage-settings'])
        <p class="{{ $menuLabel }}">Administrasi</p>
        @can('manage-users')
        <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-shield-person text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>Manajemen User</span>
        </a>
        @endcan
        @can('manage-settings')
        <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? $navLinkActive : $navLink }}">
            <i class="bi bi-gear text-xl w-7 transition-transform group-hover:scale-110 text-center"></i>
            <span>Pengaturan</span>
        </a>
        @endcan
        @endcanany

    </div>

    {{-- Footer --}}
    <div class="px-6 border-t border-slate-200 text-xs text-slate-400 flex-shrink-0 py-4">
        v3.0.0 &copy; {{ date('Y') }} {{ config('app.name', 'Makarya') }}
    </div>
</nav>

{{-- ═══ MAIN WRAPPER ══════════════════════════════════════════════════════════ --}}
<div class="lg:ml-72 min-h-screen flex flex-col">

    {{-- Topbar --}}
    <header class="sticky top-0 z-20 h-20 bg-white border-b border-slate-200 shadow-sm flex items-center gap-6 px-6">
        {{-- Mobile toggle --}}
        <button id="sidebarToggle" class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors">
            <i class="bi bi-list text-2xl"></i>
        </button>

        <h1 class="text-xl font-bold text-slate-800 flex-shrink-0">@yield('page-title', 'Dashboard')</h1>

        {{-- Quick search --}}
        <form action="{{ route('opac.index') }}" method="GET" class="hidden md:flex flex-1 max-w-md ml-6">
            <div class="relative w-full group flex items-center">
                <i class="bi bi-search absolute left-4 text-slate-400 text-sm group-focus-within:text-indigo-600 transition-colors pointer-events-none mt-0.5"></i>
                <input type="text" name="q" class="w-full pl-10 pr-5 py-2 text-sm bg-slate-100 hover:bg-slate-200/60 border border-transparent rounded-full focus:outline-none focus:bg-white focus:border-indigo-300 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-400" placeholder="Cari koleksi buku...">
            </div>
        </form>

        <div class="flex items-center ml-auto gap-2">

            {{-- Auto Refresh Widget --}}
            <div id="autoRefreshContainer" class="hidden sm:flex items-center gap-2" x-data="autoRefreshWidget()">
                <div class="relative">
                    <button @click="open = !open" @click.outside="open = false"
                        class="flex items-center gap-1 px-3 py-2 text-sm text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors border border-slate-200">
                        <i class="bi bi-arrow-clockwise"></i>
                        <span x-text="label">Auto Refresh: Off</span>
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
                    <div class="h-full bg-indigo-500 rounded-full transition-all duration-1000"
                        :style="'width:' + progress + '%'"></div>
                </div>
            </div>

            {{-- User dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false"
                    class="flex items-center py-1.5 rounded-lg hover:bg-slate-100 transition-colors gap-2 px-2">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-sm font-bold flex-shrink-0 text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="hidden md:block text-base font-semibold text-slate-700 max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                    <i class="bi bi-chevron-down text-slate-400 text-xs"></i>
                </button>
                <div x-show="open" x-transition
                    class="absolute right-0 w-52 bg-white rounded-xl shadow-lg border border-slate-200 border-slate-100 py-1 z-50 mt-1">
                    <div class="text-xs text-slate-400 truncate border-b border-slate-100 py-2 px-6">{{ auth()->user()->email }}</div>
                    <a href="{{ route('profile.edit') }}" class="flex items-center text-sm text-slate-700 hover:bg-slate-50 transition-colors no-underline gap-2 py-2 px-6">
                        <i class="bi bi-person"></i> Profil
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center text-sm text-red-500 hover:bg-red-50 transition-colors gap-2 py-2 px-6">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </header>

    {{-- Content --}}
    <main class="flex-1 p-6 content-area">

        {{-- Breadcrumb --}}
        @hasSection('breadcrumb')
        <nav class="flex items-center gap-1 text-xs text-slate-400 mb-6">
            <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition-colors no-underline"><i class="bi bi-house"></i></a>
            <i class="bi bi-chevron-right text-[0.55rem]"></i>
            @yield('breadcrumb')
        </nav>
        @endif

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
            { value: 0,   label: 'Off' },
            { value: 10,  label: '10 detik' },
            { value: 30,  label: '30 detik' },
            { value: 60,  label: '1 menit' },
            { value: 300, label: '5 menit' },
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
            this.label = opt ? (this.interval === 0 ? 'Auto Refresh: Off' : `Auto: ${opt.label}`) : 'Auto Refresh: Off';
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
