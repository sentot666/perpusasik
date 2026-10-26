@extends('layouts.app')

@section('title', __('Profil & Halaman OPAC'))
@section('page-title', __('Profil & Halaman OPAC'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item active">{{ __('Profil & Halaman OPAC') }}</li>
@endsection

@section('content')
{{-- Flash Alerts --}}
@if (session('success'))
<div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl flex items-center justify-between" role="alert">
    <div class="flex items-center gap-2.5">
        <i class="bi bi-check-circle-fill text-emerald-600"></i>
        <span>{{ session('success') }}</span>
    </div>
    <button type="button" onclick="this.closest('[role=alert]').remove()" class="text-emerald-500 hover:text-emerald-700">
        <i class="bi bi-x-lg text-xs"></i>
    </button>
</div>
@endif

@if (session('error'))
<div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-800 text-sm rounded-xl flex items-center justify-between" role="alert">
    <div class="flex items-center gap-2.5">
        <i class="bi bi-exclamation-triangle-fill text-rose-600"></i>
        <span>{{ session('error') }}</span>
    </div>
    <button type="button" onclick="this.closest('[role=alert]').remove()" class="text-rose-500 hover:text-rose-700">
        <i class="bi bi-x-lg text-xs"></i>
    </button>
</div>
@endif

{{-- Page Header --}}
<div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 mb-1">{{ __('Profil & Halaman OPAC') }}</h1>
        <p class="text-slate-500 text-xs sm:text-sm">{{ __('Kelola informasi profil, visi-misi, tata tertib, dan halaman yang tampil di website pengunjung (OPAC)') }}</p>
    </div>
    <div>
        <a href="{{ route('pages.create') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg btn-gradient-blue shadow-sm text-white gap-2 py-2 px-4 transition-all">
            <i class="bi bi-plus-lg"></i> {{ __('Tambah Halaman Kustom') }}
        </a>
    </div>
</div>

{{-- Info Banner --}}
<div class="bg-indigo-50/70 border border-indigo-100 rounded-xl p-4 mb-5 flex items-start gap-3">
    <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0 mt-0.5">
        <i class="bi bi-info-circle-fill text-sm"></i>
    </div>
    <div class="text-xs sm:text-sm text-slate-600 leading-relaxed">
        <span class="font-bold text-slate-800">{{ __('Halaman Profil Resmi OPAC') }}:</span>
        {{ __('Semua halaman di bawah ini terhubung langsung ke menu') }} <strong>"Tentang Kami"</strong> {{ __('di website pengunjung. Klik') }} 
        <span class="inline-flex items-center gap-1 font-semibold text-indigo-600 bg-indigo-100/70 px-1.5 py-0.5 rounded text-xs"><i class="bi bi-pencil"></i> {{ __('Edit') }}</span> 
        {{ __('untuk mengubah isi teks, atau klik') }} 
        <span class="inline-flex items-center gap-1 font-semibold text-slate-700 bg-slate-200/80 px-1.5 py-0.5 rounded text-xs"><i class="bi bi-box-arrow-up-right"></i> {{ __('Lihat di Web') }}</span> 
        {{ __('untuk melihat tampilan aslinya.') }}
    </div>
</div>

{{-- Main Table Card --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-0">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3.5 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50/70 transition-colors">
                <thead>
                    <tr>
                        <th style="width: 45px;">#</th>
                        <th>{{ __('Halaman / Konten') }}</th>
                        <th style="width: 140px;">{{ __('Kategori') }}</th>
                        <th style="width: 110px;">{{ __('Status') }}</th>
                        <th style="width: 140px;">{{ __('Tautan Web (OPAC)') }}</th>
                        <th class="text-right" style="width: 100px;">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                    @php 
                        $isCore = in_array($page->slug, $coreSlugs ?? []); 
                    @endphp
                    <tr>
                        <td class="text-slate-400 text-xs font-mono">
                            {{ $pages->firstItem() + $loop->index }}
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 {{ $isCore ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-100 text-slate-600' }}">
                                    @if($page->slug === 'visi-misi')
                                        <i class="bi bi-compass text-base"></i>
                                    @elseif($page->slug === 'sejarah')
                                        <i class="bi bi-clock-history text-base"></i>
                                    @elseif($page->slug === 'struktur-organisasi')
                                        <i class="bi bi-diagram-3 text-base"></i>
                                    @elseif($page->slug === 'jam-layanan')
                                        <i class="bi bi-calendar2-range text-base"></i>
                                    @elseif($page->slug === 'tata-tertib')
                                        <i class="bi bi-shield-check text-base"></i>
                                    @elseif($page->slug === 'pustakawan')
                                        <i class="bi bi-people text-base"></i>
                                    @elseif($page->slug === 'program-kerja')
                                        <i class="bi bi-journal-check text-base"></i>
                                    @else
                                        <i class="bi bi-file-earmark-text text-base"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800 text-sm hover:text-indigo-600 transition-colors">
                                        <a href="{{ route('pages.edit', $page) }}">{{ $page->title }}</a>
                                    </div>
                                    <span class="text-xs text-slate-400 font-mono">/opac/halaman/{{ $page->slug }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($isCore)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    <i class="bi bi-star-fill text-[9px] text-amber-500"></i> {{ __('Profil Utama') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ __('Halaman Kustom') }}
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($page->is_active)
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ __('Aktif') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> {{ __('Nonaktif') }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('opac.page', $page->slug) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-medium text-indigo-600 hover:text-indigo-800 bg-indigo-50/60 hover:bg-indigo-100/70 border border-indigo-100 px-2.5 py-1 rounded-lg transition-colors" title="{{ __('Buka di tab baru') }}">
                                <i class="bi bi-box-arrow-up-right text-[11px]"></i>
                                <span>{{ __('Lihat di Web') }}</span>
                            </a>
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="inline-flex items-center justify-end gap-1.5">
                                <a href="{{ route('pages.edit', $page) }}" class="inline-flex items-center gap-1 py-1.5 px-2.5 text-xs font-semibold rounded-lg text-indigo-600 hover:bg-indigo-50 transition-colors" title="{{ __('Edit Konten') }}">
                                    <i class="bi bi-pencil"></i> {{ __('Edit') }}
                                </a>

                                @if(!$isCore)
                                <form action="{{ route('pages.destroy', $page) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus halaman {{ $page->title }} secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="{{ __('Hapus') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <i class="bi bi-file-earmark-x text-2xl block mb-2 opacity-40"></i>
                            <div class="text-sm font-medium text-slate-600">{{ __('Belum ada halaman') }}</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($pages->hasPages())
        <div class="p-3.5 border-t border-slate-200">
            {{ $pages->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
