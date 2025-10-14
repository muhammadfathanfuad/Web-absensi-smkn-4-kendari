 

<?php $__env->startSection('body-attribuet'); ?>
    class="authentication-bg" style="background-image: url('/images/bg-signin.png'); background-size: cover;
    background-position: center; background-repeat: no-repeat;"
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="account-pages py-5">
        <div class="container">
            <div class="row ">
                <div class="col-md-6 col-lg-5">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">
                            <div class="text-center">
                                <div class="mx-auto mb-4 text-center auth-logo">
                                    <a href="<?php echo e(route('any', 'index')); ?>" class="logo-dark">
                                        <img src="/images/logo-dark.png" height="32" alt="logo dark">
                                    </a>

                                    <a href="<?php echo e(route('any', 'index')); ?>" class="logo-light">
                                        <img src="/images/logo-light.png" height="28" alt="logo light">
                                    </a>
                                </div>
                                <h4 class="fw-bold text-dark mb-2">Selamat Datang Kembali !</h4>
                                <p class="text-muted">Masuk ke akun anda</p>
                            </div>
                            <form method="POST" action="<?php echo e(route('login')); ?>" class="mt-4">
                                <?php echo csrf_field(); ?>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Terdaftar</label>
                                    <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="email" name="email" value="<?php echo e(old('email')); ?>"
                                        placeholder="Masukkan Email Anda">
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label for="password" class="form-label">Password</label>
                                    </div>
                                    <div class="position-relative">
                                        <input type="password" class="form-control pe-5" id="password" name="password"
                                               placeholder="Masukkan Password anda" autocomplete="current-password">
                                        <button type="button" class="btn btn-outline-secondary position-absolute end-0 top-50 translate-middle-y" id="toggle-password"
                                                aria-label="Tampilkan password" aria-pressed="false" onclick="togglePassword()">
                                            <i class="bx bx-show" aria-hidden="true"></i>
                                        </button>
                                    </div>
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
    <div id="notificationModal" class="modal fade" tabindex="-1" aria-labelledby="notificationModalLabel"
        aria-hidden="true">
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
<?php $__env->stopSection(); ?> 

<?php $__env->startSection('scripts'); ?>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const nextType = (passwordInput.type === 'password') ? 'text' : 'password';
            passwordInput.type = nextType;
            setIcon(nextType);
        }

        function setIcon(type) {
            const toggleBtn = document.getElementById('toggle-password');
            if (type === 'text') {
                toggleBtn.innerHTML = '<i class="bx bx-hide" aria-hidden="true"></i>';
                toggleBtn.setAttribute('aria-label', 'Sembunyikan password');
                toggleBtn.setAttribute('aria-pressed', 'true');
            } else {
                toggleBtn.innerHTML = '<i class="bx bx-show" aria-hidden="true"></i>';
                toggleBtn.setAttribute('aria-label', 'Tampilkan password');
                toggleBtn.setAttribute('aria-pressed', 'false');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi ikon sesuai state awal
            setIcon(document.getElementById('password').type);

            // Modal notifikasi
            const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));

            function showNotification(message, isSuccess = true) {
                document.getElementById('notificationModalLabel').innerText = isSuccess ? 'Berhasil' : 'Gagal';
                document.getElementById('notificationMessage').innerText = message;
                notificationModal.show();
            }
            // Pastikan backdrop dihapus saat modal ditutup
            document.getElementById('notificationModal').addEventListener('hidden.bs.modal', function() {
                // Hapus backdrop yang tersisa
                document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
                // Hapus class modal-open dari body jika masih ada
                document.body.classList.remove('modal-open');
            });

            <?php if($errors->has('email')): ?>
                showNotification("<?php echo e($errors->first('email')); ?>", false);
            <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.base', ['subtitle' => 'Sign In'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views\auth\signin.blade.php ENDPATH**/ ?>