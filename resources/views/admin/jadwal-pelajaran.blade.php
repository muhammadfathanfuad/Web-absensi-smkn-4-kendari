@extends('layouts.vertical-admin', ['subtitle' => 'Jadwal Pelajaran'])

@section('content')
@section('css')
    @vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
    @vite(['node_modules/select2/dist/css/select2.min.css'])
    @vite(['resources/css/admin/jadwal-pelajaran.css'])
@endsection

@include('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'Jadwal Pelajaran'])

<div class="row">
        <div class="card">
            <div class="col-lg-0">
                <div class="card-body">
                    <div class="nav-tabs-wrapper">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a href="#kelasx" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                    <span class="d-block d-sm-none">Kelas X</span>
                                    <span class="d-none d-sm-block">Kelas X</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#kelasxi" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                    <span class="d-block d-sm-none">Kelas XI</span>
                                    <span class="d-none d-sm-block">Kelas XI</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#kelasxii" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    <span class="d-block d-sm-none">Kelas XII</span>
                                    <span class="d-none d-sm-block">Kelas XII</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#mapel" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    <span class="d-block d-sm-none">Info Akademik</span>
                                    <span class="d-none d-sm-block">Info Akademik</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#manual" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    <span class="d-block d-sm-none">Tambah Mata Pelajaran Manual</span>
                                    <span class="d-none d-sm-block">Tambah Mata Pelajaran Manual</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content text-muted">
                        <div class="tab-pane show active" id="kelasx">
                            <div class="card-header">
                                <h5 class="card-title">Jadwal Pelajaran Kelas X</h5>
                                    <div class="jadwal-header-controls">
                                        <p class="text-muted mb-0 jadwal-description">
                                            Data Semua jadwal pelajaran
                                        </p>
                                        <div class="jadwal-actions-wrapper">
                                            <div class="semester-filter-wrapper d-flex gap-2 align-items-center flex-wrap">
                                                <div class="semester-filter-item">
                                                    <label for="kelasXSemesterFilter" class="form-label mb-0 me-2">Semester:</label>
                                                    <select class="form-select form-select-sm" id="kelasXSemesterFilter" style="width: auto;">
                                                        <option value="">Memuat semester...</option>
                                                    </select>
                                                </div>
                                                <div class="kelas-hari-filter-wrapper d-flex gap-2 align-items-center">
                                                    <div class="kelas-filter-item">
                                                        <label for="kelasXClassFilter" class="form-label mb-0 me-2">Kelas:</label>
                                                        <select class="form-select form-select-sm" id="kelasXClassFilter" style="width: auto;">
                                                            <option value="">Semua Kelas</option>
                                                        </select>
                                                    </div>
                                                    <div class="hari-filter-item">
                                                        <label for="kelasXDayFilter" class="form-label mb-0 me-2">Hari:</label>
                                                        <select class="form-select form-select-sm" id="kelasXDayFilter" style="width: auto;">
                                                            <option value="">Semua Hari</option>
                                                            <option value="1">Senin</option>
                                                            <option value="2">Selasa</option>
                                                            <option value="3">Rabu</option>
                                                            <option value="4">Kamis</option>
                                                            <option value="5">Jumat</option>
                                                            <option value="6">Sabtu</option>
                                                            <option value="7">Minggu</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="single-actions-jadwal" class="ms-auto">
                                                <div class="btn-group me-2" role="group">
                                                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                                                        aria-expanded="false" id="exportJadwalXDropdownBtn">
                                                        <i class="bx bx-download"></i> <span>Export</span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#" onclick="exportJadwalX('pdf'); return false;">
                                                                <i class="bx bx-file"></i> Export PDF (.pdf)
                                                            </a></li>
                                                    </ul>
                                                </div>
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
                                <div class="jadwal-header-controls">
                                    <p class="text-muted mb-0 jadwal-description">
                                        Jadwal Pelajaran Semester ini
                                    </p>
                                    <div class="jadwal-actions-wrapper">
                                        <div class="semester-filter-wrapper">
                                            <label for="kelasXISemesterFilter" class="form-label mb-0 fw-medium">Semester:</label>
                                            <select class="form-select form-select-sm" id="kelasXISemesterFilter" style="width: 200px;">
                                                <option value="">Memuat semester...</option>
                                            </select>
                                        </div>
                                        <div id="single-actions-jadwal-xi" class="ms-auto">
                                            <div class="btn-group me-2" role="group">
                                                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-expanded="false" id="exportJadwalXIDropdownBtn">
                                                    <i class="bx bx-download"></i> <span>Export</span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#" onclick="exportJadwalXI('pdf'); return false;">
                                                            <i class="bx bx-file"></i> Export PDF (.pdf)
                                                        </a></li>
                                                </ul>
                                            </div>
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
                                <div class="jadwal-header-controls">
                                    <p class="text-muted mb-0 jadwal-description">
                                        Jadwal Pelajaran Semester 1
                                    </p>
                                    <div class="jadwal-actions-wrapper">
                                        <div id="single-actions-jadwal-xii" style="display: none;">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#addMuridModal">
                                                Tambah Jadwal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <iconify-icon icon="solar:info-circle-outline" class="fs-64 text-info"></iconify-icon>
                                    </div>
                                    <h5 class="text-muted mb-3">Fitur Belum Dikembangkan</h5>
                                    <p class="text-muted mb-0" style="max-width: 600px; margin: 0 auto;">
                                        Fitur jadwal pelajaran untuk kelas XII belum dikembangkan karena tidak adanya data sebagai referensi pengembang. 
                                        Fitur ini akan segera dikembangkan setelah data referensi tersedia.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane" id="mapel">
                            <!-- Card Daftar Semester -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title">Daftar Semester</h5>
                                    <div class="jadwal-header-controls">
                                        <p class="text-muted mb-0 jadwal-description">
                                            Kelola semester akademik untuk sistem jadwal pelajaran
                                        </p>
                                        <div class="jadwal-actions-wrapper">
                                            <div id="single-actions-terms">
                                                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addTermModal">
                                                    <i class="bx bx-plus me-1"></i> Tambah Semester
                                                </button>
                                            </div>
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
                                    <div class="jadwal-header-controls">
                                        <p class="text-muted mb-0 jadwal-description">
                                            Semua kelas yang terdaftar di sistem
                                        </p>
                                        <div class="jadwal-actions-wrapper">
                                            <div id="single-actions-classes">
                                                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addClassModal">
                                                    <i class="bx bx-plus me-1"></i> Tambah Kelas
                                                </button>
                                                <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#importClassModal">
                                                    <i class="bx bx-upload me-1"></i> Impor Kelas
                                                </button>
                                            </div>
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
                                    <div class="jadwal-header-controls">
                                        <p class="text-muted mb-0 jadwal-description">
                                            Semua mata pelajaran yang tersedia di sistem
                                        </p>
                                        <div class="jadwal-actions-wrapper">
                                            <div id="single-actions-subjects">
                                                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                                                    <i class="bx bx-plus me-1"></i> Tambah Mata Pelajaran
                                                </button>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadSubjectModal">
                                                    <i class="bx bx-upload me-1"></i> Import Mata Pelajaran
                                                </button>
                                            </div>
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
                                <div class="jadwal-header-controls">
                                    <p class="text-muted mb-0 jadwal-description">
                                        Tambahkan mata pelajaran untuk kelas 10 dan 11 secara manual
                                    </p>
                                    <div class="jadwal-actions-wrapper">
                                        <div id="single-actions-manual">
                                            <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addManualClassSubjectModal">
                                                <i class="bx bx-plus me-1"></i> Tambah Mata Pelajaran
                                            </button>
                                        </div>
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
                    <form id="importJadwalForm" action="{{ route('jadwal.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="jadwalTerm" class="form-label">Pilih Semester <span class="text-danger">*</span></label>
                            <select class="form-select" id="jadwalTerm" name="term_id" required>
                                <option value="">Memuat semester yang tersedia...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="grade" class="form-label">Kelas</label>
                            <input type="text" class="form-control" id="grade" name="grade" value="Kelas X" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                            <input type="hidden" name="grade" value="X">
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
                    <form id="addSubjectForm" action="{{ route('subjects.store') }}" method="POST">
                        @csrf
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
                    <form id="addClassForm" action="{{ route('classes.store') }}" method="POST">
                        @csrf
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
                        @csrf
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
                        @csrf
                        @method('PUT')
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

    {{-- Modal Notifikasi --}}
    @include('components.pengaturan.notification-modal', [
        'modalId' => 'notificationModal',
        'modalLabelId' => 'notificationModalLabel',
        'messageId' => 'notificationMessage',
        'errorsId' => 'notificationErrors',
        'errorsBodyId' => 'notificationErrorsBody',
        'modalSize' => '',
        'showErrorsTable' => false
    ])

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
                    <form id="bulkDeleteJadwalForm" action="{{ route('jadwal.bulkDelete') }}" method="POST">
                        @csrf
                        @method('DELETE')
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
                    <form id="addGuruForm" action="{{ route('guru.store') }}" method="POST">
                        @csrf
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
                    <form id="addMuridForm" action="{{ route('murid.store') }}" method="POST">
                        @csrf
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
                        @csrf
                        @method('PUT')
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
                        @csrf
                        @method('PUT')
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

    {{-- <!-- Import Modal untuk Kelas XI -->
    <div class="modal fade" id="importModalXI" tabindex="-1" aria-labelledby="importModalXILabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalXILabel">Import Jadwal Kelas XI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="importFormXI" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="termXI" class="form-label">Semester <span class="text-danger">*</span></label>
                            <select class="form-select" id="termXI" name="term_id" required>
                                <option value="">Memuat semester yang tersedia...</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="groupTypeXI" class="form-label">Kelompok <span class="text-danger">*</span></label>
                                <select class="form-select" id="groupTypeXI" name="group_type" required>
                                    <option value="">Pilih Kelompok</option>
                                    <option value="A">Kelompok A</option>
                                    <option value="B">Kelompok B</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="fileXI" class="form-label">File Excel</label>
                                <input type="file" class="form-control" id="fileXI" name="file" accept=".xlsx,.csv" required>
                                <div class="form-text">Format: .xlsx atau .csv (Max 10MB)</div>
                            </div>
                        </div>
                        <div class="alert alert-info mt-3">
                            <h6><i class="bx bx-info-circle me-2"></i>Petunjuk Import:</h6>
                            <ul class="mb-0">
                                <li>Pastikan file Excel memiliki format yang benar</li>
                                <li>Kelompok A: 7 kelas (TKJA, TKJC, RPLA, RPLC, KTA, DKVA, PSPTA)</li>
                                <li>Kelompok B: 6 kelas (TKJB, RPLB, KK, KTB, DKVB, PSPTB)</li>
                                <li>Minggu Ganjil: Kelompok A di Lab, B di Teori</li>
                                <li>Minggu Genap: Kelompok B di Lab, A di Teori</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" id="importBtnXI">
                            <i class="bx bx-upload me-1"></i> Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

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
                        @csrf
                        <div class="mb-3">
                            <label for="xiTerm" class="form-label">Pilih Semester <span class="text-danger">*</span></label>
                            <select class="form-select" id="xiTerm" name="term_id" required>
                                <option value="">Memuat semester yang tersedia...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="xiGrade" class="form-label">Kelas</label>
                            <input type="text" class="form-control" id="xiGrade" name="grade" value="XI" readonly style="background-color: #e9ecef; cursor: not-allowed;">
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
                        @csrf
                        
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

@endsection

@section('scripts')
    <script>
        window.jadwalExportUrl = "{{ route('admin.jadwal.export') }}";
        window.jadwalXiExportUrl = "{{ route('admin.jadwal-xi.export') }}";
        window.manualFormDataUrl = "{{ route('manual.form-data') }}";
        window.manualClassSubjectStoreUrl = "{{ route('manual.class-subject.store') }}";
    </script>
    @vite(['resources/js/admin/tabel-jadwal.js'])
    @vite(['resources/js/admin/jadwal-pelajaran.js'])
@endsection

@section('styles')
@endsection
