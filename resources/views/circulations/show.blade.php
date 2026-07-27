@extends('layouts.app')

@section('title', __('Detail Sirkulasi') . ': ' . $circulation->transaction_code)
@section('page-title', __('Detail Sirkulasi'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('circulations.index') }}">{{ __('Sirkulasi') }}</a></li>
<li class="breadcrumb-item active">{{ $circulation->transaction_code }}</li>
@endsection

@section('content')
<div class="page-header justify-between items-start flex">
    <div>
        <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ __('Detail Transaksi Sirkulasi') }}</h1>
        <p>{{ __('Detail peminjaman, pengembalian dan status denda keterlambatan') }}</p>
    </div>
    <div>
        <a href="{{ route('circulations.index') }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2 px-6">
            <i class="bi bi-arrow-left mr-1"></i>{{ __('Kembali') }}
        </a>
    </div>
</div>

<div class="flex flex-wrap -mx-3">
    {{-- Left side: Transaction Info --}}
    <div class="w-full lg:w-1/2 px-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-info-circle-fill mr-2"></i>{{ __('Informasi Transaksi') }}</div>
            <div class="p-8">
                <table class="border-0 text-sm w-full text-left text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 mb-0" style="font-size:0.85rem">
                    <tr>
                        <td style="width:150px" class="text-slate-500">{{ __('KODE TRANSAKSI') }}</td>
                        <td>: <code class="text-base font-semibold">{{ $circulation->transaction_code }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-slate-500">{{ __('TANGGAL PINJAM') }}</td>
                        <td>: {{ $circulation->loan_date->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-slate-500">{{ __('JATUH TEMPO') }}</td>
                        <td>: {{ $circulation->due_date->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-slate-500">{{ __('TANGGAL KEMBALI') }}</td>
                        <td>: {{ $circulation->return_date ? $circulation->return_date->format('d F Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-slate-500">{{ __('JUMLAH PERPANJANGAN') }}</td>
                        <td>: {{ $circulation->renewal_count }}x {{ __('perpanjangan') }}</td>
                    </tr>
                    <tr>
                        <td class="text-slate-500">{{ __('STATUS') }}</td>
                        <td>:
                            @if($circulation->status === 'Dikembalikan')
                            <span class="inline-flex py-1 text-xs font-medium rounded-md bg-emerald-100 text-emerald-700 px-2">{{ __('Dikembalikan') }}</span>
                            @elseif($circulation->is_overdue)
                            <span class="inline-flex py-1 text-xs font-medium rounded-md bg-red-100 text-red-700 px-2">{{ __('Terlambat') }} {{ $circulation->days_overdue }} {{ __('hari') }}</span>
                            @else
                            <span class="inline-flex py-1 text-xs font-medium rounded-md bg-amber-100 text-amber-800 px-2">{{ __('Dipinjam') }} ({{ __('Aktif') }})</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-slate-500">{{ __('PETUGAS MELAYANI') }}</td>
                        <td>: {{ $circulation->user?->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Fine management card --}}
        @if($circulation->fine_amount > 0 || $circulation->is_overdue)
        <div class="border-danger bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden" style="border-color:#fee2e2!important">
            <div class="btn-gradient-red text-white px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-cash mr-2"></i>{{ __('Informasi Denda') }}</div>
            <div class="p-8">
                @php
                $dueFine = $circulation->fine_amount > 0 ? $circulation->fine_amount : $circulation->calculated_fine;
                @endphp
                <div class="text-center py-2">
                    <div class="text-slate-500" style="font-size:0.75rem">{{ __('JUMLAH DENDA') }}</div>
                    <div class="text-4xl font-bold fw-800 text-red-600 mb-2">Rp {{ number_format($dueFine, 0, ',', '.') }}</div>

                    @if($circulation->fine_paid)
                        <div class="p-2 text-base w-full inline-flex py-1 text-xs font-medium rounded-md bg-emerald-100 text-emerald-700 px-2">
                            <i class="bi bi-check-circle-fill mr-1"></i>{{ __('LUNAS') }} ({{ __('Dibayar pada') }} {{ $circulation->fine_paid_at ? $circulation->fine_paid_at->format('d/m/Y H:i') : '' }})
                        </div>
                    @else
                        <div class="p-2 text-base w-full inline-flex py-1 text-xs font-medium rounded-md bg-amber-100 text-amber-800 mb-6 px-2">
                            <i class="bi bi-exclamation-triangle-fill mr-1"></i>{{ __('BELUM DIBAYAR') }}
                        </div>
                        <form method="POST" action="{{ route('circulations.pay-fine', $circulation) }}">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-green transition-colors text-white font-semibold gap-2 py-2 px-6">
                                <i class="bi bi-cash mr-2"></i>{{ __('Bayar Lunas Sekarang') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Right side: Member & Book details --}}
    <div class="w-full lg:w-1/2 px-4">
        {{-- Member detail card --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-person-fill mr-2"></i>{{ __('Detail Anggota') }}</div>
            <div class="p-8">
                <div class="items-center flex gap-6">
                    <div style="width:50px;height:50px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;color:#64748b;font-weight:bold">
                        {{ strtoupper(substr($circulation->member->name, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="fw-700 mb-0"><a href="{{ route('members.show', $circulation->member) }}" class="no-underline text-slate-800">{{ $circulation->member->name }}</a></h5>
                        <div class="text-slate-500" style="font-size:0.8rem">{{ __('No. Anggota') }}: <code>{{ $circulation->member->member_code }}</code></div>
                        <div class="text-slate-500" style="font-size:0.8rem">{{ __('Tipe Anggota') }}: {{ $circulation->member->member_type }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Book detail card --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-book-fill mr-2"></i>{{ __('Detail Buku & Eksemplar') }}</div>
            <div class="p-8">
                <h5 class="fw-700"><a href="{{ route('books.show', $circulation->bookItem->book) }}" class="no-underline text-slate-800">{{ $circulation->bookItem->book->title }}</a></h5>
                <p class="text-slate-500 mb-2" style="font-size:0.82rem">{{ __('Pengarang') }}: {{ $circulation->bookItem->book->main_author ?? '-' }}</p>
                <hr>
                <div class="flex flex-wrap -mx-2">
                    <div class="w-1/2 px-4">
                        <div class="text-slate-500" style="font-size:0.72rem">{{ __('BARCODE EKSEMPLAR') }}</div>
                        <code class="text-base font-semibold">{{ $circulation->bookItem->barcode }}</code>
                    </div>
                    <div class="w-1/2 px-4">
                        <div class="text-slate-500" style="font-size:0.72rem">{{ __('NOMOR INDUK') }}</div>
                        <div class="font-medium">{{ $circulation->bookItem->accession_number }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions panel --}}
        @if($circulation->status === 'Dipinjam')
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-gear-fill mr-2"></i>{{ __('Tindakan Sirkulasi') }}</div>
            <div class="p-8">
                <div class="grid gap-2">
                    @if($circulation->renewal_count < (int) \App\Models\Setting::get('max_renewals', 2))
                    <form method="POST" action="{{ route('circulations.renew', $circulation) }}">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2 px-6">
                            <i class="bi bi-arrow-repeat mr-2"></i>{{ __('Perpanjang Masa Pinjam') }} (+{{ \App\Models\Setting::get('loan_duration', 14) }} {{ __('hari') }})
                        </button>
                    </form>
                    @else
                    <button class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2 px-6" disabled>
                        <i class="bi bi-arrow-repeat mr-2"></i>{{ __('Batas Perpanjangan Tercapai') }}
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection



