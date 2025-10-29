<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Jadwal Mengajar'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                        <iconify-icon icon="solar:calendar-outline" class="fs-32 text-primary avatar-title"></iconify-icon>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h4 class="card-title mb-1">
                        Jadwal Mengajar Hari Ini
                    </h4>
                    <p class="text-muted mb-0"><?php echo e(\App\Services\TimeOverrideService::translatedFormat('l, j F Y')); ?></p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Jam</th>
                            <th scope="col">Mata Pelajaran</th>
                            <th scope="col">Kelas</th>
                            <th scope="col">Jumlah Murid</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $jadwalHariIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $currentTime = \App\Services\TimeOverrideService::now();
                                $startTime = \Carbon\Carbon::parse($jadwal->start_time);
                                $endTime = \Carbon\Carbon::parse($jadwal->end_time);
                                $isUpcoming = $startTime->isFuture() && $startTime->diffInMinutes($currentTime) <= 30;
                                $isCurrent = $currentTime->between($startTime, $endTime);
                                $isPast = $endTime->isPast();
                            ?>
                            <tr class="<?php if($isUpcoming): ?> table-warning <?php elseif($isCurrent): ?> table-success <?php endif; ?>">
                                <td>
                                    <span class="fw-semibold"><?php echo e(\Carbon\Carbon::parse($jadwal->start_time)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($jadwal->end_time)->format('H:i')); ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-2">
                                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                <iconify-icon icon="solar:book-outline" class="fs-12"></iconify-icon>
                                            </span>
                                        </div>
                                        <?php echo e($jadwal->classSubject->subject->name ?? 'N/A'); ?>

                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary py-1 px-2">
                                        <?php echo e($jadwal->classSubject->class->name ?? 'N/A'); ?>

                                        <?php if($jadwal->classSubject->class->grade ?? null): ?>
                                            -<?php echo e($jadwal->classSubject->class->grade); ?>

                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info py-1 px-2">
                                        <iconify-icon icon="solar:users-group-rounded-outline" class="fs-12 me-1"></iconify-icon>
                                        <?php echo e($jadwal->jumlah_murid ?? 0); ?> Siswa
                                    </span>
                                </td>
                                <td>
                                    <?php if($isUpcoming): ?>
                                        <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                            <i class="bx bxs-circle text-warning me-1"></i>Segera Dimulai
                                        </span>
                                    <?php elseif($isCurrent): ?>
                                        <span class="badge bg-success-subtle text-success py-1 px-2">
                                            <i class="bx bxs-circle text-success me-1"></i>Sedang Berlangsung
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary py-1 px-2">
                                            <i class="bx bxs-circle text-secondary me-1"></i>Selesai
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted text-center">
                                        <iconify-icon icon="solar:calendar-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                                        Tidak ada jadwal mengajar hari ini.
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-md bg-success bg-opacity-10 rounded-circle">
                        <iconify-icon icon="solar:calendar-mark-outline" class="fs-32 text-success avatar-title"></iconify-icon>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h4 class="card-title mb-1">
                        Jadwal Mengajar Semester Ini
                    </h4>
                    <p class="text-muted mb-0">Jadwal lengkap untuk semester berjalan</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Jadwal Mengajar Semester Ini</h5>
                <button type="button" class="btn btn-outline-success" onclick="printJadwalSemester()">
                    <iconify-icon icon="solar:printer-outline" class="fs-16 me-2"></iconify-icon>
                    Print
                </button>
            </div>
            
            <div class="table-responsive" id="printableJadwalSemester">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Hari</th>
                            <th scope="col">Jam</th>
                            <th scope="col">Mata Pelajaran</th>
                            <th scope="col">Kelas</th>
                            <th scope="col">Jumlah Murid</th>
                            <th scope="col">Durasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayNumber => $dayName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php if(isset($semuaJadwal[$dayNumber]) && $semuaJadwal[$dayNumber]->count() > 0): ?>
                                <?php $__currentLoopData = $semuaJadwal[$dayNumber]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <?php if($index === 0): ?>
                                                <span class="badge bg-primary-subtle text-primary py-1 px-2"><?php echo e($dayName); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="fw-semibold"><?php echo e(\Carbon\Carbon::parse($jadwal->start_time)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($jadwal->end_time)->format('H:i')); ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle bg-success-subtle text-success">
                                                        <iconify-icon icon="solar:book-outline" class="fs-12"></iconify-icon>
                                                    </span>
                                                </div>
                                                <?php echo e($jadwal->classSubject->subject->name ?? 'N/A'); ?>

                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info py-1 px-2">
                                                <?php echo e($jadwal->classSubject->class->name ?? 'N/A'); ?>

                                                <?php if($jadwal->classSubject->class->grade ?? null): ?>
                                                    -<?php echo e($jadwal->classSubject->class->grade); ?>

                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning-subtle text-warning py-1 px-2">
                                                <iconify-icon icon="solar:users-group-rounded-outline" class="fs-12 me-1"></iconify-icon>
                                                <?php echo e($jadwal->jumlah_murid ?? 0); ?> Siswa
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                                $start = \Carbon\Carbon::parse($jadwal->start_time);
                                                $end = \Carbon\Carbon::parse($jadwal->end_time);
                                                $duration = $start->diffInMinutes($end);
                                            ?>
                                            <span class="badge bg-secondary-subtle text-secondary py-1 px-2">
                                                <iconify-icon icon="solar:clock-circle-outline" class="fs-12 me-1"></iconify-icon>
                                                <?php echo e($duration); ?> Menit
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted text-center">
                                        <iconify-icon icon="solar:calendar-mark-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                                        Tidak ada jadwal mengajar untuk semester ini.
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    function printJadwalSemester() {
        // Create a new window for printing
        const printWindow = window.open('', '_blank');
        
        // Get the table HTML and remove badge classes
        const tableContainer = document.getElementById('printableJadwalSemester');
        const tableClone = tableContainer.cloneNode(true);
        
        // Remove badge classes from all elements
        const badges = tableClone.querySelectorAll('.badge');
        badges.forEach(badge => {
            badge.className = badge.className.replace(/badge[^"]*/g, '').trim();
            badge.style.border = 'none';
            badge.style.padding = '0';
            badge.style.backgroundColor = 'transparent';
            badge.style.color = '#000';
        });
        
        const tableHTML = tableClone.innerHTML;
        
        // Create complete print document
        const printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Jadwal Mengajar Semester Ini</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 20px;
                        background: white;
                    }
                    .print-header {
                        text-align: center;
                        margin-bottom: 30px;
                        border-bottom: 2px solid #000;
                        padding-bottom: 15px;
                    }
                    .print-header h3 {
                        margin: 0 0 10px 0;
                        font-size: 18px;
                        font-weight: bold;
                    }
                    .print-header p {
                        margin: 5px 0;
                        font-size: 14px;
                    }
                    .table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                    }
                    .table th,
                    .table td {
                        border: 1px solid #000;
                        padding: 8px;
                        text-align: left;
                    }
                    .table thead th {
                        background-color: #f5f5f5;
                        font-weight: bold;
                    }
                    .badge {
                        border: none !important;
                        padding: 0 !important;
                        font-size: 14px !important;
                        background-color: transparent !important;
                        color: #000 !important;
                        font-weight: normal !important;
                    }
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h3>JADWAL MENGAJAR SEMESTER INI</h3>
                    <p>SMK Negeri 4 Kendari</p>
                    <p>Tanggal Print: ${new Date().toLocaleDateString('id-ID', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    })}</p>
                </div>
                ${tableHTML}
            </body>
            </html>
        `;
        
        // Write content to new window
        printWindow.document.write(printContent);
        printWindow.document.close();
        
        // Wait for content to load then print
        printWindow.onload = function() {
            printWindow.print();
            printWindow.close();
        };
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vertical-guru', ['subtitle' => 'Jadwal Mengajar'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/guru/jadwal-mengajar.blade.php ENDPATH**/ ?>