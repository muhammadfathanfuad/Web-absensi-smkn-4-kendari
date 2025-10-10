import gridjs from "gridjs/dist/gridjs.umd.js";

// Helper untuk mendapatkan token CSRF, sudah benar.
const getCsrfToken = () => {
    return document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
};

// Fungsi notifikasi
function showNotification(message, isSuccess = true) {
    const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
    document.getElementById('notificationModalLabel').innerText = isSuccess ? 'Berhasil' : 'Gagal';
    document.getElementById('notificationMessage').innerText = message;
    notificationModal.show();
}

class GridDatatable {
    constructor() {
        this.gridInstanceGuru = null;
        this.gridInstanceMurid = null;
        this.gridInstanceUser = null;

        // Panggil method untuk inisialisasi semua yang dibutuhkan
        document.addEventListener("DOMContentLoaded", () => {
            this.initTables();
            this.initEventListeners();
        });
    }

    // Fungsi untuk menginisialisasi semua tabel
    initTables() {
        this.initGuruTable();
        this.initStudentTable();
        this.initUserTable();
    }

    // Fungsi untuk menginisialisasi tabel Guru
    initGuruTable() {
        const mount = document.getElementById("table-guru");
        if (!mount) return;

        this.gridInstanceGuru = new gridjs.Grid({
            columns: [
                {
                    name: gridjs.html('<input type="checkbox" id="select-all-guru-checkbox">'),
                    formatter: (cell, row) => {
                        const guru = row.cells[5].data;
                        return gridjs.html(`<input type="checkbox" class="row-checkbox-guru" data-id="${guru.user_id}">`);
                    }
                },
                "Name",
                "NIP",
                "Department",
                "Kode Guru",
                {
                    name: "Data",
                    hidden: true,
                },
                {
                    name: "Aksi",
                    formatter: (cell, row) => {
                        const guru = row.cells[5].data;

                        return gridjs.html(`
                            <div class="d-flex gap-2 justify-content-center">
                                <button class="btn btn-sm btn-outline-primary" onclick="showEditGuruModal('${guru.user_id}', '${guru.user.full_name || ''}', '${guru.user.email || ''}', '${guru.nip || ''}', '${guru.department || ''}', '${guru.kode_guru || ''}')">
                                    Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger delete-guru-btn" data-id="${
                                    guru.user_id
                                }">
                                    Hapus
                                </button>
                            </div>
                        `);
                    },
                },
            ],
            pagination: { limit: 10 },
            search: true,
            server: {
                url: "/admin/guru",
                then: (data) => data.map(teacher => [`<input type="checkbox" class="row-checkbox-guru" data-id="${teacher.user_id}">`, teacher.user?.full_name ?? '-', teacher.nip ?? '-', teacher.department ?? '-', teacher.kode_guru ?? '-', teacher]),
            },
            language: { search: { placeholder: " Ketik untuk mencari…" } },
        }).render(mount);

        // Ekspor instance untuk auto reload dari blade
        window.gridInstanceGuru = this.gridInstanceGuru;
    }

    // Fungsi untuk menginisialisasi tabel Murid
    initStudentTable() {
        const mount = document.getElementById("table-murid");
        if (!mount) return;

        this.gridInstanceMurid = new gridjs.Grid({
            columns: [
                "Name",
                "NIS",
                "Kelas",
                "Tingkatan",
                "Nama Wali",
                "Telepon Wali",
                {
                    name: "Data",
                    hidden: true,
                },
                {
                    name: "Aksi",
                    formatter: (cell, row) => {
                        const murid = row.cells[6].data;

                        return gridjs.html(`
                            <div class="d-flex gap-2 justify-content-center">
                                <button class="btn btn-sm btn-outline-primary" onclick="showEditMuridModal('${murid.user_id}', '${murid.user.full_name || ''}', '${murid.user.email || ''}', '${murid.nis || ''}', '${murid.class_id || ''}', '${murid.guardian_name || ''}', '${murid.guardian_phone || ''}', '${murid.classroom?.grade ?? ''}')">
                                    Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger delete-murid-btn" data-id="${
                                    murid.user_id
                                }">
                                    Hapus
                                </button>
                            </div>
                        `);
                    },
                },
            ],
            pagination: { limit: 10 },
            search: true,
            server: {
                url: "/admin/murid",
                then: (data) => data.map(student => [student.user?.full_name ?? '-', student.nis ?? '-', student.classroom?.name ?? '-', student.classroom?.grade ?? '-', student.guardian_name ?? '-', student.guardian_phone ?? '-', student]),
            },
            language: { search: { placeholder: " Ketik untuk mencari…" } },
        }).render(mount);
    }

