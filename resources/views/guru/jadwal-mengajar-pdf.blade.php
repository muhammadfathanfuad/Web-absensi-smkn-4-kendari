<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Mengajar</title>
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
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        .header img {
            max-width: 100%;
            height: auto;
            margin-bottom: 0;
        }

        .header h2 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 0;
        }

        .header h3 {
            font-size: 14pt;
            font-weight: normal;
            color: #666;
            margin-bottom: 0;
        }

        .info {
            margin-bottom: 0;
            font-size: 9pt;
            text-align: left;
        }

        .info p {
            margin: 3px 0;
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

        .day-cell {
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
            background-color: #f9f9f9;
        }

        .first-row-of-day {
            page-break-before: avoid;
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
        <h2>JADWAL MENGAJAR</h2>
        <h3>SMK Negeri 4 Kendari</h3>
        <div class="info">
            <p><strong>Nama Guru:</strong> {{ $teacherName }}</p>
            <p><strong>Tanggal Export:</strong> {{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Hari</th>
                <th style="width: 15%;">Jam</th>
                <th style="width: 30%;">Mata Pelajaran</th>
                <th style="width: 20%;">Kelas</th>
                <th style="width: 15%;">Jumlah Murid</th>
                <th style="width: 10%;">Durasi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $currentDay = null;
                $dayRowCounts = [];
                
                // Count rows per day
                foreach($jadwals as $jadwal) {
                    $day = $jadwal['hari'];
                    if (!isset($dayRowCounts[$day])) {
                        $dayRowCounts[$day] = 0;
                    }
                    $dayRowCounts[$day]++;
                }
            @endphp
            @foreach($jadwals as $index => $jadwal)
                @php
                    $isNewDay = $currentDay !== $jadwal['hari'];
                    if ($isNewDay) {
                        $currentDay = $jadwal['hari'];
                    }
                    $rowspan = $isNewDay ? $dayRowCounts[$jadwal['hari']] : 0;
                @endphp
                <tr class="{{ $isNewDay ? 'first-row-of-day' : '' }}" style="page-break-inside: avoid;">
                    @if($isNewDay)
                        <td class="day-cell" rowspan="{{ $rowspan }}" style="text-align: center; font-weight: bold; vertical-align: middle; border: 1px solid #333; padding: 6px; background-color: #f9f9f9;">
                            {{ $jadwal['hari'] }}
                        </td>
                    @endif
                    <td style="text-align: center; border: 1px solid #333; padding: 6px;">{{ $jadwal['jam'] ?? '-' }}</td>
                    <td style="border: 1px solid #333; padding: 6px;">{{ $jadwal['mapel'] ?? '-' }}</td>
                    <td style="text-align: center; border: 1px solid #333; padding: 6px;">{{ $jadwal['kelas'] ?? '-' }}</td>
                    <td style="text-align: center; border: 1px solid #333; padding: 6px;">{{ $jadwal['jumlah_murid'] ?? 0 }} Siswa</td>
                    <td style="text-align: center; border: 1px solid #333; padding: 6px;">{{ $jadwal['durasi'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

