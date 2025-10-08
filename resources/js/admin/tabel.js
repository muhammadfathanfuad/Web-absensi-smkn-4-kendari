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
                "Title",
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
                                <button class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editGuruModal"
                                    data-guru='${JSON.stringify(guru)}'>
                                    Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger delete-guru-btn" data-id="${
                                    guru.id
                                }">
                                    Hapus
                                </button>
                            </div>
                        `);
                    },
                },
            ],
            pagination: { limit: 5 },
            search: true,
            server: {
                url: "/admin/guru",
                then: (data) => data,
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
                "Nama Wali",
                "Telepon Wali",
                {
                    name: "Data",
                    hidden: true,
                },
                {
                    name: "Aksi",
                    formatter: (cell, row) => {
                        const murid = row.cells[5].data;

                        return gridjs.html(`
                            <div class="d-flex gap-2 justify-content-center">
                                <button class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editMuridModal"
                                    data-murid='${JSON.stringify(murid)}'>
                                    Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger delete-murid-btn" data-id="${
                                    murid.id
                                }">
                                    Hapus
                                </button>
                            </div>
                        `);
                    },
                },
            ],
            pagination: { limit: 5 },
            search: true,
            server: {
                url: "/admin/murid",
                then: (data) => data,
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
                            id: row.cells[5].data,
                            name: row.cells[0].data,
                            email: row.cells[1].data,
                            phone: row.cells[2].data,
                            username: row.cells[3].data,
                            status: row.cells[4].data,
                        };
                        return gridjs.html(`
                            <div class="d-flex gap-2 justify-content-center">
                                <button class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editUserModal"
                                    data-user='${JSON.stringify(user)}'>
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
            pagination: { limit: 5 },
            search: true,
            server: {
                url: "/admin/users/table",
                then: (data) =>
                    data.map((u) => [
                        u.full_name ?? "-",
                        u.email ?? "-",
                        u.phone ?? "-",
                        u.username ?? "-",
                        u.status ?? "-",
                        u.id,
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
            "/admin/users",
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
