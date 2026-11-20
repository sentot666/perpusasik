<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Buku - {{ $book->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #0f172a;
            color: #0f172a;
            min-height: 100vh;
            padding: 30px 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Top Action Bar (hidden when printing) */
        .action-bar {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 12px 24px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            max-width: 900px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
            gap: 12px;
        }

        .action-title {
            color: #f8fafc;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .action-title span {
            color: #94a3b8;
            font-size: 12px;
            font-weight: 400;
        }

        .action-btns {
            display: flex;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-primary {
            background: #4f46e5;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #4338ca;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #e2e8f0;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Screen Preview Container */
        .sheet {
            background: #ffffff;
            width: 100%;
            max-width: 900px;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.2);
        }

        .book-info-header {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        .book-info-header h2 {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .book-info-header p {
            font-size: 13px;
            color: #64748b;
        }

        .card-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            justify-content: center;
        }

        /* Physical Book Card dimensions: lebar 10cm x panjang 14cm */
        .book-card-item {
            width: 10cm;
            height: 14cm;
            min-height: 14cm;
            max-height: 14cm;
            box-sizing: border-box;
            background: #ffffff;
            border: 1px solid #000000;
            padding: 5mm 6mm;
            display: flex;
            flex-direction: column;
            color: #000000;
            page-break-inside: avoid;
            break-inside: avoid;
            position: relative;
            overflow: hidden;
        }

        /* Header */
        .card-header {
            text-align: center;
            margin-bottom: 3px;
        }

        .school-name {
            font-size: 9pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            line-height: 1.2;
            color: #000000;
        }

        .school-address {
            font-size: 6.8pt;
            font-weight: 600;
            line-height: 1.2;
            margin-top: 2px;
            color: #000000;
        }

        .card-divider {
            border: none;
            border-top: 1.5px solid #000000;
            margin: 4px 0 5px 0;
            width: 100%;
        }

        /* Meta details */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
            line-height: 1.3;
            margin-bottom: 5px;
        }

        .meta-table tr td {
            padding: 0.5px 0;
            vertical-align: top;
            color: #000000;
        }

        .meta-label {
            width: 68px;
            font-weight: 600;
            white-space: nowrap;
        }

        .meta-sep {
            width: 10px;
            text-align: center;
            font-weight: 600;
        }

        .meta-val {
            word-break: break-word;
            font-weight: 500;
        }

        .meta-val.bold {
            font-weight: 700;
        }

        /* Circulation Table */
        .circ-table {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000000;
            margin-top: 2px;
            flex-grow: 1;
            table-layout: fixed;
        }

        .circ-table th {
            border: 1px solid #000000;
            border-bottom: 1.5px solid #000000;
            padding: 2.5px 1px;
            font-size: 6.8pt;
            font-weight: 700;
            text-align: center;
            line-height: 1.15;
            background-color: #ffffff;
            color: #000000;
        }

        .circ-table td {
            border: 1px solid #000000;
            height: 4.4mm;
            padding: 0;
            font-size: 6.5pt;
        }

        /* PRINT STYLES */
        @media print {
            @page {
                size: A4 portrait;
                margin: 6mm 4mm;
            }

            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .action-bar, .book-info-header {
                display: none !important;
            }

            .sheet {
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
                background: transparent !important;
                border-radius: 0 !important;
            }

            .card-grid {
                display: grid !important;
                grid-template-columns: repeat(2, 10cm) !important;
                column-gap: 2mm !important;
                row-gap: 5mm !important;
                justify-content: center !important;
                margin: 0 auto !important;
            }

            .book-card-item {
                width: 10cm !important;
                height: 14cm !important;
                min-height: 14cm !important;
                max-height: 14cm !important;
                border: 1px solid #000000 !important;
                box-shadow: none !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
        }
    </style>
</head>
<body>

    <!-- Top Action Bar -->
    <div class="action-bar">
        <div class="action-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            Kartu Buku Perpustakaan (10cm x 14cm)
            <span>({{ $items->count() }} kartu siap cetak)</span>
        </div>
        <div class="action-btns">
            <a href="{{ route('books.show', $book) }}" onclick="if (window.opener || window.history.length <= 1) { window.close(); return false; } else { window.history.back(); return false; }" class="btn btn-secondary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <button class="btn btn-primary" onclick="window.print()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak Kartu Buku
            </button>
        </div>
    </div>

    <!-- Sheet Content -->
    <div class="sheet">
        <div class="book-info-header">
            <h2>{{ $book->title }}</h2>
            <p>Pengarang: {{ $book->main_author ?? ($book->authors->first()?->name ?? '-') }} | No. Panggil: {{ $book->call_number ?? '-' }} | Dicetak: {{ $items->count() }} Kartu</p>
        </div>

        @php
            $libraryName = \App\Models\Setting::get('library_name') ?: 'SEKOLAH KATOLIK ST.PAULUS';
            $libraryAddress = \App\Models\Setting::get('library_address') ?: 'Jl. Danau Agung 13, Sunter Agung, Tanjung Priok, Kota Jakarta Utara, DKI Jakarta 14350';

            // Author formatting
            $authorList = $book->authors->pluck('name')->all();
            $authorName = !empty($authorList) ? implode(', ', $authorList) : ($book->main_author ?? '-');
        @endphp

        <div class="card-grid">
            @forelse($items as $item)
                @php
                    // Compute Clean Call Number + Copy Code (e.g. "369.4 WAH p c2")
                    $baseCallNumber = trim($book->call_number ?? ($book->ddc ? $book->ddc : ''));
                    $rawParts = array_values(array_filter(explode(' ', $baseCallNumber), fn($p) => trim($p) !== ''));

                    $cleanParts = [];
                    foreach ($rawParts as $p) {
                        if (!preg_match('/^c\.?\d+$/i', $p)) {
                            $cleanParts[] = $p;
                        }
                    }

                    if (empty($cleanParts)) {
                        if ($book->ddc) {
                            $cleanParts[] = $book->ddc;
                        }
                        $authorStr = $book->main_author ?? ($book->authors->first()?->name ?? '');
                        if ($authorStr) {
                            $cleanParts[] = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $authorStr), 0, 3));
                        }
                        $titleStr = $book->title ?? '';
                        if ($titleStr) {
                            $cleanParts[] = strtolower(substr(preg_replace('/[^a-zA-Z]/', '', $titleStr), 0, 1));
                        }
                    }

                    $copyIndex = $copyNumbers[$item->id] ?? $loop->iteration;
                    $copyCode = 'c' . $copyIndex;
                    $fullCallNumber = trim(implode(' ', $cleanParts) . ' ' . $copyCode);

                    // No. Inv (e.g. B001970)
                    $noInv = $item->barcode ?: ($item->accession_number ?: '-');
                @endphp

                <div class="book-card-item">
                    <!-- Header Instansi -->
                    <div class="card-header">
                        <div class="school-name">{{ $libraryName }}</div>
                        <div class="school-address">{{ $libraryAddress }}</div>
                    </div>

                    <hr class="card-divider">

                    <!-- Metadata Buku -->
                    <table class="meta-table">
                        <tr>
                            <td class="meta-label">No. Inv</td>
                            <td class="meta-sep">:</td>
                            <td class="meta-val bold">{{ $noInv }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Judul</td>
                            <td class="meta-sep">:</td>
                            <td class="meta-val">{{ Str::limit($book->title, 42, '..') }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Pengarang</td>
                            <td class="meta-sep">:</td>
                            <td class="meta-val">{{ Str::limit($authorName, 36, '..') }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Call Number</td>
                            <td class="meta-sep">:</td>
                            <td class="meta-val bold">{{ $fullCallNumber }}</td>
                        </tr>
                    </table>

                    <!-- Tabel Sirkulasi Fisik (22 baris kosong) -->
                    <table class="circ-table">
                        <thead>
                            <tr>
                                <th style="width: 28%;">No<br>Identitas</th>
                                <th style="width: 26%;">Tanggal<br>Kembali</th>
                                <th style="width: 23%;">Kondisi<br>Pinjam</th>
                                <th style="width: 23%;">Kondisi<br>Kembali</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 0; $i < 22; $i++)
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; color: #94a3b8; padding: 40px;">
                    Belum ada eksemplar fisik yang terdaftar untuk buku ini.
                </div>
            @endforelse
        </div>
    </div>

</body>
</html>