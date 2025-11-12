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
                        <div class="col-md-4">
                            <label for="subject_id" class="form-label">Filter berdasarkan mata pelajaran (opsional):</label>
                            <select name="subject_id" id="subject_id" class="form-select">
                                <option value="">Semua Mata Pelajaran</option>
                                <?php $__currentLoopData = $subjects ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($subject->id); ?>" <?php echo e((isset($subjectId) && $subjectId == $subject->id) ? 'selected' : ''); ?>>
                                        <?php echo e($subject->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-4 align-self-end">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <a href="<?php echo e(route('murid.absensi')); ?>" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>

                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Riwayat Absensi Saya</h5>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false" id="exportAbsensiMuridDropdownBtn">
                                <i class="bx bx-download"></i> <span>Export</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="exportAbsensiMurid('pdf'); return false;">
                                        <i class="bx bx-file"></i> Export PDF (.pdf)
                                    </a></li>
                            </ul>
                        </div>
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

        // Show loading indicator
        function showExportLoading(format = 'pdf', reportType = '', message = '', type = 'info') {
            const formatText = format.toUpperCase();
            const iconClass = type === 'success' ? 'bx-check-circle' : type === 'danger' ? 'bx-x-circle' : 'bx-loader-alt';
            const iconColor = type === 'success' ? '#28a745' : type === 'danger' ? '#dc3545' : '#007bff';
            const bgColor = type === 'success' ? '#d4edda' : type === 'danger' ? '#f8d7da' : '#d1ecf1';
            const borderColor = type === 'success' ? '#c3e6cb' : type === 'danger' ? '#f5c6cb' : '#bee5eb';
            const spinClass = type === 'info' ? 'bx-spin' : '';
            
            const iconHtml = type === 'info' 
                ? `<i class="bx bx-loader-alt ${spinClass}" style="font-size: 24px; color: ${iconColor};"></i>`
                : `<i class="bx ${iconClass}" style="font-size: 24px; color: ${iconColor};"></i>`;
            
            const loadingHtml = `
                <div id="exportLoading" class="alert alert-${type === 'success' ? 'success' : type === 'danger' ? 'danger' : 'info'} show" 
                     style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); background-color: ${bgColor}; border-color: ${borderColor};">
                    <div class="d-flex align-items-center gap-2">
                        ${iconHtml}
                        <div>
                            <strong>${message || `Sedang memproses export ${formatText}${reportType ? ' - ' + reportType : ''}...`}</strong>
                            ${message ? '' : '<br><small>File akan segera diunduh</small>'}
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing loading if any
            const existingLoading = document.getElementById('exportLoading');
            if (existingLoading) {
                existingLoading.remove();
            }
            
            // Add new loading indicator
            document.body.insertAdjacentHTML('beforeend', loadingHtml);
        }
        
        // Show success message in loading indicator
        function showExportSuccess(message = 'Export berhasil! File sedang diunduh.') {
            showExportLoading('pdf', '', message, 'success');
            setTimeout(function() {
                const loadingElement = document.getElementById('exportLoading');
                if (loadingElement) {
                    loadingElement.classList.remove('show');
                    setTimeout(function() {
                        loadingElement.remove();
                    }, 150);
                }
            }, 3000);
        }
        
        // Show error message in loading indicator
        function showExportError(message = 'Gagal mengexport data. Silakan coba lagi atau hubungi administrator.') {
            showExportLoading('pdf', '', message, 'danger');
            setTimeout(function() {
                const loadingElement = document.getElementById('exportLoading');
                if (loadingElement) {
                    loadingElement.classList.remove('show');
                    setTimeout(function() {
                        loadingElement.remove();
                    }, 3000);
                }
            }, 5000);
        }
        
        // Export function for student attendance history
        function exportAbsensiMurid(format = 'pdf') {
            try {
                // Prevent duplicate calls
                if (window.exportNavigating) {
                    return;
                }
                
                // Get filter values
                const fromDate = document.getElementById('date-from')?.value || '';
                const toDate = document.getElementById('date-to')?.value || '';
                const subjectId = document.getElementById('subject_id')?.value || '';
                
                // Build export URL
                let exportUrl = '<?php echo e(route("murid.absensi.export")); ?>?format=' + format;
                if (fromDate) {
                    exportUrl += '&from=' + encodeURIComponent(fromDate);
                }
                if (toDate) {
                    exportUrl += '&to=' + encodeURIComponent(toDate);
                }
                if (subjectId) {
                    exportUrl += '&subject_id=' + encodeURIComponent(subjectId);
                }
                
                // Show loading indicator
                showExportLoading(format, 'Riwayat Absensi');
                
                // Set flag to prevent duplicate calls
                window.exportNavigating = true;
                
                // Trigger download
                window.location.href = exportUrl;
                
                // Show success message after a delay
                setTimeout(function() {
                    showExportSuccess('Export berhasil! File sedang diunduh.');
                    window.exportNavigating = false;
                }, 2000);
                
            } catch (error) {
                console.error('Error exporting attendance history:', error);
                showExportError('Terjadi kesalahan saat mengexport data. Silakan coba lagi.');
                window.exportNavigating = false;
            }
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vertical-murid', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/murid/riwayat-absensi.blade.php ENDPATH**/ ?>