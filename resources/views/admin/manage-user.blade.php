@extends('layouts.vertical-admin', ['subtitle' => 'manajemen-pengguna'])

@section('css')
    @vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
    @vite(['node_modules/select2/dist/css/select2.min.css'])
@endsection

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'Manajemen Pengguna'])

    <div class="row">
        <div class="card">
            <div class="col-lg-0">
                <div class="card-body">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a href="#semua" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                <span>Data User</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#guru" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                <span>Data Guru</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#murid" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                <span>Data Murid</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content text-muted">
                        <div class="tab-pane show active" id="semua">
                            <div class="card-header">
                                <h5 class="card-title">Data User</h5>
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                    <div class="d-flex gap-2 align-items-center flex-wrap">
                                    <p class="text-muted mb-0">
                                        Data Semua Warga Sekolah
                                    </p>
                                        <div class="d-flex gap-2">
                                            <select class="form-select form-select-sm" id="roleFilter" style="width: auto; min-width: 150px;">
                                                <option value="">Semua User</option>
                                                <option value="teacher">Data Guru</option>
                                                <option value="student">Data Siswa</option>
                                            </select>
                                            <select class="form-select form-select-sm" id="classFilter" style="width: auto; min-width: 180px; display: none !important;">
                                                <option value="">Semua Kelas</option>
                                                @foreach($classrooms as $classroom)
                                                    <option value="{{ $classroom->id }}">{{ $classroom->grade }} - {{ $classroom->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div id="single-actions" class="ms-auto d-flex gap-2">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                                                aria-expanded="false" id="exportUserDropdownBtn">
                                                <i class="bx bx-download"></i> <span>Export</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="exportUserData('pdf'); return false;">
                                                        <i class="bx bx-file"></i> Export PDF (.pdf)
                                                    </a></li>
                                            </ul>
                                        </div>
                                        <button type="button" class="btn btn-primary" id="addUserBtn" data-bs-toggle="modal"
                                            data-bs-target="#addUserModal">
                                            Tambah User
                                        </button>
                                    </div>
                                    <div id="bulk-actions" style="display: none;">
                                        <button type="button" class="btn btn-warning me-2" id="bulkEditBtn">
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-danger" id="bulkDeleteBtn">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div id="table-search"></div>
                            </div>
                        </div>
                        <div class="tab-pane" id="guru">
                            <div class="card-header">
                                <h5 class="card-title">Data Guru</h5>
                                <div class="d-flex justify-content-between align-items-center mb-0 flex-wrap">
                                    <p class="text-muted mb-0">
                                        Data Semua Guru Sekolah
                                    </p>
                                    <div id="single-actions-guru" class="ms-auto">
                                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
                                            data-bs-target="#addGuruModal">
                                            Tambah Guru
                                        </button>
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadGuruModal">
                                            Upload Data
                                        </button>
                                    </div>
                                    <div id="bulk-actions-guru" style="display: none;">
                                        <button type="button" class="btn btn-danger" id="bulkDeleteGuruBtn">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div id="table-guru"></div>
                            </div>
                        </div>
                        <div class="tab-pane" id="murid">
                            <div class="card-header">
                                <h5 class="card-title">Data Murid</h5>
                                <div class="d-flex justify-content-between align-items-center mb-0 flex-wrap">
                                    <p class="text-muted mb-0">
                                        Data Semua Murid Sekolah
                                    </p>
                                    <div id="single-actions-murid" class="ms-auto">
                                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
                                            data-bs-target="#addMuridModal">
                                            Tambah Murid
                                        </button>
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadMuridModal">
                                            Upload Data
                                        </button>
                                    </div>
                                    <div id="bulk-actions-murid" style="display: none;">
                                        <button type="button" class="btn btn-danger" id="bulkDeleteMuridBtn">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div id="table-murid"></div>
                            </div>
                        </div>
                    </div>
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
                            <label for="addGuruEmail" class="form-label">Email User</label>
                            <input type="email" class="form-control" id="addGuruEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="addGuruNip" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="addGuruNip" name="nip" required>
                        </div>
                        <div class="mb-3">
                            <label for="addGuruKode" class="form-label">Kode Guru</label>
                            <input type="text" class="form-control" id="addGuruKode" name="kode_guru" required>
                        </div>
                        <div class="mb-3">
                            <label for="addGuruDepartment" class="form-label">Mata Pelajaran yang Diajarkan</label>
                            <select class="form-select" id="addGuruDepartment" name="department" required>
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach(\App\Models\Subject::orderBy('name')->get() as $subject)
                                    <option value="{{ $subject->name }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Tambah Guru</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Guru -->
    <div class="modal fade" id="uploadGuruModal" tabindex="-1" aria-labelledby="uploadGuruModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadGuruModalLabel">Upload Data Guru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadGuruForm" action="{{ route('guru.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="uploadGuruFile" class="form-label">Pilih File Excel atau CSV</label>
                            <input type="file" class="form-control" id="uploadGuruFile" name="file" accept=".xlsx,.xls,.csv" required>
                            <small class="form-text text-muted">Format file: Excel (.xlsx, .xls) atau CSV (.csv). Header kolom: kode_guru, nama_guru, nip, email, no_hp, department.</small>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="uploadGuruSubmitBtn">
                                <span class="spinner-border spinner-border-sm d-none" id="uploadGuruSpinner" role="status" aria-hidden="true"></span>
                                <span id="uploadGuruBtnText">Upload</span>
                            </button>
                        </div>
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
                            <label for="addMuridEmail" class="form-label">Email User</label>
                            <input type="email" class="form-control" id="addMuridEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="addMuridNis" class="form-label">NIS</label>
                            <input type="text" class="form-control" id="addMuridNis" name="nis" required>
                        </div>
                        <div class="mb-3">
                            <label for="addMuridTingkatan" class="form-label">Tingkatan</label>
                            <select class="form-select" id="addMuridTingkatan" name="tingkatan" required>
                                @foreach(\App\Models\Classroom::distinct('grade')->pluck('grade') as $grade)
                                    <option value="{{ $grade }}">{{ $grade }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="addMuridClass" class="form-label">Kelas</label>
                            <select class="form-select" id="addMuridClass" name="class_id" required>
                                @foreach(\App\Models\Classroom::all() as $class)
                                    <option value="{{ $class->id }}" data-grade="{{ $class->grade }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="addMuridGuardianName" class="form-label">Nama Wali</label>
                            <input type="text" class="form-control" id="addMuridGuardianName" name="guardian_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="addMuridGuardianPhone" class="form-label">Nomor HP Wali</label>
                            <input type="text" class="form-control" id="addMuridGuardianPhone" name="guardian_phone" required>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#uploadMuridModal">Upload File</button>
                            <button type="submit" class="btn btn-primary">Tambah Murid</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Murid -->
    <div class="modal fade" id="uploadMuridModal" tabindex="-1" aria-labelledby="uploadMuridModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadMuridModalLabel">Upload Data Murid</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadMuridForm" action="{{ route('murid.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="uploadMuridFile" class="form-label">Pilih File Excel atau CSV</label>
                            <input type="file" class="form-control" id="uploadMuridFile" name="file" accept=".xlsx,.xls,.csv" required>
                            <small class="form-text text-muted">Format file: Excel (.xlsx, .xls) atau CSV (.csv). Header kolom: nama, nis, kelas, nama_wali, telepon_wali.</small>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="uploadMuridSubmitBtn">
                                <span class="spinner-border spinner-border-sm d-none" id="uploadMuridSpinner" role="status" aria-hidden="true"></span>
                                <span id="uploadMuridBtnText">Upload</span>
                            </button>
                        </div>
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
                            <label for="editGuruName" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="editGuruName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editGuruEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editGuruEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="editGuruNip" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="editGuruNip" name="nip" required>
                        </div>
                        <div class="mb-3">
                            <label for="editGuruKode" class="form-label">Kode Guru</label>
                            <input type="text" class="form-control" id="editGuruKode" name="kode_guru" required>
                        </div>
                        <div class="mb-3">
                            <label for="editGuruDepartment" class="form-label">Department</label>
                            <input type="text" class="form-control" id="editGuruDepartment" name="department" required>
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
                            <label for="editMuridName" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="editMuridName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editMuridEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editMuridEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="editMuridNis" class="form-label">NIS</label>
                            <input type="text" class="form-control" id="editMuridNis" name="nis" required>
                        </div>
                        <div class="mb-3">
                            <label for="editMuridTingkatan" class="form-label">Tingkatan</label>
                            <select class="form-select" id="editMuridTingkatan" name="tingkatan" required>
                                @foreach(\App\Models\Classroom::distinct('grade')->pluck('grade') as $grade)
                                    <option value="{{ $grade }}">{{ $grade }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editMuridClass" class="form-label">Kelas</label>
                            <select class="form-select" id="editMuridClass" name="class_id" required>
                                @foreach(\App\Models\Classroom::all() as $class)
                                    <option value="{{ $class->id }}" data-grade="{{ $class->grade }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editMuridGuardianName" class="form-label">Nama Wali</label>
                            <input type="text" class="form-control" id="editMuridGuardianName" name="guardian_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editMuridGuardianPhone" class="form-label">Nomor HP Wali</label>
                            <input type="text" class="form-control" id="editMuridGuardianPhone" name="guardian_phone" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete Guru -->
    <div class="modal fade" id="deleteGuruModal" tabindex="-1" aria-labelledby="deleteGuruModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteGuruModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus guru ini?<br>Data guru juga akan dihapus dari tabel user.</p>
                    <input type="hidden" id="deleteGuruId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteGuruButton">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete Murid -->
    <div class="modal fade" id="deleteMuridModal" tabindex="-1" aria-labelledby="deleteMuridModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteMuridModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus murid ini?<br>Data murid juga akan dihapus dari tabel user.</p>
                    <input type="hidden" id="deleteMuridId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteMuridButton">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah User -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="addUserName" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="addUserName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="addUserEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="addUserEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="addUserPhone" class="form-label">Nomor Hp</label>
                            <input type="text" class="form-control" id="addUserPhone" name="phone">
                        </div>

                        <div class="mb-3">
                            <label for="addUserPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="addUserPassword" name="password">
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah User</button>
                    </form>
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
                            <input type="text" class="form-control" id="editUserPhone" name="phone">
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
                    <input type="hidden" id="deleteRoute" value="/admin/user">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteUserButton">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Bulk Edit Status -->
    <div class="modal fade" id="bulkEditStatusModal" tabindex="-1" aria-labelledby="bulkEditStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkEditStatusModalLabel">Edit Status User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Pilih status baru untuk user yang dipilih:</p>
                    <select class="form-select" id="bulkStatusSelect">
                        <option value="active">Active</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmBulkEditStatus">Simpan</button>
                </div>
            </div>
        </div>
    </div>



    <div id="notificationModal" class="modal fade" tabindex="-1" aria-labelledby="notificationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">Notifikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="notificationMessage" style="white-space: pre-wrap;"></div>
                    <div id="notificationErrors" class="mt-3 d-none">
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
                                <tbody id="notificationErrorsBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.baseUrl = "{{ request()->getSchemeAndHttpHost() . request()->getBasePath() }}";
    </script>
    @vite(['resources/js/admin/tabel.js'])
    @vite(['resources/js/admin/select2-init.js'])

    <script>
        // Functions to show edit modals
        function showEditUserModal(id, name, email, phone, status) {
            document.getElementById('editUserId').value = id;
            document.getElementById('editUserName').value = name;
            document.getElementById('editUserEmail').value = email;
            document.getElementById('editUserPhone').value = phone;
            document.getElementById('editUserStatus').value = status;
            const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
            modal.show();
        }

        function showEditGuruModal(id, name, email, nip, department, kode_guru) {
            document.getElementById('editGuruId').value = id;
            document.getElementById('editGuruName').value = name;
            document.getElementById('editGuruEmail').value = email;
            document.getElementById('editGuruNip').value = nip;
            document.getElementById('editGuruDepartment').value = department;
            document.getElementById('editGuruKode').value = kode_guru;
            const modal = new bootstrap.Modal(document.getElementById('editGuruModal'));
            modal.show();
        }

        function showEditMuridModal(id, name, email, nis, class_id, guardian_name, guardian_phone, grade) {
            document.getElementById('editMuridId').value = id;
            document.getElementById('editMuridName').value = name;
            document.getElementById('editMuridEmail').value = email;
            document.getElementById('editMuridNis').value = nis;
            document.getElementById('editMuridTingkatan').value = grade;
            document.getElementById('editMuridClass').value = class_id;
            document.getElementById('editMuridGuardianName').value = guardian_name;
            document.getElementById('editMuridGuardianPhone').value = guardian_phone;
            const modal = new bootstrap.Modal(document.getElementById('editMuridModal'));
            modal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Helper function to remove focus before modal is hidden to prevent accessibility warning
            function setupModalAccessibility(modalId) {
                const modalEl = document.getElementById(modalId);
                if (!modalEl) return;

                // Remove focus from close buttons before hiding
                const closeButtons = modalEl.querySelectorAll('.btn-close, [data-bs-dismiss="modal"]');
                closeButtons.forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.target.blur(); // Remove focus before hiding
                    });
                });

                // Handle when modal is hidden via Bootstrap events
                modalEl.addEventListener('hidden.bs.modal', function() {
                    // Remove focus from any focused element inside modal
                    const focusedElement = this.querySelector(':focus');
                    if (focusedElement) {
                        focusedElement.blur();
                    }
                }, { once: false });
            }

            // Setup accessibility for all modals
            const modalIds = [
                'addGuruModal',
                'uploadGuruModal',
                'addMuridModal',
                'uploadMuridModal',
                'editGuruModal',
                'editMuridModal',
                'deleteGuruModal',
                'deleteMuridModal',
                'addUserModal',
                'editUserModal',
                'deleteUserModal',
                'bulkEditStatusModal',
                'notificationModal'
            ];

            modalIds.forEach(modalId => setupModalAccessibility(modalId));

            const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
            const notificationModalElement = document.getElementById('notificationModal');

            // Reload page when notification modal is closed
            notificationModalElement.addEventListener('hidden.bs.modal', function() {
                window.location.reload();
            });

            // Ensure close buttons work - remove focus before hiding to prevent accessibility warning
            document.querySelector('#notificationModal .btn-close')?.addEventListener('click', (e) => {
                e.target.blur(); // Remove focus before hiding
                notificationModal.hide();
            });
            document.querySelector('#notificationModal .btn-light')?.addEventListener('click', (e) => {
                e.target.blur(); // Remove focus before hiding
                notificationModal.hide();
            });

            function showNotification(message, isSuccess = true, errors = null) {
                const modalLabel = document.getElementById('notificationModalLabel');
                const modalMessage = document.getElementById('notificationMessage');
                const errorsContainer = document.getElementById('notificationErrors');
                const errorsBody = document.getElementById('notificationErrorsBody');
                
                // Set modal title and message
                modalLabel.innerText = isSuccess ? 'Berhasil' : 'Gagal';
                modalLabel.className = 'modal-title ' + (isSuccess ? 'text-success' : 'text-danger');
                modalMessage.innerText = message;
                
                // Show/hide errors table
                if (errors && errors.length > 0) {
                    errorsContainer.classList.remove('d-none');
                    errorsBody.innerHTML = '';
                    
                    errors.forEach(error => {
                        const row = document.createElement('tr');
                        // Support both teacher and student error formats
                        const name = error.nama_guru || error.nama || '(kosong)';
                        const identifier = error.kode_guru || error.nis || '(kosong)';
                        const identifierLabel = error.kode_guru ? 'Kode Guru' : (error.nis ? 'NIS' : '');
                        
                        row.innerHTML = `
                            <td>${error.row}</td>
                            <td>${name}</td>
                            <td>${identifierLabel ? identifierLabel + ': ' : ''}${identifier}</td>
                            <td>
                                <ul class="mb-0 ps-3">
                                    ${error.errors.map(err => `<li>${err}</li>`).join('')}
                                </ul>
                            </td>
                        `;
                        errorsBody.appendChild(row);
                    });
                } else {
                    errorsContainer.classList.add('d-none');
                }
                
                notificationModal.show();
            }

            function refreshTable() {
                if (window.gridInstance) {
                    window.gridInstance.forceRender();
                }
            }


            // Add User form submission handler
            const addUserForm = document.getElementById('addUserForm');
            if (addUserForm) {
                addUserForm.addEventListener('submit', async function(event) {
                    event.preventDefault();
                    const formData = new FormData(this);
                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            headers: {
                                Accept: "application/json"
                            },
                            body: formData
                        });
                        const data = await response.json();
                        if (data.errors) {
                            data.message = 'Validasi gagal: ' + Object.values(data.errors).flat().join(', ');
                            data.success = false;
                        } else if (!response.ok) {
                            showNotification(data.message || 'Terjadi kesalahan server', false);
                            return;
                        }
                        bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
                        showNotification(data.message, data.success);
                        if (data.success) {
                            this.reset();
                            refreshTable();
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showNotification('Gagal menambahkan user. Periksa data atau koneksi.', false);
                    }
                });
            }

            // Upload Guru form submission
            document.getElementById('uploadGuruForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const submitButton = document.getElementById('uploadGuruSubmitBtn');
                const spinner = document.getElementById('uploadGuruSpinner');
                const btnText = document.getElementById('uploadGuruBtnText');
                const fileInput = document.getElementById('uploadGuruFile');
                const uploadModal = bootstrap.Modal.getInstance(document.getElementById('uploadGuruModal'));
                
                // Show loading state
                submitButton.disabled = true;
                spinner.classList.remove('d-none');
                btnText.textContent = 'Mengupload...';
                fileInput.disabled = true;
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    showNotification(data.message, data.success, data.errors);
                    if (data.success && data.error_count === 0) {
                        uploadModal.hide();
                        this.reset();
                        // Refresh table
                        if (window.gridInstanceGuru) window.gridInstanceGuru.forceRender();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Gagal mengupload data. ' + (error.message || ''), false);
                })
                .finally(() => {
                    submitButton.disabled = false;
                    spinner.classList.add('d-none');
                    btnText.textContent = 'Upload';
                    fileInput.disabled = false;
                });
            });

            // Upload Murid form submission
            document.getElementById('uploadMuridForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const submitButton = document.getElementById('uploadMuridSubmitBtn');
                const spinner = document.getElementById('uploadMuridSpinner');
                const btnText = document.getElementById('uploadMuridBtnText');
                const fileInput = document.getElementById('uploadMuridFile');
                const uploadModal = bootstrap.Modal.getInstance(document.getElementById('uploadMuridModal'));
                
                // Show loading state
                submitButton.disabled = true;
                spinner.classList.remove('d-none');
                btnText.textContent = 'Mengupload...';
                fileInput.disabled = true;
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async response => {
                    // Handle 504 Gateway Timeout specially
                    if (response.status === 504) {
                        throw new Error('TIMEOUT_504');
                    }
                    
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                    return response.json();
                    } else {
                        // If response is not JSON, try to get text
                        const text = await response.text();
                        throw new Error(`Server mengembalikan response yang tidak valid. Status: ${response.status}`);
                    }
                })
                .then(data => {
                    // Close upload modal first
                    uploadModal.hide();
                    
                    showNotification(data.message, data.success, data.errors);
                    if (data.success && data.error_count === 0) {
                        this.reset();
                        // Refresh table
                        if (window.gridInstanceMurid) window.gridInstanceMurid.forceRender();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    let errorMessage = '';
                    
                    // Close upload modal first
                    uploadModal.hide();
                    
                    // Special handling for 504 Gateway Timeout
                    if (error.message === 'TIMEOUT_504' || error.message.includes('504')) {
                        errorMessage = 'Import memakan waktu terlalu lama dan server timeout.\n\n';
                        errorMessage += 'Namun, data mungkin sudah berhasil diimpor ke database.\n';
                        errorMessage += 'Silakan refresh halaman atau cek tabel data murid untuk memastikan.\n\n';
                        errorMessage += 'Jika data sudah masuk, berarti import berhasil meskipun ada timeout.';
                        
                        // Show warning instead of error, and suggest to refresh
                        showNotification(errorMessage, true); // Show as success/warning
                        
                        // Auto refresh table after a delay to check if data was imported
                        setTimeout(() => {
                            if (window.gridInstanceMurid) {
                                window.gridInstanceMurid.forceRender();
                            }
                        }, 2000);
                        
                        return; // Exit early
                    }
                    
                    // Regular error handling
                    errorMessage = 'Gagal mengupload data.';
                    if (error.message) {
                        errorMessage += ' ' + error.message;
                    } else {
                        errorMessage += ' Terjadi kesalahan pada server.';
                    }
                    showNotification(errorMessage, false);
                })
                .finally(() => {
                    submitButton.disabled = false;
                    spinner.classList.add('d-none');
                    btnText.textContent = 'Upload';
                    fileInput.disabled = false;
                });
            });

            // Filter class based on tingkatan for add murid
            document.getElementById('addMuridTingkatan').addEventListener('change', function() {
                const selectedGrade = this.value;
                const classSelect = document.getElementById('addMuridClass');
                const options = classSelect.querySelectorAll('option');
                options.forEach(option => {
                    if (option.value === '') return;
                    if (option.getAttribute('data-grade') === selectedGrade) {
                        option.style.display = '';
                    } else {
                        option.style.display = 'none';
                    }
                });
                classSelect.value = '';
            });

            // Add Murid form submission
            document.getElementById('addMuridForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch(this.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    showNotification(data.message, data.success);
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('addMuridModal')).hide();
                        this.reset();
                        // Refresh table
                        if (window.gridInstance) window.gridInstance.forceRender();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Gagal menambah murid.', false);
                });
            });


            // LOGIKA EDIT USER
            const editUserForm = document.getElementById('editUserForm');

            editUserForm.addEventListener('submit', async function(event) {
                event.preventDefault();
                const userId = document.getElementById('editUserId').value;
                const formData = new FormData(editUserForm);
                formData.append('_method', 'PUT'); // Method spoofing

                try {
                    const response = await fetch(`/admin/user/${userId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });
                    const data = await response.json();
                    bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                    showNotification(data.message, data.success);
                    if (data.success) {
                        refreshTable();
                        // Auto-reorder table after status change
                        setTimeout(() => {
                            if (window.gridInstance && window.gridInstance.reorderTableByStatus) {
                                window.gridInstance.reorderTableByStatus();
                            }
                        }, 500);
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            });

            // Filter class based on tingkatan for edit murid
            document.getElementById('editMuridTingkatan').addEventListener('change', function() {
                const selectedGrade = this.value;
                const classSelect = document.getElementById('editMuridClass');
                const options = classSelect.querySelectorAll('option');
                options.forEach(option => {
                    if (option.value === '') return;
                    if (option.getAttribute('data-grade') === selectedGrade) {
                        option.style.display = '';
                    } else {
                        option.style.display = 'none';
                    }
                });
                classSelect.value = '';
            });

            // LOGIKA EDIT MURID
            const editMuridForm = document.getElementById('editMuridForm');

            editMuridForm.addEventListener('submit', async function(event) {
                event.preventDefault();
                const muridId = document.getElementById('editMuridId').value;
                const formData = new FormData(editMuridForm);
                formData.append('_method', 'PUT'); // Method spoofing

                try {
                    const response = await fetch(`/admin/murid/${muridId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });
                    const data = await response.json();
                    bootstrap.Modal.getInstance(document.getElementById('editMuridModal')).hide();
                    showNotification(data.message, data.success);
                    if (data.success) {
                        refreshTable();
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            });

            // Export User Data function
            window.exportUserData = function(format = 'pdf') {
                try {
                    // Build export URL
                    const exportUrl = '{{ route("users.export") }}?format=' + format;
                    
                    // Show loading message
                    const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
                    document.getElementById('notificationModalLabel').innerText = 'Export Data';
                    document.getElementById('notificationMessage').innerText = 'Sedang mengexport data user ke PDF...';
                    notificationModal.show();
                    
                    // Use fetch to get PDF blob (handles mixed content issues better)
                    fetch(exportUrl, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/pdf',
                        },
                        // Allow credentials if needed
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Gagal mengexport data. Status: ' + response.status);
                        }
                        return response.blob();
                    })
                    .then(blob => {
                        // Verify blob is valid
                        if (!blob || blob.size === 0) {
                            throw new Error('File PDF kosong atau tidak valid');
                        }
                        
                        // Verify it's actually a PDF
                        if (blob.type !== 'application/pdf' && !blob.type.includes('pdf')) {
                            console.warn('Unexpected content type:', blob.type);
                        }
                        
                        // Create blob URL
                        const blobUrl = window.URL.createObjectURL(blob);
                        
                        // Create download link
                        const link = document.createElement('a');
                        link.href = blobUrl;
                        link.download = 'data_user_' + new Date().toISOString().slice(0, 19).replace(/[:-]/g, '').replace('T', '_') + '.pdf';
                        link.style.display = 'none';
                        
                        // Append to body
                        document.body.appendChild(link);
                        
                        // Trigger download
                        link.click();
                        
                        // Clean up after download starts
                        setTimeout(() => {
                            document.body.removeChild(link);
                            window.URL.revokeObjectURL(blobUrl);
                            
                            // Hide loading and show success
                            notificationModal.hide();
                            showNotification('Export berhasil! File PDF akan terdownload.', true);
                        }, 1000);
                    })
                    .catch(error => {
                        console.error('Export error:', error);
                        notificationModal.hide();
                        
                        // Provide more specific error message
                        let errorMessage = 'Gagal mengexport data.';
                        if (error.message.includes('Failed to fetch') || error.message.includes('network')) {
                            errorMessage += ' Masalah koneksi atau server tidak dapat diakses.';
                        } else if (error.message.includes('mixed content') || error.message.includes('insecure')) {
                            errorMessage += ' Browser memblokir karena koneksi tidak aman. Silakan gunakan HTTPS atau izinkan mixed content di pengaturan browser.';
                        } else {
                            errorMessage += ' ' + error.message;
                        }
                        
                        showNotification(errorMessage, false);
                    });
                    
                } catch (error) {
                    console.error('Export error:', error);
                    showNotification('Gagal mengexport data: ' + (error.message || ''), false);
                }
            };

        });

        // Filter event listeners untuk Data User tab
        // Wait for both DOM and grid instance to be ready
        function setupUserFilters() {
            const roleFilter = document.getElementById('roleFilter');
            const classFilter = document.getElementById('classFilter');
            
            if (!roleFilter || !classFilter) {
                // Retry after a short delay if elements not found
                setTimeout(setupUserFilters, 100);
                return;
            }
            
            // Function to reload table with current filters
            function reloadUserTable() {
                if (!window.gridInstance || !window.gridInstance.updateConfig) {
                    console.log('Grid instance not ready yet');
                    return;
                }
                
                const roleValue = roleFilter.value || '';
                const classValue = classFilter.value || '';
                const params = new URLSearchParams();
                if (roleValue) params.append('role_filter', roleValue);
                if (classValue) params.append('class_filter', classValue);
                const url = params.toString() ? `/admin/users/table?${params.toString()}` : '/admin/users/table';
                
                console.log('Reloading table with URL:', url);
                
                window.gridInstance.updateConfig({
                    server: {
                        url: url,
                        then: (data) =>
                            data.map((u) => [
                                null, // checkbox
                                u.full_name ?? "-",
                                u.email ?? "-",
                                u.phone ?? "-",
                                u.status ?? "-",
                                u.id, // hidden ID
                                null, // Aksi
                            ]),
                    },
                }).forceRender();
            }
            
            // Event listener untuk filter role
            roleFilter.addEventListener('change', function() {
                console.log('Role filter changed to:', this.value);
                // Show/hide class filter based on role selection
                if (this.value === 'student') {
                    classFilter.style.display = 'inline-block';
                } else {
                    classFilter.style.display = 'none';
                    classFilter.value = ''; // Reset class filter
                }
                reloadUserTable();
            });

            // Event listener untuk filter kelas
            classFilter.addEventListener('change', function() {
                console.log('Class filter changed to:', this.value);
                reloadUserTable();
            });
        }
        
        // Try to setup filters immediately
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                // Wait a bit for grid to initialize
                setTimeout(setupUserFilters, 500);
            });
        } else {
            // DOM already loaded, wait for grid
            setTimeout(setupUserFilters, 500);
        }
        
        // Force apply mobile styles for Data User tab buttons
        function applyMobileButtonStyles() {
            if (window.innerWidth <= 575.98) {
                const singleActions = document.querySelector('#semua #single-actions');
                if (singleActions) {
                    singleActions.style.width = '100%';
                    singleActions.style.display = 'flex';
                    singleActions.style.justifyContent = 'center';
                    singleActions.style.alignItems = 'center';
                    singleActions.style.gap = '0.5rem';
                    singleActions.style.marginLeft = '0';
                    singleActions.style.marginRight = '0';
                    singleActions.style.marginTop = '1.5rem';
                    
                    // Apply to buttons
                    const buttons = singleActions.querySelectorAll('.btn, .btn-group');
                    buttons.forEach(btn => {
                        btn.style.flex = '0 0 auto';
                        btn.style.width = 'auto';
                        btn.style.minWidth = 'auto';
                        btn.style.maxWidth = 'none';
                    });
                }
            }
        }
        
        // Apply on load and resize
        window.addEventListener('load', applyMobileButtonStyles);
        window.addEventListener('resize', applyMobileButtonStyles);
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', applyMobileButtonStyles);
        } else {
            applyMobileButtonStyles();
        }
    </script>
