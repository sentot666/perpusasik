<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Anggota - {{ $member->name }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 10px;
            background-color: #ffffff;
            color: #333333;
        }
        .card-container {
            width: 222px; /* Approx 3.375 inches */
            height: 133px; /* Approx 2.125 inches */
            border: 1px solid #1e3a5f;
            border-radius: 8px;
            position: relative;
            padding: 8px;
            box-sizing: border-box;
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
        }
        .header {
            border-bottom: 2px solid #1e3a5f;
            padding-bottom: 3px;
            margin-bottom: 6px;
            text-align: center;
        }
        .library-name {
            font-size: 8px;
            font-weight: bold;
            color: #1e3a5f;
            text-transform: uppercase;
            margin: 0;
        }
        .card-title {
            font-size: 6px;
            color: #718096;
            margin: 1px 0 0 0;
            letter-spacing: 0.5px;
        }
        .photo-box {
            width: 40px;
            height: 50px;
            border: 1px solid #cbd5e0;
            float: left;
            margin-right: 8px;
            background-color: #edf2f7;
            text-align: center;
            line-height: 50px;
            font-size: 6px;
            color: #a0aec0;
        }
        .photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .info-box {
            float: left;
            width: 148px;
        }
        .info-row {
            margin-bottom: 3px;
            font-size: 7px;
        }
        .info-label {
            font-weight: bold;
            color: #4a5568;
            display: inline-block;
            width: 45px;
        }
        .info-value {
            color: #2d3748;
        }
        .clear {
            clear: both;
        }
        .footer {
            position: absolute;
            bottom: 6px;
            left: 8px;
            right: 8px;
            text-align: center;
        }
        .barcode-text {
            font-family: monospace;
            font-size: 7px;
            letter-spacing: 2px;
            color: #1a202c;
            margin-top: 2px;
        }
    </style>
</head>
<body>

<div class="card-container">
    <div class="header">
        <h1 class="library-name">{{ $libraryName }}</h1>
        <h2 class="card-title">KARTU ANGGOTA PERPUSTAKAAN</h2>
    </div>

    <div class="photo-box">
        @if($member->photo)
            <img class="photo" src="{{ public_path('storage/' . $member->photo) }}" alt="Foto">
        @else
            FOTO
        @endif
    </div>

    <div class="info-box">
        <div class="info-row">
            <span class="info-label">No. Anggota</span>
            <span class="info-value">: {{ $member->member_code }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Nama</span>
            <span class="info-value">: <strong>{{ strtoupper($member->name) }}</strong></span>
        </div>
        <div class="info-row">
            <span class="info-label">Jenis</span>
            <span class="info-value">: {{ $member->member_type }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Berlaku s/d</span>
            <span class="info-value">: {{ $member->expired_date ? $member->expired_date->format('d/m/Y') : '-' }}</span>
        </div>
    </div>

    <div class="clear"></div>

    <div class="footer">
        {{-- For barcode, we display the generated code in monospace --}}
        <div style="border-top: 1px dashed #cbd5e0; padding-top: 3px;">
            <div class="barcode-text">*{{ $member->barcode ?? $member->member_code }}*</div>
        </div>
    </div>
</div>

</body>
</html>
