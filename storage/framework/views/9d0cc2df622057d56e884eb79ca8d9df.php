<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'formAction' => '#',
    'formId' => 'permohonanForm',
    'mode' => 'guru', // 'guru' or 'murid'
    'showTimetables' => false, // Show jadwal mengajar field (for guru)
    'dateRangeInfoId' => 'date_range_info',
    'dateRangeTextId' => 'date_range_text',
    'timetablesLoadingId' => 'timetables_loading',
    'timetablesListId' => 'timetables_list',
    'timetablesCheckboxesId' => 'timetables_checkboxes',
    'noTimetablesId' => 'no_timetables',
    'timetablesPlaceholderId' => 'timetables_placeholder'
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
    'formAction' => '#',
    'formId' => 'permohonanForm',
    'mode' => 'guru', // 'guru' or 'murid'
    'showTimetables' => false, // Show jadwal mengajar field (for guru)
    'dateRangeInfoId' => 'date_range_info',
    'dateRangeTextId' => 'date_range_text',
    'timetablesLoadingId' => 'timetables_loading',
    'timetablesListId' => 'timetables_list',
    'timetablesCheckboxesId' => 'timetables_checkboxes',
    'noTimetablesId' => 'no_timetables',
    'timetablesPlaceholderId' => 'timetables_placeholder'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="col-lg-8">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0 d-flex align-items-center">
                <i class="bx bx-file-plus me-2"></i>
                Ajukan Permohonan Izin
            </h4>
        </div>
        <div class="card-body">
            <form id="<?php echo e($formId); ?>" action="<?php echo e($formAction); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php if($mode === 'guru'): ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="leave_date" class="form-label">Tanggal Mulai Izin <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="leave_date" name="leave_date" min="<?php echo e(date('Y-m-d')); ?>" required>
                                <small class="form-text text-muted">Pilih tanggal mulai izin</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Tanggal Akhir Izin <span class="text-muted">(Opsional)</span></label>
                                <input type="date" class="form-control" id="end_date" name="end_date" min="<?php echo e(date('Y-m-d')); ?>">
                                <small class="form-text text-muted">Kosongkan jika izin hanya satu hari</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <div id="<?php echo e($dateRangeInfoId); ?>" class="alert alert-info" style="display: none;">
                                    <i class="bx bx-info-circle"></i> <span id="<?php echo e($dateRangeTextId); ?>"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if($showTimetables): ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Jadwal Mengajar <span class="text-danger">*</span></label>
                                <div id="<?php echo e($timetablesLoadingId); ?>" class="text-center py-3" style="display: none;">
                                    <div class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <span class="ms-2">Mencari jadwal mengajar...</span>
                                </div>
                                <div id="<?php echo e($timetablesListId); ?>" class="border rounded p-3" style="display: none;">
                                    <p class="text-muted mb-2">Pilih jadwal yang akan diizinkan:</p>
                                    <div id="<?php echo e($timetablesCheckboxesId); ?>"></div>
                                    <div id="<?php echo e($noTimetablesId); ?>" class="alert alert-warning" style="display: none;">
                                        <i class="bx bx-info-circle"></i> Tidak ada jadwal mengajar dalam periode yang dipilih.
                                    </div>
                                </div>
                                <div id="<?php echo e($timetablesPlaceholderId); ?>" class="alert alert-info">
                                    <i class="bx bx-info-circle"></i> Pilih tanggal mulai dan akhir izin terlebih dahulu untuk melihat jadwal mengajar.
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="leave_type" class="form-label">Jenis Izin <span class="text-danger">*</span></label>
                                <select class="form-select" id="leave_type" name="leave_type" required>
                                    <option value="">Pilih Jenis Izin</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="izin">Izin</option>
                                    <option value="keperluan-keluarga">Keperluan Keluarga</option>
                                    <option value="acara-keluarga">Acara Keluarga</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3" id="custom_leave_type_wrapper" style="display: none;">
                                <label for="custom_leave_type" class="form-label">Jenis Izin Lainnya <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="custom_leave_type" name="custom_leave_type" placeholder="Tuliskan jenis izin lainnya...">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Alasan Izin <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reason" name="reason" rows="4" placeholder="Jelaskan alasan mengajukan izin..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="dokumenPendukung" class="form-label">Dokumen Pendukung</label>
                        <input type="file" class="form-control" id="dokumenPendukung" name="dokumenPendukung" accept=".pdf,.jpg,.jpeg,.png" data-max-size="512000">
                        <small class="form-text text-muted">Format yang diperbolehkan: PDF, JPG, PNG (Maksimal 500KB)</small>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-send me-1"></i>
                            Ajukan Permohonan
                        </button>
                    </div>
                <?php else: ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenisIzin" class="form-label">Jenis Izin <span class="text-danger">*</span></label>
                                <select class="form-select" id="jenisIzin" name="jenisIzin" required>
                                    <option value="">Pilih Jenis Izin</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="izin">Izin</option>
                                    <option value="keperluan-keluarga">Keperluan Keluarga</option>
                                    <option value="acara-keluarga">Acara Keluarga</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggalMulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggalMulai" name="tanggalMulai" required>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row" id="jenisIzinLainnya" style="display: none;">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenisIzinCustom" class="form-label">Jenis Izin Lainnya <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="jenisIzinCustom" name="jenisIzinCustom" placeholder="Tuliskan jenis izin lainnya...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggalSelesai" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggalSelesai" name="tanggalSelesai">
                            </div>
                        </div>
                    </div>

                    
                    <div class="row" id="tanggalSelesaiNormal">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggalSelesaiNormal" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggalSelesaiNormal" name="tanggalSelesaiNormal" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alasan" class="form-label">Alasan Izin <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alasan" name="alasan" rows="4" placeholder="Jelaskan alasan mengajukan izin..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="dokumenPendukung" class="form-label">Dokumen Pendukung</label>
                        <input type="file" class="form-control" id="dokumenPendukung" name="dokumenPendukung" accept=".pdf,.jpg,.jpeg,.png" data-max-size="512000">
                        <small class="form-text text-muted">Format yang diperbolehkan: PDF, JPG, PNG (Maksimal 500KB)</small>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-send me-1"></i>
                            Ajukan Permohonan
                        </button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/components/permohonan-izin/form-permohonan-izin.blade.php ENDPATH**/ ?>