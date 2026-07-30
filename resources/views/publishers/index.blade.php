@extends('layouts.app')

@section('title', __('Master Penerbit'))
@section('page-title', __('Master Penerbit'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item active">{{ __('Penerbit') }}</li>
@endsection

@section('content')
<div x-data="{ showAddModal: false }">
    <div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-1">{{ __('Master Penerbit') }}</h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-1">{{ __('Kelola data penerbit buku') }}</p>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <button type="button" @click="showAddModal = true" class="w-full sm:w-auto inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white gap-2 py-2 px-5">
                <i class="bi bi-plus-circle"></i>{{ __('Tambah Penerbit') }}
            </button>
        </div>
    </div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
    <div class="px-8 py-2">
        <form method="GET" class="items-center flex flex-wrap -mx-2">
            <div class="w-full md:w-1/2 px-4">
                <input type="text" name="search" class="w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none px-4" placeholder="{{ __('Cari nama penerbit...') }}" value="{{ request('search') }}">
            </div>
            <div class="w-auto px-4">
                <button type="submit" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-semibold rounded-lg btn-gradient-dark transition-colors text-white px-4"><i class="bi bi-search"></i> {{ __('Cari') }}</button>
                <a href="{{ route('publishers.index') }}" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors px-4"><i class="bi bi-x"></i> {{ __('Reset') }}</a>
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
                        <th>{{ __('Nama Penerbit') }}</th>
                        <th>{{ __('Kota') }}</th>
                        <th>{{ __('Negara') }}</th>
                        <th>{{ __('Website') }}</th>
                        <th class="text-center">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($publishers as $pub)
                    <tr>
                        <td style="width:50px">{{ $publishers->firstItem() + $loop->index }}</td>
                        <td class="font-semibold">{{ $pub->name }}</td>
                        <td>{{ $pub->city ?? '-' }}</td>
                        <td>{{ $pub->country ?? '-' }}</td>
                        <td>
                            @if($pub->website)
                            <a href="{{ $pub->website }}" target="_blank" class="no-underline"><i class="bi bi-globe mr-1"></i>{{ __('Link') }}</a>
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-center" style="width:150px">
                            <div class="inline-flex gap-2">
                                <button type="button"
                                    onclick="openEditPublisher({{ $pub->id }}, {{ json_encode($pub->name) }}, {{ json_encode($pub->city ?? '') }}, {{ json_encode($pub->country ?? '') }}, {{ json_encode($pub->website ?? '') }})"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors" title="{{ __('Edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('publishers.destroy', $pub) }}" onsubmit="return confirm('{{ __('Hapus penerbit ini?') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 bg-red-50 hover:bg-red-100 transition-colors" title="{{ __('Hapus') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-slate-500 py-6">{{ __('Belum ada data penerbit') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($publishers->hasPages())
    <div class="bg-white border-t border-slate-200 px-8 bg-slate-50 py-2 py-4">
        {{ $publishers->links() }}
    </div>
    @endif
</div>

{{-- Edit Modal (satu, di luar tabel) --}}
<div id="editPublisherModal" style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-6">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg overflow-hidden relative mt-10">
        <form id="editPublisherForm" method="POST" action="">
            @csrf @method('PUT')
            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h5 class="font-semibold text-slate-800">{{ __('Edit Penerbit') }}</h5>
                <button type="button" onclick="closeEditPublisher()" class="text-slate-400 hover:text-slate-600 transition-colors"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="p-8 text-left max-h-[70vh] overflow-y-auto">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Nama Penerbit') }}</label>
                    <input type="text" id="editPubName" name="name" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Kota') }}</label>
                    <input type="text" id="editPubCity" name="city" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Negara') }}</label>
                    <input type="text" id="editPubCountry" name="country" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Website') }}</label>
                    <input type="url" id="editPubWebsite" name="website" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="https://example.com">
                </div>
            </div>
            <div class="px-8 py-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-2">
                <button type="button" onclick="closeEditPublisher()" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-slate-200 hover:bg-slate-300 transition-colors text-slate-700 py-2 px-6">{{ __('Batal') }}</button>
                <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white py-2 px-6">{{ __('Simpan') }}</button>
            </div>
        </form>
    </div>
</div>

{{-- Add Modal --}}
    <div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-6">
        <div @click.outside="showAddModal = false" class="bg-white rounded-xl shadow-lg w-full max-w-lg overflow-hidden relative mt-10">
            <form method="POST" action="{{ route('publishers.store') }}">
                @csrf
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h5 class="font-semibold text-slate-800">{{ __('Tambah Penerbit') }}</h5>
                    <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 transition-colors"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="p-8 max-h-[70vh] overflow-y-auto">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Nama Penerbit') }}</label>
                        <input type="text" name="name" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="{{ __('Contoh: Balai Pustaka') }}" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Kota') }}</label>
                        <input type="text" name="city" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="{{ __('Contoh: Jakarta') }}">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Negara') }}</label>
                        <input type="text" name="country" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="{{ __('Contoh: Indonesia') }}" value="Indonesia">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Website') }}</label>
                        <input type="url" name="website" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="Contoh: https://balaipustaka.co.id">
                    </div>
                </div>
                <div class="px-8 py-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-2">
                    <button type="button" @click="showAddModal = false" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-slate-200 hover:bg-slate-300 transition-colors text-slate-700 py-2 px-6">{{ __('Batal') }}</button>
                    <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white py-2 px-6">{{ __('Simpan') }}</button>
                </div>
            </form>
        </div>
    </div>

    </div> <!-- End x-data -->

<script>
function openEditPublisher(id, name, city, country, website) {
    document.getElementById('editPublisherForm').action = '/publishers/' + id;
    document.getElementById('editPubName').value = name;
    document.getElementById('editPubCity').value = city;
    document.getElementById('editPubCountry').value = country;
    document.getElementById('editPubWebsite').value = website;
    document.getElementById('editPublisherModal').style.display = 'flex';
}
function closeEditPublisher() {
    document.getElementById('editPublisherModal').style.display = 'none';
}
document.getElementById('editPublisherModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditPublisher();
});
</script>
@endsection
