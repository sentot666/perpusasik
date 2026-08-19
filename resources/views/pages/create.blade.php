@extends('layouts.app')

@section('title', 'Buat Halaman Baru')

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
            <h1 class="text-2xl font-bold text-slate-800">Buat Halaman Baru</h1>
            <p class="text-slate-500">Tambahkan konten untuk OPAC.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form action="{{ route('pages.store') }}" method="POST" id="pageForm">
            @csrf
            
            <div class="mb-5">
                <label for="title" class="block font-semibold text-slate-700 mb-2">Judul Halaman <span class="text-rose-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                @error('title') <span class="text-rose-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-5">
                <label for="slug" class="block font-semibold text-slate-700 mb-2">Slug URL <span class="text-rose-500">*</span></label>
                <div class="flex items-center">
                    <span class="bg-slate-100 border border-slate-300 border-r-0 rounded-l-lg px-4 py-2 text-slate-500">/opac/</span>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}" required placeholder="contoh: sejarah" class="w-full px-4 py-2 border border-slate-300 rounded-r-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                </div>
                <p class="text-xs text-slate-500 mt-1">Gunakan huruf kecil dan strip (-). Pastikan cocok dengan route/link yang ada di sistem (misal: visi-misi, pustakawan, sejarah).</p>
                @error('slug') <span class="text-rose-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="block font-semibold text-slate-700 mb-2">Konten HTML <span class="text-rose-500">*</span></label>
                <div class="border border-slate-300 rounded-lg overflow-hidden">
                    <div id="editor-container" class="h-96"></div>
                </div>
                <input type="hidden" id="content" name="content" value="{{ old('content') }}">
                @error('content') <span class="text-rose-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500" {{ old('is_active', true) ? 'checked' : '' }}>
                    <span class="font-medium text-slate-700">Aktif (Tampilkan Halaman Ini)</span>
                </label>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors">
                    Simpan Halaman
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

        // Auto generate slug from title
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        
        titleInput.addEventListener('keyup', function() {
            if(!slugInput.value || slugInput.dataset.auto == "true") {
                slugInput.dataset.auto = "true";
                let text = this.value;
                let slug = text.toLowerCase().replace(/[^\w\s-]/g, '').replace(/[\s_-]+/g, '-').replace(/^-+|-+$/g, '');
                slugInput.value = slug;
            }
        });
        
        slugInput.addEventListener('change', function() {
            this.dataset.auto = "false";
        });
    });
</script>
@endpush
@endsection
