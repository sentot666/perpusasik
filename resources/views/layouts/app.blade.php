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
        [x-cloak] {
            display: none !important;
        }

        /* ── Collapsible & Mini Sidebar ── */
        .sidebar-transition {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-tooltip {
            display: none;
        }

        @media (min-width: 1024px) {
            body.sidebar-collapsed #sidebar {
                width: 5rem !important;
            }
            body.sidebar-collapsed #mainWrapper {
                margin-left: 5rem !important;
            }
            body.sidebar-collapsed .sidebar-text,
            body.sidebar-collapsed .sidebar-brand-text,
            body.sidebar-collapsed .sidebar-heading,
            body.sidebar-collapsed .sidebar-footer-text {
                display: none !important;
            }
            body.sidebar-collapsed .sidebar-heading-divider {
                display: block !important;
            }
            body.sidebar-collapsed .sidebar-brand-wrapper {
                justify-content: center !important;
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
            body.sidebar-collapsed .sidebar-item {
                margin-left: 0.5rem !important;
                margin-right: 0.5rem !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                justify-content: center !important;
            }
            body.sidebar-collapsed .sidebar-item .sidebar-icon {
                margin: 0 !important;
            }
            body.sidebar-collapsed .sidebar-item:hover .sidebar-tooltip {
                display: flex !important;
                position: absolute;
                left: calc(100% + 0.75rem);
                top: 50%;
                transform: translateY(-50%);
                background-color: #0f172a;
                color: #ffffff;
                font-size: 0.75rem;
                font-weight: 600;
                padding: 0.35rem 0.75rem;
                border-radius: 0.5rem;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
                white-space: nowrap;
                pointer-events: none;
                z-index: 9999;
            }
            body.sidebar-collapsed .sidebar-item:hover .sidebar-tooltip::before {
                content: '';
                position: absolute;
                right: 100%;
                top: 50%;
                transform: translateY(-50%);
                border-width: 5px;
                border-style: solid;
                border-color: transparent #0f172a transparent transparent;
            }
        }

        @media print {
            /* Sembunyikan elemen UI yang tidak perlu dicetak */
            #sidebar, header, footer, .no-print, form, button, .pagination, #sidebarOverlay {
                display: none !important;
            }
            /* Hilangkan background dan margin utama */
            body, .min-h-screen, .bg-blue-50 {
                background: white !important;
                min-height: auto !important;
            }
            .lg\:ml-72, #mainWrapper {
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
<div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity duration-300"></div>

