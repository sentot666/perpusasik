@extends('layouts.app')

@section('title', 'Edit Anggota')
@section('page-title', 'Edit Anggota')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('members.index') }}">Daftar Anggota</a></li>
<li class="breadcrumb-item"><a href="{{ route('members.show', $member) }}">{{ $member->name }}</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <h1>Edit Data Anggota</h1>
    <p>Perbarui informasi detail untuk anggota perpustakaan</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-pencil-square text-indigo-600 mr-2"></i>Form Edit Anggota</div>
    <div class="p-8">

        @if($errors->any())
        <div class="alert alert-danger border-0 mb-6 py-2" style="border-radius:8px">
            <i class="bi bi-exclamation-triangle-fill mr-1"></i>
            Periksa kembali form Anda. Ada beberapa input yang tidak valid.
        </div>
        @endif

        <form method="POST" action="{{ route('members.update', $member) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="flex flex-wrap -mx-3">
                {{-- Left side --}}
                <div class="w-full md:w-1/2 px-4">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kode Anggota <span class="text-red-600">*</span></label>
                        <input type="text" name="member_code" class="@error('member_code') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" value="{{ old('member_code', $member->member_code) }}" required>
                        @error('member_code')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap <span class="text-red-600">*</span></label>
                        <input type="text" name="name" class="@error('name') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" value="{{ old('name', $member->name) }}" required>
                        @error('name')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tipe Identitas</label>
                            <select name="identity_type" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4">
                                <option value="KTP" {{ old('identity_type', $member->identity_type) == 'KTP' ? 'selected' : '' }}>KTP</option>
                                <option value="SIM" {{ old('identity_type', $member->identity_type) == 'SIM' ? 'selected' : '' }}>SIM</option>
                                <option value="Kartu Pelajar" {{ old('identity_type', $member->identity_type) == 'Kartu Pelajar' ? 'selected' : '' }}>Kartu Pelajar</option>
                                <option value="KTM" {{ old('identity_type', $member->identity_type) == 'KTM' ? 'selected' : '' }}>KTM (Mahasiswa)</option>
                            </select>
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">No. Identitas</label>
                            <input type="text" name="identity_number" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('identity_number', $member->identity_number) }}">
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                            <select name="gender" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4">
                                <option value="">Pilih...</option>
                                <option value="L" {{ old('gender', $member->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender', $member->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tipe Anggota <span class="text-red-600">*</span></label>
                            <select name="member_type" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4" required>
                                <option value="Siswa SD" {{ old('member_type', $member->member_type) == 'Siswa SD' ? 'selected' : '' }}>Siswa SD</option>
                                <option value="Siswa SMP" {{ old('member_type', $member->member_type) == 'Siswa SMP' ? 'selected' : '' }}>Siswa SMP</option>
                                <option value="Siswa SMA" {{ old('member_type', $member->member_type) == 'Siswa SMA' ? 'selected' : '' }}>Siswa SMA</option>
                                <option value="Guru" {{ old('member_type', $member->member_type) == 'Guru' ? 'selected' : '' }}>Guru</option>
                                <option value="Umum" {{ old('member_type', $member->member_type) == 'Umum' ? 'selected' : '' }}>Umum</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Foto Anggota</label>
                        @if($member->photo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $member->photo) }}" style="width:60px;height:60px;object-fit:cover;border-radius:8px">
                        </div>
                        @endif
                        <input type="file" name="photo" class="@error('photo') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" accept="image/*">
                        @error('photo')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Right side --}}
                <div class="w-full md:w-1/2 px-4">
                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                            <input type="email" name="email" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('email', $member->email) }}">
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">No. Telepon</label>
                            <input type="text" name="phone" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('phone', $member->phone) }}">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Lengkap</label>
                        <textarea name="address" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" rows="2">{{ old('address', $member->address) }}</textarea>
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Kota/Kabupaten</label>
                            <input type="text" name="city" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('city', $member->city) }}">
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Provinsi</label>
                            <input type="text" name="province" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('province', $member->province) }}">
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Daftar</label>
                            <input type="date" name="register_date" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('register_date', $member->register_date ? $member->register_date->format('Y-m-d') : '') }}">
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Kedaluwarsa</label>
                            <input type="date" name="expired_date" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('expired_date', $member->expired_date ? $member->expired_date->format('Y-m-d') : '') }}">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Status Aktif</label>
                        <div class="form-switch flex items-center gap-2 mt-2">
                            <input type="hidden" name="is_active" value="0">
                            <input class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500" type="checkbox" name="is_active" id="isActiveSwitch" value="1" {{ old('is_active', $member->is_active) ? 'checked' : '' }}>
                            <label class="text-sm text-slate-700 text-slate-500" for="isActiveSwitch" style="font-size:0.8rem">Aktifkan Anggota</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="justify-end flex gap-2">
                <a href="{{ route('members.show', $member) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2 px-6">Batal</a>
                <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 transition-colors text-white font-semibold gap-2 py-2 px-6">Simpan Perubahan</button>
            </div>
        </form>

    </div>
</div>
@endsection
