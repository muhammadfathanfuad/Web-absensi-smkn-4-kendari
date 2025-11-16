// Admin Dashboard JavaScript
// This file handles all dashboard functionality including charts and pagination

(function () {
    "use strict";

    // Get data from Laravel (passed via window.dashboardData)
    var dashboardData = window.dashboardData || {};
    var attendanceTrendsData = dashboardData.attendanceTrends || [];
    var teacherPerformanceData = dashboardData.teacherPerformance || [];
    var routes = dashboardData.routes || {};

    // Convert to array if it's an object (Laravel Collection)
    if (
        typeof teacherPerformanceData === "object" &&
        !Array.isArray(teacherPerformanceData)
    ) {
        teacherPerformanceData = Object.values(teacherPerformanceData);
    }

    // Prepare categories for chart
    var allCategories =
        attendanceTrendsData.length > 0
            ? attendanceTrendsData.map((item) => item.date || "")
            : ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"];

    // Attendance Trends Chart Options
    var attendanceTrendsOptions = {
        series: [
            {
                name: "Hadir",
                data:
                    attendanceTrendsData.length > 0
                        ? attendanceTrendsData.map((item) => item.present || 0)
                        : [0, 0, 0, 0, 0, 0, 0],
            },
            {
                name: "Tidak Hadir",
                data:
                    attendanceTrendsData.length > 0
                        ? attendanceTrendsData.map((item) => item.absent || 0)
                        : [0, 0, 0, 0, 0, 0, 0],
            },
        ],
        chart: {
            type: "area",
            height: 350,
            fontFamily: "inherit",
            toolbar: {
                show: true,
            },
        },
        colors: ["#34c38f", "#f46a6a"],
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: "smooth",
            width: 2,
        },
        xaxis: {
            categories: allCategories,
            labels: {
                style: {
                    fontSize: "12px",
                    fontFamily: "inherit",
                },
                formatter: function (value) {
                    var index = allCategories.indexOf(value);
                    if (index !== -1 && index % 5 === 0) {
                        return value;
                    }
                    return "";
                },
                rotate: -45,
                rotateAlways: false,
                hideOverlappingLabels: false,
            },
        },
        yaxis: {
            title: {
                text: "",
                style: {
                    fontSize: "12px",
                    fontFamily: "inherit",
                },
            },
            labels: {
                style: {
                    fontSize: "12px",
                    fontFamily: "inherit",
                },
            },
        },
        legend: {
            position: "top",
            horizontalAlign: "left",
            fontSize: "12px",
            fontFamily: "inherit",
            markers: {
                width: 8,
                height: 8,
                radius: 2,
            },
            itemMargin: {
                horizontal: 15,
                vertical: 8,
            },
        },
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
                stops: [0, 90, 100],
            },
        },
        tooltip: {
            shared: true,
            intersect: false,
            style: {
                fontSize: "12px",
                fontFamily: "inherit",
            },
            x: {
                show: true,
                formatter: function (value, opts) {
                    try {
                        var dataPointIndex =
                            opts && opts.dataPointIndex !== undefined
                                ? opts.dataPointIndex
                                : null;

                        if (
                            dataPointIndex !== null &&
                            allCategories &&
                            allCategories[dataPointIndex] !== undefined
                        ) {
                            return allCategories[dataPointIndex];
                        }

                        if (
                            opts &&
                            opts.w &&
                            opts.w.globals &&
                            opts.w.globals.categoryLabels
                        ) {
                            var categories = opts.w.globals.categoryLabels;
                            if (
                                dataPointIndex !== null &&
                                categories[dataPointIndex] !== undefined
                            ) {
                                return categories[dataPointIndex];
                            }
                        }

                        return value || "";
                    } catch (e) {
                        console.error("Error in tooltip x formatter:", e);
                        return value || "";
                    }
                },
            },
            y: {
                formatter: function (value) {
                    try {
                        return value + " siswa";
                    } catch (e) {
                        return value;
                    }
                },
            },
        },
        responsive: [
            {
                breakpoint: 768,
                options: {
                    legend: {
                        position: "top",
                        horizontalAlign: "left",
                        fontSize: "12px",
                        fontFamily: "inherit",
                        markers: {
                            width: 8,
                            height: 8,
                            radius: 2,
                        },
                        itemMargin: {
                            horizontal: 0,
                            vertical: 8,
                        },
                    },
                },
            },
        ],
    };

    // Teacher Performance Chart Options
    var teacherPerformanceOptions = {
        series: [
            {
                name: "Jam Terjadwal",
                data:
                    teacherPerformanceData && teacherPerformanceData.length > 0
                        ? teacherPerformanceData.map(
                              (item) => item.scheduled_hours || 0
                          )
                        : [0, 0, 0, 0, 0],
            },
            {
                name: "Jam Aktual",
                data:
                    teacherPerformanceData && teacherPerformanceData.length > 0
                        ? teacherPerformanceData.map(
                              (item) => item.actual_hours || 0
                          )
                        : [0, 0, 0, 0, 0],
            },
        ],
        chart: {
            type: "bar",
            height: 350,
            fontFamily: "inherit",
            toolbar: {
                show: true,
            },
        },
        colors: ["#f46a6a", "#34c38f"],
        plotOptions: {
            bar: {
                horizontal: true,
                columnWidth: "55%",
                borderRadius: 4,
            },
        },
        dataLabels: {
            enabled: false,
        },
        xaxis: {
            categories:
                teacherPerformanceData && teacherPerformanceData.length > 0
                    ? teacherPerformanceData.map((item) => item.name || "")
                    : ["Guru 1", "Guru 2", "Guru 3", "Guru 4", "Guru 5"],
            labels: {
                style: {
                    fontSize: "12px",
                    fontFamily: "inherit",
                },
            },
        },
        yaxis: {
            title: {
                text: "",
                style: {
                    fontSize: "12px",
                    fontFamily: "inherit",
                },
            },
            labels: {
                style: {
                    fontSize: "12px",
                    fontFamily: "inherit",
                },
            },
        },
        legend: {
            position: "top",
            horizontalAlign: "left",
            fontSize: "12px",
            fontFamily: "inherit",
            markers: {
                width: 8,
                height: 8,
                radius: 2,
            },
            itemMargin: {
                horizontal: 15,
                vertical: 8,
            },
        },
        tooltip: {
            style: {
                fontSize: "12px",
                fontFamily: "inherit",
            },
            y: {
                formatter: function (val) {
                    return val + " jam";
                },
            },
        },
        responsive: [
            {
                breakpoint: 768,
                options: {
                    chart: {
                        height: 400,
                    },
                    yaxis: {
                        labels: {
                            style: {
                                fontSize: "10px",
                                fontFamily: "inherit",
                            },
                            formatter: function (value) {
                                if (value && value.length > 20) {
                                    return value.substring(0, 18) + "...";
                                }
                                return value;
                            },
                            maxWidth: 80,
                        },
                    },
                    xaxis: {
                        labels: {
                            style: {
                                fontSize: "10px",
                                fontFamily: "inherit",
                            },
                        },
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            columnWidth: "60%",
                            borderRadius: 4,
                            barHeight: "70%",
                        },
                    },
                    legend: {
                        fontSize: "11px",
                        itemMargin: {
                            horizontal: 10,
                            vertical: 6,
                        },
                    },
                    tooltip: {
                        style: {
                            fontSize: "10px",
                            fontFamily: "inherit",
                        },
                        y: {
                            formatter: function (val) {
                                return val + " jam";
                            },
                        },
                    },
                },
            },
        ],
    };

    // Function to force vertical legend on mobile
    function forceVerticalLegend() {
        if (window.innerWidth <= 768) {
            var chartContainer = document.querySelector(
                "#attendanceTrendsChart"
            );
            if (chartContainer) {
                var legend = chartContainer.querySelector(".apexcharts-legend");
                if (legend) {
                    var styleId = "mobile-legend-style";
                    var existingStyle = document.getElementById(styleId);
                    if (!existingStyle) {
                        var style = document.createElement("style");
                        style.id = styleId;
                        style.textContent =
                            "@media (max-width: 768px) { " +
                            "#attendanceTrendsChart .apexcharts-legend { " +
                            "display: flex !important; " +
                            "flex-direction: column !important; " +
                            "align-items: flex-start !important; " +
                            "justify-content: flex-start !important; " +
                            "flex-wrap: nowrap !important; " +
                            "width: 100% !important; } " +
                            "#attendanceTrendsChart .apexcharts-legend-series { " +
                            "display: flex !important; " +
                            "flex-direction: row !important; " +
                            "align-items: center !important; " +
                            "margin-right: 0 !important; " +
                            "margin-bottom: 8px !important; " +
                            "width: 100% !important; " +
                            "max-width: 100% !important; " +
                            "float: none !important; " +
                            "clear: both !important; } }";
                        document.head.appendChild(style);
                    }

                    legend.style.display = "flex";
                    legend.style.flexDirection = "column";
                    legend.style.alignItems = "flex-start";
                    legend.style.justifyContent = "flex-start";
                    legend.style.flexWrap = "nowrap";
                    legend.style.width = "100%";

                    var legendSeries = legend.querySelectorAll(
                        ".apexcharts-legend-series"
                    );
                    legendSeries.forEach(function (series) {
                        series.style.display = "flex";
                        series.style.flexDirection = "row";
                        series.style.alignItems = "center";
                        series.style.marginRight = "0";
                        series.style.marginBottom = "8px";
                        series.style.width = "100%";
                        series.style.maxWidth = "100%";
                        series.style.float = "none";
                        series.style.clear = "both";
                    });
                }
            }
        }
    }

    // Wait for ApexCharts to be available
    function waitForApexCharts(callback, maxAttempts) {
        maxAttempts = maxAttempts || 50;
        var attempts = 0;
        var checkInterval = setInterval(function () {
            attempts++;
            if (typeof ApexCharts !== "undefined") {
                clearInterval(checkInterval);
                callback();
            } else if (attempts >= maxAttempts) {
                clearInterval(checkInterval);
                console.error(
                    "ApexCharts failed to load after " +
                        maxAttempts +
                        " attempts"
                );
            }
        }, 100);
    }

    // Initialize charts function
    function initializeCharts() {
        waitForApexCharts(function () {
            try {
                if (typeof ApexCharts === "undefined") {
                    console.error("ApexCharts is not available");
                    return;
                }

                // Initialize Attendance Trends Chart
                if (document.getElementById("attendanceTrendsChart")) {
                    var attendanceTrendsChart = new ApexCharts(
                        document.querySelector("#attendanceTrendsChart"),
                        attendanceTrendsOptions
                    );

                    attendanceTrendsChart.render().then(function () {
                        setTimeout(forceVerticalLegend, 100);
                        setTimeout(forceVerticalLegend, 300);
                        setTimeout(forceVerticalLegend, 500);

                        var chartContainer = document.querySelector(
                            "#attendanceTrendsChart"
                        );
                        if (chartContainer && window.MutationObserver) {
                            var observer = new MutationObserver(function (
                                mutations
                            ) {
                                forceVerticalLegend();
                            });
                            observer.observe(chartContainer, {
                                childList: true,
                                subtree: true,
                                attributes: true,
                                attributeFilter: ["style", "class"],
                            });
                        }

                        var resizeTimeout;
                        var resizeHandler = function () {
                            clearTimeout(resizeTimeout);
                            resizeTimeout = setTimeout(
                                forceVerticalLegend,
                                150
                            );
                        };
                        window.addEventListener("resize", resizeHandler);

                        var checkInterval = setInterval(function () {
                            if (window.innerWidth <= 768) {
                                forceVerticalLegend();
                            } else {
                                clearInterval(checkInterval);
                            }
                        }, 1000);

                        setTimeout(function () {
                            clearInterval(checkInterval);
                        }, 10000);
                    });
                }

                // Initialize Teacher Performance Chart
                if (document.getElementById("teacherPerformanceChart")) {
                    window.teacherPerformanceChart = new ApexCharts(
                        document.querySelector("#teacherPerformanceChart"),
                        teacherPerformanceOptions
                    );
                    window.teacherPerformanceChart.render();
                }
            } catch (error) {
                console.error("Error initializing charts:", error);
            }
        });
    }

    // Initialize charts when document is ready or if already ready
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initializeCharts);
    } else {
        // DOM is already ready
        initializeCharts();
    }

    // ============================================
    // GENERIC PAGINATION HANDLER (DRY Principle)
    // ============================================

    /**
     * Creates a generic pagination handler
     * @param {Object} config - Configuration object
     * @param {string} config.linkSelector - CSS selector for pagination links
     * @param {string} config.endpoint - API endpoint URL
     * @param {string} config.tableSelector - CSS selector for table tbody
     * @param {string} config.paginationContainerSelector - CSS selector for pagination container
     * @param {string} config.paginationInfoSelector - CSS selector for pagination info element
     * @param {number} config.itemsPerPage - Number of items per page
     * @param {string} config.emptyMessage - Message to show when no data
     * @param {string} config.emptyIcon - Icon for empty state
     * @param {number} config.emptyColspan - Colspan for empty row
     * @param {string} config.label - Label for pagination info (e.g., 'guru', 'kelas')
     * @param {Function} config.rowRenderer - Function to render table row (item, index, paginationData)
     * @param {Function} config.onDataUpdate - Optional callback after data update (paginationData)
     * @param {Function} config.onLoadingStart - Optional callback when loading starts
     */
    function createPaginationHandler(config) {
        document.addEventListener("click", function (e) {
            var paginationLink = e.target.closest(config.linkSelector);

            if (paginationLink) {
                e.preventDefault();

                var page = paginationLink.getAttribute("data-page");
                if (!page) return;

                // Disable all pagination links
                var paginationLinks = document.querySelectorAll(
                    config.linkSelector
                );
                paginationLinks.forEach(function (link) {
                    link.classList.add("disabled");
                    link.style.pointerEvents = "none";
                });

                // Call loading start callback if provided
                if (config.onLoadingStart) {
                    config.onLoadingStart();
                }

                // Fetch data
                fetch(config.endpoint + "?page=" + page)
                    .then((response) => {
                        if (!response.ok)
                            throw new Error("Network response was not ok");
                        return response.json();
                    })
                    .then((data) => {
                        if (data.success) {
                            updatePaginationData(data.data, config);
                        } else {
                            alert(
                                "Error loading data: " +
                                    (data.message || "Unknown error")
                            );
                        }
                    })
                    .catch((error) => {
                        console.error("Pagination error:", error);
                        alert("Error loading data. Please try again.");
                    })
                    .finally(() => {
                        // Re-enable pagination links
                        paginationLinks.forEach(function (link) {
                            link.classList.remove("disabled");
                            link.style.pointerEvents = "auto";
                        });
                    });
            }
        });
    }

    /**
     * Generic function to update pagination data
     */
    function updatePaginationData(paginationData, config) {
        // Update table
        updatePaginationTable(paginationData, config);

        // Update pagination controls
        renderPaginationControls(paginationData, config);

        // Update pagination info
        updatePaginationInfo(paginationData, config);

        // Call custom callback if provided
        if (config.onDataUpdate) {
            config.onDataUpdate(paginationData);
        }
    }

    /**
     * Generic function to update table body
     */
    function updatePaginationTable(paginationData, config) {
        var tbody = document.querySelector(config.tableSelector);
        if (!tbody) return;

        tbody.innerHTML = "";

        var data = paginationData.data || [];

        if (data.length === 0) {
            var emptyRow =
                '<tr><td colspan="' +
                config.emptyColspan +
                '" class="text-center py-4">' +
                '<div class="text-muted d-flex flex-column align-items-center">' +
                '<iconify-icon icon="' +
                config.emptyIcon +
                '" class="fs-48 mb-2"></iconify-icon>' +
                config.emptyMessage +
                "</div></td></tr>";
            tbody.innerHTML = emptyRow;
            return;
        }

        data.forEach(function (item, index) {
            var row = config.rowRenderer(item, index, paginationData);
            tbody.insertAdjacentHTML("beforeend", row);
        });
    }

    /**
     * Generic function to render pagination controls
     */
    function renderPaginationControls(paginationData, config) {
        var paginationContainer = document.querySelector(
            config.paginationContainerSelector
        );
        if (!paginationContainer) return;

        paginationContainer.innerHTML = "";
        var currentPage = parseInt(paginationData.current_page);
        var linkClass = config.linkSelector.replace(".", ""); // Remove dot from selector

        // Previous button
        if (currentPage > 1) {
            paginationContainer.insertAdjacentHTML(
                "beforeend",
                '<li class="page-item"><a class="page-link ' +
                    linkClass +
                    '" href="#" data-page="' +
                    (currentPage - 1) +
                    '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>'
            );
        } else {
            paginationContainer.insertAdjacentHTML(
                "beforeend",
                '<li class="page-item disabled"><span class="page-link" aria-label="Previous"><span aria-hidden="true">&laquo;</span></span></li>'
            );
        }

        // Page numbers
        for (var i = 1; i <= paginationData.last_page; i++) {
            if (i === currentPage) {
                paginationContainer.insertAdjacentHTML(
                    "beforeend",
                    '<li class="page-item active" style="background-color: #0d6efd !important;"><span class="page-link" style="background-color: #0d6efd !important; color: white !important; border-color: #0d6efd !important;">' +
                        i +
                        "</span></li>"
                );
            } else {
                paginationContainer.insertAdjacentHTML(
                    "beforeend",
                    '<li class="page-item"><a class="page-link ' +
                        linkClass +
                        '" href="#" data-page="' +
                        i +
                        '">' +
                        i +
                        "</a></li>"
                );
            }
        }

        // Next button
        if (currentPage < paginationData.last_page) {
            paginationContainer.insertAdjacentHTML(
                "beforeend",
                '<li class="page-item"><a class="page-link ' +
                    linkClass +
                    '" href="#" data-page="' +
                    (currentPage + 1) +
                    '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>'
            );
        } else {
            paginationContainer.insertAdjacentHTML(
                "beforeend",
                '<li class="page-item disabled"><span class="page-link" aria-label="Next"><span aria-hidden="true">&raquo;</span></span></li>'
            );
        }
    }

    /**
     * Generic function to update pagination info
     */
    function updatePaginationInfo(paginationData, config) {
        // Support both ID selector (#id) and other selectors
        var paginationInfoEl;
        if (config.paginationInfoSelector.startsWith("#")) {
            paginationInfoEl = document.getElementById(
                config.paginationInfoSelector.substring(1)
            );
        } else {
            paginationInfoEl = document.querySelector(
                config.paginationInfoSelector
            );
        }

        if (paginationInfoEl) {
            paginationInfoEl.textContent =
                "Menampilkan " +
                (paginationData.from || 0) +
                " - " +
                (paginationData.to || 0) +
                " dari " +
                (paginationData.total || 0) +
                " " +
                config.label;
        }
    }

    // ============================================
    // TEACHER PAGINATION (Refactored)
    // ============================================

    // Row renderer for teacher table
    function renderTeacherRow(teacher, index, paginationData) {
        var rowNumber = (paginationData.current_page - 1) * 5 + index + 1;
        var complianceClass =
            teacher.compliance_rate >= 80
                ? "bg-success"
                : teacher.compliance_rate >= 60
                ? "bg-warning"
                : "bg-danger";

        return (
            "<tr>" +
            "<td>" +
            rowNumber +
            "</td>" +
            '<td><span class="fw-semibold">' +
            teacher.name +
            "</span></td>" +
            "<td>" +
            teacher.nip +
            "</td>" +
            '<td><span class="badge bg-danger-subtle text-danger py-1 px-2">' +
            teacher.scheduled_hours +
            " jam</span></td>" +
            '<td><span class="badge bg-success-subtle text-success py-1 px-2">' +
            teacher.actual_hours +
            " jam</span></td>" +
            '<td><div class="d-flex align-items-center">' +
            '<div class="progress progress-soft progress-sm me-2" style="width: 60px;">' +
            '<div class="progress-bar ' +
            complianceClass +
            '" role="progressbar" ' +
            'style="width: ' +
            teacher.compliance_rate +
            '%" ' +
            'aria-valuenow="' +
            teacher.compliance_rate +
            '" ' +
            'aria-valuemin="0" aria-valuemax="100"></div></div>' +
            '<span class="fw-semibold">' +
            teacher.compliance_rate +
            "%</span></div></td>" +
            "<td>" +
            teacher.sessions_conducted +
            "</td>" +
            "</tr>"
        );
    }

    // Callback to update chart and statistics after teacher data update
    function onTeacherDataUpdate(paginationData) {
        var chartData = paginationData.data || [];
        var scheduledHours = chartData.map((item) => item.scheduled_hours || 0);
        var actualHours = chartData.map((item) => item.actual_hours || 0);
        var categories = chartData.map((item) => item.name || "");

        // Update chart
        if (window.teacherPerformanceChart) {
            window.teacherPerformanceChart.updateOptions({
                series: [
                    { name: "Jam Terjadwal", data: scheduledHours },
                    { name: "Jam Aktual", data: actualHours },
                ],
                xaxis: { categories: categories },
            });
        }

        // Update statistics
        updateTeacherStatistics(paginationData);
    }

    // Loading callback for teacher pagination
    function onTeacherLoadingStart() {
        var chartContainer = document.getElementById("teacherPerformanceChart");
        if (chartContainer) {
            chartContainer.innerHTML =
                '<div class="d-flex justify-content-center align-items-center" style="height: 350px;">' +
                '<div class="spinner-border text-primary" role="status">' +
                '<span class="visually-hidden">Loading...</span></div></div>';
        }
    }

    // Initialize teacher pagination
    function initVanillaPagination() {
        createPaginationHandler({
            linkSelector: ".pagination-link",
            endpoint: routes.teacherPagination,
            tableSelector: "#teacher-performance-table tbody",
            paginationContainerSelector: "#teacher-pagination",
            paginationInfoSelector: "#pagination-info",
            itemsPerPage: 5,
            emptyMessage: "Tidak ada data guru.",
            emptyIcon: "solar:user-outline",
            emptyColspan: 7,
            label: "guru",
            rowRenderer: renderTeacherRow,
            onDataUpdate: onTeacherDataUpdate,
            onLoadingStart: onTeacherLoadingStart,
        });
    }

    // Update teacher statistics (specific to teacher pagination)
    function updateTeacherStatistics(paginationData) {
        var data = paginationData.data || [];
        var totalActualHours = data.reduce(
            (sum, teacher) => sum + (teacher.actual_hours || 0),
            0
        );
        var totalScheduledHours = data.reduce(
            (sum, teacher) => sum + (teacher.scheduled_hours || 0),
            0
        );
        var avgCompliance =
            data.length > 0
                ? (
                      data.reduce(
                          (sum, teacher) =>
                              sum + (teacher.compliance_rate || 0),
                          0
                      ) / data.length
                  ).toFixed(1)
                : 0;

        var totalActiveTeachersEl = document.getElementById(
            "totalActiveTeachers"
        );
        var totalActualHoursEl = document.getElementById("totalActualHours");
        var totalScheduledHoursEl = document.getElementById(
            "totalScheduledHours"
        );
        var avgComplianceEl = document.getElementById("avgCompliance");

        if (totalActiveTeachersEl)
            totalActiveTeachersEl.textContent = paginationData.total || 0;
        if (totalActualHoursEl)
            totalActualHoursEl.textContent = totalActualHours.toFixed(1);
        if (totalScheduledHoursEl)
            totalScheduledHoursEl.textContent = totalScheduledHours.toFixed(1);
        if (avgComplianceEl) avgComplianceEl.textContent = avgCompliance + "%";

        var totalActiveTeachersMobileEl = document.getElementById(
            "totalActiveTeachersMobile"
        );
        var totalActualHoursMobileEl = document.getElementById(
            "totalActualHoursMobile"
        );
        var totalScheduledHoursMobileEl = document.getElementById(
            "totalScheduledHoursMobile"
        );
        var avgComplianceMobileEl = document.getElementById(
            "avgComplianceMobile"
        );

        if (totalActiveTeachersMobileEl)
            totalActiveTeachersMobileEl.textContent = paginationData.total || 0;
        if (totalActualHoursMobileEl)
            totalActualHoursMobileEl.textContent = totalActualHours.toFixed(1);
        if (totalScheduledHoursMobileEl)
            totalScheduledHoursMobileEl.textContent =
                totalScheduledHours.toFixed(1);
        if (avgComplianceMobileEl)
            avgComplianceMobileEl.textContent = avgCompliance + "%";

        var paginationInfoEl = document.getElementById("pagination-info");
        if (paginationInfoEl) {
            paginationInfoEl.textContent =
                "Menampilkan " +
                (paginationData.from || 0) +
                " - " +
                (paginationData.to || 0) +
                " dari " +
                (paginationData.total || 0) +
                " guru";
        }
    }

    // ============================================
    // CLASS PAGINATION (Refactored)
    // ============================================

    // Row renderer for class table
    function renderClassRow(classItem, index, paginationData) {
        var rowNumber = (paginationData.current_page - 1) * 10 + index + 1;
        var groupBadge =
            classItem.group !== "-"
                ? '<span class="badge bg-warning-subtle text-warning py-1 px-2">' +
                  classItem.group +
                  "</span>"
                : '<span class="text-muted">-</span>';

        return (
            "<tr>" +
            "<td>" +
            rowNumber +
            "</td>" +
            '<td><span class="fw-semibold">' +
            classItem.name +
            "</span></td>" +
            '<td><span class="badge bg-primary-subtle text-primary py-1 px-2">Grade ' +
            classItem.grade +
            "</span></td>" +
            "<td>" +
            groupBadge +
            "</td>" +
            '<td><span class="badge bg-success-subtle text-success py-1 px-2">' +
            classItem.students_count +
            " siswa</span></td>" +
            '<td><span class="badge bg-warning-subtle text-warning py-1 px-2">' +
            classItem.subjects_count +
            " mapel</span></td>" +
            "</tr>"
        );
    }

    // Initialize class pagination
    function initClassPagination() {
        createPaginationHandler({
            linkSelector: ".class-pagination-link",
            endpoint: routes.classPagination,
            tableSelector: "#class-statistics-table tbody",
            paginationContainerSelector: "#class-pagination",
            paginationInfoSelector: "#class-pagination-info",
            itemsPerPage: 10,
            emptyMessage: "Tidak ada data kelas.",
            emptyIcon: "solar:home-outline",
            emptyColspan: 6,
            label: "kelas",
            rowRenderer: renderClassRow,
        });
    }

    // ============================================
    // ACTIVITIES PAGINATION (Refactored)
    // ============================================

    // Row renderer for activities table
    function renderActivityRow(activity, index, paginationData) {
        var rowNumber = (paginationData.current_page - 1) * 5 + index + 1;
        return (
            "<tr>" +
            "<td>" +
            rowNumber +
            "</td>" +
            '<td><div class="d-flex align-items-center">' +
            '<div class="avatar-xs bg-' +
            activity.color +
            '-subtle rounded-circle me-2 d-flex align-items-center justify-content-center">' +
            '<iconify-icon icon="solar:' +
            activity.icon +
            '-outline" class="fs-16 text-' +
            activity.color +
            '"></iconify-icon>' +
            '</div><span class="fw-semibold">' +
            activity.description +
            "</span></div></td>" +
            '<td><span class="badge bg-light text-dark py-1 px-2">' +
            activity.time_formatted +
            "</span></td>" +
            "</tr>"
        );
    }

    // Initialize activities pagination
    function initActivitiesPagination() {
        createPaginationHandler({
            linkSelector: ".activities-pagination-link",
            endpoint: routes.activitiesPagination,
            tableSelector: "#activities-table tbody",
            paginationContainerSelector: "#activities-pagination",
            paginationInfoSelector: "#activities-pagination-info",
            itemsPerPage: 5,
            emptyMessage: "Tidak ada aktivitas terbaru.",
            emptyIcon: "solar:clock-outline",
            emptyColspan: 3,
            label: "aktivitas",
            rowRenderer: renderActivityRow,
        });
    }

    // Initialize all pagination functions
    document.addEventListener("DOMContentLoaded", function () {
        initVanillaPagination();
        initClassPagination();
        initActivitiesPagination();
    });
})();
