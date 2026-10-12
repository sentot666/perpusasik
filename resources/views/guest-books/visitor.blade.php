@extends('layouts.auth')

@section('title', 'Buku Tamu Pengunjung')

@section('content')
<div class="w-full max-w-4xl mx-auto bg-white rounded-3xl shadow-2xl border border-[#dee3e9] overflow-hidden flex flex-col md:flex-row min-h-[580px]">
    
    {{-- Left side: Meta Dark Brand Showcase (Hidden on mobile) --}}
    <div class="w-full md:w-1/2 bg-[#0a1317] text-white relative overflow-hidden flex-col items-center justify-center p-8 hidden md:flex border-r border-[#dee3e9]/20">
        {{-- Subtle Ambient Glows --}}
        <div class="absolute -top-20 -left-20 w-72 h-72 bg-[#0064e0]/25 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-[#0457cb]/25 rounded-full blur-3xl pointer-events-none"></div>

        {{-- Content --}}
        <div class="z-10 relative flex flex-col items-center w-full text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white p-2 mb-6 shadow-xl">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded-xl mix-blend-multiply">
            </div>
            
            <img src="{{ asset('images/library-illustration.png') }}" alt="Library Illustration" class="w-full max-w-[240px] mx-auto rounded-2xl shadow-xl border border-white/10 mb-6 object-cover bg-white">
            
            <div>
                <span class="inline-block px-3.5 py-1 rounded-full bg-white/10 text-[#0091ff] text-xs font-bold uppercase tracking-wider mb-2.5 border border-white/10 shadow-sm">
                    Buku Tamu Mandiri
                </span>
                <h1 class="text-2xl font-extrabold tracking-tight text-white leading-tight">
                    {{ \App\Models\Setting::get('library_name', config('app.name', 'Makarya')) }}
                </h1>
                <p class="text-[#ced0d4] text-xs mt-2 font-medium max-w-xs mx-auto leading-relaxed">
                    Catat kehadiran kunjungan perpustakaan Anda secara cepat, mudah, dan teratur.
                </p>
            </div>
        </div>
    </div>

    {{-- Right side: Visitor Form --}}
    <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-white relative z-10">
        
        {{-- Mobile Header (Visible only on small screens) --}}
        <div class="md:hidden flex flex-col items-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-white p-1 mb-3 shadow border border-[#dee3e9]">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded-xl">
            </div>
            <h1 class="text-lg font-extrabold text-[#0a1317]">{{ \App\Models\Setting::get('library_name', config('app.name', 'Makarya')) }}</h1>
            <p class="text-[#5d6c7b] text-xs mt-0.5">Buku Tamu Mandiri Pengunjung</p>
        </div>

        <div class="text-center mb-6">
            <span class="text-xs font-bold text-[#0064e0] uppercase tracking-wider">Buku Tamu Pengunjung</span>
            <h2 class="font-extrabold text-[#0a1317] mt-1 text-xl md:text-2xl tracking-tight">Selamat Datang</h2>
            <p class="text-[#5d6c7b] text-xs mt-1">Silakan isi formulir kehadiran perpustakaan hari ini.</p>
        </div>

        @if(session('success'))
        <div class="flex items-center gap-2 bg-[#31a24c]/10 border border-[#31a24c]/30 text-[#31a24c] rounded-xl text-xs mb-5 py-2.5 px-4 font-semibold">
            <i class="bi bi-check-circle-fill flex-shrink-0 text-base"></i>
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                window.location.href = "{{ route('guest-books.visitor') }}";
            }, 4500);
        </script>
        @endif

        @if($errors->any())
        <div class="flex items-center gap-2 bg-[#e41e3f]/10 border border-[#e41e3f]/30 text-[#e41e3f] rounded-xl text-xs mb-5 py-2.5 px-4 font-semibold">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0 text-base"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
        @endif

        <form action="{{ route('guest-books.visitor.store') }}" method="POST" x-data="{ purpose: '{{ old('purpose') }}' }">
            @csrf
            
            {{-- Input: Name --}}
            <div class="mb-4">
                <label for="name" class="block text-xs font-bold text-[#1c1e21] mb-1.5 uppercase tracking-wide">
                    Nama Lengkap Anda <span class="text-[#e41e3f]">*</span>
                </label>
                <div class="flex w-full rounded-xl overflow-hidden border border-[#ced0d4] focus-within:border-[#0064e0] focus-within:ring-2 focus-within:ring-[#0064e0]/20 transition-all shadow-sm bg-white">
                    <span class="flex items-center px-3.5 bg-[#f1f4f7] border-r border-[#ced0d4] text-[#5d6c7b]">
                        <i class="bi bi-person text-base"></i>
                    </span>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        class="flex-1 bg-white text-sm outline-none py-2.5 px-3.5 text-[#1c1e21] font-medium placeholder:text-[#8595a4]" 
                        placeholder="Ketik nama lengkap..." 
                        value="{{ old('name') }}" 
                        required 
                        autofocus
                    >
                </div>
            </div>

            {{-- Input: Institution --}}
            <div class="mb-4">
                <label for="institution" class="block text-xs font-bold text-[#1c1e21] mb-1.5 uppercase tracking-wide">
                    Asal Instansi / Kelas / Alamat <span class="text-[#e41e3f]">*</span>
                </label>
                <div class="flex w-full rounded-xl overflow-hidden border border-[#ced0d4] focus-within:border-[#0064e0] focus-within:ring-2 focus-within:ring-[#0064e0]/20 transition-all shadow-sm bg-white">
                    <span class="flex items-center px-3.5 bg-[#f1f4f7] border-r border-[#ced0d4] text-[#5d6c7b]">
                        <i class="bi bi-building text-base"></i>
                    </span>
                    <input 
                        type="text" 
                        name="institution" 
                        id="institution" 
                        class="flex-1 bg-white text-sm outline-none py-2.5 px-3.5 text-[#1c1e21] font-medium placeholder:text-[#8595a4]" 
                        placeholder="Ketik sekolah/instansi/alamat..." 
                        value="{{ old('institution') }}" 
                        required
                    >
                </div>
            </div>

            {{-- Input: Purpose --}}
            <div class="mb-4">
                <label for="purpose" class="block text-xs font-bold text-[#1c1e21] mb-1.5 uppercase tracking-wide">
                    Tujuan Kunjungan <span class="text-[#e41e3f]">*</span>
                </label>
                <div class="flex w-full rounded-xl overflow-hidden border border-[#ced0d4] focus-within:border-[#0064e0] focus-within:ring-2 focus-within:ring-[#0064e0]/20 transition-all shadow-sm bg-white relative">
                    <span class="flex items-center px-3.5 bg-[#f1f4f7] border-r border-[#ced0d4] text-[#5d6c7b]">
                        <i class="bi bi-journal-check text-base"></i>
                    </span>
                    <select 
                        name="purpose" 
                        id="purpose" 
                        x-model="purpose" 
                        class="flex-1 bg-white text-sm outline-none py-2.5 px-3.5 text-[#1c1e21] font-medium appearance-none cursor-pointer pr-10" 
                        required
                    >
                        <option value="" disabled selected>Pilih tujuan kunjungan...</option>
                        <option value="Membaca Buku">Membaca Buku / Referensi</option>
                        <option value="Meminjam Buku">Meminjam Buku</option>
                        <option value="Mengembalikan Buku">Mengembalikan Buku</option>
                        <option value="Mengerjakan Tugas">Mengerjakan Tugas / Belajar Mandiri</option>
                        <option value="Studi Banding">Studi Banding / Kunjungan Dinas</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#8595a4] pointer-events-none">
                        <i class="bi bi-chevron-down text-xs"></i>
                    </span>
                </div>
            </div>

            {{-- Input: Notes (Shown only if purpose is Lainnya) --}}
            <div class="mb-4" x-show="purpose === 'Lainnya'" style="display: none;" x-transition>
                <label for="notes" class="block text-xs font-bold text-[#1c1e21] mb-1.5 uppercase tracking-wide">
                    Keterangan Tambahan <span class="text-[#e41e3f]">*</span>
                </label>
                <textarea 
                    name="notes" 
                    id="notes" 
                    class="w-full rounded-xl border border-[#ced0d4] text-sm focus:border-[#0064e0] focus:ring-2 focus:ring-[#0064e0]/20 outline-none py-2.5 px-3.5 text-[#1c1e21] font-medium shadow-sm transition-all placeholder:text-[#8595a4]" 
                    rows="3" 
                    placeholder="Tuliskan tujuan kunjungan Anda secara spesifik..." 
                    :required="purpose === 'Lainnya'"
                >{{ old('notes') }}</textarea>
            </div>

            {{-- Input: Participants Count --}}
            <div class="mb-6">
                <label for="participants_count" class="block text-xs font-bold text-[#1c1e21] mb-1.5 uppercase tracking-wide">
                    Jumlah Orang <span class="text-[#e41e3f]">*</span>
                </label>
                <div class="flex w-full rounded-xl overflow-hidden border border-[#ced0d4] focus-within:border-[#0064e0] focus-within:ring-2 focus-within:ring-[#0064e0]/20 transition-all shadow-sm bg-white">
                    <span class="flex items-center px-3.5 bg-[#f1f4f7] border-r border-[#ced0d4] text-[#5d6c7b]">
                        <i class="bi bi-people text-base"></i>
                    </span>
                    <input 
                        type="number" 
                        name="participants_count" 
                        id="participants_count" 
                        class="flex-1 bg-white text-sm outline-none py-2.5 px-3.5 text-[#1c1e21] font-medium" 
                        min="1" 
                        value="1" 
                        required
                    >
                    <span class="flex items-center px-4 bg-[#f1f4f7] border-l border-[#ced0d4] text-[#5d6c7b] font-bold text-xs uppercase tracking-wider">
                        Orang
                    </span>
                </div>
            </div>

            {{-- Submit Pill CTA --}}
            <button 
                type="submit" 
                class="w-full flex items-center justify-center gap-2 bg-[#0064e0] hover:bg-[#0457cb] active:bg-[#0344a1] text-white font-bold text-sm rounded-full py-3.5 shadow-md shadow-blue-500/20 hover:shadow-lg transition-all cursor-pointer border-0"
            >
                <i class="bi bi-send-fill text-base"></i> Kirim Catatan Kunjungan
            </button>
        </form>

        {{-- Footer Back Link (Pill Ghost) --}}
        <div class="text-center mt-6 pt-5 border-t border-[#dee3e9]">
            <a href="{{ route('opac.index') }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-[#f1f4f7] hover:bg-[#dee3e9] text-[#1c1e21] text-xs font-bold transition-all no-underline">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda OPAC
            </a>
        </div>
    </div>
</div>
@endsection
