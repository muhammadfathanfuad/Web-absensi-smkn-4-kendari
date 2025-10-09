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
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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

</script>

@endsection
