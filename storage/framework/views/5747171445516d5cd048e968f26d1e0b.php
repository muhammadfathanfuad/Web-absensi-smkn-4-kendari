<div class="app-sidebar">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="<?php echo e(route('murid.dashboard')); ?>" class="logo-dark">
            <img src="/images/logo-sm.png" class="logo-sm" alt="logo sm">
            <img src="/images/logo-dark.png" class="logo-lg" alt="logo dark">
        </a>

        <a href="<?php echo e(route('murid.dashboard')); ?>" class="logo-light">
            <img src="/images/logo-sm.png" class="logo-sm" alt="logo sm">
            <img src="/images/logo-light.png" class="logo-lg" alt="logo light">
        </a>
    </div>

    <div class="scrollbar" data-simplebar>

        <ul class="navbar-nav" id="navbar-nav">

            <li class="menu-title">Menu...</li>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('murid.dashboard')); ?>">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:widget-2-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Dashboard </span>
                </a>
                <a class="nav-link" href="<?php echo e(route('murid.qr')); ?>">
                    <span class="nav-icon">
                        <i class='bx bx-qr-scan'></i>
                    </span>
                    <span class="nav-text"> QR Code </span>
                </a>
                <a class="nav-link" href="<?php echo e(route('murid.jadwal')); ?>">
                    <span class="nav-icon">
                        <i class='bx bx-calendar-alt'></i>
                    </span>
                    <span class="nav-text"> Jadwal Pelajaran </span>
                </a>
                <a class="nav-link" href="<?php echo e(route('murid.absensi')); ?>">
                    <span class="nav-icon">
                        <i class='bx bx-history'></i>
                    </span>
                    <span class="nav-text"> Riwayat Absensi </span>
                </a>
                <a class="nav-link" href="<?php echo e(route('murid.delegasi')); ?>">
                    <span class="nav-icon">
                        <i class='bx bx-transfer'></i>
                    </span>
                    <span class="nav-text"> Tugas Absensi </span>
                </a>
                <a class="nav-link" href="<?php echo e(route('murid.pengumuman')); ?>">
                    <span class="nav-icon">
                        <i class='bx bx-news'></i>
                    </span>
                    <span class="nav-text"> Pengumuman </span>
                </a>
                <a class="nav-link" href="<?php echo e(route('murid.permohonan-izin')); ?>">
                    <span class="nav-icon">
                        <i class='bx bx-file'></i>
                    </span>
                    <span class="nav-text"> Permohonan Izin </span>
                </a>
                <a class="nav-link" href="<?php echo e(route('murid.pengaturan')); ?>">
                    <span class="nav-icon">
                        <i class='bx bx-cog'></i>
                    </span>
                    <span class="nav-text"> Pengaturan </span>
                </a>
                <a class="nav-link" href="<?php echo e(route('murid.bantuan')); ?>">
                    <span class="nav-icon">
                        <i class='bx bx-help-circle'></i>
                    </span>
                    <span class="nav-text"> Bantuan </span>
                </a>
            </li>

        </ul>
    </div>
</div>

<?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/layouts/partials/sidebar-murid.blade.php ENDPATH**/ ?>