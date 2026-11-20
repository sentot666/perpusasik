@extends('layouts.app')

@section('title', __('Tambah Buku Baru'))
@section('page-title', __('Tambah Buku'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('books.index') }}">{{ __('Master Buku') }}</a></li>
<li class="breadcrumb-item active">{{ __('Tambah Buku') }}</li>
@endsection

@push('scripts')
<!-- PDF.js untuk ekstraksi otomatis cover & halaman dari PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script>
    if (window.pdfjsLib) {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
    }
</script>

<script>
// ─── TAB / MODE SWITCHER ──────────────────────────────────
let activeMode = '{{ old('collection_type') === 'E-book' || ($defaultMode ?? '') === 'digital' ? 'digital' : 'physical' }}';

function switchMode(mode) {
    activeMode = mode;
    const digitalTabBtn = document.getElementById('tabDigitalBtn');
    const physicalTabBtn = document.getElementById('tabPhysicalBtn');
    const digitalSection = document.getElementById('digitalBookSection');
    const physicalSection = document.getElementById('physicalBookSection');

    if (mode === 'digital') {
        digitalTabBtn.className = 'flex-1 py-2.5 px-4 rounded-xl font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all bg-indigo-600 text-white shadow-md cursor-pointer';
        physicalTabBtn.className = 'flex-1 py-2.5 px-4 rounded-xl font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all text-slate-600 hover:text-slate-900 cursor-pointer';
        digitalSection.classList.remove('hidden');
        physicalSection.classList.add('hidden');
    } else {
        physicalTabBtn.className = 'flex-1 py-2.5 px-4 rounded-xl font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all bg-white text-slate-800 shadow-sm cursor-pointer';
        digitalTabBtn.className = 'flex-1 py-2.5 px-4 rounded-xl font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all text-slate-600 hover:text-slate-900 cursor-pointer';
        physicalSection.classList.remove('hidden');
        digitalSection.classList.add('hidden');
    }
}

// ─── PDF AUTO-EXTRACTION UNTUK BUKU DIGITAL ──────────────
function handleDigitalPdfSelected(file) {
    if (!file) return;

    const fileInfoBox = document.getElementById('digitalFileInfo');
    const fileNameEl = document.getElementById('digitalFileName');
    const fileSizeEl = document.getElementById('digitalFileSize');
    const titleInput = document.getElementById('digitalTitleInput');
    const pagesInput = document.getElementById('digitalPagesInput');
    const coverImageDataInput = document.getElementById('digitalCoverImageData');
    const coverPreviewImg = document.getElementById('digitalCoverPreviewImg');
    const coverPlaceholder = document.getElementById('digitalCoverPlaceholder');
    const coverStatusBadge = document.getElementById('digitalCoverStatusBadge');

    // Tampilkan nama & ukuran file
    fileInfoBox.classList.remove('hidden');
    fileNameEl.textContent = file.name;
    const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
    fileSizeEl.textContent = sizeMb + ' MB';

    // Auto-fill Judul dari nama file (jika judul masih kosong atau belum diubah manual)
    if (!titleInput.value || titleInput.dataset.autoFilled === "true") {
        let cleanName = file.name.replace(/\.[^/.]+$/, ""); // Hapus ekstensi
        cleanName = cleanName.replace(/[_-]+/g, " ").trim(); // Ganti _ dan - jadi spasi
        // Kapitalisasi awal kata
        cleanName = cleanName.replace(/\b\w/g, l => l.toUpperCase());
        titleInput.value = cleanName;
        titleInput.dataset.autoFilled = "true";
    }

    // Jika file bukan PDF (misal ePub), lewati rendering PDF.js
    if (!file.name.toLowerCase().endsWith('.pdf')) {
        coverStatusBadge.textContent = 'File ePub terdeteksi. Silakan upload cover jika ada.';
        coverStatusBadge.className = 'text-xs text-amber-600 font-medium block mt-1';
        return;
    }

    coverStatusBadge.innerHTML = '<span class="inline-flex items-center gap-1.5 text-indigo-600 font-medium text-xs"><svg class="animate-spin h-3.5 w-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Mengekstrak cover halaman 1...</span>';

    const fileReader = new FileReader();
    fileReader.onload = function() {
        const typedarray = new Uint8Array(this.result);

        pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {
            // 1. Ekstrak total halaman
            if (pagesInput) {
                pagesInput.value = pdf.numPages;
                document.getElementById('digitalPagesDetectedText').textContent = pdf.numPages + ' Halaman';
            }

            // 2. Render Halaman 1 ke Canvas untuk jadi Cover
            pdf.getPage(1).then(function(page) {
                const viewport = page.getViewport({ scale: 1.5 });
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };

                page.render(renderContext).promise.then(function() {
                    // Export canvas ke JPEG Data URL
                    const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
                    coverImageDataInput.value = dataUrl;

                    // Tampilkan di preview image
                    coverPreviewImg.src = dataUrl;
                    coverPreviewImg.classList.remove('hidden');
                    coverPlaceholder.classList.add('hidden');

                    coverStatusBadge.innerHTML = '<span class="text-xs text-emerald-600 font-semibold flex items-center gap-1"><i class="bi bi-check-circle-fill"></i> Cover halaman 1 & total ' + pdf.numPages + ' halaman berhasil diekstrak!</span>';
                });
            });
        }).catch(function(err) {
            console.error("Gagal membaca PDF:", err);
            coverStatusBadge.textContent = 'Gagal mengekstrak cover otomatis. Anda tetap bisa menyimpan atau upload cover manual.';
            coverStatusBadge.className = 'text-xs text-slate-400 mt-1';
        });
    };
    fileReader.readAsArrayBuffer(file);
}

