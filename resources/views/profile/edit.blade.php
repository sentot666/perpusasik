@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil')

@section('breadcrumb')
<li class="breadcrumb-item active">Profil</li>
@endsection

@section('content')
<div class="page-header">
    <h1>Pengaturan Akun</h1>
    <p>Perbarui data profil dan password akun Anda</p>
</div>

<div class="flex flex-wrap -mx-3">
    {{-- Edit Profile --}}
    <div class="w-full md:w-1/2 px-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-person-fill-gear text-indigo-600 mr-2"></i>Informasi Profil</div>
            <div class="p-8">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Username</label>
                        <input type="text" class="bg-slate-50 w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ $user->username }}" readonly>
                        <div class="form-text">Username tidak dapat diubah</div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" class="@error('name') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Email</label>
                        <input type="email" name="email" class="@error('email') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 transition-colors text-white font-semibold gap-2 py-2 px-6">Simpan Profil</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Password --}}
    <div class="w-full md:w-1/2 px-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-shield-lock-fill text-red-600 mr-2"></i>Ganti Password</div>
            <div class="p-8">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Password Saat Ini</label>
                        <input type="password" name="current_password" class="@error('current_password') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" required placeholder="Masukkan password sekarang">
                        @error('current_password')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Password Baru</label>
                        <input type="password" name="password" class="@error('password') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" required placeholder="Min. 8 karakter">
                        @error('password')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" required placeholder="Ulangi password baru">
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center text-sm font-medium rounded-lg bg-red-600 hover:bg-red-700 transition-colors text-white font-semibold gap-2 py-2 px-6">Ubah Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
