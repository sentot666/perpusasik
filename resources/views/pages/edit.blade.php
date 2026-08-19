@extends('layouts.app')

@section('title', 'Edit Halaman: ' . $page->title)

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('pages.index') }}" class="text-slate-500 hover:text-indigo-600 p-2 rounded-lg hover:bg-indigo-50 transition-colors">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Halaman</h1>
            <p class="text-slate-500">{{ $page->title }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form action="{{ route('pages.update', $page) }}" method="POST" id="pageForm">
            @csrf
            @method('PUT')
            
            <div class="mb-5">
                <label for="title" class="block font-semibold text-slate-700 mb-2">Judul Halaman <span class="text-rose-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                @error('title') <span class="text-rose-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-5">
                <label for="slug" class="block font-semibold text-slate-700 mb-2">Slug URL <span class="text-rose-500">*</span></label>
                <div class="flex items-center">
                    <span class="bg-slate-100 border border-slate-300 border-r-0 rounded-l-lg px-4 py-2 text-slate-500">/opac/</span>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" required class="w-full px-4 py-2 border border-slate-300 rounded-r-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-slate-50" readonly>
                </div>
                <p class="text-xs text-slate-500 mt-1">Slug URL tidak disarankan untuk diubah untuk menghindari link error (404).</p>
                @error('slug') <span class="text-rose-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="block font-semibold text-slate-700 mb-2">Konten HTML <span class="text-rose-500">*</span></label>
                <div class="border border-slate-300 rounded-lg overflow-hidden">
                    <div id="editor-container" class="h-96"></div>
                </div>
                <input type="hidden" id="content" name="content" value="{{ old('content', $page->content) }}">
                @error('content') <span class="text-rose-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500" {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                    <span class="font-medium text-slate-700">Aktif (Tampilkan Halaman Ini)</span>
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('pages.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2 px-6 rounded-lg transition-colors">Batal</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors">
                    Simpan Perubahan
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
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });

        // Set initial content if old('content') exists
        var oldContent = document.getElementById('content').value;
        if(oldContent) {
            quill.root.innerHTML = oldContent;
        }

        // On form submit, copy Quill HTML to hidden input
        var form = document.getElementById('pageForm');
        form.onsubmit = function() {
            var html = quill.root.innerHTML;
            document.getElementById('content').value = html;
        };
    });
</script>
@endpush
@endsection
