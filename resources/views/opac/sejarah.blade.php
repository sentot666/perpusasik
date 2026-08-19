@extends('layouts.opac')
@section('title', 'Sejarah Perpustakaan')
@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 md:p-12 mb-16">
        <h1 class="text-3xl font-bold text-slate-800 mb-6 border-b border-slate-100 pb-4">Sejarah Perpustakaan</h1>
        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
            @if($page)
                {!! $page->content !!}
            @else
                <p>Sejarah perpustakaan kami dimulai sejak sekolah ini didirikan. Kami berdedikasi untuk menyediakan berbagai sumber ilmu pengetahuan yang mendukung proses belajar mengajar.</p>
                <p><em>(Konten ini dapat disesuaikan lebih lanjut oleh administrator melalui Manajemen Halaman.)</em></p>
            @endif
        </div>
    </div>
</div>
@endsection
