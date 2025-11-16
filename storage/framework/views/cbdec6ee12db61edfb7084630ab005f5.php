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

                    <!-- Notifications -->
                    <div class="dropdown topbar-item">
                         <button type="button" class="topbar-button position-relative" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                              <iconify-icon icon="solar:bell-outline" class="fs-22 align-middle"></iconify-icon>
                              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationBadge" style="display: none; font-size: 10px; padding: 2px 5px;">
                                   <span id="notificationCount">0</span>
                              </span>
                         </button>
                         <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg p-0" style="width: 350px; max-width: 90vw;">
                              <div class="p-3 border-bottom">
                                   <div class="d-flex align-items-center justify-content-between">
                                        <h6 class="mb-0">Notifikasi</h6>
                                        <button type="button" class="btn btn-sm btn-link text-primary p-0" id="markAllReadBtn" style="display: none;">
                                             Tandai Semua Dibaca
                                        </button>
                                   </div>
                              </div>
                              <div id="notificationList" class="list-group" style="max-height: 400px; overflow-y: auto;">
                                   <div class="text-center py-4 text-muted">
                                        <iconify-icon icon="solar:bell-off-outline" class="fs-32 mb-2"></iconify-icon>
                                        <p class="mb-0">Tidak ada notifikasi</p>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <!-- User -->
                    <div class="dropdown topbar-item">
                         <a type="button" class="topbar-button" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                              aria-haspopup="true" aria-expanded="false">
                              <span class="d-flex align-items-center">
                                   <div style="width: 32px; height: 32px; overflow: hidden; border-radius: 50%;">
                                       <img class="rounded-circle" width="32" height="32" style="width: 100%; height: 100%; object-fit: cover;"
                                            src="<?php echo e(user_photo_url(auth()->user()->photo)); ?>"
                                            alt="avatar-<?php echo e(auth()->user()->id); ?>">
                                   </div>
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
</header>

<?php $__env->startPush('scripts'); ?>
<script>
// Handle notification redirect based on type (global function)
window.handleNotificationRedirect = function(notifType, element) {
    // Close dropdown first
    const dropdown = bootstrap.Dropdown.getInstance(document.getElementById('notificationDropdown'));
    if (dropdown) {
        dropdown.hide();
    }
    
    // Determine redirect URL based on notification type and user role
    let redirectUrl = null;
    
        <?php if(auth()->guard()->check()): ?>
            <?php if(auth()->user()->roles()->where('name', 'teacher')->exists()): ?>
                // Teacher routes
                if (notifType === 'announcement') {
                    redirectUrl = '<?php echo e(route("guru.pengumuman")); ?>';
                } else if (notifType === 'delegation') {
                    redirectUrl = '<?php echo e(route("guru.delegasi")); ?>';
                } else if (notifType === 'leave_request') {
                    redirectUrl = '<?php echo e(route("guru.dashboard")); ?>#list-siswa-izin-hari-ini';
                } else if (notifType === 'change_password') {
                    redirectUrl = '<?php echo e(route("guru.pengaturan-guru")); ?>';
                }
            <?php elseif(auth()->user()->roles()->where('name', 'student')->exists()): ?>
                // Student routes
                if (notifType === 'announcement') {
                    redirectUrl = '<?php echo e(route("murid.pengumuman")); ?>';
                } else if (notifType === 'delegation') {
                    redirectUrl = '<?php echo e(route("murid.delegasi")); ?>';
                } else if (notifType === 'leave_request') {
                    redirectUrl = '<?php echo e(route("murid.permohonan-izin")); ?>';
                } else if (notifType === 'change_password') {
                    redirectUrl = '<?php echo e(route("murid.pengaturan")); ?>';
                }
            <?php elseif(auth()->user()->roles()->where('name', 'admin')->exists()): ?>
                // Admin routes
                if (notifType === 'announcement') {
                    redirectUrl = '<?php echo e(route("admin.pengumuman")); ?>';
                } else if (notifType === 'delegation') {
                    redirectUrl = '<?php echo e(route("admin.delegasi")); ?>';
                } else if (notifType === 'leave_request') {
                    redirectUrl = '<?php echo e(route("admin.delegasi")); ?>#permohonan-izin'; // Redirect to delegasi page with permohonan izin tab active
                } else if (notifType === 'change_password') {
                    redirectUrl = '<?php echo e(route("admin.pengaturan")); ?>';
                }
            <?php endif; ?>
        <?php endif; ?>
    
    // Redirect if URL is determined
    if (redirectUrl) {
        // Small delay to allow dropdown to close
        setTimeout(() => {
            // If URL contains hash, navigate to it
            if (redirectUrl.includes('#')) {
                window.location.href = redirectUrl;
            } else {
            window.location.href = redirectUrl;
            }
        }, 100);
    }
};

