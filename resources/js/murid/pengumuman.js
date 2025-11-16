// Pengumuman JavaScript
// This file handles announcement functionality for students

// CRITICAL: Prevent duplicate declarations - use window-level variable
if (typeof window._pengumumanVars === "undefined") {
    window._pengumumanVars = {
        allAnnouncements: [],
    };
}

// Use local reference for convenience
var allAnnouncements = window._pengumumanVars.allAnnouncements;

document.addEventListener("DOMContentLoaded", function () {
    loadAnnouncements();

    const categoryFilter = document.getElementById("categoryFilter");
    const dateFilter = document.getElementById("dateFilter");
    const searchInput = document.getElementById("searchInput");

    if (categoryFilter) {
        categoryFilter.addEventListener("change", filterPengumuman);
    }
    if (dateFilter) {
        dateFilter.addEventListener("change", filterPengumuman);
    }
    if (searchInput) {
        searchInput.addEventListener("input", filterPengumuman);
    }

    // Auto-refresh announcements every 30 seconds
    setInterval(function () {
        checkForUpdates();
    }, 30000);
});

// Check for updates without full reload
function checkForUpdates() {
    fetch("/api/announcements/students")
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Check if data has changed by comparing with stored data
                const currentData = JSON.stringify(data.data);
                const storedData = localStorage.getItem(
                    "announcements_students"
                );

                if (currentData !== storedData) {
                    // Data has changed, update the display
                    localStorage.setItem("announcements_students", currentData);
                    allAnnouncements = data.data;
                    window._pengumumanVars.allAnnouncements = data.data; // Sync with window
                    renderAnnouncements(data.data);

                    // Show subtle notification that data was updated
                    showUpdateNotification();
                }
            }
        })
        .catch((error) => {
            console.error("Error checking for updates:", error);
        });
}

// Show subtle update notification
function showUpdateNotification() {
    const notification = document.createElement("div");
    notification.className =
        "alert alert-info alert-dismissible fade show position-fixed";
    notification.style.cssText =
        "top: 20px; right: 20px; z-index: 9999; min-width: 250px; font-size: 12px;";
    notification.innerHTML = `
        <i class="bx bx-refresh me-1"></i>
        Pengumuman diperbarui
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notification);

    setTimeout(() => {
        if (notification && notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

function loadAnnouncements() {
    fetch("/api/announcements/students")
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                allAnnouncements = data.data;
                window._pengumumanVars.allAnnouncements = data.data; // Sync with window
                renderAnnouncements(allAnnouncements);
            } else {
                showNoAnnouncements();
            }
        })
        .catch((error) => {
            console.error("Error loading announcements:", error);
            showNoAnnouncements();
        });
}

function renderAnnouncements(announcements) {
    const container = document.getElementById("pengumumanList");
    if (!container) return;

    if (announcements.length === 0) {
        showNoAnnouncements();
        return;
    }

    const announcementsHtml = announcements
        .map((announcement) => {
            const categoryColor = getCategoryColor(announcement.category);
            const categoryIcon = getCategoryIcon(announcement.category);
            const createdDate = new Date(announcement.created_at);
            const timeAgo = getTimeAgo(createdDate);
            const isRead = announcement.is_read_by_current_user;

            const cardClass = isRead
                ? "border-secondary"
                : `border-${categoryColor}`;
            const headerClass = isRead ? "bg-secondary" : `bg-${categoryColor}`;
            const titleClass = isRead ? "text-muted" : "";
            const buttonClass = isRead
                ? "btn-outline-secondary"
                : `btn-outline-${categoryColor}`;

            return `
            <div class="col-md-6 mb-3 announcement-item" data-category="${
                announcement.category
            }" data-date="${getDateCategory(createdDate)}" data-id="${
                announcement.id
            }" data-read="${isRead}">
                <div class="card ${cardClass} ${isRead ? "opacity-75" : ""}">
                    <div class="card-header ${headerClass} text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="bx ${categoryIcon} me-1"></i>
                                ${announcement.category.toUpperCase()}
                            </h6>
                            <small>${timeAgo}</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title ${titleClass}">${
                announcement.title
            }</h5>
                        <p class="card-text">${announcement.content}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bx bx-user me-1"></i>
                                ${
                                    announcement.creator
                                        ? announcement.creator.full_name
                                        : "Admin"
                                }
                            </small>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm ${
                                    isRead
                                        ? "btn-outline-secondary"
                                        : "btn-outline-primary"
                                } mark-read-btn" 
                                        onclick="toggleReadStatus(${
                                            announcement.id
                                        }, ${isRead})"
                                        data-announcement-id="${
                                            announcement.id
                                        }">
                                    <i class="bx ${
                                        isRead ? "bx-undo" : "bx-check"
                                    } me-1"></i>
                                    ${isRead ? "Belum Dibaca" : "Telah Dibaca"}
                                </button>
                                <button class="btn btn-sm ${buttonClass}" onclick="viewAnnouncement(${
                announcement.id
            })">Baca Selengkapnya</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        })
        .join("");

    container.innerHTML = announcementsHtml;
}

