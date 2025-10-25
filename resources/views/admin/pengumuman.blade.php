@extends('layouts.vertical-admin', ['subtitle' => 'Pengumuman'])

@section('content')

@include('layouts.partials.page-title', ['title' => 'Pengumuman', 'subtitle' => 'Admin'])

{{-- Create Announcement Button --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Kelola Pengumuman</h5>
                <p class="text-muted mb-0">Buat dan kelola pengumuman untuk guru dan siswa</p>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAnnouncementModal">
                <i class="bx bx-plus me-1"></i> Buat Pengumuman
            </button>
        </div>
    </div>
</div>

{{-- Announcements List --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Daftar Pengumuman</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Target</th>
                                <th>Status</th>
                                <th>Tanggal Dibuat</th>
                                <th>Tanggal Berakhir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="announcementsTableBody">
                            <!-- Data will be loaded via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Create Announcement Modal --}}
<div class="modal fade" id="createAnnouncementModal" tabindex="-1" aria-labelledby="createAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAnnouncementModalLabel">Buat Pengumuman Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createAnnouncementForm" method="POST" action="{{ route('any', 'admin.pengumuman.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="title" class="form-label">Judul Pengumuman <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="content" class="form-label">Isi Pengumuman <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="target" class="form-label">Target Pengumuman <span class="text-danger">*</span></label>
                            <select class="form-select" id="target" name="target" required>
                                <option value="">Pilih Target</option>
                                <option value="all">Semua (Guru & Siswa)</option>
                                <option value="teachers">Guru Saja</option>
                                <option value="students">Siswa Saja</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label">Prioritas</label>
                            <select class="form-select" id="priority" name="priority">
                                <option value="normal">Normal</option>
                                <option value="high">Tinggi</option>
                                <option value="urgent">Mendesak</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Kategori</label>
                            <select class="form-select" id="category" name="category">
                                <option value="umum">Umum</option>
                                <option value="akademik">Akademik</option>
                                <option value="kegiatan">Kegiatan</option>
                                <option value="penting">Penting</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="expires_at" class="form-label">Tanggal Berakhir</label>
                            <input type="datetime-local" class="form-control" id="expires_at" name="expires_at">
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active">
                                    Aktifkan pengumuman
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Buat Pengumuman</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- View Announcement Modal --}}
<div class="modal fade" id="viewAnnouncementModal" tabindex="-1" aria-labelledby="viewAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAnnouncementModalLabel">Detail Pengumuman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewAnnouncementContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Announcement Modal --}}
<div class="modal fade" id="editAnnouncementModal" tabindex="-1" aria-labelledby="editAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAnnouncementModalLabel">Edit Pengumuman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAnnouncementForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editAnnouncementContent">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Confirmation Modal --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">
                    <i class="bx bx-info-circle me-2"></i>
                    Konfirmasi Aksi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bx bx-question-mark fs-20 text-warning"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1" id="confirmTitle">Konfirmasi Aksi</h6>
                        <p class="text-muted mb-0" id="confirmMessage">Apakah Anda yakin ingin melakukan aksi ini?</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-warning" id="confirmActionBtn">
                    <i class="bx bx-check me-1"></i>Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let announcements = [];

    // Load announcements on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadAnnouncements();
    });

    // Load announcements from server
    function loadAnnouncements() {
        fetch('/admin/pengumuman/data')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    announcements = data.data;
                    renderAnnouncementsTable();
                }
            })
            .catch(error => {
                console.error('Error loading announcements:', error);
            });
    }

    // Render announcements table
    function renderAnnouncementsTable() {
        const tbody = document.getElementById('announcementsTableBody');
        
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

        tbody.innerHTML = announcements.map((announcement, index) => `
            <tr>
                <td>${index + 1}</td>
                <td>
                    <div>
                        <h6 class="mb-0">${announcement.title}</h6>
                        <small class="text-muted">${announcement.content.substring(0, 50)}${announcement.content.length > 50 ? '...' : ''}</small>
                    </div>
                </td>
                <td>
                    ${getTargetBadge(announcement.target)}
                </td>
                <td>
                    ${getStatusBadge(announcement.is_active)}
                </td>
                <td>${formatDate(announcement.created_at)}</td>
                <td>${announcement.expires_at ? formatDate(announcement.expires_at) : '-'}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-horizontal-rounded"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#" onclick="viewAnnouncement(${announcement.id})">
                                    <i class="bx bx-show me-2"></i> Lihat
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="editAnnouncement(${announcement.id})">
                                    <i class="bx bx-edit me-2"></i> Edit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="toggleStatus(${announcement.id}, ${!announcement.is_active})">
                                    <i class="bx bx-${announcement.is_active ? 'x' : 'check'}-circle me-2"></i> 
                                    ${announcement.is_active ? 'Nonaktifkan' : 'Aktifkan'}
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="deleteAnnouncement(${announcement.id})">
                                    <i class="bx bx-trash me-2"></i> Hapus
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    // Helper functions
    function getTargetBadge(target) {
        const badges = {
            'all': '<span class="badge bg-primary-subtle text-primary py-1 px-2"><i class="bx bx-group me-1"></i> Semua</span>',
            'teachers': '<span class="badge bg-info-subtle text-info py-1 px-2"><i class="bx bx-user me-1"></i> Guru</span>',
            'students': '<span class="badge bg-success-subtle text-success py-1 px-2"><i class="bx bx-user-circle me-1"></i> Siswa</span>'
        };
        return badges[target] || badges['all'];
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
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // View announcement function
    function viewAnnouncement(id) {
        const announcement = announcements.find(a => a.id === id);
        if (!announcement) return;

        const content = `
            <div class="announcement-detail">
                <h6>${announcement.title}</h6>
                <p>${announcement.content}</p>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Target:</strong> ${announcement.target_display || 'Semua'}
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
                        <strong>Berakhir:</strong> ${announcement.expires_at ? formatDate(announcement.expires_at) : 'Tidak ada'}
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('viewAnnouncementContent').innerHTML = content;
        new bootstrap.Modal(document.getElementById('viewAnnouncementModal')).show();
    }

    // Edit announcement function
    function editAnnouncement(id) {
        const announcement = announcements.find(a => a.id === id);
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
                        <option value="all" ${announcement.target === 'all' ? 'selected' : ''}>Semua (Guru & Siswa)</option>
                        <option value="teachers" ${announcement.target === 'teachers' ? 'selected' : ''}>Guru Saja</option>
                        <option value="students" ${announcement.target === 'students' ? 'selected' : ''}>Siswa Saja</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="edit_priority" class="form-label">Prioritas</label>
                    <select class="form-select" id="edit_priority" name="priority">
                        <option value="normal" ${announcement.priority === 'normal' ? 'selected' : ''}>Normal</option>
                        <option value="high" ${announcement.priority === 'high' ? 'selected' : ''}>Tinggi</option>
                        <option value="urgent" ${announcement.priority === 'urgent' ? 'selected' : ''}>Mendesak</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="edit_category" class="form-label">Kategori</label>
                    <select class="form-select" id="edit_category" name="category">
                        <option value="umum" ${announcement.category === 'umum' ? 'selected' : ''}>Umum</option>
                        <option value="akademik" ${announcement.category === 'akademik' ? 'selected' : ''}>Akademik</option>
                        <option value="kegiatan" ${announcement.category === 'kegiatan' ? 'selected' : ''}>Kegiatan</option>
                        <option value="penting" ${announcement.category === 'penting' ? 'selected' : ''}>Penting</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="edit_expires_at" class="form-label">Tanggal Berakhir</label>
                    <input type="datetime-local" class="form-control" id="edit_expires_at" name="expires_at" value="${announcement.expires_at ? new Date(announcement.expires_at).toISOString().slice(0, 16) : ''}">
                </div>
                <div class="col-12 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active" value="1" ${announcement.is_active ? 'checked' : ''}>
                        <label class="form-check-label" for="edit_is_active">
                            Aktifkan pengumuman
                        </label>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('editAnnouncementContent').innerHTML = content;
        document.getElementById('editAnnouncementForm').action = `/admin/pengumuman/${id}`;
        new bootstrap.Modal(document.getElementById('editAnnouncementModal')).show();
    }

    // Toggle status function
    function toggleStatus(id, status) {
        const announcement = announcements.find(ann => ann.id === id);
        if (!announcement) return;
        
        const action = status ? 'mengaktifkan' : 'menonaktifkan';
        const title = status ? 'Aktifkan Pengumuman' : 'Nonaktifkan Pengumuman';
        const message = `Apakah Anda yakin ingin ${action} pengumuman "${announcement.title}"?`;
        
        // Update modal content
        document.getElementById('confirmTitle').textContent = title;
        document.getElementById('confirmMessage').textContent = message;
        
        // Update button color based on action
        const confirmBtn = document.getElementById('confirmActionBtn');
        if (status) {
            confirmBtn.className = 'btn btn-success';
            confirmBtn.innerHTML = '<i class="bx bx-check me-1"></i>Ya, Aktifkan';
        } else {
            confirmBtn.className = 'btn btn-warning';
            confirmBtn.innerHTML = '<i class="bx bx-x me-1"></i>Ya, Nonaktifkan';
        }
        
        // Remove existing event listeners
        const newConfirmBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
        
        // Add new event listener
        newConfirmBtn.addEventListener('click', function() {
            performToggleStatus(id, status);
        });
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        modal.show();
    }
    
    // Perform the actual toggle status
    function performToggleStatus(id, status) {
        console.log('Sending toggle request:', {
            id: id,
            status: status,
            statusType: typeof status
        });
        
        fetch(`/admin/pengumuman/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ is_active: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
                modal.hide();
                
                // Reload announcements and show notification
                loadAnnouncements();
                showNotification('Status pengumuman berhasil diubah', true);
            } else {
                showNotification('Gagal mengubah status pengumuman', false);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat mengubah status pengumuman', false);
        });
    }

    // Delete announcement function
    function deleteAnnouncement(id) {
        if (confirm('Apakah Anda yakin ingin menghapus pengumuman ini? Tindakan ini tidak dapat dibatalkan.')) {
            fetch(`/admin/pengumuman/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadAnnouncements();
                    showNotification('Pengumuman berhasil dihapus', true);
                } else {
                    showNotification('Gagal menghapus pengumuman', false);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat menghapus pengumuman', false);
            });
        }
    }

    // Form submission handlers
    document.getElementById('createAnnouncementForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('/admin/pengumuman', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('createAnnouncementModal')).hide();
                this.reset();
                loadAnnouncements();
                showNotification('Pengumuman berhasil dibuat', true);
            } else {
                showNotification('Gagal membuat pengumuman', false);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat membuat pengumuman', false);
        });
    });

    document.getElementById('editAnnouncementForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const announcementId = this.action.split('/').pop();
        
        fetch(`/admin/pengumuman/${announcementId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-HTTP-Method-Override': 'PUT'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('editAnnouncementModal')).hide();
                loadAnnouncements();
                showNotification('Pengumuman berhasil diperbarui', true);
            } else {
                showNotification('Gagal memperbarui pengumuman', false);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat memperbarui pengumuman', false);
        });
    });

    // Notification function
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
        }, 5000);
    }
</script>
@endsection
