<?php $__env->startSection('css'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['node_modules/gridjs/dist/theme/mermaid.min.css']); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', ['title' => 'Manage User', 'subtitle' => 'Users'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row">
        <div class="card">
            <div class="card-header ">
                <h5 class="card-title">Data User</h5>
                <div class="d-flex justify-content-between align-items-center mb-0">
                    <p class="text-muted mb-0">
                        Data Guru dan siswa
                    </p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        Tambah User
                    </button>

                </div>

            </div>

            <div class="card-body">
                <div id="table-search"></div>
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
                            <input type="text" class="form-control" id="addUserPhone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="addUserUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="addUserUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="addUserPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="addUserPassword" name="password" required>
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
                    <form id="editUserForm" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?> <!-- Menyertakan method PUT untuk update -->
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
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/admin/tabel.js']); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editUserModal = document.getElementById('editUserModal');
            if (editUserModal) {
                editUserModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    // Ekstrak data dari atribut data-user
                    const user = JSON.parse(button.getAttribute('data-user'));

                    // Dapatkan elemen form
                    const form = document.getElementById('editUserForm');

                    // Set action URL untuk form submission
                    form.action = `/admin/users/${user.id}`;

                    // Isi form di dalam modal
                    const modalBody = editUserModal.querySelector('.modal-body');
                    modalBody.querySelector('#editUserId').value = user.id;
                    modalBody.querySelector('#editUserName').value = user.name;
                    modalBody.querySelector('#editUserEmail').value = user.email;
                    modalBody.querySelector('#editUserPhone').value = user.phone;
                    modalBody.querySelector('#editUserUsername').value = user.username;
                });
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical-admin', ['subtitle' => 'manage-user'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/admin/user/manage-user.blade.php ENDPATH**/ ?>