function getCategoryColor(category) {
    const colors = {
        penting: "danger",
        akademik: "success",
        kegiatan: "primary",
        umum: "info",
    };
    return colors[category] || "info";
}

function getCategoryIcon(category) {
    const icons = {
        penting: "bx-error-circle",
        akademik: "bx-book",
        kegiatan: "bx-calendar-event",
        umum: "bx-info-circle",
    };
    return icons[category] || "bx-info-circle";
}

function getTimeAgo(date) {
    const now = new Date();
    const diffInHours = Math.floor((now - date) / (1000 * 60 * 60));

    if (diffInHours < 1) return "Baru saja";
    if (diffInHours < 24) return `${diffInHours} jam lalu`;

    const diffInDays = Math.floor(diffInHours / 24);
    if (diffInDays < 7) return `${diffInDays} hari lalu`;

    const diffInWeeks = Math.floor(diffInDays / 7);
    if (diffInWeeks < 4) return `${diffInWeeks} minggu lalu`;

    const diffInMonths = Math.floor(diffInDays / 30);
    return `${diffInMonths} bulan lalu`;
}

function getDateCategory(date) {
    const now = new Date();
    const diffInHours = Math.floor((now - date) / (1000 * 60 * 60));

    if (diffInHours < 24) return "today";
    if (diffInHours < 168) return "week"; // 7 days
    return "month";
}

function filterPengumuman() {
    const categoryFilter = document.getElementById("categoryFilter");
    const dateFilter = document.getElementById("dateFilter");
    const searchInput = document.getElementById("searchInput");
    const pengumumanList = document.getElementById("pengumumanList");

    if (!categoryFilter || !dateFilter || !searchInput || !pengumumanList)
        return;

    const cards = pengumumanList.querySelectorAll(".col-md-6");
    const selectedCategory = categoryFilter.value;
    const selectedDate = dateFilter.value;
    const searchTerm = searchInput.value.toLowerCase();

    cards.forEach((card) => {
        const category = card.getAttribute("data-category");
        const date = card.getAttribute("data-date");
        const titleEl = card.querySelector(".card-title");
        const contentEl = card.querySelector(".card-text");

        if (!titleEl || !contentEl) return;

        const title = titleEl.textContent.toLowerCase();
        const content = contentEl.textContent.toLowerCase();

        const categoryMatch =
            !selectedCategory || category === selectedCategory;
        const dateMatch = !selectedDate || date === selectedDate;
        const searchMatch =
            !searchTerm ||
            title.includes(searchTerm) ||
            content.includes(searchTerm);

        if (categoryMatch && dateMatch && searchMatch) {
            card.style.display = "";
        } else {
            card.style.display = "none";
        }
    });
}

