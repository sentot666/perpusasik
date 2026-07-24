@extends('layouts.app')

@section('title', 'Laporan Perpustakaan')
@section('page-title', 'Laporan')

@section('breadcrumb')
<li class="breadcrumb-item active">Laporan</li>
@endsection

@section('content')
<div class="page-header">
    <h1>Laporan & Statistik</h1>
    <p>Akses berbagai format laporan sirkulasi, koleksi buku, denda, dan data keanggotaan</p>
</div>

<div class="flex flex-wrap -mx-3 mb-6">
    {{-- Summary card --}}
    <div class="w-full md:w-1/3 px-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 h-full overflow-hidden">
            <div class="bg-slate-50 px-8 border-b border-slate-200 font-medium text-slate-700 py-4"><i class="bi bi-graph-up mr-2"></i>Statistik Ringkas</div>
            <div class="p-8">
                <div class="justify-between flex mb-2">
                    <span class="text-slate-500">Total Judul Buku</span>
                    <strong class="text-slate-800">{{ number_format($stats['books_count']) }}</strong>
                </div>
                <div class="justify-between flex mb-2">
                    <span class="text-slate-500">Total Eksemplar Fisik</span>
                    <strong class="text-slate-800">{{ number_format($stats['items_count']) }}</strong>
                </div>
                <div class="justify-between flex mb-2">
                    <span class="text-slate-500">Total Anggota Terdaftar</span>
                    <strong class="text-slate-800">{{ number_format($stats['members_count']) }}</strong>
                </div>
                <div class="justify-between flex mb-2">
                    <span class="text-slate-500">Total Transaksi Sirkulasi</span>
                    <strong class="text-slate-800">{{ number_format($stats['loans_count']) }}</strong>
                </div>
                <div class="justify-between flex mb-2">
                    <span class="text-slate-500">Total Denda Terkumpul</span>
                    <strong class="text-emerald-600">Rp {{ number_format($stats['fines_total'], 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Report modules --}}
    <div class="w-full md:w-2/3 px-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-8 border-b border-slate-200 font-medium text-slate-700 py-4"><i class="bi bi-file-earmark-pdf mr-2"></i>Modul Laporan</div>
            <div class="p-8">
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full md:w-1/2 px-4">
                        <div class="border border-slate-200 rounded h-full flex-col flex p-6">
                            <h6 class="fw-700 mb-1"><i class="bi bi-arrow-left-right text-amber-500 mr-2"></i>Laporan Sirkulasi</h6>
                            <p class="text-slate-500 mb-6" style="font-size:0.78rem">Data peminjaman dan pengembalian buku berdasarkan filter rentang tanggal tertentu.</p>
                            <a href="{{ route('reports.circulation') }}" class="mt-auto align-self-start inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg text-indigo-600 border border-slate-200 border-indigo-600 hover:bg-indigo-50 transition-colors px-4">Buka Laporan</a>
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 px-4">
                        <div class="border border-slate-200 rounded h-full flex-col flex p-6">
                            <h6 class="fw-700 mb-1"><i class="bi bi-people text-indigo-600 mr-2"></i>Laporan Anggota</h6>
                            <p class="text-slate-500 mb-6" style="font-size:0.78rem">Daftar lengkap anggota perpustakaan yang terdaftar beserta status keaktifannya.</p>
                            <a href="{{ route('reports.members') }}" class="mt-auto align-self-start inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg text-indigo-600 border border-slate-200 border-indigo-600 hover:bg-indigo-50 transition-colors px-4">Buka Laporan</a>
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 px-4">
                        <div class="border border-slate-200 rounded h-full flex-col flex p-6">
                            <h6 class="fw-700 mb-1"><i class="bi bi-journal-album text-emerald-600 mr-2"></i>Laporan Koleksi Buku</h6>
                            <p class="text-slate-500 mb-6" style="font-size:0.78rem">Detail katalog buku-buku perpustakaan beserta jumlah eksemplar fisik yang dimiliki.</p>
                            <a href="{{ route('reports.collection') }}" class="mt-auto align-self-start inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg text-indigo-600 border border-slate-200 border-indigo-600 hover:bg-indigo-50 transition-colors px-4">Buka Laporan</a>
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 px-4">
                        <div class="border border-slate-200 rounded h-full flex-col flex p-6">
                            <h6 class="fw-700 mb-1"><i class="bi bi-exclamation-octagon text-red-600 mr-2"></i>Laporan Keterlambatan</h6>
                            <p class="text-slate-500 mb-6" style="font-size:0.78rem">Daftar transaksi peminjaman aktif yang telah melewati batas jatuh tempo pengembalian.</p>
                            <a href="{{ route('reports.overdue') }}" class="mt-auto align-self-start inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg text-indigo-600 border border-slate-200 border-indigo-600 hover:bg-indigo-50 transition-colors px-4">Buka Laporan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
