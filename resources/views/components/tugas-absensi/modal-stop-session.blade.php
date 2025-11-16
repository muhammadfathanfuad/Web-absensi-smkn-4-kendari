@props([
    'modalId' => 'stopSessionModalDelegasi',
    'modalLabelId' => 'stopSessionModalDelegasiLabel',
    'stopSessionTokenId' => 'stopSessionTokenDelegasi',
    'confirmStopSessionBtnId' => 'confirmStopSessionBtnDelegasi'
])

<!-- Modal Konfirmasi Stop Session -->
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalLabelId }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalLabelId }}">Konfirmasi Hentikan Sesi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <iconify-icon icon="solar:danger-triangle-outline" class="fs-48 text-warning"></iconify-icon>
                </div>
                <p class="text-center mb-0">Apakah Anda yakin ingin menghentikan sesi absensi?</p>
                <p class="text-muted text-center small mt-2">Tindakan ini tidak dapat dibatalkan dan akan menghentikan semua proses absensi yang sedang berlangsung.</p>
                <input type="hidden" id="{{ $stopSessionTokenId }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger d-flex align-items-center" id="{{ $confirmStopSessionBtnId }}">
                    <iconify-icon icon="solar:stop-circle-outline" class="fs-16 me-2"></iconify-icon>
                    Ya, Hentikan Sesi
                </button>
            </div>
        </div>
    </div>
</div>

