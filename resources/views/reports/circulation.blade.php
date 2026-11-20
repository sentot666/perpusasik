@extends('layouts.app')

@section('title', __('Laporan Sirkulasi'))
@section('page-title', __('Laporan Sirkulasi'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('reports.index') }}">{{ __('Laporan') }}</a></li>
<li class="breadcrumb-item active">{{ __('Sirkulasi') }}</li>
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
        <h3 style="font-size: 12pt; font-weight: 700; text-transform: uppercase; margin: 10px 0 3px 0; color: #000; letter-spacing: 0.5px;">
            {{ __('Laporan Transaksi Sirkulasi Buku') }}
        </h3>
        <p style="font-size: 8.5pt; color: #333; margin: 0;">
            {{ __('Rentang Tanggal') }}: <strong>{{ date('d/m/Y', strtotime($startDate)) }}</strong> s/d <strong>{{ date('d/m/Y', strtotime($endDate)) }}</strong>
        </p>
    </div>
</div>

<div class="page-header justify-between items-start flex flex-wrap gap-4 mb-4">
    <div>
        <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ __('Laporan Sirkulasi Buku') }}</h1>
        <p class="text-slate-500 text-sm">{{ __('Rentang tanggal') }}: <strong>{{ date('d/m/Y', strtotime($startDate)) }}</strong> s/d <strong>{{ date('d/m/Y', strtotime($endDate)) }}</strong></p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        {{-- Export Button (updates with selected ids if any) --}}
        <a id="btn-export-excel" href="{{ route('reports.export', ['type' => 'circulation'] + request()->all()) }}" class="inline-flex items-center justify-center text-sm font-medium rounded-lg btn-gradient-green text-white transition-colors gap-2 py-2 px-5 shadow-sm">
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

