@extends('layouts.app')

@section('title', __('Laporan Anggota'))
@section('page-title', __('Laporan Anggota'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('reports.index') }}">{{ __('Laporan') }}</a></li>
<li class="breadcrumb-item active">{{ __('Anggota') }}</li>
@endsection

@section('content')
<div class="page-header justify-between items-start flex">
    <div>
        <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ __('Laporan Data Anggota') }}</h1>
        <p>{{ __('Seluruh daftar anggota perpustakaan yang terdaftar') }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('reports.export', ['type' => 'members']) }}" class="gap-1 inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-green text-white transition-colors flex gap-2 py-2 px-6 shadow-sm">
            <i class="bi bi-file-earmark-excel text-base"></i> {{ __('Export Spreadsheet') }}
        </a>
        <button type="button" onclick="window.print()" class="gap-1 inline-flex items-center justify-center text-sm font-medium rounded-lg bg-slate-800 hover:bg-slate-900 text-white  transition-colors flex gap-2 py-2 px-6 shadow-sm">
            <i class="bi bi-printer text-base"></i> {{ __('Cetak Laporan') }}
        </button>
        <a href="{{ route('reports.index') }}" class="gap-1 inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue text-white  transition-colors flex gap-2 py-2 px-6 shadow-sm">
            <i class="bi bi-arrow-left"></i> {{ __('Kembali') }}
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-0">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Kode Anggota') }}</th>
                        <th>{{ __('Nama Anggota') }}</th>
                        <th>{{ __('Jenis Kelamin') }}</th>
                        <th>{{ __('Tipe Anggota') }}</th>
                        <th>{{ __('No. Telepon / Email') }}</th>
                        <th>{{ __('Kota') }}</th>
                        <th>{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $m)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><code>{{ $m->member_code }}</code></td>
                        <td class="font-semibold">{{ $m->name }}</td>
                        <td>{{ $m->gender == 'L' ? __('Laki-laki') : ($m->gender == 'P' ? __('Perempuan') : '-') }}</td>
                        <td>{{ $m->member_type }}</td>
                        <td>{{ $m->phone ?? '-' }} / {{ $m->email ?? '-' }}</td>
                        <td>{{ $m->city ?? '-' }}</td>
                        <td>
                            <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-{{ $m->status_badge_class }}">{{ $m->status_label }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-slate-500 py-6">{{ __('Belum ada data anggota') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection





