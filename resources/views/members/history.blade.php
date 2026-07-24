@extends('layouts.app')

@section('title', 'Riwayat Sirkulasi - ' . $member->name)
@section('page-title', 'Riwayat Sirkulasi')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('members.index') }}">Daftar Anggota</a></li>
<li class="breadcrumb-item"><a href="{{ route('members.show', $member) }}">{{ $member->name }}</a></li>
<li class="breadcrumb-item active">Riwayat Sirkulasi</li>
@endsection

@section('content')
<div class="page-header justify-between items-start flex">
    <div>
        <h1>Riwayat Sirkulasi</h1>
        <p>Seluruh riwayat peminjaman dan pengembalian buku oleh <strong>{{ $member->name }}</strong></p>
    </div>
    <div>
        <a href="{{ route('members.show', $member) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2 px-6">
            <i class="bi bi-arrow-left mr-1"></i>Kembali ke Detail
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-0">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Transaksi</th>
                        <th>Barcode</th>
                        <th>Judul Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($circulations as $c)
                    <tr>
                        <td class="text-slate-500">{{ $circulations->firstItem() + $loop->index }}</td>
                        <td><code class="font-semibold">{{ $c->transaction_code }}</code></td>
                        <td><code>{{ $c->bookItem->barcode }}</code></td>
                        <td class="font-medium">{{ $c->bookItem->book->title }}</td>
                        <td>{{ $c->loan_date->format('d/m/Y') }}</td>
                        <td>{{ $c->due_date->format('d/m/Y') }}</td>
                        <td>{{ $c->return_date ? $c->return_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($c->status === 'Dikembalikan')
                            <span class="inline-flex py-1 text-xs font-medium rounded-md bg-emerald-100 text-emerald-700 px-2">Selesai</span>
                            @elseif($c->is_overdue)
                            <span class="inline-flex py-1 text-xs font-medium rounded-md bg-red-100 text-red-700 px-2">Terlambat</span>
                            @else
                            <span class="inline-flex py-1 text-xs font-medium rounded-md bg-amber-100 text-amber-800 px-2">Dipinjam</span>
                            @endif
                        </td>
                        <td>
                            @if($c->fine_amount > 0)
                            <div class="text-red-600 font-semibold">Rp {{ number_format($c->fine_amount, 0, ',', '.') }}</div>
                            <div class="text-slate-500" style="font-size:0.72rem">{{ $c->fine_paid ? 'Lunas' : 'Belum Lunas' }}</div>
                            @else
                            <span class="text-slate-500">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="py-8 text-center text-slate-500">Belum ada riwayat transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($circulations->hasPages())
    <div class="bg-white border-t border-slate-200 px-8 bg-slate-50 py-2 py-4">
        {{ $circulations->links() }}
    </div>
    @endif
</div>
@endsection
