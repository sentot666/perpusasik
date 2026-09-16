@extends('layouts.auth')
@section('title', 'Masuk Portal Siswa')

@section('content')
<div class="w-full max-w-4xl mx-auto bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden flex flex-col md:flex-row min-h-[520px]">
    
    {{-- Left side: School & Portal Branding --}}
    <div class="w-full md:w-5/12 bg-slate-900 text-white p-8 sm:p-10 flex flex-col justify-between hidden md:flex">
        <div>
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center p-1.5 border border-white/20 flex-shrink-0">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded-lg">
                </div>
                <div class="min-w-0">
                    <h2 class="font-bold text-sm text-white leading-tight truncate">
                        {{ \App\Models\Setting::get('library_name', 'Perpustakaan') }}
                    </h2>
                    <span class="text-xs text-indigo-400 font-semibold block mt-0.5">Portal Anggota Siswa</span>
                </div>
            </div>

            <h3 class="text-2xl font-bold tracking-tight text-white mb-3">
                Layanan Perpustakaan Digital Sekolah
            </h3>
            <p class="text-xs text-slate-400 leading-relaxed">
                Akses peminjaman buku, katalog cerita anak, riwayat membaca, dan koleksi e-book langsung dari gadget kamu.
            </p>
        </div>

        <div class="space-y-2 pt-6 border-t border-slate-800 text-xs text-slate-400">
            <div class="flex items-center gap-2">
                <i class="bi bi-check2 text-indigo-400"></i>
                <span>Cari buku cetak & baca e-book sekolah</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="bi bi-check2 text-indigo-400"></i>
                <span>Pesan buku untuk diambil saat istirahat</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="bi bi-check2 text-indigo-400"></i>
                <span>Cek jadwal pengembalian buku dengan mudah</span>
            </div>
        </div>
    </div>

    {{-- Right side: Login Form --}}
    <div class="w-full md:w-7/12 p-8 sm:p-12 flex flex-col justify-center bg-white">
        
        {{-- Mobile Header --}}
        <div class="md:hidden flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center p-1.5 flex-shrink-0">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded-lg">
            </div>
            <div>
                <h2 class="font-bold text-sm text-slate-800">Portal Siswa</h2>
                <p class="text-xs text-slate-400">Perpustakaan Santo Paulus</p>
            </div>
        </div>

        <div class="mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight mb-1">
                Masuk ke Portal Siswa
            </h1>
            <p class="text-xs sm:text-sm text-slate-500">
                Masukkan NIS atau nama lengkap beserta kata sandimu.
            </p>
        </div>

        @if(session('status'))
            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs mb-4">
                {{ session('status') }}
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="memberLoginForm" class="space-y-4">
            @csrf

            {{-- Input NIS / Name --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                    NIS atau Nama Siswa
                </label>
                <div class="relative">
                    <input type="text"
                           name="login"
                           id="login"
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 placeholder:text-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors"
                           placeholder="Contoh: 26010001 atau nama lengkap"
                           value="{{ old('login') }}"
                           required
                           autofocus>
                </div>
            </div>

            {{-- Input Password --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700">
                        Kata Sandi (Password)
                    </label>
                    <span class="text-[11px] text-slate-400">Format: YYYY-MM-DD</span>
                </div>
                <div class="relative flex items-center">
                    <input type="password"
                           name="password"
                           id="password"
                           class="w-full px-3.5 py-2.5 pr-10 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 placeholder:text-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors"
                           placeholder="Contoh: 2012-08-17"
                           required>
                    <button type="button" 
                            class="absolute right-3 text-slate-400 hover:text-slate-600 cursor-pointer" 
                            id="togglePassword"
                            title="Tampilkan / Sembunyikan">
                        <i class="bi bi-eye text-sm" id="eyeIcon"></i>
                    </button>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">
                    Gunakan tanggal lahirmu sebagai kata sandi awal (contoh: <code>2012-08-17</code>).
                </p>
            </div>

            {{-- Submit Button --}}
            <div class="pt-2">
                <button type="submit" 
                        class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs sm:text-sm rounded-xl transition-colors cursor-pointer" 
                        id="loginBtn">
                    Masuk ke Portal
                </button>
            </div>
        </form>

        {{-- Footer links --}}
        <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between text-xs">
            <a href="{{ route('opac.index') }}" class="text-slate-500 hover:text-slate-800 font-medium no-underline">
                &larr; Beranda Utama
            </a>
            <a href="{{ route('login') }}" class="text-slate-400 hover:text-slate-600 no-underline">
                Login Petugas
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.className = 'bi bi-eye-slash text-sm text-indigo-600';
    } else {
        pwd.type = 'password';
        icon.className = 'bi bi-eye text-sm text-slate-400';
    }
});

document.getElementById('memberLoginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = 'Memproses...';
    btn.disabled = true;
    btn.classList.add('opacity-70', 'cursor-not-allowed');
});
</script>
@endpush
@endsection
