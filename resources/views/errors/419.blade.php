<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - {{ __('Sesi Berakhir') }} | {{ \App\Models\Setting::get('library_name', 'Perpustakaan') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }</style>
</head>
<body class="h-full bg-slate-50 text-slate-800 flex items-center justify-center p-4 antialiased">
    <div class="max-w-md w-full text-center">
        <div class="w-20 h-20 bg-amber-50 text-amber-600 rounded-3xl mx-auto flex items-center justify-center text-4xl mb-6 shadow-xs border border-amber-100">
            <i class="bi bi-clock-history"></i>
        </div>
        <span class="inline-block px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full mb-3 uppercase tracking-wider">
            {{ __('Error 419') }}
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-2">
            {{ __('Sesi Halaman Berakhir') }}
        </h1>
        <p class="text-slate-500 text-xs sm:text-sm mb-8 leading-relaxed">
            {{ __('Halaman telah terbuka terlalu lama sehingga sesi keamanan kedaluwarsa. Silakan muat ulang halaman untuk melanjutkan.') }}
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <button onclick="window.location.reload()" 
                    class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer flex items-center justify-center gap-2">
                <i class="bi bi-arrow-clockwise"></i>
                <span>{{ __('Muat Ulang Halaman') }}</span>
            </button>
            <a href="{{ url('/') }}" 
               class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-semibold shadow-xs transition-colors no-underline flex items-center justify-center gap-2">
                <i class="bi bi-house"></i>
                <span>{{ __('Beranda') }}</span>
            </a>
        </div>
        
        <p class="text-[11px] text-slate-400 mt-8">
            {{ \App\Models\Setting::get('library_name', 'Perpustakaan') }}
        </p>
    </div>
</body>
</html>

