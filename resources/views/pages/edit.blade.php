@extends('layouts.app')

@section('title', __('Edit Konten: ') . $page->title)
@section('page-title', __('Edit Konten Halaman'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('pages.index') }}">{{ __('Profil & Halaman OPAC') }}</a></li>
<li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
        border-color: #cbd5e1;
        background-color: #f8fafc;
    }
    .ql-container.ql-snow {
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
        border-color: #cbd5e1;
        font-family: inherit;
        font-size: 0.95rem;
    }
    .ql-editor {
        min-height: 280px;
        line-height: 1.7;
    }
</style>
@endpush

@section('content')
<div class="page-header flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 mb-1">{{ __('Edit Konten Halaman') }}</h1>
        <p class="text-slate-500 text-xs sm:text-sm">{{ __('Perbarui informasi dan isi konten untuk:') }} <span class="font-semibold text-slate-700">{{ $page->title }}</span></p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('opac.page', $page->slug) }}" target="_blank" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg border border-indigo-200 text-indigo-700 bg-indigo-50 hover:bg-indigo-100 shadow-sm gap-2 py-2 px-3.5 transition-all" title="{{ __('Buka halaman di website OPAC') }}">
            <i class="bi bi-box-arrow-up-right"></i> {{ __('Lihat di Web') }}
        </a>
        <a href="{{ route('pages.index') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 shadow-sm gap-2 py-2 px-4 transition-all">
            <i class="bi bi-arrow-left"></i> {{ __('Kembali') }}
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden max-w-4xl mb-12">
    {{-- Header Card --}}
    <div class="px-6 sm:px-8 border-b border-slate-200 bg-slate-50 font-semibold text-slate-700 py-4 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <i class="bi bi-pencil-square text-indigo-600 text-lg"></i>
            <span>{{ __('Form Edit Konten') }}</span>
        </div>
        <div>
            @if($isCore)
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                    <i class="bi bi-star-fill text-[9px] text-amber-500"></i> {{ __('Profil Utama OPAC') }}
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-200 text-slate-700">
                    {{ __('Halaman Kustom') }}
                </span>
            @endif
        </div>
    </div>

    <div class="p-6 sm:p-8">
        @if(isset($errors) && $errors->any())
        <div class="bg-red-50 text-red-700 rounded-xl p-4 mb-6 text-sm border border-red-200 flex items-start gap-3">
            <div class="w-6 h-6 rounded-lg bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="bi bi-exclamation-triangle-fill text-xs"></i>
            </div>
            <div>
                <div class="font-bold mb-1">{{ __('Terjadi kesalahan pengisian:') }}</div>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-red-600">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <form action="{{ route('pages.update', $page) }}" method="POST" id="pageForm">
            @csrf
            @method('PUT')

            {{-- Link Preview Box --}}
            <div class="mb-6 p-3.5 bg-slate-50 border border-slate-200 rounded-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2 text-slate-600">
                    <i class="bi bi-link-45deg text-base text-slate-400"></i>
                    <span>{{ __('Tautan Publik (OPAC):') }}</span>
                    <code class="bg-white px-2 py-0.5 rounded border border-slate-200 text-indigo-600 font-mono font-bold">/opac/halaman/{{ $page->slug }}</code>
                </div>
                <span class="text-slate-400 text-[11px]">{{ $isCore ? __('Tautan sistem tetap') : __('Tautan halaman dinamis') }}</span>
            </div>

            {{-- Judul Halaman --}}
            <div class="mb-5">
                <label for="title" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    {{ __('Judul Halaman') }} <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" required class="w-full rounded-lg border border-slate-300 py-2.5 px-3.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors" placeholder="{{ __('Masukkan judul halaman...') }}">
                </div>
                @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            @if(!$isCore)
            {{-- Slug URL (Hanya untuk halaman kustom) --}}
            <div class="mb-5">
                <label for="slug" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    {{ __('Slug URL') }} <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center">
                    <span class="bg-slate-100 border border-slate-300 border-r-0 rounded-l-lg px-3.5 py-2.5 text-xs text-slate-500 font-mono">/opac/halaman/</span>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" required class="w-full rounded-r-lg border border-slate-300 py-2.5 px-3.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors font-mono">
                </div>
                @error('slug') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            @endif

            {{-- Konten Halaman --}}
            <div class="mb-6">
                <div class="flex justify-between items-center mb-1.5">
                    <label class="block text-sm font-semibold text-slate-700">
                        {{ __('Konten / Isi Halaman') }}
                    </label>
                    <span class="text-xs text-slate-400">{{ __('Mendukung teks tebal, daftar poin, tautan, & tabel') }}</span>
                </div>
                <div class="rounded-lg overflow-hidden border border-slate-300">
                    <div id="editor-container"></div>
                </div>
                <input type="hidden" id="content" name="content" value="{{ old('content', $page->content) }}">
                @error('content') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Status Aktif --}}
            <div class="mb-6 p-4 rounded-xl border border-slate-200 bg-slate-50/50">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500 cursor-pointer" {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                    <div>
                        <span class="text-sm font-semibold text-slate-800 block">{{ __('Tampilkan Halaman Ini di Website (Aktif)') }}</span>
                        <span class="text-xs text-slate-500 block mt-0.5">{{ __('Jika dinonaktifkan, halaman ini akan disembunyikan dari menu pengunjung.') }}</span>
                    </div>
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end items-center gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('pages.index') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 shadow-sm py-2.5 px-5 transition-all">
                    {{ __('Batal') }}
                </a>
                <button type="submit" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg btn-gradient-blue shadow-md shadow-indigo-500/20 text-white py-2.5 px-6 transition-all transform hover:-translate-y-0.5 gap-2">
                    <i class="bi bi-save text-base"></i>
                    <span>{{ __('Simpan Perubahan') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Tuliskan informasi atau isi halaman di sini...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });

        // Set initial content
        var oldContent = document.getElementById('content').value;
        if(oldContent) {
            quill.root.innerHTML = oldContent;
        }

        // On submit, sync to hidden input
        var form = document.getElementById('pageForm');
        form.onsubmit = function() {
            var html = quill.root.innerHTML;
            // If quill is empty (just paragraph break), set empty
            if (html === '<p><br></p>') {
                html = '';
            }
            document.getElementById('content').value = html;
        };
    });
</script>
@endpush
@endsection
