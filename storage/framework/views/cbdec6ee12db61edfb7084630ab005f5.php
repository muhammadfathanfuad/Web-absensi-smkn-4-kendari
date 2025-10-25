<header class="app-topbar">
     <div class="container-fluid">
          <div class="navbar-header">
               <div class="d-flex align-items-center gap-2">
                    <!-- Menu Toggle Button -->
                    <div class="topbar-item">
                         <button type="button" class="button-toggle-menu topbar-button">
                              <iconify-icon icon="solar:hamburger-menu-outline"
                                   class="fs-24 align-middle"></iconify-icon>
                         </button>
                    </div>

                    
               </div>

               <div class="d-flex align-items-center gap-2">
                    <!-- Theme Color (Light/Dark) -->
                    <div class="topbar-item">
                         <button type="button" class="topbar-button" id="light-dark-mode">
                              <iconify-icon icon="solar:moon-outline"
                                   class="fs-22 align-middle light-mode"></iconify-icon>
                              <iconify-icon icon="solar:sun-2-outline"
                                   class="fs-22 align-middle dark-mode"></iconify-icon>
                         </button>
                    </div>

                    

                    <!-- User -->
                    <div class="dropdown topbar-item">
                         <a type="button" class="topbar-button" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                              aria-haspopup="true" aria-expanded="false">
                              <span class="d-flex align-items-center">
                                   <img class="rounded-circle" width="32" src="/images/users/avatar-1.jpg"
                                        alt="avatar-3">
                              </span>
                         </a>
                         <div class="dropdown-menu dropdown-menu-end">
                              <!-- item-->
                              <h6 class="dropdown-header">Welcome!</h6>

                              <?php if(auth()->guard()->check()): ?>
                                   <?php if(auth()->user()->roles()->where('name', 'admin')->exists()): ?>
                                        <a class="dropdown-item" href="<?php echo e(route('admin.pengaturan')); ?>">
                                             <iconify-icon icon="solar:settings-outline"
                                                  class="align-middle me-2 fs-18"></iconify-icon><span class="align-middle">Pengaturan</span>
                                        </a>
                                        <a class="dropdown-item" href="<?php echo e(route('admin.bantuan')); ?>">
                                             <iconify-icon icon="solar:help-outline"
                                                  class="align-middle me-2 fs-18"></iconify-icon><span class="align-middle">Bantuan</span>
                                        </a>
                                   <?php elseif(auth()->user()->roles()->where('name', 'teacher')->exists()): ?>
                                        <a class="dropdown-item" href="<?php echo e(route('guru.pengaturan-guru')); ?>">
                                             <iconify-icon icon="solar:settings-outline"
                                                  class="align-middle me-2 fs-18"></iconify-icon><span class="align-middle">Pengaturan</span>
                                        </a>
                                        <a class="dropdown-item" href="<?php echo e(route('guru.bantuan-guru')); ?>">
                                             <iconify-icon icon="solar:help-outline"
                                                  class="align-middle me-2 fs-18"></iconify-icon><span class="align-middle">Bantuan</span>
                                        </a>
                                   <?php elseif(auth()->user()->roles()->where('name', 'student')->exists()): ?>
                                        <a class="dropdown-item" href="<?php echo e(route('murid.pengaturan')); ?>">
                                             <iconify-icon icon="solar:settings-outline"
                                                  class="align-middle me-2 fs-18"></iconify-icon><span class="align-middle">Pengaturan</span>
                                        </a>
                                        <a class="dropdown-item" href="<?php echo e(route('murid.bantuan')); ?>">
                                             <iconify-icon icon="solar:help-outline"
                                                  class="align-middle me-2 fs-18"></iconify-icon><span class="align-middle">Bantuan</span>
                                        </a>
                                   <?php endif; ?>
                              <?php endif; ?>

                              <div class="dropdown-divider my-1"></div>

                              <form method="POST" action="<?php echo e(route('logout')); ?>">
                                   <?php echo csrf_field(); ?>
                                   <button type="submit" class="dropdown-item text-danger">
                                        <iconify-icon icon="solar:logout-3-outline"
                                             class="align-middle me-2 fs-18"></iconify-icon><span
                                             class="align-middle">Logout</span>
                                   </button>
                              </form>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</header><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/layouts/partials/topbar.blade.php ENDPATH**/ ?>