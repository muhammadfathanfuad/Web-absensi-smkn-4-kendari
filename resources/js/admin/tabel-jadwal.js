import gridjs from "gridjs/dist/gridjs.umd.js";

// Helper untuk mendapatkan token CSRF
const getCsrfToken = () => {
    return document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
};

// Fungsi notifikasi
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

// Fungsi global untuk edit jadwal - diimplementasikan di jadwal-pelajaran.blade.php

// Fungsi global untuk delete jadwal
window.deleteJadwal = function (id) {
    document.getElementById("deleteJadwalId").value = id;
    const modal = new bootstrap.Modal(
        document.getElementById("deleteJadwalModal")
    );
    modal.show();
};

// Fungsi global untuk reload tabel mata pelajaran (auto reload)
window.reloadSubjectsTable = function () {
    console.log("Reloading subjects table...");
    if (window.gridInstanceSubjects) {
        window.gridInstanceSubjects.forceRender();
    }
};

// Fungsi global untuk reload tabel kelas (auto reload)
window.reloadClassesTable = function () {
    console.log("Reloading classes table...");
    if (window.gridInstanceClasses) {
        window.gridInstanceClasses.forceRender();
    }
};

// Fungsi untuk menangani error dan retry dengan auto reload
window.handleSubjectsError = function (error, action) {
    console.error(`Error in ${action}:`, error);
    showNotification(`Gagal ${action} mata pelajaran`, false);

    // Retry dengan auto reload
    setTimeout(() => {
        console.log(`Retrying ${action} by reloading table...`);
        window.reloadSubjectsTable();
    }, 1000);
};

// Fungsi global untuk render subjects table
window.renderSubjectsTable = function () {
    if (
        window.tabelJadwalInstance &&
        window.tabelJadwalInstance.renderSubjectsTable
    ) {
        window.tabelJadwalInstance.renderSubjectsTable();
    } else {
        console.error("TabelJadwalInstance not available for rendering");
    }
};

// Fungsi global untuk menampilkan detail mata pelajaran
window.showSubjectDetail = function (id) {
    // Fetch subject data directly from server
    fetch(`/admin/subjects/${id}`, {
        headers: { Accept: "application/json" },
    })
        .then((response) => response.json())
        .then((subject) => {
            if (!subject) return;

            // Populate modal with subject details
            document.getElementById("subjectDetailCode").textContent =
                subject.code;
            document.getElementById("subjectDetailName").textContent =
                subject.name;
            document.getElementById("subjectDetailClassCount").textContent =
                subject.class_count;
            document.getElementById("subjectDetailTeacherCount").textContent =
                subject.teacher_count;

            // Populate classes list
            const classesList = document.getElementById("subjectDetailClasses");
            classesList.innerHTML = "";
            if (subject.classes && subject.classes.length > 0) {
                subject.classes.forEach((cls) => {
                    const classItem = document.createElement("div");
                    classItem.className =
                        "d-flex align-items-center justify-content-between p-3 mb-2 bg-light bg-opacity-50 rounded";
                    classItem.innerHTML = `
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs bg-primary bg-opacity-10 rounded-circle me-2">
                                <iconify-icon icon="solar:buildings-2-outline" class="fs-16 text-primary"></iconify-icon>
                            </div>
                            <div>
                                <h6 class="mb-0">${cls.name}</h6>
                                <p class="text-muted mb-0 fs-13">Kelas ${cls.grade}</p>
                            </div>
                        </div>
                        <span class="badge bg-primary fs-12">Grade ${cls.grade}</span>
                    `;
                    classesList.appendChild(classItem);
                });
            } else {
                classesList.innerHTML = `
                    <div class="text-center py-4">
                        <iconify-icon icon="solar:buildings-2-outline" class="fs-48 text-muted mb-2"></iconify-icon>
                        <p class="text-muted mb-0">Tidak ada kelas yang menggunakan mata pelajaran ini</p>
                    </div>
                `;
            }

            // Populate teachers list
            const teachersList = document.getElementById(
                "subjectDetailTeachers"
            );
            teachersList.innerHTML = "";
            if (subject.teachers && subject.teachers.length > 0) {
                subject.teachers.forEach((teacher) => {
                    const teacherItem = document.createElement("div");
                    teacherItem.className =
                        "d-flex align-items-center justify-content-between p-3 mb-2 bg-light bg-opacity-50 rounded";
                    teacherItem.innerHTML = `
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs bg-success bg-opacity-10 rounded-circle me-2">
                                <iconify-icon icon="solar:user-outline" class="fs-16 text-success"></iconify-icon>
                            </div>
                            <div>
                                <h6 class="mb-0">${
                                    teacher.user?.full_name || teacher
                                }</h6>
                                <p class="text-muted mb-0 fs-13">${
                                    teacher.user?.email ||
                                    "Email tidak tersedia"
                                }</p>
                            </div>
                        </div>
                        <span class="badge bg-success fs-12">Guru</span>
                    `;
                    teachersList.appendChild(teacherItem);
                });
            } else {
                teachersList.innerHTML = `
                    <div class="text-center py-4">
                        <iconify-icon icon="solar:user-outline" class="fs-48 text-muted mb-2"></iconify-icon>
                        <p class="text-muted mb-0">Tidak ada guru yang mengajar mata pelajaran ini</p>
                    </div>
                `;
            }

            // Show modal
            const modal = new bootstrap.Modal(
                document.getElementById("subjectDetailModal")
            );
            modal.show();
        })
        .catch((error) => {
            console.error("Error fetching subject details:", error);
            showNotification("Gagal memuat detail mata pelajaran", false);
        });
};

// Fungsi global untuk menampilkan detail kelas
window.showClassDetail = function (id) {
    // Fetch class data directly from server
    fetch(`/admin/classes/${id}`, {
        headers: { Accept: "application/json" },
    })
        .then((response) => response.json())
        .then((classData) => {
            if (!classData) return;

            // Populate modal with class details
            document.getElementById("classDetailName").textContent =
                classData.name;
            document.getElementById("classDetailDisplayGrade").textContent =
                classData.display_grade;
            document.getElementById("classDetailGrade").textContent =
                classData.grade;
            document.getElementById("classDetailCreated").textContent =
                classData.created_at;

            // Show modal
            const modal = new bootstrap.Modal(
                document.getElementById("classDetailModal")
            );
            modal.show();
        })
        .catch((error) => {
            console.error("Error fetching class details:", error);
            showNotification("Gagal memuat detail kelas", false);
        });
};

// Fungsi global untuk menampilkan modal edit kelas
window.editClass = function (id) {
    // Fetch class data directly from server
    fetch(`/admin/classes/${id}`, {
        headers: { Accept: "application/json" },
    })
        .then((response) => response.json())
        .then((classData) => {
            if (!classData) return;

            // Populate form with current data
            document.getElementById("editClassId").value = classData.id;
            document.getElementById("editClassName").value = classData.name;
            document.getElementById("editClassGrade").value = classData.grade;

            // Show modal (you need to create this modal)
            const modal = new bootstrap.Modal(
                document.getElementById("editClassModal")
            );
            modal.show();
        })
        .catch((error) => {
            console.error("Error fetching class for edit:", error);
            showNotification("Gagal memuat data kelas", false);
        });
};

// Fungsi global untuk menampilkan modal konfirmasi hapus kelas
window.confirmDeleteClass = function (id) {
    document.getElementById("deleteClassId").value = id;
    const modal = new bootstrap.Modal(
        document.getElementById("deleteClassModal")
    );
    modal.show();
};