    // Fungsi untuk menginisialisasi tabel User
    initUserTable() {
        const mount = document.getElementById("table-search");
        if (!mount) return;

        this.gridInstanceUser = new gridjs.Grid({
            columns: [
                {
                    name: gridjs.html('<input type="checkbox" id="select-all-checkbox">'),
                    formatter: (cell, row) => {
                        const userId = row.cells[6].data;
                        return gridjs.html(`<input type="checkbox" class="row-checkbox" data-id="${userId}">`);
                    }
                },
                "Name",
                "Email",
                "Nomor Hp",
                "Username",
                {
                    name: "Status",
                    formatter: (cell) => {
                        const statusClass =
                            cell === "active"
                                ? "btn btn-soft-success rounded-pill"
                                : "btn btn-soft-danger rounded-pill";
                        return gridjs.html(`
                            <div class="text-center">
                                <button class="${statusClass} btn-sm px-3 py-1 text-capitalize" disabled>
                                    ${cell}
                                </button>
                            </div>
                        `);
                    },
                },
                {
                    name: "ID",
                    hidden: true,
                },
                {
                    name: "Aksi",
                    formatter: (cell, row) => {
                        const user = {
                            id: row.cells[6].data,
                            name: row.cells[1].data,
                            email: row.cells[2].data,
                            phone: row.cells[3].data,
                            username: row.cells[4].data,
                            status: row.cells[5].data,
                        };
                        return gridjs.html(`
                            <div class="d-flex gap-2 justify-content-center">
                                <button class="btn btn-sm btn-outline-primary" onclick="showEditUserModal('${user.id}', '${user.name.replace(/"/g, '\\"')}', '${user.email.replace(/"/g, '\\"')}', '${user.phone.replace(/"/g, '\\"')}', '${user.username.replace(/"/g, '\\"')}', '${user.status}')">
                                    Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger delete-user-btn" data-id="${
                                    user.id
                                }">
                                    Hapus
                                </button>
                            </div>
                        `);
                    },
                },
            ],
            pagination: { limit: 10 },
            search: true,
            server: {
                url: "/admin/users/table",
                then: (data) =>
                    data.map((u) => [
                        `<input type="checkbox" class="row-checkbox" data-id="${u.id}">`,
                        u.full_name ?? "-",
                        u.email ?? "-",
                        u.phone ?? "-",
                        u.username ?? "-",
                        u.status ?? "-",
                        u.id,
                        u.role ?? "-",
                    ]),
            },
            language: { search: { placeholder: " Ketik untuk mencari…" } },
        }).render(mount);

        // Ekspor instance untuk auto reload dari blade
        window.gridInstance = this.gridInstanceUser;
    }

