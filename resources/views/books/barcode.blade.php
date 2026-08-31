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
            grid-template-columns: repeat(auto-fill, minmax(9cm, 1fr));
            gap: 16px;
        }

        /* Single Barcode Sticker Label */
        .barcode-label {
            width: 9cm;
            height: 3.4cm;
            border: 1px solid #000000;
            display: flex;
            background: #ffffff;
            page-break-inside: avoid;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            overflow: hidden;
        }

        .label-left {
            width: 60%;
            padding: 6px 8px;
            border-right: 1px solid #000000;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            text-align: center;
        }

        .label-right {
            width: 40%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .book-title {
            font-size: 7.5pt;
            line-height: 1.15;
            font-weight: 600;
            margin-bottom: 2px;
            word-wrap: break-word;
        }

        .barcode-img {
            max-width: 100%;
            height: 32px;
            display: block;
            margin: 0 auto;
        }

        .barcode-str {
            font-family: 'Inter', sans-serif;
            font-size: 8pt;
            font-weight: 700;
            margin-top: 2px;
            letter-spacing: 0.5px;
        }

        .inst-title {
            width: 100%;
            background-color: #d1d5db; /* Gray background */
            padding: 4px 2px;
            font-size: 10pt;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 5px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .call-num-stack {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5px;
            padding: 0 4px 6px 4px;
        }

        .call-num-part {
            font-size: 9pt;
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

            .action-bar, .book-info-header {
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
                /* Let inline border-bottom stay */
                border-top: 1px solid #000000 !important;
                border-left: 1px solid #000000 !important;
                border-right: 1px solid #000000 !important;
            }
            .label-left {
                border-right: 1px solid #000000 !important;
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

                    // Logika Warna DDC
                    $ddcNumber = $book->ddc;
                    if (!$ddcNumber) {
                        preg_match('/^\d{3}/', $book->call_number ?? '', $matches);
                        $ddcNumber = $matches[0] ?? '000';
                    }
                    
                    $ddcPrefix = substr(trim($ddcNumber), 0, 1);
                    
                    $ddcColors = [
                        '0' => '#2563EB', // 000 - Karya Umum (Biru)
                        '1' => '#FDE047', // 100 - Filsafat (Kuning)
                        '2' => '#16A34A', // 200 - Agama (Hijau)
                        '3' => '#9333EA', // 300 - Ilmu Sosial (Ungu)
                        '4' => '#DB2777', // 400 - Bahasa (Pink Gelap)
                        '5' => '#7DD3FC', // 500 - Ilmu Murni (Biru Muda)
                        '6' => '#F97316', // 600 - Ilmu Terapan (Orange)
                        '7' => '#FBCFE8', // 700 - Kesenian (Pink Muda)
                        '8' => '#CA8A04', // 800 - Kesusastraan (Kuning Gelap/Mustard)
                        '9' => '#DC2626', // 900 - Sejarah & Geografi (Merah)
                    ];
                    
                    $rackColor = $ddcColors[$ddcPrefix] ?? '#FFFFFF';
                @endphp

                <div class="barcode-label" style="border-bottom: 12px solid {{ $rackColor }};">
                    <!-- Bagian Kiri (Sisi Cover Buku) -->
                    <div class="label-left">
                        <div class="book-title">{{ $book->title ?? '-' }}</div>
                        <div style="flex: 1; display: flex; flex-direction: column; justify-content: center; width: 100%;">
                            @if($barcodeBase64)
                                <img src="data:image/png;base64,{{ $barcodeBase64 }}" class="barcode-img" alt="Barcode {{ $item->barcode }}" style="height: 38px;">
                            @endif
                            <div class="barcode-str">{{ $item->barcode }}</div>
                        </div>
                    </div>

                    <!-- Bagian Kanan (Sisi Punggung Buku) -->
                    <div class="label-right">
                        <div class="inst-title">Perpustakaan Sekolah Katolik<br>Santo Paulus</div>
                        <div class="call-num-stack">
                            @php
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
                                    $authorStr = $book->main_author ?? '';
                                    if ($authorStr) {
                                        $cleanParts[] = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $authorStr), 0, 3));
                                    }
                                    $titleStr = $book->title ?? '';
                                    if ($titleStr) {
                                        $cleanParts[] = strtolower(substr(preg_replace('/[^a-zA-Z]/', '', $titleStr), 0, 1));
                                    }
                                }

                                $copyCode = 'c' . $loop->iteration;
                            @endphp
                            @foreach($cleanParts as $part)
                                <span class="call-num-part">{{ $part }}</span>
                            @endforeach
                            <span class="call-num-part">{{ $copyCode }}</span>
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
