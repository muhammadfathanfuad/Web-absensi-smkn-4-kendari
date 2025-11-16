@props([
    'title' => 'Keamanan Akun',
    'formId' => 'keamananForm',
    'passwordRoute' => null,
    'showCurrentPassword' => true,
    'showPasswordChangeSection' => false
])

<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-0 d-flex align-items-center">
            <i class="bx bx-shield me-2"></i>
            {{ $title }}
        </h4>
    </div>
    <div class="card-body">
        <form id="{{ $formId }}" @if(isset($passwordRoute) && $passwordRoute) action="{{ $passwordRoute }}" method="POST" @endif>
            @csrf
            @if($showCurrentPassword)
            <div class="mb-3">
                <label for="current_password" class="form-label">Password Saat Ini</label>
                <input type="password" class="form-control" id="current_password" name="current_password" 
                       placeholder="Masukkan password saat ini" required>
                <div class="invalid-feedback" id="current_password_error"></div>
            </div>
            @endif

            <div class="mb-3">
                <label for="new_password" class="form-label">Password Baru</label>
                <input type="password" class="form-control" id="new_password" name="new_password" 
                       placeholder="Masukkan password baru" minlength="8" required>
                <div class="invalid-feedback" id="new_password_error"></div>
                @if($showPasswordChangeSection)
                <div class="form-text">Password harus minimal 8 karakter</div>
                @endif
            </div>

            <div class="mb-3">
                <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" 
                       placeholder="Konfirmasi password baru" minlength="8" required>
                <div class="invalid-feedback" id="new_password_confirmation_error"></div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-warning">
                    <i class="bx bx-key me-1"></i>
                    Ubah Password
                </button>
            </div>
        </form>
    </div>
</div>

