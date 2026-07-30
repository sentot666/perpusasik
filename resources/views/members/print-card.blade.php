<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Kartu Anggota') }} - {{ $members->count() > 1 ? 'Massal' : $members->first()->member_code }} (Foldable 17.5cm x 5.5cm)</title>
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #0f172a;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 15px;
        }

        /* Action Bar */
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
            max-width: 17.5cm;
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
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: #e2e8f0;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .print-guide {
            color: #94a3b8;
            font-size: 11px;
            margin-bottom: 12px;
            text-align: center;
        }

        /* 
         * CARD WRAPPER - TOTAL SIZE: 17.5cm x 5.5cm
         * Folds in half at 8.75cm
         */
        .card-wrapper {
            width: 17.5cm;
            height: 5.5cm;
            background: #ffffff;
            border: 1.5px dashed #94a3b8;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            display: flex;
            position: relative;
            overflow: hidden;
            border-radius: 4px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Middle Fold Line Guide */
        .fold-line {
            position: absolute;
            left: 8.75cm;
            top: 0;
            bottom: 0;
            width: 1px;
            border-right: 1.5px dashed #94a3b8;
            z-index: 50;
            pointer-events: none;
        }

        /* 
         * PANEL DEPAN (FRONT PANEL) - 8.75cm x 5.5cm
         */
        .panel-front {
            width: 8.75cm;
            height: 5.5cm;
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            background: #ffffff;
            box-sizing: border-box;
        }

        .front-header {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-img {
            width: 48px;
            height: 48px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .header-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .header-label {
            font-size: 6.5pt;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .header-title {
            font-size: 9pt;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            line-height: 1.2;
            letter-spacing: 0.2px;
        }

        .member-name {
            font-size: 11pt;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 6px;
            margin-bottom: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .details-block {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .detail-row {
            display: flex;
            align-items: baseline;
            gap: 0;
        }

        .detail-lbl {
            font-size: 6.5pt;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            white-space: nowrap;
            min-width: 2.2cm;
        }

        .detail-colon {
            font-size: 6.5pt;
            font-weight: 700;
            color: #475569;
            margin-right: 4px;
        }

        .detail-val {
            font-size: 7.5pt;
            color: #0f172a;
            font-weight: 600;
            line-height: 1.3;
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .front-bottom {
            display: flex;
            justify-content: flex-end;
            align-items: flex-end;
            margin-top: 2px;
        }

        .barcode-box {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .exp-text {
            font-size: 7pt;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .barcode-img {
            height: 18px;
            max-width: 3.5cm;
            display: block;
        }

        /* 
         * PANEL BELAKANG (BACK PANEL) - 8.75cm x 5.5cm
         */
        .panel-back {
            width: 8.75cm;
            height: 5.5cm;
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            background: #f8fafc;
            box-sizing: border-box;
            overflow: hidden;
        }

        /* Background Shapes */
        .bg-shape-grey {
            position: absolute;
            top: 0;
            right: 0;
            width: 4.8cm;
            height: 4.8cm;
            background-color: #e2e8f0;
            border-bottom-left-radius: 4.8cm;
            z-index: 1;
        }

        .bg-shape-blue {
            position: absolute;
            bottom: -1.2cm;
            right: -1.2cm;
            width: 5.2cm;
            height: 5.2cm;
            background-color: #2b6cb0;
            border-radius: 50%;
            z-index: 2;
        }

        .back-content {
            position: relative;
            z-index: 10;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .rules-title {
            font-size: 7.5pt;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 3px;
        }

        .rules-list {
            font-size: 6.5pt;
            color: #334155;
            padding-left: 12px;
            line-height: 1.35;
            margin: 0;
        }

        .rules-list li {
            margin-bottom: 2px;
        }

        .back-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .inst-info {
            display: flex;
            flex-direction: column;
        }

        .inst-name {
            font-size: 7.5pt;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
        }

        .inst-url {
            font-size: 6.5pt;
            color: #334155;
        }

        .pustakawan-box {
            text-align: right;
            color: #0f172a;
        }

        .pustakawan-date {
            font-size: 6pt;
            color: #1e293b;
            margin-bottom: 1px;
        }

        .pustakawan-role {
            font-size: 6.5pt;
            font-weight: 700;
            color: #0f172a;
        }

        .pustakawan-sign-space {
            height: 14px;
        }

        .pustakawan-name {
            font-size: 7.5pt;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }

        .pustakawan-niy {
            font-size: 6pt;
            color: #334155;
        }

        /* PRINT STYLES */
        @media print {
            @page {
                size: 17.5cm 5.5cm landscape;
                margin: 0;
            }

            html, body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                min-height: auto !important;
                display: flex !important;
                align-items: flex-start !important;
                justify-content: flex-start !important;
            }

            .action-bar, .print-guide {
                display: none !important;
            }

            .card-wrapper {
                box-shadow: none !important;
                margin-bottom: 20px !important;
                border: 1px dashed #cbd5e1 !important;
                page-break-inside: avoid !important;
            }

            .fold-line {
                border-right: 1px dashed #94a3b8 !important;
            }
        }
    </style>
</head>
<body>

    <!-- Top Action Bar -->
    <div class="action-bar">
        <div class="action-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
            </svg>
            Kartu Anggota Lipat <span>(17.5 cm × 5.5 cm)</span>
        </div>
        <div class="action-btns">
            <a href="{{ route('members.index') }}" onclick="if (window.opener || window.history.length <= 1) { window.close(); return false; } else { window.history.back(); return false; }" class="btn btn-secondary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <button class="btn btn-primary" onclick="window.print()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak Kartu
            </button>
        </div>
    </div>

    <div class="print-guide">
        Gunting mengikuti garis putus-putus luar, lalu lipat di tengah (garis lipat) untuk dilaminating.
    </div>

    <!-- FOLDABLE CARD WRAPPER (17.5 cm x 5.5 cm) -->
    <div class="cards-container">
        @foreach($members as $m)
        <div class="card-wrapper" style="page-break-inside: avoid; margin-bottom: 20px;">
        <!-- Garis Lipat Tengah -->
        <div class="fold-line"></div>

        <!-- PANEL DEPAN (8.75 cm x 5.5 cm) -->
        <div class="panel-front">
            <div>
                <!-- Header -->
                <div class="front-header">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="logo-img" onerror="this.style.display='none'">
                    <div style="width:1px; height:44px; background:#e2e8f0; margin: 0 2px; flex-shrink:0;"></div>
                    <div class="header-text">
                        <div class="header-label">KARTU PERPUSTAKAAN</div>
                        <div class="header-title">{{ strtoupper($libraryName ?? \App\Models\Setting::get('library_name', 'SAINT PAUL')) }}</div>
                    </div>
                </div>

                <!-- Garis bawah header -->
                <div style="height:2.5px; background:#2b6cb0; margin: 4px 0 10px 0; border-radius:2px;"></div>

@php
    $statusMap = [
        'SD' => 'Siswa SD', 'Siswa SD' => 'Siswa SD',
        'SMP' => 'Siswa SMP', 'Siswa SMP' => 'Siswa SMP',
        'SMA' => 'Siswa SMA', 'Siswa SMA' => 'Siswa SMA', 'Pelajar' => 'Siswa SMA',
        'Guru' => 'Guru', 'Pegawai' => 'Guru', 'Karyawan' => 'Guru',
        'Mahasiswa' => 'Mahasiswa',
    ];
    $status = $statusMap[$m->member_type] ?? ($m->member_type ?? 'Umum');
@endphp

                <!-- Details -->
                <div class="details-block">
                    <div class="detail-row">
                        <div class="detail-lbl">Nama</div>
                        <div class="detail-colon">:</div>
                        <div class="detail-val" style="font-weight:800; font-size:8pt; text-transform:uppercase;">{{ $m->name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-lbl">No. Anggota</div>
                        <div class="detail-colon">:</div>
                        <div class="detail-val">{{ $m->member_code }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-lbl">Status</div>
                        <div class="detail-colon">:</div>
                        <div class="detail-val">{{ $status }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-lbl">Alamat</div>
                        <div class="detail-colon">:</div>
                        <div class="detail-val">{{ \Illuminate\Support\Str::limit($m->address ?? '-', 40) }}</div>
                    </div>
                </div>
            </div>

            <!-- Exp & Barcode -->
            <div class="front-bottom">
                <div class="barcode-box">
                    <div class="exp-text">Exp. {{ $m->expired_date ? $m->expired_date->format('Y-m-d') : '2035-06-30' }}</div>
                    @php
                        $barcodeBase64 = null;
                        try {
                            $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                            $barcodeBase64 = base64_encode($generator->getBarcode($m->member_code, $generator::TYPE_CODE_128, 1.1, 18));
                        } catch (\Throwable $e) {
                            $barcodeBase64 = null;
                        }
                    @endphp

                    @if($barcodeBase64)
                        <img src="data:image/png;base64,{{ $barcodeBase64 }}" class="barcode-img" alt="Barcode {{ $m->member_code }}">
                    @else
                        <div style="font-family: monospace; font-size: 8pt; font-weight: bold;">*{{ $m->member_code }}*</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- PANEL BELAKANG (8.75 cm x 5.5 cm) -->
        <div class="panel-back">
            <!-- Background Graphics -->
            <div class="bg-shape-grey"></div>
            <div class="bg-shape-blue"></div>

            <div class="back-content">
                <div>
                    <div class="rules-title">Aturan Perpustakaan</div>
                    <ul class="rules-list">
                        <li>Kartu ini milik perpustakaan</li>
                        <li>Tolong kembalikan ke perpustakaan apabila menemukan kartu ini..</li>
                    </ul>
                </div>

                <div class="back-footer">
                    <div class="inst-info">
                        <div class="inst-name">SANTO PAULUS</div>
                        <div class="inst-url">santopaulus.sch.id</div>
                    </div>

                    <div class="pustakawan-box">
                        <div class="pustakawan-date">Jakarta Utara, {{ now()->format('d M Y') }}</div>
                        <div class="pustakawan-role">Pustakawan</div>
                    </div>
                </div>
            </div>
        </div>

        </div>
        @endforeach
    </div>

</body>
</html>
