@extends('layouts.vertical-guru', ['subtitle' => 'Dashboard'])

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
                        {{-- Desktop: Tombol di samping --}}
                        <div class="text-center d-none d-xl-block">
                            <button type="button" id="btnPresence" class="btn btn-primary btn-lg" onclick="recordPresence()" style="min-width: 200px; text-align: center;">
                                <i class="bx bx-check-circle me-2"></i>
                                <span id="presenceButtonText">Catat Kehadiran</span>
                            </button>
                            <div id="presenceStatus" class="mt-2">
                                <small class="text-muted" id="presenceStatusText"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mobile: Tombol Kehadiran Full Width di Bawah Welcome Card --}}
            <div class="d-xl-none mb-3">
                <div class="card">
                    <div class="card-body">
                        <button type="button" id="btnPresenceMobile" class="btn btn-primary btn-lg w-100" onclick="recordPresence()" style="text-align: center;">
                            <i class="bx bx-check-circle me-2"></i>
                            <span id="presenceButtonTextMobile">Catat Kehadiran</span>
                        </button>
                        <div id="presenceStatusMobile" class="mt-2 text-center">
                            <small class="text-muted" id="presenceStatusTextMobile"></small>
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
        var jamMengajarData = @json($jamMengajarData);
        var statistikMingguanData = @json($statistikMingguan);
        
        let currentRequestId = null;
        let currentAction = null;

        // Load presence status on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadPresenceStatus();
            // Refresh status every 30 seconds
            setInterval(loadPresenceStatus, 30000);
        });

        function updatePresenceButton(button, buttonText, statusText, data) {
            if (!button) return;
            
            const iconElement = button.querySelector('i');
            
            if (data.has_presence) {
                button.disabled = true;
                button.classList.remove('btn-primary', 'btn-secondary');
                button.classList.add('btn-success');
                if (iconElement) {
                    iconElement.className = 'bx bx-check-circle me-2';
                }
                buttonText.textContent = 'Sudah Mencatat Kehadiran';
                
                const statusBadge = {
                    'H': '<span class="badge bg-success">Hadir</span>',
                    'A': '<span class="badge bg-danger">Alfa</span>',
                    'I': '<span class="badge bg-info">Izin</span>',
                    'S': '<span class="badge bg-warning">Sakit</span>'
                };
                
                statusText.innerHTML = 'Status: ' + (statusBadge[data.status] || '') + 
                    (data.check_in_time ? ' | Waktu: ' + new Date(data.check_in_time).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'}) : '');
            } else {
                if (data.is_within_time) {
                    button.disabled = false;
                    button.classList.remove('btn-success', 'btn-secondary');
                    button.classList.add('btn-primary');
                    if (iconElement) {
                        iconElement.className = 'bx bx-check-circle me-2';
                    }
                    buttonText.textContent = 'Catat Kehadiran';
                    statusText.innerHTML = '<small class="text-muted">Waktu: ' + data.current_time + '</small>';
                } else {
                    button.disabled = true;
                    button.classList.remove('btn-primary', 'btn-success');
                    button.classList.add('btn-secondary');
                    if (iconElement) {
                        iconElement.className = 'bx bx-time me-2';
                    }
                    buttonText.textContent = 'Di Luar Waktu';
                    statusText.innerHTML = '<small class="text-warning">Tombol hanya bisa ditekan dari jam 07:00 - 14:45</small>';
                }
            }
        }

        function loadPresenceStatus() {
            fetch('{{ route("guru.presence.today-status") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update desktop button
                        const btnPresence = document.getElementById('btnPresence');
                        const btnText = document.getElementById('presenceButtonText');
                        const statusText = document.getElementById('presenceStatusText');
                        if (btnPresence) {
                            updatePresenceButton(btnPresence, btnText, statusText, data);
                        }
                        
                        // Update mobile button
                        const btnPresenceMobile = document.getElementById('btnPresenceMobile');
                        const btnTextMobile = document.getElementById('presenceButtonTextMobile');
                        const statusTextMobile = document.getElementById('presenceStatusTextMobile');
                        if (btnPresenceMobile) {
                            updatePresenceButton(btnPresenceMobile, btnTextMobile, statusTextMobile, data);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading presence status:', error);
                });
        }

        function recordPresence() {
            const btnPresence = document.getElementById('btnPresence');
            const btnPresenceMobile = document.getElementById('btnPresenceMobile');
            const btnText = document.getElementById('presenceButtonText');
            const btnTextMobile = document.getElementById('presenceButtonTextMobile');
            
            const originalText = btnText ? btnText.textContent : 'Catat Kehadiran';
            
            // Disable both buttons
            if (btnPresence) {
                btnPresence.disabled = true;
                btnText.textContent = 'Memproses...';
            }
            if (btnPresenceMobile) {
                btnPresenceMobile.disabled = true;
                btnTextMobile.textContent = 'Memproses...';
            }
            
            fetch('{{ route("guru.presence.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    loadPresenceStatus();
                } else {
                    showAlert('error', data.message || 'Terjadi kesalahan saat mencatat kehadiran.');
                    if (btnPresence) {
                        btnPresence.disabled = false;
                        btnText.textContent = originalText;
                    }
                    if (btnPresenceMobile) {
                        btnPresenceMobile.disabled = false;
                        btnTextMobile.textContent = originalText;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Terjadi kesalahan saat mencatat kehadiran.');
                if (btnPresence) {
                    btnPresence.disabled = false;
                    btnText.textContent = originalText;
                }
                if (btnPresenceMobile) {
                    btnPresenceMobile.disabled = false;
                    btnTextMobile.textContent = originalText;
                }
            });
        }

        function showDetailModal(requestId) {
            console.log('showDetailModal called with ID:', requestId);
            currentRequestId = requestId;
            
            // Show loading
            document.getElementById('modalBody').innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat detail permohonan...</p>
                </div>
            `;
            
            document.getElementById('modalFooter').innerHTML = '';
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();
            
            // Fetch data
            const url = `{{ route('guru.permohonan-izin-siswa.show', ':id') }}`.replace(':id', requestId);
            console.log('Fetching data from:', url);
            fetch(url)
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        displayRequestDetail(data.data);
                    } else {
                        console.error('API Error:', data.error);
                        document.getElementById('modalBody').innerHTML = `
                            <div class="alert alert-danger">
                                <i class="bx bx-error"></i> ${data.error || 'Terjadi kesalahan saat memuat data.'}
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    document.getElementById('modalBody').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bx bx-error"></i> Terjadi kesalahan saat memuat data.
                        </div>
                    `;
                });
        }

        function displayRequestDetail(request) {
            const statusBadge = getStatusBadge(request.status);
            const leaveTypeDisplay = request.custom_leave_type || request.leave_type;
            
            document.getElementById('modalBody').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Informasi Siswa</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Nama:</strong></td>
                                <td>${request.student.name}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>${request.student.email}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Informasi Izin</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Jenis Izin:</strong></td>
                                <td>${leaveTypeDisplay}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>${statusBadge}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Mulai:</strong></td>
                                <td>${formatDate(request.start_date)}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Selesai:</strong></td>
                                <td>${formatDate(request.end_date)}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-muted">Alasan Izin</h6>
                        <div class="border rounded p-3 bg-light">
                            ${request.reason}
                        </div>
                    </div>
                </div>
                
                ${request.supporting_document && request.document_url ? `
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-muted">Dokumen Pendukung</h6>
                        <button type="button" class="btn btn-outline-primary btn-sm view-document-btn" data-document-url="${String(request.document_url || '').replace(/"/g, '&quot;')}">
                            <i class="bx bx-file"></i> Lihat Dokumen
                        </button>
                    </div>
                </div>
                ` : ''}
            `;
            
            // Add event listeners for view document buttons
            setTimeout(() => {
                document.querySelectorAll('.view-document-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const documentUrl = this.getAttribute('data-document-url');
                        if (documentUrl && window.viewDocument) {
                            window.viewDocument(documentUrl, e);
                        } else {
                            console.error('Document URL not found or viewDocument function not available', {
                                url: documentUrl,
                                function: window.viewDocument
                            });
                        }
                    });
                });
            }, 100);
            
            // Show action buttons based on current teacher's individual status
            const currentTeacherId = {{ Auth::user()->teacher->user_id ?? 'null' }};
            const teacherStatus = request.teacher_status || {};
            const approvedBy = teacherStatus.approved_by || [];
            const rejectedBy = teacherStatus.rejected_by || [];
            
            let actionButtons = '';
            
            if (approvedBy.includes(currentTeacherId)) {
                // This teacher has already approved
                actionButtons = `
                    <button type="button" class="btn btn-success" disabled>
                        <i class="bx bx-check"></i> Sudah Disetujui
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                `;
            } else if (rejectedBy.includes(currentTeacherId)) {
                // This teacher has already rejected
                actionButtons = `
                    <button type="button" class="btn btn-danger" disabled>
                        <i class="bx bx-x"></i> Sudah Ditolak
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                `;
            } else {
                // This teacher hasn't taken action yet - can approve or reject
                actionButtons = `
                    <button type="button" class="btn btn-success" onclick="showConfirmModal('approve', ${request.id})">
                        <i class="bx bx-check"></i> Terima
                    </button>
                    <button type="button" class="btn btn-danger" onclick="showConfirmModal('reject', ${request.id})">
                        <i class="bx bx-x"></i> Tolak
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                `;
            }
            
            document.getElementById('modalFooter').innerHTML = actionButtons;
        }

        function showConfirmModal(action, requestId) {
            currentAction = action;
            currentRequestId = requestId;
            
            const actionText = action === 'approve' ? 'menyetujui' : 'menolak';
            document.getElementById('confirmMessage').textContent = `Apakah Anda yakin ingin ${actionText} permohonan izin ini?`;
            document.getElementById('adminNotes').value = '';
            
            // Close detail modal first
            const detailModal = bootstrap.Modal.getInstance(document.getElementById('detailModal'));
            if (detailModal) {
                detailModal.hide();
            }
            
            // Show confirm modal after detail modal is closed
            setTimeout(() => {
                const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
                confirmModal.show();
            }, 300); // Wait for detail modal to close
        }


        document.getElementById('confirmAction').addEventListener('click', function() {
            if (!currentAction || !currentRequestId) return;
            
            const notes = document.getElementById('adminNotes').value;
            const routeName = currentAction === 'approve' ? 'guru.permohonan-izin-siswa.approve' : 'guru.permohonan-izin-siswa.reject';
            const url = currentAction === 'approve' 
                ? `{{ route('guru.permohonan-izin-siswa.approve', ':id') }}`.replace(':id', currentRequestId)
                : `{{ route('guru.permohonan-izin-siswa.reject', ':id') }}`.replace(':id', currentRequestId);
            
            // Show loading
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ notes: notes })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close confirm modal
                    bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
                    
                    // Show success message
                    showAlert('success', data.message);
                    
                    // Reload page after 1 second
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showAlert('error', data.error || 'Terjadi kesalahan saat memproses permohonan.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Terjadi kesalahan saat memproses permohonan.');
            })
            .finally(() => {
                // Reset button
                this.disabled = false;
                this.innerHTML = 'Konfirmasi';
            });
        });

        function getStatusBadge(status) {
            const badges = {
                'pending': '<span class="badge bg-warning">Menunggu</span>',
                'approved': '<span class="badge bg-success">Disetujui</span>',
                'rejected': '<span class="badge bg-danger">Ditolak</span>',
                'partially_approved': '<span class="badge bg-info">Sebagian Disetujui</span>'
            };
            return badges[status] || '<span class="badge bg-secondary">-</span>';
        }

        function getStatusBadgeClass(status) {
            const classes = {
                'pending': 'warning',
                'approved': 'success',
                'rejected': 'danger',
                'partially_approved': 'info'
            };
            return classes[status] || 'secondary';
        }

        function getStatusText(status) {
            const texts = {
                'pending': 'Menunggu',
                'approved': 'Disetujui',
                'rejected': 'Ditolak',
                'partially_approved': 'Sebagian Disetujui'
            };
            return texts[status] || '-';
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        function formatDateTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'bx-check-circle' : 'bx-error';
            
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <i class="bx ${icon} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            
            // Insert alert at the top of the page
            const container = document.querySelector('.container-fluid');
            container.insertAdjacentHTML('afterbegin', alertHtml);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                const alert = container.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }
        
        // Smooth scroll to list siswa izin hari ini if hash is present
        if (window.location.hash === '#list-siswa-izin-hari-ini') {
            setTimeout(() => {
                const targetElement = document.getElementById('list-siswa-izin-hari-ini');
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    // Add highlight effect
                    targetElement.style.transition = 'box-shadow 0.3s ease';
                    targetElement.style.boxShadow = '0 0 20px rgba(13, 110, 253, 0.5)';
                    setTimeout(() => {
                        targetElement.style.boxShadow = '';
                    }, 2000);
                }
            }, 100);
        }

        // Function to view document in new tab (global scope)
        window.viewDocument = function(documentUrl, event) {
            // Prevent default behavior and stop event propagation if event is provided
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            // Ensure URL is a string
            let decodedUrl = documentUrl;
            if (typeof documentUrl === 'string') {
                // Don't decode if it's already a valid URL
                if (documentUrl.indexOf('%') === -1) {
                    decodedUrl = documentUrl;
                } else {
                    try {
                        decodedUrl = decodeURIComponent(documentUrl);
                    } catch (e) {
                        // If decode fails, use original URL
                        decodedUrl = documentUrl;
                    }
                }
            }
            
            console.log('Opening document in new tab:', decodedUrl);
            
            // Open document in new tab
            // The route will return the file with Content-Disposition: inline header
            // which will display the PDF in browser instead of downloading
            window.open(decodedUrl, '_blank', 'noopener,noreferrer');
        };
    </script>
    @vite(['resources/js/pages/dashboard.js'])
@endsection