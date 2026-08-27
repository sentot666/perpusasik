@extends('layouts.app')

@section('title', __('Peminjaman Buku'))
@section('page-title', __('Proses Peminjaman'))


@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ __('Proses Peminjaman') }}</h1>
    <p class="text-slate-500 text-sm">{{ __('Scan atau cari anggota dan barcode buku untuk memproses peminjaman') }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    {{-- Form --}}
    <div class="lg:col-span-7">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 border-b border-slate-200 font-semibold text-slate-700 bg-slate-50/50 flex items-center py-6">
                <i class="bi bi-box-arrow-right text-indigo-500 text-lg mr-2"></i> {{ __('Form Peminjaman') }}
            </div>
            <div class="p-8">

                @if(session('success'))
                <div class="bg-emerald-50 border border-slate-200 border-emerald-200 text-emerald-800 rounded-lg text-sm flex items-center mb-6 py-4 px-6">
                    <i class="bi bi-check-circle-fill text-emerald-500 mr-2 text-lg"></i> {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="bg-red-50 border border-slate-200 border-red-200 text-red-800 rounded-lg text-sm flex items-center mb-6 py-4 px-6">
                    <i class="bi bi-exclamation-triangle-fill text-red-500 mr-2 text-lg"></i> {{ session('error') }}
                </div>
                @endif
                @if($errors->any())
                <div class="bg-red-50 border border-slate-200 border-red-200 text-red-800 rounded-lg text-sm mb-6 py-4 px-6">
                    <ul class="list-disc pl-5 m-0 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('circulations.store-loan') }}" id="loanForm" class="space-y-5">
                    @csrf

                    {{-- Member Search --}}
                    <div class="relative">
                        <label for="memberSearch" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Anggota') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="bi bi-person-badge"></i>
                            </span>
                            <input type="text" id="memberSearch" class="w-full pl-10 pr-4 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition py-2" placeholder="{{ __('Ketik nama atau kode anggota...') }}" autocomplete="off">
                        </div>
                        <div id="memberDropdown" class="absolute z-50 w-full bg-white border border-slate-200 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden mt-1"></div>
                        <input type="hidden" name="member_id" id="memberId">
                    </div>

                    {{-- Selected Member Card --}}
                    <div id="memberCard" class="hidden bg-blue-50 border border-slate-200 border-blue-200 rounded-lg text-sm p-6">
                        <div class="flex items-center gap-6">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                                <i class="bi bi-person-check-fill text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-blue-900 truncate" id="memberCardName"></div>
                                <div class="text-blue-700 text-xs truncate" id="memberCardInfo"></div>
                            </div>
                            <button type="button" class="p-1.5 text-blue-400 hover:text-blue-600 hover:bg-blue-100 rounded-md transition" id="clearMember">
                                <i class="bi bi-x text-lg"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Barcode Input --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">{{ __('Barcode Buku') }} <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Left: Scanner -->
                            <div class="relative rounded-lg shadow-sm">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                                    <i class="bi bi-upc-scan"></i>
                                </span>
                                <input type="text" id="barcodeScanInput" class="block w-full pl-10 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition py-2 px-4" placeholder="{{ __('Scan barcode...') }}" autocomplete="off">
                            </div>
                            <!-- Right: Manual -->
                            <div class="flex relative rounded-lg shadow-sm">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                                    <i class="bi bi-keyboard"></i>
                                </span>
                                <input type="text" id="barcodeManualInput" class="flex-1 min-w-0 block w-full pl-10 bg-slate-50 border border-slate-200 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition py-2 px-4" placeholder="{{ __('Ketik manual...') }}" autocomplete="off">
                                <button type="button" class="inline-flex items-center border border-slate-200 border-l-0 rounded-r-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition py-2 px-6" id="lookupBarcodeBtn">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Hidden inputs for book item IDs will be injected dynamically by JS -->
                        <div id="hiddenBookInputs"></div>
                    </div>

                    {{-- Selected Books Container --}}
                    <div id="bookListContainer" class="hidden space-y-3">
                        <!-- Book cards will be appended here -->
                    </div>

                    <div>
                        <label for="notesInput" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Catatan') }}</label>
                        <textarea name="notes" id="notesInput" class="w-full bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition resize-none py-2 px-4" rows="2" placeholder="{{ __('Opsional...') }}"></textarea>
                    </div>

                    <button type="submit" class="w-full flex items-center justify-center py-2.5 rounded-lg shadow-sm text-sm font-semibold focus:outline-none transition text-white gap-2 px-6" id="submitLoan" disabled style="background: linear-gradient(to right, #38BDF8, #3B82F6); opacity:0.5; cursor:not-allowed;">
                        <i class="bi bi-box-arrow-right"></i> {{ __('Proses Peminjaman') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Form Peminjaman Kelas --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mt-6">
            <div class="px-8 border-b border-slate-200 font-semibold text-slate-700 bg-slate-50/50 flex items-center py-6">
                <i class="bi bi-people-fill text-indigo-500 text-lg mr-2"></i> {{ __('Form Peminjaman Kelas / Kelompok') }}
            </div>
            <div class="p-8">
                <form method="POST" action="{{ route('circulations.store-class-loan') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="borrower_name" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Nama Peminjam') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="borrower_name" id="borrower_name" class="w-full bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition py-2 px-4" required>
                    </div>
                    <div>
                        <label for="origin" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Asal (Kelas/Instansi)') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="origin" id="origin" class="w-full bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition py-2 px-4" required>
                    </div>
                    <div>
                        <label for="book_type" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Jenis Buku') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="book_type" id="book_type" class="w-full bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition py-2 px-4" required>
                    </div>
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Jumlah Pinjam') }} <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity" id="quantity" min="1" class="w-full bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition py-2 px-4" required>
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Catatan') }}</label>
                        <textarea name="notes" id="notes" class="w-full bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition resize-none py-2 px-4" rows="2"></textarea>
                    </div>
                    <button type="submit" class="w-full flex items-center justify-center py-2.5 rounded-lg shadow-sm text-sm font-semibold focus:outline-none transition text-white gap-2 px-6" style="background: linear-gradient(to right, #3B82F6, #2563EB)">
                        <i class="bi bi-box-arrow-right"></i> {{ __('Proses Peminjaman Kelas') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Info Panel --}}
    <div class="lg:col-span-5">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 border-b border-slate-200 font-semibold text-slate-700 bg-slate-50/50 flex items-center py-6">
                <i class="bi bi-info-circle text-sky-500 text-lg mr-2"></i> {{ __('Panduan') }}
            </div>
            <div class="p-8">
                <ol class="list-decimal pl-5 space-y-2 text-sm text-slate-600">
                    <li>{{ __('Cari anggota dengan nama atau kode anggota') }}</li>
                    <li>{{ __('Scan barcode eksemplar buku yang akan dipinjam') }}</li>
                    <li>{{ __('Pastikan data anggota dan buku sudah benar') }}</li>
                    <li>{{ __('Klik tombol') }} <strong class="font-semibold text-slate-800">{{ __('Proses Peminjaman') }}</strong></li>
                </ol>
                
                <div class="my-8 border-t border-slate-100"></div>
                
                <div class="flex flex-wrap gap-2">
                    <div class="inline-flex items-center gap-1.5 py-1.5 bg-slate-100 border border-slate-200 rounded-lg text-xs text-slate-700 px-4">
                        <i class="bi bi-calendar text-slate-500"></i>
                        <span>{{ __('Durasi Pinjam') }}: <strong class="font-semibold text-slate-900">{{ \App\Models\Setting::get('loan_duration', 14) }} {{ __('hari') }}</strong></span>
                    </div>
                    <div class="inline-flex items-center gap-1.5 py-1.5 bg-slate-100 border border-slate-200 rounded-lg text-xs text-slate-700 px-4">
                        <i class="bi bi-stack text-slate-500"></i>
                        <span>{{ __('Maks Pinjam') }}: <strong class="font-semibold text-slate-900">{{ \App\Models\Setting::get('max_loan_items', 3) }} {{ __('eksemplar') }}</strong></span>
                    </div>
                    <div class="inline-flex items-center gap-1.5 py-1.5 bg-slate-100 border border-slate-200 rounded-lg text-xs text-slate-700 px-4">
                        <i class="bi bi-cash text-slate-500"></i>
                        <span>{{ __('Denda') }}: <strong class="font-semibold text-slate-900">Rp {{ number_format(\App\Models\Setting::get('fine_per_day', 1000)) }}/{{ __('hari') }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const memberSearch  = document.getElementById('memberSearch');
const memberDropdown= document.getElementById('memberDropdown');
const memberCard    = document.getElementById('memberCard');
const memberIdInput = document.getElementById('memberId');
const barcodeScanInput = document.getElementById('barcodeScanInput');
const barcodeManualInput = document.getElementById('barcodeManualInput');
const hiddenBookInputs = document.getElementById('hiddenBookInputs');
const bookListContainer = document.getElementById('bookListContainer');
const submitBtn     = document.getElementById('submitLoan');

let selectedBooks = [];

function checkSubmit() {
    const ready = !!(memberIdInput.value && selectedBooks.length > 0);
    submitBtn.disabled = !ready;
    submitBtn.style.opacity = ready ? '1' : '0.5';
    submitBtn.style.cursor = ready ? 'pointer' : 'not-allowed';
    submitBtn.style.background = ready
        ? 'linear-gradient(to right, #3B82F6, #2563EB)'
        : 'linear-gradient(to right, #38BDF8, #3B82F6)';
}

// Render book list
function renderBookList() {
    hiddenBookInputs.innerHTML = '';
    bookListContainer.innerHTML = '';
    
    if (selectedBooks.length === 0) {
        bookListContainer.classList.add('hidden');
        return;
    }
    
    selectedBooks.forEach((book, index) => {
        // Add hidden input
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'book_item_ids[]';
        input.value = book.id;
        hiddenBookInputs.appendChild(input);
        
        // Add card
        const card = document.createElement('div');
        card.className = 'bg-emerald-50 border border-slate-200 border-emerald-200 rounded-lg text-sm p-4 flex items-start gap-4';
        card.innerHTML = `
            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                <i class="bi bi-book-fill text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-semibold text-emerald-900 truncate">${book.book.title}</div>
                <div class="text-emerald-700 text-xs truncate">${book.book.author || ''}</div>
                <div class="mt-1 font-mono text-xs bg-emerald-100 text-emerald-800 py-0.5 rounded inline-block px-2">${book.barcode}</div>
            </div>
            <button type="button" class="p-1 text-emerald-400 hover:text-emerald-600 hover:bg-emerald-100 rounded-md transition shrink-0" onclick="removeBook(${index})">
                <i class="bi bi-x text-lg"></i>
            </button>
        `;
        bookListContainer.appendChild(card);
    });
    
    bookListContainer.classList.remove('hidden');
    checkSubmit();
}

window.removeBook = function(index) {
    selectedBooks.splice(index, 1);
    renderBookList();
};

// ... Member search logic remains mostly same
let memberTimer;
memberSearch.addEventListener('input', function() {
    clearTimeout(memberTimer);
    const q = this.value.trim();
    if (q.length < 2) { 
        memberDropdown.classList.add('hidden'); 
        return; 
    }
    memberTimer = setTimeout(() => {
        fetch('{{ route("members.ajax-search") }}?q=' + encodeURIComponent(q))
            .then(r => r.json())
            .then(data => {
                memberDropdown.innerHTML = '';
                if (data.length === 0) {
                    memberDropdown.innerHTML = '<div class="text-sm text-slate-500 text-center py-4 px-6">{{ __('Tidak ada hasil') }}</div>';
                } else {
                    data.forEach(m => {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'w-full text-left px-4 py-2 hover:bg-slate-50 focus:bg-slate-50 focus:outline-none transition-colors border-b border-slate-50 last:border-0';
                        item.innerHTML = `
                            <div class="flex items-baseline justify-between mb-0.5">
                                <span class="text-sm font-semibold text-slate-800">${m.name}</span>
                                <span class="text-xs text-slate-400 font-mono">${m.member_code}</span>
                            </div>
                            <div class="text-xs text-slate-500">${m.member_type} &bull; ${m.status}</div>
                        `;
                        item.addEventListener('click', () => selectMember(m));
                        memberDropdown.appendChild(item);
                    });
                }
                memberDropdown.classList.remove('hidden');
            });
    }, 300);
});

memberSearch.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const q = this.value.trim();
        if (q.length < 2) return;
        fetch('{{ route("members.ajax-search") }}?q=' + encodeURIComponent(q))
            .then(r => r.json())
            .then(data => {
                if (data.length === 1) {
                    selectMember(data[0]);
                } else if (data.length > 1) {
                    memberDropdown.innerHTML = '';
                    data.forEach(m => {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'w-full text-left px-4 py-2 hover:bg-slate-50 focus:bg-slate-50 focus:outline-none transition-colors border-b border-slate-50 last:border-0';
                        item.innerHTML = `
                            <div class="flex items-baseline justify-between mb-0.5">
                                <span class="text-sm font-semibold text-slate-800">${m.name}</span>
                                <span class="text-xs text-slate-400 font-mono">${m.member_code}</span>
                            </div>
                            <div class="text-xs text-slate-500">${m.member_type} &bull; ${m.status}</div>
                        `;
                        item.addEventListener('click', () => selectMember(m));
                        memberDropdown.appendChild(item);
                    });
                    memberDropdown.classList.remove('hidden');
                }
            });
    }
});

function selectMember(m) {
    memberIdInput.value = m.id;
    memberSearch.value = m.name;
    memberDropdown.classList.add('hidden');
    document.getElementById('memberCardName').textContent = `${m.name} (${m.member_code})`;
    document.getElementById('memberCardInfo').textContent = `${m.member_type} • ${m.status}`;
    memberCard.classList.remove('hidden');
    checkSubmit();
    setTimeout(() => barcodeScanInput.focus(), 100);
}

document.getElementById('clearMember').addEventListener('click', () => {
    memberIdInput.value = '';
    memberSearch.value = '';
    memberCard.classList.add('hidden');
    checkSubmit();
});

// Barcode lookup
function lookupBarcode(sourceInput) {
    const barcode = sourceInput.value.trim();
    if (!barcode) return;
    
    // Check if already in cart
    if (selectedBooks.find(b => b.barcode === barcode || b.id == barcode)) {
        sourceInput.value = '';
        if(window.Swal) {
            Swal.fire({
                icon: 'info',
                title: '{{ __('Sudah di Keranjang') }}',
                text: '{{ __('Buku ini sudah ditambahkan ke dalam daftar pinjaman.') }}',
                confirmButtonColor: '#4f46e5'
            });
        }
        return;
    }
    
    // Disable input while searching
    sourceInput.disabled = true;
    document.getElementById('lookupBarcodeBtn').disabled = true;
    
    fetch('{{ route("book-items.ajax-lookup") }}?barcode=' + encodeURIComponent(barcode))
        .then(r => r.json())
        .then(data => {
            if (!data.found) {
                if(window.Swal) {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __('Tidak Ditemukan') }}',
                        text: data.message,
                        confirmButtonColor: '#4f46e5'
                    });
                } else {
                    alert(data.message);
                }
                return;
            }
            if (data.status !== 'Tersedia') {
                if(window.Swal) {
                    Swal.fire({
                        icon: 'warning',
                        title: '{{ __('Tidak Bisa Dipinjam') }}',
                        text: `{{ __('Eksemplar ini berstatus') }}: ${data.status}`,
                        confirmButtonColor: '#4f46e5'
                    });
                } else {
                    alert(`{{ __('Eksemplar ini berstatus') }}: ${data.status}. {{ __('Tidak bisa dipinjam') }}.`);
                }
                return;
            }
            
            // Limit check (frontend side)
            const maxItems = {{ \App\Models\Setting::get('max_loan_items', 3) }};
            if (selectedBooks.length >= maxItems) {
                if(window.Swal) {
                    Swal.fire({
                        icon: 'warning',
                        title: '{{ __('Batas Peminjaman') }}',
                        text: `{{ __('Maksimal hanya dapat meminjam') }} ${maxItems} {{ __('buku sekaligus.') }}`,
                        confirmButtonColor: '#4f46e5'
                    });
                }
                return;
            }

            // Check if THIS specific copy is already added (in case ISBN was scanned)
            if (selectedBooks.find(b => b.id === data.id)) {
                if(window.Swal) {
                    Swal.fire({
                        icon: 'info',
                        title: '{{ __('Sudah di Keranjang') }}',
                        text: '{{ __('Eksemplar buku ini sudah ditambahkan.') }}',
                        confirmButtonColor: '#4f46e5'
                    });
                }
                return;
            }
            
            selectedBooks.push(data);
            sourceInput.value = '';
            renderBookList();
        })
        .finally(() => {
            sourceInput.disabled = false;
            document.getElementById('lookupBarcodeBtn').disabled = false;
            sourceInput.focus();
        });
}

