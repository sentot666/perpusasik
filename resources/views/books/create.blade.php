@extends('layouts.app')

@section('title', __('Tambah Buku Baru'))
@section('page-title', __('Tambah Buku'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('books.index') }}">{{ __('Master Buku') }}</a></li>
<li class="breadcrumb-item active">{{ __('Tambah Buku') }}</li>
@endsection

@push('scripts')
<script>
// ─── DATA ──────────────────────────────────────────────────
const allAuthors = @json($authors->map(fn($a) => ['id' => $a->id, 'name' => $a->name, 'type' => $a->type]));
const allPublishers = @json($publishers->map(fn($p) => ['id' => $p->id, 'name' => $p->name]));

// ─── PUBLISHER COMBOBOX ────────────────────────────────────
const pubInput   = document.getElementById('publisherSearchInput');
const pubIdInput = document.getElementById('publisherIdInput');
const pubDrop    = document.getElementById('publisherDropdown');

// Pre-fill if old value exists
const oldPubId = pubIdInput.value;
if (oldPubId) {
    const found = allPublishers.find(p => p.id == oldPubId);
    if (found) pubInput.value = found.name;
}

function renderPublishers(q) {
    const filtered = q
        ? allPublishers.filter(p => p.name.toLowerCase().includes(q.toLowerCase()))
        : allPublishers;
    pubDrop.innerHTML = '';
    if (!filtered.length) {
        pubDrop.innerHTML = '<div class="px-4 py-2 text-slate-400 text-xs">Tidak ditemukan</div>';
    } else {
        filtered.forEach(p => {
            const el = document.createElement('div');
            el.className = 'px-4 py-2 hover:bg-indigo-50 cursor-pointer transition-colors';
            el.textContent = p.name;
            el.addEventListener('mousedown', () => {
                pubInput.value   = p.name;
                pubIdInput.value = p.id;
                pubDrop.classList.add('hidden');
            });
            pubDrop.appendChild(el);
        });
    }
    pubDrop.classList.remove('hidden');
}

pubInput.addEventListener('input', () => {
    pubIdInput.value = '';
    renderPublishers(pubInput.value);
});
pubInput.addEventListener('focus', () => renderPublishers(pubInput.value));
pubInput.addEventListener('blur',  () => setTimeout(() => pubDrop.classList.add('hidden'), 150));

// ─── AUTHOR TAG COMBOBOX ───────────────────────────────────
const authorSearch   = document.getElementById('authorSearchInput');
const authorDrop     = document.getElementById('authorDropdown');
const authorTagsBox  = document.getElementById('authorTagsBox');
const authorHidden   = document.getElementById('authorHiddenInputs');

let selectedAuthors = [];

// Pre-fill old values
@if(is_array(old('authors')))
    @foreach(old('authors') as $oldAuthorId)
        (function() {
            const found = allAuthors.find(a => a.id == {{ $oldAuthorId }});
            if (found) addAuthor(found);
        })();
    @endforeach
@endif

function addAuthor(author) {
    if (selectedAuthors.find(a => a.id === author.id)) return;
    selectedAuthors.push(author);
    renderAuthorTags();
}

function removeAuthor(id) {
    selectedAuthors = selectedAuthors.filter(a => a.id !== id);
    renderAuthorTags();
}

function renderAuthorTags() {
    // Clear existing tags (not the search input)
    authorTagsBox.querySelectorAll('.author-tag').forEach(el => el.remove());
    authorHidden.innerHTML = '';

    selectedAuthors.forEach(a => {
        // Tag pill
        const tag = document.createElement('span');
        tag.className = 'author-tag inline-flex items-center gap-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full px-2.5 py-1';
        tag.innerHTML = `${a.name} <button type="button" onclick="removeAuthor(${a.id})" class="text-indigo-400 hover:text-indigo-700 leading-none">&times;</button>`;
        authorTagsBox.insertBefore(tag, authorSearch);

        // Hidden input
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = 'authors[]';
        input.value = a.id;
        authorHidden.appendChild(input);
    });
}