@endsection

@section('styles')
<style>
    /* Mobile optimization for tabs */
    @media (max-width: 575.98px) {
        .nav-tabs .nav-link {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }
        
        .nav-tabs .nav-item {
            flex: 1;
        }
        
        .nav-tabs {
            display: flex;
            flex-wrap: nowrap;
        }
        
        /* Mobile optimization for action buttons */
        .card-header .d-flex {
            flex-direction: column;
            align-items: stretch !important;
            gap: 1rem;
        }
        
        /* Override untuk tab Data User - container tetap horizontal */
        #semua .card-header .d-flex,
        .tab-pane#semua .card-header .d-flex {
            align-items: center !important;
        }
        
        /* Tambahkan margin top untuk tombol action di tab Data User */
        #semua #single-actions,
        .tab-pane#semua #single-actions {
            margin-top: 1.5rem !important;
        }
        
        .card-header .text-muted {
            width: 100%;
            text-align: center;
        }
        
        /* Tab lain tetap seperti sebelumnya */
        #single-actions-murid {
            width: 100%;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
            margin-left: 0 !important;
        }
        
        #single-actions-guru {
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-left: 0 !important;
        }
        
        #single-actions-murid .btn {
            flex: 1;
            min-width: 0;
        }
        
        #single-actions-guru .btn {
            flex: 1;
            min-width: 0;
            max-width: 200px;
        }
        
        /* Khusus untuk tab Data User - tombol di tengah dan bersampingan */
        #semua #single-actions,
        .tab-pane#semua #single-actions,
        #semua #single-actions.ms-auto,
        .tab-pane#semua #single-actions.ms-auto {
            width: 100% !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            gap: 0.5rem !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            margin-inline-start: 0 !important;
            margin-inline-end: 0 !important;
        }
        
        #semua #single-actions .btn,
        .tab-pane#semua #single-actions .btn,
        #semua #single-actions .btn-group,
        .tab-pane#semua #single-actions .btn-group,
        #semua #single-actions .btn-group .btn,
        .tab-pane#semua #single-actions .btn-group .btn {
            flex: 0 0 auto !important;
            width: auto !important;
            min-width: auto !important;
            max-width: none !important;
        }
        
        #bulk-actions,
        #bulk-actions-guru,
        #bulk-actions-murid {
            width: 100%;
        }
        
        #bulk-actions .btn,
        #bulk-actions-guru .btn,
        #bulk-actions-murid .btn {
            width: 100%;
        }
    }
</style>
@endsection
