@extends('layouts.app')

@section('title', __('Reservasi Online'))

@section('content')
<div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ __('Reservasi Online') }}</h1>
        <p class="text-slate-500">{{ __('Kelola antrean pemesanan buku dari katalog OPAC') }}</p>
    </div>
</div>

@if(session('success'))
<div class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif

@if(session('info'))
<div class="bg-blue-100 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
    <i class="bi bi-info-circle-fill"></i> {{ session('info') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 border-b border-slate-200 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold">{{ __('Pemesan') }}</th>
                    <th class="px-6 py-4 font-semibold">{{ __('Buku') }}</th>
                    <th class="px-6 py-4 font-semibold">{{ __('Tgl Pesan') }}</th>
                    <th class="px-6 py-4 font-semibold">{{ __('Batas Ambil') }}</th>
                    <th class="px-6 py-4 font-semibold">{{ __('Status') }}</th>
                    <th class="px-6 py-4 font-semibold text-right">{{ __('Aksi') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($reservations as $res)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800">{{ $res->member->name }}</div>
                        <div class="text-xs text-slate-500">{{ $res->member->member_number }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800 text-sm max-w-xs truncate">{{ $res->book->title }}</div>
                        <div class="text-xs text-slate-500">Stok: {{ $res->book->available_copies }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $res->reserve_date->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-sm {{ Carbon\Carbon::today()->gt($res->expired_date) ? 'text-red-500 font-bold' : '' }}">
                        {{ $res->expired_date ? $res->expired_date->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        @if($res->status == 'Menunggu')
                            <span class="bg-amber-100 text-amber-700 px-2.5 py-1 rounded-md text-xs font-semibold">{{ __('Menunggu') }}</span>
                        @elseif($res->status == 'Siap')
                            <span class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded-md text-xs font-semibold">{{ __('Siap Diambil') }}</span>
                        @elseif($res->status == 'Dibatalkan')
                            <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md text-xs font-semibold">{{ __('Dibatalkan') }}</span>
                        @else
                            <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-md text-xs font-semibold">{{ __('Selesai') }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($res->status == 'Menunggu')
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('reservations.approve', $res->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg text-xs" title="Tandai Siap Diambil">
                                        <i class="bi bi-box-seam"></i> Siapkan Buku
                                    </button>
                                </form>
                                <form action="{{ route('reservations.reject', $res->id) }}" method="POST" onsubmit="return confirm('Tolak pemesanan ini?')">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg text-xs" title="Tolak">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                            </div>
                        @elseif($res->status == 'Siap')
                            <a href="{{ route('circulations.loan', ['member' => $res->member->member_number]) }}" class="inline-block bg-emerald-500 hover:bg-emerald-600 text-white py-1.5 px-3 rounded-lg text-xs font-bold shadow-sm" title="Proses Peminjaman Fisik">
                                <i class="bi bi-arrow-left-right mr-1"></i> Proses Pinjam
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        <i class="bi bi-journal-x text-4xl mb-3 block text-slate-300"></i>
                        Tidak ada antrean reservasi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($reservations->hasPages())
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $reservations->links() }}
    </div>
    @endif
</div>
@endsection
