@extends('layouts.app')

@section('title', 'Laporan Koleksi')
@section('page-title', 'Laporan Koleksi')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Laporan</a></li>
<li class="breadcrumb-item active">Koleksi</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Laporan Koleksi Buku</h1>
        <p>Total data judul buku dan ketersediaan eksemplar fisik</p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary"><i class="bi bi-printer"></i> Cetak Laporan</button>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
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
                        <td class="fw-600">{{ $b->title }}</td>
                        <td>{{ $b->main_author ?? '-' }}</td>
                        <td>{{ $b->publisher?->name ?? '-' }}</td>
                        <td>{{ $b->publication_year ?? '-' }}</td>
                        <td>{{ $b->collection_type }}</td>
                        <td class="text-center fw-600 text-primary">{{ $b->items_count }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data buku</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
