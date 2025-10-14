@extends('layouts.vertical-admin', ['subtitle' => 'manajemen-pengguna'])

@section('css')
    @vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
    @vite(['node_modules/select2/dist/css/select2.min.css'])
@endsection

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Manajemen', 'subtitle' => 'Manajemen Pengguna'])

    <div class="row">
        <div class="card">
            <div class="col-lg-0">
                <div class="card-body">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a href="#semua" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                <span class="d-block d-sm-none"><i class="bx bx-home"></i></span>
                                <span class="d-none d-sm-block">Semua</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#guru" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                <span class="d-block d-sm-none"><i class="bx bx-user"></i></span>
                                <span class="d-none d-sm-block">Guru</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#murid" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                <span class="d-block d-sm-none"><i class="bx bx-envelope"></i></span>
                                <span class="d-none d-sm-block">Murid</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content text-muted">
                        <div class="tab-pane show active" id="semua">
                            <div class="card-header">
                                <h5 class="card-title">Data User</h5>
                                <div class="d-flex justify-content-between align-items-center mb-0">
                                    <p class="text-muted mb-0">
                                        Data Semua Warga Sekolah
                                    </p>
                                    <div id="single-actions">
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
                                <div class="d-flex justify-content-between align-items-center mb-0">
                                    <p class="text-muted mb-0">
                                        Data Semua Guru Sekolah
                                    </p>
                                    <div id="single-actions-guru">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addGuruModal">
                                            Tambah Guru
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
                                <div class="d-flex justify-content-between align-items-center mb-0">
                                    <p class="text-muted mb-0">
                                        Data Semua Murid Sekolah
                                    </p>
                                    <div id="single-actions-murid">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addMuridModal">
                                            Tambah Murid
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
                            <label for="addGuruDepartment" class="form-label">Department</label>
                            <input type="text" class="form-control" id="addGuruDepartment" name="department" required>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#uploadGuruModal">Upload Data</button>
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
                            <small class="form-text text-muted">Format file: Excel (.xlsx, .xls) atau CSV (.csv). Header kolom: Kode Guru, Nama Guru, NIP, Email, No Hp, Department.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
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
                            <small class="form-text text-muted">Format file: Excel (.xlsx, .xls) atau CSV (.csv). Header kolom: nama_murid, email, phone, nis, kelas, grade, nama_wali, nomor_hp_wali.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
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

    <!-- Modal Delete Murid -->
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
@endsection

@section('scripts')
    <script>
        window.baseUrl = "{{ request()->getSchemeAndHttpHost() . request()->getBasePath() }}";
    </script>
    @vite(['resources/js/admin/tabel.js'])
    @vite(['node_modules/select2/dist/js/select2.min.js'])

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
            const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));

            // Ensure close buttons work
            document.querySelector('#notificationModal .btn-close').addEventListener('click', () => {
                notificationModal.hide();
            });
            document.querySelector('#notificationModal .btn-light').addEventListener('click', () => {
                notificationModal.hide();
            });

            function showNotification(message, isSuccess = true) {
                document.getElementById('notificationModalLabel').innerText = isSuccess ? 'Berhasil' : 'Gagal';
                document.getElementById('notificationMessage').innerText = message;
                notificationModal.show();
            }

            function refreshTable() {
                if (window.gridInstance) {
                    window.gridInstance.forceRender();
                }
            }

            // const addUserFormHTML = `
            //     <form id="addUserForm" action="{{ route('users.store') }}" method="POST">
            //         @csrf
            //         <div class="mb-3">
            //             <label for="addUserName" class="form-label">Nama</label>
            //             <input type="text" class="form-control" id="addUserName" name="name" required>
            //         </div>
            //         <div class="mb-3">
            //             <label for="addUserEmail" class="form-label">Email</label>
            //             <input type="email" class="form-control" id="addUserEmail" name="email" required>
            //         </div>
            //         <div class="mb-3">
            //             <label for="addUserPhone" class="form-label">Nomor Hp</label>
            //             <input type="text" class="form-control" id="addUserPhone" name="phone">
            //         </div>
            //             <div class="mb-3">
            //                 <label for="addUserUsername" class="form-label">Username</label>
            //                 <input type="text" class="form-control" id="addUserUsername" name="username">
            //             </div>
            //         <div class="mb-3">
            //             <label for="addUserPassword" class="form-label">Password</label>
            //             <input type="password" class="form-control" id="addUserPassword" name="password">
            //         </div>
            //         <button type="submit" class="btn btn-primary">Tambah User</button>
            //     </form>
            // `;

            function setAddUserModal() {
                document.getElementById('addUserModalLabel').innerText = 'Tambah User';
                document.querySelector('#addUserModal .modal-body').innerHTML = addUserFormHTML;
                // Add submit handler
                document.getElementById('addUserForm').addEventListener('submit', async function(event) {
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
                            refreshTable();
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showNotification('Gagal menambahkan user. Periksa data atau koneksi.', false);
                    }
                });
            }

            document.getElementById('addUserBtn').addEventListener('click', setAddUserModal);

            // Upload Guru form submission
            document.getElementById('uploadGuruForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.textContent;
                submitButton.disabled = true;
                submitButton.textContent = 'Mengupload...';
                fetch(this.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    showNotification(data.message, data.success);
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('uploadGuruModal')).hide();
                        this.reset();
                        // Refresh table
                        if (window.gridInstanceGuru) window.gridInstanceGuru.forceRender();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Gagal mengupload data.', false);
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
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

            // Upload Murid form submission
            document.getElementById('uploadMuridForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.textContent;
                submitButton.disabled = true;
                submitButton.textContent = 'Mengupload...';
                fetch(this.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    showNotification(data.message, data.success);
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('uploadMuridModal')).hide();
                        this.reset();
                        // Refresh table
                        if (window.gridInstance) window.gridInstance.forceRender();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Gagal mengupload data.', false);
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
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

        });
    </script>
@endsection
