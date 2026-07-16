<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - INLIS Lite 3</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>

{{-- ═══ SIDEBAR ══════════════════════════════════════════════════════════════ --}}
<nav id="sidebar" class="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-book-half"></i></div>
        <div>
            <div>INLIS Lite 3</div>
            <div style="font-size:0.6rem;font-weight:400;opacity:0.6">Perpustakaan Digital</div>
        </div>
    </a>

    <div class="sidebar-menu">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        {{-- Katalogisasi --}}
        <div class="menu-label">Katalogisasi</div>
        <a href="{{ route('books.index') }}" class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}">
            <i class="bi bi-journals"></i> Master Buku
        </a>
        <a href="{{ route('authors.index') }}" class="nav-link {{ request()->routeIs('authors.*') ? 'active' : '' }}">
            <i class="bi bi-person-lines-fill"></i> Pengarang
        </a>
        <a href="{{ route('publishers.index') }}" class="nav-link {{ request()->routeIs('publishers.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i> Penerbit
        </a>
        <a href="{{ route('subjects.index') }}" class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Subyek
        </a>
        <a href="{{ route('locations.index') }}" class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}">
            <i class="bi bi-geo-alt"></i> Lokasi/Rak
        </a>

        {{-- Sirkulasi --}}
        <div class="menu-label">Sirkulasi</div>
        <a href="{{ route('circulations.index') }}" class="nav-link {{ request()->routeIs('circulations.index') ? 'active' : '' }}">
            <i class="bi bi-arrow-left-right"></i> Transaksi
        </a>
        <a href="{{ route('circulations.loan') }}" class="nav-link {{ request()->routeIs('circulations.loan') ? 'active' : '' }}">
            <i class="bi bi-box-arrow-right"></i> Peminjaman
        </a>
        <a href="{{ route('circulations.return') }}" class="nav-link {{ request()->routeIs('circulations.return') ? 'active' : '' }}">
            <i class="bi bi-box-arrow-in-left"></i> Pengembalian
        </a>

        {{-- Keanggotaan --}}
        <div class="menu-label">Keanggotaan</div>
        <a href="{{ route('members.index') }}" class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Data Anggota
        </a>

        {{-- Laporan --}}
        <div class="menu-label">Laporan</div>
        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i> Laporan
        </a>

        {{-- Pengaturan --}}
        @can('manage-users')
        <div class="menu-label">Administrasi</div>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-shield-person"></i> Manajemen User
        </a>
        <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Pengaturan
        </a>
        @endcan

    </div>

    <div class="sidebar-footer">
        v3.0.0 &copy; {{ date('Y') }} INLIS Lite
    </div>
</nav>

{{-- ═══ MAIN WRAPPER ══════════════════════════════════════════════════════════ --}}
<div class="main-wrapper">

    {{-- Topbar --}}
    <header class="topbar">
        <button id="sidebarToggle" class="btn btn-sm btn-light d-lg-none me-2">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div class="topbar-title">@yield('page-title', 'Dashboard')</div>

        {{-- Quick search --}}
        <form action="{{ route('opac.index') }}" method="GET" class="d-none d-md-flex align-items-center" style="gap:0.5rem">
            <div class="input-group input-group-sm" style="width:280px">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="q" class="form-control border-start-0 bg-light" placeholder="Cari buku...">
            </div>
        </form>

        {{-- User dropdown --}}
        <div class="dropdown ms-2">
            <button class="btn btn-sm btn-light d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                <div style="width:30px;height:30px;background:linear-gradient(135deg,#4299e1,#2b6cb0);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.75rem">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="d-none d-md-inline fw-500 text-truncate" style="max-width:120px">{{ auth()->user()->name }}</span>
                <i class="bi bi-chevron-down" style="font-size:0.65rem"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="min-width:200px;border-radius:10px">
                <li><h6 class="dropdown-header">{{ auth()->user()->email }}</h6></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </header>

    {{-- Content --}}
    <main class="content-area">
        {{-- Breadcrumb --}}
        @hasSection('breadcrumb')
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house"></i></a></li>
                @yield('breadcrumb')
            </ol>
        </nav>
        @endif

        {{-- Flash messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius:10px">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius:10px">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </main>

</div>{{-- end .main-wrapper --}}

@stack('scripts')
</body>
</html>
