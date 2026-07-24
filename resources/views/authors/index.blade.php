@extends('layouts.app')

@section('title', 'Master Pengarang')
@section('page-title', 'Master Pengarang')

@section('breadcrumb')
<li class="breadcrumb-item active">Pengarang</li>
@endsection

@section('content')
<div x-data="{ showAddModal: false }">
    <div class="page-header justify-between items-start flex mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Master Pengarang</h1>
            <p class="text-slate-500 mt-1">Kelola data pengarang/penulis buku</p>
        </div>
        <div class="flex gap-2">
            <button type="button" @click="showAddModal = true" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 transition-colors text-white gap-2 py-2 px-6">
                <i class="bi bi-plus-circle mr-1"></i>Tambah Pengarang
            </button>
        </div>
    </div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
    <div class="px-8 py-2">
        <form method="GET" class="items-center flex flex-wrap -mx-2">
            <div class="w-full md:w-1/2 px-4">
                <input type="text" name="search" class="w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none px-4" placeholder="Cari nama pengarang..." value="{{ request('search') }}">
            </div>
            <div class="w-auto px-4">
                <button type="submit" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 transition-colors text-white px-4"><i class="bi bi-search"></i> Cari</button>
                <a href="{{ route('authors.index') }}" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors px-4"><i class="bi bi-x"></i> Reset</a>
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
                        <th>Nama Pengarang</th>
                        <th>Tipe</th>
                        <th>Biografi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($authors as $author)
                    <tr x-data="{ showEditModal: false }">
                        <td style="width:50px">{{ $authors->firstItem() + $loop->index }}</td>
                        <td class="font-semibold">{{ $author->name }}</td>
                        <td>
                            @if($author->type == 'personal')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                                    Perorangan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                    Lembaga/Badan
                                </span>
                            @endif
                        </td>
                        <td class="truncate" style="max-width:250px" title="{{ $author->biography }}">{{ $author->biography ?? '-' }}</td>
                        <td class="text-center" style="width:150px">
                            <div class="inline-flex gap-2">
                                <button type="button" @click="showEditModal = true" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('authors.destroy', $author) }}" onsubmit="return confirm('Hapus pengarang ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 bg-red-50 hover:bg-red-100 transition-colors" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-6">
                        <div @click.outside="showEditModal = false" class="bg-white rounded-xl shadow-lg w-full max-w-lg overflow-hidden relative mt-10">
                            <form method="POST" action="{{ route('authors.update', $author) }}">
                                @csrf @method('PUT')
                                
                                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                                    <h5 class="font-semibold text-slate-800">Edit Pengarang</h5>
                                    <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 transition-colors"><i class="bi bi-x-lg"></i></button>
                                </div>
                                <div class="p-8 text-left max-h-[70vh] overflow-y-auto">
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Pengarang</label>
                                            <input type="text" name="name" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ $author->name }}" required>
                                        </div>
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Tipe</label>
                                            <select name="type" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4" required>
                                                <option value="personal" {{ $author->type == 'personal' ? 'selected' : '' }}>Perorangan</option>
                                                <option value="organization" {{ $author->type == 'organization' ? 'selected' : '' }}>Lembaga/Badan</option>
                                            </select>
                                        </div>
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Biografi Ringkas</label>
                                            <textarea name="biography" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" rows="3">{{ $author->biography }}</textarea>
                                        </div>
                                    </div>
                                    <div class="px-8 py-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-2">
                                        <button type="button" @click="showEditModal = false" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-slate-200 hover:bg-slate-300 transition-colors text-slate-700 py-2 px-6">Batal</button>
                                        <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 transition-colors text-white py-2 px-6">Simpan</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="5" class="text-center text-slate-500 py-6">Belum ada data pengarang</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($authors->hasPages())
    <div class="bg-white border-t border-slate-200 px-8 bg-slate-50 py-2 py-4">
        {{ $authors->links() }}
    </div>
    @endif
</div>

{{-- Add Modal --}}
    <div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-6">
        <div @click.outside="showAddModal = false" class="bg-white rounded-xl shadow-lg w-full max-w-lg overflow-hidden relative mt-10">
            <form method="POST" action="{{ route('authors.store') }}">
                @csrf
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h5 class="font-semibold text-slate-800">Tambah Pengarang</h5>
                    <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 transition-colors"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="p-8 max-h-[70vh] overflow-y-auto">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Pengarang</label>
                        <input type="text" name="name" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="Contoh: Andrea Hirata" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tipe</label>
                        <select name="type" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4" required>
                            <option value="personal">Perorangan</option>
                            <option value="organization">Lembaga/Badan</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Biografi Ringkas</label>
                        <textarea name="biography" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" rows="3" placeholder="Opsional..."></textarea>
                    </div>
                </div>
                <div class="px-8 py-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-2">
                    <button type="button" @click="showAddModal = false" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-slate-200 hover:bg-slate-300 transition-colors text-slate-700 py-2 px-6">Batal</button>
                    <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 transition-colors text-white py-2 px-6">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    
    </div> <!-- End x-data showAddModal wrapper -->
@endsection
