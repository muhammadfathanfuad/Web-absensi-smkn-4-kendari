@props([
    'user' => null,
    'showLogoutButton' => true
])

<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-0 d-flex align-items-center">
            <i class="bx bx-history me-2"></i>
            Aktivitas Akun
        </h4>
    </div>
    <div class="card-body">
        <div class="list-group list-group-flush" id="aktivitasList">
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Login Terakhir</h6>
                    <small class="text-muted" id="lastLogin">-</small>
                </div>
                <i class="bx bx-check-circle text-success"></i>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Password Terakhir Diubah</h6>
                    <small class="text-muted" id="lastPasswordChange">-</small>
                </div>
                <i class="bx bx-shield text-info"></i>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Profil Terakhir Diupdate</h6>
                    <small class="text-muted" id="lastProfileUpdate">-</small>
                </div>
                <i class="bx bx-user text-primary"></i>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Akun Dibuat</h6>
                    <small class="text-muted" id="accountCreated">-</small>
                </div>
                <i class="bx bx-calendar text-secondary"></i>
            </div>
        </div>

        @if($showLogoutButton)
        <hr>
        <div class="d-grid gap-2">
            <button type="button" class="btn btn-outline-danger" onclick="logoutSemua()">
                <i class="bx bx-log-out me-1"></i>
                Logout dari Semua Device
            </button>
        </div>
        @endif
    </div>
</div>

