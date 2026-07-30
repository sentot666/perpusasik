<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode Buku - {{ $book->title }}</title>
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #0f172a;
            color: #0f172a;
            min-height: 100vh;
            padding: 30px 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

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
            background: #2563eb;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #e2e8f0;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Sheet for labels */
        .sheet {
            background: #ffffff;
            width: 100%;
            max-width: 900px;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }

        .book-info-header {
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .book-info-header h2 {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }

        .book-info-header p {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }

        /* Label Grid */
        .label-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(7.5cm, 1fr));
            gap: 16px;
        }

        /* Single Barcode Sticker Label */
        .barcode-label {
            width: 7.5cm;
            height: 4.5cm;
            border: 1px dashed #cbd5e1;
            border-radius: 4px;
            display: flex;
            background: #ffffff;
            page-break-inside: avoid;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            overflow: hidden;
        }

        .label-left {
            width: 60%;
            padding: 8px;
            border-right: 1px dashed #cbd5e1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            text-align: center;
        }

        .label-right {
            width: 40%;
            padding: 8px 4px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .book-title {
            font-size: 8pt;
            line-height: 1.1;
            font-weight: 600;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            word-wrap: break-word;
        }

        .barcode-img {
            max-width: 100%;
            height: 40px;
            display: block;
            margin: 0 auto;
        }

        .barcode-str {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10pt;
            font-weight: 700;
            margin-top: 4px;
            letter-spacing: 0.5px;
        }

        .inst-title {
            font-size: 7.5pt;
            font-weight: 800;
            text-transform: uppercase;
            line-height: 1.1;
            margin-bottom: 8px;
        }

        .call-num-stack {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
        }

        .call-num-part {
            font-size: 9.5pt;
            font-weight: 800;
            line-height: 1;
        }

        /* PRINT STYLES */
        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm;
            }

            body {
                background: #ffffff !important;
                padding: 0 !important;
            }

            .action-bar {
                display: none !important;
            }

            .sheet {
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 100% !important;
            }

            .label-grid {
                grid-template-columns: repeat(2, max-content) !important;
                justify-content: center;
                gap: 5mm !important;
            }

            .barcode-label {
                border: 1px dashed #000000 !important;
            }
            .label-left {
                border-right: 1px dashed #000000 !important;
            }
        }
    </style>
</head>
<body>

    <!-- Top Action Bar -->
    <div class="action-bar">
        <div class="action-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 7V5a2 2 0 0 1 2-2h2"></path><path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
                <path d="M21 17v2a2 2 0 0 1-2 2h-2"></path><path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
            </svg>
            Cetak Label Barcode <span>({{ $items->count() }} eksemplar)</span>
        </div>
        <div class="action-btns">
            <a href="{{ route('books.show', $book) }}" onclick="if (window.opener || window.history.length <= 1) { window.close(); return false; } else { window.history.back(); return false; }" class="btn btn-secondary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <button class="btn btn-primary" onclick="window.print()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak Label Barcode
            </button>
        </div>
    </div>

    <!-- Sheet Content -->
    <div class="sheet">
        <div class="book-info-header">
            <h2>{{ $book->title }}</h2>
            <p>Pengarang: {{ $book->main_author ?? '-' }} | No. Panggil: {{ $book->call_number ?? '-' }} | Total Eksemplar: {{ $items->count() }}</p>
        </div>

        <div class="label-grid">
            @forelse($items as $item)
                @php
                    $barcodeBase64 = null;
                    try {
                        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                        $barcodeBase64 = base64_encode($generator->getBarcode($item->barcode, $generator::TYPE_CODE_128, 1.6, 40));
                    } catch (\Throwable $e) {
                        $barcodeBase64 = null;
                    }
                @endphp

                <div class="barcode-label">
                    <!-- Bagian Kiri (Sisi Cover Buku) -->
                    <div class="label-left">
                        <div class="book-title">{{ $book->title ?? '-' }}</div>
                        <div style="flex: 1; display: flex; flex-direction: column; justify-content: center; width: 100%;">
                            @if($barcodeBase64)
                                <img src="data:image/png;base64,{{ $barcodeBase64 }}" class="barcode-img" alt="Barcode {{ $item->barcode }}">
                            @endif
                            <div class="barcode-str">{{ $item->barcode }}</div>
                        </div>
                    </div>

                    <!-- Bagian Kanan (Sisi Punggung Buku) -->
                    <div class="label-right">
                        <div class="inst-title">{{ \App\Models\Setting::get('library_name', 'PERPUSTAKAAN') }}</div>
                        <div class="call-num-stack">
                            @php
                                $callNumber = $book->call_number ?? ($book->ddc ? $book->ddc : '-');
                                $parts = explode(' ', $callNumber);
                            @endphp
                            @foreach($parts as $part)
                                @if(trim($part) !== '')
                                <span class="call-num-part">{{ $part }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
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
