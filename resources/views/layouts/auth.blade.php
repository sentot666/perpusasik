<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('Login')) - {{ \App\Models\Setting::get('library_name', config('app.name', 'Makarya')) }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col items-center justify-center bg-blue-50 py-12 px-4 sm:px-6 lg:px-8 relative" style="font-family: 'Inter', sans-serif;">
    {{-- Floating Language Switcher --}}
    <div class="fixed top-4 right-4 z-50 flex items-center bg-white/80 backdrop-blur-md rounded-xl p-1 shadow-sm border border-slate-200">
        <a href="{{ route('lang.switch', 'id') }}" class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all {{ app()->getLocale() == 'id' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">ID</a>
        <a href="{{ route('lang.switch', 'en') }}" class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all {{ app()->getLocale() == 'en' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">EN</a>
    </div>

    @yield('content')
    <x-toast />
    @stack('scripts')
</body>
</html>
