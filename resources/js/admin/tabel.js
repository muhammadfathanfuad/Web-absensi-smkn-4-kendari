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
                        const guru = row.cells[4].data;

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
                then: (data) => data.map(teacher => [teacher.user?.full_name ?? '-', teacher.nip ?? '-', teacher.department ?? '-', teacher.kode_guru ?? '-', teacher]),
            },
            language: { search: { placeholder: " Ketik untuk mencari…" } },
        }).render(mount);
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

        // Select all checkbox functionality
        document.addEventListener('change', (event) => {
            if (event.target.id === 'select-all-checkbox') {
                const isChecked = event.target.checked;
                document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = isChecked);
            }
        });

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
        this.handleDelete(
            "confirmDeleteUserButton",
            "deleteUserId",
            "/admin/user",
            () => this.gridInstanceUser.forceRender()
        );
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
