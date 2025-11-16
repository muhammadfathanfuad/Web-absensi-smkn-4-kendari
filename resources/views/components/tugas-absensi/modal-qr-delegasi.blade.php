@props([
    'modalId' => 'qrModalDelegasi',
    'modalLabelId' => 'qrModalDelegasiLabel',
    'qrCodeContainerId' => 'qrCodeContainerDelegasi',
    'qrInfoTextId' => 'qrInfoTextDelegasi',
    'stopSessionBtnId' => 'stopSessionBtnDelegasi'
])

<!-- Modal QR Code untuk Delegasi -->
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalLabelId }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalLabelId }}">
                    <iconify-icon icon="solar:qr-code-outline" class="fs-20 me-2"></iconify-icon>
                    QR Code Absensi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div id="{{ $qrCodeContainerId }}" class="d-flex justify-content-center align-items-center mx-auto" style="width:280px; height:280px; border: 2px dashed #dee2e6; border-radius: 12px; background-color: #f8f9fa; position: relative; min-height: 280px;">
                        <div class="text-muted text-center">
                            <iconify-icon icon="solar:qr-code-outline" class="fs-48 d-block mx-auto mb-2"></iconify-icon>
                            QR Code akan muncul di sini...
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="mb-2" id="{{ $qrInfoTextId }}"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-danger d-flex align-items-center" id="{{ $stopSessionBtnId }}" style="display: none;">
                    <iconify-icon icon="solar:stop-circle-outline" class="fs-16 me-2"></iconify-icon>
                    Hentikan Sesi
                </button>
            </div>
        </div>
    </div>
</div>

