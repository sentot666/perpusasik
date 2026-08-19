@extends('layouts.opac')
@section('title', 'Jam Layanan')
@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 md:p-12 mb-16">
        <h1 class="text-3xl font-bold text-slate-800 mb-6 border-b border-slate-100 pb-4">Jam Layanan Operasional</h1>
        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
            @if($page)
                {!! $page->content !!}
            @else
                <p class="mb-6">Perpustakaan kami buka pada jadwal berikut untuk melayani seluruh sivitas akademika:</p>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-y border-slate-200">
                                <th class="py-3 px-4 font-bold text-slate-700">Hari</th>
                                <th class="py-3 px-4 font-bold text-slate-700">Jam Buka</th>
                                <th class="py-3 px-4 font-bold text-slate-700">Istirahat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="py-3 px-4">Senin - Kamis</td>
                                <td class="py-3 px-4 text-indigo-600 font-semibold">07:00 - 15:00 WIB</td>
                                <td class="py-3 px-4 text-slate-500">12:00 - 12:30 WIB</td>
                            </tr>
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="py-3 px-4">Jumat</td>
                                <td class="py-3 px-4 text-indigo-600 font-semibold">07:00 - 14:00 WIB</td>
                                <td class="py-3 px-4 text-slate-500">11:30 - 13:00 WIB</td>
                            </tr>
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="py-3 px-4">Sabtu & Minggu</td>
                                <td class="py-3 px-4 text-rose-500 font-semibold">Tutup / Libur</td>
                                <td class="py-3 px-4 text-slate-500">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <p class="mt-8 text-sm text-slate-500"><strong>Catatan:</strong> Jam layanan dapat berubah sewaktu-waktu. Anda dapat mengedit konten ini di Manajemen Halaman.</p>
            @endif
        </div>
    </div>
</div>
@endsection
