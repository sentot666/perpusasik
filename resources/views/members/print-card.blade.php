<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Anggota - {{ $member->member_code }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .id-card {
            width: 8.56cm; /* Standard ID card size CR80 */
            height: 5.398cm;
            background: linear-gradient(135deg, #1e3a5f 0%, #2b6cb0 100%);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
            color: white;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            margin: 20px;
        }

        .id-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 60%);
            transform: rotate(30deg);
            pointer-events: none;
        }

        .header {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            background: rgba(0,0,0,0.1);
        }

        .logo {
            width: 35px;
            height: 35px;
            background: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }
        
        .logo img {
            width: 25px;
            height: auto;
        }

        .school-name {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.5px;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .school-sub {
            font-size: 8px;
            color: rgba(255,255,255,0.8);
        }

        .body-card {
            display: flex;
            padding: 12px 15px;
            flex: 1;
        }

        .photo {
            width: 60px;
            height: 75px;
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
            border: 2px solid white;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .details {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .name {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .member-type {
            font-size: 9px;
            background: #fbbf24;
            color: #78350f;
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: 600;
            margin-bottom: 8px;
            align-self: flex-start;
        }

        .info-row {
            font-size: 9px;
            margin-bottom: 3px;
            display: flex;
        }

        .info-label {
            width: 45px;
            color: rgba(255,255,255,0.7);
        }

        .info-value {
            font-weight: 600;
        }

        .footer {
            padding: 8px 15px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            background: rgba(0,0,0,0.15);
        }

        .barcode-container {
            background: white;
            padding: 4px;
            border-radius: 2px;
        }

        /* We'll use a simple font-based barcode simulation for demo, but highly suggest using a barcode font or SVG library in production */
        .barcode {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10px;
            color: black;
            font-weight: bold;
            letter-spacing: 1px;
        }
        
        .validity {
            font-size: 8px;
            text-align: right;
            color: rgba(255,255,255,0.8);
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4f46e5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-family: 'Inter', sans-serif;
        }

        @media print {
            body {
                background: white;
                align-items: flex-start;
                justify-content: flex-start;
            }
            .id-card {
                box-shadow: none;
                margin: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>

    <button class="print-btn" onclick="window.print()">Cetak Kartu</button>

    <div class="id-card">
        <div class="header">
            <div class="logo">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1e3a5f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </div>
            <div>
                <div class="school-name">Perpustakaan Makarya</div>
                <div class="school-sub">KARTU ANGGOTA DIGITAL</div>
            </div>
        </div>

        <div class="body-card">
            <div class="photo">
                {{ substr($member->name, 0, 1) }}
            </div>
            <div class="details">
                <div class="name">{{ $member->name }}</div>
                <div class="member-type">{{ $member->member_type }}</div>
                
                <div class="info-row">
                    <div class="info-label">ID</div>
                    <div class="info-value">: {{ $member->member_code }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Gender</div>
                    <div class="info-value">: {{ $member->gender === 'L' ? 'Laki-laki' : ($member->gender === 'P' ? 'Perempuan' : '-') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Kota</div>
                    <div class="info-value">: {{ $member->city ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="barcode-container">
                <div class="barcode">*{{ $member->member_code }}*</div>
            </div>
            <div class="validity">
                Berlaku s/d<br>
                <strong>{{ $member->expired_date ? $member->expired_date->format('d M Y') : 'Seumur Hidup' }}</strong>
            </div>
        </div>
    </div>

</body>
</html>
