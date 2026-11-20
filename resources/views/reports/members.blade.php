@extends('layouts.app')

@section('title', __('Laporan Anggota'))
@section('page-title', __('Laporan Anggota'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('reports.index') }}">{{ __('Laporan') }}</a></li>
<li class="breadcrumb-item active">{{ __('Anggota') }}</li>
@endsection

@section('content')
{{-- Print-only Formal Header --}}
<div class="hidden print-header mb-6">
    <div style="border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 16px; text-align: center;">
        <h2 style="font-size: 15pt; font-weight: 800; text-transform: uppercase; margin: 0; color: #000; letter-spacing: 0.5px;">
            {{ \App\Models\Setting::get('library_name') ?: 'SEKOLAH KATOLIK ST.PAULUS' }}
        </h2>
        <p style="font-size: 8.5pt; color: #333; margin: 3px 0 0 0;">
            {{ \App\Models\Setting::get('library_address') ?: 'Jl. Danau Agung 13, Sunter Agung, Tanjung Priok, Kota Jakarta Utara, DKI Jakarta 14350' }}
        </p>
        <h3 style="font-size: 12pt; font-weight: 700; text-transform: uppercase; margin: 10px 0 0 0; color: #000; letter-spacing: 0.5px;">
            {{ __('Laporan Data Anggota') }}
        </h3>
    </div>
</div>

<div class="page-header justify-between items-start flex flex-wrap gap-4 mb-4">
    <div>
        <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ __('Laporan Data Anggota') }}</h1>
        <p class="text-slate-500 text-sm">{{ __('Seluruh daftar anggota perpustakaan yang terdaftar') }}</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        {{-- Export Button (updates with selected ids if any) --}}
        <a id="btn-export-excel" href="{{ route('reports.export', ['type' => 'members']) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-green text-white transition-colors gap-2 py-2 px-5 shadow-sm">
            <i class="bi bi-file-earmark-excel text-base"></i> <span id="export-text">{{ __('Export Spreadsheet') }}</span>
        </a>

        {{-- Dynamic Print Selected Button --}}
        <button type="button" id="btn-print-selected" onclick="printSelected()" class="inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-blue text-white transition-all gap-2 py-2 px-5 shadow-sm opacity-60 cursor-not-allowed" disabled>
            <i class="bi bi-check2-square text-base"></i>
            <span>{{ __('Cetak Terpilih') }}</span>
            <span id="selected-badge-count" class="hidden ml-1 px-2 py-0.5 rounded-full text-xs bg-white text-indigo-700 font-bold">0</span>
        </button>

        {{-- Print All Button --}}
        <button type="button" onclick="printAll()" class="inline-flex items-center justify-center text-sm font-medium rounded-lg bg-slate-800 hover:bg-slate-900 text-white transition-colors gap-2 py-2 px-5 shadow-sm">
            <i class="bi bi-printer text-base"></i> {{ __('Cetak Semua') }}
        </button>

        {{-- Back Button --}}
        <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 transition-colors gap-2 py-2 px-4 shadow-sm">
            <i class="bi bi-arrow-left"></i> {{ __('Kembali') }}
        </a>
    </div>
</div>

