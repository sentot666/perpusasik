@extends('layouts.app')

@section('title', __('Data Anggota'))
@section('page-title', __('Data Anggota'))

@section('breadcrumb')
<li class="breadcrumb-item active">{{ __('Data Anggota') }}</li>
@endsection

@section('content')
<div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-1">{{ __('Daftar Anggota') }}</h1>
        <p class="text-slate-500 text-xs sm:text-sm">{{ __('Kelola data anggota perpustakaan digital') }}</p>
    </div>
    <div class="flex gap-2 w-full sm:w-auto">
        <a href="{{ route('members.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue transition-colors text-white gap-2 py-2 px-5">
            <i class="bi bi-person-plus"></i>{{ __('Tambah Anggota') }}
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
    <div class="p-4 sm:p-6">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center" id="memberFilterForm">
            <div class="lg:col-span-5">
                <div class="flex w-full text-sm">
                    <span class="flex items-center px-3 py-2 bg-slate-100 border border-slate-300 text-slate-600 rounded-l-lg"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="w-full rounded-r-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-3" placeholder="{{ __('Cari nama, kode, email, no. identitas...') }}" value="{{ request('search') }}">
                </div>
            </div>
            <div class="lg:col-span-2">
                <select name="member_type" class="w-full rounded-lg border border-slate-300 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white px-3" onchange="this.form.submit()">
                    <option value="">{{ __('Semua Tipe') }}</option>
                    @foreach($memberTypes as $type)
                    <option value="{{ $type }}" {{ request('member_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-2">
                <select name="status" class="w-full rounded-lg border border-slate-300 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white px-3" onchange="this.form.submit()">
                    <option value="">{{ __('Semua Status') }}</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>{{ __('Aktif') }}</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>{{ __('Nonaktif') }}</option>
                </select>
            </div>
            <div class="lg:col-span-3 flex items-center justify-between gap-2">
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center justify-center gap-1.5 py-2 text-xs font-semibold rounded-lg btn-gradient-blue transition-colors text-white px-3"><i class="bi bi-funnel"></i> {{ __('Filter') }}</button>
                    <a href="{{ route('members.index') }}" class="inline-flex items-center justify-center gap-1.5 py-2 text-xs font-medium rounded-lg text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors px-3"><i class="bi bi-x"></i> {{ __('Reset') }}</a>
                </div>
                <div class="text-slate-500 text-xs font-medium whitespace-nowrap">
                    {{ $members->total() }} {{ __('anggota') }}
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-0">
        <form action="{{ route('members.print-bulk') }}" method="POST" target="_blank" id="bulkPrintForm" style="display: none;">
            @csrf
            <!-- Hidden inputs will be appended here by JS -->
        </form>
            
        <div class="px-4 py-3 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="text-sm font-medium text-slate-700">{{ __('Aksi Massal:') }}</div>
            <button type="button" class="btn btn-primary bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors" onclick="submitBulkPrint()">
                <i class="bi bi-printer mr-1"></i> {{ __('Cetak Kartu Terpilih') }}
            </button>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50">
                <thead>
                    <tr>
                        <th class="w-10 text-center">
                            <input type="checkbox" id="selectAll" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onclick="document.querySelectorAll('input[name=\'member_ids[]\']').forEach(cb => cb.checked = this.checked)">
                        </th>
                        <th>#</th>
                        <th>{{ __('Kode Anggota') }}</th>
                        <th>{{ __('Nama') }}</th>
                        <th>{{ __('Kontak') }}</th>
                        <th>{{ __('Tipe') }}</th>
                        <th>{{ __('Masa Berlaku') }}</th>
                        <th class="text-center">{{ __('Status') }}</th>
                        <th class="text-center">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" name="member_ids[]" value="{{ $member->id }}" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </td>
                        <td class="text-slate-500">{{ $members->firstItem() + $loop->index }}</td>
                        <td><code class="font-bold text-slate-800 bg-slate-100 px-1.5 py-0.5 rounded text-xs">{{ $member->member_code }}</code></td>
                        <td>
                            <div class="font-medium text-slate-800">
                                <a href="{{ route('members.show', $member) }}" class="no-underline text-slate-800 hover:text-indigo-600">
                                    {{ $member->name }}
                                </a>
                            </div>
                            @if($member->identity_number)
                            <div class="text-slate-500 text-xs">{{ $member->identity_type ?? 'ID' }}: {{ $member->identity_number }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="text-xs">{{ $member->email ?? '-' }}</div>
                            <div class="text-xs text-slate-400">{{ $member->phone ?? '-' }}</div>
                        </td>
                        <td><span class="inline-block px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700">{{ $member->member_type }}</span></td>
                        <td class="text-xs">
                            {{ $member->expired_date ? $member->expired_date->format('d/m/Y') : '-' }}
                        </td>
                        <td class="text-center">
                            @if($member->status_label === 'Aktif')
                            <span class="inline-flex py-0.5 text-xs font-medium rounded-md bg-emerald-100 text-emerald-700 px-2.5">{{ __('Aktif') }}</span>
                            @elseif($member->status_label === 'Kedaluwarsa')
                            <span class="inline-flex py-0.5 text-xs font-medium rounded-md bg-amber-100 text-amber-700 px-2.5">{{ __('Kedaluwarsa') }}</span>
                            @else
                            <span class="inline-flex py-0.5 text-xs font-medium rounded-md bg-slate-100 text-slate-600 px-2.5">{{ __('Nonaktif') }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('members.print-card', $member) }}" target="_blank" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="{{ __('Cetak Kartu') }}">
                                    <i class="bi bi-card-text"></i>
                                </a>
                                <a href="{{ route('members.show', $member) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="{{ __('Detail') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('edit-members')
                                <a href="{{ route('members.edit', $member) }}" class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="{{ __('Edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                @can('delete-members')
                                <form action="{{ route('members.destroy', $member) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="{{ __('Hapus') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                    <tr>
                        <td colspan="9" class="text-center py-8 text-slate-500">
                            <i class="bi bi-people text-3xl block mb-2 text-slate-300"></i>
                            {{ __('Tidak ada data anggota ditemukan') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </table>
        </div>
        @if($members->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $members->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function submitBulkPrint() {
        const checkboxes = document.querySelectorAll('input[name="member_ids[]"]:checked');
        if (checkboxes.length === 0) {
            alert('Pilih minimal satu anggota!');
            return;
        }

        const form = document.getElementById('bulkPrintForm');
        // Clear previous hidden inputs except token
        form.querySelectorAll('input[name="member_ids[]"]').forEach(el => el.remove());

        // Append new selected inputs
        checkboxes.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'member_ids[]';
            input.value = cb.value;
            form.appendChild(input);
        });

        form.submit();
    }
</script>
@endpush
@endsection
