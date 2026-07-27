@extends('layouts.app')

@section('title', __('Master Lokasi / Rak'))
@section('page-title', __('Master Lokasi'))

@section('breadcrumb')
<li class="breadcrumb-item active">{{ __('Lokasi') }}</li>
@endsection

@section('content')
<div x-data="{ showAddModal: false }">
    <div class="page-header justify-between items-start flex mb-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ __('Master Lokasi / Rak') }}</h1>
            <p class="text-slate-500 mt-1">{{ __('Kelola data ruangan, lemari, atau rak penempatan buku fisik') }}</p>
        </div>
        <div class="flex gap-2">
            <button type="button" @click="showAddModal = true" class="inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white gap-2 py-2 px-6">
                <i class="bi bi-plus-circle mr-1"></i>{{ __('Tambah Lokasi') }}
            </button>
        </div>
    </div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
    <div class="px-8 py-2">
        <form method="GET" class="items-center flex flex-wrap -mx-2">
            <div class="w-full md:w-1/2 px-4">
                <input type="text" name="search" class="w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none px-4" placeholder="{{ __('Cari nama lokasi atau kode...') }}" value="{{ request('search') }}">
            </div>
            <div class="w-auto px-4">
                <button type="submit" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg btn-gradient-blue transition-colors text-white px-4"><i class="bi bi-search"></i> {{ __('Cari') }}</button>
                <a href="{{ route('locations.index') }}" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors px-4"><i class="bi bi-x"></i> {{ __('Reset') }}</a>
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
                        <th>{{ __('Kode Lokasi') }}</th>
                        <th>{{ __('Nama Lokasi / Rak') }}</th>
                        <th>{{ __('Deskripsi') }}</th>
                        <th class="text-center">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $loc)
                    <tr x-data="{ showEditModal: false }">
                        <td style="width:50px">{{ $locations->firstItem() + $loop->index }}</td>
                        <td><code class="text-base font-semibold">{{ $loc->code }}</code></td>
                        <td class="text-slate-800 font-semibold">{{ $loc->name }}</td>
                        <td>{{ $loc->description ?? '-' }}</td>
                        <td class="text-center" style="width:150px">
                            <div class="inline-flex gap-2">
                                <button type="button" @click="showEditModal = true" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors" title="{{ __('Edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('locations.destroy', $loc) }}" onsubmit="return confirm('{{ __('Hapus lokasi ini?') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 bg-red-50 hover:bg-red-100 transition-colors" title="{{ __('Hapus') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-6">
                        <div @click.outside="showEditModal = false" class="bg-white rounded-xl shadow-lg w-full max-w-lg overflow-hidden relative mt-10">
                            <form method="POST" action="{{ route('locations.update', $loc) }}">
                                @csrf @method('PUT')
                                
                                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                                    <h5 class="font-semibold text-slate-800">{{ __('Edit Lokasi') }}</h5>
                                    <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 transition-colors"><i class="bi bi-x-lg"></i></button>
                                </div>
                                <div class="p-8 text-left max-h-[70vh] overflow-y-auto">
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Kode Lokasi') }}</label>
                                            <input type="text" name="code" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ $loc->code }}" required>
                                        </div>
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Nama Lokasi / Rak') }}</label>
                                            <input type="text" name="name" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ $loc->name }}" required>
                                        </div>
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Keterangan / Deskripsi') }}</label>
                                            <textarea name="description" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" rows="3">{{ $loc->description }}</textarea>
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
                    <tr><td colspan="5" class="text-center text-slate-500 py-6">{{ __('Belum ada data lokasi') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($locations->hasPages())
    <div class="bg-white border-t border-slate-200 px-8 bg-slate-50 py-2 py-4">
        {{ $locations->links() }}
    </div>
    @endif
</div>

{{-- Add Modal --}}
    <div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-6">
        <div @click.outside="showAddModal = false" class="bg-white rounded-xl shadow-lg w-full max-w-lg overflow-hidden relative mt-10">
            <form method="POST" action="{{ route('locations.store') }}">
                @csrf
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h5 class="font-semibold text-slate-800">{{ __('Tambah Lokasi') }}</h5>
                    <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 transition-colors"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="p-8 max-h-[70vh] overflow-y-auto">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Kode Lokasi') }}</label>
                        <input type="text" name="code" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="{{ __('Contoh: RAK-01-A') }}" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Nama Lokasi / Rak') }}</label>
                        <input type="text" name="name" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="{{ __('Contoh: Rak Komputer Utama') }}" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Keterangan / Deskripsi') }}</label>
                        <textarea name="description" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" rows="3" placeholder="{{ __('Opsional...') }}"></textarea>
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