    initEventListeners() {
        this.handleFormSubmit("addGuruForm", () =>
            this.gridInstanceGuru.forceRender()
        );
        this.handleFormSubmit("addMuridForm", () =>
            this.gridInstanceMurid.forceRender()
        );
        this.handleFormSubmit("addUserForm", () =>
            this.gridInstanceUser.forceRender()
        );
        this.handleFormSubmit(
            "editGuruForm",
            () => this.gridInstanceGuru.forceRender(),
            "PUT"
        );
        this.handleFormSubmit(
            "editMuridForm",
            () => this.gridInstanceMurid.forceRender(),
            "PUT"
        );
        this.handleFormSubmit(
            "editUserForm",
            () => this.gridInstanceUser.forceRender(),
            "PUT"
        );

        // Checkbox functionality
        document.addEventListener('change', (event) => {
            if (event.target.classList.contains('row-checkbox') || event.target.id === 'select-all-checkbox') {
                const allCheckboxes = document.querySelectorAll('.row-checkbox');
                const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
                const selectAll = document.getElementById('select-all-checkbox');
                const singleActions = document.getElementById('single-actions');
                const bulkActions = document.getElementById('bulk-actions');

                // Update select all state
                if (selectAll) {
                    selectAll.checked = allCheckboxes.length > 0 && checkedBoxes.length === allCheckboxes.length;
                }

                // Toggle actions
                if (checkedBoxes.length > 0) {
                    if (singleActions) singleActions.style.display = 'none';
                    if (bulkActions) bulkActions.style.display = 'block';
                } else {
                    if (singleActions) singleActions.style.display = 'block';
                    if (bulkActions) bulkActions.style.display = 'none';
                }

                // If select all triggered
                if (event.target.id === 'select-all-checkbox') {
                    const isChecked = event.target.checked;
                    allCheckboxes.forEach(cb => cb.checked = isChecked);
                }
            }
        });

        // Bulk delete functionality
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', () => {
                const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.dataset.id);
                if (selectedIds.length === 0) return;

