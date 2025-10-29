<div class="app-sidebar">
     <!-- Sidebar Logo -->
     <div class="logo-box">
          <a href="<?php echo e(route('any', 'index')); ?>" class="logo-dark">
               <img src="/images/logo-sm.png" class="logo-sm" alt="logo sm">
               <img src="/images/logo-dark.png" class="logo-lg" alt="logo dark">
          </a>

          <a href="<?php echo e(route('any', 'index')); ?>" class="logo-light">
               <img src="/images/logo-sm.png" class="logo-sm" alt="logo sm">
               <img src="/images/logo-light.png" class="logo-lg" alt="logo light">
          </a>
     </div>

     <div class="scrollbar" data-simplebar>

          <ul class="navbar-nav" id="navbar-nav">

               <li class="menu-title">Menu...</li>

               <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('any', 'admin/dashboard')); ?>">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:widget-2-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Dashboard </span>
                    </a>
                    <a class="nav-link" href="<?php echo e(route('users.manage')); ?>">
                         <span class="nav-icon">
                                <i class='bx  bx-user-circle'  ></i>    
                         </span>
                         <span class="nav-text"> Manajemen User</span>
                    </a>
                    <a class="nav-link" href="<?php echo e(route('any', 'admin.jadwal-pelajaran')); ?>">
                         <span class="nav-icon">
                              <i class='bx  bx-calendar-alt'  ></i>   
                         </span>
                         <span class="nav-text"> Jadwal Pelajaran </span>
                    </a>
                    <a class="nav-link" href="<?php echo e(route('any', 'admin.laporan')); ?>">
                         <span class="nav-icon">
                              <i class='bx  bx-history'  ></i>    
                         </span>
                         <span class="nav-text"> Laporan </span>
                    </a>
                    <a class="nav-link" href="<?php echo e(route('any', 'admin.pengumuman')); ?>">
                         <span class="nav-icon">
                              <i class='bx bx-news'></i>    
                         </span>
                         <span class="nav-text"> Pengumuman </span>
                    </a>
                    <a class="nav-link" href="<?php echo e(route('admin.delegasi')); ?>">
                         <span class="nav-icon">
                              <i class='bx bx-user-check'></i>    
                         </span>
                         <span class="nav-text"> Manajemen Pengganti </span>
                    </a>
                    <a class="nav-link" href="<?php echo e(route('any', 'admin.pengaturan')); ?>">
                         <span class="nav-icon">
                              <i class='bx bx-cog'></i>    
                         </span>
                         <span class="nav-text"> Pengaturan </span>
                    </a>
                    <a class="nav-link" href="<?php echo e(route('any', 'admin.bantuan')); ?>">
                         <span class="nav-icon">
                              <i class='bx bx-help-circle'></i>    
                         </span>
                         <span class="nav-text"> Bantuan </span>
                    </a>
               </li>

          </ul>
     </div>
</div><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/layouts/partials/sidebar-admin.blade.php ENDPATH**/ ?>