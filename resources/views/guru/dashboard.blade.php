@extends('layouts.vertical-guru', ['subtitle' => 'Dashboard'])

@section('css')
    @vite(['resources/css/guru/dashboard.css'])
@endsection

@section('content')

    {{-- Mengubah judul halaman --}}
    @include('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Dashboard'])

    {{-- Welcome Card dan Chart Jam Mengajar --}}
    <div class="row">
        <div class="col-xl-8">
            {{-- Welcome Card --}}
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                                <iconify-icon icon="solar:user-outline" class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">Selamat Datang, {{ $namaGuru }}!</h5>
                            <p class="text-muted mb-0">Dashboard Guru - {{ \App\Services\TimeOverrideService::translatedFormat('l, j F Y') }}</p>
                            </div>
                        </div>
                        {{-- Status Kehadiran (Read-only) - Kotak di Kanan --}}
                        <div class="flex-shrink-0 ms-3 d-none d-xl-block" style="max-width: 130px;">
                            <div class="presence-status-box text-center p-2 rounded border" id="presenceStatusBox" style="width: 100%;">
                                <i class="bx bx-time fs-20 text-muted mb-1 d-block" id="presenceIcon"></i>
                                <div class="presence-status-badge" id="presenceStatusBadge"></div>
                            </div>
                            <div class="mt-1 text-center">
                                <small class="text-muted d-block" id="presenceStatusText" style="font-size: 0.7rem; line-height: 1.2;">Memuat...</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mobile: Status Kehadiran --}}
            <div class="d-xl-none mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="presence-status-box text-center p-2 rounded border" id="presenceStatusBoxMobile" style="min-width: 100px; max-width: 140px;">
                                <i class="bx bx-time fs-20 text-muted mb-1" id="presenceIconMobile"></i>
                                <div class="presence-status-badge" id="presenceStatusBadgeMobile"></div>
                            </div>
                        </div>
                        <div class="mt-2 text-center">
                            <small class="text-muted" id="presenceStatusTextMobile" style="font-size: 0.75rem; line-height: 1.2;">Memuat...</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistik Cards - Desktop Layout --}}
            <div class="row d-none d-xl-flex">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                                        <iconify-icon icon="solar:calendar-outline" class="fs-32 text-primary avatar-title"></iconify-icon>
                                    </div>
                                </div>
                                <div class="col-6 text-end">
                                    <p class="text-muted mb-0 text-truncate">Total Jadwal</p>
                                    <h3 class="text-dark mt-2 mb-0">{{ $totalJadwalHariIni }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-md bg-success bg-opacity-10 rounded-circle">
                                        <iconify-icon icon="solar:check-circle-outline" class="fs-32 text-success avatar-title"></iconify-icon>
                                    </div>
                                </div>
                                <div class="col-6 text-end">
                                    <p class="text-muted mb-0 text-truncate">Selesai</p>
                                    <h3 class="text-dark mt-2 mb-0">{{ $jadwalSelesai }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-none d-xl-flex">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-md bg-warning bg-opacity-10 rounded-circle">
                                        <iconify-icon icon="solar:clock-circle-outline" class="fs-32 text-warning avatar-title"></iconify-icon>
                                    </div>
                                </div>
                                <div class="col-6 text-end">
                                    <p class="text-muted mb-0 text-truncate">Berlangsung</p>
                                    <h3 class="text-dark mt-2 mb-0">{{ $jadwalBerlangsung }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-md bg-danger bg-opacity-10 rounded-circle">
                                        <iconify-icon icon="solar:user-minus-outline" class="fs-32 text-danger avatar-title"></iconify-icon>
                                    </div>
                                </div>
                                <div class="col-6 text-end">
                                    <p class="text-muted mb-0 text-truncate">Siswa Izin</p>
                                    <h3 class="text-dark mt-2 mb-0">{{ count($siswaIzin) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart Jam Mengajar - Pojok Kanan Atas --}}
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Jam Mengajar Hari Ini</h5>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        {{-- ID ini untuk inisialisasi chart dari JS --}}
                        <div id="jamMengajarChart" class="apex-charts" style="height: 200px;"></div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 text-center">
                    {{-- Label Jam Mengajar Dinamis --}}
                    <h6 class="text-muted mb-0">{{ $jamMengajarData['label'] }}</h6>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Cards - Mobile Layout --}}
    <div class="row d-xl-none">
        <div class="col-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                                <iconify-icon icon="solar:calendar-outline" class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-0 text-truncate">Total Jadwal</p>
                            <h3 class="text-dark mt-2 mb-0">{{ $totalJadwalHariIni }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-md bg-success bg-opacity-10 rounded-circle">
                                <iconify-icon icon="solar:check-circle-outline" class="fs-32 text-success avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-0 text-truncate">Selesai</p>
                            <h3 class="text-dark mt-2 mb-0">{{ $jadwalSelesai }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-md bg-warning bg-opacity-10 rounded-circle">
                                <iconify-icon icon="solar:clock-circle-outline" class="fs-32 text-warning avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-0 text-truncate">Berlangsung</p>
                            <h3 class="text-dark mt-2 mb-0">{{ $jadwalBerlangsung }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="avatar-md bg-danger bg-opacity-10 rounded-circle">
                                <iconify-icon icon="solar:user-minus-outline" class="fs-32 text-danger avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-0 text-truncate">Siswa Izin</p>
                            <h3 class="text-dark mt-2 mb-0">{{ count($siswaIzin) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Jadwal Mengajar Hari Ini - Full Width --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Jadwal Mengajar Hari Ini</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Jam</th>
                                    <th scope="col">Mata Pelajaran</th>
                                    <th scope="col">Kelas</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data Jadwal Dinamis --}}
                                @forelse ($jadwalMengajar as $index => $jadwal)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="fw-semibold">{{ $jadwal['jam'] }}</span>
                                        </td>
                                        <td>{{ $jadwal['mapel'] }}</td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary py-1 px-2">{{ $jadwal['kelas'] }}</span>
                                        </td>
                                        <td>
                                            @if ($jadwal['status'] == 'Selesai')
                                                <span class="badge bg-success-subtle text-success py-1 px-2">
                                                    <i class="bx bxs-circle text-success me-1"></i>{{ $jadwal['status'] }}
                                                </span>
                                            @elseif ($jadwal['status'] == 'Berlangsung')
                                                <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                                    <i class="bx bxs-circle text-warning me-1"></i>{{ $jadwal['status'] }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary py-1 px-2">
                                                    <i class="bx bxs-circle text-secondary me-1"></i>{{ $jadwal['status'] }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted d-flex flex-column align-items-center">
                                                <iconify-icon icon="solar:calendar-x-outline" class="fs-48 mb-2"></iconify-icon>
                                                Tidak ada jadwal mengajar hari ini.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        {{-- List Siswa Izin Hari Ini --}}
        <div class="col-lg-6">
            <div class="card card-height-100" id="list-siswa-izin-hari-ini">
                <div class="card-header">
                    <h4 class="card-title mb-0">List Siswa Izin Hari Ini</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Siswa</th>
                                    <th scope="col">Kelas</th>
                                    <th scope="col">Jenis Izin</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                 {{-- Data Siswa Izin Dinamis --}}
                                @forelse ($siswaIzin as $index => $izin)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $izin['nama'] }}</td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary py-1 px-2">{{ $izin['kelas'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info py-1 px-2">
                                                <i class="bx bxs-circle text-info me-1"></i>{{ $izin['keterangan'] }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($izin['status'] == 'pending')
                                                <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                                    <i class="bx bxs-circle text-warning me-1"></i>Menunggu
                                                </span>
                                            @elseif ($izin['status'] == 'approved')
                                                <span class="badge bg-success-subtle text-success py-1 px-2">
                                                    <i class="bx bxs-circle text-success me-1"></i>Disetujui
                                                </span>
                                            @elseif ($izin['status'] == 'rejected')
                                                <span class="badge bg-danger-subtle text-danger py-1 px-2">
                                                    <i class="bx bxs-circle text-danger me-1"></i>Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        onclick="showDetailModal({{ $izin['id'] }})">
                                                    <i class="bx bx-show"></i> Detail
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                     <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted d-flex flex-column align-items-center">
                                                <iconify-icon icon="solar:check-circle-outline" class="fs-48 mb-2"></iconify-icon>
                                                Tidak ada permohonan izin hari ini.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart Statistik Mingguan --}}
        <div class="col-lg-6">
            <div class="card card-height-100">
                <div class="card-header">
                    <h4 class="card-title mb-0">Statistik Mingguan</h4>
                </div>
                <div class="card-body">
                    <div dir="ltr">
                        <div id="statistikMingguanChart" class="apex-charts"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Rekap Kehadiran Siswa Hari Ini --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Rekap Kehadiran Siswa Hari Ini</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Siswa</th>
                                    <th scope="col">Kelas</th>
                                    <th scope="col">Mata Pelajaran</th>
                                    <th scope="col">Jam Masuk</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rekapKehadiran as $index => $kehadiran)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $kehadiran['nama'] }}</td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary py-1 px-2">{{ $kehadiran['kelas'] }}</span>
                                        </td>
                                        <td>{{ $kehadiran['mapel'] }}</td>
                                        <td>
                                            <span class="fw-semibold">{{ $kehadiran['jam_masuk'] }}</span>
                                        </td>
                                        <td>
                                            @if($kehadiran['status'] == 'H')
                                                <span class="badge bg-success-subtle text-success py-1 px-2">
                                                    <i class="bx bxs-circle text-success me-1"></i>Hadir
                                                </span>
                                            @elseif($kehadiran['status'] == 'S')
                                                <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                                    <i class="bx bxs-circle text-warning me-1"></i>Sakit
                                                </span>
                                            @elseif($kehadiran['status'] == 'I')
                                                <span class="badge bg-info-subtle text-info py-1 px-2">
                                                    <i class="bx bxs-circle text-info me-1"></i>Izin
                                                </span>
                                            @elseif($kehadiran['status'] == 'T')
                                                <span class="badge bg-danger-subtle text-danger py-1 px-2">
                                                    <i class="bx bxs-circle text-danger me-1"></i>Terlambat
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary py-1 px-2">
                                                    <i class="bx bxs-circle text-secondary me-1"></i>{{ $kehadiran['status'] }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted d-flex flex-column align-items-center">
                                                <iconify-icon icon="solar:clipboard-outline" class="fs-48 mb-2"></iconify-icon>
                                                Belum ada data kehadiran siswa hari ini.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Permohonan Izin -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Permohonan Izin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer" id="modalFooter">
                    <!-- Action buttons will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmMessage"></p>
                    <div class="mb-3">
                        <label for="adminNotes" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="adminNotes" rows="3" placeholder="Berikan catatan untuk siswa..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmAction">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    {{-- Meneruskan data dinamis ke JavaScript --}}
    <script>
        // Data dari controller yang akan digunakan oleh JavaScript
        window.jamMengajarData = @json($jamMengajarData);
        window.statistikMingguanData = @json($statistikMingguan);
        window.presenceStatusRoute = '{{ route("guru.presence.today-status") }}';
        window.showLeaveRequestRoute = '{{ route('guru.permohonan-izin-siswa.show', ':id') }}';
        window.approveLeaveRequestRoute = '{{ route('guru.permohonan-izin-siswa.approve', ':id') }}';
        window.rejectLeaveRequestRoute = '{{ route('guru.permohonan-izin-siswa.reject', ':id') }}';
        window.currentTeacherId = {{ Auth::user()->teacher->user_id ?? 'null' }};
    </script>
    @vite(['resources/js/guru/dashboard.js'])
@endsection