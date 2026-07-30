@extends('layouts.app')

@section('title', __('Profil & Kartu Anggota'))

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Profil & Kartu Anggota</h1>
            <p class="text-sm text-slate-500 mt-1">Data diri dan kartu keanggotaan digital Anda.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kartu Anggota Digital -->
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-xl overflow-hidden relative">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 rounded-full bg-white opacity-5"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-40 h-40 rounded-full bg-white opacity-5"></div>
                
                <div class="p-6 relative pb-16 min-h-[210px]">
                    <div class="flex justify-between items-start mb-4 border-b border-white/10 pb-3">
                        <div>
                            <h3 class="text-white font-bold text-base tracking-wide uppercase">{{ \App\Models\Setting::get('library_name', config('app.name', 'Makarya')) }}</h3>
                            <p class="text-slate-400 text-xs">Kartu Anggota Digital</p>
                        </div>
                        <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-white backdrop-blur-sm border border-white/20">
                            <i class="bi bi-person-badge text-lg"></i>
                        </div>
                    </div>

                    <div class="space-y-2 text-xs pr-32">
                        <div class="flex">
                            <span class="w-28 text-slate-400 font-semibold shrink-0">ID Anggota</span>
                            <span class="text-white font-mono font-bold truncate">: {{ $member->member_code }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-28 text-slate-400 font-semibold shrink-0">Nama</span>
                            <span class="text-white font-semibold uppercase truncate">: {{ $member->name }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-28 text-slate-400 font-semibold shrink-0">Nomor Identitas</span>
                            <span class="text-white truncate">: {{ $member->identity_number ?? '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-28 text-slate-400 font-semibold shrink-0">Alamat</span>
                            <span class="text-white flex-1 truncate">: {{ \Illuminate\Support\Str::limit($member->address ?? '-', 35) }}</span>
                        </div>
                    </div>

                    <!-- Barcode Container at absolute bottom right -->
                    <div class="absolute bottom-4 right-5 bg-white p-2 rounded-lg shadow-md text-right">
                        <img src="data:image/png;base64,{{ $barcodeBase64 }}" alt="Barcode {{ $member->barcode }}" class="h-9 w-28 object-contain rounded">
                        <div class="text-center font-mono text-[9px] mt-0.5 text-slate-800 tracking-wider font-semibold">{{ $member->member_code }}</div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 text-sm text-slate-500 text-center">
                <i class="bi bi-info-circle mr-1"></i> Tunjukkan kartu digital ini kepada petugas saat meminjam buku.
            </div>
        </div>

        <!-- Profil Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h2 class="text-lg font-bold text-slate-800">Detail Informasi</h2>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $member->status_badge_class === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $member->status_label }}
                    </span>
                </div>
                
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Nama Lengkap</dt>
                            <dd class="mt-1 text-sm text-slate-900 font-medium">{{ $member->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Nomor Telepon</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $member->phone ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Email</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $member->email ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Jenis Kelamin</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $member->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Alamat</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $member->address ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Tanggal Daftar</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $member->register_date ? $member->register_date->format('d F Y') : '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Berlaku Hingga</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $member->expired_date ? $member->expired_date->format('d F Y') : '-' }}</dd>
                        </div>
                    </dl>
                    
                    <div class="mt-8 border-t border-slate-100 pt-6">
                        <h3 class="text-sm font-bold text-slate-800 mb-4">Pengaturan Akun</h3>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors py-2 px-4">
                            <i class="bi bi-key mr-2"></i> Ubah Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
