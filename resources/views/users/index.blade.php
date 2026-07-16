@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('breadcrumb')
<li class="breadcrumb-item active">Manajemen User</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Manajemen User / Petugas</h1>
        <p>Kelola data hak akses petugas perpustakaan digital</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-person-plus me-1"></i>Tambah Petugas
        </button>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama atau username..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i> Cari</button>
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i> Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Role / Peran</th>
                        <th>Login Terakhir</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td style="width:50px">{{ $users->firstItem() + $loop->index }}</td>
                        <td><code class="fw-600 fs-6">{{ $u->username }}</code></td>
                        <td class="fw-600">{{ $u->name }}</td>
                        <td>{{ $u->email ?? '-' }}</td>
                        <td>
                            @foreach($u->roles as $role)
                            <span class="badge bg-primary text-capitalize">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>{{ $u->last_login_at ? $u->last_login_at->diffForHumans() : 'Belum pernah' }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $u->is_active ? 'success' : 'danger' }}">
                                {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="text-center" style="width:150px">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $u->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if($u->id !== auth()->id())
                                <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('Hapus petugas ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editUserModal{{ $u->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('users.update', $u) }}">
                                @csrf @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Petugas</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-start">
                                        <div class="mb-3">
                                            <label class="form-label fw-500">Nama Lengkap</label>
                                            <input type="text" name="name" class="form-control" value="{{ $u->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-500">Alamat Email</label>
                                            <input type="email" name="email" class="form-control" value="{{ $u->email }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-500">Role / Peran</label>
                                            <select name="role" class="form-select" required>
                                                @foreach($roles as $role)
                                                <option value="{{ $role->name }}" {{ $u->hasRole($role->name) ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-500">Password Baru <span class="text-muted">(Kosongkan jika tidak diganti)</span></label>
                                            <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-500">Status Aktif</label>
                                            <select name="is_active" class="form-select" required>
                                                <option value="1" {{ $u->is_active ? 'selected' : '' }}>Aktif</option>
                                                <option value="0" {{ !$u->is_active ? 'selected' : '' }}>Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data user</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Petugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-500">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama lengkap petugas" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Username untuk login" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500">Alamat Email</label>
                        <input type="email" name="email" class="form-control" placeholder="contoh@domain.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500">Role / Peran</label>
                        <select name="role" class="form-select" required>
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
