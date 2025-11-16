<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'mode' => 'murid', // 'guru' or 'murid'
    'showFilters' => false, // Show filter section (for murid)
    'showSidebar' => false, // Show sidebar timeline (for guru)
    'announcementsContainerId' => 'announcementsContainer',
    'timelineContainerId' => 'timelineContainer',
    'pengumumanListId' => 'pengumumanList',
    'categoryFilterId' => 'categoryFilter',
    'dateFilterId' => 'dateFilter',
    'searchInputId' => 'searchInput'
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
    'mode' => 'murid', // 'guru' or 'murid'
    'showFilters' => false, // Show filter section (for murid)
    'showSidebar' => false, // Show sidebar timeline (for guru)
    'announcementsContainerId' => 'announcementsContainer',
    'timelineContainerId' => 'timelineContainer',
    'pengumumanListId' => 'pengumumanList',
    'categoryFilterId' => 'categoryFilter',
    'dateFilterId' => 'dateFilter',
    'searchInputId' => 'searchInput'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php if($mode === 'guru'): ?>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-md bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class='bx bx-news fs-32 text-primary'></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="card-title mb-1">
                                Pengumuman Terbaru
                            </h4>
                            <p class="text-muted mb-0">Informasi penting untuk guru</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="<?php echo e($announcementsContainerId); ?>">
                        <!-- Announcements will be loaded here -->
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2">Memuat pengumuman...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if($showSidebar): ?>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <iconify-icon icon="solar:clock-circle-outline" class="fs-20 me-2"></iconify-icon>
                        Pengumuman Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline" id="<?php echo e($timelineContainerId); ?>">
                        <!-- Timeline will be loaded here -->
                        <div class="text-center py-2">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
<?php else: ?>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-megaphone me-2"></i>
                        Pengumuman Terbaru
                    </h4>
                </div>
                <div class="card-body">
                    <?php if($showFilters): ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="<?php echo e($categoryFilterId); ?>" class="form-label">Kategori</label>
                            <select class="form-select" id="<?php echo e($categoryFilterId); ?>">
                                <option value="">Semua Kategori</option>
                                <option value="umum">Umum</option>
                                <option value="akademik">Akademik</option>
                                <option value="kegiatan">Kegiatan</option>
                                <option value="penting">Penting</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="<?php echo e($dateFilterId); ?>" class="form-label">Tanggal</label>
                            <select class="form-select" id="<?php echo e($dateFilterId); ?>">
                                <option value="">Semua Tanggal</option>
                                <option value="today">Hari Ini</option>
                                <option value="week">Minggu Ini</option>
                                <option value="month">Bulan Ini</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="<?php echo e($searchInputId); ?>" class="form-label">Cari</label>
                            <input type="text" class="form-control" id="<?php echo e($searchInputId); ?>" placeholder="Cari pengumuman...">
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <div class="row" id="<?php echo e($pengumumanListId); ?>">
                        <!-- Announcements will be loaded here -->
                        <div class="col-12 text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2">Memuat pengumuman...</p>
                        </div>
                    </div>

                    
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/components/pengumuman/pengumuman.blade.php ENDPATH**/ ?>