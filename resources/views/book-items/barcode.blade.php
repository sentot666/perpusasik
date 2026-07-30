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
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: #ffffff;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .label-header { border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; margin-bottom: 3px; text-align: center; }
        .inst-title { font-size: 8.5pt; font-weight: 800; color: #0f172a; text-transform: uppercase; line-height: 1.2; text-align: center; }
        .label-title { font-size: 9pt; font-weight: 700; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin: 3px 0; text-align: center; }
        
        .call-num-box { text-align: center; margin-bottom: 3px; }
        .call-num { font-family: 'JetBrains Mono', monospace; font-size: 8pt; font-weight: 700; color: #1e3a8a; background: #eff6ff; border: 1px solid #dbeafe; padding: 2px 8px; border-radius: 4px; display: inline-block; }

        .barcode-area { display: flex; flex-direction: column; align-items: center; margin: 4px 0; }
        .barcode-img { max-width: 100%; height: 26px; display: block; }
        .barcode-str { font-family: 'JetBrains Mono', monospace; font-size: 9pt; font-weight: 700; color: #0f172a; letter-spacing: 0.8px; margin-top: 2px; }
        .label-footer { display: flex; justify-content: space-between; font-size: 7pt; color: #64748b; border-top: 1px dashed #e2e8f0; padding-top: 4px; margin-top: 2px; }

        @media print {
            @page { size: 7.5cm 4.5cm landscape; margin: 0; }
            body { background: #ffffff !important; padding: 0 !important; }
            .action-bar { display: none !important; }
            .barcode-label { box-shadow: none !important; margin: 0 !important; border: 1px solid #94a3b8 !important; }
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
            $barcodeBase64 = base64_encode($generator->getBarcode($item->barcode, $generator::TYPE_CODE_128, 1.3, 26));
        } catch (\Throwable $e) {
            $barcodeBase64 = null;
        }
    @endphp

    <div class="barcode-label">
        <!-- 1. Header (Nama Perpustakaan - Center) -->
        <div class="label-header">
            <div class="inst-title">{{ \App\Models\Setting::get('library_name', 'PERPUSTAKAAN') }}</div>
        </div>

        <!-- 2. Judul Buku (Center) -->
        <div class="label-title" title="{{ $item->book?->title }}">{{ \Illuminate\Support\Str::limit($item->book?->title ?? '-', 40) }}</div>

        <!-- 3. Code Panggil (Center) -->
        <div class="call-num-box">
            <span class="call-num">{{ $item->book?->call_number ?? ($item->book?->ddc ? 'DDC ' . $item->book?->ddc : '-') }}</span>
        </div>

        <!-- 4. Barcode Area -->
        <div class="barcode-area">
            @if($barcodeBase64)
                <img src="data:image/png;base64,{{ $barcodeBase64 }}" class="barcode-img" alt="Barcode {{ $item->barcode }}">
            @endif
            <div class="barcode-str">{{ $item->barcode }}</div>
        </div>

        <!-- 5. Footer (No. Induk & Rak) -->
        <div class="label-footer">
            <div>No. Induk: {{ $item->accession_number ?? '-' }}</div>
            <div>Rak: {{ $item->location?->name ?? '-' }}</div>
        </div>
    </div>

</body>
</html>
