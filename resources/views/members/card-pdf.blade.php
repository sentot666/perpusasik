<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Anggota - {{ $member->name }}</title>
    <style>
        @page {
            margin: 20pt;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #000000;
        }
        
        .cut-guide {
            font-size: 8pt;
            color: #64748b;
            text-align: center;
            margin-bottom: 15pt;
            margin-top: 10pt;
        }

        /* 
         * CARD WRAPPERS - Fixed size 242x153
         */
        .card-wrapper {
            position: relative;
            width: 242pt;
            height: 153pt;
            overflow: hidden;
            border: 1.5pt dashed #94a3b8; /* guides for cutting */
            margin: 0 auto;
            text-align: left;
        }

        /* Use absolute positioning instead of padding to avoid DOMPDF box-sizing bugs */
        .card-inner {
            position: absolute;
            top: 15pt;
            left: 15pt;
            right: 15pt;
            bottom: 15pt;
            z-index: 10;
        }

        /* FRONT CARD */
        .front-card {
            background: #ffffff;
        }

        .front-header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .front-header-table td {
            vertical-align: middle;
        }

        .name-block {
            margin-top: 12pt;
            margin-bottom: 10pt;
            font-size: 11.5pt;
            font-weight: bold;
            letter-spacing: 0.5pt;
            text-transform: uppercase;
            color: #0f172a;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
        }
        .details-table td {
            vertical-align: top;
        }
        
        .label {
            font-size: 6pt;
            font-weight: bold;
            color: #64748b;
            margin-bottom: 2pt;
            text-transform: uppercase;
        }
        .val {
            font-size: 7.5pt;
            color: #1e293b;
            line-height: 1.2;
            margin-bottom: 8pt;
        }

        /* BACK CARD */
        .back-card {
            background: #f8fafc;
        }

        .bg-shape-grey {
            position: absolute;
            top: 0;
            right: 0;
            width: 150pt;
            height: 153pt;
            background-color: #e2e8f0;
            border-bottom-left-radius: 150pt;
            z-index: 1;
        }
        .bg-shape-blue {
            position: absolute;
            bottom: -30pt;
            left: -30pt;
            width: 120pt;
            height: 120pt;
            background-color: #2b6cb0;
            border-radius: 50%;
            z-index: 2;
        }

        .rules-list {
            font-size: 7pt;
            margin: 0;
            padding-left: 12pt;
            line-height: 1.4;
            color: #334155;
        }
        .rules-list li {
            margin-bottom: 3pt;
        }
    </style>
</head>
<body>

<div class="cut-guide">
    Gunting mengikuti garis putus-putus. Lipat di tengah jika ingin dilaminating bolak-balik.
</div>

<table style="width: 100%; border-collapse: collapse; margin-top: 10pt;">
    <tr>
        <!-- FRONT SIDE -->
        <td style="width: 50%; text-align: center; vertical-align: top; padding: 10pt;">
            <div class="card-wrapper front-card">
                <div class="card-inner">
                    <!-- Header -->
                    <table class="front-header-table">
                        <tr>
                            <td style="width: 26pt;">
                                <div style="width: 20pt; height: 20pt;">
                                    <img src="{{ public_path('images/logo.jpg') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 7.5pt; font-weight: bold; line-height: 1.1; color: #1e3a5f;">
                                    KARTU PERPUSTAKAAN<br>SAINT PAUL
                                </div>
                            </td>
                        </tr>
                    </table>

                    <!-- Details List -->
                    <table style="width: 100%; border-collapse: collapse; margin-top: 6pt;">
                        <tr>
                            <td style="font-size: 6pt; font-weight: bold; color: #64748b; padding-bottom: 3pt; width: 60pt;">ID Anggota</td>
                            <td style="font-size: 6.5pt; font-weight: bold; color: #0f172a; padding-bottom: 3pt;">: {{ $member->member_code }}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 6pt; font-weight: bold; color: #64748b; padding-bottom: 3pt;">Nama</td>
                            <td style="font-size: 6.5pt; font-weight: bold; color: #0f172a; padding-bottom: 3pt;">: {{ $member->name }}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 6pt; font-weight: bold; color: #64748b; padding-bottom: 3pt;">Nomor Identitas</td>
                            <td style="font-size: 6.5pt; color: #0f172a; padding-bottom: 3pt;">: {{ $member->identity_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 6pt; font-weight: bold; color: #64748b; vertical-align: top;">Alamat</td>
                            <td style="font-size: 6pt; color: #0f172a; vertical-align: top; line-height: 1.1; padding-right: 65pt;">: {{ \Illuminate\Support\Str::limit($member->address ?? '-', 45) }}</td>
                        </tr>
                    </table>

                    <!-- Barcode at Bottom Right of Front Card (16pt safely inside card cut lines) -->
                    <div style="position: absolute; bottom: 16pt; right: 16pt; text-align: right;">
                        @php
                            $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                            $barcode = base64_encode($generator->getBarcode($member->member_code, $generator::TYPE_CODE_128, 1.1, 18));
                        @endphp
                        <div style="background: #ffffff; padding: 2pt 4pt; border-radius: 2pt; display: inline-block;">
                            <img src="data:image/png;base64,{{ $barcode }}" style="width: 72pt; height: 16pt; display: block;" alt="barcode">
                            <div style="font-size: 5.5pt; color: #1e293b; font-family: monospace; text-align: center; margin-top: 1pt; font-weight: bold;">{{ $member->member_code }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="font-size: 7pt; color: #94a3b8; margin-top: 5pt;">Bagian Depan</div>
        </td>

        <!-- BACK SIDE -->
        <td style="width: 50%; text-align: center; vertical-align: top; padding: 10pt;">
            <div class="card-wrapper back-card">
                <!-- Background graphics -->
                <div class="bg-shape-grey"></div>
                <div class="bg-shape-blue"></div>
                
                <!-- Content -->
                <div class="card-inner">
                    <table style="width: 100%; height: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="vertical-align: top; width: 45%;">
                                <div style="font-size: 8.5pt; font-weight: bold; margin-bottom: 2pt; color: #1e3a5f;">SANTO PAULUS</div>
                                <div style="font-size: 6.5pt; color: #64748b;">santopaulus.sch.id</div>
                            </td>
                            <td style="vertical-align: top; width: 55%; padding-left: 10pt;">
                                <div style="font-size: 7.5pt; font-weight: bold; margin-bottom: 6pt; color: #1e3a5f;">Aturan Perpustakaan</div>
                                <ul class="rules-list">
                                    <li>Kartu ini milik perpustakaan.</li>
                                    <li>Tolong kembalikan ke perpustakaan apabila menemukan kartu ini.</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="vertical-align: bottom; text-align: right;">
                                <div style="font-size: 6pt; margin-bottom: 15pt; color: #64748b;">Jakarta Utara, {{ now()->format('d M Y') }}<br>Pustakawan</div>
                                <div style="font-size: 7pt; font-weight: bold; color: #1e293b;">Yohanes Wakidi, S.Pd</div>
                                <div style="font-size: 6pt; color: #64748b;">NIY 210 07 2000</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div style="font-size: 7pt; color: #94a3b8; margin-top: 5pt;">Bagian Belakang</div>
        </td>
    </tr>
</table>

</body>
</html>
