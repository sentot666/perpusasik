@extends('layouts.app')

@section('title', 'Data Anggota')
@section('page-title', 'Data Anggota')

@section('breadcrumb')
<li class="breadcrumb-item active">Data Anggota</li>
@endsection

@section('content')
<div class="page-header justify-between items-start flex">
    <div>
        <h1>Daftar Anggota</h1>
        <p>Kelola data anggota perpustakaan digital</p>
    </div>
    <div>
        <a href="{{ route('members.create') }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 transition-colors text-white gap-2 py-2 px-6">
            <i class="bi bi-person-plus mr-1"></i>Tambah Anggota
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
    <div class="px-8 py-2">
        <form method="GET" class="items-center flex flex-wrap -mx-2" id="memberFilterForm">
            <div class="col-md-5">
                <div class="flex w-full text-sm">
                    <span class="flex items-center px-3 py-2 bg-slate-100 border border-slate-300 text-slate-600"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="Cari nama, kode, email, no. identitas..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="member_type" class="w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white px-4" onchange="this.form.submit()">
                    <option value="">Semua Tipe</option>
                    @foreach($memberTypes as $type)
                    <option value="{{ $type }}" {{ request('member_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white px-4" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="w-auto px-4">
                <button type="submit" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 transition-colors text-white px-4"><i class="bi bi-funnel"></i> Filter</button>
                <a href="{{ route('members.index') }}" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors px-4"><i class="bi bi-x"></i> Reset</a>
            </div>
            <div class="ml-auto text-slate-500 w-auto px-4" style="font-size:0.8rem">
                {{ $members->total() }} anggota terdaftar
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-0">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Anggota</th>
                        <th>Nama</th>
                        <th>Kontak</th>
                        <th>Tipe</th>
                        <th>Tgl Daftar</th>
                        <th>Masa Berlaku</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    <tr>
                        <td class="text-slate-500">{{ $members->firstItem() + $loop->index }}</td>
                        <td><code class="font-semibold" style="font-size:0.85rem">{{ $member->member_code }}</code></td>
                        <td>
                            <div class="items-center flex gap-2">
                                <div style="width:30px;height:30px;border-radius:50%;background:#e2e8f0;overflow:hidden;display:flex;align-items:center;justify-content:center;color:#718096;font-weight:bold;font-size:0.75rem">
                                    @if($member->photo)
                                        <img src="{{ asset('storage/' . $member->photo) }}" style="width:100%;height:100%;object-fit:cover">
                                    @else
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div class="font-medium">
                                    <a href="{{ route('members.show', $member) }}" class="no-underline text-slate-800">
                                        {{ $member->name }}
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>{{ $member->phone ?? '-' }}</div>
                            <div class="text-slate-500" style="font-size:0.72rem">{{ $member->email ?? '-' }}</div>
                        </td>
                        <td><span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-slate-50 border border-slate-200 text-slate-800">{{ $member->member_type }}</span></td>
                        <td>{{ $member->register_date ? $member->register_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            <span class="{{ $member->is_expired ? 'text-danger fw-600' : '' }}">
                                {{ $member->expired_date ? $member->expired_date->format('d/m/Y') : '-' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-{{ $member->status_badge_class }}">{{ $member->status_label }}</span>
                        </td>
                        <td class="text-center">
                            <div class="inline-flex rounded-md shadow-sm rounded-lg">
                                <a href="{{ route('members.show', $member) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-indigo-600 border border-slate-200 border-indigo-600 hover:bg-indigo-50 transition-colors gap-2 py-2 px-6" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('members.edit', $member) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2 px-6" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('members.print-card', $member) }}" target="_blank" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-amber-600 border border-slate-200 border-amber-600 hover:bg-amber-50 transition-colors gap-2 py-2 px-6" title="Cetak Kartu">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <form method="POST" action="{{ route('members.destroy', $member) }}" onsubmit="return confirm('Hapus anggota ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-red-600 border border-slate-200 border-red-600 hover:bg-red-50 transition-colors gap-2 py-2 px-6" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-slate-500">
                            <i class="bi bi-people text-4xl font-bold block opacity-25 mb-2"></i>
                            Belum ada data anggota. <a href="{{ route('members.create') }}">Tambah anggota pertama</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($members->hasPages())
    <div class="bg-white border-t border-slate-200 px-8 bg-slate-50 py-2 py-4">
        {{ $members->links() }}
    </div>
    @endif
</div>
@endsection
