@extends('layouts.app')

@section('title', 'Peminjaman Buku')
@section('page-title', 'Proses Peminjaman')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('circulations.index') }}">Sirkulasi</a></li>
<li class="breadcrumb-item active">Peminjaman</li>
@endsection

@section('content')
<div class="page-header">
    <h1>Proses Peminjaman</h1>
    <p>Scan atau cari anggota dan barcode buku untuk memproses peminjaman</p>
</div>

<div class="row g-3">

    {{-- Form --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-box-arrow-right me-2 text-primary"></i>Form Peminjaman</div>
            <div class="card-body">

                @if(session('success'))
                <div class="alert alert-success border-0 py-2" style="border-radius:8px">
                    <i class="bi bi-check-circle-fill me-1"></i>{{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger border-0 py-2" style="border-radius:8px">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ session('error') }}
                </div>
                @endif

                <form method="POST" action="{{ route('circulations.store-loan') }}" id="loanForm">
                    @csrf

                    {{-- Member Search --}}
                    <div class="mb-3">
                        <label class="form-label fw-500">Anggota <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                            <input type="text" id="memberSearch" class="form-control" placeholder="Ketik nama atau kode anggota..." autocomplete="off">
                        </div>
                        <div id="memberDropdown" class="dropdown-menu w-100 shadow-sm" style="display:none;position:absolute;z-index:1050;max-height:250px;overflow-y:auto"></div>
                        <input type="hidden" name="member_id" id="memberId">
                    </div>

                    {{-- Selected Member Card --}}
                    <div id="memberCard" class="alert alert-info border-0 py-2 mb-3" style="display:none;border-radius:8px;font-size:0.82rem">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-person-check-fill fs-4"></i>
                            <div>
                                <div class="fw-600" id="memberCardName"></div>
                                <div class="text-muted" id="memberCardInfo"></div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary ms-auto" id="clearMember">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Barcode Input --}}
                    <div class="mb-3">
                        <label class="form-label fw-500">Barcode Buku <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                            <input type="text" id="barcodeInput" class="form-control" placeholder="Scan atau ketik barcode eksemplar..." autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" id="lookupBarcode">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        <input type="hidden" name="book_item_id" id="bookItemId">
                    </div>

                    {{-- Selected Book Card --}}
                    <div id="bookCard" class="alert alert-success border-0 py-2 mb-3" style="display:none;border-radius:8px;font-size:0.82rem">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-book-fill fs-4 text-success"></i>
                            <div>
                                <div class="fw-600" id="bookCardTitle"></div>
                                <div class="text-muted" id="bookCardAuthor"></div>
                                <div id="bookCardCallNumber" class="mt-1"></div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary ms-auto" id="clearBook">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Opsional..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-600" id="submitLoan" disabled>
                        <i class="bi bi-box-arrow-right me-2"></i>Proses Peminjaman
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Info Panel --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><i class="bi bi-info-circle me-2 text-info"></i>Panduan</div>
            <div class="card-body">
                <ol class="ps-3 mb-0" style="font-size:0.85rem;line-height:2">
                    <li>Cari anggota dengan nama atau kode anggota</li>
                    <li>Scan barcode eksemplar buku yang akan dipinjam</li>
                    <li>Pastikan data anggota dan buku sudah benar</li>
                    <li>Klik tombol <strong>Proses Peminjaman</strong></li>
                </ol>
                <hr>
                <div class="d-flex gap-2 flex-wrap">
                    <div class="badge bg-light text-dark border p-2">
                        <i class="bi bi-calendar me-1"></i>
                        Durasi Pinjam: <strong>{{ \App\Models\Setting::get('loan_duration', 14) }} hari</strong>
                    </div>
                    <div class="badge bg-light text-dark border p-2">
                        <i class="bi bi-stack me-1"></i>
                        Maks Pinjam: <strong>{{ \App\Models\Setting::get('max_loan_items', 3) }} eksemplar</strong>
                    </div>
                    <div class="badge bg-light text-dark border p-2">
                        <i class="bi bi-cash me-1"></i>
                        Denda: <strong>Rp {{ number_format(\App\Models\Setting::get('fine_per_day', 1000)) }}/hari</strong>
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
const barcodeInput  = document.getElementById('barcodeInput');
const bookItemInput = document.getElementById('bookItemId');
const bookCard      = document.getElementById('bookCard');
const submitBtn     = document.getElementById('submitLoan');

function checkSubmit() {
    submitBtn.disabled = !(memberIdInput.value && bookItemInput.value);
}

// Member search
let memberTimer;
memberSearch.addEventListener('input', function() {
    clearTimeout(memberTimer);
    const q = this.value.trim();
    if (q.length < 2) { memberDropdown.style.display = 'none'; return; }
    memberTimer = setTimeout(() => {
        fetch('{{ route("members.ajax-search") }}?q=' + encodeURIComponent(q))
            .then(r => r.json())
            .then(data => {
                memberDropdown.innerHTML = '';
                if (data.length === 0) {
                    memberDropdown.innerHTML = '<div class="dropdown-item text-muted py-2">Tidak ada hasil</div>';
                } else {
                    data.forEach(m => {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'dropdown-item py-2';
                        item.innerHTML = `<strong>${m.name}</strong> <small class="text-muted ms-1">${m.member_code}</small><br><small class="text-muted">${m.member_type} • ${m.status}</small>`;
                        item.addEventListener('click', () => selectMember(m));
                        memberDropdown.appendChild(item);
                    });
                }
                memberDropdown.style.display = 'block';
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
                        item.className = 'dropdown-item py-2';
                        item.innerHTML = `<strong>${m.name}</strong> <small class="text-muted ms-1">${m.member_code}</small><br><small class="text-muted">${m.member_type} • ${m.status}</small>`;
                        item.addEventListener('click', () => selectMember(m));
                        memberDropdown.appendChild(item);
                    });
                    memberDropdown.style.display = 'block';
                }
            });
    }
});

function selectMember(m) {
    memberIdInput.value = m.id;
    memberSearch.value = m.name;
    memberDropdown.style.display = 'none';
    document.getElementById('memberCardName').textContent = `${m.name} (${m.member_code})`;
    document.getElementById('memberCardInfo').textContent = `${m.member_type} • ${m.status}`;
    memberCard.style.display = 'block';
    checkSubmit();
    setTimeout(() => barcodeInput.focus(), 100);
}

document.getElementById('clearMember').addEventListener('click', () => {
    memberIdInput.value = '';
    memberSearch.value = '';
    memberCard.style.display = 'none';
    checkSubmit();
});

// Barcode lookup
function lookupBarcode() {
    const barcode = barcodeInput.value.trim();
    if (!barcode) return;
    fetch('{{ route("book-items.ajax-lookup") }}?barcode=' + encodeURIComponent(barcode))
        .then(r => r.json())
        .then(data => {
            if (!data.found) {
                alert(data.message);
                return;
            }
            if (data.status !== 'Tersedia') {
                alert(`Eksemplar ini berstatus: ${data.status}. Tidak bisa dipinjam.`);
                return;
            }
            bookItemInput.value = data.id;
            document.getElementById('bookCardTitle').textContent = data.book.title;
            document.getElementById('bookCardAuthor').textContent = data.book.author || '';
            document.getElementById('bookCardCallNumber').innerHTML = `<code>${data.book.call_number || ''}</code>`;
            bookCard.style.display = 'block';
            checkSubmit();
        });
}

document.getElementById('lookupBarcode').addEventListener('click', lookupBarcode);
barcodeInput.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); lookupBarcode(); } });

document.getElementById('clearBook').addEventListener('click', () => {
    bookItemInput.value = '';
    barcodeInput.value = '';
    bookCard.style.display = 'none';
    checkSubmit();
});

// Close dropdown on outside click
document.addEventListener('click', e => {
    if (!memberSearch.contains(e.target)) memberDropdown.style.display = 'none';
});
</script>
@endpush
