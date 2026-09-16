@extends('layouts.member')

@section('title', 'Kartu Anggota & Profil')

@push('styles')
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #printableCard, #printableCard * {
        visibility: visible;
    }
    #printableCard {
        position: absolute;
        left: 50%;
        top: 40px;
        transform: translateX(-50%);
        width: 360px !important;
        border: 1px solid #94a3b8 !important;
        box-shadow: none !important;
    }
    .no-print {
        display: none !important;
    }
}
</style>
@endpush

@section('content')
<div class="space-y-6 pb-8">

    {{-- Header --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 no-print">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">
                Kartu Anggota & Profil Siswa
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Kartu identitas perpustakaan digital dan informasi data diri siswa.
            </p>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            <button onclick="window.print()" 
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors cursor-pointer">
                <i class="bi bi-printer"></i>
                <span>Cetak Kartu</span>
            </button>
            <a href="{{ route('member.profile.edit') }}" 
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition-colors no-underline">
                <i class="bi bi-pencil"></i>
                <span>Edit Profil</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- LEFT: Clean Student Library Card --}}
        <div class="lg:col-span-5 flex flex-col items-center">
            <div id="printableCard" class="w-full max-w-sm bg-slate-900 text-white rounded-2xl p-5 shadow-sm border border-slate-800 relative">
                
                {{-- Card Header --}}
                <div class="flex items-center justify-between border-b border-slate-700/80 pb-3 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white text-sm">
                            <i class="bi bi-book"></i>
                        </div>
                        <div>
                            <h2 class="font-bold text-xs text-white leading-tight uppercase tracking-wide">
                                {{ \App\Models\Setting::get('library_name', 'Perpustakaan') }}
                            </h2>
                            <p class="text-[10px] text-slate-400">Kartu Anggota Perpustakaan</p>
                        </div>
                    </div>
                    <span class="text-[9px] font-bold px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                        Aktif
                    </span>
                </div>

                {{-- Card Body --}}
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-18 h-22 rounded-xl overflow-hidden bg-slate-800 border border-slate-700 flex-shrink-0 flex items-center justify-center">
                        @if($member->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($member->photo))
                            <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="font-bold text-lg text-slate-400">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1 space-y-1 text-xs">
                        <div>
                            <span class="text-[10px] text-slate-400 block">Nama Siswa:</span>
                            <p class="font-bold text-white text-sm leading-tight truncate uppercase">{{ $member->name }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block">Nomor Induk (NIS):</span>
                            <p class="font-mono font-bold text-indigo-300">{{ $member->member_code }}</p>
                        </div>
                        <div class="text-[11px] text-slate-400">
                            JK: <strong class="text-slate-200">{{ $member->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</strong>
                        </div>
                    </div>
                </div>

                {{-- Barcode --}}
                <div class="bg-white rounded-xl p-2.5 flex flex-col items-center justify-center">
                    @if($barcodeBase64)
                        <img src="data:image/png;base64,{{ $barcodeBase64 }}" alt="Barcode" class="h-9 w-full max-w-[220px] object-contain">
                    @endif
                    <span class="font-mono text-[10px] text-slate-700 font-bold tracking-widest mt-1">
                        {{ $member->member_code }}
                    </span>
                </div>

            </div>

            <p class="text-[11px] text-slate-400 text-center mt-3 no-print">
                Tunjukkan barcode kartu ini kepada petugas saat meminjam buku.
            </p>
        </div>

        {{-- RIGHT: Clean Profile Information --}}
        <div class="lg:col-span-7 no-print">
            <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-sm">Informasi Data Diri</h3>
                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                        {{ $member->status_label }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block mb-0.5">Nama Lengkap</span>
                        <p class="font-semibold text-slate-800">{{ $member->name }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-0.5">Nomor Anggota (NIS)</span>
                        <p class="font-mono font-semibold text-slate-800">{{ $member->member_code }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-0.5">Nomor Identitas (NIK/NISN)</span>
                        <p class="font-medium text-slate-700">{{ $member->identity_number ?? '-' }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-0.5">Jenis Kelamin</span>
                        <p class="font-medium text-slate-700">{{ $member->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-0.5">No. Telepon / WA</span>
                        <p class="font-medium text-slate-700">{{ $member->phone ?? '-' }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-0.5">Alamat Email</span>
                        <p class="font-medium text-slate-700">{{ $member->email ?? '-' }}</p>
                    </div>

                    <div class="sm:col-span-2">
                        <span class="text-slate-400 block mb-0.5">Alamat Rumah</span>
                        <p class="font-medium text-slate-700">{{ $member->address ?? '-' }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-0.5">Tanggal Terdaftar</span>
                        <p class="font-medium text-slate-700">{{ $member->register_date ? $member->register_date->format('d F Y') : '-' }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-0.5">Masa Berlaku Kartu</span>
                        <p class="font-medium text-slate-700">{{ $member->expired_date ? $member->expired_date->format('d F Y') : '-' }}</p>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex gap-2">
                    <a href="{{ route('member.profile.edit') }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors no-underline">
                        <i class="bi bi-pencil"></i>
                        <span>Ubah Kontak & Password</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
