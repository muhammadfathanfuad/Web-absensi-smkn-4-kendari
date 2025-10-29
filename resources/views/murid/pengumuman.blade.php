@extends('layouts.vertical-murid')

@section('title', 'Pengumuman')

@section('content')
    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Pengumuman</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Siswa</li>
                        <li class="breadcrumb-item active">Pengumuman</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Pengumuman Terbaru --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-megaphone me-2"></i>
                        Pengumuman Terbaru
                    </h4>
                </div>
                <div class="card-body">
                    {{-- Filter --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="categoryFilter" class="form-label">Kategori</label>
                            <select class="form-select" id="categoryFilter">
                                <option value="">Semua Kategori</option>
                                <option value="umum">Umum</option>
                                <option value="akademik">Akademik</option>
                                <option value="kegiatan">Kegiatan</option>
                                <option value="penting">Penting</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="dateFilter" class="form-label">Tanggal</label>
                            <select class="form-select" id="dateFilter">
                                <option value="">Semua Tanggal</option>
                                <option value="today">Hari Ini</option>
                                <option value="week">Minggu Ini</option>
                                <option value="month">Bulan Ini</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="searchInput" class="form-label">Cari</label>
                            <input type="text" class="form-control" id="searchInput" placeholder="Cari pengumuman...">
                        </div>
                    </div>

                    {{-- List Pengumuman --}}
                    <div class="row" id="pengumumanList">
                        <!-- Announcements will be loaded here -->
                        <div class="col-12 text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2">Memuat pengumuman...</p>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Baca Selengkapnya --}}
    <div class="modal fade" id="readMoreModal" tabindex="-1" aria-labelledby="readMoreModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="readMoreModalLabel">
                        <i class="bx bx-news me-2"></i>
                        Detail Pengumuman
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" id="modalIcon">
                                        <i class="bx bx-news fs-20 text-primary" id="modalIconClass"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1" id="modalTitle">Judul Pengumuman</h6>
                                    <div class="d-flex align-items-center text-muted">
                                        <small class="me-3">
                                            <i class="bx bx-calendar me-1"></i>
                                            <span id="modalDate">Tanggal</span>
                                        </small>
                                        <small class="me-3">
                                            <i class="bx bx-user me-1"></i>
                                            <span id="modalAuthor">Penulis</span>
                                        </small>
                                        <small>
                                            <i class="bx bx-tag me-1"></i>
                                            <span id="modalCategory">Kategori</span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="border-top pt-3">
                                <h6 class="text-muted mb-2">Isi Pengumuman:</h6>
                                <div class="bg-light p-3 rounded" id="modalContent">
                                    <!-- Content will be loaded here -->
                                </div>
                            </div>
                            
                            <div class="border-top pt-3 mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">
                                            <i class="bx bx-time me-1"></i>
                                            Dibuat: <span id="modalCreatedAt">-</span>
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            <i class="bx bx-expire me-1"></i>
                                            Berlaku hingga: <span id="modalExpiresAt">-</span>
                                        </small>
                                    </div>
                                    <div>
                                        <span class="badge" id="modalPriority">Prioritas</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i>Tutup
                    </button>
                    <button type="button" class="btn btn-primary" id="modalMarkReadBtn" onclick="toggleReadStatusFromModal()">
                        <i class="bx bx-check me-1"></i>Telah Dibaca
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
/* Animation for announcement items */
.announcement-item {
    transition: all 0.3s ease;
}

.announcement-item.read {
    opacity: 0.75;
    transform: translateY(5px);
}

/* Smooth transition for read status changes */
.mark-read-btn {
    transition: all 0.2s ease;
}

.mark-read-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Loading animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.bx-spin {
    animation: spin 1s linear infinite;
}

/* Gap utility for buttons */
.gap-2 {
    gap: 0.5rem;
}

/* Modal styles */
.modal-lg {
    max-width: 800px;
}

.modal-body {
    max-height: 70vh;
    overflow-y: auto;
}

#modalContent {
    white-space: pre-wrap;
    line-height: 1.6;
    font-size: 14px;
}

.modal-header {
    border-bottom: 2px solid #e9ecef;
}

.modal-footer {
    border-top: 2px solid #e9ecef;
}
</style>
@endpush

@push('scripts')
<script>
let allAnnouncements = [];

document.addEventListener('DOMContentLoaded', function() {
    loadAnnouncements();
    
    const categoryFilter = document.getElementById('categoryFilter');
    const dateFilter = document.getElementById('dateFilter');
    const searchInput = document.getElementById('searchInput');

    categoryFilter.addEventListener('change', filterPengumuman);
    dateFilter.addEventListener('change', filterPengumuman);
    searchInput.addEventListener('input', filterPengumuman);
    
    // Auto-refresh announcements every 30 seconds
    setInterval(function() {
        checkForUpdates();
    }, 30000);
});

