// Admin Laporan JavaScript
// This file handles all report functionality including export, filters, and detail modals
(function () {
    "use strict";

    // Export function with simplified approach
    function exportReport(format = "pdf") {
        try {
            // Get current report type from checked radio button
            var checkedRadio = document.querySelector(
                'input[name="report_type"]:checked'
            );
            if (!checkedRadio) {
                showAlert("Pilih jenis laporan terlebih dahulu", "danger");
                return;
            }

            // Get current date filters
            var dateFrom = document.getElementById("date_from").value;
            var dateTo = document.getElementById("date_to").value;

            // Get class filter value if exists
            var classId = document.getElementById("class_id")
                ? document.getElementById("class_id").value
                : "";

            // Build clean export URL with all necessary parameters
            var exportUrl =
                (window.laporanExportUrl ||
                    "/admin/laporan/export") +
                "?export=1&format=" +
                format +
                "&report_type=" +
                checkedRadio.value;
            if (dateFrom) exportUrl += "&date_from=" + dateFrom;
            if (dateTo) exportUrl += "&date_to=" + dateTo;
            if (classId && checkedRadio.value === "student")
                exportUrl += "&class_id=" + classId;

            // Show loading indicator
            showExportLoading(format);

            // Create a hidden iframe to handle the download
            var iframe = document.createElement("iframe");
            iframe.style.display = "none";
            iframe.src = exportUrl;
            document.body.appendChild(iframe);

            // Clean up after download starts
            setTimeout(function () {
                document.body.removeChild(iframe);
                hideExportLoading();
                showAlert(
                    "File export dimulai. Silakan tunggu beberapa saat.",
                    "info"
                );
            }, 1000);

            // Fallback if iframe doesn't work
            setTimeout(function () {
                console.log(
                    "Iframe method may have failed, trying fallback..."
                );
                tryFallbackExport(format);
            }, 3000);
        } catch (error) {
            console.error("Export error:", error);
            hideExportLoading();
            showAlert(
                "Terjadi kesalahan saat export: " + error.message,
                "danger"
            );
        }
    }

    // Make functions globally available
    window.exportReport = exportReport;

    // Fallback export method using direct link
    function tryFallbackExport(format = "pdf") {
        try {
            // Get current report type from checked radio button
            var checkedRadio = document.querySelector(
                'input[name="report_type"]:checked'
            );
            if (!checkedRadio) {
                showAlert("Pilih jenis laporan terlebih dahulu", "danger");
                return;
            }

            // Get current date filters
            var dateFrom = document.getElementById("date_from").value;
            var dateTo = document.getElementById("date_to").value;

            // Get class filter value if exists
            var classId = document.getElementById("class_id")
                ? document.getElementById("class_id").value
                : "";

            // Build clean export URL with all necessary parameters
            var exportUrl =
                (window.laporanExportUrl ||
                    "/admin/laporan/export") +
                "?export=1&format=" +
                format +
                "&report_type=" +
                checkedRadio.value;
            if (dateFrom) exportUrl += "&date_from=" + dateFrom;
            if (dateTo) exportUrl += "&date_to=" + dateTo;
            if (classId && checkedRadio.value === "student")
                exportUrl += "&class_id=" + classId;

            // Show loading again
            showExportLoading(format);

            // Use window.location as ultimate fallback
            window.location.href = exportUrl;

            // Hide loading after a short delay
            setTimeout(function () {
                hideExportLoading();
                showAlert("File export dimulai", "info");
            }, 2000);
        } catch (error) {
            console.error("Fallback export error:", error);
            hideExportLoading();
            showAlert(
                "Gagal mengexport data. Silakan coba lagi atau hubungi administrator.",
                "danger"
            );
        }
    }

    // Show loading indicator
    function showExportLoading(format) {
        var formatText = format === "pdf" ? "PDF" : "File";
        var loadingHtml = `
            <div id="exportLoading" class="alert alert-info alert-dismissible fade show" role="alert" style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px;">
                <div class="d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div>
                        <strong>Sedang memproses export ${formatText}...</strong>
                        <br>
                        <small>File akan segera diunduh</small>
                    </div>
                </div>
            </div>
        `;

        // Remove existing loading if any
        var existingLoading = document.getElementById("exportLoading");
        if (existingLoading) {
            existingLoading.remove();
        }

        // Add new loading indicator
        document.body.insertAdjacentHTML("beforeend", loadingHtml);
    }

    // Hide loading indicator
    function hideExportLoading() {
        var loadingElement = document.getElementById("exportLoading");
        if (loadingElement) {
            loadingElement.classList.remove("show");
            setTimeout(function () {
                loadingElement.remove();
            }, 150);
        }
    }

    // Show alert message
    function showAlert(message, type = "info") {
        var alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert" style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        document.body.insertAdjacentHTML("beforeend", alertHtml);

        // Auto-dismiss after 5 seconds
        setTimeout(function () {
            var alerts = document.querySelectorAll(".alert");
            alerts.forEach(function (alert) {
                if (alert.classList.contains("show")) {
                    alert.classList.remove("show");
                    setTimeout(function () {
                        alert.remove();
                    }, 150);
                }
            });
        }, 5000);
    }

    // Show/hide class filter based on report type
    function toggleClassFilter() {
        var reportType = document.querySelector(
            'input[name="report_type"]:checked'
        );
        var classFilterSection = document.getElementById("classFilterSection");

        if (reportType && reportType.value === "student") {
            if (classFilterSection) {
                classFilterSection.style.display = "block";
            }
        } else {
            if (classFilterSection) {
                classFilterSection.style.display = "none";
            }
        }
    }

    // Auto-submit form when report type changes (radio buttons)
    document
        .querySelectorAll('input[name="report_type"]')
        .forEach(function (radio) {
            radio.addEventListener("change", function () {
                if (this.checked) {
                    // Toggle class filter visibility
                    toggleClassFilter();

                    // Get current date filters
                    var dateFrom = document.getElementById("date_from").value;
                    var dateTo = document.getElementById("date_to").value;

                    // Get class filter value if exists
                    var classId = document.getElementById("class_id")
                        ? document.getElementById("class_id").value
                        : "";

                    // Build URL with report type and date filters
                    var targetUrl =
                        (window.laporanUrl || "/admin/laporan") +
                        "?report_type=" +
                        this.value;
                    if (dateFrom) targetUrl += "&date_from=" + dateFrom;
                    if (dateTo) targetUrl += "&date_to=" + dateTo;
                    if (classId && this.value === "student")
                        targetUrl += "&class_id=" + classId;

                    // Navigate to the correct URL
                    window.location.href = targetUrl;
                }
            });
        });

    // Initialize class filter visibility on page load
    document.addEventListener("DOMContentLoaded", function () {
        toggleClassFilter();
    });

    // Date range preset functions
    function setDateRange(range) {
        var today = new Date();
        var dateFrom, dateTo;

        switch (range) {
            case "today":
                dateFrom = dateTo = today.toISOString().split("T")[0];
                break;
            case "week":
                var startOfWeek = new Date(today);
                startOfWeek.setDate(today.getDate() - today.getDay() + 1); // Monday
                dateFrom = startOfWeek.toISOString().split("T")[0];
                dateTo = today.toISOString().split("T")[0];
                break;
            case "month":
                var startOfMonth = new Date(
                    today.getFullYear(),
                    today.getMonth(),
                    1
                );
                dateFrom = startOfMonth.toISOString().split("T")[0];
                dateTo = today.toISOString().split("T")[0];
                break;
        }

        document.getElementById("date_from").value = dateFrom;
        document.getElementById("date_to").value = dateTo;
    }

    // Make setDateRange globally available
    window.setDateRange = setDateRange;

    // Reset date filter
    function resetDateFilter() {
        var today = new Date();

        document.getElementById("date_from").value = today
            .toISOString()
            .split("T")[0];
        document.getElementById("date_to").value = today
            .toISOString()
            .split("T")[0];
    }

    // Make resetDateFilter globally available
    window.resetDateFilter = resetDateFilter;

    // Validate date range
    function validateDateRange() {
        var dateFrom = document.getElementById("date_from").value;
        var dateTo = document.getElementById("date_to").value;

        if (dateFrom && dateTo && dateFrom > dateTo) {
            alert(
                'Tanggal "Dari" tidak boleh lebih besar dari tanggal "Sampai"'
            );
            return false;
        }
        return true;
    }

    // Add validation to form submission
    var filterForm = document.getElementById("filterForm");
    if (filterForm) {
        filterForm.addEventListener("submit", function (e) {
            if (!validateDateRange()) {
                e.preventDefault();
                return false;
            }
        });
    }

    // Ensure form action is always correct on page load
    document.addEventListener("DOMContentLoaded", function () {
        var form = document.getElementById("filterForm");
        if (!form) {
            console.error("Filter form not found on page load");
            return;
        }

        // Teacher search functionality with client-side pagination
        var teacherSearchInput = document.getElementById("teacherSearchInput");
        if (teacherSearchInput && window.allTeachersData) {
            // Function to render teachers table
            function renderTeachers(data, page, perPage) {
                var tbody = document.getElementById("teacherTableBody");
                tbody.innerHTML = "";

                var start = (page - 1) * perPage;
                var end = start + perPage;
                var pageData = data.slice(start, end);

                if (pageData.length === 0 && data.length > 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="6" class="text-center py-4"><div class="text-muted">Halaman tidak ditemukan.</div></td></tr>';
                } else if (pageData.length === 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="6" class="text-center py-4"><div class="text-muted d-flex flex-column align-items-center"><iconify-icon icon="solar:file-search-outline" class="fs-48 mb-2"></iconify-icon>Tidak ada hasil ditemukan.</div></td></tr>';
                } else {
                    pageData.forEach(function (teacher) {
                        var row = document.createElement("tr");
                        row.setAttribute("data-teacher-id", teacher.id);

                        // Build status badge (single status based on priority)
                        var statusHtml = "";
                        if (
                            teacher.status_kehadiran &&
                            teacher.status_kehadiran !== "-"
                        ) {
                            var badgeClass =
                                teacher.status_kehadiran_badge || "secondary";
                            statusHtml = `<span class="badge bg-${badgeClass}">${teacher.status_kehadiran}</span>`;
                        } else {
                            statusHtml =
                                '<span class="badge bg-secondary">-</span>';
                        }

                        row.innerHTML = `
                                <td>${teacher.nama}</td>
                                <td>${teacher.nip}</td>
                                <td>${statusHtml}</td>
                                <td><strong>${teacher.total_pertemuan.toLocaleString()}</strong></td>
                                <td class="text-primary"><strong>${teacher.total_record.toLocaleString()}</strong></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info btn-detail-teacher" 
                                            data-teacher-id="${teacher.id}"
                                            data-teacher-name="${teacher.nama}"
                                            data-date-from="${window.dateFrom || ""}"
                                            data-date-to="${window.dateTo || ""}">
                                        <i class="bx bx-detail me-1"></i>Detail
                                    </button>
                                </td>
                            `;
                        tbody.appendChild(row);
                    });
                }
            }

            // Function to render pagination
            function renderPagination(data, page, perPage) {
                var totalPages = Math.ceil(data.length / perPage);
                var paginationContainer = document.getElementById(
                    "teacherPaginationContainer"
                );
                var paginationUl = document.getElementById("teacherPagination");
                var paginationInfo = document.getElementById("paginationInfo");

                if (totalPages <= 1) {
                    paginationContainer.style.display = "none";
                    return;
                }

                paginationContainer.style.display = "block";
                paginationUl.innerHTML = "";

                // Previous button
                var prevLi = document.createElement("li");
                prevLi.className =
                    page > 1 ? "page-item" : "page-item disabled";
                prevLi.innerHTML =
                    page > 1
                        ? `<a class="page-link" href="#" onclick="goToPage(${
                              page - 1
                          }); return false;"><i class="bx bx-chevron-left"></i> Sebelumnya</a>`
                        : `<span class="page-link"><i class="bx bx-chevron-left"></i> Sebelumnya</span>`;
                paginationUl.appendChild(prevLi);

                // Page numbers
                var startPage = Math.max(1, page - 2);
                var endPage = Math.min(totalPages, page + 2);

                if (startPage > 1) {
                    var firstLi = document.createElement("li");
                    firstLi.className = "page-item";
                    firstLi.innerHTML = `<a class="page-link" href="#" onclick="goToPage(1); return false;">1</a>`;
                    paginationUl.appendChild(firstLi);
                    if (startPage > 2) {
                        var ellipsisLi = document.createElement("li");
                        ellipsisLi.className = "page-item disabled";
                        ellipsisLi.innerHTML =
                            '<span class="page-link">...</span>';
                        paginationUl.appendChild(ellipsisLi);
                    }
                }

                for (var i = startPage; i <= endPage; i++) {
                    var li = document.createElement("li");
                    li.className =
                        "page-item" + (i === page ? " active" : "");
                    li.innerHTML = `<a class="page-link" href="#" onclick="goToPage(${i}); return false;">${i}</a>`;
                    paginationUl.appendChild(li);
                }

                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        var ellipsisLi = document.createElement("li");
                        ellipsisLi.className = "page-item disabled";
                        ellipsisLi.innerHTML =
                            '<span class="page-link">...</span>';
                        paginationUl.appendChild(ellipsisLi);
                    }
                    var lastLi = document.createElement("li");
                    lastLi.className = "page-item";
                    lastLi.innerHTML = `<a class="page-link" href="#" onclick="goToPage(${totalPages}); return false;">${totalPages}</a>`;
                    paginationUl.appendChild(lastLi);
                }

                // Next button
                var nextLi = document.createElement("li");
                nextLi.className =
                    page < totalPages ? "page-item" : "page-item disabled";
                nextLi.innerHTML =
                    page < totalPages
                        ? `<a class="page-link" href="#" onclick="goToPage(${
                              page + 1
                          }); return false;">Selanjutnya <i class="bx bx-chevron-right"></i></a>`
                        : `<span class="page-link">Selanjutnya <i class="bx bx-chevron-right"></i></span>`;
                paginationUl.appendChild(nextLi);

                // Info text
                var start = (page - 1) * perPage + 1;
                var end = Math.min(page * perPage, data.length);
                paginationInfo.textContent = `Menampilkan ${start} - ${end} dari ${data.length} guru`;
            }

            // Global function to go to page
            window.goToPage = function (page) {
                renderTeachers(
                    window.filteredTeachers,
                    page,
                    window.perPage
                );
                renderPagination(
                    window.filteredTeachers,
                    page,
                    window.perPage
                );
            };

            // Search functionality
            teacherSearchInput.addEventListener("input", function () {
                var filter = this.value.toLowerCase();

                if (filter === "") {
                    window.filteredTeachers = window.allTeachersData;
                } else {
                    window.filteredTeachers = window.allTeachersData.filter(
                        function (teacher) {
                            return (
                                teacher.nama.toLowerCase().indexOf(filter) >
                                    -1 ||
                                teacher.nip.toLowerCase().indexOf(filter) > -1
                            );
                        }
                    );
                }

                // Reset to page 1 after search
                window.currentPage = 1;
                renderTeachers(
                    window.filteredTeachers,
                    1,
                    window.perPage
                );
                renderPagination(
                    window.filteredTeachers,
                    1,
                    window.perPage
                );
            });

            // Initial render
            renderTeachers(
                window.allTeachersData,
                window.currentPage,
                window.perPage
            );
            renderPagination(
                window.allTeachersData,
                window.currentPage,
                window.perPage
            );
        }

        // Handle detail button click (delegated event listener)
        document.addEventListener("click", function (e) {
            if (e.target.closest(".btn-detail-teacher")) {
                var button = e.target.closest(".btn-detail-teacher");
                var teacherId = button.getAttribute("data-teacher-id");
                var teacherName = button.getAttribute("data-teacher-name");
                var dateFrom = button.getAttribute("data-date-from");
                var dateTo = button.getAttribute("data-date-to");

                showTeacherDetail(teacherId, teacherName, dateFrom, dateTo);
            }
        });
    });

    // Function to show teacher detail
    function showTeacherDetail(teacherId, teacherName, dateFrom, dateTo) {
        var modal = new bootstrap.Modal(
            document.getElementById("teacherDetailModal")
        );
        modal.show();

        // Show loading, hide content
        document.getElementById("teacherDetailLoading").style.display =
            "block";
        document.getElementById("teacherDetailContent").style.display = "none";

        // Set teacher name in modal title
        document.getElementById("teacherDetailModalLabel").innerHTML =
            '<i class="bx bx-detail me-2"></i>Detail Laporan: ' + teacherName;

        // Fetch detail data
        var teacherDetailUrl =
            (window.laporanTeacherDetailUrl ||
                "/admin/laporan/teacher-detail") +
            "?teacher_id=" +
            teacherId +
            "&date_from=" +
            dateFrom +
            "&date_to=" +
            dateTo;

        fetch(teacherDetailUrl, {
            method: "GET",
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
                    // Set basic info
                    document.getElementById("detailTeacherName").textContent =
                        data.teacher.name || "N/A";
                    document.getElementById("detailTeacherNip").textContent =
                        data.teacher.nip || "N/A";
                    document.getElementById("detailPeriod").textContent =
                        new Date(dateFrom).toLocaleDateString("id-ID") +
                        " - " +
                        new Date(dateTo).toLocaleDateString("id-ID");
                    document.getElementById(
                        "detailTotalPertemuan"
                    ).textContent =
                        data.summary.total_pertemuan.toLocaleString();
                    document.getElementById("detailTotalRecord").textContent =
                        data.summary.total_record.toLocaleString();

                    // Render classes attended
                    var attendedTbody = document.getElementById(
                        "detailClassesAttended"
                    );
                    attendedTbody.innerHTML = "";
                    if (
                        data.classes_attended &&
                        data.classes_attended.length > 0
                    ) {
                        data.classes_attended.forEach(function (item, index) {
                            var row = document.createElement("tr");
                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>${item.subject_name || "-"}</td>
                                <td>${item.class_name || "-"}</td>
                                <td><span class="badge bg-info">${item.class_grade || "-"}</span></td>
                                <td>${item.date || "-"}</td>
                                <td>${item.time_range || item.start_time + " - " + item.end_time || "-"}</td>
                                <td><span class="badge bg-success">${item.total_record || 0}</span></td>
                            `;
                            attendedTbody.appendChild(row);
                        });
                        document.getElementById(
                            "detailTotalAttended"
                        ).textContent =
                            data.classes_attended.length.toLocaleString();
                    } else {
                        attendedTbody.innerHTML =
                            '<tr><td colspan="7" class="text-center text-muted">Tidak ada data</td></tr>';
                        document.getElementById(
                            "detailTotalAttended"
                        ).textContent = "0";
                    }

                    // Render classes not attended
                    var notAttendedTbody = document.getElementById(
                        "detailClassesNotAttended"
                    );
                    notAttendedTbody.innerHTML = "";
                    if (
                        data.classes_not_attended &&
                        data.classes_not_attended.length > 0
                    ) {
                        data.classes_not_attended.forEach(function (
                            item,
                            index
                        ) {
                            var row = document.createElement("tr");
                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>${item.subject_name || "-"}</td>
                                <td>${item.class_name || "-"}</td>
                                <td><span class="badge bg-info">${item.class_grade || "-"}</span></td>
                                <td>${item.date || "-"}</td>
                                <td>${item.time_range || item.start_time + " - " + item.end_time || "-"}</td>
                            `;
                            notAttendedTbody.appendChild(row);
                        });
                        document.getElementById(
                            "detailTotalNotAttended"
                        ).textContent =
                            data.classes_not_attended.length.toLocaleString();
                    } else {
                        notAttendedTbody.innerHTML =
                            '<tr><td colspan="6" class="text-center text-muted">Tidak ada data</td></tr>';
                        document.getElementById(
                            "detailTotalNotAttended"
                        ).textContent = "0";
                    }

                    // Hide loading, show content
                    document.getElementById("teacherDetailLoading").style.display =
                        "none";
                    document.getElementById("teacherDetailContent").style.display =
                        "block";
                } else {
                    alert(
                        "Gagal memuat data detail: " +
                            (data.message || "Terjadi kesalahan")
                    );
                    modal.hide();
                }
            })
            .catch((error) => {
                console.error("Error fetching teacher detail:", error);
                alert("Terjadi kesalahan saat memuat data detail");
                modal.hide();
            });
    }

    // Make showTeacherDetail globally available
    window.showTeacherDetail = showTeacherDetail;

    // Function to show student detail
    function showStudentDetail(studentId, dateFrom, dateTo) {
        var modal = new bootstrap.Modal(
            document.getElementById("studentDetailModal")
        );
        modal.show();

        // Show loading
        document.getElementById("studentDetailContent").innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Memuat data detail...</p>
                </div>
            `;

        // Fetch detail data
        var studentDetailUrl =
            (window.laporanStudentDetailUrl ||
                "/admin/laporan/student-detail") +
            "?student_id=" +
            studentId +
            "&date_from=" +
            dateFrom +
            "&date_to=" +
            dateTo;

        fetch(studentDetailUrl, {
            method: "GET",
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
                    var html = `
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary mb-3">
                                            <i class="bx bx-info-circle me-2"></i>Informasi Siswa
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p class="mb-2"><strong>Nama:</strong> ${data.student.name || "N/A"}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-2"><strong>NIS:</strong> ${data.student.nis || "N/A"}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="mb-2"><strong>Kelas:</strong> ${data.student.class || "N/A"}</p>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <p class="mb-0"><strong>Periode:</strong> ${new Date(data.date_from).toLocaleDateString("id-ID")} - ${new Date(data.date_to).toLocaleDateString("id-ID")}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="bx bx-calendar me-2"></i>Detail Absensi per Hari
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Search and Filter Controls -->
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                                                    <input type="text" id="studentDetailSearch" class="form-control" placeholder="Cari mata pelajaran...">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <select id="studentDetailSubjectFilter" class="form-select">
                                                    <option value="">Semua Mata Pelajaran</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="date" id="studentDetailDateFilter" class="form-control" placeholder="Filter tanggal...">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-secondary w-100" onclick="resetStudentDetailFilters()">
                                                    <i class="bx bx-refresh"></i> Reset
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                            <table class="table table-sm table-hover">
                                                <thead class="table-light sticky-top">
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Total Record</th>
                                                        <th>Mata Pelajaran</th>
                                                        <th>Status Absensi</th>
                                                        <th>Waktu Masuk</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="studentDetailTableBody">
                    `;

                    // Data will be rendered by renderStudentDetailTable function

                    html += `
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <!-- Pagination -->
                                        <nav aria-label="Page navigation" class="mt-3">
                                            <ul class="pagination justify-content-center" id="studentDetailPagination">
                                                <!-- Pagination will be generated by JavaScript -->
                                            </ul>
                                        </nav>
                                        
                                        <div class="text-center mt-2">
                                            <small class="text-muted">
                                                Menampilkan <span id="studentDetailShowing">0</span> dari <span id="studentDetailTotal">0</span> data
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    document.getElementById("studentDetailContent").innerHTML =
                        html;

                    // Store original data for filtering
                    window.studentDetailAllData = data.daily_data;
                    window.studentDetailCurrentPage = 1;
                    window.studentDetailItemsPerPage = 10;

                    // Initialize filters and pagination
                    initializeStudentDetailFilters();
                    renderStudentDetailTable();
                } else {
                    document.getElementById("studentDetailContent").innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bx bx-error-circle me-2"></i>${data.message || "Terjadi kesalahan saat mengambil data"}
                        </div>
                    `;
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                document.getElementById("studentDetailContent").innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bx bx-error-circle me-2"></i>Terjadi kesalahan saat mengambil data detail
                    </div>
                `;
            });
    }

    // Make showStudentDetail globally available
    window.showStudentDetail = showStudentDetail;

    // Initialize filters for student detail
    function initializeStudentDetailFilters() {
        if (!window.studentDetailAllData) return;

        // Get unique subjects
        var subjects = new Set();
        window.studentDetailAllData.forEach(function (day) {
            day.subjects.forEach(function (subject) {
                subjects.add(subject.subject_name);
            });
        });

        // Populate subject filter
        var subjectFilter = document.getElementById("studentDetailSubjectFilter");
        if (subjectFilter) {
            var currentValue = subjectFilter.value;
            subjectFilter.innerHTML =
                '<option value="">Semua Mata Pelajaran</option>';
            Array.from(subjects)
                .sort()
                .forEach(function (subject) {
                    var option = document.createElement("option");
                    option.value = subject;
                    option.textContent = subject;
                    if (subject === currentValue) {
                        option.selected = true;
                    }
                    subjectFilter.appendChild(option);
                });
        }

        // Add event listeners
        var searchInput = document.getElementById("studentDetailSearch");
        if (searchInput) {
            searchInput.addEventListener("input", function () {
                window.studentDetailCurrentPage = 1;
                renderStudentDetailTable();
            });
        }

        if (subjectFilter) {
            subjectFilter.addEventListener("change", function () {
                window.studentDetailCurrentPage = 1;
                renderStudentDetailTable();
            });
        }

        var dateFilter = document.getElementById("studentDetailDateFilter");
        if (dateFilter) {
            dateFilter.addEventListener("change", function () {
                window.studentDetailCurrentPage = 1;
                renderStudentDetailTable();
            });
        }
    }

    // Render student detail table with filters and pagination
    function renderStudentDetailTable() {
        if (!window.studentDetailAllData) return;

        var searchTerm =
            document.getElementById("studentDetailSearch")?.value.toLowerCase() ||
            "";
        var subjectFilter =
            document.getElementById("studentDetailSubjectFilter")?.value || "";
        var dateFilter =
            document.getElementById("studentDetailDateFilter")?.value || "";

        // Flatten and filter data
        var allRows = [];
        window.studentDetailAllData.forEach(function (day) {
            day.subjects.forEach(function (subject, index) {
                // Apply filters
                var matchesSearch =
                    !searchTerm ||
                    subject.subject_name.toLowerCase().includes(searchTerm);
                var matchesSubject =
                    !subjectFilter || subject.subject_name === subjectFilter;
                var matchesDate = !dateFilter || day.date === dateFilter;

                if (matchesSearch && matchesSubject && matchesDate) {
                    allRows.push({
                        day: day,
                        subject: subject,
                    });
                }
            });
        });

        // Calculate pagination
        var totalItems = allRows.length;
        var totalPages = Math.ceil(
            totalItems / window.studentDetailItemsPerPage
        );
        var startIndex =
            (window.studentDetailCurrentPage - 1) *
            window.studentDetailItemsPerPage;
        var endIndex = Math.min(
            startIndex + window.studentDetailItemsPerPage,
            totalItems
        );
        var currentRows = allRows.slice(startIndex, endIndex);

        // Render table
        var tbody = document.getElementById("studentDetailTableBody");
        if (!tbody) return;

        tbody.innerHTML = "";

        if (currentRows.length === 0) {
            tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bx bx-info-circle me-2"></i>Tidak ada data yang sesuai dengan filter
                        </td>
                    </tr>
                `;
        } else {
            // Calculate rowspan for each day in current page
            var dayRowspanMap = {};
            currentRows.forEach(function (row) {
                if (!dayRowspanMap[row.day.date]) {
                    dayRowspanMap[row.day.date] = {
                        count: 0,
                        date_display: row.day.date_display,
                        total_record: row.day.total_record,
                    };
                }
                dayRowspanMap[row.day.date].count++;
            });

            var currentDay = null;

            currentRows.forEach(function (row) {
                var html = "<tr>";
                var dayRowspan = dayRowspanMap[row.day.date].count;

                // Handle rowspan for date and total record
                if (row.day.date !== currentDay) {
                    currentDay = row.day.date;

                    html += `<td rowspan="${dayRowspan}" class="align-middle">${dayRowspanMap[row.day.date].date_display}</td>`;
                    html += `<td rowspan="${dayRowspan}" class="align-middle text-center">${dayRowspanMap[row.day.date].total_record}</td>`;
                }

                html += `<td>${row.subject.subject_name}</td>`;
                html += `<td><span class="badge bg-${row.subject.status_badge}">${row.subject.status_text}</span></td>`;
                html += `<td>${row.subject.check_in_time || "-"}</td>`;
                html += "</tr>";

                tbody.innerHTML += html;
            });
        }

        // Update pagination info
        var showingSpan = document.getElementById("studentDetailShowing");
        var totalSpan = document.getElementById("studentDetailTotal");
        if (showingSpan) {
            showingSpan.textContent =
                currentRows.length > 0
                    ? startIndex + 1 + "-" + endIndex
                    : "0";
        }
        if (totalSpan) {
            totalSpan.textContent = totalItems;
        }

        // Render pagination
        renderStudentDetailPagination(totalPages);
    }

    // Render pagination controls
    function renderStudentDetailPagination(totalPages) {
        var pagination = document.getElementById("studentDetailPagination");
        if (!pagination) return;

        if (totalPages <= 1) {
            pagination.innerHTML = "";
            return;
        }

        var html = "";
        var currentPage = window.studentDetailCurrentPage;

        // Previous button
        html += `<li class="page-item ${currentPage === 1 ? "disabled" : ""}">
                <a class="page-link" href="#" onclick="changeStudentDetailPage(${
                    currentPage - 1
                }); return false;">Previous</a>
            </li>`;

        // Page numbers
        var startPage = Math.max(1, currentPage - 2);
        var endPage = Math.min(totalPages, currentPage + 2);

        if (startPage > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="changeStudentDetailPage(1); return false;">1</a></li>`;
            if (startPage > 2) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }

        for (var i = startPage; i <= endPage; i++) {
            html += `<li class="page-item ${i === currentPage ? "active" : ""}">
                    <a class="page-link" href="#" onclick="changeStudentDetailPage(${i}); return false;">${i}</a>
                </li>`;
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            html += `<li class="page-item"><a class="page-link" href="#" onclick="changeStudentDetailPage(${totalPages}); return false;">${totalPages}</a></li>`;
        }

        // Next button
        html += `<li class="page-item ${currentPage === totalPages ? "disabled" : ""}">
                <a class="page-link" href="#" onclick="changeStudentDetailPage(${
                    currentPage + 1
                }); return false;">Next</a>
            </li>`;

        pagination.innerHTML = html;
    }

    // Change page
    function changeStudentDetailPage(page) {
        if (!window.studentDetailAllData) return;

        var searchTerm =
            document.getElementById("studentDetailSearch")?.value.toLowerCase() ||
            "";
        var subjectFilter =
            document.getElementById("studentDetailSubjectFilter")?.value || "";
        var dateFilter =
            document.getElementById("studentDetailDateFilter")?.value || "";

        // Calculate total pages
        var allRows = [];
        window.studentDetailAllData.forEach(function (day) {
            day.subjects.forEach(function (subject) {
                var matchesSearch =
                    !searchTerm ||
                    subject.subject_name.toLowerCase().includes(searchTerm);
                var matchesSubject =
                    !subjectFilter || subject.subject_name === subjectFilter;
                var matchesDate = !dateFilter || day.date === dateFilter;

                if (matchesSearch && matchesSubject && matchesDate) {
                    allRows.push({ day: day, subject: subject });
                }
            });
        });

        var totalPages = Math.ceil(
            allRows.length / window.studentDetailItemsPerPage
        );

        if (page >= 1 && page <= totalPages) {
            window.studentDetailCurrentPage = page;
            renderStudentDetailTable();

            // Scroll to top of table
            var tableContainer = document.querySelector(
                "#studentDetailContent .table-responsive"
            );
            if (tableContainer) {
                tableContainer.scrollTop = 0;
            }
        }
    }

    // Make changeStudentDetailPage globally available
    window.changeStudentDetailPage = changeStudentDetailPage;

    // Reset filters
    function resetStudentDetailFilters() {
        var searchInput = document.getElementById("studentDetailSearch");
        var subjectFilter = document.getElementById("studentDetailSubjectFilter");
        var dateFilter = document.getElementById("studentDetailDateFilter");

        if (searchInput) searchInput.value = "";
        if (subjectFilter) subjectFilter.value = "";
        if (dateFilter) dateFilter.value = "";

        window.studentDetailCurrentPage = 1;
        renderStudentDetailTable();
    }

    // Make resetStudentDetailFilters globally available
    window.resetStudentDetailFilters = resetStudentDetailFilters;
})();

