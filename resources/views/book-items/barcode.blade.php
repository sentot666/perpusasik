<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode - {{ $item->barcode }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

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
            max-width: 500px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
        }

        .action-title { color: #f8fafc; font-size: 14px; font-weight: 600; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; }
        .btn-primary { background: #2563eb; color: #ffffff; }
        .btn-secondary { background: rgba(255, 255, 255, 0.1); color: #e2e8f0; }

        .barcode-label {
            width: 7.5cm;
            height: 4.5cm;
            border: 1px dashed #cbd5e1;
            border-radius: 4px;
            display: flex;
            background: #ffffff;
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

        @media print {
            @page { size: 7.5cm 4.5cm landscape; margin: 0; }
            body { background: #ffffff !important; padding: 0 !important; }
            .action-bar { display: none !important; }
            .barcode-label { box-shadow: none !important; margin: 0 !important; border: 1px dashed #000000 !important; }
            .label-left { border-right: 1px dashed #000000 !important; }
        }
    </style>
</head>
<body>

    <div class="action-bar">
        <div class="action-title">Cetak Barcode Eksemplar</div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('books.show', $item->book_id) }}" onclick="if (window.opener || window.history.length <= 1) { window.close(); return false; } else { window.history.back(); return false; }" class="btn btn-secondary">Kembali</a>
            <button class="btn btn-primary" onclick="window.print()">Cetak</button>
        </div>
    </div>

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
            <div class="book-title">{{ $item->book?->title ?? '-' }}</div>
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
                    $callNumber = $item->book?->call_number ?? ($item->book?->ddc ? $item->book?->ddc : '-');
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

</body>
</html>