{{-- Date Filter Form --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-4 p-4">
    <form method="GET" class="items-end flex flex-wrap gap-4">
        <div class="w-full sm:w-auto">
            <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase">{{ __('Mulai Tanggal') }}</label>
            <input type="date" name="start_date" class="w-full sm:w-44 rounded-lg border border-slate-200 px-3 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none" value="{{ $startDate }}">
        </div>
        <div class="w-full sm:w-auto">
            <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase">{{ __('Sampai Tanggal') }}</label>
            <input type="date" name="end_date" class="w-full sm:w-44 rounded-lg border border-slate-200 px-3 py-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none" value="{{ $endDate }}">
        </div>
        <div>
            <button type="submit" class="inline-flex items-center justify-center gap-1.5 py-2 px-4 text-xs font-medium rounded-lg btn-gradient-blue transition-colors text-white shadow-sm">
                <i class="bi bi-funnel"></i> {{ __('Filter Periode') }}
            </button>
        </div>
    </form>
</div>

{{-- Search & Selection Status Bar --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        {{-- Quick Search Filter --}}
        <div class="relative w-full md:w-80">
            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" id="table-search" placeholder="{{ __('Cari kode trx, nama, buku, barcode...') }}" class="w-full pl-9 pr-8 py-2 text-sm rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
            <button type="button" id="clear-search" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                <i class="bi bi-x-circle-fill text-xs"></i>
            </button>
        </div>

        {{-- Selection Status Indicator --}}
        <div id="selection-status-box" class="flex items-center gap-3">
            <div id="selection-counter-wrapper" class="hidden items-center gap-2 px-3 py-1.5 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-800 text-xs font-semibold">
                <i class="bi bi-check2-circle text-sm text-indigo-600"></i>
                <span><strong id="counter-val">0</strong> transaksi dipilih</span>
                <button type="button" onclick="deselectAll()" class="ml-2 text-slate-500 hover:text-red-600 font-normal underline transition-colors">
                    {{ __('Batal') }}
                </button>
            </div>

            <div class="text-xs text-slate-500">
                {{ __('Total:') }} <strong class="text-slate-800" id="total-rows-info">{{ $circulations->count() }}</strong> {{ __('transaksi') }}
            </div>
        </div>
    </div>
</div>

{{-- Main Table --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-0">
        <div class="overflow-x-auto w-full">
            <table id="circulation-table" class="w-full text-left text-sm text-slate-600 whitespace-nowrap [&>thead>tr>th]:px-4 [&>thead>tr>th]:py-3 [&>thead>tr>th]:bg-slate-50 [&>thead>tr>th]:font-semibold [&>thead>tr>th]:text-slate-700 [&>thead>tr>th]:border-b [&>thead>tr>th]:border-slate-200 [&>tbody>tr>td]:px-4 [&>tbody>tr>td]:py-3 [&>tbody>tr]:border-b [&>tbody>tr]:border-slate-100 [&>tbody>tr:last-child]:border-0 [&>tbody>tr:hover]:bg-slate-50 transition-colors">
                <thead>
                    <tr>
                        <th class="w-12 text-center no-print">
                            <input type="checkbox" id="check-all" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500 cursor-pointer transition-all" title="{{ __('Pilih / Batalkan Semua') }}">
                        </th>
                        <th class="w-12 text-center">#</th>
                        <th>{{ __('Kode TRX') }}</th>
                        <th>{{ __('Anggota') }}</th>
                        <th>{{ __('Buku (Barcode)') }}</th>
                        <th>{{ __('Tgl Pinjam') }}</th>
                        <th>{{ __('Jatuh Tempo') }}</th>
                        <th>{{ __('Tgl Kembali') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Denda') }}</th>
                    </tr>
                </thead>
                <tbody id="circulation-tbody">
                    @forelse($circulations as $c)
                    <tr class="circ-row transition-colors" data-id="{{ $c->id }}">
                        <td class="text-center no-print">
                            <input type="checkbox" class="circ-checkbox w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500 cursor-pointer" value="{{ $c->id }}">
                        </td>
                        <td class="row-number text-center text-slate-500 font-mono text-xs">{{ $loop->iteration }}</td>
                        <td><code>{{ $c->transaction_code }}</code></td>
                        <td>{{ $c->member->name }} ({{ $c->member->member_code }})</td>
                        <td>{{ $c->bookItem->book->title }} (<code>{{ $c->bookItem->barcode }}</code>)</td>
                        <td>{{ $c->loan_date->format('d/m/Y') }}</td>
                        <td>{{ $c->due_date->format('d/m/Y') }}</td>
                        <td>{{ $c->return_date ? $c->return_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($c->status === 'Dikembalikan')
                            <span class="inline-flex py-1 text-xs font-medium rounded-md bg-emerald-100 text-emerald-700 px-2">{{ __('Kembali') }}</span>
                            @elseif($c->is_overdue)
                            <span class="inline-flex py-1 text-xs font-medium rounded-md bg-red-100 text-red-700 px-2">{{ __('Terlambat') }}</span>
                            @else
                            <span class="inline-flex py-1 text-xs font-medium rounded-md bg-amber-100 text-amber-800 px-2">{{ __('Dipinjam') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($c->fine_amount > 0)
                            <span class="text-red-600 font-semibold">Rp {{ number_format($c->fine_amount, 0, ',', '.') }}</span>
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr id="empty-row">
                        <td colspan="10" class="text-center text-slate-500 py-8">{{ __('Tidak ada data transaksi pada rentang tanggal ini') }}</td>
                    </tr>
                    @endforelse
                    <tr id="no-search-results" class="hidden">
                        <td colspan="10" class="text-center text-slate-500 py-8">
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
    body.print-selected-mode tr.circ-row.row-print-hidden {
        display: none !important;
    }

    /* Remove badges styling in print */
    span.rounded-md {
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
    const circCheckboxes = document.querySelectorAll('.circ-checkbox');
    const btnPrintSelected = document.getElementById('btn-print-selected');
    const selectedBadgeCount = document.getElementById('selected-badge-count');
    const selectionCounterWrapper = document.getElementById('selection-counter-wrapper');
    const counterVal = document.getElementById('counter-val');
    const btnExportExcel = document.getElementById('btn-export-excel');
    const exportText = document.getElementById('export-text');
    const tableSearch = document.getElementById('table-search');
    const clearSearch = document.getElementById('clear-search');
    const printMetaText = document.getElementById('print-meta-text');
    const baseExportUrl = "{{ route('reports.export', ['type' => 'circulation', 'start_date' => $startDate, 'end_date' => $endDate]) }}";

    function getSelectedCheckboxes() {
        return Array.from(document.querySelectorAll('.circ-checkbox:checked'));
    }

    function getVisibleRows() {
        return Array.from(document.querySelectorAll('.circ-row:not(.search-hidden)'));
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
            const sep = baseExportUrl.includes('?') ? '&' : '?';
            btnExportExcel.href = baseExportUrl + sep + 'ids=' + encodeURIComponent(selectedIds);
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
            const visibleCheckboxes = visibleRows.map(r => r.querySelector('.circ-checkbox')).filter(Boolean);
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
        const row = cb.closest('.circ-row');
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
                const cb = row.querySelector('.circ-checkbox');
                if (cb) {
                    cb.checked = isChecked;
                    syncRowHighlight(cb);
                }
            });
            updateSelectionUI();
        });
    }

    // Individual checkbox change
    circCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            syncRowHighlight(this);
            updateSelectionUI();
        });
    });

    // Deselect all
    window.deselectAll = function () {
        circCheckboxes.forEach(cb => {
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
            const rows = document.querySelectorAll('.circ-row');

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
            alert('Silakan pilih minimal satu transaksi dengan mencentang kotak checkbox terlebih dahulu.');
            return;
        }

        // Set mode
        document.body.classList.add('print-selected-mode');

        // Filter visible rows in print and renumber sequentially
        let counter = 1;
        document.querySelectorAll('.circ-row').forEach(row => {
            const cb = row.querySelector('.circ-checkbox');
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
        document.querySelectorAll('.circ-row').forEach(row => {
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

        document.querySelectorAll('.circ-row').forEach(row => {
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