{{-- Search & Selection Status Bar --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        {{-- Quick Search Filter --}}
        <div class="relative w-full md:w-80">
            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" id="table-search" placeholder="{{ __('Cari nama, kode, email, kota...') }}" class="w-full pl-9 pr-8 py-2 text-sm rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
            <button type="button" id="clear-search" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                <i class="bi bi-x-circle-fill text-xs"></i>
            </button>
        </div>

        {{-- Selection Status Indicator --}}
        <div id="selection-status-box" class="flex items-center gap-3">
            <div id="selection-counter-wrapper" class="hidden items-center gap-2 px-3 py-1.5 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-800 text-xs font-semibold">
                <i class="bi bi-check2-circle text-sm text-indigo-600"></i>
                <span><strong id="counter-val">0</strong> anggota dipilih</span>
                <button type="button" onclick="deselectAll()" class="ml-2 text-slate-500 hover:text-red-600 font-normal underline transition-colors">
                    {{ __('Batal') }}
                </button>
            </div>

            <div class="text-xs text-slate-500">
                {{ __('Total:') }} <strong class="text-slate-800" id="total-rows-info">{{ $members->count() }}</strong> {{ __('anggota') }}
            </div>
        </div>
    </div>
</div>

{{-- Main Table --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-0">
        <div class="overflow-x-auto w-full">
            <table id="members-table" class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50 transition-colors">
                <thead>
                    <tr>
                        <th class="w-12 text-center no-print">
                            <input type="checkbox" id="check-all" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500 cursor-pointer transition-all" title="{{ __('Pilih / Batalkan Semua') }}">
                        </th>
                        <th class="w-12 text-center">#</th>
                        <th>{{ __('Kode Anggota') }}</th>
                        <th>{{ __('Nama Anggota') }}</th>
                        <th>{{ __('Jenis Kelamin') }}</th>
                        <th>{{ __('Tipe Anggota') }}</th>
                        <th>{{ __('No. Telepon / Email') }}</th>
                        <th>{{ __('Kota') }}</th>
                        <th>{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody id="members-tbody">
                    @forelse($members as $m)
                    <tr class="member-row transition-colors" data-id="{{ $m->id }}">
                        <td class="text-center no-print">
                            <input type="checkbox" class="member-checkbox w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500 cursor-pointer" value="{{ $m->id }}">
                        </td>
                        <td class="row-number text-center text-slate-500 font-mono text-xs">{{ $loop->iteration }}</td>
                        <td><code>{{ $m->member_code }}</code></td>
                        <td class="font-semibold text-slate-800">{{ $m->name }}</td>
                        <td>{{ $m->gender == 'L' ? __('Laki-laki') : ($m->gender == 'P' ? __('Perempuan') : '-') }}</td>
                        <td>{{ $m->member_type }}</td>
                        <td>{{ $m->phone ?? '-' }} / {{ $m->email ?? '-' }}</td>
                        <td>{{ $m->city ?? '-' }}</td>
                        <td>
                            <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-{{ $m->status_badge_class }}">{{ $m->status_label }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr id="empty-row">
                        <td colspan="9" class="text-center text-slate-500 py-8">{{ __('Belum ada data anggota') }}</td>
                    </tr>
                    @endforelse
                    <tr id="no-search-results" class="hidden">
                        <td colspan="9" class="text-center text-slate-500 py-8">
                            <i class="bi bi-search text-2xl block mb-2 text-slate-300"></i>
                            {{ __('Tidak ada data yang cocok dengan pencarian') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Active selected row highlight on screen */
tr.row-is-checked {
    background-color: #f5f3ff !important; /* Soft indigo-50 */
}

@media print {
    /* Hide layout chrome and non-print elements */
    #sidebar, .topbar, .page-header, .bg-white.p-4, #selection-status-box, 
    nav[aria-label="breadcrumb"], footer, .modal, .pagination, .no-print {
        display: none !important;
    }

    /* Show formal print header */
    .print-header {
        display: block !important;
    }

    /* Full printable canvas */
    html, body {
        background: #ffffff !important;
        color: #000000 !important;
        margin: 0 !important;
        font-family: Arial, Helvetica, sans-serif !important;
    }

    body {
        padding: 12mm 10mm !important;
        box-sizing: border-box !important;
    }

    .main-wrapper, .content-area, .content {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }

    .bg-white, .shadow-sm, .rounded-xl, .border {
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    /* Print table styling */
    table {
        width: 100% !important;
        border-collapse: collapse !important;
        font-size: 8.5pt !important;
        page-break-inside: auto !important;
    }

    table tr {
        page-break-inside: avoid !important;
        page-break-after: auto !important;
    }

    table th, table td {
        border: 1px solid #222222 !important;
        padding: 4px 6px !important;
        color: #000000 !important;
        font-size: 8.5pt !important;
    }

    table thead th {
        background-color: #f1f5f9 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        font-weight: 700 !important;
        text-align: left;
    }

    table thead th.text-center, table td.text-center {
        text-align: center !important;
    }

    /* When printing only selected data */
    body.print-selected-mode tr.member-row.row-print-hidden {
        display: none !important;
    }

    /* Remove badges styling in print */
    span.rounded {
        background: transparent !important;
        color: #000000 !important;
        padding: 0 !important;
        font-weight: normal !important;
    }

    code {
        background: transparent !important;
        border: none !important;
        color: #000000 !important;
        font-family: inherit !important;
    }

    @page {
        size: A4 landscape;
        margin: 0;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('check-all');
    const memberCheckboxes = document.querySelectorAll('.member-checkbox');
    const btnPrintSelected = document.getElementById('btn-print-selected');
    const selectedBadgeCount = document.getElementById('selected-badge-count');
    const selectionCounterWrapper = document.getElementById('selection-counter-wrapper');
    const counterVal = document.getElementById('counter-val');
    const btnExportExcel = document.getElementById('btn-export-excel');
    const exportText = document.getElementById('export-text');
    const tableSearch = document.getElementById('table-search');
    const clearSearch = document.getElementById('clear-search');
    const printMetaText = document.getElementById('print-meta-text');
    const baseExportUrl = "{{ route('reports.export', ['type' => 'members']) }}";

    function getSelectedCheckboxes() {
        return Array.from(document.querySelectorAll('.member-checkbox:checked'));
    }

    function getVisibleRows() {
        return Array.from(document.querySelectorAll('.member-row:not(.search-hidden)'));
    }

    function updateSelectionUI() {
        const selected = getSelectedCheckboxes();
        const selectedCount = selected.length;
        const visibleRows = getVisibleRows();
        const visibleCount = visibleRows.length;

        // Counter text & badges
        if (counterVal) counterVal.textContent = selectedCount;
        if (selectedBadgeCount) selectedBadgeCount.textContent = selectedCount;

        if (selectedCount > 0) {
            // Enable print selected button
            btnPrintSelected.removeAttribute('disabled');
            btnPrintSelected.classList.remove('opacity-60', 'cursor-not-allowed');
            btnPrintSelected.classList.add('hover:opacity-95');
            selectedBadgeCount.classList.remove('hidden');
            selectionCounterWrapper.classList.remove('hidden');
            selectionCounterWrapper.classList.add('inline-flex');

            // Update Export button to export selected ids
            const selectedIds = selected.map(cb => cb.value).join(',');
            btnExportExcel.href = baseExportUrl + '?ids=' + encodeURIComponent(selectedIds);
            exportText.textContent = `Export Terpilih (${selectedCount})`;
        } else {
            // Disable print selected button
            btnPrintSelected.setAttribute('disabled', 'disabled');
            btnPrintSelected.classList.add('opacity-60', 'cursor-not-allowed');
            btnPrintSelected.classList.remove('hover:opacity-95');
            selectedBadgeCount.classList.add('hidden');
            selectionCounterWrapper.classList.add('hidden');
            selectionCounterWrapper.classList.remove('inline-flex');

            // Reset Export button
            btnExportExcel.href = baseExportUrl;
            exportText.textContent = '{{ __("Export Spreadsheet") }}';
        }

        // Master check-all state
        if (visibleCount === 0) {
            checkAll.checked = false;
            checkAll.indeterminate = false;
        } else {
            const visibleCheckboxes = visibleRows.map(r => r.querySelector('.member-checkbox')).filter(Boolean);
            const visibleCheckedCount = visibleCheckboxes.filter(cb => cb.checked).length;

            if (visibleCheckedCount === visibleCount) {
                checkAll.checked = true;
                checkAll.indeterminate = false;
            } else if (visibleCheckedCount > 0) {
                checkAll.checked = false;
                checkAll.indeterminate = true;
            } else {
                checkAll.checked = false;
                checkAll.indeterminate = false;
            }
        }
    }

    // Toggle row checked styling
    function syncRowHighlight(cb) {
        const row = cb.closest('.member-row');
        if (!row) return;
        if (cb.checked) {
            row.classList.add('row-is-checked');
        } else {
            row.classList.remove('row-is-checked');
        }
    }

    // Check-all checkbox change
    if (checkAll) {
        checkAll.addEventListener('change', function () {
            const isChecked = this.checked;
            getVisibleRows().forEach(row => {
                const cb = row.querySelector('.member-checkbox');
                if (cb) {
                    cb.checked = isChecked;
                    syncRowHighlight(cb);
                }
            });
            updateSelectionUI();
        });
    }

    // Individual checkbox change
    memberCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            syncRowHighlight(this);
            updateSelectionUI();
        });
    });

    // Deselect all
    window.deselectAll = function () {
        memberCheckboxes.forEach(cb => {
            cb.checked = false;
            syncRowHighlight(cb);
        });
        if (checkAll) {
            checkAll.checked = false;
            checkAll.indeterminate = false;
        }
        updateSelectionUI();
    };

    // Client-side quick filter
    if (tableSearch) {
        tableSearch.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            clearSearch.classList.toggle('hidden', query.length === 0);

            let visibleMatches = 0;
            const rows = document.querySelectorAll('.member-row');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(query)) {
                    row.classList.remove('search-hidden', 'hidden');
                    visibleMatches++;
                } else {
                    row.classList.add('search-hidden', 'hidden');
                }
            });

            const noResultsRow = document.getElementById('no-search-results');
            if (noResultsRow) {
                noResultsRow.classList.toggle('hidden', visibleMatches > 0 || rows.length === 0);
            }

            const totalRowsInfo = document.getElementById('total-rows-info');
            if (totalRowsInfo) {
                totalRowsInfo.textContent = query.length > 0 ? `${visibleMatches} dari ${rows.length}` : rows.length;
            }

            updateSelectionUI();
        });

        clearSearch.addEventListener('click', function () {
            tableSearch.value = '';
            tableSearch.dispatchEvent(new Event('input'));
            tableSearch.focus();
        });
    }

    let origDocTitle = document.title;
    function hideDocTitleForPrint() {
        origDocTitle = document.title;
        document.title = ' ';
    }
    function restoreDocTitle() {
        document.title = origDocTitle;
    }

    // Print Selected
    window.printSelected = function () {
        const selected = getSelectedCheckboxes();
        if (selected.length === 0) {
            alert('Silakan pilih minimal satu anggota dengan mencentang kotak checkbox terlebih dahulu.');
            return;
        }

        // Set mode
        document.body.classList.add('print-selected-mode');

        // Filter visible rows in print and renumber sequentially
        let counter = 1;
        document.querySelectorAll('.member-row').forEach(row => {
            const cb = row.querySelector('.member-checkbox');
            const numEl = row.querySelector('.row-number');

            if (cb && cb.checked) {
                row.classList.remove('row-print-hidden');
                if (numEl) {
                    numEl.dataset.origNum = numEl.textContent;
                    numEl.textContent = counter++;
                }
            } else {
                row.classList.add('row-print-hidden');
            }
        });

        // Hide title text for browser header suppression
        hideDocTitleForPrint();

        // Print dialog
        window.print();
    };

    // Print All
    window.printAll = function () {
        document.body.classList.remove('print-selected-mode');

        // Restore original numbering
        document.querySelectorAll('.member-row').forEach(row => {
            row.classList.remove('row-print-hidden');
            const numEl = row.querySelector('.row-number');
            if (numEl && numEl.dataset.origNum) {
                numEl.textContent = numEl.dataset.origNum;
            }
        });

        // Hide title text for browser header suppression
        hideDocTitleForPrint();

        window.print();
    };

    // Clean up after print dialog closes
    window.addEventListener('afterprint', function () {
        document.body.classList.remove('print-selected-mode');
        restoreDocTitle();

        document.querySelectorAll('.member-row').forEach(row => {
            row.classList.remove('row-print-hidden');
            const numEl = row.querySelector('.row-number');
            if (numEl && numEl.dataset.origNum) {
                numEl.textContent = numEl.dataset.origNum;
            }
        });
    });
});
</script>
@endpush
