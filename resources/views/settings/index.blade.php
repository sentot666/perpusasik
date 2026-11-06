@extends('layouts.app')

@section('title', __('Pengaturan Perpustakaan'))
@section('page-title', __('Pengaturan'))

@section('breadcrumb')
<li class="breadcrumb-item active">{{ __('Pengaturan') }}</li>
@endsection

@section('content')
<div x-data="{ activeTab: 'general' }">
    {{-- Header --}}
    <div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-1">{{ __('Pengaturan Perpustakaan') }}</h1>
            <p class="text-slate-500 text-xs sm:text-sm">{{ __('Kelola identitas perpustakaan, aturan sirkulasi, denda, dan kartu anggota') }}</p>
        </div>
        <div>
            <button type="submit" form="settingsForm" class="inline-flex items-center justify-center text-sm font-semibold rounded-lg btn-gradient-blue transition-all gap-2 py-2.5 px-6 shadow-md hover:shadow-lg cursor-pointer text-white">
                <i class="bi bi-floppy"></i> {{ __('Simpan Pengaturan') }}
            </button>
        </div>
    </div>

    {{-- Error Banner --}}
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl mb-6 shadow-sm">
        <h4 class="font-bold mb-2 flex items-center gap-2"><i class="bi bi-exclamation-triangle-fill"></i> {{ __('Terdapat Kesalahan:') }}</h4>
        <ul class="list-disc list-inside space-y-1 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Success Notification --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-xl mb-6 shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-3">
            <i class="bi bi-check-circle-fill text-emerald-500 text-lg"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <form id="settingsForm" method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            {{-- Main Column (Tabs + Content) --}}
            <div class="lg:col-span-8 flex flex-col gap-6">

                {{-- Tab Navigation Pills --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-2 flex flex-wrap gap-1.5">
                    <button type="button" 
                            @click="activeTab = 'general'" 
                            :class="activeTab === 'general' ? 'bg-indigo-600 text-white shadow-xs font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-medium'"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm transition-all duration-150 cursor-pointer">
                        <i class="bi bi-building"></i>
                        <span>{{ __('Identitas & Kontak') }}</span>
                    </button>
                    <button type="button" 
                            @click="activeTab = 'circulation'" 
                            :class="activeTab === 'circulation' ? 'bg-indigo-600 text-white shadow-xs font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-medium'"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm transition-all duration-150 cursor-pointer">
                        <i class="bi bi-arrow-left-right"></i>
                        <span>{{ __('Sirkulasi & Denda') }}</span>
                    </button>
                    <button type="button" 
                            @click="activeTab = 'membership'" 
                            :class="activeTab === 'membership' ? 'bg-indigo-600 text-white shadow-xs font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-medium'"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm transition-all duration-150 cursor-pointer">
                        <i class="bi bi-person-badge"></i>
                        <span>{{ __('Keanggotaan & Kartu') }}</span>
                    </button>
                </div>

                {{-- ═══ TAB 1: IDENTITAS & KONTAK ═══════════════════════════════════ --}}
                <div x-show="activeTab === 'general'" x-cloak class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 sm:px-8 py-5 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                                <i class="bi bi-building"></i>
                            </div>
                            <div>
                                <h2 class="font-bold text-slate-800 text-base leading-tight">{{ __('Identitas & Kontak Perpustakaan') }}</h2>
                                <p class="text-xs text-slate-500 mt-0.5">{{ __('Informasi ini akan muncul di header, footer website OPAC, dan kartu anggota.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 sm:p-8 space-y-6">
                        {{-- Nama Perpustakaan --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                {{ __('Nama Perpustakaan') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <i class="bi bi-book"></i>
                                </span>
                                <input type="text" 
                                       name="settings[library_name]" 
                                       class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none transition-all"
                                       value="{{ \App\Models\Setting::get('library_name', config('app.name', 'Perpustakaan Makarya')) }}" 
                                       placeholder="Contoh: Perpustakaan Santo Paulus" required>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">{{ __('Nama lembaga atau perpustakaan yang ditampilkan ke pengguna.') }}</p>
                        </div>

                        {{-- Alamat Perpustakaan --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                {{ __('Alamat Lengkap') }}
                            </label>
                            <div class="relative">
                                <textarea name="settings[library_address]" 
                                          rows="3" 
                                          class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none transition-all"
                                          placeholder="Contoh: Jl. Danau Agung 13 Blok E19, Sunter Agung Podomoro, Jakarta Utara">{{ \App\Models\Setting::get('library_address', '') }}</textarea>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">{{ __('Alamat fisik perpustakaan untuk footer website dan identitas surat.') }}</p>
                        </div>

                        {{-- Telepon & Email --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    {{ __('Nomor Telepon') }}
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                        <i class="bi bi-telephone"></i>
                                    </span>
                                    <input type="text" 
                                           name="settings[library_phone]" 
                                           class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none transition-all"
                                           value="{{ \App\Models\Setting::get('library_phone', '') }}" 
                                           placeholder="Contoh: (021) 645-9109">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    {{ __('Alamat Email') }}
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email" 
                                           name="settings[library_email]" 
                                           class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none transition-all"
                                           value="{{ \App\Models\Setting::get('library_email', '') }}" 
                                           placeholder="Contoh: info@perpustakaan.sch.id">
                                </div>
                            </div>
                        </div>

                        {{-- Default Tujuan Kunjungan Buku Tamu --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                {{ __('Tujuan Kunjungan Default (Scan Buku Tamu)') }}
                            </label>
                            @php
                                $defaultPurpose = \App\Models\Setting::get('default_guest_purpose', 'Membaca');
                            @endphp
                            <select name="settings[default_guest_purpose]" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none bg-white transition-all">
                                <option value="Membaca" {{ $defaultPurpose == 'Membaca' ? 'selected' : '' }}>Membaca</option>
                                <option value="Meminjam Buku" {{ $defaultPurpose == 'Meminjam Buku' ? 'selected' : '' }}>Meminjam Buku</option>
                                <option value="Mengembalikan Buku" {{ $defaultPurpose == 'Mengembalikan Buku' ? 'selected' : '' }}>Mengembalikan Buku</option>
                                <option value="Belajar / Mengerjakan Tugas" {{ $defaultPurpose == 'Belajar / Mengerjakan Tugas' ? 'selected' : '' }}>Belajar / Mengerjakan Tugas</option>
                                <option value="Kunjungan Kelas" {{ $defaultPurpose == 'Kunjungan Kelas' ? 'selected' : '' }}>Kunjungan Kelas</option>
                            </select>
                            <p class="text-xs text-slate-400 mt-1">{{ __('Tujuan otomatis saat anggota melakukan scan barcode di pintu masuk.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- ═══ TAB 2: SIRKULASI & DENDA ═══════════════════════════════════ --}}
                <div x-show="activeTab === 'circulation'" x-cloak class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 sm:px-8 py-5 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center font-bold">
                                <i class="bi bi-arrow-left-right"></i>
                            </div>
                            <div>
                                <h2 class="font-bold text-slate-800 text-base leading-tight">{{ __('Aturan Sirkulasi & Denda') }}</h2>
                                <p class="text-xs text-slate-500 mt-0.5">{{ __('Aturan standar masa pinjam, limit eksemplar, dan denda keterlambatan buku.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 sm:p-8 space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            {{-- Durasi Pinjam --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    {{ __('Durasi Peminjaman Standar') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative flex rounded-lg shadow-xs">
                                    <input type="number" 
                                           name="settings[loan_duration]" 
                                           class="w-full px-4 py-2.5 rounded-l-lg border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none transition-all"
                                           value="{{ \App\Models\Setting::get('loan_duration', 14) }}" min="1" max="90" required>
                                    <span class="inline-flex items-center px-4 rounded-r-lg border border-l-0 border-slate-300 bg-slate-100 text-slate-600 text-xs font-semibold">
                                        {{ __('Hari') }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">{{ __('Berapa hari anggota boleh meminjam buku sebelum jatuh tempo.') }}</p>
                            </div>

                            {{-- Maksimal Eksemplar --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    {{ __('Batas Maksimal Pinjam') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative flex rounded-lg shadow-xs">
                                    <input type="number" 
                                           name="settings[max_loan_items]" 
                                           class="w-full px-4 py-2.5 rounded-l-lg border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none transition-all"
                                           value="{{ \App\Models\Setting::get('max_loan_items', 3) }}" min="1" max="20" required>
                                    <span class="inline-flex items-center px-4 rounded-r-lg border border-l-0 border-slate-300 bg-slate-100 text-slate-600 text-xs font-semibold">
                                        {{ __('Eksemplar') }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">{{ __('Jumlah buku maksimal yang boleh dipinjam bersamaan per anggota.') }}</p>
                            </div>

                            {{-- Maksimal Perpanjangan --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    {{ __('Maksimal Perpanjangan Pinjaman') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative flex rounded-lg shadow-xs">
                                    <input type="number" 
                                           name="settings[max_renewals]" 
                                           class="w-full px-4 py-2.5 rounded-l-lg border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none transition-all"
                                           value="{{ \App\Models\Setting::get('max_renewals', 2) }}" min="0" max="10" required>
                                    <span class="inline-flex items-center px-4 rounded-r-lg border border-l-0 border-slate-300 bg-slate-100 text-slate-600 text-xs font-semibold">
                                        {{ __('Kali') }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">{{ __('Berapa kali suatu peminjaman dapat diperpanjang masa pinjamnya.') }}</p>
                            </div>

                            {{-- Denda per Hari --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    {{ __('Denda Keterlambatan') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative flex rounded-lg shadow-xs">
                                    <span class="inline-flex items-center px-3.5 rounded-l-lg border border-r-0 border-slate-300 bg-slate-100 text-slate-600 text-xs font-bold">
                                        Rp
                                    </span>
                                    <input type="number" 
                                           name="settings[fine_per_day]" 
                                           class="w-full px-4 py-2.5 border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none transition-all"
                                           value="{{ \App\Models\Setting::get('fine_per_day', 1000) }}" min="0" step="100" required>
                                    <span class="inline-flex items-center px-3 rounded-r-lg border border-l-0 border-slate-300 bg-slate-100 text-slate-600 text-xs font-semibold">
                                        / hari
                                    </span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">{{ __('Besaran tarif denda per eksemplar per hari keterlambatan.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ TAB 3: KEANGGOTAAN & KARTU ═══════════════════════════════════ --}}
                <div x-show="activeTab === 'membership'" x-cloak class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 sm:px-8 py-5 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                                <i class="bi bi-person-badge"></i>
                            </div>
                            <div>
                                <h2 class="font-bold text-slate-800 text-base leading-tight">{{ __('Keanggotaan & Pengesahan Kartu') }}</h2>
                                <p class="text-xs text-slate-500 mt-0.5">{{ __('Pengaturan masa aktif kartu dan stempel pengesahan kepala perpustakaan.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 sm:p-8 space-y-6">
                        {{-- Masa Berlaku Anggota --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                {{ __('Masa Berlaku Keanggotaan Default') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative flex rounded-lg shadow-xs max-w-xs">
                                <input type="number" 
                                       name="settings[member_expiry_years]" 
                                       class="w-full px-4 py-2.5 rounded-l-lg border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none transition-all"
                                       value="{{ \App\Models\Setting::get('member_expiry_years', 1) }}" min="1" max="10" required>
                                <span class="inline-flex items-center px-4 rounded-r-lg border border-l-0 border-slate-300 bg-slate-100 text-slate-600 text-xs font-semibold">
                                    {{ __('Tahun') }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">{{ __('Masa aktif default otomatis saat anggota baru didaftarkan.') }}</p>
                        </div>

                        <hr class="border-slate-100">

                        <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                            <i class="bi bi-pen text-indigo-500"></i> {{ __('Penandatangan & Pengesahan Kartu Anggota') }}
                        </h3>

                        {{-- Nama & NIY Pustakawan --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    {{ __('Nama Kepala / Pustakawan') }}
                                </label>
                                <input type="text" 
                                       name="settings[librarian_name]" 
                                       class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none transition-all"
                                       value="{{ \App\Models\Setting::get('librarian_name', 'Felix Wijaya') }}" 
                                       placeholder="Contoh: Felix Wijaya, S.I.Pust">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    {{ __('Nomor NIY / NIP Pustakawan') }}
                                </label>
                                <input type="text" 
                                       name="settings[librarian_niy]" 
                                       class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none transition-all"
                                       value="{{ \App\Models\Setting::get('librarian_niy', 'NIY. -') }}" 
                                       placeholder="Contoh: NIY. 19820514 200801 1 002">
                            </div>
                        </div>

                        {{-- Jenis Pengesahan TTD --}}
                        @php
                            $signType = \App\Models\Setting::get('librarian_sign_type', 'qr_code');
                        @endphp
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                {{ __('Jenis Pengesahan Tanda Tangan di Kartu') }}
                            </label>
                            <select name="settings[librarian_sign_type]" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none bg-white transition-all">
                                <option value="qr_code" {{ $signType == 'qr_code' ? 'selected' : '' }}>{{ __('QR Code Verifikasi Digital (Otomatis & Modern)') }}</option>
                                <option value="upload_image" {{ $signType == 'upload_image' ? 'selected' : '' }}>{{ __('Gambar Stempel / Tanda Tangan Hasil Upload') }}</option>
                                <option value="blank" {{ $signType == 'blank' ? 'selected' : '' }}>{{ __('Kosongkan (Untuk Stempel / Tanda Tangan Basah Manual)') }}</option>
                            </select>
                        </div>

                        {{-- Upload Stempel / TTD --}}
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">{{ __('Upload Gambar Stempel & Tanda Tangan') }}</label>
                            <div class="flex flex-col sm:flex-row items-center gap-4">
                                <div class="w-28 h-20 bg-white rounded-lg border border-slate-200 shadow-xs flex items-center justify-center p-2 shrink-0">
                                    @if(file_exists(public_path('images/stempel.png')))
                                        <img src="{{ asset('images/stempel.png') }}?v={{ time() }}" class="max-h-full object-contain" alt="Stempel Aktif">
                                    @else
                                        <span class="text-xs text-slate-400 text-center"><i class="bi bi-image text-lg block mb-0.5"></i>{{ __('Belum ada') }}</span>
                                    @endif
                                </div>
                                <div class="flex-1 w-full">
                                    <input type="file" 
                                           name="librarian_stamp" 
                                           class="block w-full text-xs text-slate-500
                                                  file:mr-3 file:py-2 file:px-3
                                                  file:rounded-md file:border-0
                                                  file:text-xs file:font-semibold
                                                  file:bg-indigo-50 file:text-indigo-700
                                                  hover:file:bg-indigo-100 transition-colors cursor-pointer border border-slate-300 rounded-lg bg-white" 
                                           accept="image/png,image/jpeg,image/webp">
                                    <p class="text-xs text-slate-400 mt-1.5"><i class="bi bi-info-circle mr-1"></i>{{ __('Format disarankan: PNG transparan (maks. 2MB). Aktif jika memilih opsi "Gambar Stempel / Tanda Tangan Hasil Upload".') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Side Column (Quick Info & Sticky Save Panel) --}}
            <div class="lg:col-span-4 flex flex-col gap-6 sticky top-24">
                {{-- Action Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-800 text-sm mb-2 flex items-center gap-2">
                        <i class="bi bi-shield-check text-indigo-600"></i> {{ __('Simpan Pengaturan') }}
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed mb-4">
                        {{ __('Pastikan data yang dimasukkan sudah sesuai sebelum menekan tombol simpan. Perubahan akan langsung berdampak ke seluruh sistem.') }}
                    </p>
                    <button type="submit" class="w-full inline-flex items-center justify-center text-sm font-bold rounded-xl btn-gradient-blue transition-all gap-2 py-3 px-6 shadow-md hover:shadow-lg cursor-pointer text-white">
                        <i class="bi bi-floppy"></i> {{ __('Simpan Pengaturan') }}
                    </button>
                </div>

                {{-- Quick Summary Card --}}
                <div class="bg-gradient-to-br from-slate-900 to-indigo-950 text-white rounded-xl shadow-md p-6">
                    <h3 class="font-bold text-white text-sm mb-4 flex items-center gap-2">
                        <i class="bi bi-speedometer2 text-indigo-400"></i> {{ __('Ringkasan Kebijakan Aktif') }}
                    </h3>
                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between pb-2 border-b border-white/10">
                            <span class="text-slate-400">{{ __('Durasi Pinjam') }}</span>
                            <span class="font-semibold text-slate-200">{{ \App\Models\Setting::get('loan_duration', 14) }} {{ __('Hari') }}</span>
                        </div>
                        <div class="flex items-center justify-between pb-2 border-b border-white/10">
                            <span class="text-slate-400">{{ __('Maksimal Buku') }}</span>
                            <span class="font-semibold text-slate-200">{{ \App\Models\Setting::get('max_loan_items', 3) }} {{ __('Eks') }}</span>
                        </div>
                        <div class="flex items-center justify-between pb-2 border-b border-white/10">
                            <span class="text-slate-400">{{ __('Maks Perpanjang') }}</span>
                            <span class="font-semibold text-slate-200">{{ \App\Models\Setting::get('max_renewals', 2) }}x</span>
                        </div>
                        <div class="flex items-center justify-between pb-2 border-b border-white/10">
                            <span class="text-slate-400">{{ __('Denda Keterlambatan') }}</span>
                            <span class="font-semibold text-amber-300">Rp {{ number_format(\App\Models\Setting::get('fine_per_day', 1000)) }}/hari</span>
                        </div>
                        <div class="flex items-center justify-between pt-1">
                            <span class="text-slate-400">{{ __('Masa Aktif Anggota') }}</span>
                            <span class="font-semibold text-slate-200">{{ \App\Models\Setting::get('member_expiry_years', 1) }} {{ __('Tahun') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Info Note --}}
                <div class="bg-indigo-50/70 border border-indigo-100 rounded-xl p-4 text-xs text-indigo-900 leading-relaxed">
                    <div class="font-semibold mb-1 flex items-center gap-1.5 text-indigo-700">
                        <i class="bi bi-lightbulb-fill"></i> {{ __('Tips Admin:') }}
                    </div>
                    {{ __('Jika Anda mengganti nama perpustakaan atau logo, header dan kartu anggota digital akan otomatis mengikuti identitas terbaru.') }}
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
