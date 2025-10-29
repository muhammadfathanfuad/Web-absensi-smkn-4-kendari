@extends('layouts.vertical-guru', ['subtitle' => 'Pengumuman'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Guru', 'subtitle' => 'Pengumuman'])

    {{-- Pengumuman Terbaru --}}
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-md bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class='bx bx-news fs-32 text-primary'></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="card-title mb-1">
                                Pengumuman Terbaru
                            </h4>
                            <p class="text-muted mb-0">Informasi penting untuk guru</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="announcementsContainer">
                        <!-- Announcements will be loaded here -->
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2">Memuat pengumuman...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <iconify-icon icon="solar:clock-circle-outline" class="fs-20 me-2"></iconify-icon>
                        Pengumuman Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline" id="timelineContainer">
                        <!-- Timeline will be loaded here -->
                        <div class="text-center py-2">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #dee2e6;
}

.timeline-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 5px;
    color: #495057;
}

.timeline-text {
    font-size: 13px;
    color: #6c757d;
    margin-bottom: 5px;
}

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
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadAnnouncements();
    
    // Auto-refresh announcements every 30 seconds
    setInterval(function() {
        checkForUpdates();
    }, 30000);
});

// Check for updates without full reload
function checkForUpdates() {
    fetch('/api/announcements/teachers', {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Check if data has changed by comparing with stored data
            const currentData = JSON.stringify(data.data);
            const storedData = localStorage.getItem('announcements_teachers');
            
            if (currentData !== storedData) {
                // Data has changed, update the display
                localStorage.setItem('announcements_teachers', currentData);
                renderAnnouncements(data.data);
                renderTimeline(data.data);
                
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
    console.log('Loading announcements for teachers...');
    
    fetch('/api/announcements/teachers', {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                renderAnnouncements(data.data);
                renderTimeline(data.data);
            } else {
                console.error('API returned success: false');
                showNoAnnouncements();
            }
        })
        .catch(error => {
            console.error('Error loading announcements:', error);
            let errorMessage = 'Gagal memuat pengumuman: ' + error.message;
            
            // Add more specific error messages
            if (error.message.includes('401')) {
                errorMessage = 'Anda tidak memiliki akses untuk melihat pengumuman. Silakan login ulang.';
            } else if (error.message.includes('403')) {
                errorMessage = 'Akses ditolak. Pastikan Anda memiliki izin yang sesuai.';
            } else if (error.message.includes('404')) {
                errorMessage = 'Endpoint pengumuman tidak ditemukan. Silakan hubungi administrator.';
            } else if (error.message.includes('500')) {
                errorMessage = 'Terjadi kesalahan server. Silakan coba lagi nanti.';
            }
            
            showError(errorMessage);
        });
}

function renderAnnouncements(announcements) {
    const container = document.getElementById('announcementsContainer');
    
    if (announcements.length === 0) {
        showNoAnnouncements();
        return;
    }

    const announcementsHtml = announcements.map(announcement => {
        const categoryColor = getCategoryColor(announcement.category);
        const priorityIcon = getPriorityIcon(announcement.priority);
        const createdDate = new Date(announcement.created_at).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
        
        const isRead = announcement.is_read_by_current_user;
        const cardClass = isRead ? 'border-secondary' : `border-${categoryColor}`;
        const textClass = isRead ? 'text-muted' : `text-${categoryColor}`;
        const iconClass = isRead ? 'text-muted' : `text-${categoryColor}`;
        const bgClass = isRead ? 'bg-secondary bg-opacity-10' : `bg-${categoryColor} bg-opacity-10`;

        return `
            <div class="row mb-3 announcement-item" data-id="${announcement.id}" data-read="${isRead}" data-category="${announcement.category}">
                <div class="col-12">
                    <div class="card border ${cardClass} ${isRead ? 'opacity-75' : ''}">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm ${bgClass} rounded-circle d-flex align-items-center justify-content-center">
                                        <i class='bx ${priorityIcon} fs-20 ${iconClass}'></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 ${textClass}">${announcement.title}</h6>
                                            <p class="text-muted mb-2">${announcement.content}</p>
                                            <div class="d-flex align-items-center">
                                                <small class="text-muted me-3">
                                                    <iconify-icon icon="solar:calendar-outline" class="fs-14 me-1"></iconify-icon>
                                                    ${createdDate}
                                                </small>
                                                <small class="text-muted">
                                                    <iconify-icon icon="solar:user-outline" class="fs-14 me-1"></iconify-icon>
                                                    ${announcement.creator ? announcement.creator.full_name : 'Admin'}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 ms-2">
                                            <button class="btn btn-sm ${isRead ? 'btn-outline-secondary' : 'btn-outline-primary'} mark-read-btn" 
                                                    onclick="toggleReadStatus(${announcement.id}, ${isRead})"
                                                    data-announcement-id="${announcement.id}">
                                                <i class="bx ${isRead ? 'bx-undo' : 'bx-check'} me-1"></i>
                                                ${isRead ? 'Belum Dibaca' : 'Telah Dibaca'}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');

    container.innerHTML = announcementsHtml;
}

function renderTimeline(announcements) {
    const timelineContainer = document.getElementById('timelineContainer');
    
    if (announcements.length === 0) {
        timelineContainer.innerHTML = `
            <div class="text-center py-2">
                <div class="text-muted">
                    <small>Belum ada pengumuman</small>
                </div>
            </div>
        `;
        return;
    }

    // Take only the first 3 announcements for timeline
    const timelineAnnouncements = announcements.slice(0, 3);
    
    const timelineHtml = timelineAnnouncements.map(announcement => {
        const categoryColor = getCategoryColor(announcement.category);
        const createdDate = new Date(announcement.created_at);
        const timeAgo = getTimeAgo(createdDate);
        const isRead = announcement.is_read_by_current_user;
        const markerClass = isRead ? 'bg-secondary' : `bg-${categoryColor}`;
        const titleClass = isRead ? 'text-muted' : '';

        return `
            <div class="timeline-item">
                <div class="timeline-marker ${markerClass}"></div>
                <div class="timeline-content">
                    <h6 class="timeline-title ${titleClass}">${announcement.title}</h6>
                    <p class="timeline-text">${announcement.content.substring(0, 50)}${announcement.content.length > 50 ? '...' : ''}</p>
                    <small class="text-muted">${timeAgo}</small>
                </div>
            </div>
        `;
    }).join('');

    timelineContainer.innerHTML = timelineHtml;
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

function getCategoryColor(category) {
    const colors = {
        'penting': 'danger',
        'akademik': 'success',
        'kegiatan': 'primary',
        'umum': 'info'
    };
    return colors[category] || 'info';
}

function getPriorityIcon(priority) {
    const icons = {
        'urgent': 'bx-error-circle',
        'high': 'bx-error',
        'normal': 'bx-info-circle'
    };
    return icons[priority] || 'bx-info-circle';
}

function showNoAnnouncements() {
    const container = document.getElementById('announcementsContainer');
    container.innerHTML = `
        <div class="text-center py-4">
            <div class="text-muted">
                <iconify-icon icon="solar:megaphone-outline" class="fs-48 d-block mb-2"></iconify-icon>
                Belum ada pengumuman untuk guru.
            </div>
        </div>
    `;
}

function showError(message) {
    const container = document.getElementById('announcementsContainer');
    container.innerHTML = `
        <div class="text-center py-4">
            <div class="alert alert-danger">
                <iconify-icon icon="solar:danger-circle-outline" class="fs-48 d-block mb-2"></iconify-icon>
                <h6>Error</h6>
                <p class="mb-0">${message}</p>
                <button class="btn btn-sm btn-outline-danger mt-2" onclick="loadAnnouncements()">
                    <i class="bx bx-refresh me-1"></i> Coba Lagi
                </button>
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
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    fetch(endpoint, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.json();
    })
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
            showNotification(data.message || 'Gagal mengubah status pengumuman', false);
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
    const title = announcementItem.querySelector('.card-title, h6');
    const icon = announcementItem.querySelector('.avatar-sm i');
    const button = announcementItem.querySelector('.mark-read-btn');
    
    if (isRead) {
        // Mark as read - change to gray
        card.classList.remove('border-danger', 'border-success', 'border-primary', 'border-info');
        card.classList.add('border-secondary', 'opacity-75');
        
        if (title) {
            title.classList.remove('text-danger', 'text-success', 'text-primary', 'text-info');
            title.classList.add('text-muted');
        }
        
        if (icon) {
            icon.classList.remove('text-danger', 'text-success', 'text-primary', 'text-info');
            icon.classList.add('text-muted');
        }
        
        if (button) {
            button.classList.remove('btn-outline-primary');
            button.classList.add('btn-outline-secondary');
            button.innerHTML = '<i class="bx bx-undo me-1"></i>Belum Dibaca';
            // Update onclick attribute to reflect new state
            button.setAttribute('onclick', `toggleReadStatus(${announcementId}, true)`);
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
        
        if (title) {
            title.classList.remove('text-muted');
            title.classList.add(`text-${color}`);
        }
        
        if (icon) {
            icon.classList.remove('text-muted');
            icon.classList.add(`text-${color}`);
        }
        
        if (button) {
            button.classList.remove('btn-outline-secondary');
            button.classList.add('btn-outline-primary');
            button.innerHTML = '<i class="bx bx-check me-1"></i>Telah Dibaca';
            // Update onclick attribute to reflect new state
            button.setAttribute('onclick', `toggleReadStatus(${announcementId}, false)`);
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
