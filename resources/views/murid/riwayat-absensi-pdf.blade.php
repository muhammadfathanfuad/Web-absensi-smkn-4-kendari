<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Absensi</title>
    <style>
        * {
            margin: 10px;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .header img {
            max-width: 100%;
            height: auto;
            margin-bottom: 5px;
        }

        .header h3 {
            font-size: 16pt;
            font-weight: bold;
        }

        .header h4 {
            font-size: 14pt;
            font-weight: normal;
            color: #666;
            margin-bottom: 5px;
        }

        .info {
            margin-bottom: 15px;
            font-size: 9pt;
            text-align: left;
        }

        .info p {
            margin: 3px 0;
        }

        .filter-info {
            margin-bottom: 10px;
            font-size: 9pt;
            padding: 8px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }

        .filter-info p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: auto;
        }

        thead {
            background-color: #f0f0f0;
            display: table-header-group;
        }

        tbody {
            display: table-row-group;
        }

        th {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            font-size: 9pt;
        }

        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
            font-size: 8pt;
        }

        tr {
            page-break-inside: avoid;
        }

        .text-center {
            text-align: center;
        }

        @media print {
            body {
                margin: 0;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/kop surat.png') }}" alt="Kop Surat SMK Negeri 4 Kendari">
        <h3>RIWAYAT ABSENSI SAYA</h3>
        <h4>SMK Negeri 4 Kendari</h4>
        <div class="info">
            <p><strong>Nama Siswa:</strong> {{ $studentName }}</p>
            <p><strong>Tanggal Export:</strong> {{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</p>
            @if(!empty($filterInfo))
                @foreach($filterInfo as $filter)
            <p><strong>{{ $filter }}</strong></p>
                @endforeach
            @endif
        </div>
    </div>

    @if(count($attendances) > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 20%;">Tanggal</th>
                <th style="width: 25%;">Mata Pelajaran</th>
                <th style="width: 12%;">Status</th>
                <th style="width: 12%;">Jam Masuk</th>
                <th style="width: 21%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $index => $attendance)
                <tr>
                    <td>{{ $attendance['tanggal'] }}</td>
                    <td>{{ $attendance['mata_pelajaran'] }}</td>
                    <td class="text-center">{{ $attendance['status'] }}</td>
                    <td class="text-center">{{ $attendance['jam_masuk'] }}</td>
                    <td>{{ $attendance['keterangan'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #999;">
        <p>Tidak ada data absensi untuk ditampilkan.</p>
    </div>
    @endif
</body>
</html>

