// Admin User Management JavaScript
// This file handles all user management functionality including modals, forms, and filters

(function () {
    "use strict";

    // Functions to show edit modals (exposed globally)
    window.showEditUserModal = function (id, name, email, phone, status) {
        document.getElementById("editUserId").value = id;
        document.getElementById("editUserName").value = name;
        document.getElementById("editUserEmail").value = email;
        document.getElementById("editUserPhone").value = phone;
        document.getElementById("editUserStatus").value = status;
        const modal = new bootstrap.Modal(
            document.getElementById("editUserModal")
        );
        modal.show();
    };

    window.showEditGuruModal = function (
        id,
        name,
        email,
        nip,
        department,
        kode_guru
    ) {
        document.getElementById("editGuruId").value = id;
        document.getElementById("editGuruName").value = name;
        document.getElementById("editGuruEmail").value = email;
        document.getElementById("editGuruNip").value = nip;
        document.getElementById("editGuruDepartment").value = department;
        document.getElementById("editGuruKode").value = kode_guru;
        const modal = new bootstrap.Modal(
            document.getElementById("editGuruModal")
        );
        modal.show();
    };

    window.showEditMuridModal = function (
        id,
        name,
        email,
        nis,
        class_id,
        guardian_name,
        guardian_phone,
        grade
    ) {
        document.getElementById("editMuridId").value = id;
        document.getElementById("editMuridName").value = name;
        document.getElementById("editMuridEmail").value = email;
        document.getElementById("editMuridNis").value = nis;
        document.getElementById("editMuridTingkatan").value = grade;
        document.getElementById("editMuridClass").value = class_id;
        document.getElementById("editMuridGuardianName").value =
            guardian_name;
        document.getElementById("editMuridGuardianPhone").value =
            guardian_phone;
        const modal = new bootstrap.Modal(
            document.getElementById("editMuridModal")
        );
        modal.show();
    };

    document.addEventListener("DOMContentLoaded", function () {
        // Helper function to remove focus before modal is hidden to prevent accessibility warning
        function setupModalAccessibility(modalId) {
            const modalEl = document.getElementById(modalId);
            if (!modalEl) return;

            // Remove focus from close buttons before hiding
            const closeButtons = modalEl.querySelectorAll(
                ".btn-close, [data-bs-dismiss='modal']"
            );
            closeButtons.forEach((btn) => {
                btn.addEventListener("click", (e) => {
                    e.target.blur(); // Remove focus before hiding
                });
            });

            // Handle when modal is hidden via Bootstrap events
            modalEl.addEventListener(
                "hidden.bs.modal",
                function () {
                    // Remove focus from any focused element inside modal
                    const focusedElement = this.querySelector(":focus");
                    if (focusedElement) {
                        focusedElement.blur();
                    }
                },
                { once: false }
            );
        }

        // Setup accessibility for all modals
        const modalIds = [
            "addGuruModal",
            "uploadGuruModal",
            "addMuridModal",
            "uploadMuridModal",
            "editGuruModal",
            "editMuridModal",
            "deleteGuruModal",
            "deleteMuridModal",
            "addUserModal",
            "editUserModal",
            "deleteUserModal",
            "bulkEditStatusModal",
            "notificationModal",
        ];

        modalIds.forEach((modalId) => setupModalAccessibility(modalId));

        const notificationModal = new bootstrap.Modal(
            document.getElementById("notificationModal")
        );
        const notificationModalElement =
            document.getElementById("notificationModal");

        // Reload page when notification modal is closed
        notificationModalElement.addEventListener("hidden.bs.modal", function () {
            window.location.reload();
        });

        // Ensure close buttons work - remove focus before hiding to prevent accessibility warning
        document
            .querySelector("#notificationModal .btn-close")
            ?.addEventListener("click", (e) => {
                e.target.blur(); // Remove focus before hiding
                notificationModal.hide();
            });
        document
            .querySelector("#notificationModal .btn-light")
            ?.addEventListener("click", (e) => {
                e.target.blur(); // Remove focus before hiding
                notificationModal.hide();
            });

        function showNotification(message, isSuccess = true, errors = null) {
            const modalLabel = document.getElementById("notificationModalLabel");
            const modalMessage = document.getElementById("notificationMessage");
            const errorsContainer = document.getElementById("notificationErrors");
            const errorsBody = document.getElementById("notificationErrorsBody");

            // Set modal title and message
            modalLabel.innerText = isSuccess ? "Berhasil" : "Gagal";
            modalLabel.className =
                "modal-title " + (isSuccess ? "text-success" : "text-danger");
            modalMessage.innerText = message;

            // Show/hide errors table
            if (errors && errors.length > 0) {
                errorsContainer.classList.remove("d-none");
                errorsBody.innerHTML = "";

                errors.forEach((error) => {
                    const row = document.createElement("tr");
                    // Support both teacher and student error formats
                    const name = error.nama_guru || error.nama || "(kosong)";
                    const identifier =
                        error.kode_guru || error.nis || "(kosong)";
                    const identifierLabel = error.kode_guru
                        ? "Kode Guru"
                        : error.nis
                        ? "NIS"
                        : "";

                    row.innerHTML = `
                        <td>${error.row}</td>
                        <td>${name}</td>
                        <td>${
                            identifierLabel ? identifierLabel + ": " : ""
                        }${identifier}</td>
                        <td>
                            <ul class="mb-0 ps-3">
                                ${error.errors
                                    .map((err) => `<li>${err}</li>`)
                                    .join("")}
                            </ul>
                        </td>
                    `;
                    errorsBody.appendChild(row);
                });
            } else {
                errorsContainer.classList.add("d-none");
            }

            notificationModal.show();
        }

        function refreshTable() {
            if (window.gridInstance) {
                window.gridInstance.forceRender();
            }
        }

        // Add User form submission handler
        const addUserForm = document.getElementById("addUserForm");
        if (addUserForm) {
            addUserForm.addEventListener("submit", async function (event) {
                event.preventDefault();
                const formData = new FormData(this);
                try {
                    const response = await fetch(this.action, {
                        method: "POST",
                        headers: {
                            Accept: "application/json",
                        },
                        body: formData,
                    });
                    const data = await response.json();
                    if (data.errors) {
                        data.message =
                            "Validasi gagal: " +
                            Object.values(data.errors).flat().join(", ");
                        data.success = false;
                    } else if (!response.ok) {
                        showNotification(
                            data.message || "Terjadi kesalahan server",
                            false
                        );
                        return;
                    }
                    bootstrap.Modal.getInstance(
                        document.getElementById("addUserModal")
                    ).hide();
                    showNotification(data.message, data.success);
                    if (data.success) {
                        this.reset();
                        refreshTable();
                    }
                } catch (error) {
                    console.error("Error:", error);
                    showNotification(
                        "Gagal menambahkan user. Periksa data atau koneksi.",
                        false
                    );
                }
            });
        }

        // Upload Guru form submission
        const uploadGuruForm = document.getElementById("uploadGuruForm");
        if (uploadGuruForm) {
            uploadGuruForm.addEventListener("submit", function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const submitButton = document.getElementById(
                    "uploadGuruSubmitBtn"
                );
                const spinner = document.getElementById("uploadGuruSpinner");
                const btnText = document.getElementById("uploadGuruBtnText");
                const fileInput = document.getElementById("uploadGuruFile");
                const uploadModal = bootstrap.Modal.getInstance(
                    document.getElementById("uploadGuruModal")
                );

                // Show loading state
                submitButton.disabled = true;
                spinner.classList.remove("d-none");
                btnText.textContent = "Mengupload...";
                fileInput.disabled = true;

                fetch(this.action, {
                    method: "POST",
                    body: formData,
                })
                    .then((response) => response.json())
                    .then((data) => {
                        showNotification(data.message, data.success, data.errors);
                        if (data.success && data.error_count === 0) {
                            uploadModal.hide();
                            this.reset();
                            // Refresh table
                            if (window.gridInstanceGuru)
                                window.gridInstanceGuru.forceRender();
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        showNotification(
                            "Gagal mengupload data. " + (error.message || ""),
                            false
                        );
                    })
                    .finally(() => {
                        submitButton.disabled = false;
                        spinner.classList.add("d-none");
                        btnText.textContent = "Upload";
                        fileInput.disabled = false;
                    });
            });
        }

        // Upload Murid form submission
        const uploadMuridForm = document.getElementById("uploadMuridForm");
        if (uploadMuridForm) {
            uploadMuridForm.addEventListener("submit", function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const submitButton = document.getElementById(
                    "uploadMuridSubmitBtn"
                );
                const spinner = document.getElementById("uploadMuridSpinner");
                const btnText = document.getElementById("uploadMuridBtnText");
                const fileInput = document.getElementById("uploadMuridFile");
                const uploadModal = bootstrap.Modal.getInstance(
                    document.getElementById("uploadMuridModal")
                );

                // Show loading state
                submitButton.disabled = true;
                spinner.classList.remove("d-none");
                btnText.textContent = "Mengupload...";
                fileInput.disabled = true;

                fetch(this.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                })
                    .then(async (response) => {
                        // Handle 504 Gateway Timeout specially
                        if (response.status === 504) {
                            throw new Error("TIMEOUT_504");
                        }

                        const contentType = response.headers.get("content-type");
                        if (
                            contentType &&
                            contentType.includes("application/json")
                        ) {
                            return response.json();
                        } else {
                            // If response is not JSON, try to get text
                            const text = await response.text();
                            throw new Error(
                                `Server mengembalikan response yang tidak valid. Status: ${response.status}`
                            );
                        }
                    })
                    .then((data) => {
                        // Close upload modal first
                        uploadModal.hide();

                        showNotification(data.message, data.success, data.errors);
                        if (data.success && data.error_count === 0) {
                            this.reset();
                            // Refresh table
                            if (window.gridInstanceMurid)
                                window.gridInstanceMurid.forceRender();
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        let errorMessage = "";

                        // Close upload modal first
                        uploadModal.hide();

                        // Special handling for 504 Gateway Timeout
                        if (
                            error.message === "TIMEOUT_504" ||
                            error.message.includes("504")
                        ) {
                            errorMessage =
                                "Import memakan waktu terlalu lama dan server timeout.\n\n";
                            errorMessage +=
                                "Namun, data mungkin sudah berhasil diimpor ke database.\n";
                            errorMessage +=
                                "Silakan refresh halaman atau cek tabel data murid untuk memastikan.\n\n";
                            errorMessage +=
                                "Jika data sudah masuk, berarti import berhasil meskipun ada timeout.";

                            // Show warning instead of error, and suggest to refresh
                            showNotification(errorMessage, true); // Show as success/warning

                            // Auto refresh table after a delay to check if data was imported
                            setTimeout(() => {
                                if (window.gridInstanceMurid) {
                                    window.gridInstanceMurid.forceRender();
                                }
                            }, 2000);

                            return; // Exit early
                        }

                        // Regular error handling
                        errorMessage = "Gagal mengupload data.";
                        if (error.message) {
                            errorMessage += " " + error.message;
                        } else {
                            errorMessage += " Terjadi kesalahan pada server.";
                        }
                        showNotification(errorMessage, false);
                    })
                    .finally(() => {
                        submitButton.disabled = false;
                        spinner.classList.add("d-none");
                        btnText.textContent = "Upload";
                        fileInput.disabled = false;
                    });
            });
        }

        // Filter class based on tingkatan for add murid
        const addMuridTingkatan = document.getElementById("addMuridTingkatan");
        if (addMuridTingkatan) {
            addMuridTingkatan.addEventListener("change", function () {
                const selectedGrade = this.value;
                const classSelect = document.getElementById("addMuridClass");
                const options = classSelect.querySelectorAll("option");
                options.forEach((option) => {
                    if (option.value === "") return;
                    if (option.getAttribute("data-grade") === selectedGrade) {
                        option.style.display = "";
                    } else {
                        option.style.display = "none";
                    }
                });
                classSelect.value = "";
            });
        }

        // Add Murid form submission
        const addMuridForm = document.getElementById("addMuridForm");
        if (addMuridForm) {
            addMuridForm.addEventListener("submit", function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch(this.action, {
                    method: "POST",
                    body: formData,
                })
                    .then((response) => response.json())
                    .then((data) => {
                        showNotification(data.message, data.success);
                        if (data.success) {
                            bootstrap.Modal.getInstance(
                                document.getElementById("addMuridModal")
                            ).hide();
                            this.reset();
                            // Refresh table
                            if (window.gridInstance)
                                window.gridInstance.forceRender();
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        showNotification("Gagal menambah murid.", false);
                    });
            });
        }

        // LOGIKA EDIT USER
        const editUserForm = document.getElementById("editUserForm");
        if (editUserForm) {
            editUserForm.addEventListener("submit", async function (event) {
                event.preventDefault();
                const userId = document.getElementById("editUserId").value;
                const formData = new FormData(editUserForm);
                formData.append("_method", "PUT"); // Method spoofing

                try {
                    const response = await fetch(`/admin/user/${userId}`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN":
                                document
                                    .querySelector('meta[name="csrf-token"]')
                                    ?.getAttribute("content") || "",
                        },
                        body: formData,
                    });
                    const data = await response.json();
                    bootstrap.Modal.getInstance(
                        document.getElementById("editUserModal")
                    ).hide();
                    showNotification(data.message, data.success);
                    if (data.success) {
                        refreshTable();
                        // Auto-reorder table after status change
                        setTimeout(() => {
                            if (
                                window.gridInstance &&
                                window.gridInstance.reorderTableByStatus
                            ) {
                                window.gridInstance.reorderTableByStatus();
                            }
                        }, 500);
                    }
                } catch (error) {
                    console.error("Error:", error);
                }
            });
        }

        // Filter class based on tingkatan for edit murid
        const editMuridTingkatan = document.getElementById(
            "editMuridTingkatan"
        );
        if (editMuridTingkatan) {
            editMuridTingkatan.addEventListener("change", function () {
                const selectedGrade = this.value;
                const classSelect = document.getElementById("editMuridClass");
                const options = classSelect.querySelectorAll("option");
                options.forEach((option) => {
                    if (option.value === "") return;
                    if (option.getAttribute("data-grade") === selectedGrade) {
                        option.style.display = "";
                    } else {
                        option.style.display = "none";
                    }
                });
                classSelect.value = "";
            });
        }

        // LOGIKA EDIT MURID
        const editMuridForm = document.getElementById("editMuridForm");
        if (editMuridForm) {
            editMuridForm.addEventListener("submit", async function (event) {
                event.preventDefault();
                const muridId = document.getElementById("editMuridId").value;
                const formData = new FormData(editMuridForm);
                formData.append("_method", "PUT"); // Method spoofing

                try {
                    const response = await fetch(`/admin/murid/${muridId}`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN":
                                document
                                    .querySelector('meta[name="csrf-token"]')
                                    ?.getAttribute("content") || "",
                        },
                        body: formData,
                    });
                    const data = await response.json();
                    bootstrap.Modal.getInstance(
                        document.getElementById("editMuridModal")
                    ).hide();
                    showNotification(data.message, data.success);
                    if (data.success) {
                        refreshTable();
                    }
                } catch (error) {
                    console.error("Error:", error);
                }
            });
        }

        // Export User Data function (exposed globally)
        window.exportUserData = function (format = "pdf") {
            try {
                // Get export URL from window object (set by Blade template)
                const exportUrl =
                    (window.exportUserUrl || "") + "?format=" + format;

                if (!window.exportUserUrl) {
                    console.error("Export URL not found");
                    showNotification(
                        "Konfigurasi export tidak ditemukan.",
                        false
                    );
                    return;
                }

                // Show loading message
                const notificationModal = new bootstrap.Modal(
                    document.getElementById("notificationModal")
                );
                document.getElementById("notificationModalLabel").innerText =
                    "Export Data";
                document.getElementById("notificationMessage").innerText =
                    "Sedang mengexport data user ke PDF...";
                notificationModal.show();

                // Use fetch to get PDF blob (handles mixed content issues better)
                fetch(exportUrl, {
                    method: "GET",
                    headers: {
                        Accept: "application/pdf",
                    },
                    // Allow credentials if needed
                    credentials: "same-origin",
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error(
                                "Gagal mengexport data. Status: " +
                                    response.status
                            );
                        }
                        return response.blob();
                    })
                    .then((blob) => {
                        // Verify blob is valid
                        if (!blob || blob.size === 0) {
                            throw new Error(
                                "File PDF kosong atau tidak valid"
                            );
                        }

                        // Verify it's actually a PDF
                        if (
                            blob.type !== "application/pdf" &&
                            !blob.type.includes("pdf")
                        ) {
                            console.warn("Unexpected content type:", blob.type);
                        }

                        // Create blob URL
                        const blobUrl = window.URL.createObjectURL(blob);

                        // Create download link
                        const link = document.createElement("a");
                        link.href = blobUrl;
                        link.download =
                            "data_user_" +
                            new Date()
                                .toISOString()
                                .slice(0, 19)
                                .replace(/[:-]/g, "")
                                .replace("T", "_") +
                            ".pdf";
                        link.style.display = "none";

                        // Append to body
                        document.body.appendChild(link);

                        // Trigger download
                        link.click();

                        // Clean up after download starts
                        setTimeout(() => {
                            document.body.removeChild(link);
                            window.URL.revokeObjectURL(blobUrl);

                            // Hide loading and show success
                            notificationModal.hide();
                            showNotification(
                                "Export berhasil! File PDF akan terdownload.",
                                true
                            );
                        }, 1000);
                    })
                    .catch((error) => {
                        console.error("Export error:", error);
                        notificationModal.hide();

                        // Provide more specific error message
                        let errorMessage = "Gagal mengexport data.";
                        if (
                            error.message.includes("Failed to fetch") ||
                            error.message.includes("network")
                        ) {
                            errorMessage +=
                                " Masalah koneksi atau server tidak dapat diakses.";
                        } else if (
                            error.message.includes("mixed content") ||
                            error.message.includes("insecure")
                        ) {
                            errorMessage +=
                                " Browser memblokir karena koneksi tidak aman. Silakan gunakan HTTPS atau izinkan mixed content di pengaturan browser.";
                        } else {
                            errorMessage += " " + error.message;
                        }

                        showNotification(errorMessage, false);
                    });
            } catch (error) {
                console.error("Export error:", error);
                showNotification(
                    "Gagal mengexport data: " + (error.message || ""),
                    false
                );
            }
        };
    });

    // Filter event listeners untuk Data User tab
    // Wait for both DOM and grid instance to be ready
    function setupUserFilters() {
        const roleFilter = document.getElementById("roleFilter");
        const classFilter = document.getElementById("classFilter");

        if (!roleFilter || !classFilter) {
            // Retry after a short delay if elements not found
            setTimeout(setupUserFilters, 100);
            return;
        }

        // Function to reload table with current filters
        function reloadUserTable() {
            if (!window.gridInstance || !window.gridInstance.updateConfig) {
                console.log("Grid instance not ready yet");
                return;
            }

            const roleValue = roleFilter.value || "";
            const classValue = classFilter.value || "";
            const params = new URLSearchParams();
            if (roleValue) params.append("role_filter", roleValue);
            if (classValue) params.append("class_filter", classValue);
            const url = params.toString()
                ? `/admin/users/table?${params.toString()}`
                : "/admin/users/table";

            console.log("Reloading table with URL:", url);

            window.gridInstance.updateConfig({
                server: {
                    url: url,
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
            }).forceRender();
        }

        // Event listener untuk filter role
        roleFilter.addEventListener("change", function () {
            console.log("Role filter changed to:", this.value);
            // Show/hide class filter based on role selection
            if (this.value === "student") {
                classFilter.style.display = "inline-block";
            } else {
                classFilter.style.display = "none";
                classFilter.value = ""; // Reset class filter
            }
            reloadUserTable();
        });

        // Event listener untuk filter kelas
        classFilter.addEventListener("change", function () {
            console.log("Class filter changed to:", this.value);
            reloadUserTable();
        });
    }

    // Try to setup filters immediately
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", function () {
            // Wait a bit for grid to initialize
            setTimeout(setupUserFilters, 500);
        });
    } else {
        // DOM already loaded, wait for grid
        setTimeout(setupUserFilters, 500);
    }

    // Force apply mobile styles for Data User tab buttons
    function applyMobileButtonStyles() {
        if (window.innerWidth <= 575.98) {
            const singleActions = document.querySelector(
                "#semua #single-actions"
            );
            if (singleActions) {
                singleActions.style.width = "100%";
                singleActions.style.display = "flex";
                singleActions.style.justifyContent = "center";
                singleActions.style.alignItems = "center";
                singleActions.style.gap = "0.5rem";
                singleActions.style.marginLeft = "0";
                singleActions.style.marginRight = "0";
                singleActions.style.marginTop = "1.5rem";

                // Apply to buttons
                const buttons = singleActions.querySelectorAll(
                    ".btn, .btn-group"
                );
                buttons.forEach((btn) => {
                    btn.style.flex = "0 0 auto";
                    btn.style.width = "auto";
                    btn.style.minWidth = "auto";
                    btn.style.maxWidth = "none";
                });
            }
        }
    }

    // Apply on load and resize
    window.addEventListener("load", applyMobileButtonStyles);
    window.addEventListener("resize", applyMobileButtonStyles);
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", applyMobileButtonStyles);
    } else {
        applyMobileButtonStyles();
    }
})();

