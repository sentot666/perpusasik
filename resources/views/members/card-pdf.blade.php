<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Anggota - {{ $member->name }}</title>
    <style>
        @page {
            margin: 0;
            size: 242pt 153pt landscape;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 242pt;
            height: 153pt;
            background-color: #ffffff;
            color: #333333;
            box-sizing: border-box;
        }
        .card {
            width: 242pt;
            height: 153pt;
            position: relative;
            background: linear-gradient(135deg, #ffffff 0%, #f7fafc 100%);
            box-sizing: border-box;
        }
        .header-band {
            background-color: #1e3a5f;
            color: #ffffff;
            padding: 6pt 10pt;
            text-align: center;
            border-bottom: 2px solid #2b6cb0;
        }
        .library-name {
            font-size: 9pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.1;
        }
        .card-title {
            font-size: 6.5pt;
            color: #93c5fd;
            margin: 2pt 0 0 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
        }
        .card-body {
            padding: 8pt 10pt 4pt 10pt;
        }
        .photo-cell {
            width: 52pt;
            vertical-align: top;
        }
        .photo-box {
            width: 48pt;
            height: 60pt;
            border: 1px solid #cbd5e0;
            border-radius: 3px;
            background-color: #edf2f7;
            overflow: hidden;
            text-align: center;
        }
        .photo-box-empty {
            line-height: 60pt;
            font-size: 7pt;
            color: #a0aec0;
            font-weight: bold;
        }
        .photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .info-cell {
            vertical-align: top;
            padding-left: 6pt;
            width: 122pt;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 1.5pt 0;
            font-size: 7.5pt;
            line-height: 1.2;
        }
        .info-label {
            color: #4a5568;
            font-weight: bold;
            width: 40pt;
        }
        .info-value {
            color: #1a202c;
        }
        .qr-cell {
            vertical-align: top;
            padding-left: 4pt;
            width: 48pt;
            text-align: right;
        }
        .qr-box {
            width: 42pt;
            height: 42pt;
            border: 1px solid #cbd5e0;
            border-radius: 4px;
            background-color: #ffffff;
            padding: 2pt;
            display: inline-block;
        }
        .qr-label {
            font-size: 4.5pt;
            color: #718096;
            text-align: center;
            margin-top: 2pt;
            width: 48pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .card-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 4pt 10pt;
            border-top: 1px dashed #e2e8f0;
            background-color: #f8fafc;
            text-align: center;
        }
        .barcode-text {
            font-family: 'Courier New', Courier, monospace;
            font-size: 7.5pt;
            font-weight: bold;
            letter-spacing: 3px;
            color: #2d3748;
            margin: 0;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="header-band">
        <div class="library-name">{{ $libraryName }}</div>
        <div class="card-title">Kartu Anggota Perpustakaan</div>
    </div>

    <div class="card-body">
        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
            <tr>
                <td class="photo-cell">
                    <div class="photo-box">
                        @if($member->photo)
                            <img class="photo" src="{{ storage_path('app/public/' . $member->photo) }}" alt="Foto">
                        @else
                            <div class="photo-box-empty">FOTO</div>
                        @endif
                    </div>
                </td>
                <td class="info-cell">
                    <table class="info-table">
                        <tr>
                            <td class="info-label">No. ID</td>
                            <td class="info-value">: {{ $member->member_code }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Nama</td>
                            <td class="info-value">: <strong>{{ strtoupper(\Illuminate\Support\Str::limit($member->name, 20)) }}</strong></td>
                        </tr>
                        <tr>
                            <td class="info-label">Alamat</td>
                            <td class="info-value">: {{ \Illuminate\Support\Str::limit($member->address ?? '-', 35) }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Jenis / Exp</td>
                            <td class="info-value">: {{ $member->member_type }} / {{ $member->expired_date ? $member->expired_date->format('d/m/Y') : '-' }}</td>
                        </tr>
                    </table>
                </td>
                <td class="qr-cell">
                    <div class="qr-box">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(42)->margin(0)->generate(route('members.show', $member)) !!}
                    </div>
                    <div class="qr-label">Scan Profil</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="card-footer">
        <div class="barcode-text">*{{ $member->barcode ?? $member->member_code }}*</div>
    </div>
</div>

</body>
</html>
