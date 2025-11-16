<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'modalId' => 'qrModalDelegasi',
    'modalLabelId' => 'qrModalDelegasiLabel',
    'qrCodeContainerId' => 'qrCodeContainerDelegasi',
    'qrInfoTextId' => 'qrInfoTextDelegasi',
    'stopSessionBtnId' => 'stopSessionBtnDelegasi'
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
    'modalId' => 'qrModalDelegasi',
    'modalLabelId' => 'qrModalDelegasiLabel',
    'qrCodeContainerId' => 'qrCodeContainerDelegasi',
    'qrInfoTextId' => 'qrInfoTextDelegasi',
    'stopSessionBtnId' => 'stopSessionBtnDelegasi'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<!-- Modal QR Code untuk Delegasi -->
<div class="modal fade" id="<?php echo e($modalId); ?>" tabindex="-1" aria-labelledby="<?php echo e($modalLabelId); ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="<?php echo e($modalLabelId); ?>">
                    <iconify-icon icon="solar:qr-code-outline" class="fs-20 me-2"></iconify-icon>
                    QR Code Absensi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div id="<?php echo e($qrCodeContainerId); ?>" class="d-flex justify-content-center align-items-center mx-auto" style="width:280px; height:280px; border: 2px dashed #dee2e6; border-radius: 12px; background-color: #f8f9fa; position: relative; min-height: 280px;">
                        <div class="text-muted text-center">
                            <iconify-icon icon="solar:qr-code-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                            QR Code akan muncul di sini...
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="mb-2" id="<?php echo e($qrInfoTextId); ?>"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-danger d-flex align-items-center" id="<?php echo e($stopSessionBtnId); ?>" style="display: none;">
                    <iconify-icon icon="solar:stop-circle-outline" class="fs-16 me-2"></iconify-icon>
                    Hentikan Sesi
                </button>
            </div>
        </div>
    </div>
</div>

<?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/components/tugas-absensi/modal-qr-delegasi.blade.php ENDPATH**/ ?>