@extends('layouts.app')

@section('title', __('Manajemen User'))
@section('page-title', __('Manajemen User'))

@section('breadcrumb')
<li class="breadcrumb-item active">{{ __('Manajemen User') }}</li>
@endsection

@section('content')
<div x-data="{ showAddModal: {{ $errors->any() && !old('_method') ? 'true' : 'false' }}, showEditModal: false }"
     @open-edit.window="
        showEditModal = true;
        document.getElementById('editUserForm').action = '/users/' + $event.detail.id;
        document.getElementById('editName').value = $event.detail.name;
        document.getElementById('editUsername').value = $event.detail.username;
        document.getElementById('editEmail').value = $event.detail.email || '';
        document.getElementById('editRole').value = $event.detail.role;
        document.getElementById('editIsActive').value = $event.detail.is_active ? '1' : '0';
     ">
<div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-1">{{ __('Manajemen User / Petugas') }}</h1>
        <p class="text-slate-500 text-xs sm:text-sm">{{ __('Kelola data hak akses petugas perpustakaan digital') }}</p>
    </div>
    <div class="flex flex-wrap gap-2 w-full sm:w-auto">
        <a href="{{ route('members.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center text-sm font-medium rounded-lg text-indigo-600 border border-slate-300 hover:bg-indigo-50 transition-colors gap-2 py-2 px-4 no-underline">
            <i class="bi bi-people"></i>{{ __('Tambah Anggota') }}
        </a>
        <button type="button" @click="showAddModal = true" class="w-full sm:w-auto inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white gap-2 py-2 px-5 cursor-pointer">
            <i class="bi bi-person-plus"></i>{{ __('Tambah Petugas') }}
        </button>
    </div>
</div>

