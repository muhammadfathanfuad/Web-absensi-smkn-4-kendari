@props([
    'user' => null,
    'title' => 'Profil Akun',
    'formId' => 'profilForm',
    'photoRoute' => null,
    'profileRoute' => null,
    'showNip' => false,
    'showNis' => false,
    'readonlyFields' => [],
    'additionalFields' => [],
    'showPasswordChangeSection' => false,
    'showCurrentPasswordField' => false,
    'showReset' => false,
    'customLayout' => false
])

<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-0 d-flex align-items-center">
            <i class="bx bx-user me-2"></i>
            {{ $title }}
        </h4>
    </div>
    <div class="card-body">
        {{-- Foto Profil --}}
        <div class="text-center mb-4">
            <div class="avatar-lg mx-auto mb-3 pengaturan-avatar-container">
                <img id="avatarPreview" src="{{ user_photo_url($user->photo ?? null) }}" alt="Avatar" class="rounded-circle img-thumbnail pengaturan-avatar-preview">
            </div>
            <input type="file" id="photoInput" accept="image/*" class="pengaturan-photo-input">
            <button type="button" class="btn btn-sm btn-outline-primary" id="photoUploadBtn">
                <i class="bx bx-camera me-1"></i>
                Ganti Foto
            </button>
            <div id="photoError" class="text-danger mt-2 pengaturan-photo-error"></div>
        </div>

        <form id="{{ $formId }}" @if(isset($profileRoute) && $profileRoute) action="{{ $profileRoute }}" method="POST" @endif>
            @csrf
            @if(isset($profileRoute) && $profileRoute) @method('PUT') @endif
            @if($customLayout)
            {{-- Custom Layout for Admin: Nama Lengkap | Nomor Telepon, Email | Password Saat Ini --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" 
                               value="{{ $user->full_name ?? '' }}" 
                               {{ in_array('full_name', $readonlyFields) ? 'readonly' : 'required' }}>
                        <div class="invalid-feedback" id="full_name_error"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Nomor Telepon</label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="{{ $user->phone ?? '' }}" 
                               {{ in_array('phone', $readonlyFields) ? 'readonly' : '' }}>
                        <div class="invalid-feedback" id="phone_error"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ $user->email ?? '' }}" required>
                        <div class="invalid-feedback" id="email_error"></div>
                    </div>
                </div>
                @if($showCurrentPasswordField)
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini (untuk konfirmasi)</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                        <div class="invalid-feedback" id="current_password_error"></div>
                    </div>
                </div>
                @endif
            </div>
            @else
            {{-- Default Layout: Nama Lengkap | NIP/NIS, Email | Nomor Telepon | Password Saat Ini --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" 
                               value="{{ $user->full_name ?? '' }}" 
                               {{ in_array('full_name', $readonlyFields) ? 'readonly' : 'required' }}>
                        <div class="invalid-feedback" id="full_name_error"></div>
                    </div>
                </div>
                
                @if($showNip)
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text" class="form-control" id="nip" name="nip" 
                               value="{{ $user->teacher->nip ?? '' }}">
                        <div class="invalid-feedback" id="nip_error"></div>
                    </div>
                </div>
                @endif
                
                @if($showNis)
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nis" class="form-label">NIS</label>
                        <input type="text" class="form-control" id="nis" name="nis" 
                               value="{{ $user->student->nis ?? '-' }}" readonly>
                    </div>
                </div>
                @endif
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ $user->email ?? '' }}" required>
                        <div class="invalid-feedback" id="email_error"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Nomor Telepon</label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="{{ $user->phone ?? '' }}" 
                               {{ in_array('phone', $readonlyFields) ? 'readonly' : '' }}>
                        <div class="invalid-feedback" id="phone_error"></div>
                    </div>
                </div>
                @if($showCurrentPasswordField)
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini (untuk konfirmasi)</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                        <div class="invalid-feedback" id="current_password_error"></div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            @if($showPasswordChangeSection)
            {{-- Password Change Section --}}
            <div class="row">
                <div class="col-12">
                    <hr class="my-4">
                    <h6 class="mb-3 text-primary">
                        <i class="bx bx-lock me-2"></i>
                        Ubah Password
                    </h6>
                    <p class="text-muted mb-3">Kosongkan field password jika tidak ingin mengubah password</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" 
                               minlength="8" placeholder="Minimal 8 karakter">
                        <div class="invalid-feedback" id="new_password_error"></div>
                        <div class="form-text">Password harus minimal 8 karakter</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" 
                               minlength="8" placeholder="Ulangi password baru">
                        <div class="invalid-feedback" id="new_password_confirmation_error"></div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Additional Fields Slot --}}
            @if(!empty($additionalFields))
                <div class="row">
                    @foreach($additionalFields as $field)
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="{{ $field['id'] }}" class="form-label">{{ $field['label'] }}</label>
                                <input type="{{ $field['type'] ?? 'text' }}" 
                                       class="form-control" 
                                       id="{{ $field['id'] }}" 
                                       name="{{ $field['name'] ?? $field['id'] }}" 
                                       value="{{ $field['value'] ?? '' }}"
                                       {{ isset($field['required']) && $field['required'] ? 'required' : '' }}
                                       {{ isset($field['readonly']) && $field['readonly'] ? 'readonly' : '' }}>
                                @if(isset($field['error_id']))
                                    <div class="invalid-feedback" id="{{ $field['error_id'] }}"></div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i>
                    Simpan Perubahan
                </button>
                @if($showReset)
                <button type="button" class="btn btn-secondary ms-2" id="resetFormBtn">
                    <i class="bx bx-refresh me-1"></i> Reset
                </button>
                @endif
            </div>
        </form>
    </div>
</div>

