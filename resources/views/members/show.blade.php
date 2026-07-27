@extends('layouts.app')

@section('title', __('Detail Anggota') . ': ' . $member->name)
@section('page-title', __('Detail Anggota'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('members.index') }}">{{ __('Daftar Anggota') }}</a></li>
<li class="breadcrumb-item active">{{ $member->name }}</li>
@endsection

@section('content')
<div class="page-header justify-between items-start flex">
    <div>
        <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ __('Detail Anggota') }}</h1>
        <p>{{ __('Profil dan data transaksi peminjaman anggota') }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('members.print-card', $member) }}" target="_blank" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-amber-600 border border-slate-200 border-amber-600 hover:bg-amber-50 transition-colors gap-2 py-2 px-6">
            <i class="bi bi-printer mr-1"></i>{{ __('Cetak Kartu') }}
        </a>
        <a href="{{ route('members.edit', $member) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2 px-6">
            <i class="bi bi-pencil mr-1"></i>{{ __('Edit') }}
        </a>
    </div>
</div>

<div class="flex flex-wrap -mx-3">
    {{-- Left: Card Profil --}}
    <div class="w-full lg:w-1/3 px-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="text-center p-8">
                <div class="mx-auto mb-6" style="width:120px;height:120px;border-radius:50%;background:#f1f5f9;border:3px solid #e2e8f0;overflow:hidden;display:flex;align-items:center;justify-content:center;color:#64748b;font-size:3rem;font-weight:bold">
                    @if($member->photo)
                        <img src="{{ asset('storage/' . $member->photo) }}" style="width:100%;height:100%;object-fit:cover">
                    @else
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    @endif
                </div>
                <h4 class="fw-700 text-slate-800 mb-1">{{ $member->name }}</h4>
                <div class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-slate-50 border border-slate-200 text-slate-800 mb-6">{{ $member->member_type }}</div>

                <div class="justify-content-around border-t border-slate-200 border-b my-4 text-center flex py-2">
                    <div>
                        <div class="fw-700 text-lg font-medium text-indigo-600">{{ $activeLoans->count() }}</div>
                        <div class="text-slate-500" style="font-size:0.7rem;text-transform:uppercase">{{ __('Pinjam Aktif') }}</div>
                    </div>
                    <div>
                        <div class="fw-700 text-lg font-medium text-slate-500">{{ $member->circulations()->count() }}</div>
                        <div class="text-slate-500" style="font-size:0.7rem;text-transform:uppercase">{{ __('Total Pinjam') }}</div>
                    </div>
                </div>

                <div class="text-left">
                    <div class="mb-2" style="font-size:0.82rem">
                        <span class="block text-slate-500" style="font-size:0.72rem">{{ __('KODE ANGGOTA') }}</span>
                        <code class="text-base font-semibold">{{ $member->member_code }}</code>
                    </div>
                    <div class="mb-2" style="font-size:0.82rem">
                        <span class="block text-slate-500" style="font-size:0.72rem">{{ __('STATUS') }}</span>
                        <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-{{ $member->status_badge_class }}">{{ $member->status_label }}</span>
                    </div>
                    <div class="mb-2" style="font-size:0.82rem">
                        <span class="block text-slate-500" style="font-size:0.72rem">{{ __('MASA BERLAKU') }}</span>
                        <span class="{{ $member->is_expired ? 'text-danger fw-600' : '' }}">
                            {{ $member->expired_date ? $member->expired_date->format('d F Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Detail & Transaksi --}}
    <div class="w-full lg:w-2/3 px-4">
        {{-- Profile details tab --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-person-fill mr-2"></i>{{ __('Informasi Lengkap') }}</div>
            <div class="p-8">
                <div class="flex flex-wrap -mx-2">
                    <div class="w-full md:w-1/3 w-1/2 px-4">
                        <div class="text-slate-500" style="font-size:0.72rem">{{ __('EMAIL') }}</div>
                        <div class="font-medium">{{ $member->email ?? '-' }}</div>
                    </div>
                    <div class="w-full md:w-1/3 w-1/2 px-4">
                        <div class="text-slate-500" style="font-size:0.72rem">{{ __('TELEPON') }}</div>
                        <div class="font-medium">{{ $member->phone ?? '-' }}</div>
                    </div>
                    <div class="w-full md:w-1/3 w-1/2 px-4">
                        <div class="text-slate-500" style="font-size:0.72rem">{{ __('IDENTITAS') }}</div>
                        <div class="font-medium">{{ $member->identity_type ?? 'KTP' }}: {{ $member->identity_number ?? '-' }}</div>
                    </div>
                    <div class="w-full md:w-1/3 w-1/2 mt-6 px-4">
                        <div class="text-slate-500" style="font-size:0.72rem">{{ __('JENIS KELAMIN') }}</div>
                        <div class="font-medium">{{ $member->gender == 'L' ? __('Laki-laki') : ($member->gender == 'P' ? __('Perempuan') : '-') }}</div>
                    </div>
                    <div class="w-full md:w-1/3 w-1/2 mt-6 px-4">
                        <div class="text-slate-500" style="font-size:0.72rem">{{ __('KOTA & PROVINSI') }}</div>
                        <div class="font-medium">{{ $member->city ?? '-' }}, {{ $member->province ?? '-' }}</div>
                    </div>
                    <div class="w-full md:w-1/3 w-1/2 mt-6 px-4">
                        <div class="text-slate-500" style="font-size:0.72rem">{{ __('TANGGAL DAFTAR') }}</div>
                        <div class="font-medium">{{ $member->register_date ? $member->register_date->format('d/m/Y') : '-' }}</div>
                    </div>
                    <div class="w-full mt-6 px-4">
                        <div class="text-slate-500" style="font-size:0.72rem">{{ __('ALAMAT') }}</div>
                        <div class="font-medium">{{ $member->address ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Active Loans --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="justify-between items-center px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 flex py-4">
                <span><i class="bi bi-arrow-left-right text-amber-500 mr-2"></i>{{ __('Buku Sedang Dipinjam') }}</span>
                <a href="{{ route('members.history', $member) }}" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors px-4">{{ __('Riwayat Lengkap') }}</a>
            </div>
            <div class="p-0">
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50">
                        <thead>
                            <tr>
                                <th>{{ __('Barcode') }}</th>
                                <th>{{ __('Judul Buku') }}</th>
                                <th>{{ __('Tgl Pinjam') }}</th>
                                <th>{{ __('Jatuh Tempo') }}</th>
                                <th>{{ __('Denda (Est)') }}</th>
                                <th>{{ __('Aksi') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeLoans as $loan)
                            <tr>
                                <td><code>{{ $loan->bookItem->barcode }}</code></td>
                                <td class="font-medium">{{ $loan->bookItem->book->title }}</td>
                                <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="{{ $loan->is_overdue ? 'text-danger fw-600' : '' }}">
                                        {{ $loan->due_date->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>
                                    @if($loan->is_overdue)
                                    <span class="text-red-600 font-semibold">Rp {{ number_format($loan->calculated_fine, 0, ',', '.') }}</span>
                                    @else
                                    <span class="text-slate-500">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="inline-flex rounded-md shadow-sm rounded-lg">
                                        <form method="POST" action="{{ route('circulations.renew', $loan) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2 px-6" title="{{ __('Perpanjang') }}">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('circulations.show', $loan) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-indigo-600 border border-slate-200 border-indigo-600 hover:bg-indigo-50 transition-colors gap-2 py-2 px-6" title="{{ __('Detail Transaksi') }}">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-slate-500 py-6">{{ __('Tidak ada peminjaman aktif') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

