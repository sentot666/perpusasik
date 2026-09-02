@extends('layouts.app')

@section('title', 'Manajemen Halaman')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Halaman</h1>
            <p class="text-slate-500">Kelola halaman dinamis OPAC seperti Sejarah, Visi Misi, dll.</p>
        </div>
        <a href="{{ route('pages.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class="bi bi-plus-lg"></i> Halaman Baru
        </a>
    </div>



    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="py-3 px-6 font-semibold text-sm text-slate-600">Judul Halaman</th>
                        <th class="py-3 px-6 font-semibold text-sm text-slate-600">Slug URL</th>
                        <th class="py-3 px-6 font-semibold text-sm text-slate-600">Status</th>
                        <th class="py-3 px-6 font-semibold text-sm text-slate-600 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pages as $page)
                    <tr class="hover:bg-slate-50">
                        <td class="py-4 px-6">
                            <span class="font-medium text-slate-800">{{ $page->title }}</span>
                        </td>
                        <td class="py-4 px-6 text-slate-500 text-sm">
                            {{ $page->slug }}
                        </td>
                        <td class="py-4 px-6">
                            @if($page->is_active)
                                <span class="bg-emerald-100 text-emerald-700 text-xs px-2 py-1 rounded-full font-medium">Aktif</span>
                            @else
                                <span class="bg-slate-100 text-slate-600 text-xs px-2 py-1 rounded-full font-medium">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('pages.edit', $page) }}" class="text-indigo-600 hover:bg-indigo-50 p-2 rounded-lg transition-colors" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('pages.destroy', $page) }}" method="POST" onsubmit="return confirm('Hapus halaman ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:bg-rose-50 p-2 rounded-lg transition-colors" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-slate-500">
                            Belum ada halaman yang ditambahkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pages->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $pages->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
