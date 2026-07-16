@extends('layouts.app')

@section('title', 'Pengembalian Buku')
@section('page-title', 'Proses Pengembalian')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('circulations.index') }}">Sirkulasi</a></li>
<li class="breadcrumb-item active">Pengembalian</li>
@endsection

@section('content')
<div class="page-header">
    <h1>Proses Pengembalian</h1>
    <p>Scan barcode eksemplar buku untuk memproses pengembalian</p>
</div>

<div class="row g-3 justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-box-arrow-in-left me-2 text-success"></i>Form Pengembalian</div>
            <div class="card-body">

                @if(session('success'))
                <div class="alert alert-success border-0 py-2 mb-3" style="border-radius:8px">
                    <i class="bi bi-check-circle-fill me-1"></i>{{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger border-0 py-2 mb-3" style="border-radius:8px">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ session('error') }}
                </div>
                @endif

                <form method="POST" action="{{ route('circulations.process-return') }}" id="returnForm">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-500">Barcode Buku <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                            <input
                                type="text"
                                name="barcode"
                                id="returnBarcode"
                                class="form-control"
                                placeholder="Scan barcode eksemplar buku..."
                                autofocus
                                autocomplete="off"
                            >
                        </div>
                        <div class="form-text">Arahkan scanner ke barcode pada buku, atau ketik manual lalu Enter</div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-600" id="returnSubmitBtn">
                        <i class="bi bi-box-arrow-in-left me-2"></i>Proses Pengembalian
                    </button>
                </form>

                <div class="text-center mt-4">
                    <small class="text-muted">Denda keterlambatan: <strong>Rp {{ number_format(\App\Models\Setting::get('fine_per_day', 1000)) }}/hari</strong></small>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent returns today --}}
    <div class="col-lg-10 mt-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-clock-history me-2"></i>Pengembalian Hari Ini</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Anggota</th>
                                <th>Judul Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Denda</th>
                                <th>Status Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $todayReturns = \App\Models\Circulation::with(['member', 'bookItem.book'])
                                ->whereDate('return_date', today())
                                ->latest('return_date')
                                ->limit(10)
                                ->get();
                            @endphp
                            @forelse($todayReturns as $c)
                            <tr>
                                <td>
                                    <div class="fw-500">{{ $c->member->name }}</div>
                                    <div class="text-muted" style="font-size:0.72rem">{{ $c->member->member_code }}</div>
                                </td>
                                <td class="text-truncate" style="max-width:200px">{{ $c->bookItem->book->title }}</td>
                                <td>{{ $c->loan_date->format('d/m/Y') }}</td>
                                <td>{{ $c->return_date->format('d/m/Y') }}</td>
                                <td>
                                    @if($c->fine_amount > 0)
                                    <span class="text-danger fw-600">Rp {{ number_format($c->fine_amount, 0, ',', '.') }}</span>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($c->fine_amount > 0 && !$c->fine_paid)
                                    <span class="badge bg-warning text-dark">Belum Dibayar</span>
                                    @elseif($c->fine_paid)
                                    <span class="badge bg-success">Lunas</span>
                                    @else
                                    <span class="badge bg-light text-dark">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-3">Belum ada pengembalian hari ini</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-submit on Enter after barcode scan
document.getElementById('returnBarcode').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const btn = document.getElementById('returnSubmitBtn');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        btn.disabled = true;
        document.getElementById('returnForm').submit();
    }
});
</script>
@endpush
