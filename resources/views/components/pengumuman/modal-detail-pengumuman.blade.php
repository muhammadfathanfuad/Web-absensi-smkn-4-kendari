@props([
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
])

{{-- Modal Baca Selengkapnya --}}
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalLabelId }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalLabelId }}">
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
                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" id="{{ $modalIconId }}">
                                    <i class="bx bx-news fs-20 text-primary" id="{{ $modalIconClassId }}"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1" id="{{ $modalTitleId }}">Judul Pengumuman</h6>
                                <div class="d-flex align-items-center text-muted">
                                    <small class="me-3">
                                        <i class="bx bx-calendar me-1"></i>
                                        <span id="{{ $modalDateId }}">Tanggal</span>
                                    </small>
                                    <small class="me-3">
                                        <i class="bx bx-user me-1"></i>
                                        <span id="{{ $modalAuthorId }}">Penulis</span>
                                    </small>
                                    <small>
                                        <i class="bx bx-tag me-1"></i>
                                        <span id="{{ $modalCategoryId }}">Kategori</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-top pt-3">
                            <h6 class="text-muted mb-2">Isi Pengumuman:</h6>
                            <div class="bg-light p-3 rounded" id="{{ $modalContentId }}">
                                <!-- Content will be loaded here -->
                            </div>
                        </div>
                        
                        <div class="border-top pt-3 mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        <i class="bx bx-time me-1"></i>
                                        Dibuat: <span id="{{ $modalCreatedAtId }}">-</span>
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bx bx-expire me-1"></i>
                                        Berlaku hingga: <span id="{{ $modalExpiresAtId }}">-</span>
                                    </small>
                                </div>
                                <div>
                                    <span class="badge" id="{{ $modalPriorityId }}">Prioritas</span>
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
                <button type="button" class="btn btn-primary" id="{{ $modalMarkReadBtnId }}" onclick="{{ $markReadFunction }}()">
                    <i class="bx bx-check me-1"></i>Telah Dibaca
                </button>
            </div>
        </div>
    </div>
</div>

