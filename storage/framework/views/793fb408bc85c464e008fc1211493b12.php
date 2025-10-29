<?php $__env->startSection('title', 'Riwayat Absensi'); ?>


<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Riwayat Absensi</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Siswa</li>
                        <li class="breadcrumb-item active">Riwayat Absensi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="bx bx-info-circle me-2"></i>
                                <div>
                                    <strong>Total Data:</strong> <?php echo e($attendances->total()); ?> absensi
                                    <?php if($from && $to): ?>
                                        <span class="ms-3"><strong>Filter:</strong> <?php echo e(\Carbon\Carbon::parse($from)->format('d M Y')); ?> - <?php echo e(\Carbon\Carbon::parse($to)->format('d M Y')); ?></span>
                                    <?php else: ?>
                                        <span class="ms-3"><strong>Menampilkan:</strong> Semua data absensi</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="GET" class="row mb-3" id="filter-form">
                        <div class="col-md-4">
                            <label for="date-range" class="form-label">Filter berdasarkan tanggal (opsional):</label>
                            <input type="text" id="date-range" class="form-control" placeholder="Pilih rentang tanggal..." value="<?php echo e(($from && $to) ? $from.' to '.$to : ''); ?>">
                            <input type="hidden" name="from" id="date-from" value="<?php echo e($from ?? ''); ?>">
                            <input type="hidden" name="to" id="date-to" value="<?php echo e($to ?? ''); ?>">
                        </div>
                        <div class="col-md-3 align-self-end">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <a href="<?php echo e(route('murid.absensi')); ?>" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>

                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Riwayat Absensi Saya</h5>
                        <button type="button" class="btn btn-outline-primary" onclick="printRiwayatAbsensi()">
                            <iconify-icon icon="solar:printer-outline" class="fs-16 me-2"></iconify-icon>
                            Print
                        </button>
                    </div>

                    <div class="table-responsive" id="printableRiwayatAbsensi">
                        <table class="table table-bordered table-striped table-hover dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Status</th>
                                    <th>Jam Masuk</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $attendances ?? collect(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e(optional($att->created_at)->format('d F Y')); ?></td>
                                        <td><?php echo e(optional(optional(optional($att->classSession)->timetable)->classSubject)->subject->name ?? '—'); ?></td>
                                        <td>
                                            <?php switch($att->status):
                                                case ('H'): ?>
                                                    <span class="badge bg-success">Hadir</span>
                                                    <?php break; ?>
                                                <?php case ('I'): ?>
                                                    <span class="badge bg-warning text-dark">Izin</span>
                                                    <?php break; ?>
                                                <?php case ('S'): ?>
                                                    <span class="badge bg-info">Sakit</span>
                                                    <?php break; ?>
                                                <?php case ('T'): ?>
                                                    <span class="badge bg-warning">Terlambat</span>
                                                    <?php break; ?>
                                                <?php case ('A'): ?>
                                                    <span class="badge bg-danger">Alpa</span>
                                                    <?php break; ?>
                                                <?php default: ?>
                                                    <span class="badge bg-secondary"><?php echo e($att->status); ?></span>
                                            <?php endswitch; ?>
                                        </td>
                                        <td>
                                            <?php if($att->check_in_time): ?>
                                                <?php echo e(\Carbon\Carbon::parse($att->check_in_time)->format('H:i')); ?>

                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                                // Format keterangan berdasarkan status seperti di halaman jadwal pelajaran
                                                $notes = '';
                                                
                                                if ($att->status === 'H') {
                                                    // Hadir - show scan time
                                                    if ($att->check_in_time) {
                                                        $checkInTime = \Carbon\Carbon::parse($att->check_in_time)->format('H:i');
                                                        $notes = "Hadir tepat waktu (Scan: {$checkInTime})";
                                                    } else {
                                                        $notes = 'Hadir tepat waktu';
                                                    }
                                                } elseif ($att->status === 'T') {
                                                    // Terlambat - show late time and scan time
                                                    $lateMinutes = abs(round($att->late_minutes ?? 0));
                                                    
                                                    // Format late time
                                                    if ($lateMinutes === 0) {
                                                        $timeFormat = '0 menit';
                                                    } elseif ($lateMinutes < 60) {
                                                        $timeFormat = "{$lateMinutes} menit";
                                                    } else {
                                                        $hours = floor($lateMinutes / 60);
                                                        $remainingMinutes = $lateMinutes % 60;
                                                        if ($remainingMinutes === 0) {
                                                            $timeFormat = "{$hours} jam";
                                                        } else {
                                                            $timeFormat = "{$hours} jam {$remainingMinutes} menit";
                                                        }
                                                    }
                                                    
                                                    if ($att->check_in_time) {
                                                        $checkInTime = \Carbon\Carbon::parse($att->check_in_time)->format('H:i');
                                                        $notes = "Terlambat {$timeFormat} (Scan: {$checkInTime})";
                                                    } else {
                                                        $notes = "Terlambat {$timeFormat}";
                                                    }
                                                } elseif ($att->status === 'A') {
                                                    $notes = 'Tidak hadir - tidak melakukan scan';
                                                } elseif ($att->status === 'I') {
                                                    $notes = 'Izin';
                                                } elseif ($att->status === 'S') {
                                                    $notes = 'Sakit';
                                                } else {
                                                    $notes = $att->notes ?? '-';
                                                }
                                                
                                                // Add check-out time if available
                                                if ($att->check_out_time) {
                                                    $checkOutTime = \Carbon\Carbon::parse($att->check_out_time)->format('H:i');
                                                    $notes .= " (Keluar: {$checkOutTime})";
                                                }
                                            ?>
                                            <?php echo e($notes ?: '-'); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <?php if($from && $to): ?>
                                                Tidak ada data absensi dalam rentang tanggal yang dipilih.
                                            <?php else: ?>
                                                Belum ada data absensi.
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    
                    <?php if($attendances->hasPages()): ?>
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <div class="text-muted">
                                Menampilkan <?php echo e($attendances->firstItem()); ?> sampai <?php echo e($attendances->lastItem()); ?> dari <?php echo e($attendances->total()); ?> data
                            </div>
                            <div class="d-flex">
                                <?php echo e($attendances->appends(request()->query())->links('pagination::bootstrap-4')); ?>

                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Inisialisasi Flatpickr untuk filter rentang tanggal
        const fp = flatpickr("#date-range", {
            mode: "range",
            dateFormat: "Y-m-d",
            onClose: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    document.getElementById('date-from').value = selectedDates[0].toISOString().slice(0,10);
                    document.getElementById('date-to').value = selectedDates[1].toISOString().slice(0,10);
                }
            }
        });

        function printRiwayatAbsensi() {
            // Create a new window for printing
            const printWindow = window.open('', '_blank');
            
            // Get the table HTML and remove badge classes
            const tableContainer = document.getElementById('printableRiwayatAbsensi');
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
            
            // Get filter information
            const fromDate = document.getElementById('date-from').value;
            const toDate = document.getElementById('date-to').value;
            let filterInfo = '';
            
            if (fromDate && toDate) {
                const from = new Date(fromDate).toLocaleDateString('id-ID');
                const to = new Date(toDate).toLocaleDateString('id-ID');
                filterInfo = `Filter: ${from} - ${to}`;
            } else {
                filterInfo = 'Menampilkan: Semua data absensi';
            }
            
            // Create complete print document
            const printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Riwayat Absensi Saya</title>
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
                        <h3>RIWAYAT ABSENSI SAYA</h3>
                        <p>SMK Negeri 4 Kendari</p>
                        <p>Tanggal Print: ${new Date().toLocaleDateString('id-ID', { 
                            weekday: 'long', 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        })}</p>
                        <p>${filterInfo}</p>
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
<?php echo $__env->make('layouts.vertical-murid', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/murid/riwayat-absensi.blade.php ENDPATH**/ ?>