{{-- Banner Info Perbedaan Petugas & Anggota --}}
<div class="bg-indigo-50/70 border border-indigo-100 rounded-xl p-4 mb-6 flex items-start gap-3">
    <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0 mt-0.5">
        <i class="bi bi-info-circle text-base"></i>
    </div>
    <div class="text-xs sm:text-sm text-slate-600 leading-relaxed">
        <span class="font-semibold text-slate-800">{{ __('Catatan:') }}</span> 
        {{ __('Halaman ini khusus untuk mengelola akun Petugas / Admin. Jika Anda ingin mendaftarkan') }} 
        <span class="font-semibold text-indigo-700">{{ __('Anggota Perpustakaan') }}</span> {{ __('(Siswa, Guru, atau Umum), silakan gunakan menu') }} 
        <a href="{{ route('members.create') }}" class="font-bold text-indigo-600 hover:underline inline-flex items-center gap-1">{{ __('Tambah Anggota') }} <i class="bi bi-arrow-right"></i></a>.
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
    <div class="px-8 py-2">
        <form method="GET" class="items-center flex flex-wrap -mx-2">
            <div class="w-full md:w-1/2 px-4">
                <input type="text" name="search" class="w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none px-4" placeholder="{{ __('Cari nama atau username...') }}" value="{{ request('search') }}">
            </div>
            <div class="w-auto px-4">
                <button type="submit" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-semibold rounded-lg btn-gradient-dark transition-colors text-white px-4"><i class="bi bi-search"></i> {{ __('Cari') }}</button>
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
                    <tr>
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
                                <button type="button" 
                                        @click="$dispatch('open-edit', {
                                            id: {{ $u->id }},
                                            name: '{{ addslashes($u->name) }}',
                                            username: '{{ addslashes($u->username) }}',
                                            email: '{{ addslashes($u->email ?? '') }}',
                                            role: '{{ $u->roles->first()?->name ?? '' }}',
                                            is_active: {{ $u->is_active ? 'true' : 'false' }}
                                        })" 
                                        class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2 px-6 cursor-pointer" 
                                        title="{{ __('Edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if($u->id !== auth()->id())
                                <form method="POST" action="{{ route('users.destroy', $u) }}"
                                      data-confirm="{{ __('Apakah Anda yakin ingin menghapus petugas ini?') }}"
                                      data-confirm-title="{{ __('Hapus Petugas?') }}"
                                      data-confirm-button="{{ __('Ya, Hapus') }}"
                                      data-confirm-danger="true">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-red-600 border border-slate-200 border-red-600 hover:bg-red-50 transition-colors gap-2 py-2 px-6 cursor-pointer" title="{{ __('Hapus') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-slate-500 py-6">{{ __('Belum ada data user') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-y-auto p-6">
    <div @click.outside="showEditModal = false" class="bg-white rounded-xl shadow-lg w-full max-w-lg overflow-hidden relative mt-10">
        <form method="POST" id="editUserForm">
            @csrf @method('PUT')
            
            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h5 class="font-semibold text-slate-800">{{ __('Edit Petugas') }}</h5>
                <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="p-8 text-left max-h-[70vh] overflow-y-auto">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Nama Lengkap') }}</label>
                    <input type="text" id="editName" name="name" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Username') }}</label>
                    <input type="text" id="editUsername" name="username" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Alamat Email') }}</label>
                    <input type="email" id="editEmail" name="email" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Role / Peran') }}</label>
                    <select id="editRole" name="role" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4" required>
                        @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Password Baru') }} <span class="text-slate-500">({{ __('Kosongkan jika tidak diganti') }})</span></label>
                    <input type="password" name="password" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="{{ __('Min. 8 karakter') }}">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Status Aktif') }}</label>
                    <select id="editIsActive" name="is_active" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4" required>
                        <option value="1">{{ __('Aktif') }}</option>
                        <option value="0">{{ __('Nonaktif') }}</option>
                    </select>
                </div>
            </div>
            <div class="px-8 py-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-2">
                <button type="button" @click="showEditModal = false" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-slate-200 hover:bg-slate-300 transition-colors text-slate-700 py-2 px-6 cursor-pointer">{{ __('Batal') }}</button>
                <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white py-2 px-6 cursor-pointer">{{ __('Simpan') }}</button>
            </div>
        </form>
    </div>
</div>

{{-- Add Modal --}}
    <div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-6">
        <div @click.outside="showAddModal = false" class="bg-white rounded-xl shadow-lg w-full max-w-lg overflow-hidden relative mt-10">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h5 class="font-semibold text-slate-800">{{ __('Tambah Petugas') }}</h5>
                    <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="p-8 max-h-[70vh] overflow-y-auto">
                    @if($roles->isEmpty())
                    <div class="p-3.5 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl text-xs mb-5 flex items-start gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-amber-500 text-base mt-0.5"></i>
                        <div>
                            <strong>{{ __('Role Belum Tersedia!') }}</strong><br>
                            {{ __('Tabel roles di database masih kosong. Jalankan') }} <code class="bg-amber-100 px-1 py-0.5 rounded font-bold">php artisan db:seed</code> {{ __('di terminal agar role dapat dipilih.') }}
                        </div>
                    </div>
                    @endif

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Nama Lengkap') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border @error('name') border-rose-500 bg-rose-50/30 @else border-slate-300 @enderror text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="{{ __('Nama lengkap petugas') }}" required>
                        @error('name')
                            <p class="text-xs text-rose-500 mt-1 mb-0">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Username') }} <span class="text-rose-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" class="w-full rounded-lg border @error('username') border-rose-500 bg-rose-50/30 @else border-slate-300 @enderror text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="{{ __('Username untuk login') }}" required>
                        @error('username')
                            <p class="text-xs text-rose-500 mt-1 mb-0">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Alamat Email') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border @error('email') border-rose-500 bg-rose-50/30 @else border-slate-300 @enderror text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="{{ __('contoh@domain.com') }}">
                        @error('email')
                            <p class="text-xs text-rose-500 mt-1 mb-0">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Role / Peran') }} <span class="text-rose-500">*</span></label>
                        <select name="role" class="w-full rounded-lg border @error('role') border-rose-500 bg-rose-50/30 @else border-slate-300 @enderror text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4" required>
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- {{ __('Pilih Role') }} --</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="text-xs text-rose-500 mt-1 mb-0">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Password') }} <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" class="w-full rounded-lg border @error('password') border-rose-500 bg-rose-50/30 @else border-slate-300 @enderror text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="{{ __('Min. 8 karakter') }}" required>
                        @error('password')
                            <p class="text-xs text-rose-500 mt-1 mb-0">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="px-8 py-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-2">
                    <button type="button" @click="showAddModal = false" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-slate-200 hover:bg-slate-300 transition-colors text-slate-700 py-2 px-6 cursor-pointer">{{ __('Batal') }}</button>
                    <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white py-2 px-6 cursor-pointer">{{ __('Simpan') }}</button>
                </div>
            </form>
        </div>
    </div>
    
</div> <!-- End x-data wrapper -->
@endsection