// ─── DATA BUKU FISIK ───────────────────────────────────────
const allAuthors = @json($authors->map(fn($a) => ['id' => $a->id, 'name' => $a->name, 'type' => $a->type]));
const allPublishers = @json($publishers->map(fn($p) => ['id' => $p->id, 'name' => $p->name]));

// ─── PUBLISHER COMBOBOX (FISIK) ───────────────────────────
const pubInput   = document.getElementById('publisherSearchInput');
const pubIdInput = document.getElementById('publisherIdInput');
const pubDrop    = document.getElementById('publisherDropdown');

if (pubInput && pubIdInput) {
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
}

// ─── AUTHOR TAG COMBOBOX (FISIK) ───────────────────────────
const authorSearch   = document.getElementById('authorSearchInput');
const authorDrop     = document.getElementById('authorDropdown');
const authorTagsBox  = document.getElementById('authorTagsBox');
const authorHidden   = document.getElementById('authorHiddenInputs');

let selectedAuthors = [];

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
    if (!authorTagsBox || !authorHidden) return;
    authorTagsBox.querySelectorAll('.author-tag').forEach(el => el.remove());
    authorHidden.innerHTML = '';

    selectedAuthors.forEach(a => {
        const tag = document.createElement('span');
        tag.className = 'author-tag inline-flex items-center gap-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full px-2.5 py-1';
        tag.innerHTML = `${a.name} <button type="button" onclick="removeAuthor(${a.id})" class="text-indigo-400 hover:text-indigo-700 leading-none">&times;</button>`;
        authorTagsBox.insertBefore(tag, authorSearch);

        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = 'authors[]';
        input.value = a.id;
        authorHidden.appendChild(input);
    });
}

