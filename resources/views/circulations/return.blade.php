@extends('layouts.app')

@section('title', __('Pengembalian Buku'))
@section('page-title', __('Proses Pengembalian'))


@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ __('Proses Pengembalian') }}</h1>
    <p class="text-slate-500 text-sm">{{ __('Scan barcode eksemplar buku untuk memproses pengembalian') }}</p>
</div>

<div class="justify-center flex flex-wrap -mx-3">
    <div class="w-full px-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-box-arrow-in-left text-emerald-600 mr-2"></i>{{ __('Form Pengembalian') }}</div>
            <div class="p-8">



                <form method="POST" action="{{ route('circulations.process-return') }}" id="returnForm">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Barcode Buku') }} <span class="text-red-600">*</span></label>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Left: Scanner -->
                            <div>
                                <div class="flex w-full input-group-lg relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                                        <i class="bi bi-upc-scan"></i>
                                    </span>
                                    <input
                                        type="text"
                                        id="returnBarcodeScan"
                                        class="w-full pl-10 rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4"
                                        placeholder="{{ __('Scan barcode...') }}"
                                        autofocus
                                        autocomplete="off"
                                    >
                                </div>
                                <div class="form-text mt-1 text-xs text-slate-500">{{ __('Otomatis diproses (Scanner)') }}</div>
                            </div>
                            
                            <!-- Right: Manual -->
                            <div>
                                <div class="flex w-full input-group-lg relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                                        <i class="bi bi-keyboard"></i>
                                    </span>
                                    <input
                                        type="text"
                                        id="returnBarcodeManual"
                                        class="w-full pl-10 rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4"
                                        placeholder="{{ __('Ketik manual...') }}"
                                        autocomplete="off"
                                    >
                                </div>
                                <div class="form-text mt-1 text-xs text-slate-500">{{ __('Tunggu 1 detik atau tekan Enter') }}</div>
                            </div>
                        </div>
                        <input type="hidden" name="barcode" id="actualReturnBarcode">
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-green transition-colors text-white font-semibold gap-2 py-2 px-6" id="returnSubmitBtn">
                        <i class="bi bi-box-arrow-in-left mr-2"></i>{{ __('Proses Pengembalian') }}
                    </button>
                </form>

                <div class="text-center mt-6">
                    <small class="text-slate-500">{{ __('Denda keterlambatan') }}: <strong>Rp {{ number_format(\App\Models\Setting::get('fine_per_day', 1000)) }}/{{ __('hari') }}</strong></small>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Pengembalian Kelas --}}
    <div class="w-full px-4 mt-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4">
                <i class="bi bi-people-fill text-indigo-600 mr-2"></i>{{ __('Form Pengembalian Kelas / Kelompok') }}
            </div>
            <div class="p-8">
                @if(isset($activeClassLoans) && $activeClassLoans->count() > 0)
                <form method="POST" action="{{ route('circulations.process-class-return-form') }}">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Pilih Peminjaman Kelas yang Akan Dikembalikan') }} <span class="text-red-600">*</span></label>
                        <select name="class_loan_id" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" required>
                            <option value="">-- Pilih Peminjaman Kelas --</option>
                            @foreach($activeClassLoans as $cl)
                                <option value="{{ $cl->id }}">
                                    {{ $cl->borrower_name }} ({{ $cl->origin }}) - {{ $cl->book_type }} ({{ $cl->quantity }} eks) - Pinjam: {{ $cl->loan_date->format('d/m/Y') }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text mt-1 text-xs text-slate-500">{{ __('Daftar ini hanya menampilkan kelas yang bukunya belum dikembalikan.') }}</div>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 transition-colors text-white font-semibold gap-2 py-2 px-6">
                        <i class="bi bi-check2-circle mr-2"></i>{{ __('Proses Pengembalian Kelas') }}
                    </button>
                </form>
                @else
                <div class="text-center text-slate-500 py-4">
                    <i class="bi bi-info-circle text-2xl block mb-2 text-slate-400"></i>
                    {{ __('Tidak ada peminjaman kelas yang sedang aktif saat ini.') }}
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Recent returns today --}}
    <div class="w-full px-4 mt-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-clock-history mr-2"></i>{{ __('Pengembalian Hari Ini') }}</div>
            <div class="p-0">
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50">
                        <thead>
                            <tr>
                                <th>{{ __('Anggota') }}</th>
                                <th>{{ __('Judul Buku') }}</th>
                                <th>{{ __('Tgl Pinjam') }}</th>
                                <th>{{ __('Tgl Kembali') }}</th>
                                <th>{{ __('Denda') }}</th>
                                <th>{{ __('Status Denda') }}</th>
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
                                    <span class="inline-flex py-1 text-xs font-medium rounded-md bg-amber-100 text-amber-800 px-2">{{ __('Belum Dibayar') }}</span>
                                    @elseif($c->fine_paid)
                                    <span class="inline-flex py-1 text-xs font-medium rounded-md bg-emerald-100 text-emerald-700 px-2">{{ __('Lunas') }}</span>
                                    @else
                                    <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-slate-50 text-slate-800">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-slate-500 py-4">{{ __('Belum ada pengembalian hari ini') }}</td></tr>
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
let returnBarcodeScan = document.getElementById('returnBarcodeScan');
let returnBarcodeManual = document.getElementById('returnBarcodeManual');
let actualReturnBarcode = document.getElementById('actualReturnBarcode');
let returnSubmitBtn = document.getElementById('returnSubmitBtn');
let returnForm = document.getElementById('returnForm');

function submitReturn(barcode) {
    actualReturnBarcode.value = barcode;
    returnSubmitBtn.innerHTML = '<span class="spinner-border spinner-border-sm mr-2"></span>{{ __('Memproses...') }}';
    returnSubmitBtn.disabled = true;
    returnForm.submit();
}

// Scanner Input
let scanTimeout;
returnBarcodeScan.addEventListener('input', function(e) {
    clearTimeout(scanTimeout);
    scanTimeout = setTimeout(() => {
        if(this.value.trim().length > 0) submitReturn(this.value.trim());
    }, 50);
});
returnBarcodeScan.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        clearTimeout(scanTimeout);
        if(this.value.trim().length > 0) submitReturn(this.value.trim());
    }
});

// Manual Input
let manualTimeout;
returnBarcodeManual.addEventListener('input', function(e) {
    clearTimeout(manualTimeout);
    manualTimeout = setTimeout(() => {
        if(this.value.trim().length > 0) submitReturn(this.value.trim());
    }, 1000);
});
returnBarcodeManual.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        clearTimeout(manualTimeout);
        if(this.value.trim().length > 0) submitReturn(this.value.trim());
    }
});
</script>
@endpush

