@extends('layouts.base', ['subtitle' => 'Sign In'])

@section('body-attribuet')
class="authentication-bg" style="background-image: url('/images/bg-signin.png'); background-size: cover; background-position: center; background-repeat: no-repeat;"
@endsection

@section('content')
<div class="account-pages py-5">
    <div class="container">
        <div class="row ">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center">
                            <div class="mx-auto mb-4 text-center auth-logo">
                                <a href="{{ route('any', 'index') }}" class="logo-dark">
                                    <img src="/images/logo-dark.png" height="32" alt="logo dark">
                                </a>

                                <a href="{{ route('any', 'index') }}" class="logo-light">
                                    <img src="/images/logo-light.png" height="28" alt="logo light">
                                </a>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">Selamat Datang Kembali !</h3>
                                <p class="text-muted">Masuk ke akun anda</p>
                        </div>
                        <form method="POST" action="{{ route('login') }}" class="mt-4">

                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Terdaftar</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}"
                                    placeholder="Masukkan Email Anda">
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="password" class="form-label">Password</label>
                                </div>
                                <div class="position-relative">
                                    <input type="password" class="form-control pe-5" id="password" name="password"
                                        placeholder="Masukkan Password anda">
                                    <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y me-2" id="toggle-password">
                                        <i class="bx bx-show"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="remember-me" name="remember">
                                <label class="form-check-label" for="remember-me">Ingat saya</label>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-dark btn-lg fw-medium" type="submit">Masuk</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Notifikasi -->
<div id="notificationModal" class="modal fade" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationModalLabel">Notifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0" id="notificationMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')

<script>

document.getElementById('toggle-password').addEventListener('click', function() {

    const passwordInput = document.getElementById('password');

    const icon = this.querySelector('i');

    if (passwordInput.type === 'password') {

        passwordInput.type = 'text';

        icon.className = 'bx bx-hide';

    } else {

        passwordInput.type = 'password';

        icon.className = 'bx bx-show';

    }

});

document.addEventListener('DOMContentLoaded', function() {
    const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));

    function showNotification(message, isSuccess = true) {
        document.getElementById('notificationModalLabel').innerText = isSuccess ? 'Berhasil' : 'Gagal';
        document.getElementById('notificationMessage').innerText = message;
        notificationModal.show();
    }

    @if($errors->has('email'))
        showNotification("{{ $errors->first('email') }}", false);
    @endif
});

</script>

@endsection
