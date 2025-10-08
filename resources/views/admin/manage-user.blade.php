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
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addUserModal">
                                        Tambah User
                                    </button>
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
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addGuruModal">
                                        Tambah Guru
                                    </button>
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
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addMuridModal">
                                        Tambah Murid
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
@endsection

@section('scripts')
    @vite(['resources/js/admin/tabel.js'])
    @vite(['node_modules/select2/dist/js/select2.min.js'])

    <script>
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

            // LOGIKA TAMBAH USER
            const addUserForm = document.getElementById('addUserForm');
            addUserForm.addEventListener('submit', async function(event) {
                event.preventDefault();
                const formData = new FormData(addUserForm);

                try {
                    const response = await fetch(addUserForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const data = await response.json();
                    bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
                    showNotification(data.message, data.success);
                    if (data.success) {
                        addUserForm.reset();
                        refreshTable();
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            });

            // LOGIKA EDIT USER
            const editUserModal = document.getElementById('editUserModal');
            const editUserForm = document.getElementById('editUserForm');

            editUserModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const user = JSON.parse(button.getAttribute('data-user'));
                document.getElementById('editUserId').value = user.id;
                document.getElementById('editUserName').value = user.name;
                document.getElementById('editUserEmail').value = user.email;
                document.getElementById('editUserPhone').value = user.phone;
                document.getElementById('editUserUsername').value = user.username;
            });

            editUserForm.addEventListener('submit', async function(event) {
                event.preventDefault();
                const userId = document.getElementById('editUserId').value;
                const formData = new FormData(editUserForm);
                formData.append('_method', 'PUT'); // Method spoofing

                try {
                    const response = await fetch(`/admin/users/${userId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });
                    const data = await response.json();
                    bootstrap.Modal.getInstance(editUserModal).hide();
                    showNotification(data.message, data.success);
                    if (data.success) {
                        refreshTable();
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            });



            // Menambahkan kelas melalui dropdown
            fetch('/admin/classes') // Assuming there's a route for classes
                .then(response => response.json())
                .then(data => {
                    const addClassSelect = document.getElementById('addMuridClass');
                    const editClassSelect = document.getElementById('editMuridClass');
                    data.forEach(cls => {
                        const optionAdd = document.createElement('option');
                        optionAdd.value = cls.id;
                        optionAdd.textContent = cls.name;
                        addClassSelect.appendChild(optionAdd);

                        const optionEdit = document.createElement('option');
                        optionEdit.value = cls.id;
                        optionEdit.textContent = cls.name;
                        editClassSelect.appendChild(optionEdit);
                    });
                })
                .catch(error => console.error('Error:', error));

            // LOGIKA EDIT GURU
            const editGuruModal = document.getElementById('editGuruModal');
            const editGuruForm = document.getElementById('editGuruForm');

            editGuruModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const guru = JSON.parse(button.getAttribute('data-guru'));
                document.getElementById('editGuruId').value = guru.id;
                document.getElementById('editGuruUserName').value = guru.user_name || '';
                document.getElementById('editGuruNip').value = guru.nip || '';
                document.getElementById('editGuruDepartment').value = guru.department || '';
                document.getElementById('editGuruTitle').value = guru.title || '';
            });

            // LOGIKA EDIT MURID
            const editMuridModal = document.getElementById('editMuridModal');
            const editMuridForm = document.getElementById('editMuridForm');

            editMuridModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const murid = JSON.parse(button.getAttribute('data-murid'));
                document.getElementById('editMuridId').value = murid.id;
                document.getElementById('editMuridUserName').value = murid.user_name || '';
                document.getElementById('editMuridNis').value = murid.nis || '';
                document.getElementById('editMuridClass').value = murid.class_id || '';
                document.getElementById('editMuridGuardianName').value = murid.guardian_name || '';
                document.getElementById('editMuridGuardianPhone').value = murid.guardian_phone || '';
            });

            // LOGIKA HAPUS USER
            document.getElementById('confirmDeleteUserButton').addEventListener('click', async function() {
                const userId = document.getElementById('deleteUserId').value;

                try {
                    const response = await fetch(`/admin/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const data = await response.json();
                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
                    deleteModal.hide();
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