{{-- ═══ SIDEBAR ══════════════════════════════════════════════════════════════ --}}
<nav id="sidebar" class="fixed top-0 left-0 z-50 w-72 h-screen flex flex-col bg-white border-r border-slate-200 -translate-x-full lg:translate-x-0 sidebar-transition shadow-2xl lg:shadow-none">

    {{-- Brand --}}
    <div class="sidebar-brand-wrapper flex items-center justify-between px-5 py-5 border-b border-slate-200 flex-shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center no-underline gap-3 min-w-0 flex-1">
            <div class="w-10 h-10 flex items-center justify-center flex-shrink-0 bg-white rounded-lg p-0.5 border border-slate-100 shadow-xs">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded-md">
            </div>
            <div class="sidebar-brand-text min-w-0 flex-1">
                <div class="font-bold text-base leading-tight text-slate-800 truncate">{{ \App\Models\Setting::get('library_name', config('app.name', 'Makarya')) }}</div>
                <div class="text-slate-500 text-xs font-normal truncate">{{ __('Perpustakaan Digital') }}</div>
            </div>
        </a>
        <button type="button" id="sidebarClose" class="lg:hidden p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer" aria-label="Close Sidebar">
            <i class="bi bi-x-lg text-lg"></i>
        </button>
    </div>

    {{-- Scrollable menu area --}}
    <div class="flex-1 overflow-y-auto overflow-x-hidden [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-thumb]:bg-slate-200 [&::-webkit-scrollbar-thumb]:rounded-full py-4 space-y-1">

        @php
            $navLink = 'sidebar-item flex items-center gap-3.5 mx-3 px-4 py-3 rounded-xl text-sm text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-200 group relative no-underline';
            $navLinkActive = 'sidebar-item flex items-center gap-3.5 mx-3 px-4 py-3 rounded-xl text-sm btn-gradient-blue font-semibold shadow-md shadow-indigo-500/20 text-white relative no-underline';
            $menuLabel = 'sidebar-heading text-[11px] uppercase tracking-widest text-slate-400 font-bold px-6 pt-5 pb-1';
        @endphp

        @unlessrole('anggota')
        {{-- Dashboard Admin --}}
        @can('view-full-dashboard')
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? $navLinkActive : $navLink }}" title="{{ __('Dashboard Admin') }}">
            <i class="bi bi-speedometer2 text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Dashboard Admin') }}</span>
            <span class="sidebar-tooltip">{{ __('Dashboard Admin') }}</span>
        </a>
        @elsecan('process-loans')
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? $navLinkActive : $navLink }}" title="{{ __('Dashboard') }}">
            <i class="bi bi-speedometer2 text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Dashboard') }}</span>
            <span class="sidebar-tooltip">{{ __('Dashboard') }}</span>
        </a>
        @endcan

        {{-- Pintasan Halaman Depan --}}
        <p class="{{ $menuLabel }}">{{ __('Pintasan (Front-End)') }}</p>
        <div class="sidebar-heading-divider hidden w-8 mx-auto border-t border-slate-200 my-2"></div>
        <a href="{{ route('opac.katalog') }}" target="_blank" class="{{ request()->routeIs('opac.katalog') ? $navLinkActive : $navLink }}" title="{{ __('Cari Buku (OPAC)') }}">
            <i class="bi bi-search text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Cari Buku (OPAC)') }}</span>
            <span class="sidebar-tooltip">{{ __('Cari Buku (OPAC)') }}</span>
        </a>
        <a href="{{ route('guest-books.visitor') }}" target="_blank" class="{{ request()->routeIs('guest-books.visitor') ? $navLinkActive : $navLink }}" title="{{ __('Isi Buku Tamu') }}">
            <i class="bi bi-journal-text text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Isi Buku Tamu') }}</span>
            <span class="sidebar-tooltip">{{ __('Isi Buku Tamu') }}</span>
        </a>

        {{-- Sirkulasi Buku --}}
        @can('process-loans')
        <p class="{{ $menuLabel }}">{{ __('Sirkulasi Buku') }}</p>
        <div class="sidebar-heading-divider hidden w-8 mx-auto border-t border-slate-200 my-2"></div>
        <a href="{{ route('reservations.index') }}" class="{{ request()->routeIs('reservations.*') ? $navLinkActive : $navLink }}" title="{{ __('Reservasi Online') }}">
            <i class="bi bi-journal-bookmark text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Reservasi Online') }}</span>
            <span class="sidebar-tooltip">{{ __('Reservasi Online') }}</span>
        </a>
        <a href="{{ route('circulations.index') }}" class="{{ request()->routeIs('circulations.index') || request()->routeIs('circulations.show') ? $navLinkActive : $navLink }}" title="{{ __('Transaksi Sirkulasi') }}">
            <i class="bi bi-arrow-left-right text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Transaksi Sirkulasi') }}</span>
            <span class="sidebar-tooltip">{{ __('Transaksi Sirkulasi') }}</span>
        </a>
        <a href="{{ route('circulations.loan') }}" class="{{ request()->routeIs('circulations.loan') ? $navLinkActive : $navLink }}" title="{{ __('Peminjaman') }}">
            <i class="bi bi-box-arrow-right text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Peminjaman') }}</span>
            <span class="sidebar-tooltip">{{ __('Peminjaman') }}</span>
        </a>
        <a href="{{ route('circulations.return') }}" class="{{ request()->routeIs('circulations.return') ? $navLinkActive : $navLink }}" title="{{ __('Pengembalian') }}">
            <i class="bi bi-box-arrow-in-left text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Pengembalian') }}</span>
            <span class="sidebar-tooltip">{{ __('Pengembalian') }}</span>
        </a>
        @endcan

        {{-- Katalog & Koleksi --}}
        @can('view-books')
        <p class="{{ $menuLabel }}">{{ __('Katalog & Koleksi') }}</p>
        <div class="sidebar-heading-divider hidden w-8 mx-auto border-t border-slate-200 my-2"></div>
        <a href="{{ route('books.index') }}" class="{{ request()->routeIs('books.*') ? $navLinkActive : $navLink }}" title="{{ __('Master Buku') }}">
            <i class="bi bi-journals text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Master Buku') }}</span>
            <span class="sidebar-tooltip">{{ __('Master Buku') }}</span>
        </a>
        <a href="{{ route('authors.index') }}" class="{{ request()->routeIs('authors.*') ? $navLinkActive : $navLink }}" title="{{ __('Pengarang') }}">
            <i class="bi bi-person-lines-fill text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Pengarang') }}</span>
            <span class="sidebar-tooltip">{{ __('Pengarang') }}</span>
        </a>
        <a href="{{ route('publishers.index') }}" class="{{ request()->routeIs('publishers.*') ? $navLinkActive : $navLink }}" title="{{ __('Penerbit') }}">
            <i class="bi bi-building text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Penerbit') }}</span>
            <span class="sidebar-tooltip">{{ __('Penerbit') }}</span>
        </a>
        <a href="{{ route('subjects.index') }}" class="{{ request()->routeIs('subjects.*') ? $navLinkActive : $navLink }}" title="{{ __('Subyek') }}">
            <i class="bi bi-tags text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Subyek') }}</span>
            <span class="sidebar-tooltip">{{ __('Subyek') }}</span>
        </a>
        <a href="{{ route('locations.index') }}" class="{{ request()->routeIs('locations.*') ? $navLinkActive : $navLink }}" title="{{ __('Lokasi/Rak') }}">
            <i class="bi bi-geo-alt text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Lokasi/Rak') }}</span>
            <span class="sidebar-tooltip">{{ __('Lokasi/Rak') }}</span>
        </a>
        @endcan

        {{-- Keanggotaan --}}
        @can('view-members')
        <p class="{{ $menuLabel }}">{{ __('Keanggotaan') }}</p>
        <div class="sidebar-heading-divider hidden w-8 mx-auto border-t border-slate-200 my-2"></div>
        <a href="{{ route('members.index') }}" class="{{ request()->routeIs('members.*') ? $navLinkActive : $navLink }}" title="{{ __('Data Anggota') }}">
            <i class="bi bi-people text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Data Anggota') }}</span>
            <span class="sidebar-tooltip">{{ __('Data Anggota') }}</span>
        </a>
        @endcan

        {{-- Layanan & Agenda --}}
        <p class="{{ $menuLabel }}">{{ __('Layanan & Agenda') }}</p>
        <div class="sidebar-heading-divider hidden w-8 mx-auto border-t border-slate-200 my-2"></div>
        <a href="{{ route('agendas.index') }}" class="{{ request()->routeIs('agendas.*') ? $navLinkActive : $navLink }}" title="{{ __('Agenda Perpustakaan') }}">
            <i class="bi bi-calendar-event text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Agenda Perpustakaan') }}</span>
            <span class="sidebar-tooltip">{{ __('Agenda Perpustakaan') }}</span>
        </a>
        <a href="{{ route('pages.index') }}" class="{{ request()->routeIs('pages.*') ? $navLinkActive : $navLink }}" title="{{ __('Manajemen Halaman') }}">
            <i class="bi bi-file-earmark-richtext text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Manajemen Halaman') }}</span>
            <span class="sidebar-tooltip">{{ __('Manajemen Halaman') }}</span>
        </a>
        <a href="{{ route('guest-books.index') }}" class="{{ request()->routeIs('guest-books.index') ? $navLinkActive : $navLink }}" title="{{ __('Data Buku Tamu') }}">
            <i class="bi bi-journal-check text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Data Buku Tamu') }}</span>
            <span class="sidebar-tooltip">{{ __('Data Buku Tamu') }}</span>
        </a>
        @can('view-reports')
        <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? $navLinkActive : $navLink }}" title="{{ __('Laporan') }}">
            <i class="bi bi-bar-chart-line text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Laporan') }}</span>
            <span class="sidebar-tooltip">{{ __('Laporan') }}</span>
        </a>
        @endcan

        {{-- Administrasi --}}
        @canany(['manage-users', 'manage-settings'])
        <p class="{{ $menuLabel }}">{{ __('Administrasi') }}</p>
        <div class="sidebar-heading-divider hidden w-8 mx-auto border-t border-slate-200 my-2"></div>
        @can('manage-users')
        <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? $navLinkActive : $navLink }}" title="{{ __('Manajemen User') }}">
            <i class="bi bi-person-gear text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Manajemen User') }}</span>
            <span class="sidebar-tooltip">{{ __('Manajemen User') }}</span>
        </a>
        @endcan
        @can('manage-settings')
        <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? $navLinkActive : $navLink }}" title="{{ __('Pengaturan') }}">
            <i class="bi bi-gear text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Pengaturan') }}</span>
            <span class="sidebar-tooltip">{{ __('Pengaturan') }}</span>
        </a>
        @endcan
        @endcanany
        @endunlessrole

        {{-- Portal Anggota --}}
        @role('anggota')
        <p class="{{ $menuLabel }}">{{ __('Portal Anggota') }}</p>
        <div class="sidebar-heading-divider hidden w-8 mx-auto border-t border-slate-200 my-2"></div>
        <a href="{{ route('member.dashboard') }}" class="{{ request()->routeIs('member.dashboard') ? $navLinkActive : $navLink }}" title="{{ __('Dashboard Anggota') }}">
            <i class="bi bi-house-door text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Dashboard Anggota') }}</span>
            <span class="sidebar-tooltip">{{ __('Dashboard Anggota') }}</span>
        </a>
        <a href="{{ route('member.catalog') }}" class="{{ request()->routeIs('member.catalog*') ? $navLinkActive : $navLink }}" title="{{ __('Katalog Buku') }}">
            <i class="bi bi-search text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Katalog Buku') }}</span>
            <span class="sidebar-tooltip">{{ __('Katalog Buku') }}</span>
        </a>
        <a href="{{ route('member.my-books') }}" class="{{ request()->routeIs('member.my-books') ? $navLinkActive : $navLink }}" title="{{ __('Buku Saya') }}">
            <i class="bi bi-book-half text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Buku Saya') }}</span>
            <span class="sidebar-tooltip">{{ __('Buku Saya') }}</span>
        </a>
        <a href="{{ route('member.loans') }}" class="{{ request()->routeIs('member.loans') ? $navLinkActive : $navLink }}" title="{{ __('Riwayat Pinjam') }}">
            <i class="bi bi-clock-history text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Riwayat Pinjam') }}</span>
            <span class="sidebar-tooltip">{{ __('Riwayat Pinjam') }}</span>
        </a>
        <a href="{{ route('member.wishlist') }}" class="{{ request()->routeIs('member.wishlist') ? $navLinkActive : $navLink }}" title="{{ __('Wishlist') }}">
            <i class="bi bi-heart text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Wishlist') }}</span>
            <span class="sidebar-tooltip">{{ __('Wishlist') }}</span>
        </a>
        <a href="{{ route('member.fines') }}" class="{{ request()->routeIs('member.fines') ? $navLinkActive : $navLink }}" title="{{ __('Denda') }}">
            <i class="bi bi-wallet2 text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Denda') }}</span>
            <span class="sidebar-tooltip">{{ __('Denda') }}</span>
        </a>
        <a href="{{ route('member.profile') }}" class="{{ request()->routeIs('member.profile*') ? $navLinkActive : $navLink }}" title="{{ __('Profil & Kartu') }}">
            <i class="bi bi-person-badge text-xl w-7 text-center transition-transform group-hover:scale-110 flex-shrink-0 sidebar-icon"></i>
            <span class="sidebar-text truncate">{{ __('Profil & Kartu') }}</span>
            <span class="sidebar-tooltip">{{ __('Profil & Kartu') }}</span>
        </a>
        @endrole

    </div>

    {{-- Sidebar Footer --}}
    <div class="sidebar-footer px-5 border-t border-slate-200 text-xs text-slate-400 flex-shrink-0 py-4 flex items-center justify-between">
        <span class="sidebar-footer-text truncate">&copy; {{ date('Y') }} {{ \App\Models\Setting::get('library_name', config('app.name', 'Makarya')) }}</span>
    </div>
