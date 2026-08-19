@extends('layouts.opac')
@section('title', 'Struktur Organisasi')
@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 md:p-12 mb-16">
        <h1 class="text-3xl font-bold text-slate-800 mb-6 border-b border-slate-100 pb-4">Struktur Organisasi</h1>
        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed text-center">
            @if($page)
                {!! $page->content !!}
            @else
                <p class="mb-6">Struktur organisasi perpustakaan dirancang untuk memaksimalkan pelayanan kepada pengunjung dan anggota perpustakaan.</p>
                <div class="p-8 bg-slate-50 border border-slate-100 rounded-xl">
                    <i class="bi bi-diagram-3 text-6xl text-slate-300"></i>
                    <p class="mt-4 text-slate-500">Bagan Struktur Organisasi akan ditampilkan di sini.</p>
                </div>
                <p class="mt-6 text-sm text-slate-400 text-left"><em>(Silakan tambahkan gambar atau bagan struktur organisasi Anda melalui Manajemen Halaman.)</em></p>
            @endif
        </div>
    </div>
</div>
@endsection
