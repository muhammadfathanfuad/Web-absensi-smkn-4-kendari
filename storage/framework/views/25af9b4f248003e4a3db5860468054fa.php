<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'leaveRequests' => collect(),
    'mode' => 'guru', // 'guru' or 'murid'
    'showPagination' => false, // Show pagination (for murid)
    'detailModalFunction' => 'showDetailModal'
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'leaveRequests' => collect(),
    'mode' => 'guru', // 'guru' or 'murid'
    'showPagination' => false, // Show pagination (for murid)
    'detailModalFunction' => 'showDetailModal'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0 d-flex align-items-center">
                    <i class="bx bx-list-ul me-2"></i>
                    Riwayat Permohonan Izin
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <?php if($mode === 'guru'): ?>
                                    <th>No</th>
                                    <th>Kelas</th>
                                    <th>Tanggal Izin</th>
                                    <th>Jenis Izin</th>
                                    <th>Status</th>
                                    <th>Pengganti</th>
                                    <th>Tanggal Ajukan</th>
                                    <th>Aksi</th>
                                <?php else: ?>
                                    <th>No</th>
                                    <th>Jenis Izin</th>
                                    <th>Tanggal</th>
                                    <th>Durasi</th>
                                    <th>Status</th>
                                    <th>Tanggal Ajukan</th>
                                    <th>Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($mode === 'guru'): ?>
                                <?php $__empty_1 = true; $__currentLoopData = $leaveRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        // Get all timetables for this request
                                        $allTimetables = collect();
                                        if ($request->timetables && $request->timetables->count() > 0) {
                                            foreach ($request->timetables as $timetablePivot) {
                                                if ($timetablePivot->timetable) {
                                                    $allTimetables->push($timetablePivot->timetable);
                                                }
                                            }
                                        } else {
                                            if ($request->timetable) {
                                                $allTimetables->push($request->timetable);
                                            }
                                        }
                                        
                                        // Get unique classes
                                        $uniqueClasses = $allTimetables->map(function($timetable) {
                                            return $timetable->classSubject->class->name;
                                        })->unique()->values();
                                        $totalClasses = $uniqueClasses->count();
                                    ?>
                                    <?php if($totalClasses > 0): ?>
                                        <?php $__currentLoopData = $uniqueClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $className): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <?php if($idx === 0): ?>
                                                    <td rowspan="<?php echo e($totalClasses); ?>"><?php echo e($i + 1); ?></td>
                                                <?php endif; ?>
                                                <td>
                                                    <strong><?php echo e($className); ?></strong>
                                                </td>
                                                <?php if($idx === 0): ?>
                                                    <td rowspan="<?php echo e($totalClasses); ?>">
                                                        <?php if($request->end_date && $request->end_date != $request->leave_date): ?>
                                                            <?php echo e($request->leave_date->format('d M Y')); ?> - <?php echo e($request->end_date->format('d M Y')); ?>

                                                            <br><small class="text-muted">(<?php echo e($request->leave_date->diffInDays($request->end_date) + 1); ?> hari)</small>
                                                        <?php else: ?>
                                                            <?php echo e($request->leave_date->format('d M Y')); ?>

                                                        <?php endif; ?>
                                                    </td>
                                                    <td rowspan="<?php echo e($totalClasses); ?>">
                                                        <span class="badge bg-<?php echo e($request->leave_type === 'sakit' ? 'danger' : ($request->leave_type === 'izin' ? 'secondary' : ($request->leave_type === 'keperluan-keluarga' ? 'info' : 'primary'))); ?>">
                                                            <?php echo e($request->leave_type_display); ?>

                                                        </span>
                                                    </td>
                                                    <td rowspan="<?php echo e($totalClasses); ?>">
                                                        <span class="badge bg-<?php echo e($request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning')); ?>">
                                                            <?php if($request->status === 'pending'): ?>
                                                                Menunggu
                                                            <?php elseif($request->status === 'approved'): ?>
                                                                Disetujui
                                                            <?php elseif($request->status === 'rejected'): ?>
                                                                Ditolak
                                                            <?php else: ?>
                                                                <?php echo e(ucfirst($request->status)); ?>

                                                            <?php endif; ?>
                                                        </span>
                                                    </td>
                                                    <td rowspan="<?php echo e($totalClasses); ?>">
                                                        <?php if($request->substitute): ?>
                                                            <?php echo e($request->substitute->full_name); ?>

                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td rowspan="<?php echo e($totalClasses); ?>"><?php echo e($request->created_at->format('d M Y')); ?></td>
                                                    <td rowspan="<?php echo e($totalClasses); ?>">
                                                        <button class="btn btn-sm btn-outline-primary" onclick="<?php echo e($detailModalFunction); ?>(<?php echo e($request->id); ?>)">
                                                            <i class="bx bx-show"></i> Detail
                                                        </button>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <tr>
                                            <td><?php echo e($i + 1); ?></td>
                                            <td colspan="7" class="text-center text-muted">Data jadwal tidak ditemukan</td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bx bx-inbox fs-48 d-block mx-auto mb-2"></i>
                                                Belum ada riwayat permohonan izin
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php $__empty_1 = true; $__currentLoopData = $leaveRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $startDate = \Carbon\Carbon::parse($request->start_date);
                                        $endDate = \Carbon\Carbon::parse($request->end_date);
                                        $duration = $startDate->diffInDays($endDate) + 1;
                                        $leaveTypeDisplay = $request->leave_type_display ?? ucfirst($request->leave_type);
                                        // Calculate correct sequential number across pages
                                        $sequentialNumber = ($leaveRequests->currentPage() - 1) * $leaveRequests->perPage() + $i + 1;
                                    ?>
                                    <tr>
                                        <td><?php echo e($sequentialNumber); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($request->leave_type === 'sakit' ? 'danger' : ($request->leave_type === 'izin' ? 'secondary' : ($request->leave_type === 'keperluan-keluarga' ? 'info' : 'primary'))); ?>">
                                                <?php echo e($leaveTypeDisplay); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($startDate->format('d M Y')); ?> <?php if($duration > 1): ?> - <?php echo e($endDate->format('d M Y')); ?> <?php endif; ?></td>
                                        <td><?php echo e($duration); ?> hari</td>
                                        <td>
                                            <span class="badge bg-<?php echo e($request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning')); ?>">
                                                <?php if($request->status === 'pending'): ?>
                                                    Menunggu
                                                <?php elseif($request->status === 'approved'): ?>
                                                    Disetujui
                                                <?php elseif($request->status === 'rejected'): ?>
                                                    Ditolak
                                                <?php else: ?>
                                                    <?php echo e(ucfirst($request->status)); ?>

                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td><?php echo e($request->created_at->format('d M Y')); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="<?php echo e($detailModalFunction); ?>(<?php echo e($request->id); ?>)">
                                                <i class="bx bx-show"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bx bx-inbox fs-48 d-block mx-auto mb-2"></i>
                                                Belum ada riwayat permohonan izin
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if($showPagination && $mode === 'murid' && $leaveRequests->hasPages()): ?>
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top" id="pagination-wrapper">
                        <div class="text-muted">
                            Menampilkan <?php echo e($leaveRequests->firstItem()); ?> sampai <?php echo e($leaveRequests->lastItem()); ?> dari <?php echo e($leaveRequests->total()); ?> data
                        </div>
                        <div class="d-flex">
                            <?php echo e($leaveRequests->links('pagination::bootstrap-4')); ?>

                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/components/permohonan-izin/riwayat-permohonan-izin.blade.php ENDPATH**/ ?>