// Fungsi global untuk menghapus kelas
window.deleteClass = function () {
    const id = document.getElementById("deleteClassId").value;
    if (!id) return;

    fetch(`/admin/classes/${id}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": getCsrfToken(),
            Accept: "application/json",
        },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            showNotification(data.message, data.success);
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("deleteClassModal")
                );
                if (modal) modal.hide();

                // Reload tabel setelah hapus
                window.reloadClassesTable();
            }
        })
        .catch((error) => {
            console.error("Error deleting class:", error);
            showNotification("Gagal menghapus kelas", false);
        });
};

// Fungsi global untuk menangani form tambah kelas
window.handleClassAdd = function (event) {
    console.log("handleClassAdd called");
    event.preventDefault();

    const formData = new FormData(event.target);
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;

    console.log("Form data:", {
        name: formData.get("name"),
        grade: formData.get("grade"),
    });

    // Disable submit button and show loading
    submitBtn.disabled = true;
    submitBtn.textContent = "Menambahkan...";

    fetch("/admin/classes", {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": getCsrfToken(),
        },
    })
        .then((response) => response.json())
        .then((data) => {
            console.log("Add class response:", data);
            if (data.success) {
                showNotification(data.message, true);
                // Close modal
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("addClassModal")
                );
                if (modal) {
                    modal.hide();
                }
                // Reset form
                event.target.reset();
                // Reload classes table
                if (typeof window.reloadClassesTable === "function") {
                    window.reloadClassesTable();
                }
            } else {
                showNotification(data.message, false);
            }
        })
        .catch((error) => {
            console.error("Error adding class:", error);
            showNotification("Gagal menambahkan kelas", false);
        })
        .finally(() => {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
};

// Fungsi global untuk menangani form edit kelas
window.handleClassEdit = function (event) {
    console.log("handleClassEdit called");
    event.preventDefault();

    const formData = new FormData(event.target);
    const id = formData.get("id");

    console.log("Form data:", {
        id: id,
        name: formData.get("name"),
        grade: formData.get("grade"),
    });

    // Add _method field for Laravel to recognize PUT request
    formData.append("_method", "PUT");

    // Disable submit button
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = "Menyimpan...";

    fetch(`/admin/classes/${id}`, {
        method: "POST", // Use POST with _method=PUT for Laravel
        headers: {
            "X-CSRF-TOKEN": getCsrfToken(),
            Accept: "application/json",
        },
        body: formData,
    })
        .then((response) => {
            console.log("Response status:", response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            console.log("Response data:", data);
            showNotification(data.message, data.success);
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("editClassModal")
                );
                if (modal) modal.hide();

                // Reload tabel setelah edit
                window.reloadClassesTable();
            }
        })
        .catch((error) => {
            console.error("Error updating class:", error);
            showNotification("Gagal memperbarui kelas", false);
        })
        .finally(() => {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
};

// Fungsi global untuk menangani impor kelas
window.handleClassImport = function (event) {
    console.log("handleClassImport called");
    event.preventDefault();

    const formData = new FormData(event.target);
    const file = formData.get("file");

    if (!file || file.size === 0) {
        showNotification("Pilih file Excel terlebih dahulu", false);
        return;
    }

    // Validate file type
    const allowedTypes = [
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", // .xlsx
        "application/vnd.ms-excel", // .xls
    ];

    if (!allowedTypes.includes(file.type)) {
        showNotification("File harus berupa Excel (.xlsx atau .xls)", false);
        return;
    }

    // Disable submit button
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.innerHTML =
        '<i class="bx bx-loader-alt bx-spin me-1"></i> Mengimpor...';

    fetch("/admin/classes/import", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": getCsrfToken(),
            Accept: "application/json",
        },
        body: formData,
    })
        .then((response) => {
            console.log("Import response status:", response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            console.log("Import response data:", data);
            showNotification(data.message, data.success);

            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("importClassModal")
                );
                if (modal) modal.hide();

                // Reset form
                event.target.reset();

                // Reload tabel setelah impor
                window.reloadClassesTable();

                // Show errors if any
                if (data.errors && data.errors.length > 0) {
                    console.warn("Import errors:", data.errors);
                    // You can show errors in a separate modal or alert if needed
                }
            }
        })
        .catch((error) => {
            console.error("Error importing classes:", error);
            showNotification("Gagal mengimpor kelas", false);
        })
        .finally(() => {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML =
                '<i class="bx bx-upload me-1"></i> Impor Kelas';
        });
};

// Fungsi global untuk menampilkan modal konfirmasi hapus
window.confirmDeleteSubject = function (id) {
    document.getElementById("deleteSubjectId").value = id;
    const modal = new bootstrap.Modal(
        document.getElementById("deleteSubjectModal")
    );
    modal.show();
};

// Fungsi global untuk menghapus mata pelajaran
window.deleteSubject = function () {
    const id = document.getElementById("deleteSubjectId").value;
    if (!id) return;

    fetch(`/admin/subjects/${id}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": getCsrfToken(),
            Accept: "application/json",
        },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            showNotification(data.message, data.success);
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("deleteSubjectModal")
                );
                if (modal) modal.hide();

                // Reload tabel setelah hapus
                window.reloadSubjectsTable();
            }
        })
        .catch((error) => {
            window.handleSubjectsError(error, "menghapus");
        });
};

// Fungsi global untuk menangani penambahan mata pelajaran baru
window.handleSubjectCreate = function (newSubjectData) {
    console.log("Handling subject create:", newSubjectData);
    window.reloadSubjectsTable();
};

// Fungsi global untuk menampilkan modal edit mata pelajaran
window.editSubject = function (id) {
    // Fetch subject data directly from server
    fetch(`/admin/subjects/${id}`, {
        headers: { Accept: "application/json" },
    })
        .then((response) => response.json())
        .then((subject) => {
            if (!subject) return;

            // Populate form with current data
            document.getElementById("editSubjectId").value = subject.id;
            document.getElementById("editSubjectCode").value = subject.code;
            document.getElementById("editSubjectName").value = subject.name;

            // Show modal
            const modal = new bootstrap.Modal(
                document.getElementById("editSubjectModal")
            );
            modal.show();
        })
        .catch((error) => {
            console.error("Error fetching subject for edit:", error);
            showNotification("Gagal memuat data mata pelajaran", false);
        });
};

// Fungsi global untuk update mata pelajaran
window.updateSubject = function () {
    const form = document.getElementById("editSubjectForm");
    const formData = new FormData(form);
    const id = formData.get("id");

    if (!id) {
        showNotification("ID mata pelajaran tidak ditemukan", false);
        return;
    }

    // Disable submit button
    const submitBtn = document.querySelector("#editSubjectModal .btn-primary");
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = "Menyimpan...";

    fetch(`/admin/subjects/${id}`, {
        method: "PUT",
        headers: {
            "X-CSRF-TOKEN": getCsrfToken(),
            "Content-Type": "application/json",
            Accept: "application/json",
        },
        body: JSON.stringify({
            code: formData.get("code"),
            name: formData.get("name"),
        }),
    })
        .then((response) => {
            console.log("Response status:", response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            console.log("Response data:", data);
            showNotification(data.message, data.success);
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("editSubjectModal")
                );
                if (modal) modal.hide();

                // Update data lokal secara asinkron
                const form = document.getElementById("editSubjectForm");
                const formData = new FormData(form);
                const updatedData = {
                    code: formData.get("code"),
                    name: formData.get("name"),
                };

                // Reload tabel setelah update
                window.reloadSubjectsTable();
            }
        })
        .catch((error) => {
            window.handleSubjectsError(error, "mengupdate");
        })
        .finally(() => {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
};

// Add global XI delete handler
window.deleteJadwalXi = function (id) {
    if (!id) return;
    fetch(`/admin/jadwal-xi/${id}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": getCsrfToken(),
            Accept: "application/json",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            showNotification(data.message, data.success);
            if (data.success) {
                // Auto refresh halaman setelah 1.5 detik
                setTimeout(() => {
                    location.reload();
                }, 1500);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showNotification("Gagal menghapus jadwal XI.", false);
        });
};

// Open XI single delete modal
window.openDeleteJadwalXiModal = function (id) {
    document.getElementById("deleteJadwalXiId").value = id;
    const modal = new bootstrap.Modal(
        document.getElementById("deleteJadwalXiModal")
    );
    modal.show();
};

// Wire up XI delete confirmation buttons (single)
(function () {
    const btn = document.getElementById("confirmDeleteJadwalXiButton");
    if (!btn) return;
    btn.addEventListener("click", function () {
        const id = document.getElementById("deleteJadwalXiId").value;
        if (!id) return;
        fetch(`/admin/jadwal-xi/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": getCsrfToken(),
                Accept: "application/json",
            },
        })
            .then((r) => r.json())
            .then((data) => {
                showNotification(data.message, data.success);
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("deleteJadwalXiModal")
                );
                if (modal) modal.hide();
                if (data.success) {
                    // Auto refresh halaman setelah 1.5 detik
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
            })
            .catch(() => showNotification("Gagal menghapus jadwal XI.", false));
    });
})();

// Wire up XI bulk delete confirmation
(function () {
    const btn = document.getElementById("confirmBulkDeleteJadwalXiButton");
    if (!btn) return;
    btn.addEventListener("click", function () {
        const ids = document.getElementById("deleteJadwalXiIds").value;
        if (!ids) {
            showNotification("Tidak ada jadwal XI yang dipilih.", false);
            return;
        }

        const idsArray = ids.split(",").filter((id) => id.trim() !== "");

        if (idsArray.length === 0) {
            showNotification("Tidak ada jadwal XI yang dipilih.", false);
            return;
        }
        fetch(`/admin/jadwal-xi/bulk-delete`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": getCsrfToken(),
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({ ids: ids }),
        })
            .then((response) => {
                console.log("Response status:", response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                console.log("Response data:", data);
                showNotification(data.message, data.success);
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("bulkDeleteJadwalXiModal")
                );
                if (modal) modal.hide();
                if (data.success) {
                    // Reset checkboxes and bulk actions
                    const selectAllXi = document.getElementById(
                        "select-all-jadwal-xi-checkbox"
                    );
                    if (selectAllXi) selectAllXi.checked = false;
                    document
                        .querySelectorAll(".row-checkbox-jadwal-xi")
                        .forEach((cb) => (cb.checked = false));

                    // Auto refresh halaman setelah 1.5 detik
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
            })
            .catch((error) => {
                console.error("Bulk delete error:", error);
                showNotification(
                    "Gagal menghapus jadwal XI: " + error.message,
                    false
                );
            });
    });
})();

// Hook bulk delete button to open modal with selected IDs
(function () {
    const btn = document.getElementById("bulk-delete-jadwal-xi");
    if (!btn) return;
    btn.addEventListener("click", function () {
        const selectedIds = Array.from(
            document.querySelectorAll(".row-checkbox-jadwal-xi:checked")
        ).map((cb) => cb.dataset.id);

        if (selectedIds.length === 0) {
            showNotification("Pilih minimal satu jadwal untuk dihapus.", false);
            return;
        }
        document.getElementById("deleteJadwalXiIds").value =
            selectedIds.join(",");
        const modal = new bootstrap.Modal(
            document.getElementById("bulkDeleteJadwalXiModal")
        );
        modal.show();
    });
})();

