@extends('layouts.app')

@section('title', 'Edit Buku: ' . $book->title)
@section('page-title', 'Edit Buku')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('books.index') }}">Master Buku</a></li>
<li class="breadcrumb-item"><a href="{{ route('books.show', $book) }}">{{ Str::limit($book->title, 20) }}</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <h1>Edit Data Buku</h1>
    <p>Perbarui data katalog bibliografi buku</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-pencil-square text-indigo-600 mr-2"></i>Form Edit Bibliografi</div>
    <div class="p-8">

        @if($errors->any())
        <div class="alert alert-danger border-0 mb-6 py-2" style="border-radius:8px">
            <i class="bi bi-exclamation-triangle-fill mr-1"></i>
            Periksa kembali form Anda. Ada beberapa input yang tidak valid.
        </div>
        @endif

        <form method="POST" action="{{ route('books.update', $book) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="flex flex-wrap -mx-3">
                {{-- Left Column --}}
                <div class="w-full md:w-1/2 px-4">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Judul Utama <span class="text-red-600">*</span></label>
                        <input type="text" name="title" class="@error('title') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" value="{{ old('title', $book->title) }}" required>
                        @error('title')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Sub Judul</label>
                        <input type="text" name="subtitle" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('subtitle', $book->subtitle) }}">
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">ISBN</label>
                            <input type="text" name="isbn" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('isbn', $book->isbn) }}">
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">ISBN 13</label>
                            <input type="text" name="isbn13" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('isbn13', $book->isbn13) }}">
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Panggil</label>
                            <input type="text" name="call_number" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('call_number', $book->call_number) }}">
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">DDC (Dewey Decimal)</label>
                            <input type="text" name="ddc" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('ddc', $book->ddc) }}">
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/3 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Edisi</label>
                            <input type="text" name="edition" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('edition', $book->edition) }}">
                        </div>
                        <div class="w-full md:w-1/3 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Bahasa</label>
                            <select name="language" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4">
                                <option value="id" {{ old('language', $book->language) == 'id' ? 'selected' : '' }}>Indonesia (id)</option>
                                <option value="en" {{ old('language', $book->language) == 'en' ? 'selected' : '' }}>Inggris (en)</option>
                            </select>
                        </div>
                        <div class="w-full md:w-1/3 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Koleksi <span class="text-red-600">*</span></label>
                            <select name="collection_type" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4" required>
                                <option value="Buku Teks" {{ old('collection_type', $book->collection_type) == 'Buku Teks' ? 'selected' : '' }}>Buku Teks</option>
                                <option value="Referensi" {{ old('collection_type', $book->collection_type) == 'Referensi' ? 'selected' : '' }}>Referensi</option>
                                <option value="Majalah" {{ old('collection_type', $book->collection_type) == 'Majalah' ? 'selected' : '' }}>Majalah</option>
                                <option value="Kamus" {{ old('collection_type', $book->collection_type) == 'Kamus' ? 'selected' : '' }}>Kamus</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="w-full md:w-1/2 px-4">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Pengarang <span class="text-slate-500">(Pilih beberapa jika ada)</span></label>
                        @php
                        $selectedAuthors = $book->authors->pluck('id')->toArray();
                        @endphp
                        <select name="authors[]" class="@error('authors') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" multiple style="height:115px">
                            @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ in_array($author->id, old('authors', $selectedAuthors)) ? 'selected' : '' }}>{{ $author->name }} ({{ $author->type }})</option>
                            @endforeach
                        </select>
                        <div class="form-text">Tahan Ctrl untuk memilih lebih dari satu pengarang</div>
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Penerbit</label>
                            <select name="publisher_id" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4">
                                <option value="">Pilih Penerbit...</option>
                                @foreach($publishers as $pub)
                                <option value="{{ $pub->id }}" {{ old('publisher_id', $book->publisher_id) == $pub->id ? 'selected' : '' }}>{{ $pub->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tahun Terbit</label>
                            <input type="text" name="publication_year" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('publication_year', $book->publication_year) }}">
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tempat Terbit</label>
                            <input type="text" name="place_of_publication" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('place_of_publication', $book->place_of_publication) }}">
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah Halaman</label>
                            <input type="number" name="pages" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('pages', $book->pages) }}" min="1">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Subyek / Topik</label>
                        @php
                        $selectedSubjects = $book->subjects->pluck('id')->toArray();
                        @endphp
                        <select name="subjects[]" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4" multiple style="height:78px">
                            @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}" {{ in_array($sub->id, old('subjects', $selectedSubjects)) ? 'selected' : '' }}>{{ $sub->name }} @if($sub->ddc) ({{ $sub->ddc }}) @endif</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Cover Sampul Buku</label>
                        @if($book->cover_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $book->cover_image) }}" style="width:60px;aspect-ratio:3/4;object-fit:cover;border-radius:4px">
                        </div>
                        @endif
                        <input type="file" name="cover_image" class="@error('cover_image') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" accept="image/*">
                        @error('cover_image')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Full width --}}
                <div class="w-full px-4">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Abstrak / Deskripsi Singkat</label>
                        <textarea name="abstract" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" rows="3">{{ old('abstract', $book->abstract) }}</textarea>
                    </div>
                </div>
            </div>

            <hr>

            <div class="justify-end flex gap-2">
                <a href="{{ route('books.show', $book) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-200 border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2 px-6">Batal</a>
                <button type="submit" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 transition-colors text-white font-semibold gap-2 py-2 px-6">Simpan Perubahan</button>
            </div>
        </form>

    </div>
</div>
@endsection
