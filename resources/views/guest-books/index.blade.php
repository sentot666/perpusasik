@extends('layouts.app')

@section('title', 'Buku Tamu & Aktivitas Harian')
@section('page-title', 'Buku Tamu')

@section('breadcrumb')
<li class="breadcrumb-item active">Buku Tamu & Aktivitas Harian</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Buku Tamu & Aktivitas Harian</h1>
        <p>Pendataan aktivitas harian tamu dan kunjungan institusi/kelompok di perpustakaan</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" onclick="window.print()" class="btn btn-outline-secondary d-flex align-items-center gap-1">
            <i class="bi bi-printer fs-6"></i>Cetak Laporan
        </button>
        <button type="button" class="btn btn-primary d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addGuestModal">
            <i class="bi bi-journal-plus fs-6"></i>Catat Kunjungan Baru
        </button>
    </div>
</div>

{{-- Print-only Header --}}
<div class="d-none print-header mb-4">
    <h2 class="text-center fw-bold text-uppercase" style="border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px;">
        Laporan Kunjungan Tamu & Aktivitas Harian<br>
        <span style="font-size: 1.1rem; font-weight: normal; text-transform: none;">
            {{ config('app.name', 'Makarya') }}
        </span>
    </h2>
    @if($startDate || $endDate)
        <p class="text-center text-muted" style="margin-top: -10px;">
            Periode: 
            @if($startDate) <strong>{{ date('d/m/Y', strtotime($startDate)) }}</strong> @else Awal @endif
            s.d.
            @if($endDate) <strong>{{ date('d/m/Y', strtotime($endDate)) }}</strong> @else Akhir @endif
        </p>
    @endif
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end" id="guestBookFilterForm">
            <div class="col-md-4">
                <label class="form-label" style="font-size:0.75rem;font-weight:600">CARI TAMU / INSTANSI</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 bg-light" placeholder="Cari nama, instansi, atau tujuan..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2.5 col-6">
                <label class="form-label" style="font-size:0.75rem;font-weight:600">MULAI TANGGAL</label>
                <input type="date" name="start_date" class="form-control form-control-sm bg-light" value="{{ $startDate }}">
            </div>
            <div class="col-md-2.5 col-6">
                <label class="form-label" style="font-size:0.75rem;font-weight:600">SAMPAI TANGGAL</label>
                <input type="date" name="end_date" class="form-control form-control-sm bg-light" value="{{ $endDate }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                <a href="{{ route('guest-books.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i> Reset</a>
            </div>
            <div class="col-auto ms-auto text-muted" style="font-size:0.8rem; padding-bottom: 5px;">
                {{ $activities->total() }} kunjungan tercatat
            </div>
        </form>
    </div>
</div>

{{-- Main Table Card --}}
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                <thead>
                    <tr class="table-light">
                        <th class="ps-3" style="width: 60px;">No</th>
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
                        <td class="text-muted ps-3">{{ $activities->firstItem() + $loop->index }}</td>
                        <td class="fw-500 text-dark">{{ $activity->formatted_date }}</td>
                        <td class="fw-600 text-primary">{{ $activity->name }}</td>
                        <td>{{ $activity->institution }}</td>
                        <td>
                            <span class="badge bg-light text-dark border p-2 fw-normal" style="font-size: 0.8rem;">
                                {{ $activity->purpose }}
                            </span>
                        </td>
                        <td>
                            <code class="px-2 py-1 bg-light border text-secondary" style="border-radius:4px">
                                <i class="bi bi-clock me-1 text-muted"></i>{{ $activity->formatted_time }}
                            </code>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary px-3 py-2 fs-7" style="border-radius: 20px;">
                                {{ $activity->participants_count }} orang
                            </span>
                        </td>
                        <td class="text-center pe-3">
                            <form action="{{ route('guest-books.destroy', $activity) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan kunjungan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus catatan">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-journal-x fs-1 d-block mb-2 text-muted opacity-50"></i>
                            Belum ada catatan aktivitas kunjungan tamu untuk hari ini atau pencarian Anda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $activities->links() }}
</div>

{{-- Add Guest Modal --}}
<div class="modal fade" id="addGuestModal" tabindex="-1" aria-labelledby="addGuestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-600 d-flex align-items-center gap-2" id="addGuestModalLabel">
                    <i class="bi bi-journal-plus fs-5"></i>Catat Kunjungan Tamu
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('guest-books.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    
                    {{-- Row: Tanggal & Waktu --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="visit_date" class="form-label fw-500">Tanggal Kunjungan <span class="text-danger">*</span></label>
                            <input type="date" name="visit_date" id="visit_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="visit_time" class="form-label fw-500">Waktu Kunjungan <span class="text-danger">*</span></label>
                            <input type="time" name="visit_time" id="visit_time" class="form-control" value="{{ date('H:i') }}" required>
                        </div>
                    </div>

                    {{-- Input: Nama --}}
                    <div class="mb-3">
                        <label for="name" class="form-label fw-500">Nama Lengkap Tamu / Perwakilan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Ketik nama lengkap..." required>
                        </div>
                    </div>

                    {{-- Input: Instansi / Asal --}}
                    <div class="mb-3">
                        <label for="institution" class="form-label fw-500">Kunjungan Dari (Instansi / Kelas / Asal) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-building"></i></span>
                            <input type="text" name="institution" id="institution" class="form-control" placeholder="Ketik instansi asal atau alamat..." required>
                        </div>
                    </div>

                    {{-- Input: Tujuan --}}
                    <div class="mb-3">
                        <label for="purpose" class="form-label fw-500">Tujuan Kunjungan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-journal-check"></i></span>
                            <input type="text" name="purpose" id="purpose" class="form-control" placeholder="Ketik tujuan kunjungan (studi banding, baca buku, dll.)..." required>
                        </div>
                    </div>

                    {{-- Row: Jumlah Peserta --}}
                    <div class="mb-3">
                        <label for="participants_count" class="form-label fw-500">Jumlah Peserta <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-people"></i></span>
                            <input type="number" name="participants_count" id="participants_count" class="form-control" min="1" value="1" required>
                            <span class="input-group-text">Orang</span>
                        </div>
                        <div class="form-text">Masukkan jumlah orang yang ikut rombongan (termasuk perwakilan).</div>
                    </div>

                    {{-- Input: Catatan --}}
                    <div class="mb-0">
                        <label for="notes" class="form-label fw-500">Catatan Tambahan</label>
                        <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Opsional..."></textarea>
                    </div>

                </div>
                <div class="modal-footer bg-light py-3 border-top-0 d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 fw-600">Simpan Catatan</button>
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
