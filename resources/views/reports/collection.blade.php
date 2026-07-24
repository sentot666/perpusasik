@extends('layouts.app')

@section('title', 'Laporan Koleksi')
@section('page-title', 'Laporan Koleksi')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Laporan</a></li>
<li class="breadcrumb-item active">Koleksi</li>
@endsection

@section('content')
<div class="page-header justify-between items-start flex">
    <div>
        <h1>Laporan Koleksi Buku</h1>
        <p>Total data judul buku dan ketersediaan eksemplar fisik</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('reports.export', ['type' => 'collection'] + request()->all()) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition-colors gap-2 py-2 px-6 shadow-sm">
            <i class="bi bi-file-earmark-excel text-base"></i> Export Spreadsheet
        </a>
        <button onclick="window.print()" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-slate-800 text-white hover:bg-slate-900 transition-colors gap-2 py-2 px-6 shadow-sm"><i class="bi bi-printer"></i> Cetak Laporan</button>
        <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors gap-2 py-2 px-6 shadow-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-0">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ISBN</th>
                        <th>Judul Buku</th>
                        <th>Pengarang</th>
                        <th>Penerbit</th>
                        <th>Tahun</th>
                        <th>Jenis Koleksi</th>
                        <th class="text-center">Total Kopi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $b)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><code>{{ $b->isbn ?? '-' }}</code></td>
                        <td class="font-semibold">{{ $b->title }}</td>
                        <td>{{ $b->main_author ?? '-' }}</td>
                        <td>{{ $b->publisher?->name ?? '-' }}</td>
                        <td>{{ $b->publication_year ?? '-' }}</td>
                        <td>{{ $b->collection_type }}</td>
                        <td class="text-indigo-600 text-center font-semibold">{{ $b->items_count }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-slate-500 py-6">Belum ada data buku</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
