<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Status Absensi'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Rekap Kehadiran Siswa</h4>

            
            <form action="<?php echo e(route('guru.status-absensi')); ?>" method="GET">
                <div class="row g-3 align-items-end">
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
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">Dari Tanggal</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="<?php echo e($dateFrom); ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="<?php echo e($dateTo); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <small class="text-muted">Kosongkan rentang tanggal untuk melihat semua data</small>
                    </div>
                </div>
            </form>

            <hr>

            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Data Absensi Siswa</h5>
                <button type="button" class="btn btn-outline-primary" onclick="printTable()">
                    <iconify-icon icon="solar:printer-outline" class="fs-16 me-2"></iconify-icon>
                    Print
                </button>
            </div>

            
            <div class="table-responsive mt-4" id="printableTable">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Jam Masuk</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $absen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
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
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data absensi untuk filter yang dipilih.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
    function printTable() {
        // Get current filter values
        const selectedSubject = document.getElementById('subject_id');
        const selectedClassroom = document.getElementById('classroom_id');
        const dateFrom = document.getElementById('date_from');
        const dateTo = document.getElementById('date_to');
        
        // Build filter info
        let filterInfo = '';
        
        if (selectedSubject && selectedSubject.value) {
            const subjectText = selectedSubject.options[selectedSubject.selectedIndex].text;
            filterInfo += `Mata Pelajaran: ${subjectText}<br>`;
        }
        
        if (selectedClassroom && selectedClassroom.value) {
            const classroomText = selectedClassroom.options[selectedClassroom.selectedIndex].text;
            filterInfo += `Kelas: ${classroomText}<br>`;
        }
        
        if (dateFrom && dateFrom.value) {
            const fromDate = new Date(dateFrom.value).toLocaleDateString('id-ID');
            filterInfo += `Dari Tanggal: ${fromDate}<br>`;
        }
        
        if (dateTo && dateTo.value) {
            const toDate = new Date(dateTo.value).toLocaleDateString('id-ID');
            filterInfo += `Sampai Tanggal: ${toDate}<br>`;
        }
        
        if (!filterInfo) {
            filterInfo = 'Semua Data<br>';
        }
        
        // Create a new window for printing
        const printWindow = window.open('', '_blank');
        
        // Get the table HTML and remove badge classes
        const tableContainer = document.getElementById('printableTable');
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
                <title>Rekap Kehadiran Siswa</title>
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
                    <h3>REKAP KEHADIRAN SISWA</h3>
                    <p>SMK Negeri 4 Kendari</p>
                    <p>Tanggal Print: ${new Date().toLocaleDateString('id-ID', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    })}</p>
                    <p>Filter: ${filterInfo}</p>
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
<?php echo $__env->make('layouts.vertical-guru', ['subtitle' => 'Status Absensi'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/guru/status-absensi.blade.php ENDPATH**/ ?>