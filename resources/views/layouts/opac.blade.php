<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'OPAC - Online Public Access Catalog Perpustakaan')">
    <title>@yield('title', 'Katalog') - {{ config('app.name', 'Makarya') }} OPAC</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body style="background:#f7f9fc">

{{-- OPAC Navbar --}}
<nav class="navbar navbar-expand-lg" style="background:#1e3a5f;padding:0.75rem 1.5rem">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2 text-white fw-bold" href="{{ route('opac.index') }}">
            <div style="width:32px;height:32px;background:rgba(255,255,255,0.15);border-radius:8px;display:flex;align-items:center;justify-content:center">
                <i class="bi bi-book-half"></i>
            </div>
            {{ config('app.name', 'Makarya') }}
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#opacNav">
            <i class="bi bi-list text-white fs-4"></i>
        </button>
        <div class="collapse navbar-collapse" id="opacNav">
            <form action="{{ route('opac.index') }}" method="GET" class="d-flex mx-auto my-2 my-lg-0" style="width:100%;max-width:500px">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Cari judul, pengarang, ISBN, subyek..." value="{{ request('q') }}">
                    <button type="submit" class="btn btn-warning fw-600"><i class="bi bi-search"></i></button>
                </div>
            </form>
            <ul class="navbar-nav ms-auto gap-1">
                <li class="nav-item"><a class="nav-link text-white-50" href="{{ route('opac.index') }}">Katalog</a></li>
                @auth
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('dashboard') }}">Dashboard</a></li>
                @else
                <li class="nav-item"><a class="nav-link btn btn-outline-light btn-sm px-3" href="{{ route('login') }}">Login Petugas</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer style="background:#1e3a5f;color:rgba(255,255,255,0.5);text-align:center;padding:1.5rem;font-size:0.8rem;margin-top:4rem">
    &copy; {{ date('Y') }} {{ config('app.name', 'Makarya') }} — Sistem Informasi Perpustakaan
</footer>

@stack('scripts')
</body>
</html>
