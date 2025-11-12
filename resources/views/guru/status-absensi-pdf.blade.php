<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Kehadiran Siswa</title>
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
            margin-bottom: 10px;
        }

        .header h2 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header h3 {
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
        <h2>{{ isset($viewType) && $viewType === 'summary' ? 'RINGKASAN KEHADIRAN SISWA' : 'REKAP KEHADIRAN SISWA' }}</h2>
        <h3>SMK Negeri 4 Kendari</h3>
        <div class="info">
            <p><strong>Nama Guru:</strong> {{ $teacherName }}</p>
            <p><strong>Tanggal Export:</strong> {{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</p>
        </div>
        @if(isset($filterInfo) && count($filterInfo) > 0)
        <div class="filter-info">
            @foreach($filterInfo as $info)
            <p>{{ $info }}</p>
            @endforeach
        </div>
        @endif
    </div>

    @if(count($attendances) > 0)
    <table>
        <thead>
            <tr>
                @if(isset($viewType) && $viewType === 'summary')
                    <th style="width: 4%;">No</th>
                    <th style="width: 8%;">NIS</th>
                    <th style="width: 20%;">Nama Siswa</th>
                    <th style="width: 12%;">Kelas</th>
                    <th style="width: 8%;">Hadir</th>
                    <th style="width: 8%;">Terlambat</th>
                    <th style="width: 8%;">Absen</th>
                    <th style="width: 8%;">Izin</th>
                    <th style="width: 8%;">Sakit</th>
                    <th style="width: 8%;">Total</th>
                    <th style="width: 8%;">%</th>
                @else
                    <th style="width: 4%;">No</th>
                    <th style="width: 10%;">Tanggal</th>
                    <th style="width: 8%;">NIS</th>
                    <th style="width: 20%;">Nama Siswa</th>
                    <th style="width: 12%;">Kelas</th>
                    <th style="width: 20%;">Mata Pelajaran</th>
                    <th style="width: 8%;">Jam Masuk</th>
                    <th style="width: 8%;">Status</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
            <tr style="page-break-inside: avoid;">
                <td class="text-center" style="border: 1px solid #333; padding: 6px;">{{ $attendance['no'] }}</td>
                @if(isset($viewType) && $viewType === 'summary')
                    <td class="text-center" style="border: 1px solid #333; padding: 6px;">{{ $attendance['nis'] }}</td>
                    <td style="border: 1px solid #333; padding: 6px;">{{ $attendance['nama'] }}</td>
                    <td class="text-center" style="border: 1px solid #333; padding: 6px;">{{ $attendance['kelas'] }}</td>
                    <td class="text-center" style="border: 1px solid #333; padding: 6px;">{{ $attendance['total_hadir'] ?? 0 }}</td>
                    <td class="text-center" style="border: 1px solid #333; padding: 6px;">{{ $attendance['total_terlambat'] ?? 0 }}</td>
                    <td class="text-center" style="border: 1px solid #333; padding: 6px;">{{ $attendance['total_absen'] ?? 0 }}</td>
                    <td class="text-center" style="border: 1px solid #333; padding: 6px;">{{ $attendance['total_izin'] ?? 0 }}</td>
                    <td class="text-center" style="border: 1px solid #333; padding: 6px;">{{ $attendance['total_sakit'] ?? 0 }}</td>
                    <td class="text-center" style="border: 1px solid #333; padding: 6px;">{{ $attendance['total_pertemuan'] ?? 0 }}</td>
                    <td class="text-center" style="border: 1px solid #333; padding: 6px;">{{ number_format($attendance['persentase'] ?? 0, 2) }}%</td>
                @else
                    <td class="text-center" style="border: 1px solid #333; padding: 6px;">{{ $attendance['tanggal'] ?? '-' }}</td>
                    <td class="text-center" style="border: 1px solid #333; padding: 6px;">{{ $attendance['nis'] }}</td>
                    <td style="border: 1px solid #333; padding: 6px;">{{ $attendance['nama'] }}</td>
                    <td class="text-center" style="border: 1px solid #333; padding: 6px;">{{ $attendance['kelas'] }}</td>
                    <td style="border: 1px solid #333; padding: 6px;">{{ $attendance['mapel'] ?? 'N/A' }}</td>
                    <td class="text-center" style="border: 1px solid #333; padding: 6px;">{{ $attendance['jam_masuk'] ?? '-' }}</td>
                    <td class="text-center" style="border: 1px solid #333; padding: 6px;">{{ $attendance['status'] ?? 'N/A' }}</td>
                @endif
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

