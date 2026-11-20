@extends('layouts.app')

@section('title', __('Edit Buku') . ': ' . $book->title)
@section('page-title', __('Edit Buku'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('books.index') }}">{{ __('Master Buku') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('books.show', $book) }}">{{ Str::limit($book->title, 20) }}</a></li>
<li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script>
    if (window.pdfjsLib) {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
    }
</script>

<script>
function handleEditDigitalPdf(file) {
    if (!file) return;

    const fileInfoBox = document.getElementById('editDigitalFileInfo');
    const fileNameEl = document.getElementById('editDigitalFileName');
    const fileSizeEl = document.getElementById('editDigitalFileSize');
    const pagesInput = document.getElementById('editDigitalPagesInput');
    const coverImageDataInput = document.getElementById('editDigitalCoverImageData');
    const coverPreviewImg = document.getElementById('editDigitalCoverPreviewImg');
    const coverStatus = document.getElementById('editDigitalCoverStatus');

    fileInfoBox.classList.remove('hidden');
    fileNameEl.textContent = file.name;
    fileSizeEl.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB (File Baru)';

    if (!file.name.toLowerCase().endsWith('.pdf')) return;

    coverStatus.innerHTML = '<span class="inline-flex items-center gap-1.5 text-indigo-600 font-medium text-xs"><svg class="animate-spin h-3 w-3 text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Memperbarui cover dari PDF baru...</span>';

    const fileReader = new FileReader();
    fileReader.onload = function() {
        const typedarray = new Uint8Array(this.result);
        pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {
            if (pagesInput) pagesInput.value = pdf.numPages;

            pdf.getPage(1).then(function(page) {
                const viewport = page.getViewport({ scale: 1.5 });
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                page.render({ canvasContext: context, viewport: viewport }).promise.then(function() {
                    const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
                    coverImageDataInput.value = dataUrl;
                    coverPreviewImg.src = dataUrl;
                    coverStatus.innerHTML = '<span class="text-xs text-emerald-600 font-semibold flex items-center gap-1"><i class="bi bi-check-circle-fill"></i> Cover baru & ' + pdf.numPages + ' halaman terdeteksi!</span>';
                });
            });
        });
    };
    fileReader.readAsArrayBuffer(file);
}
</script>
@endpush

