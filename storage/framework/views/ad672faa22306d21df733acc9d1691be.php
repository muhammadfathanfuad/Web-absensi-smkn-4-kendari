<?php $__env->startSection('css'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['node_modules/gridjs/dist/theme/mermaid.min.css']); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['node_modules/select2/dist/css/select2.min.css']); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/admin/manage-user.css']); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'Manajemen Pengguna'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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
                                                <?php $__currentLoopData = $classrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($classroom->id); ?>"><?php echo e($classroom->grade); ?> - <?php echo e($classroom->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <form id="addGuruForm" action="<?php echo e(route('guru.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
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
                                <?php $__currentLoopData = \App\Models\Subject::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($subject->name); ?>"><?php echo e($subject->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <form id="uploadGuruForm" action="<?php echo e(route('guru.import')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
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
                    <form id="addMuridForm" action="<?php echo e(route('murid.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
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
                                <?php $__currentLoopData = \App\Models\Classroom::distinct('grade')->pluck('grade'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($grade); ?>"><?php echo e($grade); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="addMuridClass" class="form-label">Kelas</label>
                            <select class="form-select" id="addMuridClass" name="class_id" required>
                                <?php $__currentLoopData = \App\Models\Classroom::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>" data-grade="<?php echo e($class->grade); ?>"><?php echo e($class->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <form id="uploadMuridForm" action="<?php echo e(route('murid.import')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
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
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
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
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
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
                                <?php $__currentLoopData = \App\Models\Classroom::distinct('grade')->pluck('grade'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($grade); ?>"><?php echo e($grade); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editMuridClass" class="form-label">Kelas</label>
                            <select class="form-select" id="editMuridClass" name="class_id" required>
                                <?php $__currentLoopData = \App\Models\Classroom::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>" data-grade="<?php echo e($class->grade); ?>"><?php echo e($class->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <form id="addUserForm" action="<?php echo e(route('users.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
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

    
    <?php echo $__env->make('components.pengaturan.notification-modal', [
        'modalId' => 'notificationModal',
        'modalLabelId' => 'notificationModalLabel',
        'messageId' => 'notificationMessage',
        'errorsId' => 'notificationErrors',
        'errorsBodyId' => 'notificationErrorsBody',
        'modalSize' => 'modal-lg',
        'showErrorsTable' => true
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        window.baseUrl = "<?php echo e(request()->getSchemeAndHttpHost() . request()->getBasePath()); ?>";
        window.exportUserUrl = "<?php echo e(route('users.export')); ?>";
    </script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/admin/tabel.js']); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/admin/select2-init.js']); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/admin/manage-user.js']); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical-admin', ['subtitle' => 'manajemen-pengguna'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/admin/manage-user.blade.php ENDPATH**/ ?>