function renderAuthors(q) {
    const filtered = q
        ? allAuthors.filter(a => a.name.toLowerCase().includes(q.toLowerCase()))
        : allAuthors;
    authorDrop.innerHTML = '';
    if (!filtered.length) {
        authorDrop.innerHTML = '<div class="px-4 py-2 text-slate-400 text-xs">Tidak ditemukan</div>';
    } else {
        filtered.slice(0, 30).forEach(a => {
            const selected = selectedAuthors.find(s => s.id === a.id);
            const el = document.createElement('div');
            el.className = `px-4 py-2 cursor-pointer transition-colors flex items-center gap-2 ${selected ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'hover:bg-indigo-50'}`;
            el.innerHTML = `<span>${a.name}</span><span class="text-xs text-slate-400">${a.type}</span>${selected ? '<span class="ml-auto text-indigo-400 text-xs">✓ dipilih</span>' : ''}`;
            el.addEventListener('mousedown', () => {
                if (selected) {
                    removeAuthor(a.id);
                } else {
                    addAuthor(a);
                }
                authorSearch.value = '';
                renderAuthors('');
                authorSearch.focus();
            });
            authorDrop.appendChild(el);
        });
    }
    authorDrop.classList.remove('hidden');
}

authorSearch.addEventListener('input',  () => renderAuthors(authorSearch.value));
authorSearch.addEventListener('focus',  () => renderAuthors(authorSearch.value));
authorSearch.addEventListener('blur',   () => setTimeout(() => authorDrop.classList.add('hidden'), 150));

// Close dropdowns on outside click
document.addEventListener('click', (e) => {
    if (!document.getElementById('publisherCombo').contains(e.target)) pubDrop.classList.add('hidden');
    if (!document.getElementById('authorCombo').contains(e.target)) authorDrop.classList.add('hidden');
    if (!document.getElementById('subjectCombo').contains(e.target)) subjectDrop.classList.add('hidden');
});

// ─── SUBJECT TAG COMBOBOX ──────────────────────────────────
const allSubjects    = @json($subjects->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'ddc' => $s->ddc]));
const subjectSearch  = document.getElementById('subjectSearchInput');
const subjectDrop    = document.getElementById('subjectDropdown');
const subjectTagsBox = document.getElementById('subjectTagsBox');
const subjectHidden  = document.getElementById('subjectHiddenInputs');

let selectedSubjects = [];

@if(is_array(old('subjects')))
    @foreach(old('subjects') as $oldSubjectId)
        (function() {
            const found = allSubjects.find(s => s.id == {{ $oldSubjectId }});
            if (found) addSubject(found);
        })();
    @endforeach
@endif

function addSubject(subject) {
    if (selectedSubjects.find(s => s.id === subject.id)) return;
    selectedSubjects.push(subject);
    renderSubjectTags();
}

function removeSubject(id) {
    selectedSubjects = selectedSubjects.filter(s => s.id !== id);
    renderSubjectTags();
}

function renderSubjectTags() {
    subjectTagsBox.querySelectorAll('.subject-tag').forEach(el => el.remove());
    subjectHidden.innerHTML = '';
    selectedSubjects.forEach(s => {
        const tag = document.createElement('span');
        tag.className = 'subject-tag inline-flex items-center gap-1 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full px-2.5 py-1';
        tag.innerHTML = `${s.name}${s.ddc ? ' <span class="opacity-60">('+s.ddc+')</span>' : ''} <button type="button" onclick="removeSubject(${s.id})" class="text-emerald-400 hover:text-emerald-700 leading-none">&times;</button>`;
        subjectTagsBox.insertBefore(tag, subjectSearch);
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = 'subjects[]';
        input.value = s.id;
        subjectHidden.appendChild(input);
    });
}

