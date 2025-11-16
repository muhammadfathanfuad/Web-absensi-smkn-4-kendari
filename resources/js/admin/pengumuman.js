// Admin Pengumuman JavaScript
// This file handles all announcement functionality including CRUD operations
(function () {
    "use strict";

    let announcements = [];

    // Load announcements on page load
    document.addEventListener("DOMContentLoaded", function () {
        loadAnnouncements();
    });

    // Load announcements from server
    function loadAnnouncements() {
        fetch("/admin/pengumuman/data")
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    announcements = data.data;
                    renderAnnouncementsTable();
                }
            })
            .catch((error) => {
                console.error("Error loading announcements:", error);
            });
    }

    // Render announcements table
    function renderAnnouncementsTable() {
        const tbody = document.getElementById("announcementsTableBody");

        if (announcements.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="text-muted">
                            <iconify-icon icon="solar:megaphone-outline" class="fs-48 d-block mb-2"></iconify-icon>
                            Belum ada pengumuman.
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = announcements
            .map(
                (announcement, index) => `
            <tr>
                <td>${index + 1}</td>
                <td>
                    <div>
                        <h6 class="mb-0">${announcement.title}</h6>
                        <small class="text-muted">${announcement.content.substring(0, 50)}${announcement.content.length > 50 ? "..." : ""}</small>
                    </div>
                </td>
                <td>
                    ${getTargetBadge(announcement.target)}
                </td>
                <td>
                    ${getStatusBadge(announcement.is_active)}
                </td>
                <td>${formatDate(announcement.created_at)}</td>
                <td>${announcement.expires_at ? formatDate(announcement.expires_at) : "-"}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-horizontal-rounded"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#" onclick="viewAnnouncement(${announcement.id}); return false;">
                                    <i class="bx bx-show me-2"></i> Lihat
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="editAnnouncement(${announcement.id}); return false;">
                                    <i class="bx bx-edit me-2"></i> Edit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="toggleStatus(${announcement.id}, ${!announcement.is_active}); return false;">
                                    <i class="bx bx-${announcement.is_active ? "x" : "check"}-circle me-2"></i> 
                                    ${announcement.is_active ? "Nonaktifkan" : "Aktifkan"}
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="deleteAnnouncement(${announcement.id}); return false;">
                                    <i class="bx bx-trash me-2"></i> Hapus
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
        `
            )
            .join("");
    }

    // Helper functions
    function getTargetBadge(target) {
        const badges = {
            all: '<span class="badge bg-primary-subtle text-primary py-1 px-2"><i class="bx bx-group me-1"></i> Semua</span>',
            teachers: '<span class="badge bg-info-subtle text-info py-1 px-2"><i class="bx bx-user me-1"></i> Guru</span>',
            students: '<span class="badge bg-success-subtle text-success py-1 px-2"><i class="bx bx-user-circle me-1"></i> Siswa</span>',
        };
        return badges[target] || badges["all"];
    }

    function getStatusBadge(isActive) {
        if (isActive) {
            return '<span class="badge bg-success-subtle text-success py-1 px-2"><i class="bx bx-check-circle me-1"></i> Aktif</span>';
        } else {
            return '<span class="badge bg-secondary-subtle text-secondary py-1 px-2"><i class="bx bx-x-circle me-1"></i> Tidak Aktif</span>';
        }
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "2-digit",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
    }

    // View announcement function
    function viewAnnouncement(id) {
        const announcement = announcements.find((a) => a.id === id);
        if (!announcement) return;

        const content = `
            <div class="announcement-detail">
                <h6>${announcement.title}</h6>
                <p>${announcement.content}</p>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Target:</strong> ${announcement.target_display || "Semua"}
                    </div>
                    <div class="col-md-6">
                        <strong>Prioritas:</strong> ${announcement.priority}
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <strong>Dibuat:</strong> ${formatDate(announcement.created_at)}
                    </div>
                    <div class="col-md-6">
                        <strong>Berakhir:</strong> ${announcement.expires_at ? formatDate(announcement.expires_at) : "Tidak ada"}
                    </div>
                </div>
            </div>
        `;

        document.getElementById("viewAnnouncementContent").innerHTML = content;
        new bootstrap.Modal(document.getElementById("viewAnnouncementModal")).show();
    }

    // Make viewAnnouncement globally available
    window.viewAnnouncement = viewAnnouncement;

    // Edit announcement function
    function editAnnouncement(id) {
        const announcement = announcements.find((a) => a.id === id);
        if (!announcement) return;

        const content = `
            <div class="row">
                <div class="col-12 mb-3">
                    <label for="edit_title" class="form-label">Judul Pengumuman <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="edit_title" name="title" value="${announcement.title}" required>
                </div>
                <div class="col-12 mb-3">
                    <label for="edit_content" class="form-label">Isi Pengumuman <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="edit_content" name="content" rows="5" required>${announcement.content}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="edit_target" class="form-label">Target Pengumuman <span class="text-danger">*</span></label>
                    <select class="form-select" id="edit_target" name="target" required>
                        <option value="all" ${announcement.target === "all" ? "selected" : ""}>Semua (Guru & Siswa)</option>
                        <option value="teachers" ${announcement.target === "teachers" ? "selected" : ""}>Guru Saja</option>
                        <option value="students" ${announcement.target === "students" ? "selected" : ""}>Siswa Saja</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="edit_priority" class="form-label">Prioritas</label>
                    <select class="form-select" id="edit_priority" name="priority">
                        <option value="normal" ${announcement.priority === "normal" ? "selected" : ""}>Normal</option>
                        <option value="high" ${announcement.priority === "high" ? "selected" : ""}>Tinggi</option>
                        <option value="urgent" ${announcement.priority === "urgent" ? "selected" : ""}>Mendesak</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="edit_category" class="form-label">Kategori</label>
                    <select class="form-select" id="edit_category" name="category">
                        <option value="umum" ${announcement.category === "umum" ? "selected" : ""}>Umum</option>
                        <option value="akademik" ${announcement.category === "akademik" ? "selected" : ""}>Akademik</option>
                        <option value="kegiatan" ${announcement.category === "kegiatan" ? "selected" : ""}>Kegiatan</option>
                        <option value="penting" ${announcement.category === "penting" ? "selected" : ""}>Penting</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="edit_expires_at" class="form-label">Tanggal Berakhir</label>
                    <input type="datetime-local" class="form-control" id="edit_expires_at" name="expires_at" value="${announcement.expires_at ? new Date(announcement.expires_at).toISOString().slice(0, 16) : ""}">
                </div>
                <div class="col-12 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active" value="1" ${announcement.is_active ? "checked" : ""}>
                        <label class="form-check-label" for="edit_is_active">
                            Aktifkan pengumuman
                        </label>
                    </div>
                </div>
            </div>
        `;

        document.getElementById("editAnnouncementContent").innerHTML = content;
        document.getElementById("editAnnouncementForm").action = `/admin/pengumuman/${id}`;
        new bootstrap.Modal(document.getElementById("editAnnouncementModal")).show();
    }

    // Make editAnnouncement globally available
    window.editAnnouncement = editAnnouncement;

    // Toggle status function
    function toggleStatus(id, status) {
        const announcement = announcements.find((ann) => ann.id === id);
        if (!announcement) return;

        const action = status ? "mengaktifkan" : "menonaktifkan";
        const title = status ? "Aktifkan Pengumuman" : "Nonaktifkan Pengumuman";
        const message = `Apakah Anda yakin ingin ${action} pengumuman "${announcement.title}"?`;

        // Update modal content
        document.getElementById("confirmTitle").textContent = title;
        document.getElementById("confirmMessage").textContent = message;

        // Update button color based on action
        const confirmBtn = document.getElementById("confirmActionBtn");
        if (status) {
            confirmBtn.className = "btn btn-success";
            confirmBtn.innerHTML = '<i class="bx bx-check me-1"></i>Ya, Aktifkan';
        } else {
            confirmBtn.className = "btn btn-warning";
            confirmBtn.innerHTML = '<i class="bx bx-x me-1"></i>Ya, Nonaktifkan';
        }

        // Remove existing event listeners
        const newConfirmBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

        // Add new event listener
        newConfirmBtn.addEventListener("click", function () {
            performToggleStatus(id, status);
        });

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById("confirmModal"));
        modal.show();
    }

    // Make toggleStatus globally available
    window.toggleStatus = toggleStatus;

    // Perform the actual toggle status
    function performToggleStatus(id, status) {
        console.log("Sending toggle request:", {
            id: id,
            status: status,
            statusType: typeof status,
        });

        fetch(`/admin/pengumuman/${id}/toggle-status`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({ is_active: status }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Hide modal
                    const modal = bootstrap.Modal.getInstance(
                        document.getElementById("confirmModal")
                    );
                    modal.hide();

                    // Reload announcements and show notification
                    loadAnnouncements();
                    showNotification("Status pengumuman berhasil diubah", true);
                } else {
                    showNotification("Gagal mengubah status pengumuman", false);
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showNotification(
                    "Terjadi kesalahan saat mengubah status pengumuman",
                    false
                );
            });
    }

    // Delete announcement function
    function deleteAnnouncement(id) {
        if (
            confirm(
                "Apakah Anda yakin ingin menghapus pengumuman ini? Tindakan ini tidak dapat dibatalkan."
            )
        ) {
            fetch(`/admin/pengumuman/${id}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        loadAnnouncements();
                        showNotification("Pengumuman berhasil dihapus", true);
                    } else {
                        showNotification("Gagal menghapus pengumuman", false);
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    showNotification(
                        "Terjadi kesalahan saat menghapus pengumuman",
                        false
                    );
                });
        }
    }

    // Make deleteAnnouncement globally available
    window.deleteAnnouncement = deleteAnnouncement;

    // Form submission handlers
    const createForm = document.getElementById("createAnnouncementForm");
    if (createForm) {
        createForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch("/admin/pengumuman", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(
                            document.getElementById("createAnnouncementModal")
                        ).hide();
                        this.reset();
                        loadAnnouncements();
                        showNotification("Pengumuman berhasil dibuat", true);
                    } else {
                        showNotification("Gagal membuat pengumuman", false);
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    showNotification(
                        "Terjadi kesalahan saat membuat pengumuman",
                        false
                    );
                });
        });
    }

    const editForm = document.getElementById("editAnnouncementForm");
    if (editForm) {
        editForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const announcementId = this.action.split("/").pop();

            fetch(`/admin/pengumuman/${announcementId}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    "X-HTTP-Method-Override": "PUT",
                },
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(
                            document.getElementById("editAnnouncementModal")
                        ).hide();
                        loadAnnouncements();
                        showNotification("Pengumuman berhasil diperbarui", true);
                    } else {
                        showNotification("Gagal memperbarui pengumuman", false);
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    showNotification(
                        "Terjadi kesalahan saat memperbarui pengumuman",
                        false
                    );
                });
        });
    }

    // Notification function
    function showNotification(message, isSuccess) {
        const notificationModal = new bootstrap.Modal(
            document.getElementById("notificationModal")
        );
        const modalLabel = document.getElementById("notificationModalLabel");
        const modalMessage = document.getElementById("notificationMessage");

        // Set modal title and message
        modalLabel.innerText = isSuccess ? "Berhasil" : "Gagal";
        modalLabel.className =
            "modal-title " + (isSuccess ? "text-success" : "text-danger");
        modalMessage.innerText = message;

        notificationModal.show();
    }

    // Make showNotification globally available
    window.showNotification = showNotification;
})();

