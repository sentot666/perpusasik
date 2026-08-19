@extends('layouts.opac')
@section('title', 'Pustakawan')
@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 md:p-12 mb-16">
        <h1 class="text-3xl font-bold text-slate-800 mb-6 border-b border-slate-100 pb-4">Daftar Pustakawan</h1>
        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
            @if($page)
                {!! $page->content !!}
            @else
                <p class="mb-8">Mengenal lebih dekat para pustakawan dan staf yang berdedikasi melayani Anda di perpustakaan.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Example Profil -->
                    <div class="flex items-center gap-4 p-4 border border-slate-100 rounded-xl hover:shadow-md transition-shadow">
                        <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                            <i class="bi bi-person-circle text-3xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 m-0">Nama Pustakawan</h4>
                            <p class="text-sm text-slate-500 m-0">Kepala Perpustakaan</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4 p-4 border border-slate-100 rounded-xl hover:shadow-md transition-shadow">
                        <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                            <i class="bi bi-person-circle text-3xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 m-0">Nama Staf</h4>
                            <p class="text-sm text-slate-500 m-0">Staf Administrasi</p>
                        </div>
                    </div>
                </div>
                
                <p class="mt-8 text-sm text-slate-400"><em>(Data pustakawan dapat diperbarui melalui Manajemen Halaman.)</em></p>
            @endif
        </div>
    </div>
</div>
@endsection