document.getElementById('lookupBarcodeBtn').addEventListener('click', () => lookupBarcode(barcodeManualInput));

// Scanner Input - Fast debounce
let scanTimeout;
barcodeScanInput.addEventListener('input', function(e) {
    clearTimeout(scanTimeout);
    scanTimeout = setTimeout(() => {
        if(barcodeScanInput.value.trim().length > 0) {
            lookupBarcode(barcodeScanInput);
        }
    }, 50);
});
barcodeScanInput.addEventListener('keydown', e => { 
    if (e.key === 'Enter') { 
        e.preventDefault(); 
        clearTimeout(scanTimeout); 
        if(barcodeScanInput.value.trim().length > 0) lookupBarcode(barcodeScanInput);
    } 
});

// Manual Input - Slower debounce
let manualTimeout;
barcodeManualInput.addEventListener('input', function(e) {
    clearTimeout(manualTimeout);
    manualTimeout = setTimeout(() => {
        if(barcodeManualInput.value.trim().length > 0) {
            lookupBarcode(barcodeManualInput);
        }
    }, 1000);
});
barcodeManualInput.addEventListener('keydown', e => { 
    if (e.key === 'Enter') { 
        e.preventDefault(); 
        clearTimeout(manualTimeout); 
        if(barcodeManualInput.value.trim().length > 0) lookupBarcode(barcodeManualInput);
    } 
});

// Close dropdown on outside click
document.addEventListener('click', e => {
    if (!memberSearch.contains(e.target)) memberDropdown.classList.add('hidden');
});
</script>
@endpush

