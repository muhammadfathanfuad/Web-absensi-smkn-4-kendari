<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
        }
        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
        }
        .kop-surat img {
            max-width: 100%;
            height: auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
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
            font-size: 10px;
        }
        .info p {
            margin: 3px 0;
        }
        .section-title {
            margin-top: 25px;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            background-color: #e0e0e0;
            padding: 8px;
            border: 1px solid #333;
        }
        .section-title.page-break-before {
            page-break-before: always;
            margin-top: 0;
        }
        .section-wrapper {
            page-break-before: always;
            page-break-inside: avoid;
        }
        .section-wrapper-first {
            page-break-inside: avoid;
        }
        .table-section {
            page-break-inside: avoid;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
            font-size: 10px;
        }
        table thead {
            display: table-header-group;
        }
        table tbody {
            display: table-row-group;
        }
        table th, table td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        table tr {
            page-break-inside: avoid;
        }
        table td {
            vertical-align: top;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #333;
            font-size: 9px;
            text-align: center;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="kop-surat">
        <img src="{{ public_path('images/kop surat.png') }}" alt="Kop Surat SMK Negeri 4 Kendari">
    </div>

    <div class="header">
        <h1>SMK NEGERI 4 KENDARI</h1>
        <h2>DATA SEMUA USER</h2>
    </div>

    <div class="info">
        <p><strong>Tanggal Export:</strong> {{ $exportDate }}</p>
        <p><strong>Total Guru:</strong> {{ count($teachers) }} orang | <strong>Total Siswa:</strong> {{ $studentsByClass->sum(function($students) { return count($students); }) }} orang</p>
    </div>

    <!-- Tabel Guru -->
    <div class="section-wrapper-first">
        <div class="section-title">DATA GURU</div>
        <table class="table-section">
            <thead>
                <tr>
                    <th style="width: 8%;">No</th>
                    <th style="width: 30%;">Nama Lengkap</th>
                    <th style="width: 30%;">Email</th>
                    <th style="width: 15%;">Kelas</th>
                    <th style="width: 17%;">Password Default</th>
                </tr>
            </thead>
            <tbody>
                @if(count($teachers) > 0)
                    @foreach($teachers as $index => $user)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $user->full_name ?? '-' }}</td>
                        <td>{{ $user->email ?? '-' }}</td>
                        <td class="text-center">{{ $user->classroom_info ?? '-' }}</td>
                        <td class="text-center">{{ $user->default_password ?? 'password' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data guru</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Tabel Siswa per Kelas -->
    @if($studentsByClass->count() > 0)
        @foreach($studentsByClass as $className => $students)
        <div class="section-wrapper">
            <div class="section-title">DATA SISWA - KELAS {{ $className }}</div>
            <table class="table-section">
            <thead>
                <tr>
                    <th style="width: 8%;">No</th>
                    <th style="width: 35%;">Nama Lengkap</th>
                    <th style="width: 35%;">Email</th>
                    <th style="width: 22%;">Password Default</th>
                </tr>
            </thead>
            <tbody>
                @if(count($students) > 0)
                    @foreach($students as $index => $user)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $user->full_name ?? '-' }}</td>
                        <td>{{ $user->email ?? '-' }}</td>
                        <td class="text-center">{{ $user->default_password ?? 'password' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data siswa</td>
                    </tr>
                @endif
            </tbody>
        </table>
        </div>
        @endforeach
    @else
        <div class="section-wrapper">
            <div class="section-title">DATA SISWA</div>
            <table class="table-section">
            <thead>
                <tr>
                    <th style="width: 8%;">No</th>
                    <th style="width: 35%;">Nama Lengkap</th>
                    <th style="width: 35%;">Email</th>
                    <th style="width: 22%;">Password Default</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data siswa</td>
                </tr>
            </tbody>
        </table>
        </div>
    @endif

    <div class="footer">
        <p>Dokumen ini di-generate otomatis oleh Sistem Absensi SMK Negeri 4 Kendari</p>
        <p>Export dilakukan pada: {{ $exportDate }}</p>
    </div>
</body>
</html>

