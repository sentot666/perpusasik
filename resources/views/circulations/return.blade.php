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

<div class="justify-center flex flex-wrap -mx-3">
    <div class="w-full lg:w-1/2 px-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-box-arrow-in-left text-emerald-600 mr-2"></i>Form Pengembalian</div>
            <div class="p-8">

                @if(session('success'))
                <div class="alert alert-success border-0 mb-6 py-2" style="border-radius:8px">
                    <i class="bi bi-check-circle-fill mr-1"></i>{{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger border-0 mb-6 py-2" style="border-radius:8px">
                    <i class="bi bi-exclamation-triangle-fill mr-1"></i>{{ session('error') }}
                </div>
                @endif

                <form method="POST" action="{{ route('circulations.process-return') }}" id="returnForm">
                    @csrf

                    <div class="mb-6">
                        <label for="returnBarcode" class="block text-sm font-medium text-slate-700 mb-1">Barcode Buku <span class="text-red-600">*</span></label>
                        <div class="flex w-full input-group-lg">
                            <span class="flex items-center px-3 py-2 bg-slate-100 border border-slate-300 text-slate-600"><i class="bi bi-upc-scan"></i></span>
                            <input
                                type="text"
                                name="barcode"
                                id="returnBarcode"
                                class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4"
                                placeholder="Scan barcode eksemplar buku..."
                                autofocus
                                autocomplete="off"
                            >
                        </div>
                        <div class="form-text">Arahkan scanner ke barcode pada buku, atau ketik manual lalu Enter</div>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center text-sm font-medium rounded-lg bg-emerald-600 hover:bg-emerald-700 transition-colors text-white font-semibold gap-2 py-2 px-6" id="returnSubmitBtn">
                        <i class="bi bi-box-arrow-in-left mr-2"></i>Proses Pengembalian
                    </button>
                </form>

                <div class="text-center mt-6">
                    <small class="text-slate-500">Denda keterlambatan: <strong>Rp {{ number_format(\App\Models\Setting::get('fine_per_day', 1000)) }}/hari</strong></small>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent returns today --}}
    <div class="col-lg-10 mt-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-clock-history mr-2"></i>Pengembalian Hari Ini</div>
            <div class="p-0">
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50">
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
                                    <div class="font-medium">{{ $c->member->name }}</div>
                                    <div class="text-slate-500" style="font-size:0.72rem">{{ $c->member->member_code }}</div>
                                </td>
                                <td class="truncate" style="max-width:200px">{{ $c->bookItem->book->title }}</td>
                                <td>{{ $c->loan_date->format('d/m/Y') }}</td>
                                <td>{{ $c->return_date->format('d/m/Y') }}</td>
                                <td>
                                    @if($c->fine_amount > 0)
                                    <span class="text-red-600 font-semibold">Rp {{ number_format($c->fine_amount, 0, ',', '.') }}</span>
                                    @else
                                    <span class="text-slate-500">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($c->fine_amount > 0 && !$c->fine_paid)
                                    <span class="inline-flex py-1 text-xs font-medium rounded-md bg-amber-100 text-amber-800 px-2">Belum Dibayar</span>
                                    @elseif($c->fine_paid)
                                    <span class="inline-flex py-1 text-xs font-medium rounded-md bg-emerald-100 text-emerald-700 px-2">Lunas</span>
                                    @else
                                    <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-slate-50 text-slate-800">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-slate-500 py-4">Belum ada pengembalian hari ini</td></tr>
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
        btn.innerHTML = '<span class="spinner-border spinner-border-sm mr-2"></span>Memproses...';
        btn.disabled = true;
        document.getElementById('returnForm').submit();
    }
});
</script>
@endpush