                // Show confirmation modal
                document.getElementById('deleteUserModalLabel').innerText = 'Konfirmasi Hapus';
                document.querySelector('#deleteUserModal .modal-body p').innerText = `Apakah Anda yakin ingin menghapus ${selectedIds.length} user yang dipilih?`;
                document.getElementById('deleteUserId').value = selectedIds.join(',');
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
                deleteModal.show();
            });
        }

        // Bulk edit functionality
        const bulkEditBtn = document.getElementById('bulkEditBtn');
        if (bulkEditBtn) {
            bulkEditBtn.addEventListener('click', () => {
                const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.dataset.id);
                if (selectedIds.length === 0) return;

                // Show bulk edit status modal
                const modal = new bootstrap.Modal(document.getElementById('bulkEditStatusModal'));
                modal.show();
            });
        }

        // Confirm bulk edit status
        const confirmBulkEditStatus = document.getElementById('confirmBulkEditStatus');
        if (confirmBulkEditStatus) {
            confirmBulkEditStatus.addEventListener('click', () => {
                const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.dataset.id);
                const newStatus = document.getElementById('bulkStatusSelect').value;

                const formData = new FormData();
                selectedIds.forEach(id => formData.append('ids[]', id));

                const url = newStatus === 'active' ? '/admin/users/bulk-status-active' : '/admin/users/bulk-status-suspended';

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken(),
                    },
                    body: formData,
                }).then(res => res.json()).then(data => {
                    if (data.errors) {
                        data.message = 'Error: ' + Object.values(data.errors).flat().join(', ');
                        data.success = false;
                    }
                    showNotification(data.message, data.success);
                    if (data.success) {
                        const route = document.getElementById('deleteRoute').value;
                        if (route === '/admin/user') {
                            this.gridInstanceUser.forceRender();
                            // Reset actions
                            document.getElementById('single-actions').style.display = 'block';
                            document.getElementById('bulk-actions').style.display = 'none';
                        } else if (route === '/admin/guru') {
                            this.gridInstanceGuru.forceRender();
                            // Reset actions
                            document.getElementById('single-actions-guru').style.display = 'block';
                            document.getElementById('bulk-actions-guru').style.display = 'none';
                        }
                    }
                }).catch(error => {
                    console.error(error);
                    showNotification('Gagal mengupdate status', false);
                });

                bootstrap.Modal.getInstance(document.getElementById('bulkEditStatusModal')).hide();
            });
        }

        // Guru bulk actions
        document.addEventListener('change', (event) => {
            if (event.target.classList.contains('row-checkbox-guru') || event.target.id === 'select-all-guru-checkbox') {
                const allCheckboxes = document.querySelectorAll('.row-checkbox-guru');
                const checkedBoxes = document.querySelectorAll('.row-checkbox-guru:checked');
                const selectAll = document.getElementById('select-all-guru-checkbox');
                const bulkActions = document.getElementById('bulk-actions-guru');

                // Update select all state
                if (selectAll) {
                    selectAll.checked = allCheckboxes.length > 0 && checkedBoxes.length === allCheckboxes.length;
                }

                // Toggle actions
                if (checkedBoxes.length > 0) {
                    if (document.getElementById('single-actions-guru')) document.getElementById('single-actions-guru').style.display = 'none';
                    if (bulkActions) bulkActions.style.display = 'block';
                } else {
                    if (document.getElementById('single-actions-guru')) document.getElementById('single-actions-guru').style.display = 'block';
                    if (bulkActions) bulkActions.style.display = 'none';
                }

                // If select all triggered
                if (event.target.id === 'select-all-guru-checkbox') {
                    const isChecked = event.target.checked;
                    allCheckboxes.forEach(cb => cb.checked = isChecked);
                }
            }
        });

        const bulkDeleteGuruBtn = document.getElementById('bulkDeleteGuruBtn');
        if (bulkDeleteGuruBtn) {
            bulkDeleteGuruBtn.addEventListener('click', () => {
                const selectedIds = Array.from(document.querySelectorAll('.row-checkbox-guru:checked')).map(cb => cb.dataset.id);
                if (selectedIds.length === 0) return;

                // Show confirmation modal, reuse deleteUserModal
                document.getElementById('deleteUserModalLabel').innerText = 'Konfirmasi Hapus Guru';
                document.querySelector('#deleteUserModal .modal-body p').innerText = `Apakah Anda yakin ingin menghapus ${selectedIds.length} guru yang dipilih?`;
                document.getElementById('deleteUserId').value = selectedIds.join(',');
                document.getElementById('deleteRoute').value = '/admin/guru';
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
                deleteModal.show();
            });
        }

        document.body.addEventListener("click", (event) => {
            if (event.target.classList.contains("delete-guru-btn")) {
                const guruId = event.target.dataset.id;
                document.getElementById("deleteGuruId").value = guruId;
                const deleteModal = new bootstrap.Modal(
                    document.getElementById("deleteGuruModal")
                );
                deleteModal.show();
            }
            if (event.target.classList.contains("delete-murid-btn")) {
                const muridId = event.target.dataset.id;
                document.getElementById("deleteMuridId").value = muridId;
                const deleteModal = new bootstrap.Modal(
                    document.getElementById("deleteMuridModal")
                );
                deleteModal.show();
            }
            if (event.target.classList.contains("delete-user-btn")) {
                const userId = event.target.dataset.id;
                document.getElementById("deleteUserId").value = userId;
                document.getElementById('deleteUserModalLabel').innerText = 'Konfirmasi Hapus User';
                document.querySelector('#deleteUserModal .modal-body p').innerText = 'Apakah Anda yakin ingin menghapus user ini?';
                document.getElementById('deleteRoute').value = '/admin/user';
                const deleteModal = new bootstrap.Modal(
                    document.getElementById("deleteUserModal")
                );
                deleteModal.show();
            }
        });

        this.handleDelete(
            "confirmDeleteGuruButton",
            "deleteGuruId",
            "/admin/guru",
            () => this.gridInstanceGuru.forceRender()
        );
        this.handleDelete(
            "confirmDeleteMuridButton",
            "deleteMuridId",
            "/admin/murid",
            () => this.gridInstanceMurid.forceRender()
        );
        // Modified handleDelete for bulk
        const confirmDeleteUserButton = document.getElementById("confirmDeleteUserButton");
        if (confirmDeleteUserButton) {
            confirmDeleteUserButton.addEventListener("click", () => {
                const resourceIds = document.getElementById("deleteUserId").value.split(',');
                const baseUrl = document.getElementById("deleteRoute").value;

                const promises = resourceIds.map(resourceId =>
                    fetch(`${baseUrl}/${resourceId}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": getCsrfToken(),
                            Accept: "application/json",
                        },
                    }).then(res => res.json()).then(data => {
                        if (data.errors) {
                            data.message = 'Error: ' + Object.values(data.errors).flat().join(', ');
                            data.success = false;
                        }
                        return data;
                    }).catch(error => {
                        console.error(error);
                        return { success: false, message: 'Gagal menghapus user' };
                    })
                );

                Promise.all(promises).then(results => {
                    const successes = results.filter(r => r.success !== false).length;
                    const failures = results.length - successes;
                    if (failures === 0) {
                        showNotification(`Berhasil menghapus ${successes} user`, true);
                    } else if (successes === 0) {
                        showNotification(`Gagal menghapus ${failures} user`, false);
                    } else {
                        showNotification(`Berhasil menghapus ${successes} user, gagal ${failures}`, true);
                    }
                    if (successes > 0) {
                        this.gridInstanceUser.forceRender();
                        // Reset actions after render
                        document.getElementById('single-actions').style.display = 'block';
                        document.getElementById('bulk-actions').style.display = 'none';
                        // Uncheck checkboxes
                        document.querySelectorAll('.row-checkbox-guru').forEach(cb => cb.checked = false);
                        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
                        document.getElementById('select-all-checkbox').checked = false;
                        document.getElementById('select-all-guru-checkbox').checked = false;
                    }
                });

                const modal = confirmDeleteUserButton.closest(".modal");
                if (modal) {
                    bootstrap.Modal.getInstance(modal).hide();
                }
            });
        }
    }

    handleFormSubmit(formId, callback, method = "POST") {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener("submit", (event) => {
            event.preventDefault(); // Pastikan form tidak melakukan reload default
            const formData = new FormData(form);
            let url = form.action;
            let fetchMethod = "POST";

            if (method === "PUT") {
                formData.append("_method", "PUT");
                const resourceId = form.querySelector(
                    'input[type="hidden"][name="id"]'
                ).value;
                url = `${form.dataset.action}/${resourceId}`;
            }

            fetch(url, {
                method: fetchMethod,
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": getCsrfToken(),
                    Accept: "application/json",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.errors) {
                        data.message = 'Validasi gagal: ' + Object.values(data.errors).flat().join(', ');
                        data.success = false;
                    }
                    const modal = form.closest(".modal");
                    if (modal) {
                        bootstrap.Modal.getInstance(modal).hide();
                    }

                    showNotification(data.message, data.success);
                    if (data.success) {
                        form.reset();
                        callback(); // Panggil forceRender()
                    }
                })
                .catch(console.error);
        });
    }

    handleDelete(buttonId, inputId, baseUrl, callback) {
        const confirmButton = document.getElementById(buttonId);
        if (!confirmButton) return;

        confirmButton.addEventListener("click", () => {
            const resourceId = document.getElementById(inputId).value;
            fetch(`${baseUrl}/${resourceId}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": getCsrfToken(),
                    Accept: "application/json",
                },
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.errors) {
                        data.message = 'Error: ' + Object.values(data.errors).flat().join(', ');
                        data.success = false;
                    }
                    const modal = confirmButton.closest(".modal");
                    if (modal) {
                        bootstrap.Modal.getInstance(modal).hide();
                    }

                    showNotification(data.message, data.success);
                    if (data.success) {
                        callback(); // Panggil forceRender()
                    }
                })
                .catch(console.error);
        });
    }
}

// Inisialisasi class
new GridDatatable();
