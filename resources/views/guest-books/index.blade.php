@extends('layouts.app')

@section('title', 'Buku Tamu & Aktivitas Harian')
@section('page-title', 'Buku Tamu')

@section('breadcrumb')
<li class="breadcrumb-item active">Buku Tamu & Aktivitas Harian</li>
@endsection

@section('content')
<div x-data="{ showAddModal: false }">
    <div class="page-header justify-between items-start flex mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Buku Tamu & Aktivitas Harian</h1>
            <p class="text-slate-500 mt-1">Pendataan aktivitas harian tamu dan kunjungan institusi/kelompok di perpustakaan</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('guest-books.export', request()->all()) }}" class="gap-1 inline-flex items-center justify-center text-sm font-medium rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition-colors flex gap-2 py-2 px-6 shadow-sm">
                <i class="bi bi-file-earmark-excel text-base"></i> Export Spreadsheet
            </a>
            <button type="button" onclick="window.print()" class="gap-1 inline-flex items-center justify-center text-sm font-medium rounded-lg bg-slate-800 text-white hover:bg-slate-900 transition-colors flex gap-2 py-2 px-6 shadow-sm">
                <i class="bi bi-printer text-base"></i> Cetak Laporan
            </button>
            <button type="button" @click="showAddModal = true" class="gap-1 items-center inline-flex justify-center text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 transition-colors text-white flex gap-2 py-2 px-6">
                <i class="bi bi-journal-plus text-base"></i>Catat Kunjungan Baru
            </button>
        </div>
    </div>

{{-- Print-only Header --}}
<div class="hidden print-header mb-6">
    <h2 class="uppercase text-center font-bold" style="border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px;">
        Laporan Kunjungan Tamu & Aktivitas Harian<br>
        <span style="font-size: 1.1rem; font-weight: normal; text-transform: none;">
            {{ config('app.name', 'Makarya') }}
        </span>
    </h2>
    @if($startDate || $endDate)
        <p class="text-center text-slate-500" style="margin-top: -10px;">
            Periode: 
            @if($startDate) <strong>{{ date('d/m/Y', strtotime($startDate)) }}</strong> @else Awal @endif
            s.d.
            @if($endDate) <strong>{{ date('d/m/Y', strtotime($endDate)) }}</strong> @else Akhir @endif
        </p>
    @endif
    <div class="text-center text-slate-800" style="margin-top: 10px; font-weight: 600; font-size: 0.95rem;">
        Total Kunjungan: {{ $activities->total() }} kali &nbsp;|&nbsp; Total Peserta: {{ $totalParticipants ?? 0 }} orang
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
    <div class="px-8 py-2">
        <form method="GET" class="items-end flex flex-wrap -mx-2" id="guestBookFilterForm">
            <div class="w-full md:w-1/3 px-4">
                <label class="block text-sm font-medium text-slate-700 mb-1" style="font-size:0.75rem;font-weight:600">CARI TAMU / INSTANSI</label>
                <div class="flex w-full text-sm">
                    <span class="flex items-center px-3 py-2 bg-slate-100 border border-slate-300 text-slate-600 bg-slate-50 border-r-0"><i class="bi bi-search text-slate-500"></i></span>
                    <input type="text" name="search" class="border-l-0 bg-slate-50 w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="Cari nama, instansi, atau tujuan..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2.5 w-1/2 px-4">
                <label class="block text-sm font-medium text-slate-700 mb-1" style="font-size:0.75rem;font-weight:600">MULAI TANGGAL</label>
                <input type="date" name="start_date" class="bg-slate-50 w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none px-4" value="{{ $startDate }}">
            </div>
            <div class="col-md-2.5 w-1/2 px-4">
                <label class="block text-sm font-medium text-slate-700 mb-1" style="font-size:0.75rem;font-weight:600">SAMPAI TANGGAL</label>
                <input type="date" name="end_date" class="bg-slate-50 w-full rounded-lg border border-slate-200 border-slate-300 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none px-4" value="{{ $endDate }}">
            </div>
            <div class="w-auto px-4">
                <button type="submit" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 transition-colors text-white px-4"><i class="bi bi-funnel"></i> Filter</button>
                <a href="{{ route('guest-books.index') }}" class="inline-flex items-center justify-center gap-1.5 py-1.5 text-xs font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors px-4"><i class="bi bi-x"></i> Reset</a>
            </div>
            <div class="ml-auto text-slate-500 w-auto px-4" style="font-size:0.8rem; padding-bottom: 5px;">
                {{ $activities->total() }} kunjungan tercatat
            </div>
        </form>
    </div>