</nav>

{{-- ═══ MAIN WRAPPER ══════════════════════════════════════════════════════════ --}}
<div id="mainWrapper" class="lg:ml-72 min-h-screen flex flex-col sidebar-transition">

    {{-- Topbar --}}
    <header class="sticky top-0 z-20 h-16 sm:h-20 bg-white border-b border-slate-200 shadow-sm flex items-center gap-2 sm:gap-4 px-3 sm:px-6">
        {{-- Sidebar Toggle Button (Mobile: Drawer / Desktop: Mini Sidebar Collapse) --}}
        <button type="button" id="sidebarToggle" class="flex items-center justify-center p-2 rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-slate-100 transition-colors cursor-pointer" aria-label="Toggle Sidebar" title="Toggle Sidebar">
            <i class="bi bi-layout-sidebar text-xl"></i>
        </button>

        <div class="flex items-center ml-auto gap-2">

            {{-- Notification Bell (for members - Hover Effect) --}}
            @role('anggota')
            <div class="relative group">
                <div class="relative w-10 h-10 flex items-center justify-center text-slate-500 group-hover:text-indigo-600 group-hover:bg-indigo-50 rounded-xl transition-colors cursor-pointer">
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
                    <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full ring-2 ring-white animate-pulse"></span>
                    @endif
                </div>

                {{-- Notification Dropdown on Hover --}}
                <div class="absolute right-0 top-full pt-1 hidden group-hover:block z-50">
                    <div class="w-80 bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 font-bold text-slate-700 flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <i class="bi bi-bell text-indigo-500"></i> {{ __('Notifikasi') }}
                            </span>
                            @if($pendingNotifCount > 0)
                            <span class="text-xs bg-red-50 text-red-600 font-semibold px-2 py-0.5 rounded-full">{{ $pendingNotifCount }}</span>
                            @endif
                        </div>
                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-50">
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
                            <div class="px-5 py-3.5 hover:bg-slate-50 transition-colors flex gap-3 items-start">
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
                            <div class="py-8 text-center">
                                <div class="text-3xl mb-2">🎉</div>
                                <p class="text-sm font-semibold text-slate-700">Semua aman!</p>
                                <p class="text-xs text-slate-400 mt-1">Tidak ada buku yang akan jatuh tempo.</p>
                            </div>
                            @endforelse
                        </div>
                        <div class="px-5 py-3 bg-slate-50/50 border-t border-slate-100 text-center">
                            <a href="{{ route('member.my-books') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 no-underline inline-flex items-center gap-1">
                                {{ __('Lihat Buku Saya') }} <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endrole

            {{-- Auto Refresh Widget --}}
            <div id="autoRefreshContainer" class="hidden sm:flex items-center gap-2" x-data="autoRefreshWidget()">
                <div class="relative">
                    <button type="button" @click="open = !open" @click.outside="open = false"
                        class="flex items-center gap-1.5 px-3 py-2 text-sm text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors border border-slate-200 cursor-pointer">
                        <i class="bi bi-arrow-clockwise"></i>
                        <span x-text="label">{{ __('Auto Refresh: Off') }}</span>
                        <i class="bi bi-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-cloak x-transition
                        class="absolute right-0 w-36 bg-white rounded-xl shadow-xl border border-slate-200 py-1 z-50 mt-1">
                        <template x-for="opt in options" :key="opt.value">
                            <button type="button" @click="setInterval(opt.value); open = false"
                                class="w-full text-left py-1.5 text-xs hover:bg-slate-50 transition-colors px-4 cursor-pointer"
                                :class="opt.value === interval ? 'text-indigo-600 font-semibold bg-indigo-50/50' : 'text-slate-600'"
                                x-text="opt.label">
                            </button>
                        </template>
                    </div>
                </div>
                {{-- Progress bar --}}
                <div x-show="interval > 0" x-cloak class="w-10 h-0.5 bg-slate-200 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-600 rounded-full transition-all duration-1000"
                        :style="'width:' + progress + '%'"></div>
                </div>
            </div>

            {{-- User dropdown (Hover Effect) --}}
            @auth
            <div class="relative group">
                <div class="flex items-center py-1.5 rounded-lg group-hover:bg-slate-100 transition-colors gap-2 px-2 cursor-pointer">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-sm font-bold flex-shrink-0 text-white shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <span class="hidden md:block text-base font-semibold text-slate-700 max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                    <i class="bi bi-chevron-down text-slate-400 text-xs transition-transform duration-200 group-hover:rotate-180"></i>
                </div>
                
                {{-- Dropdown Menu on Hover with safe hover bridge --}}
                <div class="absolute right-0 top-full pt-1 hidden group-hover:block z-50">
                    <div class="w-52 bg-white rounded-xl shadow-xl border border-slate-200/80 py-1.5 overflow-hidden">
                        <div class="text-xs text-slate-400 truncate border-b border-slate-100 py-2 px-4">{{ auth()->user()->email }}</div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center text-sm text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition-colors no-underline gap-2.5 py-2.5 px-4">
                            <i class="bi bi-person text-base"></i> {{ __('Profil') }}
                        </a>
                        
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center text-sm text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors gap-2.5 py-2.5 px-4 text-left cursor-pointer border-none bg-transparent">
                                <i class="bi bi-box-arrow-right text-base"></i> {{ __('Logout') }}
                            </button>
                        </form>
                    </div>
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
// Safe registration for Alpine autoRefreshWidget
if (window.autoRefreshWidget) {
    if (window.Alpine) {
        try { window.Alpine.data('autoRefreshWidget', window.autoRefreshWidget); } catch(e) {}
    } else {
        document.addEventListener('alpine:init', () => {
            try { Alpine.data('autoRefreshWidget', window.autoRefreshWidget); } catch(e) {}
        });
    }
}
</script>

    <x-toast />

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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


