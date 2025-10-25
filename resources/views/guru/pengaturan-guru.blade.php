@extends('layouts.vertical-guru', ['subtitle' => 'Pengaturan'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Pengaturan', 'subtitle' => 'Guru'])

    <div class="row">
        <div class="col-lg-8">
            {{-- Profil Guru --}}
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                                <iconify-icon icon="solar:user-outline" class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="card-title mb-1">
                                Profil Guru
                            </h4>
                            <p class="text-muted mb-0">Kelola informasi profil Anda</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_lengkap" class="form-label fw-semibold">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama_lengkap" value="Dr. Ahmad Wijaya, S.Pd., M.Pd.">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nip" class="form-label fw-semibold">NIP</label>
                                    <input type="text" class="form-control" id="nip" value="196512151990031001">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">Email</label>
                                    <input type="email" class="form-control" id="email" value="ahmad.wijaya@smkn4kendari.sch.id">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="no_hp" class="form-label fw-semibold">No. Handphone</label>
                                    <input type="text" class="form-control" id="no_hp" value="081234567890">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mata_pelajaran" class="form-label fw-semibold">Mata Pelajaran</label>
                                    <input type="text" class="form-control" id="mata_pelajaran" value="Matematika">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kelas" class="form-label fw-semibold">Kelas yang Diampu</label>
                                    <input type="text" class="form-control" id="kelas" value="XII RPL 1, XII RPL 2">
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <iconify-icon icon="solar:diskette-outline" class="fs-16 me-2"></iconify-icon>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Keamanan Akun --}}
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-md bg-warning bg-opacity-10 rounded-circle">
                                <iconify-icon icon="solar:shield-user-outline" class="fs-32 text-warning avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="card-title mb-1">
                                Keamanan Akun
                            </h4>
                            <p class="text-muted mb-0">Kelola keamanan akun Anda</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="password_lama" class="form-label fw-semibold">Password Lama</label>
                            <input type="password" class="form-control" id="password_lama">
                        </div>
                        <div class="mb-3">
                            <label for="password_baru" class="form-label fw-semibold">Password Baru</label>
                            <input type="password" class="form-control" id="password_baru">
                        </div>
                        <div class="mb-3">
                            <label for="konfirmasi_password" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="konfirmasi_password">
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-warning">
                                <iconify-icon icon="solar:lock-password-outline" class="fs-16 me-2"></iconify-icon>
                                Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Notifikasi --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <iconify-icon icon="solar:bell-outline" class="fs-20 me-2"></iconify-icon>
                        Pengaturan Notifikasi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="notif_email" checked>
                        <label class="form-check-label" for="notif_email">
                            Notifikasi Email
                        </label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="notif_reminder" checked>
                        <label class="form-check-label" for="notif_reminder">
                            Pengingat Jadwal
                        </label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="notif_absensi">
                        <label class="form-check-label" for="notif_absensi">
                            Notifikasi Absensi
                        </label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="notif_pengumuman" checked>
                        <label class="form-check-label" for="notif_pengumuman">
                            Pengumuman Sekolah
                        </label>
                    </div>
                </div>
            </div>

            {{-- Tema --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <iconify-icon icon="solar:palette-outline" class="fs-20 me-2"></iconify-icon>
                        Pengaturan Tema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mode Tampilan</label>
                        <select class="form-select">
                            <option value="light" selected>Terang</option>
                            <option value="dark">Gelap</option>
                            <option value="auto">Otomatis</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ukuran Font</label>
                        <select class="form-select">
                            <option value="small">Kecil</option>
                            <option value="medium" selected>Sedang</option>
                            <option value="large">Besar</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-outline-primary">
                            <iconify-icon icon="solar:refresh-outline" class="fs-16 me-2"></iconify-icon>
                            Terapkan
                        </button>
                    </div>
                </div>
            </div>

            {{-- Informasi Sistem --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <iconify-icon icon="solar:info-circle-outline" class="fs-20 me-2"></iconify-icon>
                        Informasi Sistem
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Versi Aplikasi</span>
                        <span class="fw-semibold">v1.0.0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Terakhir Update</span>
                        <span class="fw-semibold">{{ \Carbon\Carbon::now()->subDays(7)->format('d/m/Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Status Server</span>
                        <span class="badge bg-success-subtle text-success py-1 px-2">
                            <i class="bx bxs-circle text-success me-1"></i>Online
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Penyimpanan</span>
                        <span class="fw-semibold">2.5 GB / 10 GB</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
