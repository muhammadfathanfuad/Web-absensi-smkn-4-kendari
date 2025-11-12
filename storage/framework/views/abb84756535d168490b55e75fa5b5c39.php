<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Status Absensi'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Rekap Kehadiran Siswa</h4>

            
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo e($viewType == 'summary' ? 'active' : ''); ?>" id="summary-tab" data-bs-toggle="tab" 
                            data-bs-target="#summary" type="button" role="tab" onclick="switchViewType('summary')">
                        <i class="bx bx-list-ul me-1"></i> Ringkasan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo e($viewType == 'detail' ? 'active' : ''); ?>" id="detail-tab" data-bs-toggle="tab" 
                            data-bs-target="#detail" type="button" role="tab" onclick="switchViewType('detail')">
                        <i class="bx bx-detail me-1"></i> Detail
                    </button>
                </li>
            </ul>

            
            <form action="<?php echo e(route('guru.status-absensi')); ?>" method="GET" id="filterForm">
                <input type="hidden" name="view_type" id="view_type" value="<?php echo e($viewType); ?>">
                
                <div class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label for="period_preset" class="form-label">Periode</label>
                        <select name="period_preset" id="period_preset" class="form-select" onchange="handlePeriodPreset()">
                            <option value="custom" <?php echo e($periodPreset == 'custom' ? 'selected' : ''); ?>>Custom</option>
                            <option value="semester_ganjil" <?php echo e($periodPreset == 'semester_ganjil' ? 'selected' : ''); ?>>Semester Ganjil (Jul-Des)</option>
                            <option value="semester_genap" <?php echo e($periodPreset == 'semester_genap' ? 'selected' : ''); ?>>Semester Genap (Jan-Jun)</option>
                            <option value="bulan_ini" <?php echo e($periodPreset == 'bulan_ini' ? 'selected' : ''); ?>>Bulan Ini</option>
                        </select>
                    </div>
                    <div class="col-md-2" id="date_from_wrapper">
                        <label for="date_from" class="form-label">Dari Tanggal</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="<?php echo e($dateFrom); ?>">
                    </div>
                    <div class="col-md-2" id="date_to_wrapper">
                        <label for="date_to" class="form-label">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="<?php echo e($dateTo); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="subject_id" class="form-label">Pilih Mata Pelajaran</label>
                        <select name="subject_id" id="subject_id" class="form-select">
                            <option value="">Semua Mapel</option>
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subject->id); ?>" <?php echo e($selectedSubjectId == $subject->id ? 'selected' : ''); ?>>
                                    <?php echo e($subject->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="classroom_id" class="form-label">Pilih Kelas</label>
                        <select name="classroom_id" id="classroom_id" class="form-select">
                            <option value="">Semua Kelas</option>
                            <?php $__currentLoopData = $classrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($classroom->id); ?>" <?php echo e($selectedClassroomId == $classroom->id ? 'selected' : ''); ?>>
                                    <?php echo e($classroom->grade); ?> - <?php echo e($classroom->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-filter me-1"></i> Filter
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="resetFilter()">
                            <i class="bx bx-refresh me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </form>

            <hr>

            
            <div class="tab-content" id="myTabContent">
                
                <div class="tab-pane fade <?php echo e($viewType == 'summary' ? 'show active' : ''); ?>" id="summary" role="tabpanel">
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Ringkasan Absensi Siswa</h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false" id="exportSummaryDropdownBtn">
                        <i class="bx bx-download"></i> <span>Export</span>
                    </button>
                    <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="exportAbsensiGuru('pdf', 'summary'); return false;">
                                <i class="bx bx-file"></i> Export PDF (.pdf)
                            </a></li>
                    </ul>
                </div>
            </div>

                    
                    <div class="table-responsive mt-4" id="printableSummaryTable">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Total Hadir</th>
                                    <th>Total Terlambat</th>
                                    <th>Total Absen</th>
                                    <th>Total Izin</th>
                                    <th>Total Sakit</th>
                                    <th>Total Pertemuan</th>
                                    <th>Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $summary ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <td><?php echo e($student['nis']); ?></td>
                                        <td><?php echo e($student['name']); ?></td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary">
                                                <?php echo e($student['class']); ?>

                                            </span>
                                        </td>
                                        <td class="text-success fw-bold"><?php echo e($student['total_hadir']); ?></td>
                                        <td class="text-warning fw-bold"><?php echo e($student['total_terlambat']); ?></td>
                                        <td class="text-danger fw-bold"><?php echo e($student['total_absen']); ?></td>
                                        <td class="text-info fw-bold"><?php echo e($student['total_izin']); ?></td>
                                        <td class="text-warning fw-bold"><?php echo e($student['total_sakit']); ?></td>
                                        <td class="fw-bold"><?php echo e($student['total_pertemuan']); ?></td>
                                        <td>
                                            <span class="badge <?php echo e($student['persentase'] >= 80 ? 'bg-success' : ($student['persentase'] >= 60 ? 'bg-warning' : 'bg-danger')); ?>">
                                                <?php echo e($student['persentase']); ?>%
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="11" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bx bx-info-circle me-2"></i>
                                                Tidak ada data absensi untuk filter yang dipilih.
                                                <?php if($viewType == 'summary'): ?>
                                                    <br><small>Pastikan Anda telah memilih periode tanggal dan filter lainnya.</small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                
                <div class="tab-pane fade <?php echo e($viewType == 'detail' ? 'show active' : ''); ?>" id="detail" role="tabpanel">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Data Absensi Siswa (Detail)</h5>
                        <div class="d-flex gap-2 align-items-center">
                            
                            <div class="input-group" style="max-width: 300px;">
                                <span class="input-group-text"><i class="bx bx-search"></i></span>
                                <input type="text" class="form-control" id="detailSearchInput" 
                                       placeholder="Cari NIS, Nama, Kelas, Mapel..." 
                                       onkeyup="filterDetailTable()">
                            </div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-expanded="false" id="exportDetailDropdownBtn">
                                    <i class="bx bx-download"></i> <span>Export</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="exportAbsensiGuru('pdf', 'detail'); return false;">
                                            <i class="bx bx-file"></i> Export PDF (.pdf)
                                        </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    
                    <div class="table-responsive mt-4" id="printableTable">
                        <table class="table table-hover table-striped" id="detailTable">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Jam Masuk</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                            <tbody id="detailTableBody">
                        <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $absen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="detail-row" 
                                        data-nis="<?php echo e(strtolower($absen->student->nis ?? '')); ?>"
                                        data-nama="<?php echo e(strtolower($absen->student->user->full_name ?? '')); ?>"
                                        data-kelas="<?php echo e(strtolower($absen->student->classroom ? ($absen->student->classroom->grade . ' - ' . $absen->student->classroom->name) : '')); ?>"
                                        data-mapel="<?php echo e(strtolower($absen->classSession->timetable->classSubject->subject->name ?? '')); ?>"
                                        data-tanggal="<?php echo e($absen->classSession->date ?? ''); ?>">
                                        <td class="detail-no"><?php echo e($loop->iteration); ?></td>
                                        <td>
                                            <?php if($absen->classSession && $absen->classSession->date): ?>
                                                <?php echo e(\Carbon\Carbon::parse($absen->classSession->date)->translatedFormat('d/m/Y')); ?>

                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                <td><?php echo e($absen->student->nis ?? 'N/A'); ?></td>
                                <td><?php echo e($absen->student->user->full_name ?? 'N/A'); ?></td>
                                <td>
                                    <?php if($absen->student->classroom): ?>
                                        <span class="badge bg-primary-subtle text-primary">
                                            <?php echo e($absen->student->classroom->grade); ?> - <?php echo e($absen->student->classroom->name); ?>

                                        </span>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($absen->classSession->timetable->classSubject->subject->name ?? 'N/A'); ?></td>
                                <td><?php echo e($absen->check_in_time ?? '-'); ?></td>
                                <td>
                                    <?php if($absen->status == 'S'): ?>
                                        <span class="badge bg-soft-warning text-warning">Sakit</span>
                                    <?php elseif($absen->status == 'I'): ?>
                                        <span class="badge bg-soft-info text-info">Izin</span>
                                    <?php elseif($absen->status == 'T' || ($absen->notes === 'Terlambat' && $absen->status !== 'H')): ?>
                                        <span class="badge bg-soft-danger text-danger">Terlambat</span>
                                    <?php elseif($absen->status == 'H'): ?>
                                        <span class="badge bg-soft-success text-success">Hadir</span>
                                    <?php else: ?>
                                        <span class="badge bg-soft-secondary text-secondary"><?php echo e($absen->status); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr id="emptyRow">
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bx bx-info-circle me-2"></i>
                                                Tidak ada data absensi untuk filter yang dipilih.
                                            </div>
                                        </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    @media print {
        /* Sembunyikan semua elemen */
        * {
            visibility: hidden !important;
        }
        
        /* Tampilkan hanya tabel data absensi dan header print */
        #printableTable, 
        #printableTable *,
        .print-header,
        .print-header * {
            visibility: visible !important;
        }
        
        /* Posisikan tabel di atas */
        #printableTable {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        /* Sembunyikan semua elemen UI yang tidak perlu */
        .navbar,
        .navbar *,
        .sidebar,
        .sidebar *,
        .page-title,
        .page-title *,
        .card-header,
        .card-header *,
        .card-title,
        .card-title *,
        .card-body > form,
        .card-body > form *,
        .card-body > hr,
        .d-flex.justify-content-between,
        .d-flex.justify-content-between *,
        .btn,
        .btn *,
        .form-control,
        .form-control *,
        .form-select,
        .form-select *,
        .form-label,
        .form-label *,
        .text-muted,
        .text-muted *,
        h4, h4 *,
        h5, h5 *,
        .card,
        .card *,
        .container,
        .container *,
        .row,
        .row *,
        .col-md-3,
        .col-md-3 *,
        .col-md-2,
        .col-md-2 *,
        .col-12,
        .col-12 *,
        .mt-4,
        .mb-3,
        .mb-4,
        .align-items-end,
        .align-items-end *,
        .g-3,
        .g-3 *,
        .mt-2,
        .mt-2 *,
        .small,
        .small *,
        .table-responsive,
        .table-responsive * {
            display: none !important;
            visibility: hidden !important;
        }
        
        /* Styling tabel untuk print */
        .table {
            border-collapse: collapse !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .table th,
        .table td {
            border: 1px solid #000 !important;
            padding: 8px !important;
            text-align: left !important;
            margin: 0 !important;
        }
        
        .table thead th {
            background-color: #f5f5f5 !important;
            font-weight: bold !important;
            color: #000 !important;
        }
        
        .table tbody td {
            color: #000 !important;
        }
        
        /* Styling badge untuk print */
        .badge {
            border: 1px solid #000 !important;
            color: #000 !important;
            background-color: transparent !important;
            padding: 2px 6px !important;
            font-size: 12px !important;
        }
        
        /* Pastikan tidak ada background color */
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: transparent !important;
        }
        
        .table-hover tbody tr:hover {
            background-color: transparent !important;
        }
        
        /* Reset body untuk print */
        body {
            margin: 0 !important;
            padding: 0 !important;
            background: white !important;
        }
        
        /* Sembunyikan elemen layout utama */
        .main-content,
        .main-content *,
        .page-wrapper,
        .page-wrapper *,
        .content-page,
        .content-page *,
        .container-fluid,
        .container-fluid *,
        .wrapper,
        .wrapper * {
            display: none !important;
            visibility: hidden !important;
        }
        
        /* Pastikan hanya print header dan tabel yang terlihat */
        .print-header {
            display: block !important;
            visibility: visible !important;
            position: relative !important;
            margin-bottom: 20px !important;
        }
        
        #printableTable {
            display: block !important;
            visibility: visible !important;
            position: relative !important;
        }
    }
    
    .print-header {
        display: none;
    }
    
    @media print {
        .print-header {
            display: block;
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .print-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        
        .print-header p {
            margin: 5px 0 0 0;
            font-size: 14px;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Show loading indicator for export
    function showExportLoading(format, reportType = '', message = '', type = 'info') {
        const formatText = format === 'pdf' ? 'PDF' : 'File';
        const alertClass = type === 'success' ? 'alert-success' : (type === 'danger' ? 'alert-danger' : 'alert-info');
        const iconClass = type === 'success' ? 'bx-check-circle' : (type === 'danger' ? 'bx-x-circle' : '');
        const spinnerHtml = type === 'success' || type === 'danger' ? '' : '<div class="spinner-border spinner-border-sm me-2" role="status"><span class="visually-hidden">Loading...</span></div>';
        const iconHtml = iconClass ? `<i class="bx ${iconClass} me-2" style="font-size: 1.2em;"></i>` : '';
        
        const loadingHtml = `
            <div id="exportLoading" class="alert ${alertClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px;">
                <div class="d-flex align-items-center">
                    ${spinnerHtml}
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
    
    function exportAbsensiGuru(format = 'pdf', viewType = 'detail') {
        try {
            // Check if export is already in progress
            if (window.exportNavigating) {
                return;
            }
            
            // Get current filter values
            const selectedSubject = document.getElementById('subject_id');
            const selectedClassroom = document.getElementById('classroom_id');
            const dateFrom = document.getElementById('date_from');
            const dateTo = document.getElementById('date_to');
            const periodPreset = document.getElementById('period_preset');
            const viewTypeInput = document.getElementById('view_type');
            
            // Build export URL with filters
            let exportUrl = '<?php echo e(route("guru.status-absensi.export")); ?>?format=' + format;
            
            if (viewTypeInput && viewTypeInput.value) {
                exportUrl += '&view_type=' + encodeURIComponent(viewTypeInput.value);
            } else {
                exportUrl += '&view_type=' + encodeURIComponent(viewType);
            }
            
            if (periodPreset && periodPreset.value) {
                exportUrl += '&period_preset=' + encodeURIComponent(periodPreset.value);
            }
            
            if (selectedSubject && selectedSubject.value) {
                exportUrl += '&subject_id=' + encodeURIComponent(selectedSubject.value);
            }
            
            if (selectedClassroom && selectedClassroom.value) {
                exportUrl += '&classroom_id=' + encodeURIComponent(selectedClassroom.value);
            }
            
            if (dateFrom && dateFrom.value) {
                exportUrl += '&date_from=' + encodeURIComponent(dateFrom.value);
            }
            
            if (dateTo && dateTo.value) {
                exportUrl += '&date_to=' + encodeURIComponent(dateTo.value);
            }
            
            // Show loading indicator
            const reportType = viewType === 'summary' ? 'Ringkasan Absensi' : 'Rekap Kehadiran Siswa';
            showExportLoading(format, reportType);
            
            // Mark as navigating to prevent duplicate
            window.exportNavigating = true;
            
            // Use window.location.href for direct download (more reliable)
            window.location.href = exportUrl;
            
            // Show success message after a delay
            setTimeout(function() {
                showExportSuccess('Export berhasil! File sedang diunduh.');
                window.exportNavigating = false;
            }, 2000);
            
        } catch (error) {
            showExportError('Terjadi kesalahan saat export: ' + error.message);
            window.exportNavigating = false;
        }
    }

    function switchViewType(type) {
        document.getElementById('view_type').value = type;
        // Submit form to reload with new view type
        document.getElementById('filterForm').submit();
    }

    function handlePeriodPreset() {
        const preset = document.getElementById('period_preset').value;
        const dateFromWrapper = document.getElementById('date_from_wrapper');
        const dateToWrapper = document.getElementById('date_to_wrapper');
        
        if (preset === 'custom') {
            dateFromWrapper.style.display = 'block';
            dateToWrapper.style.display = 'block';
        } else {
            dateFromWrapper.style.display = 'none';
            dateToWrapper.style.display = 'none';
        }
    }

    function resetFilter() {
        document.getElementById('period_preset').value = 'custom';
        document.getElementById('date_from').value = '';
        document.getElementById('date_to').value = '';
        document.getElementById('subject_id').value = '';
        document.getElementById('classroom_id').value = '';
        document.getElementById('view_type').value = '<?php echo e($viewType); ?>';
        handlePeriodPreset();
    }

    // Function to filter detail table
    function filterDetailTable() {
        const searchInput = document.getElementById('detailSearchInput');
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const rows = document.querySelectorAll('#detailTableBody .detail-row');
        const emptyRow = document.getElementById('emptyRow');
        let visibleCount = 0;
        let rowNumber = 1;

        rows.forEach(function(row) {
            const nis = row.getAttribute('data-nis') || '';
            const nama = row.getAttribute('data-nama') || '';
            const kelas = row.getAttribute('data-kelas') || '';
            const mapel = row.getAttribute('data-mapel') || '';
            const tanggal = row.getAttribute('data-tanggal') || '';
            
            // Format tanggal untuk pencarian (multiple formats)
            let tanggalFormatted = '';
            if (tanggal) {
                try {
                    const dateObj = new Date(tanggal);
                    // Format: dd/mm/yyyy
                    const dd = String(dateObj.getDate()).padStart(2, '0');
                    const mm = String(dateObj.getMonth() + 1).padStart(2, '0');
                    const yyyy = dateObj.getFullYear();
                    tanggalFormatted = `${dd}/${mm}/${yyyy} ${dd}-${mm}-${yyyy} ${dd} ${mm} ${yyyy}`.toLowerCase();
                } catch (e) {
                    tanggalFormatted = tanggal.toLowerCase();
                }
            }
            
            const searchableText = `${nis} ${nama} ${kelas} ${mapel} ${tanggalFormatted}`;
            
            if (searchTerm === '' || searchableText.includes(searchTerm)) {
                row.style.display = '';
                // Update row number
                const noCell = row.querySelector('.detail-no');
                if (noCell) {
                    noCell.textContent = rowNumber++;
                }
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Show/hide empty row
        if (emptyRow) {
            if (visibleCount === 0 && searchTerm !== '') {
                emptyRow.style.display = '';
                emptyRow.querySelector('td').colSpan = 8;
                emptyRow.querySelector('.text-muted').innerHTML = 
                    '<i class="bx bx-info-circle me-2"></i>Tidak ada data yang sesuai dengan pencarian.';
            } else if (visibleCount === 0 && searchTerm === '') {
                emptyRow.style.display = '';
                emptyRow.querySelector('td').colSpan = 8;
                emptyRow.querySelector('.text-muted').innerHTML = 
                    '<i class="bx bx-info-circle me-2"></i>Tidak ada data absensi untuk filter yang dipilih.';
            } else {
                emptyRow.style.display = 'none';
            }
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        handlePeriodPreset();
        
        // Initialize detail search if on detail tab
        const detailTab = document.getElementById('detail');
        if (detailTab && detailTab.classList.contains('active')) {
            filterDetailTable();
        }
        
        // Re-filter when switching to detail tab
        const detailTabButton = document.getElementById('detail-tab');
        if (detailTabButton) {
            detailTabButton.addEventListener('shown.bs.tab', function() {
                filterDetailTable();
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vertical-guru', ['subtitle' => 'Status Absensi'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/guru/status-absensi.blade.php ENDPATH**/ ?>