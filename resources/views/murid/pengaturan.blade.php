@extends('layouts.vertical-murid')

@section('title', 'Pengaturan')

@section('content')
    {{-- Page Title --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Pengaturan</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('murid.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pengaturan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Profil Akun --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-user me-2"></i>
                        Profil Akun
                    </h4>
                </div>
                <div class="card-body">
                    <form id="profilForm">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <img src="/images/users/avatar-1.jpg" alt="Avatar" class="rounded-circle img-thumbnail">
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary">
                                <i class="bx bx-camera me-1"></i>
                                Ganti Foto
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="namaLengkap" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="namaLengkap" value="Ahmad Fathan" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nis" class="form-label">NIS</label>
                                    <input type="text" class="form-control" id="nis" value="124510190" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kelas" class="form-label">Kelas</label>
                                    <input type="text" class="form-control" id="kelas" value="XI RPL 1" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" value="ahmad.fathan@smkn4kendari.sch.id">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telepon" class="form-label">Nomor Telepon</label>
                                    <input type="tel" class="form-control" id="telepon" value="081234567890">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" id="alamat" value="Jl. Pendidikan No. 123">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Keamanan Akun --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-shield me-2"></i>
                        Keamanan Akun
                    </h4>
                </div>
                <div class="card-body">
                    <form id="keamananForm">
                        <div class="mb-3">
                            <label for="passwordLama" class="form-label">Password Lama</label>
                            <input type="password" class="form-control" id="passwordLama" placeholder="Masukkan password lama">
                        </div>

                        <div class="mb-3">
                            <label for="passwordBaru" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="passwordBaru" placeholder="Masukkan password baru">
                        </div>

                        <div class="mb-3">
                            <label for="konfirmasiPassword" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="konfirmasiPassword" placeholder="Konfirmasi password baru">
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="bx bx-key me-1"></i>
                                Ubah Password
                            </button>
                        </div>
                    </form>

                    <hr>

                    <div class="mb-3">
                        <h6>Notifikasi</h6>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="notifEmail" checked>
                            <label class="form-check-label" for="notifEmail">
                                Email Notifikasi
                            </label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="notifSMS" checked>
                            <label class="form-check-label" for="notifSMS">
                                SMS Notifikasi
                            </label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="notifPush">
                            <label class="form-check-label" for="notifPush">
                                Push Notifikasi
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pengaturan Aplikasi --}}
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-cog me-2"></i>
                        Pengaturan Aplikasi
                    </h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="bahasa" class="form-label">Bahasa</label>
                        <select class="form-select" id="bahasa">
                            <option value="id">Bahasa Indonesia</option>
                            <option value="en">English</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="zonaWaktu" class="form-label">Zona Waktu</label>
                        <select class="form-select" id="zonaWaktu">
                            <option value="WITA">WITA (Waktu Indonesia Tengah)</option>
                            <option value="WIB">WIB (Waktu Indonesia Barat)</option>
                            <option value="WIT">WIT (Waktu Indonesia Timur)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tema" class="form-label">Tema</label>
                        <select class="form-select" id="tema">
                            <option value="light">Light</option>
                            <option value="dark">Dark</option>
                            <option value="auto">Auto</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" onclick="simpanPengaturan()">
                            <i class="bx bx-save me-1"></i>
                            Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Aktivitas Akun --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-history me-2"></i>
                        Aktivitas Akun
                    </h4>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Login Terakhir</h6>
                                <small class="text-muted">Hari ini, 08:30 WITA</small>
                            </div>
                            <i class="bx bx-check-circle text-success"></i>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Perubahan Password</h6>
                                <small class="text-muted">2 minggu lalu</small>
                            </div>
                            <i class="bx bx-shield text-info"></i>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Update Profil</h6>
                                <small class="text-muted">1 bulan lalu</small>
                            </div>
                            <i class="bx bx-user text-primary"></i>
                        </div>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-danger" onclick="logoutSemua()">
                            <i class="bx bx-log-out me-1"></i>
                            Logout dari Semua Device
                        </button>
                        <button type="button" class="btn btn-outline-warning" onclick="hapusAkun()">
                            <i class="bx bx-trash me-1"></i>
                            Hapus Akun
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profil form
    document.getElementById('profilForm').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Profil Anda telah diperbarui.',
            confirmButtonText: 'OK'
        });
    });

    // Keamanan form
    document.getElementById('keamananForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const passwordBaru = document.getElementById('passwordBaru').value;
        const konfirmasiPassword = document.getElementById('konfirmasiPassword').value;

        if (passwordBaru !== konfirmasiPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Password baru dan konfirmasi password tidak sama.',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Password Anda telah diubah.',
            confirmButtonText: 'OK'
        });
    });
});

function simpanPengaturan() {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Pengaturan aplikasi telah disimpan.',
        confirmButtonText: 'OK'
    });
}

function logoutSemua() {
    Swal.fire({
        title: 'Logout dari Semua Device?',
        text: "Anda akan logout dari semua device yang sedang aktif.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Logout!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                'Berhasil!',
                'Anda telah logout dari semua device.',
                'success'
            );
        }
    });
}

function hapusAkun() {
    Swal.fire({
        title: 'Hapus Akun?',
        text: "Tindakan ini tidak dapat dibatalkan! Akun Anda akan dihapus permanen.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                'Dihapus!',
                'Akun Anda telah dihapus.',
                'success'
            );
        }
    });
}
</script>
@endpush
