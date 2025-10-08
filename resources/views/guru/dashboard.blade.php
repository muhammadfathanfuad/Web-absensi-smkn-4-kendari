@extends('layouts.vertical-guru', ['subtitle' => 'Dashboard'])

@section('content')

    {{-- Mengubah judul halaman --}}
    @include('layouts.partials.page-title', ['title' => 'Dashboard', 'subtitle' => 'Guru'])

    <div class="row">
        {{-- Jadwal Mengajar Hari Ini --}}
        <div class="col-xl-7">
            <div class="card card-height-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">Jadwal Mengajar Hari Ini</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Jam</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data Jadwal Dinamis --}}
                                @forelse ($jadwalMengajar as $jadwal)
                                    <tr>
                                        <td>{{ $jadwal['jam'] }}</td>
                                        <td>{{ $jadwal['mapel'] }}</td>
                                        <td>{{ $jadwal['kelas'] }}</td>
                                        <td>
                                            @if ($jadwal['status'] == 'Selesai')
                                                <span class="badge bg-soft-success text-success">{{ $jadwal['status'] }}</span>
                                            @elseif ($jadwal['status'] == 'Berlangsung')
                                                <span class="badge bg-soft-warning text-warning">{{ $jadwal['status'] }}</span>
                                            @else
                                                <span class="badge bg-soft-secondary text-secondary">{{ $jadwal['status'] }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada jadwal mengajar hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Jam Mengajar Hari Ini --}}
        <div class="col-xl-5">
            <div class="card card-height-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">Jam Mengajar Hari Ini</h4>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div dir="ltr">
                        {{-- ID ini untuk inisialisasi chart dari JS --}}
                        <div id="jamMengajarChart" class="apex-charts" style="height: 250px;"></div>
                    </div>
                </div>
                 <div class="card-footer bg-transparent border-top-0 text-center">
                    {{-- Label Jam Mengajar Dinamis --}}
                    <h5 class="text-muted">{{ $jamMengajarData['label'] }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Riwayat Mengajar Bulan Ini --}}
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Riwayat Mengajar Bulan Ini</h4>
                </div>
                <div class="card-body">
                    {{-- ID ini untuk inisialisasi chart dari JS --}}
                    <div id="riwayatMengajarChart" class="apex-charts" dir="ltr" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- List Siswa Izin Hari Ini --}}
        <div class="col-lg-6">
            <div class="card card-height-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">List Siswa Izin Hari Ini</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                 {{-- Data Siswa Izin Dinamis --}}
                                @forelse ($siswaIzin as $izin)
                                    <tr>
                                        <td>{{ $izin['nama'] }}</td>
                                        <td>{{ $izin['kelas'] }}</td>
                                        <td>
                                            @if ($izin['keterangan'] == 'Sakit')
                                                <span class="badge bg-soft-warning text-warning">{{ $izin['keterangan'] }}</span>
                                            @else
                                                <span class="badge bg-soft-info text-info">{{ $izin['keterangan'] }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                     <tr>
                                        <td colspan="3" class="text-center">Tidak ada siswa yang izin hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistik Kehadiran & Pengumuman --}}
        <div class="col-lg-6">
            {{-- Statistik Kehadiran Siswa --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Statistik Kehadiran Siswa</h4>
                </div>
                <div class="card-body">
                    {{-- ID ini untuk inisialisasi chart dari JS --}}
                    <div id="statistikKehadiranChart" class="apex-charts" dir="ltr" style="height: 250px;"></div>
                </div>
            </div>
            {{-- Pengumuman --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Pengumuman</h4>
                </div>
                <div class="card-body" style="max-height: 220px; overflow-y: auto;">
                    {{-- Pengumuman Dinamis --}}
                    @forelse ($pengumuman as $item)
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                 <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle text-center">
                                    <iconify-icon icon="{{ $item['icon'] }}" class="fs-24 text-primary avatar-title"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $item['judul'] }}</h6>
                                <small class="text-muted">{{ $item['tanggal'] }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-center">Tidak ada pengumuman.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Meneruskan data dinamis ke JavaScript --}}
    <script>
        // Data dari controller yang akan digunakan oleh JavaScript
        var jamMengajarData = @json($jamMengajarData);
        var riwayatMengajarData = @json($riwayatMengajarData);
        var statistikKehadiranData = @json($statistikKehadiranData);
    </script>
    @vite(['resources/js/pages/dashboard.js'])
@endsection