class GridJadwalDatatable {
    constructor() {
        this.gridInstanceJadwal = null;
        this.gridInstanceSubjects = null;

        // Panggil method untuk inisialisasi semua yang dibutuhkan
        document.addEventListener("DOMContentLoaded", () => {
            this.initTables();
        });
    }

    // Fungsi untuk menginisialisasi semua tabel
    initTables() {
        // Initialize JadwalTable with default (no filter) - will be filtered when semester is selected
        this.initJadwalTable();
        this.initJadwalXiTable();
        this.initSubjectsTable();
        this.initClassesTable();
        // initTermsTable() - hanya dipanggil ketika tab semester dibuka
        this.initImportForm();
        this.initSubjectForm();
        this.initUploadSubjectForm();
        this.initImportJadwalXiForm();
        this.initDeleteModal();
        this.initFilterModal();
        this.initClassForms();
        this.initBulkActions();
    }

    // Fungsi untuk menginisialisasi tabel Mata Pelajaran dengan auto reload
    initSubjectsTable() {
        this.renderSubjectsTable();
    }

    // Fungsi untuk menginisialisasi tabel Kelas dengan auto reload
    initClassesTable() {
        this.renderClassesTable();
    }

    // Fungsi untuk menginisialisasi tabel Semester dengan auto reload
    initTermsTable() {
        this.renderTermsTable();
    }

    // Fungsi untuk menginisialisasi form kelas
    initClassForms() {
        // Add class form
        const addClassForm = document.getElementById("addClassForm");
        if (addClassForm) {
            console.log("Add class form found, adding event listener");
            addClassForm.addEventListener("submit", window.handleClassAdd);
        } else {
            console.error("Add class form not found!");
        }

        // Edit class form
        const editClassForm = document.getElementById("editClassForm");
        if (editClassForm) {
            console.log("Edit class form found, adding event listener");
            editClassForm.addEventListener("submit", window.handleClassEdit);
        } else {
            console.error("Edit class form not found!");
        }

        // Import class form
        const importClassForm = document.getElementById("importClassForm");
        if (importClassForm) {
            console.log("Import class form found, adding event listener");
            importClassForm.addEventListener(
                "submit",
                window.handleClassImport
            );
        } else {
            console.error("Import class form not found!");
        }
    }

    // Fungsi untuk menginisialisasi bulk actions
    initBulkActions() {
        // Subjects bulk actions
        this.initSubjectsBulkActions();

        // Classes bulk actions
        this.initClassesBulkActions();

        // Bulk delete event listeners
        this.initBulkDeleteListeners();
    }

    // Fungsi untuk menginisialisasi bulk actions mata pelajaran
    initSubjectsBulkActions() {
        // Remove existing event listeners to prevent duplicates
        if (this.subjectsBulkActionHandler) {
            document.removeEventListener(
                "change",
                this.subjectsBulkActionHandler
            );
        }

        // Create new event handler
        this.subjectsBulkActionHandler = (event) => {
            if (event.target.id === "select-all-subjects-checkbox") {
                console.log(
                    "Select all subjects checkbox changed:",
                    event.target.checked
                );
                const isChecked = event.target.checked;
                const allCheckboxes = document.querySelectorAll(
                    ".row-checkbox-subjects"
                );
                console.log(
                    "Found",
                    allCheckboxes.length,
                    "subject checkboxes"
                );
                allCheckboxes.forEach((cb) => (cb.checked = isChecked));
                this.updateSubjectsBulkActions();
            }

            if (event.target.classList.contains("row-checkbox-subjects")) {
                console.log("Individual subject checkbox changed");
                this.updateSubjectsBulkActions();
            }
        };

        // Add event listener
        document.addEventListener("change", this.subjectsBulkActionHandler);
    }

    // Fungsi untuk attach event listener langsung ke checkbox setelah tabel di-render
    attachSubjectsCheckboxEvents() {
        const selectAllCheckbox = document.getElementById(
            "select-all-subjects-checkbox"
        );
        if (selectAllCheckbox) {
            console.log(
                "Attaching event listener to select all subjects checkbox"
            );
            selectAllCheckbox.addEventListener("change", () => {
                console.log(
                    "Select all subjects checkbox changed:",
                    selectAllCheckbox.checked
                );
                const isChecked = selectAllCheckbox.checked;
                const allCheckboxes = document.querySelectorAll(
                    ".row-checkbox-subjects"
                );
                console.log(
                    "Found",
                    allCheckboxes.length,
                    "subject checkboxes"
                );
                allCheckboxes.forEach((cb) => (cb.checked = isChecked));
                this.updateSubjectsBulkActions();
            });
        } else {
            console.error("Select all subjects checkbox not found!");
        }

        // Attach event listeners to individual checkboxes
        const individualCheckboxes = document.querySelectorAll(
            ".row-checkbox-subjects"
        );
        console.log(
            "Found",
            individualCheckboxes.length,
            "individual subject checkboxes"
        );
        individualCheckboxes.forEach((checkbox, index) => {
            checkbox.addEventListener("change", () => {
                console.log(`Individual subject checkbox ${index + 1} changed`);
                this.updateSubjectsBulkActions();
            });
        });
    }

    // Fungsi untuk menginisialisasi bulk actions kelas
    initClassesBulkActions() {
        // Remove existing event listeners to prevent duplicates
        if (this.classesBulkActionHandler) {
            document.removeEventListener(
                "change",
                this.classesBulkActionHandler
            );
        }

        // Create new event handler
        this.classesBulkActionHandler = (event) => {
            if (event.target.id === "select-all-classes-checkbox") {
                console.log(
                    "Select all classes checkbox changed:",
                    event.target.checked
                );
                const isChecked = event.target.checked;
                const allCheckboxes = document.querySelectorAll(
                    ".row-checkbox-classes"
                );
                console.log("Found", allCheckboxes.length, "class checkboxes");
                allCheckboxes.forEach((cb) => (cb.checked = isChecked));
                this.updateClassesBulkActions();
            }

            if (event.target.classList.contains("row-checkbox-classes")) {
                console.log("Individual class checkbox changed");
                this.updateClassesBulkActions();
            }
        };

        // Add event listener
        document.addEventListener("change", this.classesBulkActionHandler);
    }

    // Fungsi untuk attach event listener langsung ke checkbox setelah tabel di-render
    attachClassesCheckboxEvents() {
        const selectAllCheckbox = document.getElementById(
            "select-all-classes-checkbox"
        );
        if (selectAllCheckbox) {
            console.log(
                "Attaching event listener to select all classes checkbox"
            );
            selectAllCheckbox.addEventListener("change", () => {
                console.log(
                    "Select all classes checkbox changed:",
                    selectAllCheckbox.checked
                );
                const isChecked = selectAllCheckbox.checked;
                const allCheckboxes = document.querySelectorAll(
                    ".row-checkbox-classes"
                );
                console.log("Found", allCheckboxes.length, "class checkboxes");
                allCheckboxes.forEach((cb) => (cb.checked = isChecked));
                this.updateClassesBulkActions();
            });
        } else {
            console.error("Select all classes checkbox not found!");
        }

        // Attach event listeners to individual checkboxes
        const individualCheckboxes = document.querySelectorAll(
            ".row-checkbox-classes"
        );
        console.log(
            "Found",
            individualCheckboxes.length,
            "individual class checkboxes"
        );
        individualCheckboxes.forEach((checkbox, index) => {
            checkbox.addEventListener("change", () => {
                console.log(`Individual class checkbox ${index + 1} changed`);
                this.updateClassesBulkActions();
            });
        });
    }

    // Fungsi untuk attach event listener langsung ke checkbox setelah tabel di-render
    attachTermsCheckboxEvents() {
        const selectAllCheckbox = document.getElementById(
            "select-all-terms-checkbox"
        );
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener("change", () => {
                const isChecked = selectAllCheckbox.checked;
                const allCheckboxes = document.querySelectorAll(
                    ".row-checkbox-terms"
                );
                allCheckboxes.forEach((cb) => (cb.checked = isChecked));
                this.updateTermsBulkActions();
            });
        }

