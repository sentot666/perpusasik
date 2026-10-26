@extends('layouts.app')

@section('title', __('Tambah Halaman Kustom'))
@section('page-title', __('Tambah Halaman Kustom'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('pages.index') }}">{{ __('Profil & Halaman OPAC') }}</a></li>
<li class="breadcrumb-item active">{{ __('Tambah') }}</li>
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
        <h1 class="text-2xl font-bold text-slate-800 mb-1">{{ __('Tambah Halaman Kustom') }}</h1>
        <p class="text-slate-500 text-xs sm:text-sm">{{ __('Tambahkan halaman informasi tambahan untuk website perpustakaan') }}</p>
    </div>
    <div>
        <a href="{{ route('pages.index') }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-semibold rounded-lg border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 shadow-sm gap-2 py-2 px-4 transition-all">
            <i class="bi bi-arrow-left"></i> {{ __('Kembali') }}
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden max-w-4xl mb-12">
    <div class="px-6 sm:px-8 border-b border-slate-200 bg-slate-50 font-semibold text-slate-700 py-4 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <i class="bi bi-file-earmark-plus text-indigo-600 text-lg"></i>
            <span>{{ __('Form Halaman Baru') }}</span>
        </div>
        <span class="text-xs font-normal text-slate-500"><span class="text-red-500 font-bold">*</span> Wajib diisi</span>
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

        <form action="{{ route('pages.store') }}" method="POST" id="pageForm">
            @csrf

            {{-- Judul Halaman --}}
            <div class="mb-5">
                <label for="title" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    {{ __('Judul Halaman') }} <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full rounded-lg border border-slate-300 py-2.5 px-3.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors" placeholder="{{ __('Contoh: Fasilitas & Ruang Baca') }}">
                </div>
                @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Slug URL (Otomatis dari Judul) --}}
            <div class="mb-5">
                <label for="slug" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    {{ __('Tautan / Slug URL') }} <span class="text-slate-400 font-normal">({{ __('Dibuat otomatis dari judul') }})</span>
                </label>
                <div class="flex items-center">
                    <span class="bg-slate-100 border border-slate-300 border-r-0 rounded-l-lg px-3.5 py-2.5 text-xs text-slate-500 font-mono">/opac/halaman/</span>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}" class="w-full rounded-r-lg border border-slate-300 py-2.5 px-3.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors font-mono" placeholder="{{ __('fasilitas-ruang-baca') }}">
                </div>
                <p class="text-[11px] text-slate-500 mt-1 flex items-center gap-1">
                    <i class="bi bi-info-circle text-indigo-500"></i>
                    <span>{{ __('Bisa dikosongkan, sistem akan otomatis menyesuaikan dari judul halaman.') }}</span>
                </p>
                @error('slug') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

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
                <input type="hidden" id="content" name="content" value="{{ old('content') }}">
                @error('content') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Status Aktif --}}
            <div class="mb-6 p-4 rounded-xl border border-slate-200 bg-slate-50/50">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500 cursor-pointer" {{ old('is_active', true) ? 'checked' : '' }}>
                    <div>
                        <span class="text-sm font-semibold text-slate-800 block">{{ __('Tampilkan Halaman Ini di Website (Aktif)') }}</span>
                        <span class="text-xs text-slate-500 block mt-0.5">{{ __('Halaman aktif akan langsung dapat diakses oleh pengunjung.') }}</span>
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
                    <span>{{ __('Simpan Halaman') }}</span>
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

        // Set initial content if old exists
        var oldContent = document.getElementById('content').value;
        if(oldContent) {
            quill.root.innerHTML = oldContent;
        }

        // Auto slug from title
        var titleInput = document.getElementById('title');
        var slugInput = document.getElementById('slug');
        var isSlugManuallyChanged = false;

        slugInput.addEventListener('input', function() {
            isSlugManuallyChanged = (slugInput.value.trim() !== '');
        });

        titleInput.addEventListener('input', function() {
            if (!isSlugManuallyChanged) {
                slugInput.value = titleInput.value
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }
        });

        // On submit, sync to hidden input
        var form = document.getElementById('pageForm');
        form.onsubmit = function() {
            var html = quill.root.innerHTML;
            if (html === '<p><br></p>') {
                html = '';
            }
            document.getElementById('content').value = html;
        };
    });
</script>
@endpush
@endsection