</div>

{{-- Main Table Card --}}
<div class="shadow-sm border-0 bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="p-0">
        <div class="overflow-x-auto w-full">
            <table class="align-middle w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50" style="font-size: 0.88rem;">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="pl-4" style="width: 60px;">No</th>
                        <th>Hari & Tanggal</th>
                        <th>Nama</th>
                        <th>Kunjungan Dari</th>
                        <th>Tujuan</th>
                        <th>Waktu Kunjungan</th>
                        <th class="text-center">Jumlah Peserta</th>
                        <th class="text-center" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    <tr>
                        <td class="pl-4 text-slate-500">{{ $activities->firstItem() + $loop->index }}</td>
                        <td class="text-slate-800 font-medium">{{ $activity->formatted_date }}</td>
                        <td class="text-indigo-600 font-semibold">{{ $activity->name }}</td>
                        <td>{{ $activity->institution }}</td>
                        <td>
                            <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-slate-50 border border-slate-200 p-2 font-normal text-slate-800" style="font-size: 0.8rem;">
                                {{ $activity->purpose }}
                                @if($activity->purpose === 'Lainnya' && $activity->notes)
                                    <br><span class="text-slate-500 italic mt-1 block">{{ $activity->notes }}</span>
                                @endif
                            </span>
                        </td>
                        <td>
                            <code class="py-1 bg-slate-50 border border-slate-200 text-slate-600 px-2" style="border-radius:4px">
                                <i class="bi bi-clock mr-1 text-slate-500"></i>{{ $activity->formatted_time }}
                            </code>
                        </td>
                        <td class="text-center">
                            <span class="text-sm inline-flex py-1 text-xs font-medium rounded-md bg-indigo-100 text-indigo-700 py-2 px-2 px-4" style="border-radius: 20px;">
                                {{ $activity->participants_count }} orang
                            </span>
                        </td>
                        <td class="pr-4 text-center">
                            <form action="{{ route('guest-books.destroy', $activity) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan kunjungan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 bg-red-50 hover:bg-red-100 transition-colors" title="Hapus catatan">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-500">
                            <i class="bi bi-journal-x text-4xl font-bold block opacity-50 text-slate-500 mb-2"></i>
                            Belum ada catatan aktivitas kunjungan tamu untuk hari ini atau pencarian Anda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-6">
    {{ $activities->links() }}
</div>

