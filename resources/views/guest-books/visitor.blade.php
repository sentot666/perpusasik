@extends('layouts.auth')

@section('title', 'Buku Tamu')

@section('content')
<div class="w-full max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[550px]">
    
    {{-- Left side: Image and Animation (Hidden on mobile) --}}
    <div class="w-full md:w-1/2 bg-gradient-to-br from-[#2b6cb0] to-[#1e3a5f] relative overflow-hidden flex flex-col items-center justify-center p-8 hidden md:flex">
        
        {{-- Animated background blob elements --}}
        <div class="absolute inset-0 z-0 opacity-40">
            <div class="absolute w-48 h-48 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob top-10 left-10"></div>
            <div class="absolute w-48 h-48 bg-teal-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000 top-20 right-10"></div>
            <div class="absolute w-48 h-48 bg-indigo-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000 bottom-10 left-1/2 -translate-x-1/2"></div>
        </div>

        {{-- Content --}}
        <div class="z-10 relative flex flex-col items-center w-full">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white p-1 mb-6 shadow-xl hover:scale-105 transition-transform duration-300">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded-xl mix-blend-multiply">
            </div>
            
            <img src="{{ asset('images/library-illustration.png') }}" alt="Library Illustration" class="w-full max-w-[260px] mx-auto hover:scale-105 transition-transform duration-500 rounded-2xl shadow-[0_0_20px_rgba(0,0,0,0.3)] border-4 border-white/20 mb-6 object-cover bg-white">
            
            <div class="text-center">
                <h1 class="text-2xl lg:text-3xl font-extrabold leading-tight text-white">{{ __('Buku Tamu Mandiri') }}</h1>
                <p class="text-white/80 text-sm mt-2 font-medium">{{ __('Perpustakaan Sekolah Katolik Santo Paulus') }}</p>
            </div>
        </div>
    </div>

    {{-- Right side: Visitor Form --}}
    <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center bg-white relative z-10">
        
        {{-- Mobile Header (Visible only on small screens) --}}
        <div class="md:hidden flex flex-col items-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white p-1 mb-3 shadow border border-slate-100">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-full h-full object-contain rounded-xl">
            </div>
            <h1 class="text-xl font-extrabold text-slate-800">{{ __('Buku Tamu Mandiri') }}</h1>
            <p class="text-slate-500 text-xs mt-1">{{ __('Perpustakaan Sekolah Katolik Santo Paulus') }}</p>
        </div>

        <h5 class="font-bold text-center text-slate-800 mb-1 text-xl md:text-2xl">{{ __('Selamat Datang') }}</h5>
        <p class="text-center text-slate-500 text-sm mb-6">{{ __('Silakan isi buku tamu kunjungan harian perpustakaan') }}</p>

        @if(session('success'))
            <script>
                // Redirect setelah toast selesai (4.5 detik)
                setTimeout(() => {
                    window.location.href = "{{ route('guest-books.visitor') }}";
                }, 4500);
            </script>
        @endif

        @if($errors->any())
            <div class="bg-red-50 text-red-700 rounded-lg text-xs mb-6 py-3 px-4 border border-red-200">
                <ul class="list-disc pl-4 mb-0 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('guest-books.visitor.store') }}" method="POST" x-data="{ purpose: '{{ old('purpose') }}' }">
            @csrf
            
            {{-- Real-time Visit Clock --}}
            <div class="mb-5 hidden" x-data="{ time: '', date: '' }" x-init="
                setInterval(() => {
                    const now = new Date();
                    time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    date = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                }, 1000);
            ">
                <label class="block text-xs font-semibold text-slate-700 mb-1.5 uppercase tracking-wide">{{ __('Waktu Kunjungan (Real-time)') }}</label>
                <div class="flex w-full rounded-lg overflow-hidden border border-indigo-200 transition-all shadow-sm">
                    <span class="flex items-center px-3.5 bg-indigo-50 border-r border-indigo-200 text-indigo-600"><i class="bi bi-clock-history text-lg"></i></span>
                    <div class="flex-1 bg-indigo-50/50 py-2.5 px-3 flex items-center justify-between text-indigo-800 font-medium text-sm">
                        <span x-text="date">{{ __('Memuat tanggal...') }}</span>
                        <span x-text="time" class="font-bold tracking-wider">--:--:--</span>
                    </div>
                </div>
            </div>

            {{-- Input: Name --}}
            <div class="mb-5">
                <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5 uppercase tracking-wide">{{ __('Nama Lengkap Anda') }} <span class="text-red-600">*</span></label>
                <div class="flex w-full rounded-lg overflow-hidden border border-slate-300 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-200 transition-all shadow-sm">
                    <span class="flex items-center px-3.5 bg-slate-50 border-r border-slate-300 text-slate-500"><i class="bi bi-person text-lg"></i></span>
                    <input type="text" name="name" id="name" class="flex-1 bg-white text-sm outline-none py-2.5 px-3 text-slate-700 font-medium" placeholder="{{ __('Ketik nama lengkap...') }}" value="{{ old('name') }}" required autofocus>
                </div>
            </div>

            {{-- Input: Institution --}}
            <div class="mb-5">
                <label for="institution" class="block text-xs font-semibold text-slate-700 mb-1.5 uppercase tracking-wide">{{ __('Asal Instansi / Kelas / Alamat') }} <span class="text-red-600">*</span></label>
                <div class="flex w-full rounded-lg overflow-hidden border border-slate-300 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-200 transition-all shadow-sm">
                    <span class="flex items-center px-3.5 bg-slate-50 border-r border-slate-300 text-slate-500"><i class="bi bi-building text-lg"></i></span>
                    <input type="text" name="institution" id="institution" class="flex-1 bg-white text-sm outline-none py-2.5 px-3 text-slate-700 font-medium" placeholder="{{ __('Ketik sekolah/instansi/alamat...') }}" value="{{ old('institution') }}" required>
                </div>
            </div>

            {{-- Input: Purpose --}}
            <div class="mb-5">
                <label for="purpose" class="block text-xs font-semibold text-slate-700 mb-1.5 uppercase tracking-wide">{{ __('Tujuan Kunjungan') }} <span class="text-red-600">*</span></label>
                <div class="flex w-full rounded-lg overflow-hidden border border-slate-300 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-200 transition-all shadow-sm">
                    <span class="flex items-center px-3.5 bg-slate-50 border-r border-slate-300 text-slate-500"><i class="bi bi-journal-check text-lg"></i></span>
                    <select name="purpose" id="purpose" x-model="purpose" class="flex-1 bg-white text-sm outline-none py-2.5 px-3 text-slate-700 font-medium appearance-none" required>
                        <option value="" disabled selected>{{ __('Pilih tujuan kunjungan...') }}</option>
                        <option value="Membaca Buku">{{ __('Membaca Buku / Referensi') }}</option>
                        <option value="Meminjam Buku">{{ __('Meminjam Buku') }}</option>
                        <option value="Mengembalikan Buku">{{ __('Mengembalikan Buku') }}</option>
                        <option value="Mengerjakan Tugas">{{ __('Mengerjakan Tugas / Belajar Mandiri') }}</option>
                        <option value="Studi Banding">{{ __('Studi Banding / Kunjungan Dinas') }}</option>
                        <option value="Lainnya">{{ __('Lainnya') }}</option>
                    </select>
                </div>
            </div>

            {{-- Input: Notes (Shown only if purpose is Lainnya) --}}
            <div class="mb-5" x-show="purpose === 'Lainnya'" style="display: none;" x-transition>
                <label for="notes" class="block text-xs font-semibold text-slate-700 mb-1.5 uppercase tracking-wide">{{ __('Keterangan Tambahan') }} <span class="text-red-600">*</span></label>
                <textarea name="notes" id="notes" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none py-2.5 px-3 text-slate-700 font-medium shadow-sm transition-all" rows="3" placeholder="{{ __('Tuliskan tujuan kunjungan Anda secara spesifik...') }}" :required="purpose === 'Lainnya'">{{ old('notes') }}</textarea>
            </div>

            {{-- Input: Participants Count --}}
            <div class="mb-6">
                <label for="participants_count" class="block text-xs font-semibold text-slate-700 mb-1.5 uppercase tracking-wide">{{ __('Jumlah Orang') }} <span class="text-red-600">*</span></label>
                <div class="flex w-full rounded-lg overflow-hidden border border-slate-300 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-200 transition-all shadow-sm">
                    <span class="flex items-center px-3.5 bg-slate-50 border-r border-slate-300 text-slate-500"><i class="bi bi-people text-lg"></i></span>
                    <input type="number" name="participants_count" id="participants_count" class="flex-1 bg-white text-sm outline-none py-2.5 px-3 text-slate-700 font-medium" min="1" value="1" required>
                    <span class="flex items-center px-3.5 bg-slate-50 border-l border-slate-300 text-slate-500 font-medium text-sm">{{ __('Orang') }}</span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <button type="submit" class="w-full flex items-center justify-center gap-2 btn-gradient-blue text-white font-bold text-sm rounded-lg py-3 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                <i class="bi bi-send text-lg"></i> {{ __('Kirim Catatan Kunjungan') }}
            </button>
        </form>

        <div class="text-center mt-8 pt-6 border-t border-slate-100">
            <a href="{{ route('opac.index') }}" class="inline-flex items-center text-slate-500 hover:text-indigo-600 font-medium transition-colors text-sm">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('Kembali ke Beranda') }}
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
</style>
@endpush

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
    });
</script>
@endsection

