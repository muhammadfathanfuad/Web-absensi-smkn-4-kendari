<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'modalId' => 'notificationModal',
    'modalLabelId' => 'notificationModalLabel',
    'messageId' => 'notificationMessage',
    'errorsId' => 'notificationErrors',
    'errorsBodyId' => 'notificationErrorsBody',
    'modalSize' => '', // 'modal-lg' or empty
    'showErrorsTable' => false, // Set to true if errors table is needed
    'showIcon' => false, // Set to true if icon is needed
    'iconId' => 'notificationIcon' // ID for icon element
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
    'modalId' => 'notificationModal',
    'modalLabelId' => 'notificationModalLabel',
    'messageId' => 'notificationMessage',
    'errorsId' => 'notificationErrors',
    'errorsBodyId' => 'notificationErrorsBody',
    'modalSize' => '', // 'modal-lg' or empty
    'showErrorsTable' => false, // Set to true if errors table is needed
    'showIcon' => false, // Set to true if icon is needed
    'iconId' => 'notificationIcon' // ID for icon element
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>


<div id="<?php echo e($modalId); ?>" class="modal fade" tabindex="-1" aria-labelledby="<?php echo e($modalLabelId); ?>" aria-hidden="true">
    <div class="modal-dialog <?php echo e($modalSize); ?>">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="<?php echo e($modalLabelId); ?>">Notifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if($showIcon): ?>
                <div class="text-center mb-3">
                    <iconify-icon id="<?php echo e($iconId); ?>" class="fs-48"></iconify-icon>
                </div>
                <?php endif; ?>
                <div id="<?php echo e($messageId); ?>" style="white-space: pre-wrap;" class="<?php echo e($showIcon ? 'text-center' : ''); ?>"></div>
                <?php if($showErrorsTable): ?>
                <div id="<?php echo e($errorsId); ?>" class="mt-3 d-none">
                    <h6 class="text-danger mb-2">Detail Error:</h6>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th style="width: 60px;">Baris</th>
                                    <th>Nama</th>
                                    <th>Identitas</th>
                                    <th>Penyebab Error</th>
                                </tr>
                            </thead>
                            <tbody id="<?php echo e($errorsBodyId); ?>">
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/components/pengaturan/notification-modal.blade.php ENDPATH**/ ?>