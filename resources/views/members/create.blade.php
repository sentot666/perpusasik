@extends('layouts.app')

@section('title', 'Tambah Anggota Baru')
@section('page-title', 'Tambah Anggota')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('members.index') }}">Daftar Anggota</a></li>
<li class="breadcrumb-item active">Tambah Anggota</li>
@endsection

@section('content')
<div class="page-header">
    <h1>Tambah Anggota Baru</h1>
    <p>Daftarkan anggota perpustakaan baru ke sistem</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-person-plus-fill text-indigo-600 mr-2"></i>Form Data Anggota</div>
    <div class="p-8">

        @if($errors->any())
        <div class="alert alert-danger border-0 mb-6 py-2" style="border-radius:8px">
            <i class="bi bi-exclamation-triangle-fill mr-1"></i>
            Periksa kembali form Anda. Ada beberapa input yang tidak valid.
        </div>
        @endif

        <form method="POST" action="{{ route('members.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="flex flex-wrap -mx-3">
                {{-- Left side --}}
                <div class="w-full md:w-1/2 px-4">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kode Anggota <span class="text-red-600">*</span></label>
                        <input type="text" name="member_code" class="@error('member_code') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" value="{{ old('member_code', $memberCode) }}" required>
                        @error('member_code')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap <span class="text-red-600">*</span></label>
                        <input type="text" name="name" class="@error('name') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" value="{{ old('name') }}" required placeholder="Nama lengkap sesuai identitas">
                        @error('name')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tipe Identitas</label>
                            <select name="identity_type" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4">
                                <option value="KTP" {{ old('identity_type') == 'KTP' ? 'selected' : '' }}>KTP</option>
                                <option value="SIM" {{ old('identity_type') == 'SIM' ? 'selected' : '' }}>SIM</option>
                                <option value="Kartu Pelajar" {{ old('identity_type') == 'Kartu Pelajar' ? 'selected' : '' }}>Kartu Pelajar</option>
                                <option value="KTM" {{ old('identity_type') == 'KTM' ? 'selected' : '' }}>KTM (Mahasiswa)</option>
                            </select>
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">No. Identitas</label>
                            <input type="text" name="identity_number" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('identity_number') }}" placeholder="NIK / NIM / NIS">
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                            <select name="gender" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4">
                                <option value="">Pilih...</option>
                                <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tipe Anggota <span class="text-red-600">*</span></label>
                            <select name="member_type" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4" required>
                                <option value="Siswa SD" {{ old('member_type') == 'Siswa SD' ? 'selected' : '' }}>Siswa SD</option>
                                <option value="Siswa SMP" {{ old('member_type') == 'Siswa SMP' ? 'selected' : '' }}>Siswa SMP</option>
                                <option value="Siswa SMA" {{ old('member_type') == 'Siswa SMA' ? 'selected' : '' }}>Siswa SMA</option>
                                <option value="Guru" {{ old('member_type') == 'Guru' ? 'selected' : '' }}>Guru</option>
                                <option value="Umum" {{ old('member_type') == 'Umum' ? 'selected' : '' }}>Umum</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Foto Anggota</label>
                        <input type="file" name="photo" class="@error('photo') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" accept="image/*">
                        @error('photo')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                        <div class="form-text">Format: JPG, PNG. Maks 2MB</div>
                    </div>
                </div>

                {{-- Right side --}}
                <div class="w-full md:w-1/2 px-4">
                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                            <input type="email" name="email" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('email') }}" placeholder="contoh@domain.com">
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">No. Telepon</label>
                            <input type="text" name="phone" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Lengkap</label>
                        <textarea name="address" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" rows="2" placeholder="Nama jalan, RT/RW, Dusun...">{{ old('address') }}</textarea>
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Kota/Kabupaten</label>
                            <input type="text" name="city" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('city') }}">
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Provinsi</label>
                            <input type="text" name="province" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('province') }}">
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Daftar</label>
                            <input type="date" name="register_date" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('register_date', date('Y-m-d')) }}">
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Kedaluwarsa</label>
                            <input type="date" name="expired_date" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('expired_date') }}">
                            <div class="form-text">Biarkan kosong untuk otomatis 1 tahun</div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Catatan Lain</label>
                        <textarea name="notes" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" rows="2" placeholder="Informasi tambahan...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <hr>

            <div class="justify-end flex gap-2">
                <a href="{{ route('members.index') }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2 px-6">Batal</a>
                <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 transition-colors text-white font-semibold gap-2 py-2 px-6">Simpan Anggota</button>
            </div>
        </form>

    </div>
</div>
@endsection
