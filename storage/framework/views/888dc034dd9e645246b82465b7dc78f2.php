<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'mode' => 'guru', // 'guru' or 'murid'
    'recentRequests' => null, // For murid mode, pass recent requests
    'showRecentRequests' => false // Show recent requests sidebar (for murid)
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
    'mode' => 'guru', // 'guru' or 'murid'
    'recentRequests' => null, // For murid mode, pass recent requests
    'showRecentRequests' => false // Show recent requests sidebar (for murid)
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="col-lg-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bx bx-info-circle fs-18 text-info"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="card-title mb-0">
                        Informasi
                    </h5>
                    <p class="text-muted mb-0 small">Ketentuan & Panduan</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if($mode === 'guru'): ?>
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="bx bx-info-circle me-2"></i>
                        Ketentuan Izin:
                    </h6>
                    <ul class="mb-0 small">
                        <li>Izin harus diajukan minimal 1 hari sebelumnya</li>
                        <li>Untuk izin sakit, lampirkan surat dokter</li>
                        <li>Admin akan menugaskan pengganti</li>
                        <li>Status dapat dicek di riwayat permohonan</li>
                    </ul>
                </div>
            <?php else: ?>
                <div class="mb-3">
                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-xs bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-calendar-check fs-14 text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Waktu Pengajuan</h6>
                            <p class="text-muted mb-0 small">Izin harus diajukan minimal 1 hari sebelumnya untuk memudahkan proses persetujuan.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-xs bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-file fs-14 text-danger"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Dokumen Pendukung</h6>
                            <p class="text-muted mb-0 small">Untuk izin sakit, wajib melampirkan surat dokter sebagai dokumen pendukung.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-xs bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-time-five fs-14 text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Waktu Proses</h6>
                            <p class="text-muted mb-0 small">Izin akan diproses dalam 1-2 hari kerja setelah pengajuan.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="avatar-xs bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-list-check fs-14 text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Cek Status</h6>
                            <p class="text-muted mb-0 small">Status permohonan dapat dicek di tabel riwayat permohonan di bawah.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if($showRecentRequests && $mode === 'murid' && $recentRequests): ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-history me-2"></i>
                    Riwayat Terbaru
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <?php $__empty_1 = true; $__currentLoopData = $recentRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1"><?php echo e($request->leave_type_display); ?></h6>
                                <small class="text-muted"><?php echo e($request->created_at->format('d M Y')); ?></small>
                            </div>
                            <span class="badge bg-<?php echo e($request->status_badge); ?>">
                                <?php switch($request->status):
                                    case ('pending'): ?>
                                        Menunggu
                                        <?php break; ?>
                                    <?php case ('approved'): ?>
                                        Disetujui
                                        <?php break; ?>
                                    <?php case ('rejected'): ?>
                                        Ditolak
                                        <?php break; ?>
                                    <?php default: ?>
                                        <?php echo e(ucfirst($request->status)); ?>

                                <?php endswitch; ?>
                            </span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="list-group-item text-center text-muted">
                            <small>Belum ada permohonan izin</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/components/permohonan-izin/sidebar-info-permohonan-izin.blade.php ENDPATH**/ ?>