{{-- Add Guest Modal --}}
    <div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-6">
        <div @click.outside="showAddModal = false" class="bg-white rounded-xl shadow-lg w-full max-w-lg overflow-hidden relative mt-10">
            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-indigo-600 text-white">
                <h5 class="font-semibold flex items-center gap-2">
                    <i class="bi bi-journal-plus text-lg font-medium"></i>Catat Kunjungan Tamu
                </h5>
                <button type="button" @click="showAddModal = false" class="text-white hover:text-slate-200 transition-colors"><i class="bi bi-x-lg"></i></button>
            </div>
            <form action="{{ route('guest-books.store') }}" method="POST">
                @csrf
                <div class="p-8 max-h-[70vh] overflow-y-auto">
                    
                    {{-- Row: Tanggal & Waktu --}}
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label for="visit_date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Kunjungan <span class="text-red-600">*</span></label>
                            <input type="date" name="visit_date" id="visit_date" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label for="visit_time" class="block text-sm font-medium text-slate-700 mb-1">Waktu Kunjungan <span class="text-red-600">*</span></label>
                            <input type="time" name="visit_time" id="visit_time" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ date('H:i') }}" required>
                        </div>
                    </div>

                    {{-- Input: Nama --}}
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap Tamu / Perwakilan <span class="text-red-600">*</span></label>
                        <div class="flex w-full">
                            <span class="flex items-center px-3 py-2 bg-slate-100 border border-slate-300 text-slate-600"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" id="name" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="Ketik nama lengkap..." required>
                        </div>
                    </div>

                    {{-- Input: Instansi / Asal --}}
                    <div class="mb-6">
                        <label for="institution" class="block text-sm font-medium text-slate-700 mb-1">Kunjungan Dari (Instansi / Kelas / Asal) <span class="text-red-600">*</span></label>
                        <div class="flex w-full">
                            <span class="flex items-center px-3 py-2 bg-slate-100 border border-slate-300 text-slate-600"><i class="bi bi-building"></i></span>
                            <input type="text" name="institution" id="institution" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="Ketik instansi asal atau alamat..." required>
                        </div>
                    </div>

                    {{-- Input: Tujuan --}}
                    <div class="mb-6">
                        <label for="purpose" class="block text-sm font-medium text-slate-700 mb-1">Tujuan Kunjungan <span class="text-red-600">*</span></label>
                        <div class="flex w-full">
                            <span class="flex items-center px-3 py-2 bg-slate-100 border border-slate-300 text-slate-600"><i class="bi bi-journal-check"></i></span>
                            <input type="text" name="purpose" id="purpose" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" placeholder="Ketik tujuan kunjungan (studi banding, baca buku, dll.)..." required>
                        </div>
                    </div>

                    {{-- Row: Jumlah Peserta --}}
                    <div class="mb-6">
                        <label for="participants_count" class="block text-sm font-medium text-slate-700 mb-1">Jumlah Peserta <span class="text-red-600">*</span></label>
                        <div class="flex w-full">
                            <span class="flex items-center px-3 py-2 bg-slate-100 border border-slate-300 text-slate-600"><i class="bi bi-people"></i></span>
                            <input type="number" name="participants_count" id="participants_count" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" min="1" value="1" required>
                            <span class="flex items-center px-3 py-2 bg-slate-100 border border-slate-300 text-slate-600">Orang</span>
                        </div>
                        <div class="form-text">Masukkan jumlah orang yang ikut rombongan (termasuk perwakilan).</div>
                    </div>

                    {{-- Input: Catatan --}}
                    <div class="mb-0">
                        <label for="notes" class="block text-sm font-medium text-slate-700 mb-1">Catatan Tambahan</label>
                        <textarea name="notes" id="notes" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" rows="2" placeholder="Opsional..."></textarea>
                    </div>

                </div>
                <div class="px-8 py-6 border-t border-slate-100 bg-slate-50 flex justify-end gap-2">
                    <button type="button" @click="showAddModal = false" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors py-2 px-6">Batal</button>
                    <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 transition-colors text-white font-semibold py-2 px-6">Simpan Catatan</button>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection

@push('styles')
<style>
@media print {
    /* Hide layout elements */
    #sidebar, .topbar, .card:has(form), .btn, nav[aria-label="breadcrumb"], footer, .modal, .pagination, .page-header {
        display: none !important;
    }
    /* Show print header */
    .print-header {
        display: block !important;
    }
    .main-wrapper {
        margin-left: 0 !important;
        padding: 0 !important;
    }
    .content-area {
        padding: 0 !important;
        margin: 0 !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    /* Format table for print */
    .table {
        width: 100% !important;
        border-collapse: collapse !important;
        font-size: 0.85rem !important;
    }
    .table th, .table td {
        border: 1px solid #000 !important;
        padding: 6px 8px !important;
        color: #000 !important;
    }
    .table-light {
        background-color: #f8f9fa !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .badge {
        border: none !important;
        background: transparent !important;
        color: #000 !important;
        padding: 0 !important;
        font-size: 0.85rem !important;
    }
    code {
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
        color: #000 !important;
    }
}
</style>
@endpush
