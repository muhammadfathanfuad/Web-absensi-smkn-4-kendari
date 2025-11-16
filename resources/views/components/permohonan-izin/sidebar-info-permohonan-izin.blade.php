@props([
    'mode' => 'guru', // 'guru' or 'murid'
    'recentRequests' => null, // For murid mode, pass recent requests
    'showRecentRequests' => false // Show recent requests sidebar (for murid)
])

<div class="col-lg-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bx bx-info-circle fs-18 text-info"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="card-title mb-0">
                        Informasi
                    </h5>
                    <p class="text-muted mb-0 small">Ketentuan & Panduan</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($mode === 'guru')
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="bx bx-info-circle me-2"></i>
                        Ketentuan Izin:
                    </h6>
                    <ul class="mb-0 small">
                        <li>Izin harus diajukan minimal 1 hari sebelumnya</li>
                        <li>Untuk izin sakit, lampirkan surat dokter</li>
                        <li>Admin akan menugaskan pengganti</li>
                        <li>Status dapat dicek di riwayat permohonan</li>
                    </ul>
                </div>
            @else
                <div class="mb-3">
                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-xs bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-calendar-check fs-14 text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Waktu Pengajuan</h6>
                            <p class="text-muted mb-0 small">Izin harus diajukan minimal 1 hari sebelumnya untuk memudahkan proses persetujuan.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-xs bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-file fs-14 text-danger"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Dokumen Pendukung</h6>
                            <p class="text-muted mb-0 small">Untuk izin sakit, wajib melampirkan surat dokter sebagai dokumen pendukung.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-xs bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-time-five fs-14 text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Waktu Proses</h6>
                            <p class="text-muted mb-0 small">Izin akan diproses dalam 1-2 hari kerja setelah pengajuan.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="avatar-xs bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-list-check fs-14 text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Cek Status</h6>
                            <p class="text-muted mb-0 small">Status permohonan dapat dicek di tabel riwayat permohonan di bawah.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($showRecentRequests && $mode === 'murid' && $recentRequests)
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-history me-2"></i>
                    Riwayat Terbaru
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @forelse($recentRequests as $request)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $request->leave_type_display }}</h6>
                                <small class="text-muted">{{ $request->created_at->format('d M Y') }}</small>
                            </div>
                            <span class="badge bg-{{ $request->status_badge }}">
                                @switch($request->status)
                                    @case('pending')
                                        Menunggu
                                        @break
                                    @case('approved')
                                        Disetujui
                                        @break
                                    @case('rejected')
                                        Ditolak
                                        @break
                                    @default
                                        {{ ucfirst($request->status) }}
                                @endswitch
                            </span>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted">
                            <small>Belum ada permohonan izin</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>

