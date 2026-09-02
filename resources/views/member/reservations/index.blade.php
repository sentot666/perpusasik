@extends('layouts.opac')

@section('title', 'Buku Saya - Reservasi')

@section('content')
<div class="bg-blue-50 min-h-screen py-10">
    <div class="max-w-5xl mx-auto px-6">
        
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Buku Saya (Reservasi)</h1>
            <p class="text-slate-500 mt-2">Daftar buku yang sedang Anda pesan atau pinjam secara online.</p>
        </div>



        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            @if($reservations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Buku</th>
                                <th class="px-6 py-4">Tanggal Pesan</th>
                                <th class="px-6 py-4">Batas Ambil</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($reservations as $res)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-16 bg-slate-200 rounded flex-shrink-0 overflow-hidden">
                                                @if($res->book->cover_image)
                                                    <img src="{{ asset('storage/' . $res->book->cover_image) }}" class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 text-base mb-1">{{ $res->book->title }}</div>
                                                <div class="text-xs text-slate-500">Oleh: {{ $res->book->authors->pluck('name')->join(', ') ?: '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-medium">{{ $res->reserve_date->format('d M Y') }}</td>
                                    <td class="px-6 py-4 font-medium">{{ $res->expired_date ? $res->expired_date->format('d M Y') : '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if($res->status == 'Menunggu')
                                            <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold">Menunggu</span>
                                        @elseif($res->status == 'Siap')
                                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">Siap Diambil</span>
                                        @elseif($res->status == 'Dibatalkan')
                                            <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-bold">Dibatalkan</span>
                                        @else
                                            <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($res->status == 'Menunggu' || $res->status == 'Siap')
                                            <form action="{{ route('member.reservations.destroy', $res->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                                    Batalkan
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-16 px-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4 text-3xl">
                        <i class="bi bi-journal-x"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Reservasi</h3>
                    <p class="text-slate-500 mb-6">Anda belum memesan atau meminjam buku secara online.</p>
                    <a href="{{ route('opac.katalog') }}" class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-xl transition-colors">
                        Mulai Cari Buku
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
