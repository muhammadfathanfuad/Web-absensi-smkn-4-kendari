<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kehadiran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 14px;
            font-weight: normal;
        }
        .info {
            margin-bottom: 15px;
        }
        .info p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/kop surat.png') }}" alt="Kop Surat">
        <h1>LAPORAN KEHADIRAN</h1>
        <h2>{{ $reportLabels[$reportType] ?? 'Laporan' }}</h2>
    </div>

    <div class="info">
        <p><strong>Periode:</strong> {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</p>
    </div>

    @if($reportType == 'class')
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kelas</th>
                    <th>Total Siswa</th>
                    <th>Total Record</th>
                    <th>Hadir</th>
                    <th>Terlambat</th>
                    <th>Absen</th>
                    <th>Persentase Hadir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $class)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $class['grade'] }} - {{ $class['name'] }}</td>
                        <td class="text-center">{{ number_format($class['total_students']) }}</td>
                        <td class="text-center">{{ number_format($class['total_records']) }}</td>
                        <td class="text-center">{{ number_format($class['present']) }}</td>
                        <td class="text-center">{{ number_format($class['late']) }}</td>
                        <td class="text-center">{{ number_format($class['absent']) }}</td>
                        <td class="text-center">{{ number_format($class['percentage'], 2) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($reportType == 'student')
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>NIS</th>
                    <th>Kelas</th>
                    <th>Status Kehadiran</th>
                    <th>Hadir</th>
                    <th>Terlambat</th>
                    <th>Absen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $student)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $student['name'] }}</td>
                        <td class="text-center">{{ $student['nis'] }}</td>
                        <td>{{ $student['grade'] }} - {{ $student['class_name'] }}</td>
                        <td class="text-center">
                            @if(isset($student['has_mixed_approval']) && $student['has_mixed_approval'])
                                <span style="color: green; font-weight: bold;">{{ $student['total_approval'] }}</span> | <span style="color: red; font-weight: bold;">{{ $student['total_rejection'] }}</span>
                            @else
                                {{ $student['status_kehadiran'] ?? '-' }}
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($student['present']) }}</td>
                        <td class="text-center">{{ number_format($student['late']) }}</td>
                        <td class="text-center">{{ number_format($student['absent']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($reportType == 'teacher')
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Guru</th>
                    <th>NIP</th>
                    <th>Status Kehadiran</th>
                    <th>Total Pertemuan</th>
                    <th>Total Record</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $teacher)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $teacher['name'] }}</td>
                        <td class="text-center">{{ $teacher['nip'] }}</td>
                        <td class="text-center">{{ $teacher['status_kehadiran'] ?? '-' }}</td>
                        <td class="text-center">{{ number_format($teacher['total_pertemuan']) }}</td>
                        <td class="text-center">{{ number_format($teacher['total_record']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</p>
    </div>
</body>
</html>

