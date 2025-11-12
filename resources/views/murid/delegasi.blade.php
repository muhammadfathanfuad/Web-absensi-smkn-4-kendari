@extends('layouts.vertical-murid', ['subtitle' => 'Delegasi Saya'])

@section('content')
@include('layouts.partials.page-title', ['title' => 'Siswa', 'subtitle' => 'Tugas Absensi'])

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">📋 Tugas Absensi dari Guru</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="bx bx-info-circle"></i>
                    <strong>Info:</strong> Sebagai Pengganti, Anda dapat membuka QR Code untuk absensi kelas yang ditunjuk oleh Guru Anda.
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Jam</th>
                                <th>Guru Asli</th>
                                <th>Tipe</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Group delegations by subject, day, and class
                                $groupedDelegations = [];
                                    $dayNames = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                                
                                foreach ($myDelegations as $delegasi) {
                                    if (!is_object($delegasi) || !isset($delegasi->id) || !$delegasi->timetable) {
                                        continue;
                                    }
                                    
                                    $timetable = $delegasi->timetable;
                                    $dayOfWeek = $timetable->day_of_week ?? null;
                                    $subjectId = ($timetable->classSubject && is_object($timetable->classSubject) && $timetable->classSubject->subject) 
                                        ? $timetable->classSubject->subject->id 
                                        : null;
                                    $classId = ($timetable->classSubject && is_object($timetable->classSubject) && $timetable->classSubject->class) 
                                        ? $timetable->classSubject->class->id 
                                        : null;
                                    
                                    // Create unique key based on subject, day, and class
                                    $key = $subjectId . '_' . $dayOfWeek . '_' . $classId;
                                    
                                    if (!isset($groupedDelegations[$key])) {
                                        $classSubject = $timetable->classSubject;
                                        $groupedDelegations[$key] = [
                                            'day_of_week' => $dayOfWeek,
                                            'subject_name' => ($classSubject && is_object($classSubject) && $classSubject->subject) 
                                                ? $classSubject->subject->name 
                                                : 'N/A',
                                            'class_name' => ($classSubject && is_object($classSubject) && $classSubject->class) 
                                                ? $classSubject->class->name 
                                                : 'N/A',
                                            'original_teacher' => ($delegasi->originalTeacher && is_object($delegasi->originalTeacher) && $delegasi->originalTeacher->user && is_object($delegasi->originalTeacher->user)) 
                                                ? $delegasi->originalTeacher->user->full_name 
                                                : 'N/A',
                                            'type' => $delegasi->type ?? 'N/A',
                                            'start_times' => [],
                                            'delegations' => []
                                        ];
                                    }
                                    
                                    // Add start time
                                    if ($timetable->start_time) {
                                        $groupedDelegations[$key]['start_times'][] = $timetable->start_time;
                                    }
                                    
                                    // Add delegation to group
                                    $groupedDelegations[$key]['delegations'][] = $delegasi;
                                }
                                
                                // Find earliest start time for each group
                                foreach ($groupedDelegations as $key => &$group) {
                                    if (!empty($group['start_times'])) {
                                        $earliestStart = null;
                                        foreach ($group['start_times'] as $time) {
                                            if ($time) {
                                                $timeObj = \Carbon\Carbon::parse($time);
                                                if ($earliestStart === null || $timeObj->lt(\Carbon\Carbon::parse($earliestStart))) {
                                                    $earliestStart = $time;
                                                }
                                            }
                                        }
                                        $group['earliest_start'] = $earliestStart;
                                    } else {
                                        $group['earliest_start'] = null;
                                    }
                                }
                                unset($group);
                            @endphp
                            
                            @forelse($groupedDelegations as $key => $group)
                            @php
                                $dayName = isset($dayNames[$group['day_of_week']]) ? $dayNames[$group['day_of_week']] : 'N/A';
                                $timeDisplay = 'N/A';
                                if ($group['earliest_start']) {
                                    $timeDisplay = \Carbon\Carbon::parse($group['earliest_start'])->format('H:i');
                                }
                                
                                // Get first delegation for action check
                                $firstDelegation = $group['delegations'][0];
                                $delegationDayNumber = $firstDelegation->timetable->day_of_week;
                                $todayDayNumber = $today->dayOfWeekIso;
                                $isToday = ($todayDayNumber === $delegationDayNumber);
                                
                                $isWithinTemporaryPeriod = true;
                                if ($firstDelegation->type === 'temporary') {
                                    $validFrom = \Carbon\Carbon::parse($firstDelegation->valid_from)->startOfDay();
                                    $validUntil = \Carbon\Carbon::parse($firstDelegation->valid_until)->endOfDay();
                                    $todayDate = $today->startOfDay();
                                    $isWithinTemporaryPeriod = $todayDate->isBetween($validFrom, $validUntil, true);
                                }
                                
                                // Get first timetable for QR action
                                $firstTimetable = $firstDelegation->timetable;
                                $firstTimetableId = $firstTimetable->id;
                                $firstSubjectName = $group['subject_name'];
                                $firstClassName = $group['class_name'];
                                $firstTimeRange = $timeDisplay;
                            @endphp
                            <tr>
                                <td>{{ $dayName }}</td>
                                <td>{{ $group['subject_name'] }}</td>
                                <td>{{ $group['class_name'] }}</td>
                                <td>{{ $timeDisplay }}</td>
                                <td>{{ $group['original_teacher'] }}</td>
                                <td>
                                    @if($group['type'] == 'permanent')
                                        <span class="badge bg-info">Permanent</span>
                                    @else
                                        <span class="badge bg-warning">Temporary</span>
                                    @endif
                                </td>
                                <td>
                                    @if($isToday && $isWithinTemporaryPeriod)
                                    <button type="button" class="btn btn-sm btn-primary" onclick="openQRModal({{ $firstTimetableId }}, {{ json_encode($firstSubjectName) }}, {{ json_encode($firstClassName) }}, {{ json_encode($firstTimeRange) }})">
                                        <i class="bx bx-qr-scan"></i> Buka QR
                                    </button>
                                    @else
                                    <span class="badge bg-secondary">Belum waktunya</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bx bx-info-circle fs-32"></i>
                                        <p class="mb-0 mt-2">Anda belum memiliki Tugas menggantikan guru untuk mengabsen</p>
                                        <small>Guru akan menunjuk Anda sebagai Pengganti jika diperlukan</small>
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

    <!-- Modal QR Code untuk Delegasi -->
    <div class="modal fade" id="qrModalDelegasi" tabindex="-1" aria-labelledby="qrModalDelegasiLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModalDelegasiLabel">
                        <iconify-icon icon="solar:qr-code-outline" class="fs-20 me-2"></iconify-icon>
                        QR Code Absensi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <div id="qrCodeContainerDelegasi" class="d-flex justify-content-center align-items-center mx-auto" style="width:280px; height:280px; border: 2px dashed #dee2e6; border-radius: 12px; background-color: #f8f9fa; position: relative; min-height: 280px;">
                            <div class="text-muted text-center">
                                <iconify-icon icon="solar:qr-code-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                                QR Code akan muncul di sini...
                            </div>
                        </div>
                        <div class="mt-3">
                            <p class="mb-2" id="qrInfoTextDelegasi"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-danger d-flex align-items-center" id="stopSessionBtnDelegasi" style="display: none;">
                        <iconify-icon icon="solar:stop-circle-outline" class="fs-16 me-2"></iconify-icon>
                        Hentikan Sesi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Stop Session -->
    <div class="modal fade" id="stopSessionModalDelegasi" tabindex="-1" aria-labelledby="stopSessionModalDelegasiLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stopSessionModalDelegasiLabel">Konfirmasi Hentikan Sesi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <iconify-icon icon="solar:danger-triangle-outline" class="fs-48 text-warning"></iconify-icon>
                    </div>
                    <p class="text-center mb-0">Apakah Anda yakin ingin menghentikan sesi absensi?</p>
                    <p class="text-muted text-center small mt-2">Tindakan ini tidak dapat dibatalkan dan akan menghentikan semua proses absensi yang sedang berlangsung.</p>
                    <input type="hidden" id="stopSessionTokenDelegasi">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger d-flex align-items-center" id="confirmStopSessionBtnDelegasi">
                        <iconify-icon icon="solar:stop-circle-outline" class="fs-16 me-2"></iconify-icon>
                        Ya, Hentikan Sesi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Notifikasi -->
    <div id="notificationModalDelegasi" class="modal fade" tabindex="-1" aria-labelledby="notificationModalDelegasiLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalDelegasiLabel">Notifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <iconify-icon id="notificationIconDelegasi" class="fs-48"></iconify-icon>
                    </div>
                    <p class="mb-0 text-center" id="notificationMessageDelegasi"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if QRCode library is loaded
            if (typeof qrcode !== 'undefined') {
                window.QRCodeLoaded = true;
                window.QRCodeType = 'qrcode-generator';
            } else {
                window.QRCodeLoaded = false;
            }

            // Event listener untuk tombol konfirmasi stop session
            document.getElementById('confirmStopSessionBtnDelegasi').addEventListener('click', function() {
                const sessionToken = document.getElementById('stopSessionTokenDelegasi').value;
                if (sessionToken) {
                    stopAttendanceSessionDelegasi(sessionToken);
                }
            });

            // Ensure close buttons work for notification modal
            const notificationModalDelegasi = document.getElementById('notificationModalDelegasi');
            const notificationModalDelegasiInstance = notificationModalDelegasi ? new bootstrap.Modal(notificationModalDelegasi) : null;
            
            if (notificationModalDelegasi) {
                const closeBtn = notificationModalDelegasi.querySelector('.btn-close');
                const dismissBtn = notificationModalDelegasi.querySelector('.btn-light');
                if (closeBtn) {
                    closeBtn.onclick = () => notificationModalDelegasiInstance?.hide();
                }
                if (dismissBtn) {
                    dismissBtn.onclick = () => notificationModalDelegasiInstance?.hide();
                }
            }
        });

        // Fungsi untuk membuka modal QR
        function openQRModal(timetableId, subjectName, className, time) {
            const modal = new bootstrap.Modal(document.getElementById('qrModalDelegasi'));
            modal.show();

            // Reset state
            const qrContainer = document.getElementById('qrCodeContainerDelegasi');
            const qrInfoText = document.getElementById('qrInfoTextDelegasi');
            const stopBtn = document.getElementById('stopSessionBtnDelegasi');

            qrContainer.innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-primary mb-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="text-muted">Membuat QR Code...</div>
                </div>
            `;

            qrInfoText.innerHTML = `
                <div class="alert alert-info mb-0">
                    <strong>${subjectName}</strong> - ${className}<br>
                    <small>Waktu: ${time}</small>
                </div>
            `;

            stopBtn.style.display = 'none';

            // Generate QR Code
            generateQRCodeDelegasi(timetableId, subjectName, className, time);
        }

        // Fungsi untuk generate QR Code
        function generateQRCodeDelegasi(timetableId, subjectName, className, time) {
            const qrContainer = document.getElementById('qrCodeContainerDelegasi');
            const stopBtn = document.getElementById('stopSessionBtnDelegasi');

            fetch('{{ route("murid.delegasi.generate-qr") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    timetable_id: timetableId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }

                window.currentSessionTokenDelegasi = data.session_id;

                if (!window.QRCodeLoaded || typeof qrcode === 'undefined') {
                    qrContainer.innerHTML = `
                        <div class="text-center text-danger">
                            <iconify-icon icon="solar:danger-circle-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                            Library QR Code tidak tersedia. Silakan refresh halaman.
                        </div>
                    `;
                    return;
                }

                generateQRCodeWithLibrary(qrContainer, data, {
                    cellSize: 4,
                    margin: 2,
                    darkColor: '#000000',
                    lightColor: '#ffffff'
                })
                .then(() => {
                    stopBtn.style.display = 'flex';
                    stopBtn.onclick = () => {
                        showStopSessionModalDelegasi(data.session_id);
                    };
                })
                .catch((error) => {
                    qrContainer.innerHTML = `
                        <div class="text-center text-danger">
                            <iconify-icon icon="solar:danger-circle-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                            Gagal menghasilkan QR Code: ${error.message}
                        </div>
                    `;
                });
            })
            .catch(error => {
                qrContainer.innerHTML = `
                    <div class="text-center text-danger">
                        <iconify-icon icon="solar:danger-circle-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                        ${error.message}
                    </div>
                `;
            });
        }

        // Fungsi helper untuk generate QR code dengan library
        function generateQRCodeWithLibrary(container, data, options = {}) {
            if (typeof qrcode === 'undefined') {
                return Promise.reject(new Error('qrcode-generator library not available'));
            }
            
            try {
                const qr = qrcode(0, 'M');
                qr.addData(JSON.stringify(data));
                qr.make();
                
                container.innerHTML = '';
                
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const cellSize = options.cellSize || 4;
                const margin = options.margin || 2;
                
                const size = qr.getModuleCount() * cellSize + margin * 2;
                canvas.width = size;
                canvas.height = size;
                
                ctx.fillStyle = options.lightColor || '#ffffff';
                ctx.fillRect(0, 0, size, size);
                
                ctx.fillStyle = options.darkColor || '#000000';
                for (let row = 0; row < qr.getModuleCount(); row++) {
                    for (let col = 0; col < qr.getModuleCount(); col++) {
                        if (qr.isDark(row, col)) {
                            ctx.fillRect(
                                col * cellSize + margin,
                                row * cellSize + margin,
                                cellSize,
                                cellSize
                            );
                        }
                    }
                }
                
                container.appendChild(canvas);
                return Promise.resolve();
            } catch (error) {
                return Promise.reject(error);
            }
        }

        // Fungsi untuk menampilkan modal konfirmasi stop session
        function showStopSessionModalDelegasi(sessionToken) {
            if (!sessionToken) return;

            const hiddenInput = document.getElementById('stopSessionTokenDelegasi');
            if (hiddenInput) {
                hiddenInput.value = sessionToken;
            }

            const modal = new bootstrap.Modal(document.getElementById('stopSessionModalDelegasi'));
            modal.show();
        }

        // Fungsi untuk stop attendance session
        function stopAttendanceSessionDelegasi(sessionToken) {
            if (!sessionToken) return;

            const confirmBtn = document.getElementById('confirmStopSessionBtnDelegasi');
            const originalText = confirmBtn.innerHTML;

            confirmBtn.innerHTML = `
                <div class="spinner-border spinner-border-sm me-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                Menghentikan...
            `;
            confirmBtn.disabled = true;

            fetch('{{ route("murid.delegasi.stop-session") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    session_token: sessionToken
                })
            })
            .then(response => response.json())
            .then(data => {
                confirmBtn.innerHTML = originalText;
                confirmBtn.disabled = false;

                const stopModal = bootstrap.Modal.getInstance(document.getElementById('stopSessionModalDelegasi'));
                stopModal.hide();

                if (data.success) {
                    showNotificationDelegasi('Sesi absensi berhasil dihentikan', 'success');
                    
                    // Tutup modal QR juga
                    const qrModal = bootstrap.Modal.getInstance(document.getElementById('qrModalDelegasi'));
                    if (qrModal) {
                        qrModal.hide();
                    }
                    
                    // Reset state
                    document.getElementById('qrCodeContainerDelegasi').innerHTML = `
                        <div class="text-muted text-center">
                            <iconify-icon icon="solar:qr-code-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                            QR Code akan muncul di sini...
                        </div>
                    `;
                    document.getElementById('stopSessionBtnDelegasi').style.display = 'none';
                } else {
                    showNotificationDelegasi('Gagal menghentikan sesi: ' + (data.error || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                confirmBtn.innerHTML = originalText;
                confirmBtn.disabled = false;
                showNotificationDelegasi('Terjadi kesalahan saat menghentikan sesi: ' + error.message, 'error');
            });
        }

        // Fungsi untuk menampilkan notifikasi
        function showNotificationDelegasi(message, type = 'info') {
            const modalEl = document.getElementById('notificationModalDelegasi');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            const modalLabel = document.getElementById('notificationModalDelegasiLabel');
            const modalMessage = document.getElementById('notificationMessageDelegasi');
            const modalIcon = document.getElementById('notificationIconDelegasi');
            
            if (type === 'success') {
                modalLabel.textContent = 'Berhasil';
                modalIcon.setAttribute('icon', 'solar:check-circle-outline');
                modalIcon.className = 'fs-48 text-success';
            } else if (type === 'error') {
                modalLabel.textContent = 'Gagal';
                modalIcon.setAttribute('icon', 'solar:danger-circle-outline');
                modalIcon.className = 'fs-48 text-danger';
            } else {
                modalLabel.textContent = 'Informasi';
                modalIcon.setAttribute('icon', 'solar:info-circle-outline');
                modalIcon.className = 'fs-48 text-info';
            }
            
            modalMessage.textContent = message;

            const closeBtn = modalEl.querySelector('.btn-close');
            const dismissBtn = modalEl.querySelector('.btn-light');
            if (closeBtn) {
                closeBtn.onclick = () => modal.hide();
            }
            if (dismissBtn) {
                dismissBtn.onclick = () => modal.hide();
            }

            modal.show();
        }
    </script>
@endpush