function viewAnnouncement(id) {
    // Use window-level variable to ensure consistency
    const announcements =
        window._pengumumanVars && window._pengumumanVars.allAnnouncements
            ? window._pengumumanVars.allAnnouncements
            : allAnnouncements;
    const announcement = announcements.find((ann) => ann.id === id);
    if (!announcement) {
        showNotification("Pengumuman tidak ditemukan", false);
        return;
    }

    // Update modal content
    const modalTitle = document.getElementById("modalTitle");
    const modalContent = document.getElementById("modalContent");
    const modalAuthor = document.getElementById("modalAuthor");
    const modalCategory = document.getElementById("modalCategory");

    if (!modalTitle || !modalContent || !modalAuthor || !modalCategory) return;

    modalTitle.textContent = announcement.title;
    modalContent.textContent = announcement.content;
    modalAuthor.textContent = announcement.creator
        ? announcement.creator.full_name
        : "Admin";
    modalCategory.textContent = announcement.category.toUpperCase();

    // Format dates
    const createdDate = new Date(announcement.created_at);
    const expiresDate = announcement.expires_at
        ? new Date(announcement.expires_at)
        : null;

    const modalDate = document.getElementById("modalDate");
    const modalCreatedAt = document.getElementById("modalCreatedAt");
    const modalExpiresAt = document.getElementById("modalExpiresAt");

    if (modalDate) {
        modalDate.textContent = createdDate.toLocaleDateString("id-ID", {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
        });
    }

    if (modalCreatedAt) {
        modalCreatedAt.textContent = createdDate.toLocaleString("id-ID");
    }

    if (modalExpiresAt) {
        modalExpiresAt.textContent = expiresDate
            ? expiresDate.toLocaleString("id-ID")
            : "Tidak ada batas waktu";
    }

    // Update priority badge
    const priorityBadge = document.getElementById("modalPriority");
    if (priorityBadge) {
        const priorityColors = {
            urgent: "bg-danger",
            high: "bg-warning",
            normal: "bg-info",
            low: "bg-secondary",
        };
        priorityBadge.className = `badge ${
            priorityColors[announcement.priority] || "bg-info"
        }`;
        priorityBadge.textContent = announcement.priority.toUpperCase();
    }

    // Update icon and colors based on category
    const categoryColor = getCategoryColor(announcement.category);
    const categoryIcon = getCategoryIcon(announcement.category);
    const modalIcon = document.getElementById("modalIcon");
    const modalIconClass = document.getElementById("modalIconClass");

    if (modalIcon) {
        modalIcon.className = `avatar-sm bg-${categoryColor} bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center`;
    }

    if (modalIconClass) {
        modalIconClass.className = `bx ${categoryIcon} fs-20 text-${categoryColor}`;
    }

    // Update mark read button
    const modalMarkReadBtn = document.getElementById("modalMarkReadBtn");
    if (modalMarkReadBtn) {
        const isRead = announcement.is_read_by_current_user;

        if (isRead) {
            modalMarkReadBtn.className = "btn btn-outline-secondary";
            modalMarkReadBtn.innerHTML =
                '<i class="bx bx-undo me-1"></i>Belum Dibaca';
        } else {
            modalMarkReadBtn.className = "btn btn-primary";
            modalMarkReadBtn.innerHTML =
                '<i class="bx bx-check me-1"></i>Telah Dibaca';
        }

        // Store current announcement ID for modal actions
        modalMarkReadBtn.setAttribute("data-announcement-id", announcement.id);
        modalMarkReadBtn.setAttribute("data-is-read", isRead);
    }

    // Show modal
    const modalEl = document.getElementById("readMoreModal");
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

function showNoAnnouncements() {
    const container = document.getElementById("pengumumanList");
    if (!container) return;

    container.innerHTML = `
        <div class="col-12 text-center py-4">
            <div class="text-muted">
                <iconify-icon icon="solar:megaphone-outline" class="fs-48 d-block mb-2"></iconify-icon>
                Belum ada pengumuman untuk siswa.
            </div>
        </div>
    `;
}

function toggleReadStatus(announcementId, isCurrentlyRead) {
    const endpoint = isCurrentlyRead
        ? `/api/announcements/${announcementId}/mark-unread`
        : `/api/announcements/${announcementId}/mark-read`;

    const button = document.querySelector(
        `[data-announcement-id="${announcementId}"]`
    );
    if (!button) return;

    const originalText = button.innerHTML;

    // Show loading state
    button.disabled = true;
    button.innerHTML =
        '<i class="bx bx-loader-alt bx-spin me-1"></i>Loading...';

    const csrfToken =
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content") || "";

    fetch(endpoint, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
            "Content-Type": "application/json",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Update the announcement item directly without reloading
                updateAnnouncementStatus(announcementId, !isCurrentlyRead);

                // Update button state explicitly
                updateButtonState(button, announcementId, !isCurrentlyRead);

                // Show success notification
                showNotification(data.message, true);
            } else {
                // Restore button state on error
                button.disabled = false;
                button.innerHTML = originalText;
                showNotification("Gagal mengubah status pengumuman", false);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            // Restore button state on error
            button.disabled = false;
            button.innerHTML = originalText;
            showNotification(
                "Terjadi kesalahan saat mengubah status pengumuman",
                false
            );
        });
}

function updateButtonState(button, announcementId, isRead) {
    if (!button) return;

    // Re-enable button
    button.disabled = false;

    if (isRead) {
        // Mark as read state
        button.classList.remove("btn-outline-primary");
        button.classList.add("btn-outline-secondary");
        button.innerHTML = '<i class="bx bx-undo me-1"></i>Belum Dibaca';
        button.setAttribute(
            "onclick",
            `toggleReadStatus(${announcementId}, true)`
        );
    } else {
        // Mark as unread state
        button.classList.remove("btn-outline-secondary");
        button.classList.add("btn-outline-primary");
        button.innerHTML = '<i class="bx bx-check me-1"></i>Telah Dibaca';
        button.setAttribute(
            "onclick",
            `toggleReadStatus(${announcementId}, false)`
        );
    }
}

