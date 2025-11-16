<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'modalId' => 'stopSessionModalDelegasi',
    'modalLabelId' => 'stopSessionModalDelegasiLabel',
    'stopSessionTokenId' => 'stopSessionTokenDelegasi',
    'confirmStopSessionBtnId' => 'confirmStopSessionBtnDelegasi'
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
    'modalId' => 'stopSessionModalDelegasi',
    'modalLabelId' => 'stopSessionModalDelegasiLabel',
    'stopSessionTokenId' => 'stopSessionTokenDelegasi',
    'confirmStopSessionBtnId' => 'confirmStopSessionBtnDelegasi'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<!-- Modal Konfirmasi Stop Session -->
<div class="modal fade" id="<?php echo e($modalId); ?>" tabindex="-1" aria-labelledby="<?php echo e($modalLabelId); ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="<?php echo e($modalLabelId); ?>">Konfirmasi Hentikan Sesi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <iconify-icon icon="solar:danger-triangle-outline" class="fs-48 text-warning"></iconify-icon>
                </div>
                <p class="text-center mb-0">Apakah Anda yakin ingin menghentikan sesi absensi?</p>
                <p class="text-muted text-center small mt-2">Tindakan ini tidak dapat dibatalkan dan akan menghentikan semua proses absensi yang sedang berlangsung.</p>
                <input type="hidden" id="<?php echo e($stopSessionTokenId); ?>">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger d-flex align-items-center" id="<?php echo e($confirmStopSessionBtnId); ?>">
                    <iconify-icon icon="solar:stop-circle-outline" class="fs-16 me-2"></iconify-icon>
                    Ya, Hentikan Sesi
                </button>
            </div>
        </div>
    </div>
</div>

<?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/components/tugas-absensi/modal-stop-session.blade.php ENDPATH**/ ?>