        // Attach event listeners to individual checkboxes
        const individualCheckboxes = document.querySelectorAll(
            ".row-checkbox-terms"
        );
        individualCheckboxes.forEach((checkbox, index) => {
            checkbox.addEventListener("change", () => {
                this.updateTermsBulkActions();
            });
        });
    }

    // Fungsi untuk update bulk actions mata pelajaran
    updateSubjectsBulkActions() {
        const allCheckboxes = document.querySelectorAll(
            ".row-checkbox-subjects"
        );
        const checkedBoxes = document.querySelectorAll(
            ".row-checkbox-subjects:checked"
        );
        const selectAll = document.getElementById(
            "select-all-subjects-checkbox"
        );
        const singleActions = document.getElementById(
            "single-actions-subjects"
        );
        const bulkActions = document.getElementById("bulk-actions-subjects");

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

        console.log(
            `Subjects: ${checkedBoxes.length} of ${allCheckboxes.length} selected`
        );
    }

    // Fungsi untuk update bulk actions kelas
    updateClassesBulkActions() {
        const allCheckboxes = document.querySelectorAll(
            ".row-checkbox-classes"
        );
        const checkedBoxes = document.querySelectorAll(
            ".row-checkbox-classes:checked"
        );
        const selectAll = document.getElementById(
            "select-all-classes-checkbox"
        );
        const singleActions = document.getElementById("single-actions-classes");
        const bulkActions = document.getElementById("bulk-actions-classes");

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

        console.log(
            `Classes: ${checkedBoxes.length} of ${allCheckboxes.length} selected`
        );
    }

    // Fungsi untuk update bulk actions semester
    updateTermsBulkActions() {
        const allCheckboxes = document.querySelectorAll(".row-checkbox-terms");
        const checkedBoxes = document.querySelectorAll(
            ".row-checkbox-terms:checked"
        );
        const selectAll = document.getElementById("select-all-terms-checkbox");
        const singleActions = document.getElementById("single-actions-terms");
        const bulkActions = document.getElementById("bulk-actions-terms");

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

    // Fungsi untuk menampilkan modal konfirmasi bulk delete
    showBulkDeleteConfirmation(type, selectedIds) {
        this.currentSelectedIds = selectedIds;

        const modalId =
            type === "subjects"
                ? "bulkDeleteSubjectsModal"
                : "bulkDeleteClassesModal";
        const countTextId =
            type === "subjects" ? "subjects-count-text" : "classes-count-text";

        // Update count text
        const countText = document.getElementById(countTextId);
        if (countText) {
            countText.textContent = selectedIds.length;
        }

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    // Fungsi untuk menampilkan modal notifikasi
    showNotificationModal(message, type = "info", title = "Notifikasi") {
        const modal = document.getElementById("notificationModal");
        const messageElement = document.getElementById("notificationMessage");

        // Set message
        messageElement.textContent = message;

        // Show modal
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    }

    // Fungsi untuk menginisialisasi bulk delete listeners
    initBulkDeleteListeners() {
        // Bulk delete subjects
        const bulkDeleteSubjectsBtn = document.getElementById(
            "bulk-delete-subjects"
        );
        if (bulkDeleteSubjectsBtn) {
            bulkDeleteSubjectsBtn.addEventListener("click", () => {
                const selectedIds = Array.from(
                    document.querySelectorAll(".row-checkbox-subjects:checked")
                ).map((cb) => cb.dataset.id);

                if (selectedIds.length === 0) {
                    this.showNotificationModal(
                        "Pilih minimal satu mata pelajaran untuk dihapus."
                    );
                    return;
                }

                // Show confirmation modal
                this.showBulkDeleteConfirmation("subjects", selectedIds);
            });
        }

        // Bulk delete classes
        const bulkDeleteClassesBtn = document.getElementById(
            "bulk-delete-classes"
        );
        if (bulkDeleteClassesBtn) {
            bulkDeleteClassesBtn.addEventListener("click", () => {
                const selectedIds = Array.from(
                    document.querySelectorAll(".row-checkbox-classes:checked")
                ).map((cb) => cb.dataset.id);

                if (selectedIds.length === 0) {
                    this.showNotificationModal(
                        "Pilih minimal satu kelas untuk dihapus."
                    );
                    return;
                }

                // Show confirmation modal
                this.showBulkDeleteConfirmation("classes", selectedIds);
            });
        }

        // Confirm bulk delete subjects
        const confirmBulkDeleteSubjectsBtn = document.getElementById(
            "confirmBulkDeleteSubjects"
        );
        if (confirmBulkDeleteSubjectsBtn) {
            confirmBulkDeleteSubjectsBtn.addEventListener("click", () => {
                const selectedIds = this.currentSelectedIds;
                if (selectedIds && selectedIds.length > 0) {
                    this.bulkDeleteSubjects(selectedIds);
                    // Close confirmation modal
                    const modal = bootstrap.Modal.getInstance(
                        document.getElementById("bulkDeleteSubjectsModal")
                    );
                    if (modal) modal.hide();
                }
            });
        }

        // Confirm bulk delete classes
        const confirmBulkDeleteClassesBtn = document.getElementById(
            "confirmBulkDeleteClasses"
        );
        if (confirmBulkDeleteClassesBtn) {
            confirmBulkDeleteClassesBtn.addEventListener("click", () => {
                const selectedIds = this.currentSelectedIds;
                if (selectedIds && selectedIds.length > 0) {
                    this.bulkDeleteClasses(selectedIds);
                    // Close confirmation modal
                    const modal = bootstrap.Modal.getInstance(
                        document.getElementById("bulkDeleteClassesModal")
                    );
                    if (modal) modal.hide();
                }
            });
        }
    }

    // Fungsi untuk bulk delete mata pelajaran
    bulkDeleteSubjects(selectedIds) {
        const deletePromises = selectedIds.map((id) =>
            fetch(`/admin/subjects/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": getCsrfToken(),
                    Accept: "application/json",
                },
            })
        );

        Promise.all(deletePromises)
            .then((responses) => Promise.all(responses.map((r) => r.json())))
            .then((results) => {
                const successCount = results.filter((r) => r.success).length;
                const errorCount = results.length - successCount;

                if (successCount > 0) {
                    this.showNotificationModal(
                        `Berhasil menghapus ${successCount} mata pelajaran.`
                    );
                    // Reset checkboxes
                    document
                        .querySelectorAll(".row-checkbox-subjects")
                        .forEach((cb) => (cb.checked = false));
                    document.getElementById(
                        "select-all-subjects-checkbox"
                    ).checked = false;
                    this.updateSubjectsBulkActions();
                    // Reload table
                    window.reloadSubjectsTable();
                }

                if (errorCount > 0) {
                    this.showNotificationModal(
                        `Gagal menghapus ${errorCount} mata pelajaran.`
                    );
                }
            })
            .catch((error) => {
                console.error("Bulk delete subjects error:", error);
                this.showNotificationModal(
                    "Terjadi kesalahan saat menghapus mata pelajaran."
                );
            });
    }

    // Fungsi untuk bulk delete kelas
    bulkDeleteClasses(selectedIds) {
        const deletePromises = selectedIds.map((id) =>
            fetch(`/admin/classes/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": getCsrfToken(),
                    Accept: "application/json",
                },
            })
        );

        Promise.all(deletePromises)
            .then((responses) => Promise.all(responses.map((r) => r.json())))
            .then((results) => {
                const successCount = results.filter((r) => r.success).length;
                const errorCount = results.length - successCount;

                if (successCount > 0) {
                    this.showNotificationModal(
                        `Berhasil menghapus ${successCount} kelas.`
                    );
                    // Reset checkboxes
                    document
                        .querySelectorAll(".row-checkbox-classes")
                        .forEach((cb) => (cb.checked = false));
                    document.getElementById(
                        "select-all-classes-checkbox"
                    ).checked = false;
                    this.updateClassesBulkActions();
                    // Reload table
                    window.reloadClassesTable();
                }

                if (errorCount > 0) {
                    this.showNotificationModal(
                        `Gagal menghapus ${errorCount} kelas.`
                    );
                }
            })
            .catch((error) => {
                console.error("Bulk delete classes error:", error);
                this.showNotificationModal(
                    "Terjadi kesalahan saat menghapus kelas."
                );
            });
    }

    // Render subjects table with auto reload
    renderSubjectsTable() {
        console.log("renderSubjectsTable called with auto reload");
        const container = document.getElementById("subjects-table");
        if (!container) {
            console.error("Container subjects-table not found");
            return;
        }

        // Clear container first
        container.innerHTML = "";
        console.log("Container cleared");

        // Destroy existing instance if it exists
        if (this.gridInstanceSubjects) {
            try {
                console.log("Destroying existing GridJS instance...");
                // Cek apakah method destroy tersedia
                if (typeof this.gridInstanceSubjects.destroy === "function") {
                    this.gridInstanceSubjects.destroy();
                    console.log("GridJS instance destroyed successfully");
                } else {
                    console.log(
                        "Destroy method not available, clearing container only"
                    );
                }
            } catch (e) {
                console.log("Error destroying GridJS instance:", e);
            }
        } else {
            console.log("No existing GridJS instance to destroy");
        }

        // Create new GridJS instance with auto reload
        console.log("Creating new GridJS instance with auto reload");
        this.gridInstanceSubjects = new gridjs.Grid({
            columns: [
                {
                    id: "checkbox",
                    name: gridjs.html(
                        '<input type="checkbox" id="select-all-subjects-checkbox">'
                    ),
                    width: "48px",
                    sort: false,
                },
                { name: "Kode" },
                { name: "Mata Pelajaran" },
                {
                    name: "Aksi",
                    width: "200px",
                    sort: false,
                    formatter: (cell, row) => {
                        const subjectId = row.cells[3].data; // ID is now in the fourth column
                        return gridjs.html(`
                            <div class="d-flex gap-2 justify-content-center">
                                <button class="btn btn-sm btn-outline-info" onclick="showSubjectDetail(${subjectId})">
                                    <i class="bx bx-info-circle me-1"></i>Detail
                                </button>
                                <button class="btn btn-sm btn-outline-warning" onclick="editSubject(${subjectId})">
                                    <i class="bx bx-edit me-1"></i>Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDeleteSubject(${subjectId})">
                                    <i class="bx bx-trash me-1"></i>Hapus
                                </button>
                            </div>
                        `);
                    },
                },
            ],
            pagination: { limit: 5 },
            search: true,
            sort: true,
            server: {
                url: "/admin/subjects/by-class",
                then: (data) =>
                    data.map((subject) => [
                        gridjs.html(
                            `<input type="checkbox" class="row-checkbox-subjects" data-id="${subject.id}">`
                        ),
                        subject.code,
                        subject.name,
                        subject.id,
                        null, // Aksi
                    ]),
            },
            language: { search: { placeholder: "Ketik untuk mencari…" } },
        }).render(container);

        console.log(
            "GridJS instance created and rendered successfully with auto reload"
        );

        // Ekspor instance untuk auto reload
        window.gridInstanceSubjects = this.gridInstanceSubjects;
        console.log("GridJS instance exported to window.gridInstanceSubjects");

        // Re-initialize bulk actions after table is rendered
        setTimeout(() => {
            console.log("Re-initializing subjects bulk actions...");
            this.attachSubjectsCheckboxEvents();
        }, 100);
    }

    // Render classes table with auto reload
    renderClassesTable() {
        console.log("renderClassesTable called with auto reload");
        const container = document.getElementById("classes-table");
        if (!container) {
            console.error("Container classes-table not found");
            return;
        }

        // Clear container first
        container.innerHTML = "";
        console.log("Container cleared");

        // Destroy existing instance if it exists
        if (this.gridInstanceClasses) {
            try {
                console.log("Destroying existing GridJS instance...");
                if (typeof this.gridInstanceClasses.destroy === "function") {
                    this.gridInstanceClasses.destroy();
                    console.log("GridJS instance destroyed successfully");
                } else {
                    console.log(
                        "Destroy method not available, clearing container only"
                    );
                }
            } catch (e) {
                console.log("Error destroying GridJS instance:", e);
            }
        } else {
            console.log("No existing GridJS instance to destroy");
        }

        // Create new GridJS instance with auto reload
        console.log("Creating new GridJS instance with auto reload");
        this.gridInstanceClasses = new gridjs.Grid({
            columns: [
                {
                    id: "checkbox",
                    name: gridjs.html(
                        '<input type="checkbox" id="select-all-classes-checkbox">'
                    ),
                    width: "48px",
                    sort: false,
                },
                { name: "Nama Kelas" },
                { name: "Grade" },
                { name: "Display Grade" },
                {
                    name: "Aksi",
                    width: "200px",
                    sort: false,
                    formatter: (cell, row) => {
                        const classId = row.cells[4].data; // ID is now in the fifth column (index 4)
                        return gridjs.html(`
                            <div class="d-flex gap-2 justify-content-center">
                                <button class="btn btn-sm btn-outline-info" onclick="showClassDetail(${classId})">
                                    <i class="bx bx-info-circle me-1"></i>Detail
                                </button>
                                <button class="btn btn-sm btn-outline-warning" onclick="editClass(${classId})">
                                    <i class="bx bx-edit me-1"></i>Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDeleteClass(${classId})">
                                    <i class="bx bx-trash me-1"></i>Hapus
                                </button>
                            </div>
                        `);
                    },
                },
            ],
            pagination: { limit: 5 },
            search: true,
            sort: true,
            server: {
                url: "/admin/classes",
                then: (data) => {
                    console.log("=== CLASSES DATA DEBUG ===");
                    console.log("Classes data received:", data);
                    console.log("Data type:", typeof data);
                    console.log(
                        "Data length:",
                        data ? data.length : "undefined"
                    );
                    console.log("Data is array:", Array.isArray(data));

                    if (data && data.length > 0) {
                        console.log("First item:", data[0]);
                        console.log("First item keys:", Object.keys(data[0]));
                        console.log("First item grade:", data[0].grade);
                        console.log(
                            "First item display_grade:",
                            data[0].display_grade
                        );
                    }

                    if (!data || !Array.isArray(data)) {
                        console.error("Invalid data received:", data);
                        return [];
                    }

                    return data.map((classItem, index) => {
                        console.log(`=== MAPPING ITEM ${index} ===`);
                        console.log("Raw classItem:", classItem);
                        console.log("classItem type:", typeof classItem);
                        console.log("classItem keys:", Object.keys(classItem));
                        console.log(
                            `  - name: '${
                                classItem.name
                            }' (type: ${typeof classItem.name})`
                        );
                        console.log(
                            `  - grade: '${
                                classItem.grade
                            }' (type: ${typeof classItem.grade})`
                        );
                        console.log(
                            `  - display_grade: '${
                                classItem.display_grade
                            }' (type: ${typeof classItem.display_grade})`
                        );
                        console.log(
                            `  - id: ${
                                classItem.id
                            } (type: ${typeof classItem.id})`
                        );

                        const mapped = [
                            gridjs.html(
                                `<input type="checkbox" class="row-checkbox-classes" data-id="${classItem.id}">`
                            ),
                            classItem.name || "",
                            classItem.grade || "",
                            classItem.display_grade || "",
                            classItem.id || "",
                            null, // Aksi
                        ];

                        console.log("Mapped result:", mapped);
                        console.log("=== END MAPPING ITEM ===");

                        return mapped;
                    });
                },
                error: (error) => {
                    console.error("Server error:", error);
                },
            },
            language: { search: { placeholder: "Ketik untuk mencari…" } },
        }).render(container);

        console.log(
            "GridJS instance created and rendered successfully with auto reload"
        );

        // Ekspor instance untuk auto reload
        window.gridInstanceClasses = this.gridInstanceClasses;
        console.log("GridJS instance exported to window.gridInstanceClasses");

        // Re-initialize bulk actions after table is rendered
        setTimeout(() => {
            console.log("Re-initializing classes bulk actions...");
            this.attachClassesCheckboxEvents();
        }, 100);
    }

    // Render terms table with auto reload
    renderTermsTable() {
        const container = document.getElementById("terms-table");
        if (!container) {
            console.error("Container terms-table not found");
            return;
        }

        // Clear container first
        container.innerHTML = "";

        // Destroy existing instance if it exists
        if (this.gridInstanceTerms) {
            try {
                if (typeof this.gridInstanceTerms.destroy === "function") {
                    this.gridInstanceTerms.destroy();
                }
            } catch (e) {
                console.log("Error destroying GridJS instance:", e);
            }
        }

        // Create new GridJS instance with auto reload
        this.gridInstanceTerms = new gridjs.Grid({
            columns: [
                {
                    id: "checkbox",
                    name: gridjs.html(
                        '<input type="checkbox" id="select-all-terms-checkbox">'
                    ),
                    width: "48px",
                    sort: false,
                    formatter: (cell, row) => {
                        const termId = row.cells[5].data; // ID is in the sixth column (index 5)
                        return gridjs.html(
                            `<input type="checkbox" class="row-checkbox-terms" data-id="${termId}">`
                        );
                    },
                },
                { name: "Nama Semester" },
                {
                    name: "Tanggal Mulai",
                    formatter: (cell) => {
                        return new Date(cell).toLocaleDateString("id-ID");
                    },
                },
                {
                    name: "Tanggal Berakhir",
                    formatter: (cell) => {
                        return new Date(cell).toLocaleDateString("id-ID");
                    },
                },
                {
                    name: "Status",
                    formatter: (cell) => {
                        return cell
                            ? gridjs.html(
                                  '<span class="badge bg-success">Aktif</span>'
                              )
                            : gridjs.html(
                                  '<span class="badge bg-secondary">Tidak Aktif</span>'
                              );
                    },
                },
                {
                    name: "Aksi",
                    width: "200px",
                    sort: false,
                    formatter: (cell, row) => {
                        const termId = row.cells[5].data; // ID is in the sixth column (index 5)
                        return gridjs.html(`
                            <div class="d-flex gap-2 justify-content-center">
                                <button class="btn btn-sm btn-outline-warning" onclick="editTerm(${termId})">
                                    <i class="bx bx-edit me-1"></i>Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteTerm(${termId})">
                                    <i class="bx bx-trash me-1"></i>Hapus
                                </button>
                            </div>
                        `);
                    },
                },
            ],
            pagination: { limit: 10 },
            search: true,
            sort: true,
            server: {
                url: "/admin/terms/data",
                then: (data) => {
                    if (!data || !Array.isArray(data)) {
                        console.error("Invalid data received:", data);
                        return [];
                    }

                    return data.map((term) => [
                        "", // Checkbox column
                        term.name,
                        term.start_date,
                        term.end_date,
                        term.is_active,
                        term.id, // Hidden ID column
                    ]);
                },
                total: (data) => {
                    return data.length;
                },
            },
        }).render(container);

        // Ekspor instance untuk auto reload
        window.gridInstanceTerms = this.gridInstanceTerms;

        // Re-initialize bulk actions after table is rendered
        setTimeout(() => {
            this.attachTermsCheckboxEvents();
        }, 100);
    }

    // Fungsi untuk menginisialisasi tabel Jadwal
    initJadwalTable(termId = null) {
        const mount = document.getElementById("table-search");
        if (!mount) return;

        // Build URL with optional term_id parameter
        let serverUrl = "/admin/jadwal";
        if (termId) {
            serverUrl += `?term_id=${termId}`;
        }

        this.gridInstanceJadwal = new gridjs.Grid({
            columns: [
                {
                    id: "checkbox",
                    name: gridjs.html(
                        '<input type="checkbox" id="select-all-jadwal-checkbox">'
                    ),
                    formatter: (cell, row) => {
                        const jadwalId = row.cells[7].data; // ID ada di index 7 (hidden)
                        return gridjs.html(
                            `<input type="checkbox" class="row-checkbox-jadwal" data-id="${jadwalId}">`
                        );
                    },
                },
                { id: "hari", name: "Hari" },
                { id: "jam", name: "Jam" },
                { id: "kelas", name: "Kelas" },
                { id: "mapel", name: "Mata Pelajaran" },
                { id: "guru", name: "Guru" },
                { id: "jenis", name: "Jenis Kelas" },
                { id: "id", name: "ID", hidden: true },
                {
                    id: "aksi",
                    name: "Aksi",
                    formatter: (cell, row) => {
                        const jadwalId = row.cells[7].data; // ID ada di index 7 (hidden)

                        return gridjs.html(`
                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteJadwal('${jadwalId}')">
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
                url: serverUrl,
                then: (data) =>
                    data.map((jadwal) => [
                        null, // checkbox
                        jadwal.hari ?? "-", // Hari
                        jadwal.jam ?? "-", // Jam
                        jadwal.kelas ?? "-", // Kelas
                        jadwal.mapel ?? "-", // Mata Pelajaran
                        jadwal.guru ?? "-", // Guru
                        jadwal.jenis ?? "-", // Jenis Kelas
                        jadwal.id, // ID (hidden)
                        null, // Aksi
                    ]),
            },
            language: { search: { placeholder: "Ketik untuk mencari…" } },
        }).render(mount);

        // Ekspor instance untuk auto reload dari blade
        window.gridInstanceJadwal = this.gridInstanceJadwal;

        // Inisialisasi event listeners setelah render
        this.gridInstanceJadwal.on("ready", () => {
            this.initJadwalEventListeners();
        });
    }

    // Inisialisasi form Import Jadwal XI (grade & kelompok)
    initImportJadwalXiForm() {
        const form = document.getElementById("importJadwalXiForm");
        const gradeSelect = document.getElementById("xiGrade");

        // Set default value to XI for XI import
        if (gradeSelect) {
            gradeSelect.value = "XI";
        }

        if (form) {
            form.addEventListener(
                "submit",
                function (e) {
                    e.preventDefault();
                    const submitBtn = form.querySelector(
                        'button[type="submit"], .modal-footer .btn-success'
                    );
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.textContent = "Mengimport...";
                    }

                    const formData = new FormData(form);
                    fetch(form.action, {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": getCsrfToken(),
                            Accept: "application/json",
                        },
                    })
                        .then((res) => res.json())
                        .then((data) => {
                            showNotification(
                                data.message || "Import selesai",
                                !!data.success
                            );
                            if (data.success) {
                                const modalEl = document.getElementById(
                                    "importJadwalXIModal"
                                );
                                const modal =
                                    bootstrap.Modal.getInstance(modalEl) ||
                                    new bootstrap.Modal(modalEl);
                                modal.hide();
                                form.reset();
                                // Refresh tabel XI jika ada
                                const mount =
                                    document.getElementById("table-search-xi");
                                if (mount) {
                                    // Force reload grid by re-rendering
                                    mount.innerHTML = "";
                                    setTimeout(
                                        () =>
                                            this.initJadwalXiTable &&
                                            this.initJadwalXiTable(),
                                        50
                                    );
                                }
                            }
                        })
                        .catch(() =>
                            showNotification(
                                "Gagal mengimport jadwal XI",
                                false
                            )
                        )
                        .finally(() => {
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                submitBtn.textContent = "Import";
                            }
                        });
                }.bind(this)
            );
        }
    }

    // Fungsi untuk menginisialisasi tabel Jadwal XI (mengikuti style tabel utama)
    initJadwalXiTable(termId = null) {
        const mount = document.getElementById("table-search-xi");
        if (!mount) {
            console.warn(
                "Table mount element not found, skipping XI table initialization"
            );
            return;
        }

        // Get current filter parameters from URL or default
        const urlParams = new URLSearchParams(window.location.search);
        const filterParams = {
            class: urlParams.get("class") || "",
            group_type: urlParams.get("group_type") || "",
            week_type: urlParams.get("week_type") || "",
            location_type: urlParams.get("location_type") || "",
            day: urlParams.get("day") || "",
        };

        // Add term_id parameter if provided
        if (termId) {
            filterParams.term_id = termId;
        }

        // Build query string for server request
        const queryString = new URLSearchParams(filterParams).toString();
        const serverUrl = queryString
            ? `/admin/jadwal-xi?${queryString}`
            : "/admin/jadwal-xi";

        // Debug: Log the server URL being used
        console.log("Loading Jadwal XI with URL:", serverUrl);
        console.log("Current URL params:", window.location.search);

        this.gridInstanceJadwalXI = new gridjs.Grid({
            columns: [
                {
                    id: "checkbox",
                    name: gridjs.html(
                        '<input type="checkbox" id="select-all-jadwal-xi-checkbox">'
                    ),
                    formatter: (cell, row) => {
                        const jadwalId = row.cells[8].data; // ID ada di index 8 (hidden)
                        return gridjs.html(
                            `<input type="checkbox" class="row-checkbox-jadwal-xi" data-id="${jadwalId}">`
                        );
                    },
                    width: "48px",
                },
                { id: "hari", name: "Hari" },
                { id: "jam", name: "Jam" },
                { id: "kelas", name: "Kelas" },
                { id: "mapel", name: "Mata Pelajaran" },
                { id: "guru", name: "Guru" },
                { id: "jenis", name: "Jenis Kelas" },
                { id: "minggu", name: "Minggu" },
                { id: "id", name: "ID", hidden: true },
                {
                    id: "aksi",
                    name: "Aksi",
                    formatter: (cell, row) => {
                        const jadwalId = row.cells[8].data; // ID ada di index 8 (hidden)

                        return gridjs.html(`
                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="openDeleteJadwalXiModal('${jadwalId}')">
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
                url: serverUrl,
                then: (data) => {
                    console.log("Data received:", data);

                    // Handle empty data case
                    if (!data || data.length === 0) {
                        return [
                            [
                                "Tidak ada data jadwal kelas XI yang ditemukan. Silakan import data terlebih dahulu.",
                                "",
                                "",
                                "",
                                "",
                                "",
                                "",
                                "",
                                "",
                            ],
                        ];
                    }

                    return data.map((jadwal) => [
                        null, // checkbox
                        jadwal.hari ?? "-", // Hari
                        jadwal.jam ?? "-", // Jam
                        jadwal.kelas ?? "-", // Kelas
                        jadwal.mapel ?? "-", // Mata Pelajaran
                        jadwal.guru ?? "-", // Guru
                        `${jadwal.kelompok ?? "-"} | ${jadwal.lokasi ?? "-"}`, // Jenis Kelas
                        jadwal.minggu ?? "-", // Minggu
                        jadwal.id, // ID (hidden)
                        null, // Aksi
                    ]);
                },
                catch: (error) => {
                    console.error("Error loading data:", error);
                    // Show error message
                    mount.innerHTML = `
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Error!</h4>
                            <p>Gagal memuat data jadwal. Silakan refresh halaman.</p>
                            <hr>
                            <p class="mb-0">Error: ${
                                error.message || "Unknown error"
                            }</p>
                        </div>
                    `;
                },
            },
            language: { search: { placeholder: "Ketik untuk mencari…" } },
        }).render(mount);

        // Make gridInstanceJadwalXI global for refresh functionality
        window.gridInstanceJadwalXI = this.gridInstanceJadwalXI;

        // expose reload helper for XI table
        window.gridXiReload = () => {
            mount.innerHTML = "";
            setTimeout(
                () => this.initJadwalXiTable && this.initJadwalXiTable(),
                0
            );
        };

        // Tombol filter - buka modal filter
        const filterBtn = document.getElementById("filter-jadwal-xi");
        if (filterBtn) {
            filterBtn.addEventListener("click", () => {
                const modal = new bootstrap.Modal(
                    document.getElementById("filterJadwalXiModal")
                );
                modal.show();
            });
        }

        // XI checkbox bulk selection behavior
        const selectAllXi = document.getElementById(
            "select-all-jadwal-xi-checkbox"
        );
        const bulkActionsXi = document.getElementById("bulk-actions-jadwal-xi");
        const singleActionsXi = document.getElementById(
            "single-actions-jadwal-xi"
        );

        if (selectAllXi) {
            selectAllXi.addEventListener("change", () => {
                const isChecked = selectAllXi.checked;
                const allCheckboxes = document.querySelectorAll(
                    ".row-checkbox-jadwal-xi"
                );
                allCheckboxes.forEach((cb) => (cb.checked = isChecked));
                updateBulkActionsXi();
            });
        }

        document.addEventListener("change", (event) => {
            if (event.target.classList.contains("row-checkbox-jadwal-xi")) {
                updateBulkActionsXi();
            }
        });

        function updateBulkActionsXi() {
            const allCheckboxes = document.querySelectorAll(
                ".row-checkbox-jadwal-xi"
            );
            const checkedBoxes = document.querySelectorAll(
                ".row-checkbox-jadwal-xi:checked"
            );
            if (selectAllXi) {
                selectAllXi.checked =
                    allCheckboxes.length > 0 &&
                    checkedBoxes.length === allCheckboxes.length;
            }
            if (checkedBoxes.length > 0) {
                if (singleActionsXi) singleActionsXi.style.display = "none";
                if (bulkActionsXi) bulkActionsXi.style.display = "block";
            } else {
                if (singleActionsXi) singleActionsXi.style.display = "block";
                if (bulkActionsXi) bulkActionsXi.style.display = "none";
            }
        }
    }

    // Fungsi untuk menginisialisasi form import
    initImportForm() {
        const importForm = document.getElementById("importJadwalForm");
        if (importForm) {
            importForm.addEventListener("submit", function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitButton = this.querySelector(
                    'button[type="submit"]'
                );
                const originalText = submitButton.textContent;

                // Disable button and show loading
                submitButton.disabled = true;
                submitButton.textContent = "Mengimport...";

                fetch(this.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": getCsrfToken(),
                    },
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            // Show success message
                            showNotification(data.message, true);
                            // Close modal
                            const modal = bootstrap.Modal.getInstance(
                                document.getElementById("addUserModal")
                            );
                            modal.hide();
                            // Reset form
                            this.reset();
                            // Refresh table instead of full reload
                            if (window.gridInstanceJadwal) {
                                window.gridInstanceJadwal.forceRender();
                            } else {
                                location.reload();
                            }
                        } else {
                            showNotification(data.message, false);
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        showNotification(
                            "Terjadi kesalahan saat mengimport.",
                            false
                        );
                    })
                    .finally(() => {
                        // Re-enable button
                        submitButton.disabled = false;
                        submitButton.textContent = originalText;
                    });
            });
        }
    }

    // Fungsi untuk menginisialisasi form tambah mata pelajaran
    initSubjectForm() {
        const subjectForm = document.getElementById("addSubjectForm");
        if (subjectForm) {
            subjectForm.addEventListener("submit", function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitButton = this.querySelector(
                    'button[type="submit"]'
                );
                const originalText = submitButton.textContent;

                // Disable button and show loading
                submitButton.disabled = true;
                submitButton.textContent = "Menambah...";

                fetch(this.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": getCsrfToken(),
                        Accept: "application/json",
                    },
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            // Show success message
                            showNotification(data.message, true);
                            // Close modal
                            const modal = bootstrap.Modal.getInstance(
                                document.getElementById("addSubjectModal")
                            );
                            modal.hide();
                            // Reset form
                            this.reset();

                            // Auto reload tabel mata pelajaran setelah tambah berhasil
                            console.log(
                                "Tambah mata pelajaran berhasil, melakukan auto reload tabel..."
                            );
                            window.reloadSubjectsTable();
                        } else {
                            showNotification(data.message, false);
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        showNotification(
                            "Terjadi kesalahan saat menambah mata pelajaran.",
                            false
                        );
                    })
                    .finally(() => {
                        // Re-enable button
                        submitButton.disabled = false;
                        submitButton.textContent = originalText;
                    });
            });
        }
    }

    // Fungsi untuk menginisialisasi form upload mata pelajaran
    initUploadSubjectForm() {
        const uploadSubjectForm = document.getElementById("uploadSubjectForm");
        if (uploadSubjectForm) {
            uploadSubjectForm.addEventListener("submit", function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitButton = this.querySelector(
                    'button[type="submit"]'
                );
                const originalText = submitButton.textContent;

                // Disable button and show loading
                submitButton.disabled = true;
                submitButton.textContent = "Mengimport...";

                // Route updated to subjects.upload to support flexible excel
                const actionUrl =
                    this.getAttribute("action") || "/admin/subjects/upload";
                fetch(actionUrl, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": getCsrfToken(),
                    },
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            // Show success message
                            showNotification(data.message, true);
                            // Close modal
                            const modal = bootstrap.Modal.getInstance(
                                document.getElementById("uploadSubjectModal")
                            );
                            modal.hide();
                            // Reset form
                            this.reset();

                            // Auto reload tabel mata pelajaran setelah import berhasil
                            console.log(
                                "Import berhasil, melakukan auto reload tabel mata pelajaran..."
                            );
                            window.reloadSubjectsTable();

                            // Show errors if any
                            if (data.errors && data.errors.length > 0) {
                                console.warn("Import errors:", data.errors);
                                // Show errors in notification
                                setTimeout(() => {
                                    showNotification(
                                        `Import berhasil, tetapi terdapat ${data.errors.length} error yang perlu diperhatikan.`,
                                        false
                                    );
                                }, 1000);
                            }
                        } else {
                            showNotification(data.message, false);
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        showNotification(
                            "Terjadi kesalahan saat mengimport mata pelajaran.",
                            false
                        );
                    })
                    .finally(() => {
                        // Re-enable button
                        submitButton.disabled = false;
                        submitButton.textContent = originalText;
                    });
            });
        }
    }

    // Fungsi untuk menginisialisasi event listeners jadwal
    initJadwalEventListeners() {
        const selectAllJadwal = document.getElementById(
            "select-all-jadwal-checkbox"
        );
        const bulkActionsJadwal = document.getElementById(
            "bulk-actions-jadwal"
        );
        const singleActionsJadwal = document.getElementById(
            "single-actions-jadwal"
        );

        if (selectAllJadwal) {
            selectAllJadwal.addEventListener("change", () => {
                const isChecked = selectAllJadwal.checked;
                const allCheckboxes = document.querySelectorAll(
                    ".row-checkbox-jadwal"
                );
                allCheckboxes.forEach((cb) => (cb.checked = isChecked));
                updateBulkActionsJadwal();
            });
        }

        document.addEventListener("change", (event) => {
            if (event.target.classList.contains("row-checkbox-jadwal")) {
                updateBulkActionsJadwal();
            }
        });

        const bulkDeleteJadwalBtn =
            document.getElementById("bulk-delete-jadwal");
        if (bulkDeleteJadwalBtn) {
            bulkDeleteJadwalBtn.addEventListener("click", () => {
                const selectedIds = Array.from(
                    document.querySelectorAll(".row-checkbox-jadwal:checked")
                ).map((cb) => cb.dataset.id);
                if (selectedIds.length === 0) return;
                document.getElementById("deleteJadwalIds").value =
                    selectedIds.join(",");
                const modal = new bootstrap.Modal(
                    document.getElementById("bulkDeleteJadwalModal")
                );
                modal.show();
            });
        }

        function updateBulkActionsJadwal() {
            const allCheckboxes = document.querySelectorAll(
                ".row-checkbox-jadwal"
            );
            const checkedBoxes = document.querySelectorAll(
                ".row-checkbox-jadwal:checked"
            );
            if (selectAllJadwal) {
                selectAllJadwal.checked =
                    allCheckboxes.length > 0 &&
                    checkedBoxes.length === allCheckboxes.length;
            }
            if (checkedBoxes.length > 0) {
                if (singleActionsJadwal)
                    singleActionsJadwal.style.display = "none";
                if (bulkActionsJadwal)
                    bulkActionsJadwal.style.display = "block";
            } else {
                if (singleActionsJadwal)
                    singleActionsJadwal.style.display = "block";
                if (bulkActionsJadwal) bulkActionsJadwal.style.display = "none";
            }
        }
    }

    // Fungsi untuk menginisialisasi modal delete jadwal
    initDeleteModal() {
        const confirmDeleteButton = document.getElementById(
            "confirmDeleteJadwalButton"
        );
        if (confirmDeleteButton) {
            confirmDeleteButton.addEventListener("click", function () {
                const jadwalId =
                    document.getElementById("deleteJadwalId").value;
                if (!jadwalId) return;

                fetch(`/admin/jadwal/${jadwalId}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": getCsrfToken(),
                        Accept: "application/json",
                    },
                })
                    .then((response) => response.json())
                    .then((data) => {
                        showNotification(data.message, data.success);
                        if (data.success) {
                            // Close modal
                            const modal = bootstrap.Modal.getInstance(
                                document.getElementById("deleteJadwalModal")
                            );
                            modal.hide();
                            // Refresh table
                            location.reload();
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        showNotification("Gagal menghapus jadwal.", false);
                    });
            });
        }

        const confirmBulkDeleteButton = document.getElementById(
            "confirmBulkDeleteJadwalButton"
        );
        if (confirmBulkDeleteButton) {
            confirmBulkDeleteButton.addEventListener("click", function () {
                const ids = document.getElementById("deleteJadwalIds").value;
                if (!ids) return;

                // Kirim ID sebagai body request
                fetch(`/admin/jadwal/bulk-delete`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": getCsrfToken(),
                        "Content-Type": "application/json",
                        Accept: "application/json",
                    },
                    body: JSON.stringify({ ids: ids }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        showNotification(data.message, data.success);
                        if (data.success) {
                            // Close modal
                            const modal = bootstrap.Modal.getInstance(
                                document.getElementById("bulkDeleteJadwalModal")
                            );
                            modal.hide();
                            // Refresh table
                            location.reload();
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        showNotification("Gagal menghapus jadwal.", false);
                    });
            });
        }
    }

    // Fungsi untuk menginisialisasi modal filter
    initFilterModal() {
        // Load filter options when modal is shown
        const filterModal = document.getElementById("filterJadwalXiModal");
        if (filterModal) {
            filterModal.addEventListener("show.bs.modal", () => {
                this.loadFilterOptions();
            });
        }

        // Apply filter button
        const applyFilterBtn = document.getElementById("applyFilterBtn");
        if (applyFilterBtn) {
            applyFilterBtn.addEventListener("click", () => {
                this.applyFilter();
            });
        }

        // Reset filter button
        const resetFilterBtn = document.getElementById("resetFilterBtn");
        if (resetFilterBtn) {
            resetFilterBtn.addEventListener("click", () => {
                this.resetFilter();
            });
        }
    }

    // Load filter options from server
    loadFilterOptions() {
        fetch("/admin/jadwal-xi/filter-options", {
            headers: { Accept: "application/json" },
        })
            .then((response) => response.json())
            .then((data) => {
                // Populate class dropdown
                const classSelect = document.getElementById("filterClass");
                if (classSelect && data.classes) {
                    classSelect.innerHTML =
                        '<option value="">Semua Kelas</option>';
                    data.classes.forEach((className) => {
                        const option = document.createElement("option");
                        option.value = className;
                        option.textContent = className;
                        classSelect.appendChild(option);
                    });
                }

                // Set current filter values from URL
                this.setCurrentFilterValues();

                // Initialize smart filter dependencies
                this.initSmartFilterDependencies();
            })
            .catch((error) => {
                console.error("Error loading filter options:", error);
            });
    }

    // Set current filter values from URL parameters
    setCurrentFilterValues() {
        const urlParams = new URLSearchParams(window.location.search);

        const filterFields = [
            "class",
            "group_type",
            "week_type",
            "location_type",
            "day",
        ];
        filterFields.forEach((field) => {
            const value = urlParams.get(field);
            const select = document.getElementById(
                `filter${field.charAt(0).toUpperCase() + field.slice(1)}`
            );
            if (select && value) {
                select.value = value;
            }
        });
    }

    // Apply filter
    applyFilter() {
        console.log("Apply filter clicked");

        const form = document.getElementById("filterJadwalXiForm");
        const formData = new FormData(form);

        // Build query string
        const params = new URLSearchParams();
        for (const [key, value] of formData.entries()) {
            if (value) {
                params.append(key, value);
            }
        }

        console.log("Filter parameters:", params.toString());

        // Update URL first
        const newUrl = params.toString()
            ? `?${params.toString()}`
            : window.location.pathname;
        window.history.pushState({}, "", newUrl);

        console.log("URL updated to:", newUrl);

        // Close modal
        const modal = bootstrap.Modal.getInstance(
            document.getElementById("filterJadwalXiModal")
        );
        if (modal) modal.hide();

        // Reload the page to ensure filter is applied correctly
        console.log("Reloading page with new filters...");
        window.location.reload();
    }

    // Reset filter
    resetFilter() {
        console.log("Reset filter clicked");

        const form = document.getElementById("filterJadwalXiForm");
        form.reset();

        // Clear URL parameters first
        window.history.pushState({}, "", window.location.pathname);

        console.log("URL cleared, reloading page...");

        // Reload the page to ensure filter is reset correctly
        window.location.reload();
    }

    // Initialize smart filter dependencies
    initSmartFilterDependencies() {
        const classSelect = document.getElementById("filterClass");
        const groupSelect = document.getElementById("filterGroupType");
        const weekSelect = document.getElementById("filterWeekType");
        const locationSelect = document.getElementById("filterLocationType");

        // Class to Group mapping
        if (classSelect) {
            classSelect.addEventListener("change", (e) => {
                this.updateGroupBasedOnClass(e.target.value, groupSelect);
            });
        }

        // Week to Location mapping
        if (weekSelect) {
            weekSelect.addEventListener("change", (e) => {
                this.updateLocationBasedOnWeek(e.target.value, locationSelect);
            });
        }

        // Group to Location mapping (when group changes, update location if week is selected)
        if (groupSelect) {
            groupSelect.addEventListener("change", (e) => {
                const currentWeek = weekSelect ? weekSelect.value : "";
                if (currentWeek) {
                    this.updateLocationBasedOnWeek(currentWeek, locationSelect);
                }
            });
        }
    }

    // Update group based on selected class
    updateGroupBasedOnClass(selectedClass, groupSelect) {
        if (!groupSelect) return;

        // Define class to group mapping
        const classToGroupMap = {
            // Kelompok A classes
            TKJA: "A",
            TKJC: "A",
            RPLA: "A",
            RPLC: "A",
            KTA: "A",
            DKVA: "A",
            PSPTA: "A",
            // Kelompok B classes
            TKJB: "B",
            RPLB: "B",
            KTB: "B",
            KK: "B",
            DKVB: "B",
            PSPTB: "B",
        };

        if (selectedClass && classToGroupMap[selectedClass]) {
            // Auto-select group based on class
            groupSelect.value = classToGroupMap[selectedClass];

            // Add visual feedback
            this.highlightField(groupSelect, "auto-set");
        } else if (!selectedClass) {
            // Reset group if no class selected
            groupSelect.value = "";
            this.removeHighlight(groupSelect);
        }
    }

    // Update location based on selected week and group
    updateLocationBasedOnWeek(selectedWeek, locationSelect) {
        if (!locationSelect) return;

        // Get current group selection
        const groupSelect = document.getElementById("filterGroupType");
        const selectedGroup = groupSelect ? groupSelect.value : "";

        if (selectedWeek === "ganjil") {
            if (selectedGroup === "A") {
                // Kelompok A: Ganjil = Lab
                locationSelect.value = "lab";
                this.highlightField(locationSelect, "auto-set (Kelompok A)");
            } else if (selectedGroup === "B") {
                // Kelompok B: Ganjil = Teori
                locationSelect.value = "theory";
                this.highlightField(locationSelect, "auto-set (Kelompok B)");
            } else {
                // Default behavior if no group selected
                locationSelect.value = "lab";
                this.highlightField(locationSelect, "auto-set");
            }
        } else if (selectedWeek === "genap") {
            if (selectedGroup === "A") {
                // Kelompok A: Genap = Teori
                locationSelect.value = "theory";
                this.highlightField(locationSelect, "auto-set (Kelompok A)");
            } else if (selectedGroup === "B") {
                // Kelompok B: Genap = Lab
                locationSelect.value = "lab";
                this.highlightField(locationSelect, "auto-set (Kelompok B)");
            } else {
                // Default behavior if no group selected
                locationSelect.value = "theory";
                this.highlightField(locationSelect, "auto-set");
            }
        } else if (!selectedWeek) {
            // Reset location if no week selected
            locationSelect.value = "";
            this.removeHighlight(locationSelect);
        }
    }

    // Highlight field to show it was auto-set
    highlightField(field, type) {
        if (!field) return;

        field.classList.add("filter-field-auto-set");

        // Add a small indicator
        const indicator = document.createElement("small");
        indicator.className = "auto-set-indicator";
        indicator.textContent = `✓ ${type}`;
        indicator.id = `${field.id}-indicator`;

        // Remove existing indicator
        const existingIndicator = document.getElementById(
            `${field.id}-indicator`
        );
        if (existingIndicator) {
            existingIndicator.remove();
        }

        // Add new indicator
        field.parentNode.appendChild(indicator);

        // Remove highlight after 4 seconds
        setTimeout(() => {
            this.removeHighlight(field);
        }, 4000);
    }

    // Remove highlight from field
    removeHighlight(field) {
        if (!field) return;

        field.classList.remove("filter-field-auto-set");

        const indicator = document.getElementById(`${field.id}-indicator`);
        if (indicator) {
            indicator.remove();
        }
    }
}

// Global functions for delete all operations
window.deleteAllSubjects = function () {
    fetch("/admin/subjects/delete-all", {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": getCsrfToken(),
            Accept: "application/json",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            showNotification(data.message, data.success);
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("deleteAllSubjectsModal")
                );
                if (modal) modal.hide();

                // Reload subjects table
                if (typeof window.reloadSubjectsTable === "function") {
                    window.reloadSubjectsTable();
                }
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showNotification(
                "Gagal menghapus semua data mata pelajaran",
                false
            );
        });
};

window.deleteAllClasses = function () {
    fetch("/admin/classes/delete-all", {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": getCsrfToken(),
            Accept: "application/json",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            showNotification(data.message, data.success);
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("deleteAllClassesModal")
                );
                if (modal) modal.hide();

                // Reload classes table
                if (typeof window.reloadClassesTable === "function") {
                    window.reloadClassesTable();
                }
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showNotification("Gagal menghapus semua data kelas", false);
        });
};

window.deleteAllJadwal = function () {
    fetch("/admin/jadwal/delete-all", {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": getCsrfToken(),
            Accept: "application/json",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            showNotification(data.message, data.success);
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("deleteAllJadwalModal")
                );
                if (modal) modal.hide();

                // Reload jadwal table
                if (
                    window.tabelJadwalInstance &&
                    window.tabelJadwalInstance.initJadwalTable
                ) {
                    window.tabelJadwalInstance.initJadwalTable();
                }
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showNotification("Gagal menghapus semua data jadwal", false);
        });
};

window.deleteAllJadwalXi = function () {
    fetch("/admin/jadwal-xi/delete-all", {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": getCsrfToken(),
            Accept: "application/json",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            showNotification(data.message, data.success);
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("deleteAllJadwalXiModal")
                );
                if (modal) modal.hide();

                // Reload jadwal XI table
                if (
                    window.tabelJadwalInstance &&
                    window.tabelJadwalInstance.initJadwalXiTable
                ) {
                    window.tabelJadwalInstance.initJadwalXiTable();
                }
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showNotification("Gagal menghapus semua data jadwal XI", false);
        });
};

// Inisialisasi class
window.tabelJadwalInstance = new GridJadwalDatatable();
