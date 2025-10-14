<?php $__env->startSection('content'); ?>
<?php $__env->startSection('css'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['node_modules/gridjs/dist/theme/mermaid.min.css']); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['node_modules/select2/dist/css/select2.min.css']); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.partials.page-title', ['title' => 'jadwal', 'subtitle' => 'jadwal pelajaran'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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
                            <a href="#kelasX" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                <span class="d-block d-sm-none"><i class="bx bx-user"></i></span>
                                <span class="d-none d-sm-block">Kelas X</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#kelasXI" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                <span class="d-block d-sm-none"><i class="bx bx-envelope"></i></span>
                                <span class="d-none d-sm-block">Kelas XI</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#kelasXII" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                <span class="d-block d-sm-none"><i class="bx bx-envelope"></i></span>
                                <span class="d-none d-sm-block">Kelas XII</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content text-muted">
                        <div class="tab-pane show active" id="semua">
                            <div class="card-header">
                                <h5 class="card-title">Jadwal Pelajaran Semester ini</h5>
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
                        </div>
                        <div class="tab-pane" id="kelasX">
                            <div class="card-header">
                                <h5 class="card-title">Jadwal Pelajaran kelas X</h5>
                                <div class="d-flex justify-content-between align-items-center mb-0">
                                    <p class="text-muted mb-0">
                                        Jadwal Pelajaran Semester 1
                                    </p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addGuruModal">
                                        Tambah Jadwal
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                <div id="table-guru"></div>
                            </div>
                        </div>
                        <div class="tab-pane" id="kelasXI">
                            <div class="card-header">
                                <h5 class="card-title">Jadwal Pelajaran kelas XI</h5>
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
                        <div class="tab-pane" id="kelasXII">
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

    <!-- Modal Upload Mata Pelajaran -->
    <div class="modal fade" id="uploadSubjectModal" tabindex="-1" aria-labelledby="uploadSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadSubjectModalLabel">Upload Mata Pelajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadSubjectForm" action="<?php echo e(route('subjects.store')); ?>" method="POST" enctype="multipart/form-data">
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

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/admin/tabel-jadwal.js']); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical-admin', ['subtitle' => 'Jadwal Pelajaran'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views\admin\jadwal-pelajaran.blade.php ENDPATH**/ ?>