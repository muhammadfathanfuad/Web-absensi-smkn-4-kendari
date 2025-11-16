<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'modalId' => 'readMoreModal',
    'modalLabelId' => 'readMoreModalLabel',
    'modalTitleId' => 'modalTitle',
    'modalDateId' => 'modalDate',
    'modalAuthorId' => 'modalAuthor',
    'modalCategoryId' => 'modalCategory',
    'modalContentId' => 'modalContent',
    'modalCreatedAtId' => 'modalCreatedAt',
    'modalExpiresAtId' => 'modalExpiresAt',
    'modalPriorityId' => 'modalPriority',
    'modalIconId' => 'modalIcon',
    'modalIconClassId' => 'modalIconClass',
    'modalMarkReadBtnId' => 'modalMarkReadBtn',
    'markReadFunction' => 'toggleReadStatusFromModal'
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
    'modalId' => 'readMoreModal',
    'modalLabelId' => 'readMoreModalLabel',
    'modalTitleId' => 'modalTitle',
    'modalDateId' => 'modalDate',
    'modalAuthorId' => 'modalAuthor',
    'modalCategoryId' => 'modalCategory',
    'modalContentId' => 'modalContent',
    'modalCreatedAtId' => 'modalCreatedAt',
    'modalExpiresAtId' => 'modalExpiresAt',
    'modalPriorityId' => 'modalPriority',
    'modalIconId' => 'modalIcon',
    'modalIconClassId' => 'modalIconClass',
    'modalMarkReadBtnId' => 'modalMarkReadBtn',
    'markReadFunction' => 'toggleReadStatusFromModal'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>


<div class="modal fade" id="<?php echo e($modalId); ?>" tabindex="-1" aria-labelledby="<?php echo e($modalLabelId); ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="<?php echo e($modalLabelId); ?>">
                    <i class="bx bx-news me-2"></i>
                    Detail Pengumuman
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" id="<?php echo e($modalIconId); ?>">
                                    <i class="bx bx-news fs-20 text-primary" id="<?php echo e($modalIconClassId); ?>"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1" id="<?php echo e($modalTitleId); ?>">Judul Pengumuman</h6>
                                <div class="d-flex align-items-center text-muted">
                                    <small class="me-3">
                                        <i class="bx bx-calendar me-1"></i>
                                        <span id="<?php echo e($modalDateId); ?>">Tanggal</span>
                                    </small>
                                    <small class="me-3">
                                        <i class="bx bx-user me-1"></i>
                                        <span id="<?php echo e($modalAuthorId); ?>">Penulis</span>
                                    </small>
                                    <small>
                                        <i class="bx bx-tag me-1"></i>
                                        <span id="<?php echo e($modalCategoryId); ?>">Kategori</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-top pt-3">
                            <h6 class="text-muted mb-2">Isi Pengumuman:</h6>
                            <div class="bg-light p-3 rounded" id="<?php echo e($modalContentId); ?>">
                                <!-- Content will be loaded here -->
                            </div>
                        </div>
                        
                        <div class="border-top pt-3 mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        <i class="bx bx-time me-1"></i>
                                        Dibuat: <span id="<?php echo e($modalCreatedAtId); ?>">-</span>
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bx bx-expire me-1"></i>
                                        Berlaku hingga: <span id="<?php echo e($modalExpiresAtId); ?>">-</span>
                                    </small>
                                </div>
                                <div>
                                    <span class="badge" id="<?php echo e($modalPriorityId); ?>">Prioritas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i>Tutup
                </button>
                <button type="button" class="btn btn-primary" id="<?php echo e($modalMarkReadBtnId); ?>" onclick="<?php echo e($markReadFunction); ?>()">
                    <i class="bx bx-check me-1"></i>Telah Dibaca
                </button>
            </div>
        </div>
    </div>
</div>

<?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/components/pengumuman/modal-detail-pengumuman.blade.php ENDPATH**/ ?>