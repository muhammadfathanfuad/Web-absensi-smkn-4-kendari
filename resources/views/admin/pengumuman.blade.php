@extends('layouts.vertical-admin', ['subtitle' => 'Pengumuman'])

@section('css')
    @vite(['resources/css/admin/pengumuman.css'])
@endsection

@section('content')

@include('layouts.partials.page-title', ['title' => 'Admin', 'subtitle' => 'Pengumuman'])

{{-- Create Announcement Button --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="pengumuman-header-controls">
            <div class="pengumuman-description">
                <h5 class="mb-0">Kelola Pengumuman</h5>
                <p class="text-muted mb-0">Buat dan kelola pengumuman untuk guru dan siswa</p>
            </div>
            <div class="pengumuman-actions-wrapper">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAnnouncementModal">
                    <i class="bx bx-plus me-1"></i> Buat Pengumuman
                </button>
            </div>
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
            <form id="createAnnouncementForm" method="POST" action="{{ route('admin.pengumuman.store') }}">
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

{{-- Modal Notifikasi --}}
@include('components.pengaturan.notification-modal', [
    'modalId' => 'notificationModal',
    'modalLabelId' => 'notificationModalLabel',
    'messageId' => 'notificationMessage',
    'errorsId' => 'notificationErrors',
    'errorsBodyId' => 'notificationErrorsBody',
    'modalSize' => '',
    'showErrorsTable' => false
])
@endsection

@section('scripts')
    @vite(['resources/js/admin/pengumuman.js'])
@endsection