@section('content')
<div class="page-header mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-1">
                {{ __('Edit Data Buku') }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-500">
                @if($book->collection_type === 'E-book' || $book->isDigital())
                    {{ __('Perbarui file dan data buku digital') }} : <strong class="text-slate-700">{{ $book->title }}</strong>
                @else
                    {{ __('Perbarui data katalog bibliografi buku') }} : <strong class="text-slate-700">{{ $book->title }}</strong>
                @endif
            </p>
        </div>
        <a href="{{ route('books.show', $book) }}" class="inline-flex items-center justify-center text-xs sm:text-sm font-medium rounded-lg text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 transition-colors gap-1.5 py-2 px-4 shadow-sm">
            <i class="bi bi-arrow-left"></i>{{ __('Kembali ke Detail') }}
        </a>
    </div>
</div>

@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl mb-6 shadow-sm">
    <div class="font-bold flex items-center gap-2 mb-1">
        <i class="bi bi-exclamation-triangle-fill text-red-500"></i> {{ __('Terdapat kesalahan pada input form:') }}
    </div>
    <ul class="list-disc list-inside text-xs sm:text-sm space-y-0.5">
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

@if($book->collection_type === 'E-book' || $book->isDigital())
{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- FORM KHUSUS EDIT BUKU DIGITAL (RINGKAS & TIDAK REPOT)               --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
    <div class="px-6 sm:px-8 py-5 border-b border-slate-200 bg-gradient-to-r from-indigo-50 to-white flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-500/20">
                <i class="bi bi-file-earmark-pdf-fill text-xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-base">{{ __('Perbarui Buku Digital (E-Book)') }}</h3>
                <p class="text-xs text-slate-500">{{ __('File e-book ini lengkap beserta cover & isinya. Ganti file PDF atau edit informasi dasarnya.') }}</p>
            </div>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
            <i class="bi bi-cloud-check-fill"></i> E-Book
        </span>
    </div>

    <form method="POST" action="{{ route('books.update', $book) }}" enctype="multipart/form-data" class="p-6 sm:p-8">
        @csrf
        @method('PUT')
        <input type="hidden" name="collection_type" value="E-book">
        <input type="hidden" name="language" value="{{ $book->language ?? 'id' }}">
        <input type="hidden" name="pages" id="editDigitalPagesInput" value="{{ old('pages', $book->pages) }}">
        <input type="hidden" name="cover_image_data" id="editDigitalCoverImageData">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- Kolom Kiri: File PDF & Cover Preview --}}
            <div class="lg:col-span-5 flex flex-col gap-4">
                
                {{-- File PDF Saat Ini & Ganti File --}}
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        {{ __('File E-Book (PDF)') }}
                    </label>

                    @if($book->digital_file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->digital_file_path))
                    <div class="mb-3 p-3 bg-white border border-slate-200 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <i class="bi bi-file-earmark-pdf-fill text-red-500 text-2xl flex-shrink-0"></i>
                            <div class="min-w-0">
                                <div class="text-xs font-bold text-slate-800 truncate">{{ basename($book->digital_file_path) }}</div>
                                <div class="text-[11px] text-slate-400">
                                    {{ round(\Illuminate\Support\Facades\Storage::disk('public')->size($book->digital_file_path) / 1048576, 2) }} MB &bull; {{ $book->pages ?? '-' }} Hlm
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('opac.read', $book) }}" target="_blank" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors inline-flex items-center gap-1">
                            <i class="bi bi-book-half"></i> {{ __('Baca') }}
                        </a>
                    </div>
                    @endif

                    <div class="border-2 border-dashed border-slate-300 hover:border-indigo-400 bg-white rounded-xl p-4 text-center cursor-pointer transition-colors"
                         onclick="document.getElementById('editDigitalFileInput').click()">
                        <input type="file" 
                               name="digital_file" 
                               id="editDigitalFileInput" 
                               class="hidden" 
                               accept=".pdf,.epub"
                               onchange="if(this.files.length) handleEditDigitalPdf(this.files[0])">
                        
                        <i class="bi bi-arrow-repeat text-indigo-600 text-xl block mb-1"></i>
                        <span class="text-xs font-bold text-slate-700 block">{{ __('Pilih PDF Baru untuk Mengganti') }}</span>
                        <span class="text-[11px] text-slate-400">{{ __('Kosongkan jika tidak ingin mengubah file digital') }}</span>
                    </div>

                    <div id="editDigitalFileInfo" class="hidden mt-2 p-2 bg-indigo-50 rounded-lg text-xs flex items-center justify-between">
                        <span id="editDigitalFileName" class="font-bold text-indigo-900 truncate"></span>
                        <span id="editDigitalFileSize" class="text-indigo-600 font-semibold flex-shrink-0 ml-2"></span>
                    </div>
                </div>

                {{-- Box Cover Sampul --}}
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex flex-col items-center text-center">
                    <div class="text-xs font-semibold text-slate-600 mb-2 flex items-center gap-1.5">
                        <i class="bi bi-image text-indigo-500"></i> {{ __('Cover Sampul Buku Saat Ini') }}
                    </div>

                    <div class="w-28 aspect-[3/4] bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex items-center justify-center relative">
                        @if($book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
                            <img id="editDigitalCoverPreviewImg" src="{{ asset('storage/' . $book->cover_image) }}" class="w-full h-full object-cover" alt="Cover">
                        @else
                            <img id="editDigitalCoverPreviewImg" src="" class="hidden w-full h-full object-cover" alt="Cover">
                            <i class="bi bi-book text-3xl text-slate-300"></i>
                        @endif
                    </div>

                    <div id="editDigitalCoverStatus" class="mt-2 text-xs text-slate-400">
                        {{ __('Cover diambil otomatis dari halaman 1 PDF') }}
                    </div>

                    <div class="w-full mt-3 pt-3 border-t border-slate-200 text-left">
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">{{ __('Ganti Cover Manual (Opsional)') }}</label>
                        <input type="file" name="cover_image" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" accept="image/*">
                    </div>
                </div>

            </div>

            {{-- Kolom Kanan: Data Informasi Buku --}}
            <div class="lg:col-span-7 space-y-4">
                
                {{-- Judul Buku --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        {{ __('Judul Buku') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none transition-all font-medium" 
                           value="{{ old('title', $book->title) }}" 
                           required>
                </div>

                {{-- Pengarang & Penerbit --}}
                @php
                    $mainAuthorName = $book->authors->first()?->name ?? ($book->main_author ?? '');
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            {{ __('Nama Pengarang / Penulis') }}
                        </label>
                        <input type="text" 
                               name="author_name" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none transition-all" 
                               placeholder="{{ __('Contoh: Andrea Hirata') }}" 
                               value="{{ old('author_name', $mainAuthorName) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            {{ __('Penerbit') }}
                        </label>
                        <select name="publisher_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none bg-white transition-all">
                            <option value="">{{ __('-- Pilih Penerbit (Opsional) --') }}</option>
                            @foreach($publishers as $pub)
                                <option value="{{ $pub->id }}" {{ old('publisher_id', $book->publisher_id) == $pub->id ? 'selected' : '' }}>{{ $pub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tahun & Kategori / Subyek --}}
                @php
                    $currentSubjectId = $book->subjects->first()?->id ?? null;
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            {{ __('Tahun Terbit') }}
                        </label>
                        <input type="text" 
                               name="publication_year" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none transition-all" 
                               value="{{ old('publication_year', $book->publication_year) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            {{ __('Kategori / Subyek') }}
                        </label>
                        <select name="subjects[]" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none bg-white transition-all">
                            <option value="">{{ __('-- Pilih Kategori (Opsional) --') }}</option>
                            @foreach($subjects as $sub)
                                <option value="{{ $sub->id }}" {{ old('subjects.0', $currentSubjectId) == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Sinopsis / Deskripsi --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        {{ __('Sinopsis / Catatan Ringkas') }}
                    </label>
                    <textarea name="abstract" 
                              rows="4" 
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none transition-all">{{ old('abstract', $book->abstract) }}</textarea>
                </div>

                {{-- Opsi Lanjutan (DDC / ISBN jika ada) --}}
                <details class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs">
                    <summary class="font-semibold text-slate-700 cursor-pointer flex items-center justify-between">
                        <span><i class="bi bi-sliders mr-1 text-indigo-500"></i> {{ __('Opsi Lanjutan (ISBN / DDC / No. Panggil jika diperlukan)') }}</span>
                        <i class="bi bi-chevron-down text-slate-400"></i>
                    </summary>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-3 pt-3 border-t border-slate-200">
                        <div>
                            <label class="block text-slate-600 mb-1">ISBN</label>
                            <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 text-xs bg-white">
                        </div>
                        <div>
                            <label class="block text-slate-600 mb-1">DDC</label>
                            <input type="text" name="ddc" value="{{ old('ddc', $book->ddc) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 text-xs bg-white">
                        </div>
                        <div>
                            <label class="block text-slate-600 mb-1">Nomor Panggil</label>
                            <input type="text" name="call_number" value="{{ old('call_number', $book->call_number) }}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 text-xs bg-white">
                        </div>
                    </div>
                </details>

                <div class="pt-3 flex justify-end gap-3">
                    <a href="{{ route('books.show', $book) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-xl text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors py-2.5 px-6">
                        {{ __('Batal') }}
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center text-sm font-bold rounded-xl btn-gradient-blue transition-all gap-2 py-2.5 px-8 shadow-md hover:shadow-lg cursor-pointer text-white">
                        <i class="bi bi-check-circle-fill"></i> {{ __('Simpan Perubahan Buku Digital') }}
                    </button>
                </div>

            </div>

        </div>
    </form>
</div>

@else

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- FORM STANDAR EDIT BUKU FISIK                                        --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-8">
    <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4 flex items-center justify-between">
        <span class="flex items-center gap-2"><i class="bi bi-pencil-square text-indigo-600"></i>{{ __('Form Edit Bibliografi Buku Fisik') }}</span>
        <span class="text-xs text-slate-400">{{ __('Koleksi Fisik Perpustakaan') }}</span>
    </div>
    <div class="p-8">

        <form method="POST" action="{{ route('books.update', $book) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="flex flex-wrap -mx-3">
                {{-- Left Column --}}
                <div class="w-full md:w-1/2 px-4">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Judul Utama') }} <span class="text-red-600">*</span></label>
                        <input type="text" name="title" class="@error('title') @enderror w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('title', $book->title) }}" required>
                        @error('title')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Sub Judul') }}</label>
                        <input type="text" name="subtitle" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('subtitle', $book->subtitle) }}">
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">ISBN</label>
                            <input type="text" name="isbn" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('isbn', $book->isbn) }}">
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">ISBN 13</label>
                            <input type="text" name="isbn13" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('isbn13', $book->isbn13) }}">
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Nomor Panggil') }}</label>
                            <input type="text" name="call_number" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('call_number', $book->call_number) }}">
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('DDC (Dewey Decimal)') }}</label>
                            <input type="text" name="ddc" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('ddc', $book->ddc) }}">
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/3 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Edisi') }}</label>
                            <input type="text" name="edition" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('edition', $book->edition) }}">
                        </div>
                        <div class="w-full md:w-1/3 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Bahasa') }}</label>
                            <select name="language" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4">
                                <option value="id" {{ old('language', $book->language) == 'id' ? 'selected' : '' }}>{{ __('Indonesia (id)') }}</option>
                                <option value="en" {{ old('language', $book->language) == 'en' ? 'selected' : '' }}>{{ __('Inggris (en)') }}</option>
                            </select>
                        </div>
                        <div class="w-full md:w-1/3 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Jenis Koleksi') }} <span class="text-red-600">*</span></label>
                            <select name="collection_type" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4" required>
                                <option value="Buku Teks" {{ old('collection_type', $book->collection_type) == 'Buku Teks' ? 'selected' : '' }}>{{ __('Buku Teks') }}</option>
                                <option value="Referensi" {{ old('collection_type', $book->collection_type) == 'Referensi' ? 'selected' : '' }}>{{ __('Referensi') }}</option>
                                <option value="Majalah" {{ old('collection_type', $book->collection_type) == 'Majalah' ? 'selected' : '' }}>{{ __('Majalah') }}</option>
                                <option value="Kamus" {{ old('collection_type', $book->collection_type) == 'Kamus' ? 'selected' : '' }}>{{ __('Kamus') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="w-full md:w-1/2 px-4">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Pengarang') }}</label>
                        @php
                        $selectedAuthors = $book->authors->pluck('id')->toArray();
                        @endphp
                        <select name="authors[]" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4">
                            <option value="">{{ __('Pilih Pengarang...') }}</option>
                            @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ in_array($author->id, old('authors', $selectedAuthors)) ? 'selected' : '' }}>{{ $author->name }} ({{ $author->type }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Penerbit') }}</label>
                            <select name="publisher_id" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4">
                                <option value="">{{ __('Pilih Penerbit...') }}</option>
                                @foreach($publishers as $pub)
                                <option value="{{ $pub->id }}" {{ old('publisher_id', $book->publisher_id) == $pub->id ? 'selected' : '' }}>{{ $pub->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Tahun Terbit') }}</label>
                            <input type="text" name="publication_year" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('publication_year', $book->publication_year) }}">
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Tempat Terbit') }}</label>
                            <input type="text" name="place_of_publication" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('place_of_publication', $book->place_of_publication) }}">
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Jumlah Halaman') }}</label>
                            <input type="number" name="pages" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('pages', $book->pages) }}" min="1">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Subyek / Topik') }}</label>
                        @php
                        $selectedSubjects = $book->subjects->pluck('id')->toArray();
                        @endphp
                        <select name="subjects[]" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4">
                            <option value="">{{ __('Pilih Subyek...') }}</option>
                            @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}" {{ in_array($sub->id, old('subjects', $selectedSubjects)) ? 'selected' : '' }}>{{ $sub->name }} @if($sub->ddc) ({{ $sub->ddc }}) @endif</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Cover Sampul Buku') }}</label>
                        @if($book->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_image))
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $book->cover_image) }}" style="width:60px;aspect-ratio:3/4;object-fit:cover;border-radius:4px">
                        </div>
                        @endif
                        <input type="file" name="cover_image" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" accept="image/*">
                    </div>
                </div>

                {{-- Full width --}}
                <div class="w-full px-4">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Abstrak / Deskripsi Singkat') }}</label>
                        <textarea name="abstract" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" rows="3">{{ old('abstract', $book->abstract) }}</textarea>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100 my-4">

            <div class="justify-end flex gap-2">
                <a href="{{ route('books.show', $book) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2.5 px-6">{{ __('Batal') }}</a>
                <button type="submit" class="inline-flex items-center justify-center text-sm font-semibold rounded-lg btn-gradient-green hover:opacity-95 shadow-md shadow-emerald-500/20 text-white gap-2 py-2.5 px-6 transition-all transform hover:-translate-y-0.5">
                    <i class="bi bi-check-circle-fill text-base"></i>{{ __('Simpan Perubahan') }}
                </button>
            </div>
        </form>

    </div>
</div>
@endif

@endsection
