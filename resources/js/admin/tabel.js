import gridjs from "gridjs/dist/gridjs.umd.js";

class GridDatatable {
    init() {
        this.GridjsTableInit();
    }

    GridjsTableInit() {
        const mount = document.getElementById("table-search");
        if (!mount) return;

        window.gridInstance = new gridjs.Grid({
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
                        // Ambil semua data dari baris saat ini
                        const user = {
                            id: row.cells[5].data, // ID from hidden column
                            name: row.cells[0].data,
                            email: row.cells[1].data,
                            phone: row.cells[2].data,
                            username: row.cells[3].data,
                            status: row.cells[4].data,
                        };

                        // Ubah tombol 'Edit' untuk memicu modal dan kirim data user melalui atribut 'data-user'
                        return gridjs.html(`
                        <div class="d-flex gap-2 justify-content-center">
        
                        <button class="btn btn-sm btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#editUserModal"
                            data-user='${JSON.stringify(user)}'>
                            Edit
                        </button>

                        <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="${
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
                        u.id, // Add id for actions
                    ]),
            },
            language: { search: { placeholder: " Ketik untuk mencari…" } },
        }).render(mount);

        // Event listener untuk membuka modal dan mengisi form edit
        document.addEventListener("DOMContentLoaded", function () {
            const editUserModal = document.getElementById("editUserModal");
            if (editUserModal) {
                editUserModal.addEventListener(
                    "show.bs.modal",
                    function (event) {
                        const button = event.relatedTarget;
                        // Ekstrak data dari atribut data-user
                        const user = JSON.parse(
                            button.getAttribute("data-user")
                        );

                        // Dapatkan elemen form
                        const modalBody =
                            editUserModal.querySelector(".modal-body");

                        // Isi form di dalam modal
                        modalBody.querySelector("#editUserName").value =
                            user.name;
                        modalBody.querySelector("#editUserEmail").value =
                            user.email;
                        modalBody.querySelector("#editUserPhone").value =
                            user.phone;
                        modalBody.querySelector("#editUserUsername").value =
                            user.username;
                        modalBody.querySelector("#editUserStatus").value =
                            user.status;

                        // Kamu bisa set form action dengan ID user yang sesuai
                        const form = editUserModal.querySelector("form");
                        form.action = `/admin/users/${user.id}`; // Pastikan form submit ke URL yang benar
                    }
                );
            }
        });
    }

    // Fungsi untuk me-refresh tabel
    refreshTable() {
        if (window.gridInstance) {
            window.gridInstance.forceRender(); // Memaksa render ulang tabel
        }
    }

    // Fungsi untuk menambahkan user
    addUser() {
        const addUserForm = document.getElementById("addUserForm");
        addUserForm.addEventListener("submit", function (event) {
            event.preventDefault();
            const formData = new FormData(addUserForm);

            fetch(addUserForm.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    bootstrap.Modal.getInstance(
                        document.getElementById("addUserModal")
                    ).hide();
                    showNotification(data.message, data.success);
                    if (data.success) {
                        addUserForm.reset();
                        window.gridInstance.forceRender(); // Refresh tabel setelah menambah user
                    }
                })
                .catch(console.error);
        });
    }

    // Fungsi untuk mengedit user
    editUser() {
        const editUserForm = document.getElementById("editUserForm");
        editUserForm.addEventListener("submit", function (event) {
            event.preventDefault();
            const userId = document.getElementById("editUserId").value;
            const formData = new FormData(editUserForm);
            formData.append("_method", "PUT"); // Method spoofing

            fetch(`/admin/users/${userId}`, {
                method: "POST", // POST method with method spoofing
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                body: formData,
            })
                .then((res) => res.json())
                .then((data) => {
                    bootstrap.Modal.getInstance(editUserModal).hide();
                    showNotification(data.message, data.success);
                    if (data.success) {
                        window.gridInstance.forceRender(); // Refresh tabel setelah update user
                    }
                })
                .catch(console.error);
        });
    }

    // Fungsi untuk menghapus user
    deleteUser() {
        const deleteModal = new bootstrap.Modal(
            document.getElementById("deleteUserModal")
        );

        document
            .getElementById("confirmDeleteButton")
            .addEventListener("click", function () {
                const userId = document.getElementById("deleteUserId").value;
                fetch(`/admin/users/${userId}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                })
                    .then((res) => res.json())
                    .then((data) => {
                        deleteModal.hide();
                        showNotification(data.message, data.success);
                        if (data.success) {
                            window.gridInstance.forceRender(); // Refresh tabel setelah hapus user
                        }
                    })
                    .catch(console.error);
            });
    }
}

document.addEventListener("DOMContentLoaded", () => new GridDatatable().init());