function renderAuthors(q) {
    if (!authorDrop) return;
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

if (authorSearch) {
    authorSearch.addEventListener('input',  () => renderAuthors(authorSearch.value));
    authorSearch.addEventListener('focus',  () => renderAuthors(authorSearch.value));
    authorSearch.addEventListener('blur',   () => setTimeout(() => authorDrop.classList.add('hidden'), 150));
}

// ─── SUBJECT TAG COMBOBOX (FISIK) ──────────────────────────
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
    if (!subjectTagsBox || !subjectHidden) return;
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
    if (!subjectDrop) return;
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

if (subjectSearch) {
    subjectSearch.addEventListener('input',  () => renderSubjects(subjectSearch.value));
    subjectSearch.addEventListener('focus',  () => renderSubjects(subjectSearch.value));
    subjectSearch.addEventListener('blur',   () => setTimeout(() => subjectDrop.classList.add('hidden'), 150));
}

// Inisialisasi awal saat halaman termuat
document.addEventListener('DOMContentLoaded', () => {
    switchMode(activeMode);
});
</script>
@endpush

@section('content')
<div class="page-header mb-6">
    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-1">{{ __('Tambah Koleksi Buku') }}</h1>
    <p class="text-xs sm:text-sm text-slate-500">{{ __('Pilih jenis buku yang ingin ditambahkan: Buku Digital instan (PDF) atau Buku Fisik perpustakaan') }}</p>
</div>

{{-- ═══ MODE TOGGLE PILLS ═══════════════════════════════════════════ --}}
<div class="flex items-center gap-2 p-1.5 bg-slate-200/80 rounded-2xl max-w-md mb-6 shadow-xs border border-slate-300/60">
    <button type="button" id="tabDigitalBtn" onclick="switchMode('digital')" 
            class="flex-1 py-2.5 px-4 rounded-xl font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all cursor-pointer">
        <i class="bi bi-file-earmark-pdf-fill"></i>
        <span>{{ __('Buku Digital (E-Book)') }}</span>
        <span class="bg-indigo-100 text-indigo-700 text-[10px] font-extrabold px-1.5 py-0.5 rounded-full uppercase">Instan</span>
    </button>
    <button type="button" id="tabPhysicalBtn" onclick="switchMode('physical')" 
            class="flex-1 py-2.5 px-4 rounded-xl font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all cursor-pointer">
        <i class="bi bi-book"></i>
        <span>{{ __('Buku Fisik') }}</span>
    </button>
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

{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- 1. SECTION FORM BUKU DIGITAL (SUPER STREAMLINED & INSTAN)           --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div id="digitalBookSection" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
    <div class="px-6 sm:px-8 py-5 border-b border-slate-200 bg-gradient-to-r from-indigo-50 to-white flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-500/20">
                <i class="bi bi-file-earmark-pdf-fill text-xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-base">{{ __('Upload Buku Digital (E-Book)') }}</h3>
                <p class="text-xs text-slate-500">{{ __('Tinggal upload file PDF e-book seutuhnya. Judul, halaman, dan cover otomatis diekstrak!') }}</p>
            </div>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
            <i class="bi bi-magic"></i> Auto Extract
        </span>
    </div>

    <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data" class="p-6 sm:p-8">
        @csrf
        <input type="hidden" name="collection_type" value="E-book">
        <input type="hidden" name="language" value="id">
        <input type="hidden" name="pages" id="digitalPagesInput" value="{{ old('pages') }}">
        <input type="hidden" name="cover_image_data" id="digitalCoverImageData">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- Kolom Kiri: Upload Area & Preview Cover Otomatis --}}
            <div class="lg:col-span-5 flex flex-col gap-4">
                
                {{-- Dropzone File PDF --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        {{ __('File E-Book (PDF)') }} <span class="text-red-500">*</span>
                    </label>

                    <div class="relative border-2 border-dashed border-indigo-200 hover:border-indigo-400 bg-indigo-50/40 hover:bg-indigo-50/70 rounded-2xl p-6 text-center transition-all cursor-pointer group"
                         onclick="document.getElementById('digitalFileInput').click()"
                         ondragover="event.preventDefault(); this.classList.add('border-indigo-500', 'bg-indigo-100/50');"
                         ondragleave="event.preventDefault(); this.classList.remove('border-indigo-500', 'bg-indigo-100/50');"
                         ondrop="event.preventDefault(); this.classList.remove('border-indigo-500', 'bg-indigo-100/50'); if(event.dataTransfer.files.length) { document.getElementById('digitalFileInput').files = event.dataTransfer.files; handleDigitalPdfSelected(event.dataTransfer.files[0]); }">
                        
                        <input type="file" 
                               name="digital_file" 
                               id="digitalFileInput" 
                               class="hidden" 
                               accept=".pdf,.epub"
                               required
                               onchange="if(this.files.length) handleDigitalPdfSelected(this.files[0])">

                        <div class="w-14 h-14 rounded-2xl bg-white text-indigo-600 flex items-center justify-center mx-auto mb-3 shadow-xs border border-indigo-100 group-hover:scale-110 transition-transform">
                            <i class="bi bi-cloud-arrow-up-fill text-2xl"></i>
                        </div>
                        <div class="font-bold text-slate-800 text-sm mb-1">{{ __('Klik untuk pilih file PDF atau tarik ke sini') }}</div>
                        <p class="text-xs text-slate-400 leading-relaxed">{{ __('Format yang didukung: PDF, EPUB (Maksimal 50MB)') }}</p>
                    </div>

                    {{-- Info File Terpilih --}}
                    <div id="digitalFileInfo" class="hidden mt-3 p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <i class="bi bi-file-earmark-pdf-fill text-red-500 text-xl flex-shrink-0"></i>
                            <div class="min-w-0">
                                <div id="digitalFileName" class="text-xs font-bold text-slate-800 truncate">document.pdf</div>
                                <div class="text-[11px] text-slate-400 flex items-center gap-2">
                                    <span id="digitalFileSize">0 MB</span>
                                    <span>&bull;</span>
                                    <span id="digitalPagesDetectedText" class="text-indigo-600 font-semibold">-</span>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="document.getElementById('digitalFileInput').click()" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold px-2 py-1 rounded hover:bg-indigo-50">
                            {{ __('Ganti') }}
                        </button>
                    </div>
                </div>

                {{-- Box Pratinjau Cover Otomatis --}}
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex flex-col items-center text-center">
                    <div class="text-xs font-semibold text-slate-600 mb-2 flex items-center gap-1.5">
                        <i class="bi bi-image text-indigo-500"></i> {{ __('Pratinjau Cover Sampul') }}
                    </div>
                    
                    <div class="w-28 aspect-[3/4] bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex items-center justify-center relative">
                        <img id="digitalCoverPreviewImg" src="" class="hidden w-full h-full object-cover" alt="Cover Preview">
                        <div id="digitalCoverPlaceholder" class="text-slate-300 flex flex-col items-center justify-center p-2">
                            <i class="bi bi-book text-3xl mb-1"></i>
                            <span class="text-[10px] text-slate-400 text-center leading-tight">{{ __('Otomatis dari Halaman 1 PDF') }}</span>
                        </div>
                    </div>

                    <div id="digitalCoverStatusBadge" class="mt-2 text-xs text-slate-400">
                        {{ __('Belum ada PDF dipilih') }}
                    </div>
                </div>

            </div>

            {{-- Kolom Kanan: Identitas Ringkas (Judul, Pengarang, Kategori, Sinopsis) --}}
            <div class="lg:col-span-7 space-y-4">
                
                {{-- Judul Buku (Auto-fill dari nama file PDF) --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        {{ __('Judul Buku') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           id="digitalTitleInput" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none transition-all font-medium" 
                           placeholder="{{ __('Akan terisi otomatis saat PDF dipilih...') }}" 
                           value="{{ old('title') }}" 
                           required>
                    <p class="text-xs text-slate-400 mt-1">{{ __('Judul otomatis terisi saat memilih file PDF, Anda tetap bisa mengeditnya.') }}</p>
                </div>

                {{-- Pengarang & Penerbit --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            {{ __('Nama Pengarang / Penulis') }}
                        </label>
                        <input type="text" 
                               name="author_name" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none transition-all" 
                               placeholder="{{ __('Contoh: Andrea Hirata') }}" 
                               value="{{ old('author_name') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            {{ __('Penerbit') }}
                        </label>
                        <select name="publisher_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none bg-white transition-all">
                            <option value="">{{ __('-- Pilih Penerbit (Opsional) --') }}</option>
                            @foreach($publishers as $pub)
                                <option value="{{ $pub->id }}" {{ old('publisher_id') == $pub->id ? 'selected' : '' }}>{{ $pub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tahun & Kategori / Subyek --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            {{ __('Tahun Terbit') }}
                        </label>
                        <input type="text" 
                               name="publication_year" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none transition-all" 
                               placeholder="{{ date('Y') }}" 
                               value="{{ old('publication_year', date('Y')) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            {{ __('Kategori / Subyek') }}
                        </label>
                        <select name="subjects[]" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none bg-white transition-all">
                            <option value="">{{ __('-- Pilih Kategori (Opsional) --') }}</option>
                            @foreach($subjects as $sub)
                                <option value="{{ $sub->id }}" {{ in_array($sub->id, (array)old('subjects', [])) ? 'selected' : '' }}>{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Abstrak / Ringkasan --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        {{ __('Sinopsis / Catatan Ringkas') }}
                    </label>
                    <textarea name="abstract" 
                              rows="3" 
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 text-sm outline-none transition-all" 
                              placeholder="{{ __('Deskripsi atau sinopsis singkat buku digital ini...') }}">{{ old('abstract') }}</textarea>
                </div>

                {{-- Catatan Kemudahan --}}
                <div class="p-3 bg-emerald-50/70 border border-emerald-200/80 rounded-xl text-xs text-emerald-800 flex items-start gap-2">
                    <i class="bi bi-info-circle-fill text-emerald-600 mt-0.5"></i>
                    <span>{{ __('Tidak perlu mengisi nomor panggil, DDC, rak, maupun nomor eksemplar fisik. Buku digital langsung dapat dibaca oleh seluruh siswa secara online.') }}</span>
                </div>

                <div class="pt-3 flex justify-end gap-3">
                    <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center text-sm font-medium rounded-xl text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors py-2.5 px-6">
                        {{ __('Batal') }}
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center text-sm font-bold rounded-xl btn-gradient-blue transition-all gap-2 py-2.5 px-8 shadow-md hover:shadow-lg cursor-pointer text-white">
                        <i class="bi bi-cloud-arrow-up-fill"></i> {{ __('Simpan Buku Digital') }}
                    </button>
                </div>

            </div>

        </div>
    </form>
</div>


{{-- ═══════════════════════════════════════════════════════════════════ --}}
{{-- 2. SECTION FORM BUKU FISIK (KATALOGISASI LENGKAP)                   --}}
{{-- ═══════════════════════════════════════════════════════════════════ --}}
<div id="physicalBookSection" class="hidden bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
    <div class="px-8 border-b border-slate-200 bg-slate-50 font-medium text-slate-700 py-4 flex items-center justify-between">
        <span class="flex items-center gap-2"><i class="bi bi-book text-indigo-600"></i>{{ __('Form Data Bibliografi Buku Fisik') }}</span>
        <span class="text-xs text-slate-400">{{ __('Untuk koleksi buku fisik yang disimpan di rak perpustakaan') }}</span>
    </div>
    <div class="p-8">

        <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="flex flex-wrap -mx-3">
                {{-- Left Column --}}
                <div class="w-full md:w-1/2 px-4">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Judul Utama') }} <span class="text-red-600">*</span></label>
                        <input type="text" name="title" class="@error('title') @enderror w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none border-red-500 focus:border-red-500 focus:ring-red-500 py-2 px-4" value="{{ old('title') }}" placeholder="{{ __('Contoh: Pemrograman Web dengan Laravel') }}">
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
                            <select name="collection_type" id="collectionTypeSelect" class="w-full rounded-lg border border-slate-200 border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white py-2 px-4" required>
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
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Cover Sampul Buku (Opsional)') }}</label>
                        <input type="file" name="cover_image" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" accept="image/*">
                    </div>
                </div>

                {{-- Full width --}}
                <div class="w-full px-4">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Abstrak / Deskripsi Singkat') }}</label>
                        <textarea name="abstract" class="w-full rounded-lg border border-slate-300 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none py-2 px-4" rows="3" placeholder="{{ __('Deskripsi singkat isi buku...') }}">{{ old('abstract') }}</textarea>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100 my-4">

            <div class="justify-end flex gap-2">
                <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors gap-2 py-2.5 px-6">{{ __('Batal') }}</a>
                <button type="submit" class="inline-flex items-center justify-center text-sm font-semibold rounded-lg btn-gradient-green hover:opacity-95 shadow-md shadow-emerald-500/20 text-white gap-2 py-2.5 px-6 transition-all transform hover:-translate-y-0.5">
                    <i class="bi bi-check-circle-fill text-base"></i>{{ __('Simpan Buku Fisik') }}
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
