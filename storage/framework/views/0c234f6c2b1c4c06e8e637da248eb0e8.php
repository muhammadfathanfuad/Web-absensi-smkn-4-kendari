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
                <!-- Pengumuman menu removed -->
            </li>

        </ul>
    </div>
</div>

<?php /**PATH D:\PresenZ\Web-absensi-smkn-4-kendari\resources\views/layouts/partials/sidebar-murid.blade.php ENDPATH**/ ?>