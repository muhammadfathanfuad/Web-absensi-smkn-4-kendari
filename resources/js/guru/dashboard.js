// Guru Dashboard JavaScript
// This file handles all dashboard functionality including presence status, leave requests, and charts

import ApexCharts from "apexcharts";

(function () {
    "use strict";

    // Get data from window variables (set in Blade template)
    const jamMengajarData = window.jamMengajarData || null;
    const statistikMingguanData = window.statistikMingguanData || null;
    const presenceStatusRoute = window.presenceStatusRoute || null;
    const showLeaveRequestRoute = window.showLeaveRequestRoute || null;
    const approveLeaveRequestRoute = window.approveLeaveRequestRoute || null;
    const rejectLeaveRequestRoute = window.rejectLeaveRequestRoute || null;
    const currentTeacherId = window.currentTeacherId || null;

    let currentRequestId = null;
    let currentAction = null;

    // Load presence status on page load
    document.addEventListener("DOMContentLoaded", function () {
        if (presenceStatusRoute) {
            loadPresenceStatus();
            // Refresh status every 30 seconds
            setInterval(loadPresenceStatus, 30000);
        }

        // Setup confirm action button
        const confirmActionBtn = document.getElementById("confirmAction");
        if (confirmActionBtn) {
            confirmActionBtn.addEventListener("click", handleConfirmAction);
        }

        // Smooth scroll to list siswa izin hari ini if hash is present
        if (window.location.hash === "#list-siswa-izin-hari-ini") {
            setTimeout(() => {
                const targetElement = document.getElementById(
                    "list-siswa-izin-hari-ini"
                );
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: "smooth",
                        block: "start",
                    });
                    // Add highlight effect
                    targetElement.style.transition = "box-shadow 0.3s ease";
                    targetElement.style.boxShadow =
                        "0 0 20px rgba(13, 110, 253, 0.5)";
                    setTimeout(() => {
                        targetElement.style.boxShadow = "";
                    }, 2000);
                }
            }, 100);
        }
    });

    function loadPresenceStatus() {
        if (!presenceStatusRoute) {
            console.error("Presence status route not defined");
            return;
        }

        fetch(presenceStatusRoute)
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    const statusConfig = {
                        H: {
                            text: "Hadir",
                            icon: "bx-check-circle",
                            badge: '<span class="badge bg-success">Hadir</span>',
                            boxClass: "border-success bg-success bg-opacity-10",
                        },
                        A: {
                            text: "Alfa",
                            icon: "bx-x-circle",
                            badge: '<span class="badge bg-danger">Alfa</span>',
                            boxClass: "border-danger bg-danger bg-opacity-10",
                        },
                        I: {
                            text: "Izin",
                            icon: "bx-info-circle",
                            badge: '<span class="badge bg-info">Izin</span>',
                            boxClass: "border-info bg-info bg-opacity-10",
                        },
                        S: {
                            text: "Sakit",
                            icon: "bx-error-circle",
                            badge: '<span class="badge bg-warning">Sakit</span>',
                            boxClass: "border-warning bg-warning bg-opacity-10",
                        },
                    };

                    // Update desktop
                    updatePresenceStatus(
                        "presenceStatusBox",
                        "presenceIcon",
                        "presenceStatusBadge",
                        "presenceStatusText",
                        data,
                        statusConfig
                    );

                    // Update mobile
                    updatePresenceStatus(
                        "presenceStatusBoxMobile",
                        "presenceIconMobile",
                        "presenceStatusBadgeMobile",
                        "presenceStatusTextMobile",
                        data,
                        statusConfig
                    );
                }
            })
            .catch((error) => {
                console.error("Error loading presence status:", error);
                const statusText =
                    document.getElementById("presenceStatusText");
                const statusTextMobile = document.getElementById(
                    "presenceStatusTextMobile"
                );
                if (statusText) {
                    statusText.textContent = "Gagal memuat";
                }
                if (statusTextMobile) {
                    statusTextMobile.textContent = "Gagal memuat";
                }
            });
    }

    function updatePresenceStatus(
        boxId,
        iconId,
        badgeId,
        textId,
        data,
        statusConfig
    ) {
        const statusBox = document.getElementById(boxId);
        const statusIcon = document.getElementById(iconId);
        const statusBadge = document.getElementById(badgeId);
        const statusText = document.getElementById(textId);

        if (!statusBox || !statusIcon || !statusBadge || !statusText) {
            return;
        }

        if (data.has_presence && data.status && statusConfig[data.status]) {
            const config = statusConfig[data.status];
            statusBox.className =
                "presence-status-box text-center p-2 rounded border " +
                config.boxClass;
            statusIcon.className = "bx " + config.icon + " fs-20 mb-1";
            statusIcon.classList.remove("text-muted");

            // Add appropriate color class
            if (data.status === "H") statusIcon.classList.add("text-success");
            else if (data.status === "A")
                statusIcon.classList.add("text-danger");
            else if (data.status === "I") statusIcon.classList.add("text-info");
            else if (data.status === "S")
                statusIcon.classList.add("text-warning");

            statusBadge.innerHTML = config.badge;
            const timeText = data.check_in_time
                ? " | " +
                  new Date(data.check_in_time).toLocaleTimeString("id-ID", {
                      hour: "2-digit",
                      minute: "2-digit",
                  })
                : "";
            statusText.textContent = config.text + timeText;
        } else {
            statusBox.className =
                "presence-status-box text-center p-2 rounded border";
            statusIcon.className = "bx bx-time fs-20 text-muted mb-1";
            statusBadge.innerHTML = "";
            statusText.textContent = "Absensi Belum Tercatat";
        }
    }

    function showDetailModal(requestId) {
        console.log("showDetailModal called with ID:", requestId);
        currentRequestId = requestId;

        if (!showLeaveRequestRoute) {
            console.error("Show leave request route not defined");
            return;
        }

        // Show loading
        const modalBody = document.getElementById("modalBody");
        const modalFooter = document.getElementById("modalFooter");

        if (modalBody) {
            modalBody.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat detail permohonan...</p>
                </div>
            `;
        }

        if (modalFooter) {
            modalFooter.innerHTML = "";
        }

        // Show modal
        const modalElement = document.getElementById("detailModal");
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }

        // Fetch data
        const url = showLeaveRequestRoute.replace(":id", requestId);
        console.log("Fetching data from:", url);
        fetch(url)
            .then((response) => {
                console.log("Response status:", response.status);
                return response.json();
            })
            .then((data) => {
                console.log("Response data:", data);
                if (data.success) {
                    displayRequestDetail(data.data);
                } else {
                    console.error("API Error:", data.error);
                    if (modalBody) {
                        modalBody.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="bx bx-error"></i> ${
                                    data.error ||
                                    "Terjadi kesalahan saat memuat data."
                                }
                            </div>
                        `;
                    }
                }
            })
            .catch((error) => {
                console.error("Fetch Error:", error);
                if (modalBody) {
                    modalBody.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bx bx-error"></i> Terjadi kesalahan saat memuat data.
                        </div>
                    `;
                }
            });
    }

    function displayRequestDetail(request) {
        const statusBadge = getStatusBadge(request.status);
        const leaveTypeDisplay =
            request.custom_leave_type || request.leave_type;
        const modalBody = document.getElementById("modalBody");
        const modalFooter = document.getElementById("modalFooter");

        if (!modalBody || !modalFooter) {
            return;
        }

        modalBody.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted">Informasi Siswa</h6>
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Nama:</strong></td>
                            <td>${request.student.name}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>${request.student.email}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Informasi Izin</h6>
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Jenis Izin:</strong></td>
                            <td>${leaveTypeDisplay}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>${statusBadge}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Mulai:</strong></td>
                            <td>${formatDate(request.start_date)}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Selesai:</strong></td>
                            <td>${formatDate(request.end_date)}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="text-muted">Alasan Izin</h6>
                    <div class="border rounded p-3 bg-light">
                        ${request.reason}
                    </div>
                </div>
            </div>
            
            ${
                request.supporting_document && request.document_url
                    ? `
            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="text-muted">Dokumen Pendukung</h6>
                    <button type="button" class="btn btn-outline-primary btn-sm view-document-btn" data-document-url="${String(
                        request.document_url || ""
                    ).replace(/"/g, "&quot;")}">
                        <i class="bx bx-file"></i> Lihat Dokumen
                    </button>
                </div>
            </div>
            `
                    : ""
            }
        `;

        // Add event listeners for view document buttons
        setTimeout(() => {
            document.querySelectorAll(".view-document-btn").forEach((btn) => {
                btn.addEventListener("click", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const documentUrl = this.getAttribute("data-document-url");
                    if (documentUrl && window.viewDocument) {
                        window.viewDocument(documentUrl, e);
                    } else {
                        console.error(
                            "Document URL not found or viewDocument function not available",
                            {
                                url: documentUrl,
                                function: window.viewDocument,
                            }
                        );
                    }
                });
            });
        }, 100);

        // Show action buttons based on current teacher's individual status
        const teacherStatus = request.teacher_status || {};
        const approvedBy = teacherStatus.approved_by || [];
        const rejectedBy = teacherStatus.rejected_by || [];

        let actionButtons = "";

        if (currentTeacherId && approvedBy.includes(currentTeacherId)) {
            // This teacher has already approved
            actionButtons = `
                <button type="button" class="btn btn-success" disabled>
                    <i class="bx bx-check"></i> Sudah Disetujui
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            `;
        } else if (currentTeacherId && rejectedBy.includes(currentTeacherId)) {
            // This teacher has already rejected
            actionButtons = `
                <button type="button" class="btn btn-danger" disabled>
                    <i class="bx bx-x"></i> Sudah Ditolak
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            `;
        } else {
            // This teacher hasn't taken action yet - can approve or reject
            actionButtons = `
                <button type="button" class="btn btn-success" onclick="showConfirmModal('approve', ${request.id})">
                    <i class="bx bx-check"></i> Terima
                </button>
                <button type="button" class="btn btn-danger" onclick="showConfirmModal('reject', ${request.id})">
                    <i class="bx bx-x"></i> Tolak
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            `;
        }

        modalFooter.innerHTML = actionButtons;
    }

    function showConfirmModal(action, requestId) {
        currentAction = action;
        currentRequestId = requestId;

        const actionText = action === "approve" ? "menyetujui" : "menolak";
        const confirmMessage = document.getElementById("confirmMessage");
        const adminNotes = document.getElementById("adminNotes");

        if (confirmMessage) {
            confirmMessage.textContent = `Apakah Anda yakin ingin ${actionText} permohonan izin ini?`;
        }
        if (adminNotes) {
            adminNotes.value = "";
        }

        // Close detail modal first
        const detailModalElement = document.getElementById("detailModal");
        if (detailModalElement) {
            const detailModal = bootstrap.Modal.getInstance(detailModalElement);
            if (detailModal) {
                detailModal.hide();
            }
        }

        // Show confirm modal after detail modal is closed
        setTimeout(() => {
            const confirmModalElement = document.getElementById("confirmModal");
            if (confirmModalElement) {
                const confirmModal = new bootstrap.Modal(confirmModalElement);
                confirmModal.show();
            }
        }, 300); // Wait for detail modal to close
    }

    function handleConfirmAction() {
        if (!currentAction || !currentRequestId) return;

        const adminNotes = document.getElementById("adminNotes");
        const notes = adminNotes ? adminNotes.value : "";

        let url = "";
        if (currentAction === "approve" && approveLeaveRequestRoute) {
            url = approveLeaveRequestRoute.replace(":id", currentRequestId);
        } else if (currentAction === "reject" && rejectLeaveRequestRoute) {
            url = rejectLeaveRequestRoute.replace(":id", currentRequestId);
        } else {
            console.error("Route not defined for action:", currentAction);
            return;
        }

        // Show loading
        const confirmActionBtn = document.getElementById("confirmAction");
        if (confirmActionBtn) {
            confirmActionBtn.disabled = true;
            confirmActionBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        }

        fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({ notes: notes }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Close confirm modal
                    const confirmModalElement =
                        document.getElementById("confirmModal");
                    if (confirmModalElement) {
                        const confirmModal =
                            bootstrap.Modal.getInstance(confirmModalElement);
                        if (confirmModal) {
                            confirmModal.hide();
                        }
                    }

                    // Show success message
                    showAlert("success", data.message);

                    // Reload page after 1 second
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showAlert(
                        "error",
                        data.error ||
                            "Terjadi kesalahan saat memproses permohonan."
                    );
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showAlert(
                    "error",
                    "Terjadi kesalahan saat memproses permohonan."
                );
            })
            .finally(() => {
                // Reset button
                if (confirmActionBtn) {
                    confirmActionBtn.disabled = false;
                    confirmActionBtn.innerHTML = "Konfirmasi";
                }
            });
    }

    function getStatusBadge(status) {
        const badges = {
            pending: '<span class="badge bg-warning">Menunggu</span>',
            approved: '<span class="badge bg-success">Disetujui</span>',
            rejected: '<span class="badge bg-danger">Ditolak</span>',
            partially_approved:
                '<span class="badge bg-info">Sebagian Disetujui</span>',
        };
        return badges[status] || '<span class="badge bg-secondary">-</span>';
    }

    function getStatusBadgeClass(status) {
        const classes = {
            pending: "warning",
            approved: "success",
            rejected: "danger",
            partially_approved: "info",
        };
        return classes[status] || "secondary";
    }

    function getStatusText(status) {
        const texts = {
            pending: "Menunggu",
            approved: "Disetujui",
            rejected: "Ditolak",
            partially_approved: "Sebagian Disetujui",
        };
        return texts[status] || "-";
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "2-digit",
            year: "numeric",
        });
    }

    function formatDateTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString("id-ID", {
            day: "2-digit",
            month: "2-digit",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
    }

    function showAlert(type, message) {
        const alertClass =
            type === "success" ? "alert-success" : "alert-danger";
        const icon = type === "success" ? "bx-check-circle" : "bx-error";

        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="bx ${icon} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        // Insert alert at the top of the page
        const container = document.querySelector(".container-fluid");
        if (container) {
            container.insertAdjacentHTML("afterbegin", alertHtml);

            // Auto remove after 5 seconds
            setTimeout(() => {
                const alert = container.querySelector(".alert");
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }
    }

    // Function to view document in new tab (global scope)
    window.viewDocument = function (documentUrl, event) {
        // Prevent default behavior and stop event propagation if event is provided
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        // Ensure URL is a string
        let decodedUrl = documentUrl;
        if (typeof documentUrl === "string") {
            // Don't decode if it's already a valid URL
            if (documentUrl.indexOf("%") === -1) {
                decodedUrl = documentUrl;
            } else {
                try {
                    decodedUrl = decodeURIComponent(documentUrl);
                } catch (e) {
                    // If decode fails, use original URL
                    decodedUrl = documentUrl;
                }
            }
        }

        console.log("Opening document in new tab:", decodedUrl);

        // Open document in new tab
        // The route will return the file with Content-Disposition: inline header
        // which will display the PDF in browser instead of downloading
        window.open(decodedUrl, "_blank", "noopener,noreferrer");
    };

    // Make functions globally available
    window.showDetailModal = showDetailModal;
    window.showConfirmModal = showConfirmModal;

    // ============================================
    // Chart Initialization
    // ============================================

    // Fungsi untuk mendapatkan warna tema dari atribut HTML
    function getChartColorsArray(chartId) {
        const chartElement = document.getElementById(chartId);
        if (chartElement) {
            const colors = chartElement.dataset.colors;
            if (colors) {
                return JSON.parse(colors);
            }
        }
        // Warna default jika tidak ada yang diset
        return ["#3498db", "#2ecc71", "#e74c3c", "#f1c40f", "#9b59b6"];
    }

    // 1. Chart Jam Mengajar Hari Ini (Radial Bar) - DINAMIS
    const jamMengajarChartEl = document.getElementById("jamMengajarChart");
    if (jamMengajarChartEl && jamMengajarData) {
        const colors = getChartColorsArray("jamMengajarChart");
        const options = {
            // Menggunakan persentase dari controller
            series: [jamMengajarData.persentase],
            chart: {
                height: 250,
                type: "radialBar",
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        size: "70%",
                    },
                    dataLabels: {
                        name: {
                            show: false,
                        },
                        value: {
                            fontSize: "24px",
                            fontWeight: "bold",
                            offsetY: 10,
                            formatter: function (val) {
                                return val + "%";
                            },
                        },
                    },
                },
            },
            colors: colors,
            stroke: {
                lineCap: "round",
            },
            labels: ["Selesai"],
        };

        const chart = new ApexCharts(jamMengajarChartEl, options);
        chart.render();
    }

    // 2. Chart Statistik Mingguan (Bar Chart) - DINAMIS
    const statistikMingguanChartEl = document.getElementById(
        "statistikMingguanChart"
    );
    if (statistikMingguanChartEl && statistikMingguanData) {
        const colors = getChartColorsArray("statistikMingguanChart");
        const options = {
            series: [
                {
                    name: "Jam Mengajar",
                    data: statistikMingguanData.map((item) => item.jam),
                },
            ],
            chart: {
                height: 250,
                type: "bar",
                toolbar: {
                    show: false,
                },
            },
            colors: colors,
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: false,
                },
            },
            dataLabels: {
                enabled: false,
            },
            xaxis: {
                categories: statistikMingguanData.map((item) => item.tanggal),
            },
            yaxis: {
                title: {
                    text: "Jam",
                },
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + " jam";
                    },
                },
            },
        };

        const chart = new ApexCharts(statistikMingguanChartEl, options);
        chart.render();
    }
})();
