@extends('layouts.opac')
@section('title', 'Tata Tertib')
@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 md:p-12 mb-16">
        <h1 class="text-3xl font-bold text-slate-800 mb-6 border-b border-slate-100 pb-4">Tata Tertib Perpustakaan</h1>
        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
            @if($page)
                {!! $page->content !!}
            @else
                <ol class="list-decimal pl-5 space-y-3">
                    <li>Pengunjung wajib mengisi buku tamu sebelum memasuki area koleksi.</li>
                    <li>Pengunjung tidak diperkenankan membawa makanan dan minuman ke dalam area baca.</li>
                    <li>Pengunjung wajib menjaga ketenangan dan tidak membuat kegaduhan.</li>
                    <li>Barang bawaan berupa tas, jaket, dan topi harus dititipkan pada loker yang telah disediakan.</li>
                    <li>Peminjaman buku hanya dapat dilakukan oleh anggota perpustakaan yang memiliki kartu anggota aktif.</li>
                    <li>Keterlambatan pengembalian buku akan dikenakan sanksi denda sesuai dengan ketentuan yang berlaku.</li>
                    <li>Buku yang hilang atau rusak saat masa peminjaman harus diganti dengan buku yang sama atau membayar ganti rugi.</li>
                </ol>
                <p class="mt-6 text-sm text-slate-400"><em>(Daftar tata tertib ini dapat disesuaikan melalui Manajemen Halaman.)</em></p>
            @endif
        </div>
    </div>
</div>
@endsection
