@extends('layouts.app')

@section('title', 'Detail Sirkulasi: ' . $circulation->transaction_code)
@section('page-title', 'Detail Sirkulasi')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('circulations.index') }}">Sirkulasi</a></li>
<li class="breadcrumb-item active">{{ $circulation->transaction_code }}</li>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Detail Transaksi Sirkulasi</h1>
        <p>Detail detail pinjam/kembali dan status denda keterlambatan</p>
    </div>
    <div>
        <a href="{{ route('circulations.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- Left side: Transaction Info --}}
    <div class="col-lg-6">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-info-circle-fill me-2"></i>Informasi Transaksi</div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0" style="font-size:0.85rem">
                    <tr>
                        <td style="width:150px" class="text-muted">KODE TRANSAKSI</td>
                        <td>: <code class="fw-600 fs-6">{{ $circulation->transaction_code }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted">TANGGAL PINJAM</td>
                        <td>: {{ $circulation->loan_date->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">JATUH TEMPO</td>
                        <td>: {{ $circulation->due_date->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">TANGGAL KEMBALI</td>
                        <td>: {{ $circulation->return_date ? $circulation->return_date->format('d F Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">JUMLAH PERPANJANGAN</td>
                        <td>: {{ $circulation->renewal_count }}x perpanjangan</td>
                    </tr>
                    <tr>
                        <td class="text-muted">STATUS</td>
                        <td>:
                            @if($circulation->status === 'Dikembalikan')
                            <span class="badge bg-success">Dikembalikan</span>
                            @elseif($circulation->is_overdue)
                            <span class="badge bg-danger">Terlambat {{ $circulation->days_overdue }} hari</span>
                            @else
                            <span class="badge bg-warning text-dark">Dipinjam (Aktif)</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">PETUGAS MELAYANI</td>
                        <td>: {{ $circulation->user?->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Fine management card --}}
        @if($circulation->fine_amount > 0 || $circulation->is_overdue)
        <div class="card border-danger" style="border-color:#fee2e2!important">
            <div class="card-header bg-danger text-white"><i class="bi bi-cash me-2"></i>Informasi Denda</div>
            <div class="card-body">
                @php
                $dueFine = $circulation->fine_amount > 0 ? $circulation->fine_amount : $circulation->calculated_fine;
                @endphp
                <div class="text-center py-2">
                    <div class="text-muted" style="font-size:0.75rem">JUMLAH DENDA</div>
                    <div class="fs-1 fw-800 text-danger mb-2">Rp {{ number_format($dueFine, 0, ',', '.') }}</div>

                    @if($circulation->fine_paid)
                        <div class="badge bg-success p-2 fs-6 w-100">
                            <i class="bi bi-check-circle-fill me-1"></i>LUNAS (Dibayar pada {{ $circulation->fine_paid_at ? $circulation->fine_paid_at->format('d/m/Y H:i') : '' }})
                        </div>
                    @else
                        <div class="badge bg-warning text-dark p-2 fs-6 mb-3 w-100">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>BELUM DIBAYAR
                        </div>
                        <form method="POST" action="{{ route('circulations.pay-fine', $circulation) }}">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 py-2 fw-600">
                                <i class="bi bi-cash me-2"></i>Bayar Lunas Sekarang
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Right side: Member & Book details --}}
    <div class="col-lg-6">
        {{-- Member detail card --}}
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-person-fill me-2"></i>Detail Anggota</div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:50px;height:50px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;color:#64748b;font-weight:bold">
                        {{ strtoupper(substr($circulation->member->name, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="fw-700 mb-0"><a href="{{ route('members.show', $circulation->member) }}" class="text-decoration-none text-dark">{{ $circulation->member->name }}</a></h5>
                        <div class="text-muted" style="font-size:0.8rem">No. Anggota: <code>{{ $circulation->member->member_code }}</code></div>
                        <div class="text-muted" style="font-size:0.8rem">Tipe Anggota: {{ $circulation->member->member_type }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Book detail card --}}
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-book-fill me-2"></i>Detail Buku & Eksemplar</div>
            <div class="card-body">
                <h5 class="fw-700"><a href="{{ route('books.show', $circulation->bookItem->book) }}" class="text-decoration-none text-dark">{{ $circulation->bookItem->book->title }}</a></h5>
                <p class="text-muted mb-2" style="font-size:0.82rem">Pengarang: {{ $circulation->bookItem->book->main_author ?? '-' }}</p>
                <hr>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="text-muted" style="font-size:0.72rem">BARCODE EKSEMPLAR</div>
                        <code class="fw-600 fs-6">{{ $circulation->bookItem->barcode }}</code>
                    </div>
                    <div class="col-6">
                        <div class="text-muted" style="font-size:0.72rem">NOMOR INDUK</div>
                        <div class="fw-500">{{ $circulation->bookItem->accession_number }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions panel --}}
        @if($circulation->status === 'Dipinjam')
        <div class="card">
            <div class="card-header"><i class="bi bi-gear-fill me-2"></i>Tindakan Sirkulasi</div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($circulation->renewal_count < (int) \App\Models\Setting::get('max_renewals', 2))
                    <form method="POST" action="{{ route('circulations.renew', $circulation) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-repeat me-2"></i>Perpanjang Masa Pinjam (+{{ \App\Models\Setting::get('loan_duration', 14) }} hari)
                        </button>
                    </form>
                    @else
                    <button class="btn btn-outline-secondary" disabled>
                        <i class="bi bi-arrow-repeat me-2"></i>Batas Perpanjangan Tercapai
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
