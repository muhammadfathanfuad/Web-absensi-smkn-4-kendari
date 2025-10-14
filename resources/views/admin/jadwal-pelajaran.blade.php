@extends('layouts.vertical-admin', ['subtitle' => 'Jadwal Pelajaran'])

@section('content')
@section('css')
    @vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
    @vite(['node_modules/select2/dist/css/select2.min.css'])
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
@endsection

@include('layouts.partials.page-title', ['title' => 'jadwal', 'subtitle' => 'jadwal pelajaran'])

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
                                <span class="d-none d-sm-block">Mata Pelajaran</span>
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
                                    <div id="single-actions-jadwal">
                                        <button type="button" class="btn btn-success me-2" data-bs-toggle="modal"
                                            data-bs-target="#addSubjectModal">
                                            Tambah Mata Pelajaran
                                        </button>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addUserModal">
                                            Import Jadwal
                                        </button>
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
                                    <div id="single-actions-jadwal-xi">
                                        <button type="button" class="btn btn-info me-2" id="filter-jadwal-xi">
                                            <i class="bx bx-filter me-1"></i> Filter
                                        </button>
                                        <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#importJadwalXIModal">
                                            <i class="bx bx-upload me-1"></i> Import Mata Pelajaran
                                        </button>
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
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#uploadSubjectModal">
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
                            <div class="card mt-4">
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
                            <label for="fileXI" class="form-label">File Excel</label>
                            <input type="file" class="form-control" id="fileXI" name="file" accept=".xlsx,.csv" required>
                            <div class="form-text">Format: .xlsx atau .csv (Max 10MB)</div>
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
                            <li><strong>Kelompok A:</strong>Jika memilih salah satu kelas ini TKJA, TKJC, RPLA, RPLC, KTA, DKVA, PSPTA</li>
                            <li><strong>Kelompok B:</strong>Jika memilih salah satu kelas ini TKJB, RPLB, KTB, KK, DKVB, PSPTB</li>
                            <li><strong>Minggu → Lokasi:</strong> Ganjil = Lab, Genap = Teori</li>
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
                            <label for="xiFile" class="form-label">Pilih File</label>
                            <input type="file" class="form-control" id="xiFile" name="file" accept=".xlsx,.csv" required>
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
                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-3">
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
                                                <div class="avatar-xs bg-primary bg-opacity-10 rounded-circle me-2">
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
                                                <div class="avatar-xs bg-success bg-opacity-10 rounded-circle me-2">
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
                                                <div class="avatar-xs bg-info bg-opacity-10 rounded-circle me-2">
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
                                                <div class="avatar-xs bg-warning bg-opacity-10 rounded-circle me-2">
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

    <!-- Modal Detail Mata Pelajaran -->
    <div class="modal fade" id="subjectDetailModal" tabindex="-1" aria-labelledby="subjectDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary bg-opacity-10">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-3">
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
                                                <div class="avatar-xs bg-primary bg-opacity-10 rounded-circle me-2">
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
                                                <div class="avatar-xs bg-success bg-opacity-10 rounded-circle me-2">
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
                                                <div class="avatar-xs bg-info bg-opacity-10 rounded-circle me-2">
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
                                                <div class="avatar-xs bg-warning bg-opacity-10 rounded-circle me-2">
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
                                            <iconify-icon icon="solar:clock-circle-outline" class="fs-48 text-muted"></iconify-icon>
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
                                            <iconify-icon icon="solar:clock-circle-outline" class="fs-48 text-muted"></iconify-icon>
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

@endsection

@section('scripts')
    @vite(['resources/js/admin/tabel-jadwal.js'])
    <script>
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
    </script>
@endsection
