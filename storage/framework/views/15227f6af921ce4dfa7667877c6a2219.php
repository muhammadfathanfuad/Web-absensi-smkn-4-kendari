<?php $__env->startSection('content'); ?>
<?php $__env->startSection('css'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['node_modules/gridjs/dist/theme/mermaid.min.css']); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['node_modules/select2/dist/css/select2.min.css']); ?>
    <style>
        .filter-field-auto-set {
            border: 2px solid #28a745 !important;
            background-color: #f8f9fa !important;
            transition: all 0.3s ease;
        }
        .filter-field-auto-set:focus {
            border-color: #28a745 !important;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
        }
        .auto-set-indicator {
            font-size: 0.75rem;
            font-weight: 600;
            color: #28a745;
            margin-top: 2px;
            display: block;
        }
        
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'Jadwal Pelajaran'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="row">
        <div class="card">
            <div class="col-lg-0">
                <div class="card-body">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a href="#kelasx" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                <span class="d-block d-sm-none"><i class="bx bx-home"></i></span>
                                <span class="d-none d-sm-block">Kelas X</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#kelasxi" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                <span class="d-block d-sm-none"><i class="bx bx-user"></i></span>
                                <span class="d-none d-sm-block">Kelas XI</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#kelasxii" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                <span class="d-block d-sm-none"><i class="bx bx-envelope"></i></span>
                                <span class="d-none d-sm-block">Kelas XII</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#mapel" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                <span class="d-block d-sm-none"><i class="bx bx-envelope"></i></span>
                                <span class="d-none d-sm-block">Info Akademik</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#manual" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                <span class="d-block d-sm-none"><i class="bx bx-plus"></i></span>
                                <span class="d-none d-sm-block">Tambah Mata Pelajaran Manual</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content text-muted">
                        <div class="tab-pane show active" id="kelasx">
                            <div class="card-header">
                                <h5 class="card-title">Jadwal Pelajaran Kelas X</h5>
                                    <div class="d-flex justify-content-between align-items-center mb-0">
                                    <p class="text-muted mb-0">
                                        Data Semua jadwal pelajaran
                                    </p>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="mb-0">
                                            <label for="kelasXSemesterFilter" class="form-label mb-0 me-2">Semester:</label>
                                            <select class="form-select form-select-sm" id="kelasXSemesterFilter" style="width: auto;">
                                                <option value="">Memuat semester...</option>
                                            </select>
                                        </div>
                                        <div id="single-actions-jadwal">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#addUserModal">
                                                Import Jadwal
                                            </button>
                                        </div>
                                    </div>
                                    <div id="bulk-actions-jadwal" style="display: none;">
                                        <button type="button" class="btn btn-danger" id="bulk-delete-jadwal">Hapus Terpilih</button>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div id="table-search"></div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAllJadwalModal">
                                        <i class="bx bx-trash me-1"></i> Hapus Semua Data Jadwal Kelas X
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="kelasxi">
                            <div class="card-header">
                                <h5 class="card-title">Jadwal Pelajaran Kelas XI</h5>
                                <div class="d-flex justify-content-between align-items-center mb-0">
                                    <p class="text-muted mb-0">
                                        Jadwal Pelajaran Semester ini
                                    </p>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <label for="kelasXISemesterFilter" class="form-label mb-0 fw-medium">Semester:</label>
                                            <select class="form-select form-select-sm" id="kelasXISemesterFilter" style="width: 200px;">
                                                <option value="">Memuat semester...</option>
                                            </select>
                                        </div>
                                        <div id="single-actions-jadwal-xi">
                                            <button type="button" class="btn btn-info me-2" id="filter-jadwal-xi">
                                                <i class="bx bx-filter me-1"></i> Filter
                                            </button>
                                            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#importJadwalXIModal">Import Jadwal
                                            </button>
                                        </div>
                                    </div>
                                    <div id="bulk-actions-jadwal-xi" style="display:none">
                                        <button type="button" class="btn btn-danger" id="bulk-delete-jadwal-xi">Hapus Terpilih</button>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div id="table-search-xi"></div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAllJadwalXiModal">
                                        <i class="bx bx-trash me-1"></i> Hapus Semua Data Jadwal Kelas XI
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="kelasxii">
                            <div class="card-header">
                                <h5 class="card-title">Jadwal Pelajaran kelas XII</h5>
                                <div class="d-flex justify-content-between align-items-center mb-0">
                                    <p class="text-muted mb-0">
                                        Jadwal Pelajaran Semester 1
                                    </p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addMuridModal">
                                        Tambah Jadwal
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                <div id="table-murid"></div>
                            </div>
                        </div>
                        
                        <div class="tab-pane" id="mapel">
                            <!-- Card Daftar Semester -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title">Daftar Semester</h5>
                                    <div class="d-flex justify-content-between align-items-center mb-0">
                                        <p class="text-muted mb-0">
                                            Kelola semester akademik untuk sistem jadwal pelajaran
                                        </p>
                                        <div id="single-actions-terms">
                                            <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addTermModal">
                                                <i class="bx bx-plus me-1"></i> Tambah Semester
                                            </button>
                                        </div>
                                        <div id="bulk-actions-terms" style="display: none;">
                                            <button type="button" class="btn btn-danger" id="bulk-delete-terms">
                                                <i class="bx bx-trash me-1"></i> Hapus Terpilih
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div id="terms-table"></div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAllTermsModal">
                                            <i class="bx bx-trash me-1"></i> Hapus Semua Data Semester
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Daftar Kelas -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title">Daftar Kelas</h5>
                                    <div class="d-flex justify-content-between align-items-center mb-0">
                                        <p class="text-muted mb-0">
                                            Semua kelas yang terdaftar di sistem
                                        </p>
                                        <div id="single-actions-classes">
                                            <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addClassModal">
                                                <i class="bx bx-plus me-1"></i> Tambah Kelas
                                            </button>
                                            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#importClassModal">
                                                <i class="bx bx-upload me-1"></i> Impor Kelas
                                            </button>
                                        </div>
                                        <div id="bulk-actions-classes" style="display: none;">
                                            <button type="button" class="btn btn-danger" id="bulk-delete-classes">
                                                <i class="bx bx-trash me-1"></i> Hapus Terpilih
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="classes-table"></div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAllClassesModal">
                                            <i class="bx bx-trash me-1"></i> Hapus Semua Data Kelas
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Daftar Mata Pelajaran -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Daftar Mata Pelajaran</h5>
                                    <div class="d-flex justify-content-between align-items-center mb-0">
                                        <p class="text-muted mb-0">
                                            Semua mata pelajaran yang tersedia di sistem
                                        </p>
                                        <div id="single-actions-subjects">
                                            <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                                                <i class="bx bx-plus me-1"></i> Tambah Mata Pelajaran
                                            </button>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadSubjectModal">
                                                <i class="bx bx-upload me-1"></i> Import Mata Pelajaran
                                            </button>
                                        </div>
                                        <div id="bulk-actions-subjects" style="display: none;">
                                            <button type="button" class="btn btn-danger" id="bulk-delete-subjects">
                                                <i class="bx bx-trash me-1"></i> Hapus Terpilih
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div id="subjects-table"></div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAllSubjectsModal">
                                            <i class="bx bx-trash me-1"></i> Hapus Semua Data Mata Pelajaran
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tab Manual Class Subject -->
                        <div class="tab-pane" id="manual">
                            <div class="card-header">
                                <h5 class="card-title">Tambah Mata Pelajaran Manual</h5>
                                <div class="d-flex justify-content-between align-items-center mb-0">
                                    <p class="text-muted mb-0">
                                        Tambahkan mata pelajaran untuk kelas 10 dan 11 secara manual
                                    </p>
                                    <div>
                                        <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addManualClassSubjectModal">
                                            <i class="bx bx-plus me-1"></i> Tambah Mata Pelajaran
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary">
                                                    <i class="bx bx-info-circle me-2"></i>Informasi
                                                </h6>
                                                <p class="mb-0 text-muted">
                                                    Fitur ini memungkinkan Anda untuk menambahkan mata pelajaran secara manual 
                                                    dengan memilih guru, mata pelajaran, dan kelas yang telah terdaftar di sistem.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title text-success">
                                                    <i class="bx bx-check-circle me-2"></i>Kelas yang Didukung
                                                </h6>
                                                <p class="mb-0 text-muted">
                                                    Hanya kelas 10 (X) dan 11 (XI) yang dapat ditambahkan melalui fitur ini.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tabel Kelas -->
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Jadwal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Import Jadwal Pelajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="importJadwalForm" action="<?php echo e(route('jadwal.import')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="jadwalTerm" class="form-label">Pilih Semester <span class="text-danger">*</span></label>
                            <select class="form-select" id="jadwalTerm" name="term_id" required>
                                <option value="">Memuat semester yang tersedia...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="grade" class="form-label">Pilih Kelas</label>
                            <select class="form-select" id="grade" name="grade" required>
                                <option value="">Pilih Kelas</option>
                                <option value="X">Kelas X</option>
                                <option value="XI">Kelas XI</option>
                                <option value="XII">Kelas XII</option>
                            </select>
                        </div>
                        <div class="mb-3" id="weekTypeContainer" style="display: none;">
                            <label for="week_type" class="form-label">Tipe Minggu</label>
                            <select class="form-select" id="week_type" name="week_type">
                                <option value="">Pilih Tipe Minggu</option>
                                <option value="ganjil">Ganjil</option>
                                <option value="genap">Genap</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jadwalFile" class="form-label">Pilih File Excel atau CSV</label>
                            <input type="file" class="form-control" id="jadwalFile" name="file" accept=".xlsx,.csv" required>
                            <div class="form-text">Format yang didukung: .xlsx, .csv</div>
                        </div>
                        <button type="submit" class="btn btn-primary">Import Jadwal</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Mata Pelajaran -->
    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubjectModalLabel">Tambah Mata Pelajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addSubjectForm" action="<?php echo e(route('subjects.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="subjectCode" class="form-label">Kode Mata Pelajaran</label>
                            <input type="text" class="form-control" id="subjectCode" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label for="subjectName" class="form-label">Nama Mata Pelajaran</label>
                            <input type="text" class="form-control" id="subjectName" name="name" required>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" id="uploadSubjectBtn" data-bs-toggle="modal" data-bs-target="#uploadSubjectModal">Upload File</button>
                            <button type="submit" class="btn btn-primary">Tambah Mata Pelajaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kelas -->
    <div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClassModalLabel">Tambah Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addClassForm" action="<?php echo e(route('classes.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="className" class="form-label">Nama Kelas</label>
                            <input type="text" class="form-control" id="className" name="name" placeholder="Contoh: TKJA, TKJB, RPLA" required>
                            <div class="form-text">Masukkan nama kelas (contoh: TKJA, TKJB, RPLA, dll)</div>
                        </div>
                        <div class="mb-3">
                            <label for="classGrade" class="form-label">Grade</label>
                            <select class="form-select" id="classGrade" name="grade" required>
                                <option value="">Pilih Grade</option>
                                <option value="10">10 (Kelas X)</option>
                                <option value="11">11 (Kelas XI)</option>
                                <option value="12">12 (Kelas XII)</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Tambah Kelas</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Mata Pelajaran -->
    <div class="modal fade" id="uploadSubjectModal" tabindex="-1" aria-labelledby="uploadSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadSubjectModalLabel">Upload Mata Pelajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadSubjectForm" action="/admin/subjects/upload" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="uploadSubjectFile" class="form-label">Upload File Excel atau CSV</label>
                            <input type="file" class="form-control" id="uploadSubjectFile" name="file" accept=".xlsx,.csv" required>
                            <div class="form-text">Format yang didukung: .xlsx, .csv. Data akan diimport dari file.</div>
                        </div>
                        <button type="submit" class="btn btn-primary">Import Mata Pelajaran</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- XI: Modal Konfirmasi Hapus (single) -->
    <div class="modal fade" id="deleteJadwalXiModal" tabindex="-1" aria-labelledby="deleteJadwalXiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteJadwalXiModalLabel">Konfirmasi Hapus Jadwal XI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus jadwal XI ini?
                    <input type="hidden" id="deleteJadwalXiId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteJadwalXiButton">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- XI: Modal Konfirmasi Hapus Massal (bulk) -->
    <div class="modal fade" id="bulkDeleteJadwalXiModal" tabindex="-1" aria-labelledby="bulkDeleteJadwalXiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkDeleteJadwalXiModalLabel">Konfirmasi Hapus Massal Jadwal XI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus semua jadwal XI yang dipilih?
                    <input type="hidden" id="deleteJadwalXiIds">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmBulkDeleteJadwalXiButton">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" data-action="/admin/user" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <input type="hidden" id="editUserId" name="id">
                        <div class="mb-3">
                            <label for="editUserName" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="editUserName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUserEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editUserEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUserPhone" class="form-label">Nomor Hp</label>
                            <input type="text" class="form-control" id="editUserPhone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUserUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="editUserUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUserPassword" class="form-label">Password (Opsional)</label>
                            <input type="password" class="form-control" id="editUserPassword" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="editUserStatus" class="form-label">Status</label>
                            <select class="form-select" id="editUserStatus" name="status" required>
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteUserModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus user ini?</p>
                    <input type="hidden" id="deleteUserId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteUserButton">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <div id="notificationModal" class="modal fade" tabindex="-1" aria-labelledby="notificationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">Notifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0" id="notificationMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteJadwalModal" tabindex="-1" aria-labelledby="deleteJadwalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteJadwalModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus jadwal ini?</p>
                    <input type="hidden" id="deleteJadwalId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteJadwalButton">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bulkDeleteJadwalModal" tabindex="-1" aria-labelledby="bulkDeleteJadwalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkDeleteJadwalModalLabel">Konfirmasi Hapus Massal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus jadwal yang dipilih?</p>
                    <form id="bulkDeleteJadwalForm" action="<?php echo e(route('jadwal.bulkDelete')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <input type="hidden" name="ids" id="deleteJadwalIds">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmBulkDeleteJadwalButton">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Guru -->
    <div class="modal fade" id="addGuruModal" tabindex="-1" aria-labelledby="addGuruModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGuruModalLabel">Tambah Guru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addGuruForm" action="<?php echo e(route('guru.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="addGuruUser" class="form-label">Username Guru</label>
                            <input type="text" class="form-control" id="addGuruUser" name="user_username" required>
                        </div>
                        <div class="mb-3">
                            <label for="addGuruNip" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="addGuruNip" name="nip">
                        </div>
                        <div class="mb-3">
                            <label for="addGuruDepartment" class="form-label">Department</label>
                            <input type="text" class="form-control" id="addGuruDepartment" name="department">
                        </div>
                        <div class="mb-3">
                            <label for="addGuruTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="addGuruTitle" name="title">
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah Guru</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Murid -->
    <div class="modal fade" id="addMuridModal" tabindex="-1" aria-labelledby="addMuridModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMuridModalLabel">Tambah Murid</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addMuridForm" action="<?php echo e(route('murid.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="addMuridUser" class="form-label">Username Murid</label>
                            <input type="text" class="form-control" id="addMuridUser" name="user_username" required>
                        </div>
                        <div class="mb-3">
                            <label for="addMuridNis" class="form-label">NIS</label>
                            <input type="text" class="form-control" id="addMuridNis" name="nis" required>
                        </div>
                        <div class="mb-3">
                            <label for="addMuridClass" class="form-label">Kelas</label>
                            <select class="form-select" id="addMuridClass" name="class_id" required>
                                <option value="">Pilih Kelas</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="addMuridGuardianName" class="form-label">Nama Wali</label>
                            <input type="text" class="form-control" id="addMuridGuardianName" name="guardian_name">
                        </div>
                        <div class="mb-3">
                            <label for="addMuridGuardianPhone" class="form-label">Telepon Wali</label>
                            <input type="text" class="form-control" id="addMuridGuardianPhone" name="guardian_phone">
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah Murid</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Guru -->
    <div class="modal fade" id="editGuruModal" tabindex="-1" aria-labelledby="editGuruModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editGuruModalLabel">Edit Guru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editGuruForm" data-action="/admin/guru" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <input type="hidden" id="editGuruId" name="id">
                        <div class="mb-3">
                            <label for="editGuruUserName" class="form-label">Nama Guru</label>
                            <input type="text" class="form-control" id="editGuruUserName" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editGuruNip" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="editGuruNip" name="nip">
                        </div>
                        <div class="mb-3">
                            <label for="editGuruDepartment" class="form-label">Department</label>
                            <input type="text" class="form-control" id="editGuruDepartment" name="department">
                        </div>
                        <div class="mb-3">
                            <label for="editGuruTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="editGuruTitle" name="title">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Murid -->
    <div class="modal fade" id="editMuridModal" tabindex="-1" aria-labelledby="editMuridModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMuridModalLabel">Edit Murid</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editMuridForm" data-action="/admin/murid" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <input type="hidden" id="editMuridId" name="id">
                        <div class="mb-3">
                            <label for="editMuridUserName" class="form-label">Nama Murid</label>
                            <input type="text" class="form-control" id="editMuridUserName" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editMuridNis" class="form-label">NIS</label>
                            <input type="text" class="form-control" id="editMuridNis" name="nis" required>
                        </div>
                        <div class="mb-3">
                            <label for="editMuridClass" class="form-label">Kelas</label>
                            <select class="form-select" id="editMuridClass" name="class_id" required>
                                <option value="">Pilih Kelas</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editMuridGuardianName" class="form-label">Nama Wali</label>
                            <input type="text" class="form-control" id="editMuridGuardianName" name="guardian_name">
                        </div>
                        <div class="mb-3">
                            <label for="editMuridGuardianPhone" class="form-label">Telepon Wali</label>
                            <input type="text" class="form-control" id="editMuridGuardianPhone" name="guardian_phone">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Guru -->
    <div class="modal fade" id="deleteGuruModal" tabindex="-1" aria-labelledby="deleteGuruModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteGuruModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus guru ini?</p>
                    <input type="hidden" id="deleteGuruId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteGuruButton">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Murid -->
    <div class="modal fade" id="deleteMuridModal" tabindex="-1" aria-labelledby="deleteMuridModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteMuridModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus murid ini?</p>
                    <input type="hidden" id="deleteMuridId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteMuridButton">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Filter Jadwal XI Modal -->
    <div class="modal fade" id="filterJadwalXiModal" tabindex="-1" aria-labelledby="filterJadwalXiLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterJadwalXiLabel">Filter Jadwal Kelas XI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-3">
                        <h6><i class="bx bx-info-circle me-2"></i>Smart Filter</h6>
                        <ul class="mb-0 small">
                            <li><strong>Kelompok A:</strong> Jika memilih salah satu kelas ini TKJA, TKJC, RPLA, RPLC, KTA, DKVA, PSPTA</li>
                            <li><strong>Kelompok B:</strong> Jika memilih salah satu kelas ini TKJB, RPLB, KTB, KK, DKVB, PSPTB</li>
                            <li><strong>Otomatisasi Lokasi:</strong></li>
                            <li class="ms-3">• <strong>Kelompok A:</strong> Ganjil = Lab, Genap = Teori</li>
                            <li class="ms-3">• <strong>Kelompok B:</strong> Ganjil = Teori, Genap = Lab</li>
                        </ul>
                    </div>
                    <form id="filterJadwalXiForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="filterClass" class="form-label">Kelas</label>
                                <select class="form-select" id="filterClass" name="class">
                                    <option value="">Semua Kelas</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="filterGroupType" class="form-label">Kelompok</label>
                                <select class="form-select" id="filterGroupType" name="group_type">
                                    <option value="">Semua Kelompok</option>
                                    <option value="A">Kelompok A</option>
                                    <option value="B">Kelompok B</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="filterWeekType" class="form-label">Minggu</label>
                                <select class="form-select" id="filterWeekType" name="week_type">
                                    <option value="">Semua Minggu</option>
                                    <option value="ganjil">Ganjil</option>
                                    <option value="genap">Genap</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="filterLocationType" class="form-label">Lokasi</label>
                                <select class="form-select" id="filterLocationType" name="location_type">
                                    <option value="">Semua Lokasi</option>
                                    <option value="lab">Lab</option>
                                    <option value="theory">Teori</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="filterDay" class="form-label">Hari</label>
                                <select class="form-select" id="filterDay" name="day">
                                    <option value="">Semua Hari</option>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                    <option value="Minggu">Minggu</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" id="resetFilterBtn">Reset</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="applyFilterBtn">Terapkan Filter</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Jadwal XI Modal (with class & group selection) -->
    <div class="modal fade" id="importJadwalXIModal" tabindex="-1" aria-labelledby="importJadwalXILabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importJadwalXILabel">Import Mata Pelajaran - Kelas XI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="importJadwalXiForm" action="/admin/jadwal-xi/import" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="xiTerm" class="form-label">Pilih Semester <span class="text-danger">*</span></label>
                            <select class="form-select" id="xiTerm" name="term_id" required>
                                <option value="">Memuat semester yang tersedia...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="xiGrade" class="form-label">Pilih Grade</label>
                            <select class="form-select" id="xiGrade" name="grade" required>
                                <option value="">Pilih Grade</option>
                                <option value="X">Kelas X</option>
                                <option value="XI">Kelas XI</option>
                                <option value="XII">Kelas XII</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="xiFile" class="form-label">Pilih File</label>
                            <input type="file" class="form-control" id="xiFile" name="file" accept=".xlsx,.csv" required>
                        </div>
                        <div class="mb-3">
                            <label for="xiGroupType" class="form-label">Kelompok</label>
                            <select class="form-select" id="xiGroupType" name="group_type" required>
                                <option value="">Pilih Kelompok</option>
                                <option value="A">Kelompok A</option>
                                <option value="B">Kelompok B</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" form="importJadwalXiForm" class="btn btn-success">Import</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Kelas -->
    <div class="modal fade" id="classDetailModal" tabindex="-1" aria-labelledby="classDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary bg-opacity-10">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:buildings-2-outline" class="fs-24 text-primary"></iconify-icon>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0" id="classDetailModalLabel">Detail Kelas</h5>
                            <p class="text-muted mb-0 fs-13">Informasi lengkap kelas</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Informasi Dasar -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-0 bg-light bg-opacity-50">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3">
                                        <iconify-icon icon="solar:info-circle-outline" class="me-2"></iconify-icon>
                                        Informasi Dasar
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs bg-primary bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <iconify-icon icon="solar:tag-outline" class="fs-16 text-primary"></iconify-icon>
                                                </div>
                                                <div>
                                                    <p class="text-muted mb-0 fs-13">Nama Kelas</p>
                                                    <h6 class="mb-0" id="classDetailName">-</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs bg-success bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <iconify-icon icon="solar:bookmark-outline" class="fs-16 text-success"></iconify-icon>
                                                </div>
                                                <div>
                                                    <p class="text-muted mb-0 fs-13">Display Grade</p>
                                                    <h6 class="mb-0" id="classDetailDisplayGrade">-</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs bg-info bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <iconify-icon icon="solar:graduation-outline" class="fs-16 text-info"></iconify-icon>
                                                </div>
                                                <div>
                                                    <p class="text-muted mb-0 fs-13">Grade</p>
                                                    <span class="badge bg-primary fs-12" id="classDetailGrade">-</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs bg-warning bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <iconify-icon icon="solar:calendar-outline" class="fs-16 text-warning"></iconify-icon>
                                                </div>
                                                <div>
                                                    <p class="text-muted mb-0 fs-13">Dibuat</p>
                                                    <h6 class="mb-0" id="classDetailCreated">-</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light bg-opacity-50">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <iconify-icon icon="solar:close-circle-outline" class="me-1"></iconify-icon>
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kelas -->
    <div class="modal fade" id="editClassModal" tabindex="-1" aria-labelledby="editClassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClassModalLabel">Edit Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editClassForm">
                    <div class="modal-body">
                        <input type="hidden" id="editClassId" name="id">
                        <div class="mb-3">
                            <label for="editClassName" class="form-label">Nama Kelas</label>
                            <input type="text" class="form-control" id="editClassName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editClassGrade" class="form-label">Grade</label>
                            <select class="form-select" id="editClassGrade" name="grade" required>
                                <option value="10">10 (X)</option>
                                <option value="11">11 (XI)</option>
                                <option value="12">12 (XII)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Kelas -->
    <div class="modal fade" id="deleteClassModal" tabindex="-1" aria-labelledby="deleteClassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteClassModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus kelas ini?</p>
                    <input type="hidden" id="deleteClassId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="deleteClass()">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Bulk Delete Mata Pelajaran -->
    <div class="modal fade" id="bulkDeleteSubjectsModal" tabindex="-1" aria-labelledby="bulkDeleteSubjectsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkDeleteSubjectsModalLabel">Konfirmasi Hapus Massal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus <strong id="subjects-count-text">0</strong> mata pelajaran yang dipilih?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmBulkDeleteSubjects">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Bulk Delete Kelas -->
    <div class="modal fade" id="bulkDeleteClassesModal" tabindex="-1" aria-labelledby="bulkDeleteClassesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkDeleteClassesModalLabel">Konfirmasi Hapus Massal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus <strong id="classes-count-text">0</strong> kelas yang dipilih?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmBulkDeleteClasses">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Impor Kelas -->
    <div class="modal fade" id="importClassModal" tabindex="-1" aria-labelledby="importClassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importClassModalLabel">Impor Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="importClassForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="importClassFile" class="form-label">Pilih File Excel</label>
                            <input type="file" class="form-control" id="importClassFile" name="file" accept=".xlsx,.xls" required>
                            <div class="form-text">
                                Format file: Excel (.xlsx, .xls)<br>
                                Kolom yang diperlukan: Nama Kelas, Grade
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="bx bx-info-circle me-1"></i> Format File Excel
                            </h6>
                            <p class="mb-0">File Excel harus memiliki kolom:</p>
                            <ul class="mb-0 mt-2">
                                <li><strong>Nama Kelas</strong> - Nama kelas (contoh: TKJA, TKJB)</li>
                                <li><strong>Grade</strong> - Grade kelas (10, 11, atau 12)</li>
                            </ul>
                        </div>
                        <div class="text-center">
                            <a href="/public/data/Kelas.xlsx" class="btn btn-outline-primary btn-sm" download>
                                <i class="bx bx-download me-1"></i> Download Template
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-upload me-1"></i> Impor Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Notifikasi -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">Notifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0" id="notificationMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Notifikasi Manual Class Subject -->
    <div class="modal fade" id="manualNotificationModal" tabindex="-1" aria-labelledby="manualNotificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0" id="manualNotificationHeader">
                    <div class="d-flex align-items-center w-100">
                        <div class="avatar-sm rounded-circle me-3 d-flex align-items-center justify-content-center" id="manualNotificationIcon">
                            <i class="bx bx-check-circle fs-24" id="manualNotificationIconClass"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0" id="manualNotificationTitle">Berhasil!</h5>
                            <p class="text-muted mb-0 small" id="manualNotificationSubtitle">Jadwal mata pelajaran berhasil ditambahkan</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-3">
                    <div class="alert border-0 mb-0" id="manualNotificationAlert">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="bx bx-info-circle fs-20" id="manualNotificationAlertIcon"></i>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0" id="manualNotificationMessage">Jadwal mata pelajaran berhasil ditambahkan ke sistem.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detail Jadwal yang Ditambahkan -->
                    <div class="mt-3" id="manualNotificationDetails" style="display: none;">
                        <h6 class="text-muted mb-2">Detail Jadwal:</h6>
                        <div class="bg-light rounded p-3">
                            <div class="row g-2">
                                <div class="col-6">
                                    <small class="text-muted">Kelas:</small>
                                    <div class="fw-medium" id="detailClass">-</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Mata Pelajaran:</small>
                                    <div class="fw-medium" id="detailSubject">-</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Guru:</small>
                                    <div class="fw-medium" id="detailTeacher">-</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Hari:</small>
                                    <div class="fw-medium" id="detailDay">-</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Waktu:</small>
                                    <div class="fw-medium" id="detailTime">-</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Jenis:</small>
                                    <div class="fw-medium" id="detailType">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal" id="manualNotificationButton">
                        <i class="bx bx-check me-1"></i> Baik, Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Mata Pelajaran -->
    <div class="modal fade" id="subjectDetailModal" tabindex="-1" aria-labelledby="subjectDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary bg-opacity-10">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:book-2-outline" class="fs-24 text-primary"></iconify-icon>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0" id="subjectDetailModalLabel">Detail Mata Pelajaran</h5>
                            <p class="text-muted mb-0 fs-13">Informasi lengkap mata pelajaran</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Informasi Dasar -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-0 bg-light bg-opacity-50">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3">
                                        <iconify-icon icon="solar:info-circle-outline" class="me-2"></iconify-icon>
                                        Informasi Dasar
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs bg-primary bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <iconify-icon icon="solar:tag-outline" class="fs-16 text-primary"></iconify-icon>
                                                </div>
                                                <div>
                                                    <p class="text-muted mb-0 fs-13">Kode Mata Pelajaran</p>
                                                    <h6 class="mb-0" id="subjectDetailCode">-</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs bg-success bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <iconify-icon icon="solar:bookmark-outline" class="fs-16 text-success"></iconify-icon>
                                                </div>
                                                <div>
                                                    <p class="text-muted mb-0 fs-13">Nama Mata Pelajaran</p>
                                                    <h6 class="mb-0" id="subjectDetailName">-</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs bg-info bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <iconify-icon icon="solar:users-group-rounded-outline" class="fs-16 text-info"></iconify-icon>
                                                </div>
                                                <div>
                                                    <p class="text-muted mb-0 fs-13">Jumlah Kelas</p>
                                                    <span class="badge bg-primary fs-12" id="subjectDetailClassCount">0</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs bg-warning bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <iconify-icon icon="solar:user-outline" class="fs-16 text-warning"></iconify-icon>
                                                </div>
                                                <div>
                                                    <p class="text-muted mb-0 fs-13">Jumlah Guru</p>
                                                    <span class="badge bg-success fs-12" id="subjectDetailTeacherCount">0</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kelas yang Menggunakan -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-0">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3">
                                        <iconify-icon icon="solar:buildings-2-outline" class="me-2"></iconify-icon>
                                        Kelas yang Menggunakan
                                    </h6>
                                    <div id="subjectDetailClasses">
                                        <div class="text-center py-3">
                                            <div class="d-flex justify-content-center mb-3">
                                                <iconify-icon icon="solar:clock-circle-outline" class="fs-48 text-muted"></iconify-icon>
                                            </div>
                                            <p class="text-muted mb-0">Memuat data kelas...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Guru yang Mengajar -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card border-0">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3">
                                        <iconify-icon icon="solar:user-speak-outline" class="me-2"></iconify-icon>
                                        Guru yang Mengajar
                                    </h6>
                                    <div id="subjectDetailTeachers">
                                        <div class="text-center py-3">
                                            <div class="d-flex justify-content-center mb-3">
                                                <iconify-icon icon="solar:clock-circle-outline" class="fs-48 text-muted"></iconify-icon>
                                            </div>
                                            <p class="text-muted mb-0">Memuat data guru...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light bg-opacity-50">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <iconify-icon icon="solar:close-circle-outline" class="me-1"></iconify-icon>
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Edit Mata Pelajaran -->
    <div class="modal fade" id="editSubjectModal" tabindex="-1" aria-labelledby="editSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubjectModalLabel">Edit Mata Pelajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editSubjectForm">
                        <input type="hidden" id="editSubjectId" name="id">
                        <div class="mb-3">
                            <label for="editSubjectCode" class="form-label">Kode Mata Pelajaran</label>
                            <input type="text" class="form-control" id="editSubjectCode" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label for="editSubjectName" class="form-label">Nama Mata Pelajaran</label>
                            <input type="text" class="form-control" id="editSubjectName" name="name" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="updateSubject()">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Mata Pelajaran -->
    <div class="modal fade" id="deleteSubjectModal" tabindex="-1" aria-labelledby="deleteSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSubjectModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus mata pelajaran ini?</p>
                    <input type="hidden" id="deleteSubjectId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="deleteSubject()">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Semua Data Mata Pelajaran -->
    <div class="modal fade" id="deleteAllSubjectsModal" tabindex="-1" aria-labelledby="deleteAllSubjectsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAllSubjectsModalLabel">Konfirmasi Hapus Semua Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus <strong>SEMUA DATA MATA PELAJARAN</strong>?</p>
                    <p class="text-danger"><strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="deleteAllSubjects()">Ya, Hapus Semua</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Semua Data Kelas -->
    <div class="modal fade" id="deleteAllClassesModal" tabindex="-1" aria-labelledby="deleteAllClassesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAllClassesModalLabel">Konfirmasi Hapus Semua Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus <strong>SEMUA DATA KELAS</strong>?</p>
                    <p class="text-danger"><strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="deleteAllClasses()">Ya, Hapus Semua</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Semester -->
    <div class="modal fade" id="addTermModal" tabindex="-1" aria-labelledby="addTermModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTermModalLabel">Tambah Semester</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addTermForm">
                        <div class="mb-3">
                            <label for="term_name" class="form-label">Nama Semester <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="term_name" name="name" required placeholder="Contoh: 2025/2026 – Ganjil">
                        </div>
                        <div class="mb-3">
                            <label for="term_start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="term_start_date" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="term_end_date" class="form-label">Tanggal Berakhir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="term_end_date" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="term_is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="term_is_active">
                                    Set sebagai semester aktif
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="addTerm()">Tambah Semester</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Semester -->
    <div class="modal fade" id="editTermModal" tabindex="-1" aria-labelledby="editTermModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTermModalLabel">Edit Semester</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTermForm">
                        <input type="hidden" id="edit_term_id" name="id">
                        <div class="mb-3">
                            <label for="edit_term_name" class="form-label">Nama Semester <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_term_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_term_start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_term_start_date" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_term_end_date" class="form-label">Tanggal Berakhir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_term_end_date" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_term_is_active" name="is_active" value="1">
                                <label class="form-check-label" for="edit_term_is_active">
                                    Set sebagai semester aktif
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="updateTerm()">Update Semester</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Semester -->
    <div class="modal fade" id="deleteTermModal" tabindex="-1" aria-labelledby="deleteTermModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteTermModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus semester ini?</p>
                    <input type="hidden" id="deleteTermId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDeleteTerm()">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Semua Data Semester -->
    <div class="modal fade" id="deleteAllTermsModal" tabindex="-1" aria-labelledby="deleteAllTermsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAllTermsModalLabel">Konfirmasi Hapus Semua Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus <strong>SEMUA DATA SEMESTER</strong>?</p>
                    <p class="text-danger"><strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="deleteAllTerms()">Ya, Hapus Semua</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Semua Data Jadwal Kelas X -->
    <div class="modal fade" id="deleteAllJadwalModal" tabindex="-1" aria-labelledby="deleteAllJadwalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAllJadwalModalLabel">Konfirmasi Hapus Semua Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus <strong>SEMUA DATA JADWAL KELAS X</strong>?</p>
                    <p class="text-danger"><strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="deleteAllJadwal()">Ya, Hapus Semua</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Semua Data Jadwal Kelas XI -->
    <div class="modal fade" id="deleteAllJadwalXiModal" tabindex="-1" aria-labelledby="deleteAllJadwalXiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAllJadwalXiModalLabel">Konfirmasi Hapus Semua Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus <strong>SEMUA DATA JADWAL KELAS XI</strong>?</p>
                    <p class="text-danger"><strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="deleteAllJadwalXi()">Ya, Hapus Semua</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Mata Pelajaran Manual -->
    <div class="modal fade" id="addManualClassSubjectModal" tabindex="-1" aria-labelledby="addManualClassSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addManualClassSubjectModalLabel">Tambah Mata Pelajaran Manual</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addManualClassSubjectForm">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Step 1: Hari dan Jam -->
                        <div class="step-container" id="step1">
                            <h6 class="text-primary mb-3">
                                <i class="bx bx-calendar me-2"></i>Langkah 1: Pilih Semester dan Jadwal
                            </h6>
                            <div class="mb-3">
                                <label for="manual_term_id" class="form-label">Pilih Semester <span class="text-danger">*</span></label>
                                <select class="form-select" id="manual_term_id" name="term_id" required>
                                    <option value="">Memuat semester yang tersedia...</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="manual_day" class="form-label">Pilih Hari <span class="text-danger">*</span></label>
                                <select class="form-select" id="manual_day" name="day_of_week" required>
                                    <option value="">Pilih Hari</option>
                                    <option value="1">Senin</option>
                                    <option value="2">Selasa</option>
                                    <option value="3">Rabu</option>
                                    <option value="4">Kamis</option>
                                    <option value="5">Jumat</option>
                                    <option value="6">Sabtu</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="manual_start_time" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" id="manual_start_time" name="start_time" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="manual_end_time" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" id="manual_end_time" name="end_time" required>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" id="nextToStep2" disabled>
                                    Lanjut <i class="bx bx-right-arrow-alt ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Kelas -->
                        <div class="step-container" id="step2" style="display: none;">
                            <h6 class="text-primary mb-3">
                                <i class="bx bx-building me-2"></i>Langkah 2: Pilih Kelas
                            </h6>
                            <div class="mb-3">
                                <label for="manual_class_id" class="form-label">Pilih Kelas <span class="text-danger">*</span></label>
                                <select class="form-select" id="manual_class_id" name="class_id" required>
                                    <option value="">Memuat kelas yang tersedia...</option>
                                </select>
                            </div>
                            <div class="mb-3" id="manual_class_type_container" style="display: none;">
                                <label for="manual_class_type" class="form-label">Jenis Kelas <span class="text-danger">*</span></label>
                                <select class="form-select" id="manual_class_type" name="class_type">
                                    <option value="">Pilih Jenis Kelas</option>
                                    <option value="teori">Teori</option>
                                    <option value="praktik">Praktik</option>
                                </select>
                            </div>
                            <div class="mb-3" id="manual_week_type_container" style="display: none;">
                                <label for="manual_week_type" class="form-label">Tipe Minggu <span class="text-danger">*</span></label>
                                <select class="form-select" id="manual_week_type" name="week_type">
                                    <option value="">Pilih Tipe Minggu</option>
                                    <option value="ganjil">Ganjil</option>
                                    <option value="genap">Genap</option>
                                </select>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" id="backToStep1">
                                    <i class="bx bx-left-arrow-alt me-1"></i> Kembali
                                </button>
                                <button type="button" class="btn btn-primary" id="nextToStep3" disabled>
                                    Lanjut <i class="bx bx-right-arrow-alt ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Mata Pelajaran -->
                        <div class="step-container" id="step3" style="display: none;">
                            <h6 class="text-primary mb-3">
                                <i class="bx bx-book me-2"></i>Langkah 3: Pilih Mata Pelajaran
                            </h6>
                            <div class="mb-3">
                                <label for="manual_subject_id" class="form-label">Pilih Mata Pelajaran <span class="text-danger">*</span></label>
                                <select class="form-select" id="manual_subject_id" name="subject_id" required>
                                    <option value="">Memuat mata pelajaran yang tersedia...</option>
                                </select>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" id="backToStep2">
                                    <i class="bx bx-left-arrow-alt me-1"></i> Kembali
                                </button>
                                <button type="button" class="btn btn-primary" id="nextToStep4" disabled>
                                    Lanjut <i class="bx bx-right-arrow-alt ms-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 4: Guru -->
                        <div class="step-container" id="step4" style="display: none;">
                            <h6 class="text-primary mb-3">
                                <i class="bx bx-user me-2"></i>Langkah 4: Pilih Guru
                            </h6>
                            <div class="mb-3">
                                <label for="manual_teacher_id" class="form-label">Pilih Guru <span class="text-danger">*</span></label>
                                <select class="form-select" id="manual_teacher_id" name="teacher_id" required>
                                    <option value="">Memuat guru yang tersedia...</option>
                                </select>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" id="backToStep3">
                                    <i class="bx bx-left-arrow-alt me-1"></i> Kembali
                                </button>
                                <button type="button" class="btn btn-success" id="submitForm" disabled>
                                    <i class="bx bx-check me-1"></i> Simpan Jadwal
                                </button>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="bx bx-info-circle me-2"></i>
                            <strong>Informasi:</strong> Sistem akan memastikan tidak ada konflik jadwal dengan kelas lain pada hari dan jam yang sama.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Notifikasi Sederhana -->
    <div id="notificationModal" class="modal fade" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">Notifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0" id="notificationMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/admin/tabel-jadwal.js']); ?>
    <script>
        // Load terms data for all modals
        function loadTermsData() {
            console.log('Loading terms data...');
            console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
            
            fetch('/admin/terms/data', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
                .then(response => {
                    console.log('Terms response status:', response.status);
                    console.log('Terms response headers:', response.headers);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Terms data received:', data);
                    if (data && data.length > 0) {
                        // Use all terms (not just active ones) for Kelas X filter
                        console.log('All terms:', data);
                        
                            // Load for Kelas X semester filter (show all terms)
                            const kelasXSemesterFilter = document.getElementById('kelasXSemesterFilter');
                            if (kelasXSemesterFilter) {
                                console.log('Loading Kelas X semester filter with', data.length, 'terms');
                                kelasXSemesterFilter.innerHTML = '<option value="">Pilih Semester</option>';
                                data.forEach(term => {
                                    const option = document.createElement('option');
                                    option.value = term.id;
                                    option.textContent = term.name + (term.is_active ? ' (Aktif)' : '');
                                    kelasXSemesterFilter.appendChild(option);
                                    console.log('Added term:', term.name, 'Active:', term.is_active);
                                });
                                
                                // Set default to first active term, or first term if no active
                                const activeTerm = data.find(term => term.is_active === true);
                                if (activeTerm) {
                                    kelasXSemesterFilter.value = activeTerm.id;
                                    console.log('Set default to active term:', activeTerm.name);
                                    // Initialize table with active term
                                    reloadKelasXTable(activeTerm.id);
                                } else if (data.length > 0) {
                                    kelasXSemesterFilter.value = data[0].id;
                                    console.log('Set default to first term:', data[0].name);
                                    // Initialize table with first term
                                    reloadKelasXTable(data[0].id);
                                }
                                
                                // Don't trigger change event here since we're calling reloadKelasXTable directly
                                console.log('Kelas X semester filter loaded successfully');
                            } else {
                                console.error('kelasXSemesterFilter element not found');
                            }

                            // Load for Kelas XI semester filter (show all terms)
                            const kelasXISemesterFilter = document.getElementById('kelasXISemesterFilter');
                            if (kelasXISemesterFilter) {
                                console.log('Loading Kelas XI semester filter with', data.length, 'terms');
                                kelasXISemesterFilter.innerHTML = '<option value="">Pilih Semester</option>';
                                data.forEach(term => {
                                    const option = document.createElement('option');
                                    option.value = term.id;
                                    option.textContent = term.name + (term.is_active ? ' (Aktif)' : '');
                                    kelasXISemesterFilter.appendChild(option);
                                    console.log('Added term to XI:', term.name, 'Active:', term.is_active);
                                });
                                
                                // Set default to first active term, or first term if no active
                                const activeTermXI = data.find(term => term.is_active === true);
                                if (activeTermXI) {
                                    kelasXISemesterFilter.value = activeTermXI.id;
                                    console.log('Set default XI to active term:', activeTermXI.name);
                                    // Initialize table with active term
                                    reloadKelasXITable(activeTermXI.id);
                                } else if (data.length > 0) {
                                    kelasXISemesterFilter.value = data[0].id;
                                    console.log('Set default XI to first term:', data[0].name);
                                    // Initialize table with first term
                                    reloadKelasXITable(data[0].id);
                                }
                                
                                console.log('Kelas XI semester filter loaded successfully');
                            } else {
                                console.error('kelasXISemesterFilter element not found');
                            }
                        
                        // Filter only active terms for other modals
                        const activeTerms = data.filter(term => term.is_active === true);
                        console.log('Active terms for modals:', activeTerms);
                        
                        if (activeTerms.length > 0) {
                            // Load for import XI modal
                            const xiTermSelect = document.getElementById('xiTerm');
                            if (xiTermSelect) {
                                xiTermSelect.innerHTML = '<option value="">Pilih Semester</option>';
                                activeTerms.forEach(term => {
                                    const option = document.createElement('option');
                                    option.value = term.id;
                                    option.textContent = term.name;
                                    xiTermSelect.appendChild(option);
                                });
                            }

                            // Load for import XI modal (alternative)
                            const termXISelect = document.getElementById('termXI');
                            if (termXISelect) {
                                termXISelect.innerHTML = '<option value="">Pilih Semester</option>';
                                activeTerms.forEach(term => {
                                    const option = document.createElement('option');
                                    option.value = term.id;
                                    option.textContent = term.name;
                                    termXISelect.appendChild(option);
                                });
                            }

                            // Load for manual subject modal
                            const manualTermSelect = document.getElementById('manual_term_id');
                            if (manualTermSelect) {
                                manualTermSelect.innerHTML = '<option value="">Pilih Semester</option>';
                                activeTerms.forEach(term => {
                                    const option = document.createElement('option');
                                    option.value = term.id;
                                    option.textContent = term.name;
                                    manualTermSelect.appendChild(option);
                                });
                            }

                            // Load for jadwal import modal (Kelas X)
                            const jadwalTermSelect = document.getElementById('jadwalTerm');
                            if (jadwalTermSelect) {
                                jadwalTermSelect.innerHTML = '<option value="">Pilih Semester</option>';
                                activeTerms.forEach(term => {
                                    const option = document.createElement('option');
                                    option.value = term.id;
                                    option.textContent = term.name;
                                    jadwalTermSelect.appendChild(option);
                                });
                            }
                        } else {
                            console.log('No active terms available');
                            // Show no active terms message in dropdowns (except Kelas X filter)
                            const dropdowns = ['xiTerm', 'termXI', 'manual_term_id', 'jadwalTerm'];
                            dropdowns.forEach(id => {
                                const select = document.getElementById(id);
                                if (select) {
                                    select.innerHTML = '<option value="">Tidak ada semester aktif</option>';
                                }
                            });
                            
                            // For Kelas X filter, show all terms even if no active
                            const kelasXSemesterFilter = document.getElementById('kelasXSemesterFilter');
                            if (kelasXSemesterFilter && data.length > 0) {
                                kelasXSemesterFilter.innerHTML = '<option value="">Pilih Semester</option>';
                                data.forEach(term => {
                                    const option = document.createElement('option');
                                    option.value = term.id;
                                    option.textContent = term.name + (term.is_active ? ' (Aktif)' : '');
                                    kelasXSemesterFilter.appendChild(option);
                                });
                                
                                // Set default to first term
                                kelasXSemesterFilter.value = data[0].id;
                                // Initialize table with first term
                                reloadKelasXTable(data[0].id);
                            }

                            // For Kelas XI filter, show all terms even if no active
                            const kelasXISemesterFilter = document.getElementById('kelasXISemesterFilter');
                            if (kelasXISemesterFilter && data.length > 0) {
                                kelasXISemesterFilter.innerHTML = '<option value="">Pilih Semester</option>';
                                data.forEach(term => {
                                    const option = document.createElement('option');
                                    option.value = term.id;
                                    option.textContent = term.name + (term.is_active ? ' (Aktif)' : '');
                                    kelasXISemesterFilter.appendChild(option);
                                });
                                
                                // Set default to first term
                                kelasXISemesterFilter.value = data[0].id;
                                // Initialize table with first term
                                reloadKelasXITable(data[0].id);
                            }
                        }
                    } else {
                        console.log('No terms data available');
                        // Show no data message in dropdowns
                        const dropdowns = ['xiTerm', 'termXI', 'manual_term_id', 'jadwalTerm', 'kelasXSemesterFilter', 'kelasXISemesterFilter'];
                        dropdowns.forEach(id => {
                            const select = document.getElementById(id);
                            if (select) {
                                select.innerHTML = '<option value="">Tidak ada semester tersedia</option>';
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading terms:', error);
                    console.error('Error details:', {
                        message: error.message,
                        stack: error.stack,
                        name: error.name
                    });
                    
                    // Show error message in dropdowns
                    const dropdowns = ['xiTerm', 'termXI', 'manual_term_id', 'jadwalTerm', 'kelasXSemesterFilter', 'kelasXISemesterFilter'];
                    dropdowns.forEach(id => {
                        const select = document.getElementById(id);
                        if (select) {
                            select.innerHTML = '<option value="">Error loading semester data</option>';
                        }
                    });
                });
        }

        // Function to reload Kelas X table with selected semester
        function reloadKelasXTable(termId) {
            console.log('=== RELOAD KELAS X TABLE ===');
            console.log('Term ID:', termId);
            console.log('GridJS instance exists:', !!window.gridInstanceJadwal);
            
            // Check if GridJS instance exists
            if (window.gridInstanceJadwal) {
                console.log('Current URL:', window.gridInstanceJadwal.config.server.url);
                
                // Update the data source URL with term parameter
                const baseUrl = "/admin/jadwal";
                const urlWithTerm = termId ? `${baseUrl}?term_id=${termId}` : baseUrl;
                
                console.log('New URL:', urlWithTerm);
                
                // Update the server URL and reload
                window.gridInstanceJadwal.config.server.url = urlWithTerm;
                console.log('Updated URL in config:', window.gridInstanceJadwal.config.server.url);
                
                console.log('Calling forceRender...');
                window.gridInstanceJadwal.forceRender();
                console.log('forceRender called');
            } else {
                console.log('GridJS instance not found, trying to initialize...');
                // If no GridJS instance, try to initialize the table
                if (window.tabelJadwalInstance && window.tabelJadwalInstance.initJadwalTable) {
                    console.log('Initializing new table with term:', termId);
                    // Pass term_id to the initialization
                    window.tabelJadwalInstance.initJadwalTable(termId);
                } else {
                    console.error('GridJS instance not found for Kelas X table');
                }
            }
            console.log('=== END RELOAD KELAS X TABLE ===');
        }

        // Function to reload Kelas XI table with selected semester
        function reloadKelasXITable(termId) {
            console.log('=== RELOAD KELAS XI TABLE ===');
            console.log('Term ID:', termId);
            console.log('GridJS instance exists:', !!window.gridInstanceJadwalXI);
            
            // Check if GridJS instance exists
            if (window.gridInstanceJadwalXI) {
                console.log('Current URL:', window.gridInstanceJadwalXI.config.server.url);
                
                // Update the data source URL with term parameter
                const baseUrl = "/admin/jadwal-xi";
                const urlWithTerm = termId ? `${baseUrl}?term_id=${termId}` : baseUrl;
                
                console.log('New URL:', urlWithTerm);
                
                // Update the server URL and reload
                window.gridInstanceJadwalXI.config.server.url = urlWithTerm;
                console.log('Updated URL in config:', window.gridInstanceJadwalXI.config.server.url);
                
                console.log('Calling forceRender...');
                window.gridInstanceJadwalXI.forceRender();
                console.log('forceRender called');
            } else {
                console.log('GridJS instance not found, trying to initialize...');
                // If no GridJS instance, try to initialize the table
                if (window.tabelJadwalInstance && window.tabelJadwalInstance.initJadwalXiTable) {
                    console.log('Initializing new XI table with term:', termId);
                    // Pass term_id to the initialization
                    window.tabelJadwalInstance.initJadwalXiTable(termId);
                } else {
                    console.error('GridJS instance not found for Kelas XI table');
                }
            }
            console.log('=== END RELOAD KELAS XI TABLE ===');
        }

        // Initialize notification modal
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Starting initialization');
            
            // Wait a bit to ensure all elements are ready
            setTimeout(function() {
                console.log('Loading terms data after timeout');
                loadTermsData();
                
                // Add event listener for Kelas X semester filter
                const kelasXSemesterFilter = document.getElementById('kelasXSemesterFilter');
                console.log('kelasXSemesterFilter element:', kelasXSemesterFilter);
                
                if (kelasXSemesterFilter) {
                    console.log('Adding event listener to kelasXSemesterFilter');
                    kelasXSemesterFilter.addEventListener('change', function() {
                        const selectedTermId = this.value;
                        console.log('Kelas X semester changed to:', selectedTermId);
                        
                        if (selectedTermId) {
                            // Reload Kelas X table with selected semester
                            reloadKelasXTable(selectedTermId);
                        } else {
                            // Clear table if no semester selected
                            const tableContainer = document.getElementById('table-search');
                            if (tableContainer) {
                                tableContainer.innerHTML = '<div class="text-center text-muted">Pilih semester untuk melihat jadwal</div>';
                            }
                        }
                    });
                } else {
                    console.error('kelasXSemesterFilter element not found during event listener setup');
                }

                // Add event listener for Kelas XI semester filter
                const kelasXISemesterFilter = document.getElementById('kelasXISemesterFilter');
                console.log('kelasXISemesterFilter element:', kelasXISemesterFilter);
                
                if (kelasXISemesterFilter) {
                    console.log('Adding event listener to kelasXISemesterFilter');
                    kelasXISemesterFilter.addEventListener('change', function() {
                        const selectedTermId = this.value;
                        console.log('Kelas XI semester changed to:', selectedTermId);
                        
                        if (selectedTermId) {
                            // Reload Kelas XI table with selected semester
                            reloadKelasXITable(selectedTermId);
                        } else {
                            // Clear table if no semester selected
                            const tableContainer = document.getElementById('table-search-xi');
                            if (tableContainer) {
                                tableContainer.innerHTML = '<div class="text-center text-muted">Pilih semester untuk melihat jadwal</div>';
                            }
                        }
                    });
                } else {
                    console.error('kelasXISemesterFilter element not found during event listener setup');
                }
            }, 100);
            
            const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));

            // Ensure close buttons work
            document.querySelector('#notificationModal .btn-close').addEventListener('click', () => {
                notificationModal.hide();
            });
            document.querySelector('#notificationModal .btn-light').addEventListener('click', () => {
                notificationModal.hide();
            });

            // Function to show notification (same as manage-user)
            function showNotification(message, isSuccess = true) {
                document.getElementById('notificationModalLabel').innerText = isSuccess ? 'Berhasil' : 'Gagal';
                document.getElementById('notificationMessage').innerText = message;
                notificationModal.show();
            }

            // Make showNotification available globally
            window.showNotification = showNotification;
        });

        // Persist active tab across reloads (hash + localStorage fallback)
        (function() {
            const STORAGE_KEY = 'admin-jadwal-active-tab';
            function activateTabBySelector(selector) {
                const link = document.querySelector(`a[data-bs-toggle="tab"][href="${selector}"]`);
                if (link) { new bootstrap.Tab(link).show(); return true; }
                return false;
            }

            window.addEventListener('load', function() {
                // Prefer URL hash if present
                if (location.hash && activateTabBySelector(location.hash)) {
                    // synced
                } else {
                    const saved = localStorage.getItem(STORAGE_KEY);
                    if (saved) activateTabBySelector(saved);
                }
            });

            document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(link => {
                link.addEventListener('shown.bs.tab', function (e) {
                    const target = e.target.getAttribute('href');
                    if (!target) return;
                    // Update hash and storage
                    history.replaceState(null, '', target);
                    localStorage.setItem(STORAGE_KEY, target);
                    
                    // Initialize table when Kelas XI tab is shown
                    if (target === '#kelasxi' && window.tabelJadwalInstance) {
                        // Check if table is already initialized
                        if (!window.gridInstanceJadwalXI) {
                            console.log('Kelas XI tab shown, initializing table...');
                            if (window.tabelJadwalInstance.initJadwalXiTable) {
                                window.tabelJadwalInstance.initJadwalXiTable();
                            }
                        }
                    }
                    
                    // Initialize terms table when Info Akademik tab is shown
                    if (target === '#mapel') {
                        if (window.tabelJadwalInstance && window.tabelJadwalInstance.initTermsTable) {
                            window.tabelJadwalInstance.initTermsTable();
                        }
                    }
                });
            });
        })();

        document.getElementById('grade').addEventListener('change', function() {
            const grade = this.value;
            const weekTypeContainer = document.getElementById('weekTypeContainer');
            const weekTypeSelect = document.getElementById('week_type');

            if (grade === 'XI' || grade === 'XII') {
                weekTypeContainer.style.display = 'block';
                weekTypeSelect.required = true;
            } else {
                weekTypeContainer.style.display = 'none';
                weekTypeSelect.required = false;
                weekTypeSelect.value = '';
            }
        });

        // Manual Class Subject Functions - Step by Step
        let currentStep = 1;
        let formData = {
            day_of_week: null,
            start_time: null,
            end_time: null,
            class_id: null,
            class_type: null,
            week_type: null,
            subject_id: null,
            teacher_id: null
        };

        // Initialize step navigation
        document.addEventListener('DOMContentLoaded', function() {
            initializeStepNavigation();
        });

        function initializeStepNavigation() {
            // Step 1: Semester, Day and Time validation
            const manualTermSelect = document.getElementById('manual_term_id');
            const daySelect = document.getElementById('manual_day');
            const startTimeInput = document.getElementById('manual_start_time');
            const endTimeInput = document.getElementById('manual_end_time');
            const nextToStep2Btn = document.getElementById('nextToStep2');

            function validateStep1() {
                const isValid = manualTermSelect.value && daySelect.value && startTimeInput.value && endTimeInput.value && 
                               startTimeInput.value < endTimeInput.value;
                nextToStep2Btn.disabled = !isValid;
            }

            manualTermSelect.addEventListener('change', function() {
                formData.term_id = this.value;
                validateStep1();
            });

            daySelect.addEventListener('change', function() {
                formData.day_of_week = this.value;
                validateStep1();
            });

            startTimeInput.addEventListener('change', function() {
                formData.start_time = this.value;
                validateStep1();
            });

            endTimeInput.addEventListener('change', function() {
                formData.end_time = this.value;
                validateStep1();
            });

            // Step navigation buttons
            document.getElementById('nextToStep2').addEventListener('click', function() {
                loadAvailableClasses();
                showStep(2);
            });

            document.getElementById('backToStep1').addEventListener('click', function() {
                showStep(1);
            });

            document.getElementById('nextToStep3').addEventListener('click', function() {
                loadAvailableSubjects();
                showStep(3);
            });

            document.getElementById('backToStep2').addEventListener('click', function() {
                showStep(2);
            });

            document.getElementById('nextToStep4').addEventListener('click', function() {
                loadAvailableTeachers();
                showStep(4);
            });

            document.getElementById('backToStep3').addEventListener('click', function() {
                showStep(3);
            });

            document.getElementById('submitForm').addEventListener('click', function() {
                submitManualClassSubject();
            });
        }

        function showStep(stepNumber) {
            // Hide all steps
            document.querySelectorAll('.step-container').forEach(step => {
                step.style.display = 'none';
            });
            
            // Show current step
            document.getElementById(`step${stepNumber}`).style.display = 'block';
            currentStep = stepNumber;
        }

        function loadAvailableClasses() {
            const classSelect = document.getElementById('manual_class_id');
            classSelect.innerHTML = '<option value="">Memuat kelas yang tersedia...</option>';

            fetch(`<?php echo e(route("manual.form-data")); ?>?day=${formData.day_of_week}&start_time=${formData.start_time}&end_time=${formData.end_time}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        classSelect.innerHTML = '<option value="">Pilih Kelas</option>';
                        data.data.classes.forEach(classItem => {
                            const option = document.createElement('option');
                            option.value = classItem.id;
                            option.textContent = classItem.display_name;
                            option.setAttribute('data-grade', classItem.grade);
                            classSelect.appendChild(option);
                        });

                        // Show conflict information if available
                        if (data.data.filter_info) {
                            const filterInfo = data.data.filter_info;
                            const conflictingClasses = data.data.conflicting_classes || [];
                            
                            if (conflictingClasses.length > 0) {
                                showConflictInfo(filterInfo, conflictingClasses);
                            }
                        }

                        // Add event listener for class change
                        classSelect.addEventListener('change', function() {
                            const selectedOption = this.options[this.selectedIndex];
                            const grade = selectedOption.getAttribute('data-grade');
                            
                            formData.class_id = this.value;
                            formData.class_type = null;
                            formData.week_type = null;
                            
                            const classTypeContainer = document.getElementById('manual_class_type_container');
                            const weekTypeContainer = document.getElementById('manual_week_type_container');
                            const classTypeSelect = document.getElementById('manual_class_type');
                            const weekTypeSelect = document.getElementById('manual_week_type');
                            const nextToStep3Btn = document.getElementById('nextToStep3');
                            
                            if (grade === '11') {
                                classTypeContainer.style.display = 'block';
                                weekTypeContainer.style.display = 'block';
                                classTypeSelect.required = true;
                                weekTypeSelect.required = true;
                                
                                // Add event listeners for class type and week type
                                classTypeSelect.addEventListener('change', function() {
                                    formData.class_type = this.value;
                                    validateStep2();
                                });
                                
                                weekTypeSelect.addEventListener('change', function() {
                                    formData.week_type = this.value;
                                    validateStep2();
                                });
                            } else {
                                classTypeContainer.style.display = 'none';
                                weekTypeContainer.style.display = 'none';
                                classTypeSelect.required = false;
                                weekTypeSelect.required = false;
                                classTypeSelect.value = '';
                                weekTypeSelect.value = '';
                                nextToStep3Btn.disabled = false;
                            }
                        });
                    } else {
                        showAlert('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error loading classes:', error);
                    showAlert('error', 'Gagal memuat kelas yang tersedia');
                });
        }

        function showConflictInfo(filterInfo, conflictingClasses) {
            const days = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const dayName = days[filterInfo.day];
            
            let conflictMessage = `
                <div class="alert alert-warning mt-3">
                    <i class="bx bx-info-circle me-2"></i>
                    <strong>Informasi Konflik Jadwal:</strong><br>
                    Pada <strong>${dayName} ${filterInfo.start_time}-${filterInfo.end_time}</strong>, 
                    terdapat <strong>${filterInfo.conflicting_classes_count}</strong> kelas yang sudah memiliki jadwal 
                    dari total <strong>${filterInfo.total_classes}</strong> kelas.
                    <br><br>
                    <strong>Kelas yang tidak tersedia:</strong><br>
            `;
            
            conflictingClasses.forEach(conflictClass => {
                conflictMessage += `• ${conflictClass.display_name}<br>`;
            });
            
            conflictMessage += `
                    <br>
                    <small class="text-muted">
                        <i class="bx bx-lightbulb me-1"></i>
                        <strong>Tips:</strong> Coba pilih waktu yang berbeda atau kelas lain yang tersedia.
                    </small>
                </div>
            `;
            
            // Remove existing conflict info
            const existingConflictInfo = document.querySelector('.alert-warning');
            if (existingConflictInfo) {
                existingConflictInfo.remove();
            }
            
            // Add new conflict info after the class select
            const classSelectContainer = document.getElementById('manual_class_id').parentElement;
            classSelectContainer.insertAdjacentHTML('afterend', conflictMessage);
        }

        function validateStep2() {
            const classSelect = document.getElementById('manual_class_id');
            const classTypeSelect = document.getElementById('manual_class_type');
            const weekTypeSelect = document.getElementById('manual_week_type');
            const nextToStep3Btn = document.getElementById('nextToStep3');
            
            const selectedClass = classSelect.options[classSelect.selectedIndex];
            const grade = selectedClass.getAttribute('data-grade');
            
            let isValid = classSelect.value;
            
            if (grade === '11') {
                isValid = isValid && classTypeSelect.value && weekTypeSelect.value;
            }
            
            nextToStep3Btn.disabled = !isValid;
        }

        function loadAvailableSubjects() {
            const subjectSelect = document.getElementById('manual_subject_id');
            subjectSelect.innerHTML = '<option value="">Memuat mata pelajaran yang tersedia...</option>';

            const params = new URLSearchParams({
                day: formData.day_of_week,
                start_time: formData.start_time,
                end_time: formData.end_time,
                class_id: formData.class_id
            });

            fetch(`<?php echo e(route("manual.form-data")); ?>?${params}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        subjectSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
                        data.data.subjects.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.id;
                            option.textContent = subject.name;
                            subjectSelect.appendChild(option);
                        });

                        subjectSelect.addEventListener('change', function() {
                            formData.subject_id = this.value;
                            validateStep3();
                        });
                        
                        function validateStep3() {
                            const nextToStep4Btn = document.getElementById('nextToStep4');
                            nextToStep4Btn.disabled = !subjectSelect.value;
                        }
                    } else {
                        showAlert('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error loading subjects:', error);
                    showAlert('error', 'Gagal memuat mata pelajaran yang tersedia');
                });
        }

        function loadAvailableTeachers() {
            const teacherSelect = document.getElementById('manual_teacher_id');
            teacherSelect.innerHTML = '<option value="">Memuat guru yang tersedia...</option>';

            const params = new URLSearchParams({
                day: formData.day_of_week,
                start_time: formData.start_time,
                end_time: formData.end_time,
                class_id: formData.class_id,
                subject_id: formData.subject_id
            });

            fetch(`<?php echo e(route("manual.form-data")); ?>?${params}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        teacherSelect.innerHTML = '<option value="">Pilih Guru</option>';
                        data.data.teachers.forEach(teacher => {
                            const option = document.createElement('option');
                            option.value = teacher.id;
                            option.textContent = `${teacher.name} (${teacher.nip || teacher.kode_guru})`;
                            teacherSelect.appendChild(option);
                        });

                        teacherSelect.addEventListener('change', function() {
                            formData.teacher_id = this.value;
                            const submitBtn = document.getElementById('submitForm');
                            submitBtn.disabled = !this.value;
                        });
                    } else {
                        showAlert('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error loading teachers:', error);
                    showAlert('error', 'Gagal memuat guru yang tersedia');
                });
        }


        function submitManualClassSubject() {
            // Create FormData object with all collected data
            const submitData = new FormData();
            submitData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            submitData.append('day_of_week', formData.day_of_week);
            submitData.append('start_time', formData.start_time);
            submitData.append('end_time', formData.end_time);
            submitData.append('class_id', formData.class_id);
            submitData.append('subject_id', formData.subject_id);
            submitData.append('teacher_id', formData.teacher_id);
            submitData.append('term_id', document.getElementById('manual_term_id').value);
            
            if (formData.class_type) {
                submitData.append('class_type', formData.class_type);
            }
            if (formData.week_type) {
                submitData.append('week_type', formData.week_type);
            }

            fetch('<?php echo e(route("manual.class-subject.store")); ?>', {
                method: 'POST',
                body: submitData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showManualNotification('success', data.message, data.data);
                    resetForm();
                    bootstrap.Modal.getInstance(document.getElementById('addManualClassSubjectModal')).hide();
                    
                    // Refresh tabel jadwal berdasarkan kelas yang ditambahkan
                    refreshJadwalTable(data.data.class);
                } else {
                    showManualNotification('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error submitting form:', error);
                showManualNotification('error', 'Gagal menambahkan jadwal mata pelajaran');
            });
        }

        function resetForm() {
            // Reset form data
            formData = {
                term_id: null,
                day_of_week: null,
                start_time: null,
                end_time: null,
                class_id: null,
                class_type: null,
                week_type: null,
                subject_id: null,
                teacher_id: null
            };

            // Reset form elements
            document.getElementById('manual_term_id').value = '';
            document.getElementById('manual_day').value = '';
            document.getElementById('manual_start_time').value = '';
            document.getElementById('manual_end_time').value = '';
            document.getElementById('manual_class_id').innerHTML = '<option value="">Memuat kelas yang tersedia...</option>';
            document.getElementById('manual_subject_id').innerHTML = '<option value="">Memuat mata pelajaran yang tersedia...</option>';
            document.getElementById('manual_teacher_id').innerHTML = '<option value="">Memuat guru yang tersedia...</option>';
            
            // Hide additional fields
            document.getElementById('manual_class_type_container').style.display = 'none';
            document.getElementById('manual_week_type_container').style.display = 'none';
            
            // Reset buttons
            document.getElementById('nextToStep2').disabled = true;
            document.getElementById('nextToStep3').disabled = true;
            document.getElementById('nextToStep4').disabled = true;
            document.getElementById('submitForm').disabled = true;
            
            // Show step 1
            showStep(1);
        }


        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alertHTML = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            // Remove existing alerts
            document.querySelectorAll('.alert').forEach(alert => alert.remove());
            
            // Add new alert
            const container = document.querySelector('.container-fluid');
            container.insertAdjacentHTML('afterbegin', alertHTML);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }

        function showManualNotification(type, message, data = null) {
            const modal = document.getElementById('manualNotificationModal');
            const header = document.getElementById('manualNotificationHeader');
            const icon = document.getElementById('manualNotificationIcon');
            const iconClass = document.getElementById('manualNotificationIconClass');
            const title = document.getElementById('manualNotificationTitle');
            const subtitle = document.getElementById('manualNotificationSubtitle');
            const alert = document.getElementById('manualNotificationAlert');
            const alertIcon = document.getElementById('manualNotificationAlertIcon');
            const alertMessage = document.getElementById('manualNotificationMessage');
            const details = document.getElementById('manualNotificationDetails');
            const button = document.getElementById('manualNotificationButton');

            if (type === 'success') {
                // Success styling
                header.className = 'modal-header border-0 pb-0 bg-success bg-opacity-10';
                icon.className = 'avatar-sm bg-success bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center';
                iconClass.className = 'bx bx-check-circle fs-24 text-success';
                title.textContent = 'Berhasil!';
                subtitle.textContent = 'Jadwal mata pelajaran berhasil ditambahkan';
                alert.className = 'alert alert-success border-0 mb-0';
                alertIcon.className = 'bx bx-check-circle fs-20 text-success';
                button.className = 'btn btn-success w-100';
                button.innerHTML = '<i class="bx bx-check me-1"></i> Baik, Mengerti';

                // Show details if data is provided
                if (data) {
                    document.getElementById('detailClass').textContent = data.class || '-';
                    document.getElementById('detailSubject').textContent = data.subject || '-';
                    document.getElementById('detailTeacher').textContent = data.teacher || '-';
                    document.getElementById('detailDay').textContent = data.day || '-';
                    document.getElementById('detailTime').textContent = data.time || '-';
                    document.getElementById('detailType').textContent = data.type || '-';
                    details.style.display = 'block';
                } else {
                    details.style.display = 'none';
                }
            } else {
                // Error styling
                header.className = 'modal-header border-0 pb-0 bg-danger bg-opacity-10';
                icon.className = 'avatar-sm bg-danger bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center';
                iconClass.className = 'bx bx-x-circle fs-24 text-danger';
                title.textContent = 'Gagal!';
                subtitle.textContent = 'Terjadi kesalahan saat menambahkan jadwal';
                alert.className = 'alert alert-danger border-0 mb-0';
                alertIcon.className = 'bx bx-error-circle fs-20 text-danger';
                button.className = 'btn btn-danger w-100';
                button.innerHTML = '<i class="bx bx-x me-1"></i> Tutup';
                details.style.display = 'none';
            }

            alertMessage.textContent = message;

            // Remove any existing event listeners to prevent conflicts
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);

            // Add event listener to close modal properly
            newButton.addEventListener('click', function() {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                } else {
                    // Fallback: hide modal manually
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                }
            });

            // Also handle close button (X) in header
            const closeButton = modal.querySelector('.btn-close');
            if (closeButton) {
                const newCloseButton = closeButton.cloneNode(true);
                closeButton.parentNode.replaceChild(newCloseButton, closeButton);
                
                newCloseButton.addEventListener('click', function() {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) {
                        modalInstance.hide();
                    } else {
                        // Fallback: hide modal manually
                        modal.classList.remove('show');
                        modal.style.display = 'none';
                        document.body.classList.remove('modal-open');
                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) {
                            backdrop.remove();
                        }
                    }
                });
            }

            // Show modal
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();

            // Add event listener for modal hidden event to clean up
            modal.addEventListener('hidden.bs.modal', function() {
                // Clean up any remaining backdrop
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
                // Ensure body is not locked
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            });

            // Handle ESC key to close modal
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && modal.classList.contains('show')) {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
            });
        }

        function refreshJadwalTable(className) {
            // Determine which table to refresh based on class name
            const isClassX = className.includes('-X') && !className.includes('-XI');
            const isClassXI = className.includes('-XI');
            
            if (isClassX) {
                // Refresh Kelas X table
                if (window.gridInstanceJadwal) {
                    window.gridInstanceJadwal.forceRender();
                } else {
                    location.reload();
                }
            } else if (isClassXI) {
                // Refresh Kelas XI table - same logic as Kelas X
                if (window.gridInstanceJadwalXI) {
                    window.gridInstanceJadwalXI.forceRender();
                } else if (window.gridXiReload) {
                    window.gridXiReload();
                } else {
                    location.reload();
                }
            } else {
                // Fallback: reload page if class type cannot be determined
                location.reload();
            }
        }

        // Function to load jadwal data for editing
        function loadJadwalForEdit(id) {
            // Fetch data jadwal
            fetch(`/admin/jadwal/${id}`, {
                headers: { Accept: 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Populate form dengan data yang ada
                    document.getElementById('editJadwalId').value = data.jadwal.id;
                    document.getElementById('editJadwalHari').value = data.jadwal.day_of_week;
                    document.getElementById('editJadwalJamMulai').value = data.jadwal.start_time;
                    document.getElementById('editJadwalJamSelesai').value = data.jadwal.end_time;
                    
                    if (data.jadwal.week_type) {
                        document.getElementById('editJadwalWeekType').value = data.jadwal.week_type;
                    }

                    // Load dropdown data
                    loadEditJadwalDropdowns(data.jadwal);
                    
                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('editJadwalModal'));
                    modal.show();
                } else {
                    showAlert('error', 'Gagal memuat data jadwal');
                }
            })
            .catch(error => {
                console.error('Error fetching jadwal:', error);
                showAlert('error', 'Gagal memuat data jadwal');
            });
        }

        // Load teachers by subject for edit modal
        function loadEditTeachersBySubject(subjectId, selectedTeacherId = null) {
            if (!subjectId) {
                const teacherSelect = document.getElementById('editJadwalGuru');
                teacherSelect.innerHTML = '<option value="">Pilih Mata Pelajaran terlebih dahulu</option>';
                return;
            }

            // Show loading state
            const teacherSelect = document.getElementById('editJadwalGuru');
            teacherSelect.innerHTML = '<option value="">Memuat guru...</option>';

            fetch(`/admin/manual-form-data?subject_id=${subjectId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
                .then(response => {
                    console.log('Response status:', response.status);
                    
                    if (!response.ok) {
                        if (response.status === 401) {
                            throw new Error('Unauthorized - Silakan login ulang');
                        } else if (response.status === 403) {
                            throw new Error('Forbidden - Anda tidak memiliki akses');
                        } else {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Teachers data received:', data);
                    teacherSelect.innerHTML = '<option value="">Pilih Guru</option>';
                    
                    if (data.teachers && data.teachers.length > 0) {
                        data.teachers.forEach(teacher => {
                            const option = document.createElement('option');
                            option.value = teacher.user_id;
                            option.textContent = teacher.user.full_name;
                            if (teacher.user_id == selectedTeacherId) {
                                option.selected = true;
                            }
                            teacherSelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'Tidak ada guru yang mengajar mata pelajaran ini';
                        option.disabled = true;
                        teacherSelect.appendChild(option);
                    }
                })
                .catch(error => {
                    console.error('Error loading teachers:', error);
                    
                    // Check if it's an authentication error
                    if (error.message.includes('401') || error.message.includes('Unauthorized')) {
                        teacherSelect.innerHTML = '<option value="">Silakan login ulang</option>';
                    } else if (error.message.includes('403') || error.message.includes('Forbidden')) {
                        teacherSelect.innerHTML = '<option value="">Anda tidak memiliki akses</option>';
                    } else {
                        teacherSelect.innerHTML = '<option value="">Error memuat guru</option>';
                    }
                });
        }

        // Load dropdown data untuk edit jadwal
        function loadEditJadwalDropdowns(jadwalData) {
            // Load classes
            fetch('/admin/classes')
                .then(response => response.json())
                .then(data => {
                    const classSelect = document.getElementById('editJadwalKelas');
                    classSelect.innerHTML = '<option value="">Pilih Kelas</option>';
                    data.forEach(cls => {
                        const option = document.createElement('option');
                        option.value = cls.id;
                        option.textContent = cls.name + '-' + cls.grade;
                        if (cls.id == jadwalData.class_id) {
                            option.selected = true;
                        }
                        classSelect.appendChild(option);
                    });
                    
                    // Show/hide week type based on class grade
                    const selectedClass = data.find(cls => cls.id == jadwalData.class_id);
                    const weekTypeContainer = document.getElementById('editJadwalWeekTypeContainer');
                    if (selectedClass && selectedClass.grade == '11') {
                        weekTypeContainer.style.display = 'block';
                    } else {
                        weekTypeContainer.style.display = 'none';
                    }
                });

            // Load subjects
            fetch('/admin/subjects')
                .then(response => response.json())
                .then(data => {
                    const subjectSelect = document.getElementById('editJadwalMataPelajaran');
                    subjectSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
                    data.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = subject.name;
                        if (subject.id == jadwalData.subject_id) {
                            option.selected = true;
                        }
                        subjectSelect.appendChild(option);
                    });
                });

            // Load teachers based on selected subject
            loadEditTeachersBySubject(jadwalData.subject_id, jadwalData.teacher_id);
        }

        // Function to check for schedule conflicts
        function checkEditJadwalConflict() {
            const form = document.getElementById('editJadwalForm');
            const formData = new FormData(form);
            
            const dayOfWeek = formData.get('day_of_week');
            const startTime = formData.get('start_time');
            const endTime = formData.get('end_time');
            const classId = formData.get('class_id');
            const jadwalId = formData.get('id');
            
            if (!dayOfWeek || !startTime || !endTime || !classId) {
                hideEditJadwalConflictAlert();
                return;
            }
            
            // Check for conflicts
            fetch(`/admin/manual-form-data?day=${dayOfWeek}&start_time=${startTime}&end_time=${endTime}&class_id=${classId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.conflicting_classes && data.conflicting_classes.length > 0) {
                        showEditJadwalConflictAlert(data.conflicting_classes, data.filter_info);
                    } else {
                        hideEditJadwalConflictAlert();
                    }
                })
                .catch(error => {
                    console.error('Error checking conflicts:', error);
                    hideEditJadwalConflictAlert();
                });
        }
        
        // Show conflict alert
        function showEditJadwalConflictAlert(conflictingClasses, filterInfo) {
            const alert = document.getElementById('editJadwalConflictAlert');
            const message = document.getElementById('editJadwalConflictMessage');
            
            let conflictText = `⚠️ Konflik jadwal terdeteksi! `;
            if (conflictingClasses.length > 0) {
                conflictText += `Kelas yang bentrok: ${conflictingClasses.join(', ')}. `;
            }
            if (filterInfo && filterInfo.total_classes > 0) {
                conflictText += `Total ${filterInfo.total_classes} kelas, ${filterInfo.available_classes} tersedia. `;
            }
            conflictText += `Silakan pilih waktu atau kelas yang berbeda.`;
            
            message.textContent = conflictText;
            alert.style.display = 'block';
        }
        
        // Hide conflict alert
        function hideEditJadwalConflictAlert() {
            const alert = document.getElementById('editJadwalConflictAlert');
            alert.style.display = 'none';
        }

        // Event listener untuk edit jadwal
        document.addEventListener('DOMContentLoaded', function() {
            // Event listener untuk mengubah kelas
            const classSelect = document.getElementById('editJadwalKelas');
            if (classSelect) {
                classSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const weekTypeContainer = document.getElementById('editJadwalWeekTypeContainer');
                    
                    if (selectedOption.textContent.includes('-11')) {
                        weekTypeContainer.style.display = 'block';
                    } else {
                        weekTypeContainer.style.display = 'none';
                    }
                });
            }

            // Event listener untuk mengubah mata pelajaran
            const subjectSelect = document.getElementById('editJadwalMataPelajaran');
            if (subjectSelect) {
                subjectSelect.addEventListener('change', function() {
                    const subjectId = this.value;
                    loadEditTeachersBySubject(subjectId);
                });
            }

            // Event listeners untuk mengecek konflik
            const conflictFields = ['editJadwalHari', 'editJadwalJamMulai', 'editJadwalJamSelesai', 'editJadwalKelas'];
            conflictFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('change', function() {
                        // Delay untuk memastikan form sudah ter-update
                        setTimeout(checkEditJadwalConflict, 100);
                    });
                }
            });
            
            const saveEditBtn = document.getElementById('saveEditJadwalBtn');
            if (saveEditBtn) {
                saveEditBtn.addEventListener('click', function() {
                    const form = document.getElementById('editJadwalForm');
                    const formData = new FormData(form);
                    const jadwalId = formData.get('id');

                    // Disable button
                    this.disabled = true;
                    this.textContent = 'Menyimpan...';

                    fetch(`/admin/jadwal/${jadwalId}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            day_of_week: formData.get('day_of_week'),
                            start_time: formData.get('start_time'),
                            end_time: formData.get('end_time'),
                            class_id: formData.get('class_id'),
                            subject_id: formData.get('subject_id'),
                            teacher_id: formData.get('teacher_id'),
                            type: 'teori', // Default value
                            week_type: formData.get('week_type')
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('success', data.message);
                            bootstrap.Modal.getInstance(document.getElementById('editJadwalModal')).hide();
                            
                            // Refresh table
                            if (window.gridInstanceJadwal) {
                                window.gridInstanceJadwal.forceRender();
                            }
                        } else {
                            showAlert('error', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error updating jadwal:', error);
                        showAlert('error', 'Gagal memperbarui jadwal');
                    })
                    .finally(() => {
                        this.disabled = false;
                        this.textContent = 'Simpan Perubahan';
                    });
                });
            }
        });

        // Clean up modal instances when they are hidden
        document.addEventListener('DOMContentLoaded', function() {
            // Clean up edit term modal
            const editTermModal = document.getElementById('editTermModal');
            if (editTermModal) {
                editTermModal.addEventListener('hidden.bs.modal', function(event) {
                    // Only dispose if the event target is the modal itself
                    if (event.target === editTermModal) {
                        const instance = bootstrap.Modal.getInstance(editTermModal);
                        if (instance) {
                            instance.dispose();
                        }
                    }
                });
            }

            // Clean up delete term modal
            const deleteTermModal = document.getElementById('deleteTermModal');
            if (deleteTermModal) {
                deleteTermModal.addEventListener('hidden.bs.modal', function(event) {
                    // Only dispose if the event target is the modal itself
                    if (event.target === deleteTermModal) {
                        const instance = bootstrap.Modal.getInstance(deleteTermModal);
                        if (instance) {
                            try {
                                instance.dispose();
                            } catch (e) {
                                console.log('Error disposing delete modal:', e);
                            }
                        }
                    }
                });
            }
        });

        // Semester Management Functions - GridJS Version
        // Add Term
        function addTerm() {
            const form = document.getElementById('addTermForm');
            const formData = new FormData(form);
            
            const data = {
                name: formData.get('name'),
                start_date: formData.get('start_date'),
                end_date: formData.get('end_date'),
                is_active: formData.get('is_active') ? 1 : 0
            };

            fetch('/admin/terms', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide add modal first
                    bootstrap.Modal.getInstance(document.getElementById('addTermModal')).hide();
                    
                    // Show success notification
                    showNotification(data.message, true);
                    
                    form.reset();
                    // Reload terms table
                    console.log('Attempting to reload terms table after add...');
                    console.log('window.tabelJadwalInstance:', window.tabelJadwalInstance);
                    console.log('renderTermsTable function:', window.tabelJadwalInstance?.renderTermsTable);
                    
                    // Reload terms table using same pattern as Kelas X and XI
                    console.log('Attempting to reload terms table...');
                    console.log('window.gridInstanceTerms:', window.gridInstanceTerms);
                    
                    // Try immediate reload first
                    if (window.gridInstanceTerms) {
                        console.log('Using gridInstanceTerms.forceRender()');
                        window.gridInstanceTerms.forceRender();
                    } else {
                        console.log('gridInstanceTerms not available, trying to activate tab first...');
                        
                        // Ensure Info Akademik tab is active first
                        const infoAkademikTab = document.querySelector('a[href="#mapel"]');
                        if (infoAkademikTab) {
                            const tab = new bootstrap.Tab(infoAkademikTab);
                            tab.show();
                            
                            // Wait for tab to be active, then try again
                            setTimeout(() => {
                                console.log('Retrying after tab activation...');
                                console.log('window.gridInstanceTerms after tab activation:', window.gridInstanceTerms);
                                
                                if (window.gridInstanceTerms) {
                                    console.log('Using gridInstanceTerms.forceRender() after tab activation');
                                    window.gridInstanceTerms.forceRender();
                                } else {
                                    console.log('Still no gridInstanceTerms, reloading page');
                                    location.reload();
                                }
                            }, 300);
                        } else {
                            console.log('Info Akademik tab not found, reloading page');
                            location.reload();
                        }
                    }
                } else {
                    showNotification(data.message, false);
                }
            })
            .catch(error => {
                console.error('Error adding term:', error);
                showNotification('Gagal menambahkan semester', false);
            });
        }

        // Edit Term
        function editTerm(id) {
            fetch(`/admin/terms/${id}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const term = data.data;
                        document.getElementById('edit_term_id').value = term.id;
                        document.getElementById('edit_term_name').value = term.name;
                        
                        // Convert date format for input type="date"
                        // Handle both ISO format and simple date format
                        let formattedStartDate, formattedEndDate;
                        
                        if (term.start_date.includes('T')) {
                            // ISO format: 2025-06-30T16:00:00.000000Z
                            formattedStartDate = term.start_date.split('T')[0];
                            formattedEndDate = term.end_date.split('T')[0];
                        } else {
                            // Simple format: 2025-06-30
                            formattedStartDate = term.start_date;
                            formattedEndDate = term.end_date;
                        }
                        
                        
                        document.getElementById('edit_term_start_date').value = formattedStartDate;
                        document.getElementById('edit_term_end_date').value = formattedEndDate;
                        document.getElementById('edit_term_is_active').checked = term.is_active;
                        
                        // Show modal using setTimeout to ensure DOM is ready
                        setTimeout(() => {
                            const editModalElement = document.getElementById('editTermModal');
                            if (editModalElement) {
                                // Remove any existing modal instances
                                const existingInstance = bootstrap.Modal.getInstance(editModalElement);
                                if (existingInstance) {
                                    existingInstance.dispose();
                                }
                                
                                // Create and show new modal instance
                                const modal = new bootstrap.Modal(editModalElement, {
                                    backdrop: true,
                                    keyboard: true,
                                    focus: true
                                });
                                modal.show();
                            }
                        }, 100);
                    } else {
                        showAlert('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error loading term:', error);
                    showAlert('error', 'Gagal memuat data semester: ' + error.message);
                });
        }

        // Update Term
        function updateTerm() {
            const form = document.getElementById('editTermForm');
            const formData = new FormData(form);
            const id = formData.get('id');
            
            const data = {
                name: formData.get('name'),
                start_date: formData.get('start_date'),
                end_date: formData.get('end_date'),
                is_active: formData.get('is_active') ? 1 : 0
            };

            fetch(`/admin/terms/${id}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide edit modal first
                    bootstrap.Modal.getInstance(document.getElementById('editTermModal')).hide();
                    
                    // Show success notification
                    showNotification(data.message, true);
                    
                    // Reload terms table
                    console.log('Attempting to reload terms table after update...');
                    console.log('window.tabelJadwalInstance:', window.tabelJadwalInstance);
                    console.log('renderTermsTable function:', window.tabelJadwalInstance?.renderTermsTable);
                    
                    // Reload terms table using same pattern as Kelas X and XI
                    console.log('Attempting to reload terms table...');
                    console.log('window.gridInstanceTerms:', window.gridInstanceTerms);
                    
                    // Try immediate reload first
                    if (window.gridInstanceTerms) {
                        console.log('Using gridInstanceTerms.forceRender()');
                        window.gridInstanceTerms.forceRender();
                    } else {
                        console.log('gridInstanceTerms not available, trying to activate tab first...');
                        
                        // Ensure Info Akademik tab is active first
                        const infoAkademikTab = document.querySelector('a[href="#mapel"]');
                        if (infoAkademikTab) {
                            const tab = new bootstrap.Tab(infoAkademikTab);
                            tab.show();
                            
                            // Wait for tab to be active, then try again
                            setTimeout(() => {
                                console.log('Retrying after tab activation...');
                                console.log('window.gridInstanceTerms after tab activation:', window.gridInstanceTerms);
                                
                                if (window.gridInstanceTerms) {
                                    console.log('Using gridInstanceTerms.forceRender() after tab activation');
                                    window.gridInstanceTerms.forceRender();
                                } else {
                                    console.log('Still no gridInstanceTerms, reloading page');
                                    location.reload();
                                }
                            }, 300);
                        } else {
                            console.log('Info Akademik tab not found, reloading page');
                            location.reload();
                        }
                    }
                } else {
                    showNotification(data.message, false);
                }
            })
            .catch(error => {
                console.error('Error updating term:', error);
                showNotification('Gagal memperbarui semester', false);
            });
        }

        // Delete Term - Safe Bootstrap approach
        function deleteTerm(id) {
            console.log('deleteTerm called with ID:', id);
            document.getElementById('deleteTermId').value = id;
            
            try {
                const modalElement = document.getElementById('deleteTermModal');
                if (!modalElement) {
                    console.error('Delete modal element not found');
                    return;
                }
                
                // Dispose any existing instance first
                const existingInstance = bootstrap.Modal.getInstance(modalElement);
                if (existingInstance) {
                    try {
                        existingInstance.dispose();
                    } catch (e) {
                        console.log('Error disposing existing modal:', e);
                    }
                }
                
                // Create new modal instance
                const modal = new bootstrap.Modal(modalElement, {
                    backdrop: true,
                    keyboard: true,
                    focus: true
                });
                
                modal.show();
            } catch (error) {
                console.error('Error showing delete modal:', error);
                // Fallback: try to show modal using data-bs-toggle
                const modalElement = document.getElementById('deleteTermModal');
                if (modalElement) {
                    modalElement.setAttribute('data-bs-toggle', 'modal');
                    modalElement.setAttribute('data-bs-target', '#deleteTermModal');
                    modalElement.click();
                }
            }
        }

        // Confirm Delete Term
        function confirmDeleteTerm() {
            const id = document.getElementById('deleteTermId').value;
            console.log('Deleting term with ID:', id);
            
            fetch(`/admin/terms/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Delete response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Delete response data:', data);
                if (data.success) {
                    // Hide delete modal first
                    bootstrap.Modal.getInstance(document.getElementById('deleteTermModal')).hide();
                    
                    // Show success notification
                    showNotification(data.message, true);
                    
                    // Reload terms table
                    console.log('Attempting to reload terms table after delete...');
                    console.log('window.tabelJadwalInstance:', window.tabelJadwalInstance);
                    console.log('renderTermsTable function:', window.tabelJadwalInstance?.renderTermsTable);
                    
                    // Reload terms table using same pattern as Kelas X and XI
                    console.log('Attempting to reload terms table...');
                    console.log('window.gridInstanceTerms:', window.gridInstanceTerms);
                    
                    // Try immediate reload first
                    if (window.gridInstanceTerms) {
                        console.log('Using gridInstanceTerms.forceRender()');
                        window.gridInstanceTerms.forceRender();
                    } else {
                        console.log('gridInstanceTerms not available, trying to activate tab first...');
                        
                        // Ensure Info Akademik tab is active first
                        const infoAkademikTab = document.querySelector('a[href="#mapel"]');
                        if (infoAkademikTab) {
                            const tab = new bootstrap.Tab(infoAkademikTab);
                            tab.show();
                            
                            // Wait for tab to be active, then try again
                            setTimeout(() => {
                                console.log('Retrying after tab activation...');
                                console.log('window.gridInstanceTerms after tab activation:', window.gridInstanceTerms);
                                
                                if (window.gridInstanceTerms) {
                                    console.log('Using gridInstanceTerms.forceRender() after tab activation');
                                    window.gridInstanceTerms.forceRender();
                                } else {
                                    console.log('Still no gridInstanceTerms, reloading page');
                                    location.reload();
                                }
                            }, 300);
                        } else {
                            console.log('Info Akademik tab not found, reloading page');
                            location.reload();
                        }
                    }
                } else {
                    showNotification(data.message, false);
                }
            })
            .catch(error => {
                console.error('Error deleting term:', error);
                showNotification('Gagal menghapus semester', false);
            });
        }

        // Delete All Terms
        function deleteAllTerms() {
            fetch('/admin/terms/delete-all', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    bootstrap.Modal.getInstance(document.getElementById('deleteAllTermsModal')).hide();
                    // Reload terms table
                    if (window.tabelJadwalInstance && window.tabelJadwalInstance.renderTermsTable) {
                        window.tabelJadwalInstance.renderTermsTable();
                    }
                } else {
                    showAlert('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error deleting all terms:', error);
                showAlert('error', 'Gagal menghapus semua semester');
            });
        }

    </script>

    <style>
        .filter-field-auto-set {
            border-color: #28a745 !important;
            background-color: #d4edda !important;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
        }
        
        .auto-set-indicator {
            color: #28a745;
            font-weight: 500;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: block;
        }
        
        .auto-set-indicator::before {
            content: "✓ ";
            font-weight: bold;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical-admin', ['subtitle' => 'Jadwal Pelajaran'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/admin/jadwal-pelajaran.blade.php ENDPATH**/ ?>