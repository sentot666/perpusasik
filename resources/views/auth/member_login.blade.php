@extends('layouts.auth')
@section('title', 'Masuk Portal Siswa')

@section('content')
<div class="w-full max-w-4xl mx-auto bg-white rounded-3xl shadow-2xl border border-[#dee3e9] overflow-hidden flex flex-col md:flex-row min-h-[520px]">
    
    {{-- Left side: Meta Dark Brand Showcase (Hidden on mobile) --}}
    <div class="w-full md:w-5/12 bg-[#0a1317] text-white p-8 sm:p-10 flex flex-col justify-between hidden md:flex border-r border-[#dee3e9]/20 relative overflow-hidden">
        {{-- Subtle Blue Ambient Glow --}}
        <div class="absolute -top-20 -left-20 w-72 h-72 bg-[#0064e0]/25 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-[#0457cb]/25 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center p-1 border border-[#dee3e9] flex-shrink-0 shadow-sm">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded-lg mix-blend-multiply">
                </div>
                <div class="min-w-0">
                    <h2 class="font-bold text-sm text-white leading-tight truncate">
                        {{ \App\Models\Setting::get('library_name', 'Perpustakaan') }}
                    </h2>
                    <span class="text-xs text-[#0091ff] font-bold block mt-0.5">Portal Anggota Siswa</span>
                </div>
            </div>

            <h3 class="text-2xl font-extrabold tracking-tight text-white mb-3 leading-snug">
                Layanan Perpustakaan Digital Sekolah
            </h3>
            <p class="text-xs text-[#ced0d4] leading-relaxed">
                Akses peminjaman buku, katalog cerita, riwayat membaca, dan koleksi e-book langsung dari perangkatmu.
            </p>
        </div>

        <div class="space-y-2.5 pt-6 border-t border-white/10 text-xs text-[#ced0d4] relative z-10">
            <div class="flex items-center gap-2">
                <i class="bi bi-check2 text-[#0091ff]"></i>
                <span>Cari buku cetak & baca e-book sekolah</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="bi bi-check2 text-[#0091ff]"></i>
                <span>Pesan buku untuk diambil saat istirahat</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="bi bi-check2 text-[#0091ff]"></i>
                <span>Cek jadwal pengembalian buku dengan mudah</span>
            </div>
        </div>
    </div>

    {{-- Right side: Login Form --}}
    <div class="w-full md:w-7/12 p-8 sm:p-12 flex flex-col justify-center bg-white relative z-10">
        
        {{-- Mobile Header --}}
        <div class="md:hidden flex items-center gap-3 mb-6 pb-4 border-b border-[#dee3e9]">
            <div class="w-10 h-10 rounded-xl bg-white border border-[#dee3e9] flex items-center justify-center p-1 flex-shrink-0 shadow-sm">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded-lg">
            </div>
            <div>
                <h2 class="font-bold text-sm text-[#0a1317]">Portal Siswa</h2>
                <p class="text-xs text-[#5d6c7b]">{{ \App\Models\Setting::get('library_name', 'Perpustakaan') }}</p>
            </div>
        </div>

        <div class="mb-6">
            <span class="text-xs font-bold text-[#0064e0] uppercase tracking-wider">Akses Siswa & Anggota</span>
            <h1 class="text-xl sm:text-2xl font-extrabold text-[#0a1317] tracking-tight mt-1 mb-1">
                Masuk ke Portal Siswa
            </h1>
            <p class="text-xs text-[#5d6c7b]">
                Masukkan NIS atau nama lengkap beserta kata sandimu.
            </p>
        </div>

        @if(session('status'))
            <div class="p-3 rounded-xl bg-[#31a24c]/10 border border-[#31a24c]/30 text-[#31a24c] text-xs mb-4 font-semibold">
                {{ session('status') }}
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="p-3 rounded-xl bg-[#e41e3f]/10 border border-[#e41e3f]/30 text-[#e41e3f] text-xs mb-4 font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="memberLoginForm" class="space-y-4">
            @csrf

            {{-- Input NIS / Name --}}
            <div>
                <label class="block text-xs font-bold text-[#1c1e21] mb-1.5 uppercase tracking-wide">
                    NIS atau Nama Siswa
                </label>
                <div class="relative">
                    <input type="text"
                           name="login"
                           id="login"
                           class="w-full px-3.5 py-2.5 bg-white border border-[#ced0d4] rounded-xl text-xs sm:text-sm font-medium text-[#1c1e21] placeholder:text-[#8595a4] focus:border-[#0064e0] focus:ring-2 focus:ring-[#0064e0]/20 outline-none transition-all shadow-sm"
                           placeholder="Contoh: 26010001 atau nama lengkap"
                           value="{{ old('login') }}"
                           required
                           autofocus>
                </div>
            </div>

            {{-- Input Password --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold text-[#1c1e21] uppercase tracking-wide">
                        Kata Sandi (Password)
                    </label>
                    <span class="text-[11px] text-[#8595a4]">Format: YYYY-MM-DD</span>
                </div>
                <div class="relative flex items-center">
                    <input type="password"
                           name="password"
                           id="password"
                           class="w-full px-3.5 py-2.5 pr-10 bg-white border border-[#ced0d4] rounded-xl text-xs sm:text-sm font-medium text-[#1c1e21] placeholder:text-[#8595a4] focus:border-[#0064e0] focus:ring-2 focus:ring-[#0064e0]/20 outline-none transition-all shadow-sm"
                           placeholder="Contoh: 2012-08-17"
                           required>
                    <button type="button" 
                            class="absolute right-3 text-[#8595a4] hover:text-[#0064e0] cursor-pointer" 
                            id="togglePassword"
                            title="Tampilkan / Sembunyikan">
                        <i class="bi bi-eye text-sm" id="eyeIcon"></i>
                    </button>
                </div>
                <p class="text-[11px] text-[#8595a4] mt-1.5">
                    Gunakan tanggal lahirmu sebagai kata sandi awal (contoh: <code class="bg-[#f1f4f7] px-1 py-0.5 rounded text-[#0a1317]">2012-08-17</code>).
                </p>
            </div>

            {{-- Submit Button (Meta Cobalt Pill) --}}
            <div class="pt-2">
                <button type="submit" 
                        class="w-full py-3.5 px-6 bg-[#0064e0] hover:bg-[#0457cb] active:bg-[#0344a1] text-white font-bold text-xs uppercase tracking-wider rounded-full shadow-md shadow-blue-500/20 transition-all cursor-pointer" 
                        id="loginBtn">
                    Masuk ke Portal Siswa
                </button>
            </div>
        </form>

        {{-- Footer links --}}
        <div class="mt-6 pt-5 border-t border-[#dee3e9] flex items-center justify-between text-xs">
            <a href="{{ route('opac.index') }}" class="text-[#5d6c7b] hover:text-[#0064e0] font-bold no-underline">
                &larr; Beranda Utama
            </a>
            <a href="{{ route('login') }}" class="text-[#8595a4] hover:text-[#0a1317] no-underline">
                Login Petugas
            </a>
        </div>
    </div>
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
