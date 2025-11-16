@extends('layouts.vertical-guru', ['subtitle' => 'Scan QR Absensi'])

@section('css')
    @vite(['resources/css/guru/scan-qr.css'])
@endsection

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Absensi'])

    <div class="row">
        <div class="col-lg-6">
            <div class="card card-height-100">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <iconify-icon icon="solar:qr-code-outline" class="fs-20 me-2"></iconify-icon>
                        Generate QR code 
                    </h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Pilih Jadwal Mata Pelajaran</label>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" id="dropdownmapel"
                                data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="true"
                                style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                Pilih Mata Pelajaran
                            </button>
                            <div class="dropdown-menu w-100" aria-labelledby="dropdownmapel" style="max-width: 100%; word-wrap: break-word;">
                                @forelse ($jadwalHariIni as $jadwal)
                                    <button class="dropdown-item text-wrap" type="button" 
                                        data-timetable-id="{{ $jadwal->id }}" 
                                        data-subject-name="{{ $jadwal->classSubject->subject->name }}" 
                                        data-class-name="{{ $jadwal->classSubject->class->name }}" 
                                        data-time="{{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->end_time)->format('H:i') }}"
                                        style="white-space: normal; text-align: left; min-height: auto; padding: 0.5rem 1rem;">
                                        {{ $jadwal->classSubject->subject->name }} - {{ $jadwal->classSubject->class->name }} ({{ \Carbon\Carbon::parse($jadwal->start_time)->format('H:i') }})
                                    </button>
                                @empty
                                    <span class="dropdown-item-text">Tidak ada jadwal mengajar hari ini.</span>
                                @endforelse
                                
                                @if($jadwalHariIni->count() > 0)
                                    <div class="dropdown-divider"></div>
                                    <button class="dropdown-item d-flex align-items-center" type="button" onclick="resetDropdown()">
                                        <iconify-icon icon="solar:close-circle-outline" class="fs-16 me-2" style="color: inherit;"></iconify-icon>
                                        <span>Batalkan Pilihan</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <input type="hidden" id="timetable_id" name="timetable_id" value="">
                        <div class="invalid-feedback">Silakan pilih jadwal terlebih dahulu.</div>
                    </div>

                    <div class="text-center">
                        <div id="qrcode" class="d-flex justify-content-center align-items-center mx-auto" style="display:none; width:280px; height:280px; border: 2px dashed #dee2e6; border-radius: 12px; background-color: #f8f9fa; position: relative; min-height: 280px;">
                            <div class="text-muted text-center">
                                <iconify-icon icon="solar:qr-code-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                                QR Code akan muncul di sini...
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <p class="mb-2" id="qrInfoText"></p>
                            <div class="text-center">
                                <button id="stopSession" class="btn btn-danger btn-lg d-flex align-items-center justify-content-center mx-auto">
                                    <iconify-icon icon="solar:stop-circle-outline" class="fs-18 me-2"></iconify-icon>
                                Hentikan Sesi Absensi
                            </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian kanan tabel hasil pindaian -->
        <div class="col-lg-6">
            <div class="card card-height-100">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <iconify-icon icon="solar:list-check-outline" class="fs-20 me-2"></iconify-icon>
                        Hasil Pindaian Hari Ini
                    </h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                        <table class="table table-hover table-centered" id="scan-results-table">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Jam Masuk</th>
                                    <th scope="col">Jam Keluar</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="initial-message-row">
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted text-center">
                                            <iconify-icon icon="solar:list-check-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                                            Silakan pilih mata pelajaran untuk melihat data.
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- Rekap Riwayat Absensi Hari Ini --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <iconify-icon icon="solar:history-outline" class="fs-20 me-2"></iconify-icon>
                        Rekap Riwayat Absensi Hari Ini
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Mata Pelajaran</th>
                                    <th scope="col">Kelas</th>
                                    <th scope="col">Jam</th>
                                    <th scope="col">Total Siswa</th>
                                    <th scope="col">Hadir</th>
                                    <th scope="col">Terlambat</th>
                                    <th scope="col">Izin</th>
                                    <th scope="col">Sakit</th>
                                    <th scope="col">Alpa</th>
                                    <th scope="col">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rekapRiwayat as $index => $rekap)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $rekap['mata_pelajaran'] }}</td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary py-1 px-2">{{ $rekap['kelas'] }}</span>
                                        </td>
                                        <td>{{ $rekap['jam'] }}</td>
                                        <td>{{ $rekap['total_siswa'] }}</td>
                                        <td>{{ $rekap['hadir'] }}</td>
                                        <td>{{ $rekap['terlambat'] }}</td>
                                        <td>{{ $rekap['izin'] }}</td>
                                        <td>{{ $rekap['sakit'] }}</td>
                                        <td>{{ $rekap['alpa'] }}</td>
                                        <td>
                                            <span class="badge {{ $rekap['status_badge'] }} py-1 px-2">{{ $rekap['persentase'] }}%</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-4">
                                            <div class="text-muted d-flex flex-column align-items-center">
                                                <iconify-icon icon="solar:clipboard-outline" class="fs-48 mb-2"></iconify-icon>
                                                Belum ada data rekap absensi hari ini.
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

    <!-- Modal Konfirmasi Stop Session -->
    <div class="modal fade" id="stopSessionModal" tabindex="-1" aria-labelledby="stopSessionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stopSessionModalLabel">Konfirmasi Hentikan Sesi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <iconify-icon icon="solar:danger-triangle-outline" class="fs-48 text-warning"></iconify-icon>
                    </div>
                    <p class="text-center mb-0">Apakah Anda yakin ingin menghentikan sesi absensi?</p>
                    <p class="text-muted text-center small mt-2">Tindakan ini tidak dapat dibatalkan dan akan menghentikan semua proses absensi yang sedang berlangsung.</p>
                    <input type="hidden" id="stopSessionToken">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger d-flex align-items-center" id="confirmStopSessionButton">
                        <iconify-icon icon="solar:stop-circle-outline" class="fs-16 me-2"></iconify-icon>
                        Ya, Hentikan Sesi
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Notifikasi --}}
    @include('components.pengaturan.notification-modal', [
        'modalId' => 'notificationModal',
        'modalLabelId' => 'notificationModalLabel',
        'messageId' => 'notificationMessage',
        'errorsId' => 'notificationErrors',
        'errorsBodyId' => 'notificationErrorsBody',
        'modalSize' => '',
        'showErrorsTable' => false,
        'showIcon' => true,
        'iconId' => 'notificationIcon'
    ])
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>

    {{-- Inject routes ke window object untuk digunakan oleh JavaScript --}}
    <script>
        window.scanQRRoutes = {
            generateQR: '{{ route("guru.absensi.generate-qr") }}',
            stopSession: '{{ route("guru.absensi.stop-session") }}',
            results: '{{ route("guru.absensi.results", "") }}'
        };
    </script>

    @vite(['resources/js/guru/scan-qr.js'])
@endpush