document.addEventListener('DOMContentLoaded', function() {
    // CRITICAL: Don't load notifications on DOMContentLoaded
    // Wait for page load to complete first to prevent blocking
    // Notifications will be loaded after SSE connection is established
    
    // CRITICAL: Prevent multiple SSE instances - use window-level flag
    if (window._sseInitialized) {
        return;
    }
    window._sseInitialized = true;
    
    // Setup Server-Sent Events (SSE) for real-time notifications
    let eventSource = null;
    let sseInitTimeout = null;
    let isNavigating = false; // Reset untuk page baru
    let sseInitialized = false; // Flag untuk prevent double initialization
    let sseConnecting = false; // Flag untuk prevent multiple simultaneous connections
    
    // Function to close SSE connection - AGGRESSIVE untuk fast navigation
    function closeSSEConnection() {
        // Set navigating flag immediately
        isNavigating = true;
        
        if (eventSource) {
            try {
                // Nullify all event handlers FIRST (prevent any callbacks from firing)
                eventSource.onopen = null;
                eventSource.onerror = null;
                eventSource.onmessage = null;
                
                // Remove all event listeners
                if (eventSource.removeEventListener) {
                    // Try to remove notification listener if exists
                    try {
                        eventSource.removeEventListener('notification', function(){});
                    } catch(e) {}
                }
                
                // Close the connection
                eventSource.close();
            } catch (e) {
                // Ignore errors
            }
            eventSource = null;
        }
        
        // Reset flags
        sseInitialized = false;
        sseConnecting = false;
        window._sseInitialized = false; // Reset window-level flag
        
        // Clear initialization timeout if exists
        if (sseInitTimeout) {
            clearTimeout(sseInitTimeout);
            sseInitTimeout = null;
        }
    }
    
    // Initialize SSE connection after page loads with delay
    // SSE akan connect setelah 3 detik setelah page load selesai (sama seperti admin)
    // CRITICAL: Gunakan once: true untuk prevent multiple load event handlers
    window.addEventListener('load', function() {
        // Prevent multiple initialization
        if (sseInitialized || sseConnecting) {
            return;
        }
        
        if (typeof EventSource === 'undefined') {
            return;
        }
        
        // Mark as initializing
        sseConnecting = true;
        
        // Delay SSE connection untuk tidak mempengaruhi initial page load
        // Sama seperti implementasi admin: delay 3 detik
        sseInitTimeout = setTimeout(function() {
            // Double check: hanya connect jika page masih active dan tidak navigating
            // CRITICAL: Check juga apakah sudah ada connection yang aktif
            if (!isNavigating && !sseInitialized && !eventSource && document.readyState === 'complete' && !document.hidden) {
                // Close any existing connection first (safety check)
                if (eventSource) {
                    try {
                        eventSource.close();
                    } catch(e) {}
                    eventSource = null;
                }
                
                const sseUrl = '<?php echo e(route("api.notifications.stream")); ?>';
                eventSource = new EventSource(sseUrl);
                sseInitialized = true; // Mark as initialized immediately
            
                // Load notification count when connection opens
                eventSource.onopen = function() {
                    // Don't process if navigating
                    if (isNavigating) {
                        return;
                    }
                    sseConnecting = false; // Mark as no longer connecting
                    loadNotificationCount();
                };
                
                // Handle notification events
                eventSource.addEventListener('notification', function(e) {
                    // CRITICAL: Don't process events if navigating or connection invalid
                    if (isNavigating || !eventSource || eventSource.readyState === EventSource.CLOSED) {
                        return;
                    }
                    
                    try {
                        const notification = JSON.parse(e.data);
                        
                        // Double check: still not navigating
                        if (isNavigating) {
                            return;
                        }
                        
                        // Update notification count
                        loadNotificationCount();
                        
                        // Update notification list if dropdown is open
                        const dropdown = document.getElementById('notificationDropdown');
                        if (dropdown?.getAttribute('aria-expanded') === 'true') {
    loadNotifications();
                        }
                        
                        // Show popup for new notification
                        const shownKey = `notif_shown_${notification.id}`;
                        if (!sessionStorage.getItem(shownKey)) {
                            showNotificationPopup(notification);
                            sessionStorage.setItem(shownKey, 'true');
                        }
                    } catch (error) {
                        // Silent error handling
                    }
                });
                
                // Handle errors
                eventSource.onerror = function(e) {
                    // Don't process if navigating
                    if (isNavigating) {
                        return;
                    }
                    
                    // Check readyState
                    if (eventSource.readyState === EventSource.CLOSED) {
                        sseInitialized = false;
                        sseConnecting = false;
                        eventSource = null;
                        return;
                    }
                    
                    // If CONNECTING, it means EventSource is trying to reconnect
                    // Prevent auto-reconnect by closing and resetting flags
                    if (eventSource.readyState === EventSource.CONNECTING) {
                        try {
                            eventSource.close();
                        } catch(err) {}
                        sseInitialized = false;
                        sseConnecting = false;
                        eventSource = null;
                        return;
                    }
                    // For OPEN state errors, close connection to prevent auto-reconnect
                    if (eventSource.readyState === EventSource.OPEN) {
                        try {
                            eventSource.close();
                        } catch(err) {}
                        sseInitialized = false;
                        sseConnecting = false;
                        eventSource = null;
                    }
                };
            } else {
                // Reset flag if connection was not created
                sseConnecting = false;
            }
        }, 3000); // Delay 3 detik setelah page load (sama seperti admin)
    }, { once: true }); // CRITICAL: once: true untuk prevent multiple load handlers
    
    // Close SSE connection saat navigation - CRITICAL: Close on mousedown (BEFORE click)
    // Ini memberikan waktu lebih banyak untuk close sebelum navigation terjadi
    document.addEventListener('mousedown', function(e) {
        const link = e.target.closest('a[href]');
        if (link && link.href) {
            const href = link.getAttribute('href');
            const isInternalLink = href && !href.startsWith('#') && 
                                 !href.startsWith('javascript:') && 
                                 href !== '#' && 
                                 !link.target &&
                                 !link.hasAttribute('data-bs-toggle') && 
                                 !link.hasAttribute('data-bs-target') &&
                                 !link.hasAttribute('data-bs-dismiss');
            
            if (isInternalLink) {
                // Close SSE immediately on mousedown (before click fires)
                closeSSEConnection();
            }
        }
    }, { capture: true, passive: true });
    
    // Backup: Also close on click (in case mousedown missed)
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a[href]');
        if (link && link.href) {
            const href = link.getAttribute('href');
            const isInternalLink = href && !href.startsWith('#') && 
                                 !href.startsWith('javascript:') && 
                                 href !== '#' && 
                                 !link.target &&
                                 !link.hasAttribute('data-bs-toggle') && 
                                 !link.hasAttribute('data-bs-target') &&
                                 !link.hasAttribute('data-bs-dismiss');
            
            if (isInternalLink && eventSource) {
                // Force close if still open
                closeSSEConnection();
            }
        }
    }, { capture: true, passive: true });
    
    // Close SSE connection saat form submission (GET method untuk navigation)
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form && form.tagName === 'FORM' && form.method.toLowerCase() === 'get') {
            isNavigating = true;
            closeSSEConnection();
        }
    }, { capture: true });
    
    // Close SSE connection on page unload/refresh - AGGRESSIVE
    window.addEventListener('beforeunload', function() {
        closeSSEConnection();
        // Reset window-level flag untuk allow reconnect di page baru
        window._sseInitialized = false;
        // Force stop all network requests if possible
        try {
            if (window.stop) {
                window.stop();
            }
        } catch (e) {
            // Ignore errors
        }
    }, { passive: true });
    
    // Close SSE connection on pagehide (lebih reliable untuk refresh)
    window.addEventListener('pagehide', function(e) {
        closeSSEConnection();
        // If page is being unloaded (not just hidden), force stop
        if (e.persisted === false) {
            try {
                if (window.stop) {
                    window.stop();
                }
            } catch (e) {
                // Ignore errors
            }
        }
    }, { passive: true });
    
    // Close SSE connection saat tab hidden (visibility change)
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            closeSSEConnection();
        } else {
            // Tab visible again - reset navigating flag untuk allow reconnect
            // Tapi jangan reconnect otomatis, biarkan page load handler yang handle
            isNavigating = false;
        }
    });
    
    // Load notification count
    function loadNotificationCount() {
        // CRITICAL: Don't load if navigating
        if (isNavigating || document.readyState === 'unloading') {
            return;
        }
        
        // Prevent multiple simultaneous requests
        if (window._loadingNotificationCount) {
            return;
        }
        
        window._loadingNotificationCount = true;
        
        fetch('<?php echo e(route("api.notifications.unread-count")); ?>', {
            cache: 'no-cache',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notificationBadge');
                const count = document.getElementById('notificationCount');
                
                if (badge && count) {
                if (data.count > 0) {
                    badge.style.display = 'block';
                    count.textContent = data.count > 99 ? '99+' : data.count;
                } else {
                    badge.style.display = 'none';
                }
                }
            })
            .catch(error => {
                console.error('Error loading notification count:', error);
            })
            .finally(() => {
                window._loadingNotificationCount = false;
            });
    }
    
    // Load notifications list
    function loadNotifications(silent = false) {
        const dropdown = document.getElementById('notificationDropdown');
        const isOpen = dropdown?.getAttribute('aria-expanded') === 'true';
        
        // Only refresh if dropdown is open or if silent mode
        if (!isOpen && !silent) return;
        
        fetch('<?php echo e(route("api.notifications.recent")); ?>?limit=10')
            .then(response => response.json())
            .then(notifications => {
                const list = document.getElementById('notificationList');
                const markAllBtn = document.getElementById('markAllReadBtn');
                
                if (notifications.length === 0) {
                    list.innerHTML = `
                        <div class="text-center py-4 text-muted">
                            <iconify-icon icon="solar:bell-off-outline" class="fs-32 mb-2"></iconify-icon>
                            <p class="mb-0">Tidak ada notifikasi</p>
                        </div>
                    `;
                    markAllBtn.style.display = 'none';
                    return;
                }
                
                const unreadCount = notifications.filter(n => !n.is_read).length;
                if (unreadCount > 0) {
                    markAllBtn.style.display = 'block';
                } else {
                    markAllBtn.style.display = 'none';
                }
                
                list.innerHTML = notifications.map(notif => `
                    <a href="#" class="list-group-item list-group-item-action ${!notif.is_read ? 'bg-light' : ''}" 
                       data-id="${notif.id}" 
                       data-type="${notif.type || ''}" 
                       data-related-id="${notif.related_id || ''}" 
                       data-related-type="${notif.related_type || ''}">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 ${!notif.is_read ? 'fw-bold' : ''}">${notif.title}</h6>
                                <p class="mb-1 small">${notif.message}</p>
                                <small class="text-muted">${notif.created_at}</small>
                            </div>
                            ${!notif.is_read ? `
                                <button type="button" class="btn btn-sm btn-link text-primary mark-read-btn" data-id="${notif.id}" onclick="event.stopPropagation(); markAsRead(${notif.id});">
                                    Dibaca
                                </button>
                            ` : ''}
                        </div>
                    </a>
                `).join('');
                
                // Add click handlers
                list.querySelectorAll('.list-group-item').forEach(item => {
                    item.addEventListener('click', function(e) {
                        if (!e.target.classList.contains('mark-read-btn')) {
                            const notifId = this.getAttribute('data-id');
                            const notifType = this.getAttribute('data-type');
                            
                            // Mark as read
                            if (notifId) {
                                markAsRead(notifId);
                            }
                            
                            // Redirect based on notification type
                            window.handleNotificationRedirect(notifType, this);
                        }
                    });
                });
            })
            .catch(error => console.error('Error loading notifications:', error));
    }
    
    // Mark notification as read
    window.markAsRead = function(notifId) {
        fetch(`<?php echo e(route("api.notifications.mark-read", ":id")); ?>`.replace(':id', notifId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
                loadNotificationCount();
            }
        })
        .catch(error => console.error('Error marking as read:', error));
    };
    
    // Mark all as read
    document.getElementById('markAllReadBtn')?.addEventListener('click', function() {
        fetch('<?php echo e(route("api.notifications.mark-all-read")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
                loadNotificationCount();
            }
        })
        .catch(error => console.error('Error marking all as read:', error));
    });
    
    // Refresh when dropdown opens
    const notificationDropdown = document.getElementById('notificationDropdown');
    if (notificationDropdown) {
        notificationDropdown.addEventListener('shown.bs.dropdown', function() {
            loadNotifications();
        });
    }
    
    // Show notification popup
    function showNotificationPopup(notification) {
        // Create popup element
        const popup = document.createElement('div');
        popup.className = 'notification-popup alert alert-info alert-dismissible fade show position-fixed';
        popup.style.cssText = 'top: 80px; right: 20px; z-index: 9999; max-width: 400px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); cursor: pointer;';
        popup.innerHTML = `
            <div class="d-flex align-items-start">
                <iconify-icon icon="solar:bell-bold" class="fs-24 me-2"></iconify-icon>
                <div class="flex-grow-1">
                    <strong>${notification.title}</strong>
                    <p class="mb-0 small">${notification.message}</p>
                    <small class="text-muted">${notification.created_at}</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" onclick="event.stopPropagation();"></button>
            </div>
        `;
        
        // Add click handler to redirect
        popup.addEventListener('click', function(e) {
            if (!e.target.classList.contains('btn-close')) {
                window.handleNotificationRedirect(notification.type || '', popup);
            }
        });
        
        document.body.appendChild(popup);
        
        // Auto remove after 8 seconds
        setTimeout(() => {
            if (popup.parentNode) {
                popup.classList.remove('show');
                setTimeout(() => popup.remove(), 300);
            }
        }, 8000);
    }
});
</script>
<?php $__env->stopPush(); ?><?php /**PATH C:\Users\fatha\Herd\website_absensi_smkn_4_kendari\resources\views/layouts/partials/topbar.blade.php ENDPATH**/ ?>