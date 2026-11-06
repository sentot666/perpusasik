<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ __('Portal Siswa Perpustakaan Sekolah Katolik Santo Paulus') }}">
    <title>@hasSection('title')@yield('title') - @endif {{ __('Portal Siswa') }} | {{ \App\Models\Setting::get('library_name', __('Perpustakaan')) }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @stack('styles')
</head>
<body x-data="{ sidebarOpen: false, notifOpen: false }" class="bg-slate-50 text-slate-800 min-h-screen flex antialiased">

@php
    $currentUser = auth()->user();
    $currentMember = $currentUser?->member;
    $firstName = $currentUser ? explode(' ', $currentUser->name)[0] : __('Siswa');
    
    // Calculate pending due notifications
    $pendingNotifCount = 0;
    $notifLoans = collect();
    if ($currentMember) {
        $notifLoans = \App\Models\Circulation::with('bookItem.book')
            ->where('member_id', $currentMember->id)
            ->where('status', 'Dipinjam')
            ->where('due_date', '<=', now()->addDays(3))
            ->get();
        $pendingNotifCount = $notifLoans->count();
    }
    
    // Count active loans & reservations for badge
    $activeLoanCount = $currentMember ? \App\Models\Circulation::where('member_id', $currentMember->id)->where('status', 'Dipinjam')->count() : 0;
    $activeReserveCount = $currentMember ? \App\Models\Reservation::where('member_id', $currentMember->id)->whereIn('status', ['Menunggu', 'Siap'])->count() : 0;
    $wishlistCount = \App\Models\Wishlist::where('user_id', auth()->id())->count();
@endphp

{{-- 1. Mobile Backdrop --}}
<div x-show="sidebarOpen" 
     x-transition.opacity.duration.200ms
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-40 lg:hidden" 
     style="display: none;"></div>

{{-- 2. Sidebar --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       class="-translate-x-full lg:translate-x-0 fixed lg:sticky top-0 left-0 z-50 h-screen w-64 bg-white border-r border-slate-200 flex flex-col justify-between transition-transform duration-200 ease-in-out flex-shrink-0 overflow-y-auto">
    
    <div>
        {{-- Brand --}}
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <a href="{{ route('member.dashboard') }}" class="flex items-center gap-3 no-underline">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center p-1.5 flex-shrink-0">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded-lg" width="40" height="40">
                </div>
                <div class="min-w-0">
                    <h1 class="font-bold text-sm text-slate-800 leading-tight truncate">
                        {{ \App\Models\Setting::get('library_name', __('Perpustakaan')) }}
                    </h1>
                    <span class="text-xs font-semibold text-indigo-600 block mt-0.5">{{ __('Portal Siswa') }}</span>
                </div>
            </a>

            <button @click="sidebarOpen = false" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 cursor-pointer">
                <i class="bi bi-x-lg text-base"></i>
            </button>
        </div>

        {{-- User Section --}}
        <div class="p-4 mx-3 my-3 rounded-xl bg-slate-50 border border-slate-200/70 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-bold text-sm flex items-center justify-center flex-shrink-0">
                @if($currentMember?->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($currentMember->photo))
                    <img src="{{ asset('storage/' . $currentMember->photo) }}" class="w-full h-full object-cover rounded-xl">
                @else
                    {{ strtoupper(substr($firstName, 0, 1)) }}
                @endif
            </div>
            <div class="min-w-0 flex-1">
                <p class="font-bold text-xs text-slate-800 truncate leading-tight">{{ $currentUser->name }}</p>
                <p class="text-[11px] font-mono text-slate-500 truncate mt-0.5">NIS: {{ $currentMember?->member_code ?? '-' }}</p>
            </div>
        </div>

        {{-- Nav Items --}}
        <nav class="px-3 space-y-1">
            <div class="px-3 pt-2 pb-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                {{ __('Menu') }}
            </div>

            {{-- Beranda --}}
            <a href="{{ route('member.dashboard') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-colors no-underline {{ request()->routeIs('member.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-grid-fill text-sm {{ request()->routeIs('member.dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>{{ __('Beranda') }}</span>
                </div>
            </a>

            {{-- Buku Saya --}}
            <a href="{{ route('member.my-books') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-colors no-underline {{ request()->routeIs('member.my-books') ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-book text-sm {{ request()->routeIs('member.my-books') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>{{ __('Buku Saya') }}</span>
                </div>
                @if($activeLoanCount > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ request()->routeIs('member.my-books') ? 'bg-white text-indigo-700' : 'bg-indigo-100 text-indigo-700' }}">
                        {{ $activeLoanCount }}
                    </span>
                @endif
            </a>

            {{-- Cari Buku --}}
            <a href="{{ route('member.catalog') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-colors no-underline {{ request()->routeIs('member.catalog*') ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-search text-sm {{ request()->routeIs('member.catalog*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>{{ __('Cari Buku') }}</span>
                </div>
            </a>

            {{-- Pemesanan --}}
            <a href="{{ route('member.reservations.index') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-colors no-underline {{ request()->routeIs('member.reservations*') ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-bookmark text-sm {{ request()->routeIs('member.reservations*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>{{ __('Pemesanan') }}</span>
                </div>
                @if($activeReserveCount > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                        {{ $activeReserveCount }}
                    </span>
                @endif
            </a>

            {{-- Favorit --}}
            <a href="{{ route('member.wishlist') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-colors no-underline {{ request()->routeIs('member.wishlist') ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-heart text-sm {{ request()->routeIs('member.wishlist') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>{{ __('Favorit') }}</span>
                </div>
                @if($wishlistCount > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ request()->routeIs('member.wishlist') ? 'bg-white text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                        {{ $wishlistCount }}
                    </span>
                @endif
            </a>

            {{-- Riwayat Baca --}}
            <a href="{{ route('member.loans') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-colors no-underline {{ request()->routeIs('member.loans') ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-clock-history text-sm {{ request()->routeIs('member.loans') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>{{ __('Riwayat Baca') }}</span>
                </div>
            </a>

            {{-- Kartu Anggota --}}
            <a href="{{ route('member.profile') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-colors no-underline {{ request()->routeIs('member.profile*') ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-person-badge text-sm {{ request()->routeIs('member.profile*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>{{ __('Kartu Anggota') }}</span>
                </div>
            </a>

            {{-- Denda / Tanggung Jawab --}}
            <a href="{{ route('member.fines') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-colors no-underline {{ request()->routeIs('member.fines') ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <div class="flex items-center gap-3">
                    <i class="bi bi-shield-check text-sm {{ request()->routeIs('member.fines') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>{{ __('Status Denda') }}</span>
                </div>
            </a>
        </nav>
    </div>

    {{-- Bottom --}}
    <div class="p-3 border-t border-slate-100 space-y-1">
        <a href="{{ route('opac.index') }}" target="_blank" 
           class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition-colors no-underline">
            <span class="flex items-center gap-2">
                <i class="bi bi-globe"></i>
                <span>{{ __('Katalog Web') }}</span>
            </span>
            <i class="bi bi-box-arrow-up-right text-[10px]"></i>
        </a>

        <form action="{{ route('logout') }}" method="POST"
              data-confirm="{{ __('Apakah kamu yakin ingin keluar dari portal siswa?') }}"
              data-confirm-title="{{ __('Keluar dari Akun?') }}"
              data-confirm-button="{{ __('Ya, Keluar') }}"
              data-confirm-cancel="{{ __('Batal') }}"
              data-confirm-danger="true">
            @csrf
            <button type="submit" 
                    class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-slate-500 hover:bg-rose-50 hover:text-rose-600 transition-colors cursor-pointer text-left">
                <i class="bi bi-box-arrow-right"></i>
                <span>{{ __('Keluar') }}</span>
            </button>
        </form>
    </div>

</aside>

{{-- 3. Main Content Wrapper --}}
<div class="flex-1 flex flex-col min-w-0 min-h-screen">

    {{-- Top Header --}}
    <header class="sticky top-0 z-30 bg-white border-b border-slate-200 px-4 sm:px-6 py-3 flex items-center justify-between">
        
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = true" 
                    class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 cursor-pointer"
                    title="{{ __('Buka Menu') }}">
                <i class="bi bi-list text-xl"></i>
            </button>

            <div>
                <h2 class="font-bold text-slate-800 text-sm sm:text-base leading-tight">
                    {{ __('Halo, :name', ['name' => $firstName]) }}
                </h2>
                <p class="text-xs text-slate-500 hidden sm:block">
                    {{ __('Selamat datang di perpustakaan sekolah.') }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 sm:gap-3">
            {{-- Language Switcher --}}
            <div class="flex items-center bg-slate-100 rounded-lg p-1 border border-slate-200">
                <a href="{{ route('lang.switch', 'id') }}" class="px-2 py-0.5 rounded text-xs font-bold transition {{ app()->getLocale() == 'id' ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">ID</a>
                <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-0.5 rounded text-xs font-bold transition {{ app()->getLocale() == 'en' ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">EN</a>
            </div>

            {{-- Notification --}}
            <div class="relative" @click.away="notifOpen = false">
                <button type="button" @click="notifOpen = !notifOpen" 
                        class="w-9 h-9 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-600 transition-colors flex items-center justify-center relative cursor-pointer" 
                        title="{{ __('Pemberitahuan') }}">
                    <i class="bi bi-bell text-sm"></i>
                    @if($pendingNotifCount > 0)
                    <span class="absolute top-1 right-1 w-2 h-2 bg-rose-500 rounded-full"></span>
                    @endif
                </button>

                {{-- Notification Popover --}}
                <div x-show="notifOpen" x-transition.opacity.duration.150ms class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden z-50" style="display: none;">
                    <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                        <span class="font-bold text-xs text-slate-700">{{ __('Pemberitahuan') }}</span>
                        @if($pendingNotifCount > 0)
                        <span class="bg-rose-100 text-rose-700 text-[10px] font-bold px-2 py-0.5 rounded-full">
                            {{ __(':count Perlu Diperhatikan', ['count' => $pendingNotifCount]) }}
                        </span>
                        @endif
                    </div>

                    <div class="max-h-72 overflow-y-auto divide-y divide-slate-100">
                        @forelse($notifLoans as $notifLoan)
                            @php
                                $daysLeft = (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($notifLoan->due_date)->startOfDay(), false);
                                $isOverdueNotif = $daysLeft < 0;
                            @endphp
                            <div class="p-3 text-xs {{ $isOverdueNotif ? 'bg-rose-50/50' : 'bg-amber-50/50' }}">
                                <p class="font-bold text-slate-800 line-clamp-1 mb-0.5">
                                    {{ $notifLoan->bookItem?->book?->title }}
                                </p>
                                <p class="text-[11px] {{ $isOverdueNotif ? 'text-rose-600 font-semibold' : 'text-amber-700 font-medium' }} mb-1">
                                    {{ $isOverdueNotif ? __('Terlambat :days hari. Mohon kembalikan ke perpustakaan.', ['days' => abs($daysLeft)]) : __('Batas pengembalian :time.', ['time' => ($daysLeft == 0 ? __('hari ini') : __(':days hari lagi', ['days' => $daysLeft]))]) }}
                                </p>
                                <span class="text-[10px] text-slate-400">{{ __('Jatuh tempo:') }} {{ \Carbon\Carbon::parse($notifLoan->due_date)->format('d M Y') }}</span>
                            </div>
                        @empty
                            <div class="py-6 text-center text-xs text-slate-400">
                                {{ __('Tidak ada pemberitahuan pengembalian.') }}
                            </div>
                        @endforelse
                    </div>

                    <div class="p-2.5 bg-slate-50 border-t border-slate-100 text-center">
                        <a href="{{ route('member.my-books') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 no-underline">
                            {{ __('Lihat Semua Buku Saya') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- Quick Profile --}}
            <a href="{{ route('member.profile') }}" 
               class="flex items-center gap-2 p-1 sm:px-2.5 sm:py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors no-underline">
                <div class="w-7 h-7 rounded-md bg-indigo-600 text-white font-bold text-xs flex items-center justify-center">
                    {{ strtoupper(substr($firstName, 0, 1)) }}
                </div>
                <span class="text-xs font-semibold text-slate-700 hidden sm:inline">{{ $firstName }}</span>
            </a>
        </div>

    </header>

    {{-- Flash Alerts --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-4 w-full">
        @if(session('success'))
        <div class="flex items-center gap-2.5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium mb-3">
            <i class="bi bi-check-circle-fill text-emerald-600 text-sm"></i>
            <div class="flex-1">{{ session('success') }}</div>
        </div>
        @endif

        @if(session('warning'))
        <div class="flex items-center gap-2.5 p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-medium mb-3">
            <i class="bi bi-exclamation-triangle-fill text-amber-600 text-sm"></i>
            <div class="flex-1">{{ session('warning') }}</div>
        </div>
        @endif

        @if(session('error') || (isset($errors) && $errors->any()))
        <div class="flex items-center gap-2.5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium mb-3">
            <i class="bi bi-x-circle-fill text-rose-600 text-sm"></i>
            <div class="flex-1">{{ session('error') ?: (isset($errors) ? $errors->first() : '') }}</div>
        </div>
        @endif
    </div>

    {{-- Main Content --}}
    <main class="flex-1 max-w-6xl mx-auto px-4 sm:px-6 py-5 w-full">
        @yield('content')
    </main>

    {{-- Clean Minimalist Footer --}}
    <footer class="mt-auto bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-400">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>{{ \App\Models\Setting::get('library_name', __('Perpustakaan')) }}</span>
            <span>&copy; {{ date('Y') }} {{ __('Portal Siswa') }}</span>
        </div>
    </footer>

</div>

<x-toast />

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Universal Modern Confirmation Interceptor for Forms with data-confirm
        document.addEventListener('submit', function (e) {
            const form = e.target;
            const confirmMsg = form.dataset.confirm;
            if (!confirmMsg || form.dataset.confirmed === 'true') return;

            e.preventDefault();
            const title = form.dataset.confirmTitle || '{{ __('Konfirmasi Tindakan') }}';
            const subtitle = form.dataset.confirmSubtitle || '';
            const confirmBtnText = form.dataset.confirmButton || '{{ __('Ya, Lanjutkan') }}';
            const cancelBtnText = form.dataset.confirmCancel || '{{ __('Batal') }}';
            const isDanger = form.dataset.confirmDanger === 'true';
            const icon = form.dataset.confirmIcon || (isDanger ? 'warning' : 'question');

            let htmlContent = `<div class="text-xs sm:text-sm text-slate-600 leading-relaxed">${confirmMsg}</div>`;
            if (subtitle) {
                htmlContent += `<div class="text-[11px] sm:text-xs text-slate-500 mt-2.5 bg-slate-50 p-2.5 rounded-xl border border-slate-100 text-left">${subtitle}</div>`;
            }

            if (window.Swal) {
                Swal.fire({
                    title: title,
                    html: htmlContent,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonText: confirmBtnText,
                    cancelButtonText: cancelBtnText,
                    confirmButtonColor: isDanger ? '#e11d48' : '#0f172a',
                    cancelButtonColor: '#94a3b8',
                    reverseButtons: true,
                    focusConfirm: true,
                    customClass: {
                        popup: 'rounded-3xl p-6 shadow-2xl border border-slate-100 max-w-sm sm:max-w-md',
                        title: 'text-slate-900 text-lg sm:text-xl font-bold pt-1',
                        confirmButton: 'rounded-xl font-semibold text-xs sm:text-sm px-5 py-2.5 shadow-sm',
                        cancelButton: 'rounded-xl font-semibold text-xs sm:text-sm px-5 py-2.5 shadow-sm'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.dataset.confirmed = 'true';
                        Swal.fire({
                            title: '{{ __('Memproses...') }}',
                            text: '{{ __('Mohon tunggu sebentar.') }}',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        form.submit();
                    }
                });
            } else if (confirm(confirmMsg)) {
                form.dataset.confirmed = 'true';
                form.submit();
            }
        });
    });
</script>

@stack('scripts')
</body>
</html>
