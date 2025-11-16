@props([
    'modalId' => 'notificationModal',
    'modalLabelId' => 'notificationModalLabel',
    'messageId' => 'notificationMessage',
    'errorsId' => 'notificationErrors',
    'errorsBodyId' => 'notificationErrorsBody',
    'modalSize' => '', // 'modal-lg' or empty
    'showErrorsTable' => false, // Set to true if errors table is needed
    'showIcon' => false, // Set to true if icon is needed
    'iconId' => 'notificationIcon' // ID for icon element
])

{{-- Modal Notifikasi --}}
<div id="{{ $modalId }}" class="modal fade" tabindex="-1" aria-labelledby="{{ $modalLabelId }}" aria-hidden="true">
    <div class="modal-dialog {{ $modalSize }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalLabelId }}">Notifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($showIcon)
                <div class="text-center mb-3">
                    <iconify-icon id="{{ $iconId }}" class="fs-48"></iconify-icon>
                </div>
                @endif
                <div id="{{ $messageId }}" style="white-space: pre-wrap;" class="{{ $showIcon ? 'text-center' : '' }}"></div>
                @if($showErrorsTable)
                <div id="{{ $errorsId }}" class="mt-3 d-none">
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
                            <tbody id="{{ $errorsBodyId }}">
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