function renderSubjects(q) {
    const filtered = q
        ? allSubjects.filter(s => s.name.toLowerCase().includes(q.toLowerCase()) || (s.ddc && s.ddc.includes(q)))
        : allSubjects;
    subjectDrop.innerHTML = '';
    if (!filtered.length) {
        subjectDrop.innerHTML = '<div class="px-4 py-2 text-slate-400 text-xs">Tidak ditemukan</div>';
    } else {
        filtered.slice(0, 30).forEach(s => {
            const selected = selectedSubjects.find(x => x.id === s.id);
            const el = document.createElement('div');
            el.className = `px-4 py-2 cursor-pointer transition-colors flex items-center gap-2 ${selected ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'hover:bg-emerald-50'}`;
            el.innerHTML = `<span>${s.name}</span>${s.ddc ? '<span class="text-xs text-slate-400">'+s.ddc+'</span>' : ''}${selected ? '<span class="ml-auto text-emerald-400 text-xs">✓ dipilih</span>' : ''}`;
            el.addEventListener('mousedown', () => {
                if (selected) removeSubject(s.id); else addSubject(s);
                subjectSearch.value = '';
                renderSubjects('');
                subjectSearch.focus();
            });
            subjectDrop.appendChild(el);
        });
    }
    subjectDrop.classList.remove('hidden');
}