// Check for updates without full reload
function checkForUpdates() {
    fetch('/api/announcements/students')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Check if data has changed by comparing with stored data
                const currentData = JSON.stringify(data.data);
                const storedData = localStorage.getItem('announcements_students');
                
                if (currentData !== storedData) {
                    // Data has changed, update the display
                    localStorage.setItem('announcements_students', currentData);
                    allAnnouncements = data.data;
                    renderAnnouncements(data.data);
                    
                    // Show subtle notification that data was updated
                    showUpdateNotification();
                }
            }
        })
        .catch(error => {
            console.error('Error checking for updates:', error);
        });
}

// Show subtle update notification
function showUpdateNotification() {
    const notification = document.createElement('div');
    notification.className = 'alert alert-info alert-dismissible fade show position-fixed';
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px; font-size: 12px;';
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
    fetch('/api/announcements/students')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allAnnouncements = data.data;
                renderAnnouncements(allAnnouncements);
            } else {
                showNoAnnouncements();
            }
        })
        .catch(error => {
            console.error('Error loading announcements:', error);
            showNoAnnouncements();
        });
}

function renderAnnouncements(announcements) {
    const container = document.getElementById('pengumumanList');
    
    if (announcements.length === 0) {
        showNoAnnouncements();
        return;
    }

    const announcementsHtml = announcements.map(announcement => {
        const categoryColor = getCategoryColor(announcement.category);
        const categoryIcon = getCategoryIcon(announcement.category);
        const createdDate = new Date(announcement.created_at);
        const timeAgo = getTimeAgo(createdDate);
        const isRead = announcement.is_read_by_current_user;
        
        const cardClass = isRead ? 'border-secondary' : `border-${categoryColor}`;
        const headerClass = isRead ? 'bg-secondary' : `bg-${categoryColor}`;
        const titleClass = isRead ? 'text-muted' : '';
        const buttonClass = isRead ? 'btn-outline-secondary' : `btn-outline-${categoryColor}`;

        return `
            <div class="col-md-6 mb-3 announcement-item" data-category="${announcement.category}" data-date="${getDateCategory(createdDate)}" data-id="${announcement.id}" data-read="${isRead}">
                <div class="card ${cardClass} ${isRead ? 'opacity-75' : ''}">
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
                        <h5 class="card-title ${titleClass}">${announcement.title}</h5>
                        <p class="card-text">${announcement.content}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bx bx-user me-1"></i>
                                ${announcement.creator ? announcement.creator.full_name : 'Admin'}
                            </small>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm ${isRead ? 'btn-outline-secondary' : 'btn-outline-primary'} mark-read-btn" 
                                        onclick="toggleReadStatus(${announcement.id}, ${isRead})"
                                        data-announcement-id="${announcement.id}">
                                    <i class="bx ${isRead ? 'bx-undo' : 'bx-check'} me-1"></i>
                                    ${isRead ? 'Belum Dibaca' : 'Telah Dibaca'}
                                </button>
                                <button class="btn btn-sm ${buttonClass}" onclick="viewAnnouncement(${announcement.id})">Baca Selengkapnya</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');

    container.innerHTML = announcementsHtml;
}

function getCategoryColor(category) {
    const colors = {
        'penting': 'danger',
        'akademik': 'success',
        'kegiatan': 'primary',
        'umum': 'info'
    };
    return colors[category] || 'info';
}

function getCategoryIcon(category) {
    const icons = {
        'penting': 'bx-error-circle',
        'akademik': 'bx-book',
        'kegiatan': 'bx-calendar-event',
        'umum': 'bx-info-circle'
    };
    return icons[category] || 'bx-info-circle';
}

function getTimeAgo(date) {
    const now = new Date();
    const diffInHours = Math.floor((now - date) / (1000 * 60 * 60));
    
    if (diffInHours < 1) return 'Baru saja';
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
    
    if (diffInHours < 24) return 'today';
    if (diffInHours < 168) return 'week'; // 7 days
    return 'month';
}

function filterPengumuman() {
    const categoryFilter = document.getElementById('categoryFilter');
    const dateFilter = document.getElementById('dateFilter');
    const searchInput = document.getElementById('searchInput');
    const pengumumanList = document.getElementById('pengumumanList');
    const cards = pengumumanList.querySelectorAll('.col-md-6');

        const selectedCategory = categoryFilter.value;
        const selectedDate = dateFilter.value;
        const searchTerm = searchInput.value.toLowerCase();

        cards.forEach(card => {
            const category = card.getAttribute('data-category');
            const date = card.getAttribute('data-date');
            const title = card.querySelector('.card-title').textContent.toLowerCase();
            const content = card.querySelector('.card-text').textContent.toLowerCase();

            const categoryMatch = !selectedCategory || category === selectedCategory;
            const dateMatch = !selectedDate || date === selectedDate;
            const searchMatch = !searchTerm || title.includes(searchTerm) || content.includes(searchTerm);

            if (categoryMatch && dateMatch && searchMatch) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

function viewAnnouncement(id) {
    const announcement = allAnnouncements.find(ann => ann.id === id);
    if (!announcement) {
        showNotification('Pengumuman tidak ditemukan', false);
        return;
    }
    
    // Update modal content
    document.getElementById('modalTitle').textContent = announcement.title;
    document.getElementById('modalContent').textContent = announcement.content;
    document.getElementById('modalAuthor').textContent = announcement.creator ? announcement.creator.full_name : 'Admin';
    document.getElementById('modalCategory').textContent = announcement.category.toUpperCase();
    
    // Format dates
    const createdDate = new Date(announcement.created_at);
    const expiresDate = announcement.expires_at ? new Date(announcement.expires_at) : null;
    
    document.getElementById('modalDate').textContent = createdDate.toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    document.getElementById('modalCreatedAt').textContent = createdDate.toLocaleString('id-ID');
    document.getElementById('modalExpiresAt').textContent = expiresDate ? 
        expiresDate.toLocaleString('id-ID') : 'Tidak ada batas waktu';
    
    // Update priority badge
    const priorityBadge = document.getElementById('modalPriority');
    const priorityColors = {
        'urgent': 'bg-danger',
        'high': 'bg-warning',
        'normal': 'bg-info',
        'low': 'bg-secondary'
    };
    priorityBadge.className = `badge ${priorityColors[announcement.priority] || 'bg-info'}`;
    priorityBadge.textContent = announcement.priority.toUpperCase();
    
    // Update icon and colors based on category
    const categoryColor = getCategoryColor(announcement.category);
    const categoryIcon = getCategoryIcon(announcement.category);
    const modalIcon = document.getElementById('modalIcon');
    const modalIconClass = document.getElementById('modalIconClass');
    
    modalIcon.className = `avatar-sm bg-${categoryColor} bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center`;
    modalIconClass.className = `bx ${categoryIcon} fs-20 text-${categoryColor}`;
    
    // Update mark read button
    const modalMarkReadBtn = document.getElementById('modalMarkReadBtn');
    const isRead = announcement.is_read_by_current_user;
    
    if (isRead) {
        modalMarkReadBtn.className = 'btn btn-outline-secondary';
        modalMarkReadBtn.innerHTML = '<i class="bx bx-undo me-1"></i>Belum Dibaca';
    } else {
        modalMarkReadBtn.className = 'btn btn-primary';
        modalMarkReadBtn.innerHTML = '<i class="bx bx-check me-1"></i>Telah Dibaca';
    }
    
    // Store current announcement ID for modal actions
    modalMarkReadBtn.setAttribute('data-announcement-id', announcement.id);
    modalMarkReadBtn.setAttribute('data-is-read', isRead);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('readMoreModal'));
    modal.show();
}

function showNoAnnouncements() {
    const container = document.getElementById('pengumumanList');
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
    const endpoint = isCurrentlyRead ? 
        `/api/announcements/${announcementId}/mark-unread` : 
        `/api/announcements/${announcementId}/mark-read`;
    
    const button = document.querySelector(`[data-announcement-id="${announcementId}"]`);
    const originalText = button.innerHTML;
    
    // Show loading state
    button.disabled = true;
    button.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Loading...';
    
    fetch(endpoint, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
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
            showNotification('Gagal mengubah status pengumuman', false);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Restore button state on error
        button.disabled = false;
        button.innerHTML = originalText;
        showNotification('Terjadi kesalahan saat mengubah status pengumuman', false);
    });
}

function updateButtonState(button, announcementId, isRead) {
    if (!button) return;
    
    // Re-enable button
    button.disabled = false;
    
    if (isRead) {
        // Mark as read state
        button.classList.remove('btn-outline-primary');
        button.classList.add('btn-outline-secondary');
        button.innerHTML = '<i class="bx bx-undo me-1"></i>Belum Dibaca';
        button.setAttribute('onclick', `toggleReadStatus(${announcementId}, true)`);
    } else {
        // Mark as unread state
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-outline-primary');
        button.innerHTML = '<i class="bx bx-check me-1"></i>Telah Dibaca';
        button.setAttribute('onclick', `toggleReadStatus(${announcementId}, false)`);
    }
}

function updateAnnouncementStatus(announcementId, isRead) {
    const announcementItem = document.querySelector(`[data-id="${announcementId}"]`);
    if (!announcementItem) return;
    
    // Update data attribute
    announcementItem.setAttribute('data-read', isRead);
    
    // Get the card element
    const card = announcementItem.querySelector('.card');
    const header = announcementItem.querySelector('.card-header');
    const title = announcementItem.querySelector('.card-title');
    const button = announcementItem.querySelector('.mark-read-btn');
    const readButton = announcementItem.querySelector('.mark-read-btn');
    const viewButton = announcementItem.querySelector('button:not(.mark-read-btn)');
    
    if (isRead) {
        // Mark as read - change to gray
        card.classList.remove('border-danger', 'border-success', 'border-primary', 'border-info');
        card.classList.add('border-secondary', 'opacity-75');
        
        if (header) {
            header.classList.remove('bg-danger', 'bg-success', 'bg-primary', 'bg-info');
            header.classList.add('bg-secondary');
        }
        
        if (title) {
            title.classList.add('text-muted');
        }
        
        if (readButton) {
            readButton.classList.remove('btn-outline-primary');
            readButton.classList.add('btn-outline-secondary');
            readButton.innerHTML = '<i class="bx bx-undo me-1"></i>Belum Dibaca';
            // Update onclick attribute to reflect new state
            readButton.setAttribute('onclick', `toggleReadStatus(${announcementId}, true)`);
        }
        
        if (viewButton) {
            viewButton.classList.remove('btn-outline-danger', 'btn-outline-success', 'btn-outline-primary', 'btn-outline-info');
            viewButton.classList.add('btn-outline-secondary');
        }
        
        // Move to bottom with animation
        announcementItem.style.transition = 'all 0.5s ease';
        announcementItem.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            // Move the element to the end
            const container = announcementItem.parentElement;
            container.appendChild(announcementItem);
            announcementItem.style.transform = 'translateY(0)';
        }, 250);
        
    } else {
        // Mark as unread - restore original colors
        card.classList.remove('border-secondary', 'opacity-75');
        
        // Determine original color based on category
        const category = announcementItem.getAttribute('data-category') || 'umum';
        const colorMap = {
            'penting': 'danger',
            'akademik': 'success', 
            'kegiatan': 'primary',
            'umum': 'info'
        };
        const color = colorMap[category] || 'info';
        
        card.classList.add(`border-${color}`);
        
        if (header) {
            header.classList.remove('bg-secondary');
            header.classList.add(`bg-${color}`);
        }
        
        if (title) {
            title.classList.remove('text-muted');
        }
        
        if (readButton) {
            readButton.classList.remove('btn-outline-secondary');
            readButton.classList.add('btn-outline-primary');
            readButton.innerHTML = '<i class="bx bx-check me-1"></i>Telah Dibaca';
            // Update onclick attribute to reflect new state
            readButton.setAttribute('onclick', `toggleReadStatus(${announcementId}, false)`);
        }
        
        if (viewButton) {
            viewButton.classList.remove('btn-outline-secondary');
            viewButton.classList.add(`btn-outline-${color}`);
        }
        
        // Move to top with animation
        announcementItem.style.transition = 'all 0.5s ease';
        announcementItem.style.transform = 'translateY(-20px)';
        
        setTimeout(() => {
            // Move the element to the beginning
            const container = announcementItem.parentElement;
            const firstUnread = container.querySelector('[data-read="false"]');
            if (firstUnread) {
                container.insertBefore(announcementItem, firstUnread);
            } else {
                container.insertBefore(announcementItem, container.firstChild);
            }
            announcementItem.style.transform = 'translateY(0)';
        }, 250);
    }
}

function toggleReadStatusFromModal() {
    const modalMarkReadBtn = document.getElementById('modalMarkReadBtn');
    const announcementId = modalMarkReadBtn.getAttribute('data-announcement-id');
    const isCurrentlyRead = modalMarkReadBtn.getAttribute('data-is-read') === 'true';
    
    if (!announcementId) {
        showNotification('ID pengumuman tidak ditemukan', false);
        return;
    }
    
    // Call the existing toggleReadStatus function
    toggleReadStatus(announcementId, isCurrentlyRead);
    
    // Close modal after successful toggle
    const modal = bootstrap.Modal.getInstance(document.getElementById('readMoreModal'));
    if (modal) {
        modal.hide();
    }
}

function showNotification(message, isSuccess) {
    const alertClass = isSuccess ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', alertHtml);
    
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 3000);
}
</script>
@endpush
