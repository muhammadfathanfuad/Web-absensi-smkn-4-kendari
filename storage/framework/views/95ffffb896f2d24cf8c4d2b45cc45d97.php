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
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <div id="notificationModal" class="modal fade" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-top">
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
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/admin/tabel.js']); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Inisialisasi & Fungsi Bantuan ---
            const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
            
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

            // --- LOGIKA TAMBAH USER (Diperbarui agar konsisten) ---
            const addUserForm = document.getElementById('addUserForm');
            addUserForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(addUserForm);

                fetch(addUserForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' }
                })
                .then(response => response.json())
                .then(data => {
                    bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
                    showNotification(data.message, data.success); // Pakai fungsi notifikasi standar
                    if (data.success) {
                        addUserForm.reset();
                        refreshTable(); // Aktifkan refresh tabel
                    }
                }).catch(console.error);
            });

            // --- LOGIKA EDIT USER (Diperbaiki) ---
            const editUserModal = document.getElementById('editUserModal');
            const editUserForm = document.getElementById('editUserForm');

            // Mengisi form (tidak berubah)
            editUserModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const user = JSON.parse(button.getAttribute('data-user'));
                document.getElementById('editUserId').value = user.id;
                document.getElementById('editUserName').value = user.name;
                document.getElementById('editUserEmail').value = user.email;
                document.getElementById('editUserPhone').value = user.phone;
                document.getElementById('editUserUsername').value = user.username;
            });

            // Submit form edit
            editUserForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const userId = document.getElementById('editUserId').value;
                const formData = new FormData(editUserForm);
                formData.append('_method', 'PUT'); // Method spoofing

                fetch(`/admin/users/${userId}`, {
                    method: 'POST', // **PERBAIKAN: Harus 'POST' untuk method spoofing**
                    headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    bootstrap.Modal.getInstance(editUserModal).hide();
                    showNotification(data.message, data.success);
                    if (data.success) {
                        refreshTable();
                    }
                }).catch(console.error);
            });

            // --- LOGIKA HAPUS USER (Sudah benar, hanya dirapikan) ---
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
            
            document.getElementById('table-search').addEventListener('click', function(event) {
                if (event.target.matches('.delete-btn')) {
                    const userId = event.target.getAttribute('data-id');
                    document.getElementById('deleteUserId').value = userId;
                    deleteModal.show();
                }
            });

            document.getElementById('confirmDeleteButton').addEventListener('click', function() {
                const userId = document.getElementById('deleteUserId').value;
                fetch(`/admin/users/${userId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' }
                })
                .then(res => res.json())
                .then(data => {
                    deleteModal.hide();
                    showNotification(data.message, data.success);
                    if (data.success) {
                        refreshTable();
                    }
                }).catch(console.error);
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vertical-admin', ['subtitle' => 'manage-user'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/admin/user/manage-user.blade.php ENDPATH**/ ?>