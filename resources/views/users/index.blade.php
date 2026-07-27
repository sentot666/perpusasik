@extends('layouts.app')

@section('title', __('Manajemen User'))
@section('page-title', __('Manajemen User'))

@section('breadcrumb')
<li class="breadcrumb-item active">{{ __('Manajemen User') }}</li>
@endsection

@section('content')
<div x-data="{ showAddModal: false }">
<div class="page-header justify-between items-start flex">
    <div>
        <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ __('Manajemen User / Petugas') }}</h1>
        <p>{{ __('Kelola data hak akses petugas perpustakaan digital') }}</p>
    </div>
    <div>
        <button type="button" @click="showAddModal = true" class="inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white gap-2 py-2 px-6">
            <i class="bi bi-person-plus mr-1"></i>{{ __('Tambah Petugas') }}
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
    <div class="px-8 py-2">
        <form method="GET" class="items-center flex flex-wrap -mx-2">
            <div class="w-full md:w-1/2 px-4">
                <input type="text" name="search" class="w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none px-4" placeholder="{{ __('Cari nama atau username...') }}" value="{{ request('search') }}">
            </div>
            <div class="w-auto px-4">
                <button type="submit" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg btn-gradient-blue transition-colors text-white px-4"><i class="bi bi-search"></i> {{ __('Cari') }}</button>
                <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors px-4"><i class="bi bi-x"></i> {{ __('Reset') }}</a>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-0">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Username') }}</th>
                        <th>{{ __('Nama Lengkap') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Role / Peran') }}</th>
                        <th>{{ __('Login Terakhir') }}</th>
                        <th class="text-center">{{ __('Status') }}</th>
                        <th class="text-center">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr x-data="{ showEditModal: false }">
                        <td style="width:50px">{{ $users->firstItem() + $loop->index }}</td>
                        <td><code class="text-base font-semibold">{{ $u->username }}</code></td>
                        <td class="font-semibold">{{ $u->name }}</td>
                        <td>{{ $u->email ?? '-' }}</td>
                        <td>
                            @foreach($u->roles as $role)
                            <span class="capitalize inline-flex py-1 text-xs font-medium rounded-md bg-indigo-100 text-indigo-700 px-2">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>{{ $u->last_login_at ? $u->last_login_at->diffForHumans() : __('Belum pernah') }}</td>
                        <td class="text-center">
                            <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-{{ $u->is_active ? 'success' : 'danger' }}">
                                {{ $u->is_active ? __('Aktif') : __('Nonaktif') }}
                            </span>
                        </td>
                        <td class="text-center" style="width:150px">
                            <div class="inline-flex rounded-md shadow-sm rounded-lg">
                                <button type="button" @click="showEditModal = true" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2 px-6" title="{{ __('Edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if($u->id !== auth()->id())
                                <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('{{ __('Hapus petugas ini?') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-red-600 border border-slate-200 border-red-600 hover:bg-red-50 transition-colors gap-2 py-2 px-6" title="{{ __('Hapus') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-y-auto p-6">
                        <div @click.outside="showEditModal = false" class="bg-white rounded-xl shadow-lg w-full max-w-lg overflow-hidden relative mt-10">
                            <form method="POST" action="{{ route('users.update', $u) }}">
                                @csrf @method('PUT')
                                
                                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                                    <h5 class="font-semibold text-slate-800">{{ __('Edit Petugas') }}</h5>
                                    <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 transition-colors"><i class="bi bi-x-lg"></i></button>
                                </div>
                                <div class="p-8 text-left max-h-[70vh] overflow-y-auto">
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Nama Lengkap') }}</label>
                                            <input type="text" name="name" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ $u->name }}" required>
                                        </div>
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Username') }}</label>
                                            <input type="text" name="username" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ $u->username }}" required>
                                        </div>
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Alamat Email') }}</label>
                                            <input type="email" name="email" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ $u->email }}">
                                        </div>
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Role / Peran') }}</label>
                                            <select name="role" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4" required>
                                                @foreach($roles as $role)
                                                <option value="{{ $role->name }}" {{ $u->hasRole($role->name) ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Password Baru') }} <span class="text-slate-500">({{ __('Kosongkan jika tidak diganti') }})</span></label>
                                            <input type="password" name="password" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="{{ __('Min. 8 karakter') }}">
                                        </div>
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Status Aktif') }}</label>
                                            <select name="is_active" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4" required>
                                                <option value="1" {{ $u->is_active ? 'selected' : '' }}>{{ __('Aktif') }}</option>
                                                <option value="0" {{ !$u->is_active ? 'selected' : '' }}>{{ __('Nonaktif') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="px-8 py-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-2">
                                        <button type="button" @click="showEditModal = false" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-slate-200 hover:bg-slate-300 transition-colors text-slate-700 py-2 px-6">{{ __('Batal') }}</button>
                                        <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white py-2 px-6">{{ __('Simpan') }}</button>
                                    </div>
                                
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="8" class="text-center text-slate-500 py-6">{{ __('Belum ada data user') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Modal --}}
    <div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-6">
        <div @click.outside="showAddModal = false" class="bg-white rounded-xl shadow-lg w-full max-w-lg overflow-hidden relative mt-10">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h5 class="font-semibold text-slate-800">{{ __('Tambah Petugas') }}</h5>
                    <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 transition-colors"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="p-8 max-h-[70vh] overflow-y-auto">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Nama Lengkap') }}</label>
                        <input type="text" name="name" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="{{ __('Nama lengkap petugas') }}" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Username') }}</label>
                        <input type="text" name="username" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="{{ __('Username untuk login') }}" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Alamat Email') }}</label>
                        <input type="email" name="email" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="{{ __('contoh@domain.com') }}">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Role / Peran') }}</label>
                        <select name="role" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4" required>
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Password') }}</label>
                        <input type="password" name="password" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="{{ __('Min. 8 karakter') }}" required>
                    </div>
                </div>
                <div class="px-8 py-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-2">
                    <button type="button" @click="showAddModal = false" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-slate-200 hover:bg-slate-300 transition-colors text-slate-700 py-2 px-6">{{ __('Batal') }}</button>
                    <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white py-2 px-6">{{ __('Simpan') }}</button>
                </div>
            </form>
        </div>
    </div>
    
    </div> <!-- End x-data showAddModal wrapper -->
@endsection


