<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Pelajaran Kelas {{ $grade }}</title>
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
            margin-bottom: 5px;
            padding-bottom: 5px;
        }

        .header img {
            max-width: 100%;
            height: auto;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 16pt;
            font-weight: bold;
        }

        .header h3 {
            font-size: 14pt;
            font-weight: normal;
            color: #666;
        }

        .info {
            margin-bottom: 15px;
            font-size: 9pt;
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

        .day-cell-continued {
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
            background-color: #f0f0f0;
            border-left: 2px solid #333;
        }

        .first-row-of-day {
            page-break-before: avoid;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #333;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }

        @page {
            margin: 1cm;
        }

        @media print {
            thead {
                display: table-header-group;
            }
            
            tbody {
                display: table-row-group;
            }
            
            tr {
                page-break-inside: avoid;
            }
            
            /* Ensure table structure is maintained across pages */
            table {
                border-collapse: collapse;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/kop surat.png') }}" alt="Kop Surat">
        <h2>JADWAL PELAJARAN</h2>
        <h3>Kelas {{ $grade }}</h3>
    </div>

    @if($grade == 'XI' && isset($groupInfo))
    <div class="info">
        <p><strong>Semester:</strong> {{ $termName }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ date('d F Y') }}</p>
        <p><strong>Kelompok:</strong> {{ $groupInfo['type'] ?? 'Semua Kelompok' }}</p>
        <p><strong>Minggu:</strong> {{ $groupInfo['week'] ?? 'Semua Minggu' }}</p>
    </div>
    @endif

    @if($jadwals && $jadwals->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">Hari</th>
                    <th style="width: 15%;">Jam</th>
                    <th style="width: 18%;">Kelas</th>
                    <th style="width: 25%;">Mata Pelajaran</th>
                    <th style="width: 20%;">Guru</th>
                    <th style="width: 12%;">Jenis Kelas</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $currentDay = null;
                    $previousDay = null;
                @endphp
                @foreach($jadwals as $index => $jadwal)
                    @php
                        $isNewDay = $currentDay !== $jadwal['hari'];
                        if ($isNewDay) {
                            $previousDay = $currentDay;
                            $currentDay = $jadwal['hari'];
                        }
                        
                        // Determine if this is the first row of a day
                        $isFirstRowOfDay = $isNewDay;
                        
                        // Check if previous row was a different day (for page break detection)
                        $previousWasDifferentDay = $index > 0 && $jadwals[$index - 1]['hari'] !== $jadwal['hari'];
                    @endphp
                    <tr class="{{ $isFirstRowOfDay ? 'first-row-of-day' : '' }}" style="page-break-inside: avoid;">
                        @if($isFirstRowOfDay)
                            {{-- First row of day: always show day --}}
                            <td class="day-cell" style="text-align: center; font-weight: bold; vertical-align: middle; border: 1px solid #333; padding: 6px; background-color: #f9f9f9;">
                                {{ $jadwal['hari'] }}
                            </td>
                        @else
                            {{-- Continuation row: show empty cell to maintain table structure --}}
                            <td style="border: 1px solid #333; padding: 6px; background-color: #f9f9f9;"></td>
                        @endif
                        <td style="text-align: center; border: 1px solid #333; padding: 6px;">{{ $jadwal['jam'] ?? '-' }}</td>
                        <td style="border: 1px solid #333; padding: 6px;">{{ $jadwal['kelas'] ?? '-' }}</td>
                        <td style="border: 1px solid #333; padding: 6px;">{{ $jadwal['mapel'] ?? '-' }}</td>
                        <td style="border: 1px solid #333; padding: 6px;">{{ $jadwal['guru'] ?? '-' }}</td>
                        <td style="text-align: center; border: 1px solid #333; padding: 6px;">{{ $jadwal['jenis'] ?? 'Teori' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 40px; color: #999;">
            <p>Tidak ada data jadwal untuk ditampilkan.</p>
        </div>
    @endif

    <div class="footer">
        <p>Dicetak pada {{ date('d F Y H:i:s') }}</p>
    </div>
</body>
</html>