function updateAnnouncementStatus(announcementId, isRead) {
    const announcementItem = document.querySelector(
        `[data-id="${announcementId}"]`
    );
    if (!announcementItem) return;

    // Update data attribute
    announcementItem.setAttribute("data-read", isRead);

    // Get the card element
    const card = announcementItem.querySelector(".card");
    const header = announcementItem.querySelector(".card-header");
    const title = announcementItem.querySelector(".card-title");
    const readButton = announcementItem.querySelector(".mark-read-btn");
    const viewButton = announcementItem.querySelector(
        "button:not(.mark-read-btn)"
    );

    if (isRead) {
        // Mark as read - change to gray
        if (card) {
            card.classList.remove(
                "border-danger",
                "border-success",
                "border-primary",
                "border-info"
            );
            card.classList.add("border-secondary", "opacity-75");
        }

        if (header) {
            header.classList.remove(
                "bg-danger",
                "bg-success",
                "bg-primary",
                "bg-info"
            );
            header.classList.add("bg-secondary");
        }

        if (title) {
            title.classList.add("text-muted");
        }

        if (readButton) {
            readButton.classList.remove("btn-outline-primary");
            readButton.classList.add("btn-outline-secondary");
            readButton.innerHTML =
                '<i class="bx bx-undo me-1"></i>Belum Dibaca';
            // Update onclick attribute to reflect new state
            readButton.setAttribute(
                "onclick",
                `toggleReadStatus(${announcementId}, true)`
            );
        }

        if (viewButton) {
            viewButton.classList.remove(
                "btn-outline-danger",
                "btn-outline-success",
                "btn-outline-primary",
                "btn-outline-info"
            );
            viewButton.classList.add("btn-outline-secondary");
        }

        // Move to bottom with animation
        announcementItem.style.transition = "all 0.5s ease";
        announcementItem.style.transform = "translateY(20px)";

        setTimeout(() => {
            // Move the element to the end
            const container = announcementItem.parentElement;
            if (container) {
                container.appendChild(announcementItem);
                announcementItem.style.transform = "translateY(0)";
            }
        }, 250);
    } else {
        // Mark as unread - restore original colors
        if (card) {
            card.classList.remove("border-secondary", "opacity-75");

            // Determine original color based on category
            const category =
                announcementItem.getAttribute("data-category") || "umum";
            const colorMap = {
                penting: "danger",
                akademik: "success",
                kegiatan: "primary",
                umum: "info",
            };
            const color = colorMap[category] || "info";

            card.classList.add(`border-${color}`);

            if (header) {
                header.classList.remove("bg-secondary");
                header.classList.add(`bg-${color}`);
            }

            if (title) {
                title.classList.remove("text-muted");
            }

            if (readButton) {
                readButton.classList.remove("btn-outline-secondary");
                readButton.classList.add("btn-outline-primary");
                readButton.innerHTML =
                    '<i class="bx bx-check me-1"></i>Telah Dibaca';
                // Update onclick attribute to reflect new state
                readButton.setAttribute(
                    "onclick",
                    `toggleReadStatus(${announcementId}, false)`
                );
            }

            if (viewButton) {
                viewButton.classList.remove("btn-outline-secondary");
                viewButton.classList.add(`btn-outline-${color}`);
            }

            // Move to top with animation
            announcementItem.style.transition = "all 0.5s ease";
            announcementItem.style.transform = "translateY(-20px)";

            setTimeout(() => {
                // Move the element to the beginning
                const container = announcementItem.parentElement;
                if (container) {
                    const firstUnread = container.querySelector(
                        '[data-read="false"]'
                    );
                    if (firstUnread) {
                        container.insertBefore(announcementItem, firstUnread);
                    } else {
                        container.insertBefore(
                            announcementItem,
                            container.firstChild
                        );
                    }
                    announcementItem.style.transform = "translateY(0)";
                }
            }, 250);
        }
    }
}

function toggleReadStatusFromModal() {
    const modalMarkReadBtn = document.getElementById("modalMarkReadBtn");
    if (!modalMarkReadBtn) return;

    const announcementId = modalMarkReadBtn.getAttribute(
        "data-announcement-id"
    );
    const isCurrentlyRead =
        modalMarkReadBtn.getAttribute("data-is-read") === "true";

    if (!announcementId) {
        showNotification("ID pengumuman tidak ditemukan", false);
        return;
    }

    // Call the existing toggleReadStatus function
    toggleReadStatus(announcementId, isCurrentlyRead);

    // Close modal after successful toggle
    const modalEl = document.getElementById("readMoreModal");
    if (modalEl) {
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) {
            modal.hide();
        }
    }
}

function showNotification(message, isSuccess) {
    const alertClass = isSuccess ? "alert-success" : "alert-danger";
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    document.body.insertAdjacentHTML("beforeend", alertHtml);

    setTimeout(() => {
        const alert = document.querySelector(".alert");
        if (alert) {
            alert.remove();
        }
    }, 3000);
}

// Expose functions to global scope for onclick handlers
window.toggleReadStatus = toggleReadStatus;
window.viewAnnouncement = viewAnnouncement;
window.toggleReadStatusFromModal = toggleReadStatusFromModal;
