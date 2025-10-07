import gridjs from "gridjs/dist/gridjs.umd.js";

class GridDatatable {
    init() {
        this.GridjsTableInit();
    }

    GridjsTableInit() {
        const mount = document.getElementById("table-search");
        if (!mount) return;

        new gridjs.Grid({
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
                    name: "Aksi",
                    formatter: (cell, row) => {
                        // Ambil semua data dari baris saat ini
                        const user = {
                            id: row.cells[3].data, // Asumsi username unik sebagai ID, atau ambil dari data server jika ada ID
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
                        modalBody.querySelector("#simpleinput").value =
                            user.name;
                        modalBody.querySelector("#example-email").value =
                            user.email;
                        modalBody.querySelector("#example-nohp").value =
                            user.phone;
                        modalBody.querySelector("#simpleinput").value =
                            user.username;

                        // Kamu bisa set form action dengan ID user yang sesuai
                        const form = editUserModal.querySelector("form");
                        form.action = `/admin/users/${user.id}`; // Pastikan form submit ke URL yang benar
                    }
                );
            }
        });
    }
}

document.addEventListener("DOMContentLoaded", () => new GridDatatable().init());
