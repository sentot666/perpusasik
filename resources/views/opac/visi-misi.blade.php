@extends('layouts.opac')
@section('title', 'Visi dan Misi')
@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 md:p-12 mb-16">
        <h1 class="text-3xl font-bold text-slate-800 mb-6 border-b border-slate-100 pb-4">Visi dan Misi</h1>
        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
            @if($page)
                {!! $page->content !!}
            @else
                <h3 class="text-xl font-bold text-slate-800 mb-2">Visi</h3>
                <p class="mb-6">Menjadi pusat informasi dan ilmu pengetahuan yang unggul dalam mendukung pendidikan, penelitian, dan pengabdian masyarakat.</p>
                
                <h3 class="text-xl font-bold text-slate-800 mb-2">Misi</h3>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Menyediakan koleksi bahan pustaka yang relevan dan mutakhir.</li>
                    <li>Memberikan layanan prima kepada seluruh sivitas akademika.</li>
                    <li>Mengembangkan sistem informasi perpustakaan yang inovatif dan terintegrasi.</li>
                </ul>
                <p class="mt-6 text-sm text-slate-400"><em>(Konten ini dapat disesuaikan lebih lanjut oleh administrator melalui Manajemen Halaman.)</em></p>
            @endif
        </div>
    </div>
</div>
@endsection