subjectSearch.addEventListener('input',  () => renderSubjects(subjectSearch.value));
subjectSearch.addEventListener('focus',  () => renderSubjects(subjectSearch.value));
subjectSearch.addEventListener('blur',   () => setTimeout(() => subjectDrop.classList.add('hidden'), 150));
</script>
@endpush
@section('content')
<div class="page-header">
    <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ __('Tambah Buku Baru') }}</h1>
    <p>{{ __('Masukkan data bibliografi buku baru ke katalog perpustakaan') }}</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4"><i class="bi bi-plus-circle-fill text-indigo-600 mr-2"></i>{{ __('Form Data Bibliografi') }}</div>
    <div class="p-8">

        @if($errors->any())
        <div class="alert alert-danger border-0 mb-6 py-2" style="border-radius:8px">
            <i class="bi bi-exclamation-triangle-fill mr-1"></i>
            {{ __('Periksa kembali form Anda. Ada beberapa input yang tidak valid.') }}
        </div>
        @endif

        <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="flex flex-wrap -mx-3">
                {{-- Left Column --}}
                <div class="w-full md:w-1/2 px-4">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Judul Utama') }} <span class="text-red-600">*</span></label>
                        <input type="text" name="title" class="@error('title') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" value="{{ old('title') }}" required placeholder="{{ __('Contoh: Pemrograman Web dengan Laravel') }}">
                        @error('title')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Sub Judul') }}</label>
                        <input type="text" name="subtitle" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('subtitle') }}" placeholder="{{ __('Anak judul atau penjelasan judul (opsional)') }}">
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">ISBN</label>
                            <input type="text" name="isbn" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('isbn') }}" placeholder="ISBN 10">
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">ISBN 13</label>
                            <input type="text" name="isbn13" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('isbn13') }}" placeholder="ISBN 13">
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Nomor Panggil') }}</label>
                            <input type="text" name="call_number" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('call_number') }}" placeholder="{{ __('Contoh: 005.3 WID p') }}">
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('DDC (Dewey Decimal)') }}</label>
                            <input type="text" name="ddc" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('ddc') }}" placeholder="{{ __('Contoh: 005.3') }}">
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/3 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Edisi') }}</label>
                            <input type="text" name="edition" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('edition') }}" placeholder="{{ __('Contoh: Cet. 2') }}">
                        </div>
                        <div class="w-full md:w-1/3 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Bahasa') }}</label>
                            <select name="language" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4">
                                <option value="id" {{ old('language') == 'id' ? 'selected' : '' }}>{{ __('Indonesia (id)') }}</option>
                                <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>{{ __('Inggris (en)') }}</option>
                            </select>
                        </div>
                        <div class="w-full md:w-1/3 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Jenis Koleksi') }} <span class="text-red-600">*</span></label>
                            <select name="collection_type" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4" required>
                                <option value="Buku Teks" {{ old('collection_type') == 'Buku Teks' ? 'selected' : '' }}>{{ __('Buku Teks') }}</option>
                                <option value="Referensi" {{ old('collection_type') == 'Referensi' ? 'selected' : '' }}>{{ __('Referensi') }}</option>
                                <option value="Majalah" {{ old('collection_type') == 'Majalah' ? 'selected' : '' }}>{{ __('Majalah') }}</option>
                                <option value="Kamus" {{ old('collection_type') == 'Kamus' ? 'selected' : '' }}>{{ __('Kamus') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="w-full md:w-1/2 px-4">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Pengarang') }} <span class="text-slate-500">({{ __('Bisa pilih lebih dari satu') }})</span></label>
                        <div class="relative" id="authorCombo">
                            <div id="authorTagsBox" class="min-h-[42px] w-full rounded-lg border border-slate-300 bg-white text-sm focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 px-2 py-1.5 flex flex-wrap gap-1 cursor-text" onclick="document.getElementById('authorSearchInput').focus()">
                                <div id="authorHiddenInputs"></div>
                                <input type="text" id="authorSearchInput" class="outline-none border-none bg-transparent text-sm flex-1 min-w-[120px] py-0.5 px-1" placeholder="{{ __('Ketik nama pengarang...') }}" autocomplete="off">
                            </div>
                            <div id="authorDropdown" class="absolute z-50 w-full bg-white border border-slate-200 rounded-lg shadow-lg max-h-48 overflow-y-auto hidden mt-1 text-sm"></div>
                        </div>
                        <div class="text-xs text-slate-400 mt-1">{{ __('Ketik untuk mencari pengarang') }}</div>
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Penerbit') }}</label>
                            <div class="relative" id="publisherCombo">
                                <input type="text" id="publisherSearchInput" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4" placeholder="{{ __('Ketik nama penerbit...') }}" autocomplete="off">
                                <input type="hidden" name="publisher_id" id="publisherIdInput" value="{{ old('publisher_id') }}">
                                <div id="publisherDropdown" class="absolute z-50 w-full bg-white border border-slate-200 rounded-lg shadow-lg max-h-48 overflow-y-auto hidden mt-1 text-sm"></div>
                            </div>
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Tahun Terbit') }}</label>
                            <input type="text" name="publication_year" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('publication_year') }}" placeholder="{{ __('Contoh: 2023') }}">
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-2 mb-6">
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Tempat Terbit') }}</label>
                            <input type="text" name="place_of_publication" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('place_of_publication') }}" placeholder="{{ __('Kota/Negara') }}">
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Jumlah Halaman') }}</label>
                            <input type="number" name="pages" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" value="{{ old('pages') }}" min="1">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Subyek / Topik') }}</label>
                        <div class="relative" id="subjectCombo">
                            <div id="subjectTagsBox" class="min-h-[42px] w-full rounded-lg border border-slate-300 bg-white text-sm focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500 px-2 py-1.5 flex flex-wrap gap-1 cursor-text" onclick="document.getElementById('subjectSearchInput').focus()">
                                <div id="subjectHiddenInputs"></div>
                                <input type="text" id="subjectSearchInput" class="outline-none border-none bg-transparent text-sm flex-1 min-w-[120px] py-0.5 px-1" placeholder="{{ __('Ketik nama subyek atau DDC...') }}" autocomplete="off">
                            </div>
                            <div id="subjectDropdown" class="absolute z-50 w-full bg-white border border-slate-200 rounded-lg shadow-lg max-h-48 overflow-y-auto hidden mt-1 text-sm"></div>
                        </div>
                        <div class="text-xs text-slate-400 mt-1">{{ __('Ketik untuk mencari subyek') }}</div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Cover Sampul Buku') }}</label>
                        <input type="file" name="cover_image" class="@error('cover_image') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" accept="image/*">
                        @error('cover_image')<div class="text-xs text-red-500 mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Full width --}}
                <div class="w-full px-4">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Abstrak / Deskripsi Singkat') }}</label>
                        <textarea name="abstract" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" rows="3" placeholder="{{ __('Deskripsi singkat isi buku...') }}">{{ old('abstract') }}</textarea>
                    </div>
                </div>
            </div>

            <hr>

            <div class="justify-end flex gap-2 pt-4">
                <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2.5 px-6">{{ __('Batal') }}</a>
                <button type="submit" class="inline-flex items-center justify-center text-sm font-semibold rounded-lg btn-gradient-green hover:opacity-95 shadow-md shadow-emerald-500/20 text-white gap-2 py-2.5 px-6 transition-all transform hover:-translate-y-0.5">
                    <i class="bi bi-check-circle-fill text-base"></i>{{ __('Simpan Buku') }}
                </button>
            </div>
        </form>

    </div>
</div>
@endsection


