<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Buat QR Absensi', 'subtitle' => 'Guru'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row">
        
        <div class="col-lg-5">
            
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Pilih Jadwal Mengajar</h4>
                </div>
                <div class="card-body">
                    <?php if($jadwalHariIni->isEmpty()): ?>
                        <div class="alert alert-warning text-center">
                            Tidak ada jadwal mengajar untuk hari ini.
                        </div>
                    <?php else: ?>
                        <div class="mb-3">
                            <label for="jadwalSelect" class="form-label">Pilih Mata Pelajaran</label>
                            <select class="form-select" id="jadwalSelect">
                                <option selected disabled>-- Pilih Jadwal --</option>
                                <?php $__currentLoopData = $jadwalHariIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $startTime = \Carbon\Carbon::parse($jadwal->start_time)->format('H:i');
                                        $endTime = \Carbon\Carbon::parse($jadwal->end_time)->format('H:i');
                                        $subject = $jadwal->subject->name ?? 'N/A';
                                        $class = $jadwal->classroom->name ?? 'N/A';
                                    ?>
                                    <option value="<?php echo e($jadwal->id); ?>"><?php echo e($startTime); ?> - <?php echo e($endTime); ?> | <?php echo e($subject); ?> | <?php echo e($class); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="card" id="qrCard" style="display: none;">
                <div class="card-header">
                    <h4 class="card-title mb-0">Scan untuk Absensi</h4>
                </div>
                <div class="card-body text-center">
                    <div id="qrcode" class="d-flex justify-content-center p-3"></div>
                    <p class="mt-2 text-muted" id="qrInfoText"></p>
                    <button id="stopSession" class="btn btn-danger mt-2" style="display: none;">Hentikan Sesi Absensi</button>
                </div>
            </div>
        </div>

        
        <div class="col-lg-7">
            <div class="card" id="scanResultsCard" style="display: none;">
                <div class="card-header">
                    <h4 class="card-title mb-0">Hasil Pindaian Real-Time (<span id="scanCount">0</span> Siswa)</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 450px;">
                        <table class="table table-striped table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Siswa</th>
                                    <th>NISN</th>
                                    <th>Waktu Absen</th>
                                </tr>
                            </thead>
                            <tbody id="scanResultsBody">
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Pilih jadwal untuk memulai sesi absensi.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jadwalSelect = document.getElementById('jadwalSelect');
            const qrCard = document.getElementById('qrCard');
            const qrcodeContainer = document.getElementById('qrcode');
            const qrInfoText = document.getElementById('qrInfoText');
            const scanResultsCard = document.getElementById('scanResultsCard');
            const scanResultsBody = document.getElementById('scanResultsBody');
            const scanCount = document.getElementById('scanCount');
            const stopSessionBtn = document.getElementById('stopSession');

            let qrcode = null;
            let scanInterval = null; // Variabel untuk timer

            // Fungsi untuk mengambil hasil pindaian
            function fetchScanResults(timetableId) {
                fetch(`/scan-qr/results/${timetableId}`)
                    .then(response => response.json())
                    .then(data => {
                        scanResultsBody.innerHTML = ''; // Kosongkan tabel
                        scanCount.innerText = data.length; // Update jumlah siswa

                        if (data.length === 0) {
                            scanResultsBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Belum ada siswa yang melakukan absensi.</td></tr>';
                        } else {
                            data.forEach(result => {
                                const row = `<tr>
                                    <td>${result.no}</td>
                                    <td>${result.student_name}</td>
                                    <td>${result.student_nisn}</td>
                                    <td><span class="badge bg-success">${result.check_in_time}</span></td>
                                </tr>`;
                                scanResultsBody.innerHTML += row;
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching scan results:', error);
                    });
            }

            // Event listener saat jadwal dipilih
            jadwalSelect.addEventListener('change', function() {
                const timetableId = this.value;
                if (!timetableId) return;

                // Hentikan interval sebelumnya jika ada
                if (scanInterval) {
                    clearInterval(scanInterval);
                }
                
                // Reset tampilan
                qrCard.style.display = 'block';
                scanResultsCard.style.display = 'block';
                stopSessionBtn.style.display = 'block';
                qrcodeContainer.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Membuat QR...';
                scanResultsBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Memuat data...</td></tr>';

                // 1. Buat QR Code
                fetch(`<?php echo e(route('guru.absensi.generate-qr')); ?>?timetable_id=${timetableId}`)
                    .then(response => response.json())
                    .then(data => {
                        qrcodeContainer.innerHTML = '';
                        new QRCode(qrcodeContainer, {
                            text: JSON.stringify(data),
                            width: 256,
                            height: 256,
                        });
                        const selectedOptionText = jadwalSelect.options[jadwalSelect.selectedIndex].text;
                        qrInfoText.innerText = `Sesi untuk: ${selectedOptionText}`;
                    });

                // 2. Mulai ambil hasil pindaian secara periodik
                fetchScanResults(timetableId); // Panggil pertama kali
                scanInterval = setInterval(() => fetchScanResults(timetableId), 5000); // Ulangi setiap 5 detik
            });

            // Event listener untuk tombol Hentikan Sesi
            stopSessionBtn.addEventListener('click', function() {
                 if (scanInterval) {
                    clearInterval(scanInterval);
                }
                this.style.display = 'none';
                qrCard.style.display = 'none';
                scanResultsCard.style.display = 'none';
                jadwalSelect.selectedIndex = 0;
                 alert('Sesi absensi telah dihentikan.');
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.vertical-guru', ['subtitle' => 'Buat QR Absensi'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\PresenZ\Web-absensi-smkn-4-kendari\resources\views/guru/scan-qr.blade.php ENDPATH**/ ?>