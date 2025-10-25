import gridjs from "gridjs/dist/gridjs.umd.js";

// Helper CSRF
const getCsrfToken = () => {
    const el = document.querySelector('meta[name="csrf-token"]');
    return el ? el.getAttribute("content") : "";
};

// Notifikasi (modal)
function showNotification(message, isSuccess = true) {
    const notificationModal = new bootstrap.Modal(
        document.getElementById("notificationModal")
    );
    document.getElementById("notificationModalLabel").innerText = isSuccess
        ? "Berhasil"
        : "Gagal";
    document.getElementById("notificationMessage").innerText = message;
    notificationModal.show();
}

class GridDatatable {
    constructor() {
        this.gridInstanceGuru = null;
        this.gridInstanceMurid = null;
        this.gridInstanceUser = null;

        document.addEventListener("DOMContentLoaded", () => {
            this.initTables();
            this.initEventListeners();
        });
    }

    // ---------- INIT ALL TABLES ----------
    initTables() {
        this.initGuruTable();
        this.initStudentTable();
        this.initUserTable();
    }

    // ---------- GURU ----------
    initGuruTable() {
        const mount = document.getElementById("table-guru");
        if (!mount) return;

        this.gridInstanceGuru = new gridjs.Grid({
            columns: [
                {
                    name: gridjs.html(
                        '<input type="checkbox" id="select-all-guru-checkbox">'
                    ),
                    formatter: (cell, row) => {
                        const guru = row.cells[5].data; // objek guru
                        return gridjs.html(
                            `<input type="checkbox" class="row-checkbox-guru" data-id="${guru.user_id}">`
                        );
                    },
                },
                "Name",
                "NIP",
                "Department",
                "Kode Guru",
                { name: "Data", hidden: true },
                {
                    name: "Aksi",
                    formatter: (cell, row) => {
                        const guru = row.cells[5].data; // objek guru
                        return gridjs.html(`
              <div class="d-flex gap-2 justify-content-center">
                <button class="btn btn-sm btn-outline-primary"
                  onclick="showEditGuruModal(
                    '${guru.user_id}',
                    '${(guru.user?.full_name || "").replace(/"/g, '\\"')}',
                    '${(guru.user?.email || "").replace(/"/g, '\\"')}',
                    '${(guru.nip || "").replace(/"/g, '\\"')}',
                    '${(guru.department || "").replace(/"/g, '\\"')}',
                    '${(guru.kode_guru || "").replace(/"/g, '\\"')}'
                  )">
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
            language: {
                search: { placeholder: " Ketik untuk mencari…" },
                error: "Gagal mengambil data",
            },
            server: {
                url: "/admin/guru",
                then: (data) =>
                    data.map((teacher) => [
                        null, // checkbox (formatter)
                        teacher.user?.full_name ?? "-",
                        teacher.nip ?? "-",
                        teacher.department ?? "-",
                        teacher.kode_guru ?? "-",
                        teacher, // Data (hidden) → dipakai formatter
                        null, // Aksi (formatter)
                    ]),
            },
        }).render(mount);

        // Select all guru
        const selectAllGuru = document.getElementById(
            "select-all-guru-checkbox"
        );
        if (selectAllGuru) {
            selectAllGuru.addEventListener("change", () => {
                const isChecked = selectAllGuru.checked;
                document
                    .querySelectorAll(".row-checkbox-guru")
                    .forEach((cb) => (cb.checked = isChecked));
                // Update bulk actions
                const checkedBoxes = document.querySelectorAll(
                    ".row-checkbox-guru:checked"
                );
                const singleActions = document.getElementById(
                    "single-actions-guru"
                );
                const bulkActions =
                    document.getElementById("bulk-actions-guru");
                if (checkedBoxes.length > 0) {
                    if (singleActions) singleActions.style.display = "none";
                    if (bulkActions) bulkActions.style.display = "block";
                } else {
                    if (singleActions) singleActions.style.display = "block";
                    if (bulkActions) bulkActions.style.display = "none";
                }
            });
        }

        // Untuk akses dari blade
        window.gridInstanceGuru = this.gridInstanceGuru;
    }

    // ---------- MURID ----------
    initStudentTable() {
        const mount = document.getElementById("table-murid");
        if (!mount) return;

        this.gridInstanceMurid = new gridjs.Grid({
            columns: [
                {
                    name: gridjs.html(
                        '<input type="checkbox" id="select-all-murid-checkbox">'
                    ),
                    formatter: (cell, row) => {
                        const murid = row.cells[7].data;
                        return gridjs.html(
                            `<input type="checkbox" class="row-checkbox-murid" data-id="${murid.user_id}">`
                        );
                    },
                },
                "Name",
                "NIS",
                "Kelas",
                "Tingkatan",
                "Nama Wali",
                "Telepon Wali",
                { name: "Data", hidden: true },
                {
                    name: "Aksi",
                    formatter: (cell, row) => {
                        const murid = row.cells[7].data;
                        return gridjs.html(`
              <div class="d-flex gap-2 justify-content-center">
                <button class="btn btn-sm btn-outline-primary"
                  onclick="showEditMuridModal(
                    '${murid.user_id}',
                    '${(murid.user?.full_name || "").replace(/"/g, '\\"')}',
                    '${(murid.user?.email || "").replace(/"/g, '\\"')}',
                    '${(murid.nis || "").replace(/"/g, '\\"')}',
                    '${murid.class_id || ""}',
                    '${(murid.guardian_name || "").replace(/"/g, '\\"')}',
                    '${(murid.guardian_phone || "").replace(/"/g, '\\"')}',
                    '${murid.classroom?.grade ?? ""}'
                  )">
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
            language: {
                search: { placeholder: " Ketik untuk mencari…" },
                error: "Gagal mengambil data",
            },
            server: {
                url: "/admin/murid",
                then: (data) =>
                    data.map((student) => [
                        null, // checkbox
                        student.user?.full_name ?? "-",
                        student.nis ?? "-",
                        student.classroom?.name ?? "-",
                        student.classroom?.grade ?? "-",
                        student.guardian_name ?? "-",
                        student.guardian_phone ?? "-",
                        student, // Data (hidden)
                        null, // Aksi
                    ]),
            },
        }).render(mount);

        // Select all murid
        const selectAllMurid = document.getElementById(
            "select-all-murid-checkbox"
        );
        if (selectAllMurid) {
            selectAllMurid.addEventListener("change", () => {
                const isChecked = selectAllMurid.checked;
                document
                    .querySelectorAll(".row-checkbox-murid")
                    .forEach((cb) => (cb.checked = isChecked));
                // Update bulk actions
                const checkedBoxes = document.querySelectorAll(
                    ".row-checkbox-murid:checked"
                );
                const singleActions = document.getElementById(
                    "single-actions-murid"
                );
                const bulkActions =
                    document.getElementById("bulk-actions-murid");
                if (checkedBoxes.length > 0) {
                    if (singleActions) singleActions.style.display = "none";
                    if (bulkActions) bulkActions.style.display = "block";
                } else {
                    if (singleActions) singleActions.style.display = "block";
                    if (bulkActions) bulkActions.style.display = "none";
                }
            });
        }
    }

    // ---------- USER ----------
    initUserTable() {
        const mount = document.getElementById("table-search");
        if (!mount) return;

        this.gridInstanceUser = new gridjs.Grid({
            columns: [
                {
                    name: gridjs.html(
                        '<input type="checkbox" id="select-all-checkbox">'
                    ),
                    formatter: (cell, row) => {
                        const userId = row.cells[5].data; // id di kolom 5 (hidden)
                        return gridjs.html(
                            `<input type="checkbox" class="row-checkbox" data-id="${userId}">`
                        );
                    },
                },
                "Name",
                "Email",
                "Nomor Hp",
                {
                    name: gridjs.html(
                        '<span>Status <i class="bx bx-sort-up text-primary" title="Diurutkan: Aktif di atas"></i></span>'
                    ),
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
                { name: "ID", hidden: true },
                {
                    name: "Aksi",
                    formatter: (cell, row) => {
                        const user = {
                            id: row.cells[5].data,
                            name: row.cells[1].data || "",
                            email: row.cells[2].data || "",
                            phone: row.cells[3].data || "",
                            status: row.cells[4].data || "",
                        };
                        return gridjs.html(`
              <div class="d-flex gap-2 justify-content-center">
                <button class="btn btn-sm btn-outline-primary"
                  onclick="showEditUserModal(
                    '${user.id}',
                    '${user.name.replace(/"/g, '\\"')}',
                    '${user.email.replace(/"/g, '\\"')}',
                    '${user.phone.replace(/"/g, '\\"')}',
                    '${user.status}'
                  )">
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
                        null, // checkbox
                        u.full_name ?? "-",
                        u.email ?? "-",
                        u.phone ?? "-",
                        u.status ?? "-",
                        u.id, // hidden ID
                        null, // Aksi
                    ]),
            },
            language: {
                search: { placeholder: " Ketik untuk mencari…" },
                loading: "Memuat...",
                noRecordsFound: "Tidak ada data ditemukan",
                error: "Gagal mengambil data",
            },
        }).render(mount);

        // Select all user
        const selectAllUser = document.getElementById("select-all-checkbox");
        if (selectAllUser) {
            selectAllUser.addEventListener("change", () => {
                const isChecked = selectAllUser.checked;
                document
                    .querySelectorAll(".row-checkbox")
                    .forEach((cb) => (cb.checked = isChecked));
                // Update bulk actions
                const checkedBoxes = document.querySelectorAll(
                    ".row-checkbox:checked"
                );
                const singleActions = document.getElementById("single-actions");
                const bulkActions = document.getElementById("bulk-actions");
                if (checkedBoxes.length > 0) {
                    if (singleActions) singleActions.style.display = "none";
                    if (bulkActions) bulkActions.style.display = "block";
                } else {
                    if (singleActions) singleActions.style.display = "block";
                    if (bulkActions) bulkActions.style.display = "none";
                }
            });
        }

        window.gridInstance = this.gridInstanceUser;
        // Make reorderTableByStatus method accessible globally
        window.gridInstance.reorderTableByStatus = () =>
            this.reorderTableByStatus();
    }

    // ---------- EVENT LISTENERS ----------
    initEventListeners() {
        this.handleFormSubmit("addGuruForm", () =>
            this.gridInstanceGuru?.forceRender()
        );
        this.handleFormSubmit("addMuridForm", () =>
            this.gridInstanceMurid?.forceRender()
        );
        this.handleFormSubmit("addUserForm", () =>
            this.gridInstanceUser?.forceRender()
        );
        this.handleFormSubmit(
            "editGuruForm",
            () => this.gridInstanceGuru?.forceRender(),
            "PUT"
        );
        this.handleFormSubmit(
            "editMuridForm",
            () => this.gridInstanceMurid?.forceRender(),
            "PUT"
        );
        this.handleFormSubmit(
            "editUserForm",
            () => {
                this.gridInstanceUser?.forceRender();
                // Auto-reorder table after status change
                setTimeout(() => {
                    this.reorderTableByStatus();
                }, 500);
            },
            "PUT"
        );

        // ----- Checkbox (User) -----
        document.addEventListener("change", (event) => {
            if (
                event.target.classList.contains("row-checkbox") ||
                event.target.id === "select-all-checkbox"
            ) {
                const allCheckboxes =
                    document.querySelectorAll(".row-checkbox");
                const checkedBoxes = document.querySelectorAll(
                    ".row-checkbox:checked"
                );
                const selectAll = document.getElementById(
                    "select-all-checkbox"
                );
                const singleActions = document.getElementById("single-actions");
                const bulkActions = document.getElementById("bulk-actions");

                if (selectAll) {
                    selectAll.checked =
                        allCheckboxes.length > 0 &&
                        checkedBoxes.length === allCheckboxes.length;
                }

                if (checkedBoxes.length > 0) {
                    if (singleActions) singleActions.style.display = "none";
                    if (bulkActions) bulkActions.style.display = "block";
                } else {
                    if (singleActions) singleActions.style.display = "block";
                    if (bulkActions) bulkActions.style.display = "none";
                }
            }
        });

        // Bulk delete (User)
        const bulkDeleteBtn = document.getElementById("bulkDeleteBtn");
        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener("click", () => {
                const selectedIds = Array.from(
                    document.querySelectorAll(".row-checkbox:checked")
                ).map((cb) => cb.dataset.id);
                if (selectedIds.length === 0) return;

                document.getElementById("deleteUserModalLabel").innerText =
                    "Konfirmasi Hapus";
                document.querySelector(
                    "#deleteUserModal .modal-body p"
                ).innerText = `Apakah Anda yakin ingin menghapus ${selectedIds.length} user yang dipilih?`;
                document.getElementById("deleteUserId").value =
                    selectedIds.join(",");
                const deleteModal = new bootstrap.Modal(
                    document.getElementById("deleteUserModal")
                );
                deleteModal.show();
            });
        }

        // Bulk edit status (User)
        const bulkEditBtn = document.getElementById("bulkEditBtn");
        if (bulkEditBtn) {
            bulkEditBtn.addEventListener("click", () => {
                const selectedIds = Array.from(
                    document.querySelectorAll(".row-checkbox:checked")
                ).map((cb) => cb.dataset.id);
                if (selectedIds.length === 0) return;

                const modal = new bootstrap.Modal(
                    document.getElementById("bulkEditStatusModal")
                );
                modal.show();
            });
        }

        // Confirm bulk edit status (User + reusability for Guru/Murid via deleteRoute)
        const confirmBulkEditStatus = document.getElementById(
            "confirmBulkEditStatus"
        );
        if (confirmBulkEditStatus) {
            confirmBulkEditStatus.addEventListener("click", () => {
                const selectedIds = Array.from(
                    document.querySelectorAll(".row-checkbox:checked")
                ).map((cb) => cb.dataset.id);
                const newStatus =
                    document.getElementById("bulkStatusSelect").value;

                const formData = new FormData();
                selectedIds.forEach((id) => formData.append("ids[]", id));

                const url =
                    newStatus === "active"
                        ? "/admin/users/bulk-status-active"
                        : "/admin/users/bulk-status-suspended";

                fetch(url, {
                    method: "POST",
                    headers: { "X-CSRF-TOKEN": getCsrfToken() },
                    body: formData,
                })
                    .then((res) => res.json())
                    .then((data) => {
                        if (data.errors) {
                            data.message =
                                "Error: " +
                                Object.values(data.errors).flat().join(", ");
                            data.success = false;
                        }
                        showNotification(data.message, data.success);
                        if (data.success) {
                            const route =
                                document.getElementById("deleteRoute")?.value;
                            if (route === "/admin/user") {
                                this.gridInstanceUser?.forceRender();
                                // Auto-reorder table after bulk status change
                                setTimeout(() => {
                                    this.reorderTableByStatus();
                                }, 500);
                                document.getElementById(
                                    "single-actions"
                                ).style.display = "block";
                                document.getElementById(
                                    "bulk-actions"
                                ).style.display = "none";
                            } else if (route === "/admin/guru") {
                                this.gridInstanceGuru?.forceRender();
                                document.getElementById(
                                    "single-actions-guru"
                                ).style.display = "block";
                                document.getElementById(
                                    "bulk-actions-guru"
                                ).style.display = "none";
                            } else if (route === "/admin/murid") {
                                this.gridInstanceMurid?.forceRender();
                                document.getElementById(
                                    "single-actions-murid"
                                ).style.display = "block";
                                document.getElementById(
                                    "bulk-actions-murid"
                                ).style.display = "none";
                            }
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                        showNotification("Gagal mengupdate status", false);
                    });

                bootstrap.Modal.getInstance(
                    document.getElementById("bulkEditStatusModal")
                ).hide();
            });
        }

        // ----- Guru bulk -----
        document.addEventListener("change", (event) => {
            if (
                event.target.classList.contains("row-checkbox-guru") ||
                event.target.id === "select-all-guru-checkbox"
            ) {
                const allCheckboxes =
                    document.querySelectorAll(".row-checkbox-guru");
                const checkedBoxes = document.querySelectorAll(
                    ".row-checkbox-guru:checked"
                );
                const selectAll = document.getElementById(
                    "select-all-guru-checkbox"
                );
                const bulkActions =
                    document.getElementById("bulk-actions-guru");

                if (selectAll) {
                    selectAll.checked =
                        allCheckboxes.length > 0 &&
                        checkedBoxes.length === allCheckboxes.length;
                }
                if (checkedBoxes.length > 0) {
                    document.getElementById("single-actions-guru")?.style &&
                        (document.getElementById(
                            "single-actions-guru"
                        ).style.display = "none");
                    if (bulkActions) bulkActions.style.display = "block";
                } else {
                    document.getElementById("single-actions-guru")?.style &&
                        (document.getElementById(
                            "single-actions-guru"
                        ).style.display = "block");
                    if (bulkActions) bulkActions.style.display = "none";
                }
            }
        });

        const bulkDeleteGuruBtn = document.getElementById("bulkDeleteGuruBtn");
        if (bulkDeleteGuruBtn) {
            bulkDeleteGuruBtn.addEventListener("click", () => {
                const selectedIds = Array.from(
                    document.querySelectorAll(".row-checkbox-guru:checked")
                ).map((cb) => cb.dataset.id);
                if (selectedIds.length === 0) return;

                document.getElementById("deleteUserModalLabel").innerText =
                    "Konfirmasi Hapus Guru";
                document.querySelector(
                    "#deleteUserModal .modal-body p"
                ).innerText = `Apakah Anda yakin ingin menghapus ${selectedIds.length} guru yang dipilih?`;
                document.getElementById("deleteUserId").value =
                    selectedIds.join(",");
                document.getElementById("deleteRoute").value = "/admin/guru";
                const deleteModal = new bootstrap.Modal(
                    document.getElementById("deleteUserModal")
                );
                deleteModal.show();
            });
        }

        // ----- Murid bulk -----
        document.addEventListener("change", (event) => {
            if (
                event.target.classList.contains("row-checkbox-murid") ||
                event.target.id === "select-all-murid-checkbox"
            ) {
                const allCheckboxes = document.querySelectorAll(
                    ".row-checkbox-murid"
                );
                const checkedBoxes = document.querySelectorAll(
                    ".row-checkbox-murid:checked"
                );
                const selectAll = document.getElementById(
                    "select-all-murid-checkbox"
                );
                const bulkActions =
                    document.getElementById("bulk-actions-murid");

                if (selectAll) {
                    selectAll.checked =
                        allCheckboxes.length > 0 &&
                        checkedBoxes.length === allCheckboxes.length;
                }
                if (checkedBoxes.length > 0) {
                    document.getElementById("single-actions-murid")?.style &&
                        (document.getElementById(
                            "single-actions-murid"
                        ).style.display = "none");
                    if (bulkActions) bulkActions.style.display = "block";
                } else {
                    document.getElementById("single-actions-murid")?.style &&
                        (document.getElementById(
                            "single-actions-murid"
                        ).style.display = "block");
                    if (bulkActions) bulkActions.style.display = "none";
                }
            }
        });

        const bulkDeleteMuridBtn =
            document.getElementById("bulkDeleteMuridBtn");
        if (bulkDeleteMuridBtn) {
            bulkDeleteMuridBtn.addEventListener("click", () => {
                const selectedIds = Array.from(
                    document.querySelectorAll(".row-checkbox-murid:checked")
                ).map((cb) => cb.dataset.id);
                if (selectedIds.length === 0) return;

                document.getElementById("deleteUserModalLabel").innerText =
                    "Konfirmasi Hapus Murid";
                document.querySelector(
                    "#deleteUserModal .modal-body p"
                ).innerText = `Apakah Anda yakin ingin menghapus ${selectedIds.length} murid yang dipilih?`;
                document.getElementById("deleteUserId").value =
                    selectedIds.join(",");
                document.getElementById("deleteRoute").value = "/admin/murid";
                const deleteModal = new bootstrap.Modal(
                    document.getElementById("deleteUserModal")
                );
                deleteModal.show();
            });
        }

        // ----- Single delete buttons (User/Guru/Murid) -----
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
                document.getElementById("deleteUserModalLabel").innerText =
                    "Konfirmasi Hapus User";
                document.querySelector(
                    "#deleteUserModal .modal-body p"
                ).innerText = "Apakah Anda yakin ingin menghapus user ini?";
                document.getElementById("deleteRoute").value = "/admin/user";
                const deleteModal = new bootstrap.Modal(
                    document.getElementById("deleteUserModal")
                );
                deleteModal.show();
            }
        });

        // Konfirmasi hapus single
        this.handleDelete(
            "confirmDeleteGuruButton",
            "deleteGuruId",
            "/admin/guru",
            () => this.gridInstanceGuru?.forceRender()
        );
        this.handleDelete(
            "confirmDeleteMuridButton",
            "deleteMuridId",
            "/admin/murid",
            () => this.gridInstanceMurid?.forceRender()
        );

        // Konfirmasi hapus bulk (reusable modal)
        const confirmDeleteUserButton = document.getElementById(
            "confirmDeleteUserButton"
        );
        if (confirmDeleteUserButton) {
            confirmDeleteUserButton.addEventListener("click", () => {
                const resourceIds = document
                    .getElementById("deleteUserId")
                    .value.split(",")
                    .filter(Boolean);
                const baseUrl = document.getElementById("deleteRoute").value;

                const promises = resourceIds.map((resourceId) =>
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
                                data.message =
                                    "Error: " +
                                    Object.values(data.errors)
                                        .flat()
                                        .join(", ");
                                data.success = false;
                            }
                            return data;
                        })
                        .catch((error) => {
                            console.error(error);
                            return {
                                success: false,
                                message: "Gagal menghapus data",
                            };
                        })
                );

                Promise.all(promises).then((results) => {
                    const successes = results.filter(
                        (r) => r.success !== false
                    ).length;
                    const failures = results.length - successes;

                    if (failures === 0) {
                        showNotification(
                            `Berhasil menghapus ${successes} data`,
                            true
                        );
                    } else if (successes === 0) {
                        showNotification(
                            `Gagal menghapus ${failures} data`,
                            false
                        );
                    } else {
                        showNotification(
                            `Berhasil menghapus ${successes} data, gagal ${failures}`,
                            true
                        );
                    }

                    if (successes > 0) {
                        const route =
                            document.getElementById("deleteRoute").value;
                        if (route === "/admin/user") {
                            this.gridInstanceUser?.forceRender();
                            // Auto-reorder table after bulk delete
                            setTimeout(() => {
                                this.reorderTableByStatus();
                            }, 500);
                            document.getElementById(
                                "single-actions"
                            ).style.display = "block";
                            document.getElementById(
                                "bulk-actions"
                            ).style.display = "none";
                            document
                                .querySelectorAll(".row-checkbox")
                                .forEach((cb) => (cb.checked = false));
                            const all = document.getElementById(
                                "select-all-checkbox"
                            );
                            if (all) all.checked = false;
                        } else if (route === "/admin/guru") {
                            this.gridInstanceGuru?.forceRender();
                            document.getElementById(
                                "single-actions-guru"
                            ).style.display = "block";
                            document.getElementById(
                                "bulk-actions-guru"
                            ).style.display = "none";
                            document
                                .querySelectorAll(".row-checkbox-guru")
                                .forEach((cb) => (cb.checked = false));
                            const all = document.getElementById(
                                "select-all-guru-checkbox"
                            );
                            if (all) all.checked = false;
                        } else if (route === "/admin/murid") {
                            this.gridInstanceMurid?.forceRender();
                            document.getElementById(
                                "single-actions-murid"
                            ).style.display = "block";
                            document.getElementById(
                                "bulk-actions-murid"
                            ).style.display = "none";
                            document
                                .querySelectorAll(".row-checkbox-murid")
                                .forEach((cb) => (cb.checked = false));
                            const all = document.getElementById(
                                "select-all-murid-checkbox"
                            );
                            if (all) all.checked = false;
                        }
                    }
                });

                const modal = confirmDeleteUserButton.closest(".modal");
                if (modal) bootstrap.Modal.getInstance(modal).hide();
            });
        }
    }

    // ---------- HELPERS ----------
    handleFormSubmit(formId, callback, method = "POST") {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener("submit", (event) => {
            event.preventDefault();
            const formData = new FormData(form);
            let url = form.action;

            if (method === "PUT") {
                formData.append("_method", "PUT");
                const resourceId = form.querySelector(
                    'input[type="hidden"][name="id"]'
                )?.value;
                url = `${form.dataset.action}/${resourceId}`;
            }

            fetch(url, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": getCsrfToken(),
                    Accept: "application/json",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.errors) {
                        data.message =
                            "Validasi gagal: " +
                            Object.values(data.errors).flat().join(", ");
                        data.success = false;
                    }
                    const modal = form.closest(".modal");
                    if (modal) bootstrap.Modal.getInstance(modal).hide();

                    showNotification(data.message, data.success);
                    if (data.success) {
                        form.reset();
                        if (typeof callback === "function") callback();
                    }
                })
                .catch((err) => {
                    console.error(err);
                    showNotification(
                        "Terjadi kesalahan saat menyimpan data",
                        false
                    );
                });
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
                        data.message =
                            "Error: " +
                            Object.values(data.errors).flat().join(", ");
                        data.success = false;
                    }
                    const modal = confirmButton.closest(".modal");
                    if (modal) bootstrap.Modal.getInstance(modal).hide();

                    showNotification(data.message, data.success);
                    if (data.success && typeof callback === "function")
                        callback();
                })
                .catch((err) => {
                    console.error(err);
                    showNotification("Gagal menghapus data", false);
                });
        });
    }

    // Method to reorder table by status (active users first)
    reorderTableByStatus() {
        if (!this.gridInstanceUser) return;

        // Add a subtle animation to indicate reordering
        const tableContainer = document.querySelector(
            "#table-search .gridjs-wrapper"
        );
        if (tableContainer) {
            tableContainer.style.transition = "opacity 0.3s ease";
            tableContainer.style.opacity = "0.7";
        }

        // Show a brief notification that table is being reordered
        const notification = document.createElement("div");
        notification.className =
            "alert alert-info alert-dismissible fade show position-fixed";
        notification.style.cssText =
            "top: 20px; right: 20px; z-index: 9999; min-width: 300px;";
        notification.innerHTML = `
            <i class="bx bx-sort-up me-2"></i>
            Mengurutkan tabel: User aktif di atas
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(notification);

        // Force re-render to get fresh data from server (which is already sorted)
        this.gridInstanceUser.forceRender();

        // Restore opacity and remove notification after a short delay
        setTimeout(() => {
            if (tableContainer) {
                tableContainer.style.opacity = "1";
            }
            // Auto-remove notification after 2 seconds
            setTimeout(() => {
                if (notification && notification.parentNode) {
                    notification.remove();
                }
            }, 2000);
        }, 300);
    }
}

// Inisialisasi
new GridDatatable();
