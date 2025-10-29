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
                                            src="{{ auth()->user()->photo ? asset('storage/users/' . auth()->user()->photo) : '/images/users/avatar-1.jpg' }}"
                                            alt="avatar-{{ auth()->user()->id }}">
                                   </div>
                              </span>
                         </a>
                         <div class="dropdown-menu dropdown-menu-end">
                              <!-- item-->
                              <h6 class="dropdown-header">Welcome!</h6>

                              @auth
                                   @if(auth()->user()->roles()->where('name', 'admin')->exists())
                                        <a class="dropdown-item" href="{{ route('admin.pengaturan') }}">
                                             <iconify-icon icon="solar:settings-outline"
                                                  class="align-middle me-2 fs-18"></iconify-icon><span class="align-middle">Pengaturan</span>
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.bantuan') }}">
                                             <iconify-icon icon="solar:help-outline"
                                                  class="align-middle me-2 fs-18"></iconify-icon><span class="align-middle">Bantuan</span>
                                        </a>
                                   @elseif(auth()->user()->roles()->where('name', 'teacher')->exists())
                                        <a class="dropdown-item" href="{{ route('guru.pengaturan-guru') }}">
                                             <iconify-icon icon="solar:settings-outline"
                                                  class="align-middle me-2 fs-18"></iconify-icon><span class="align-middle">Pengaturan</span>
                                        </a>
                                        <a class="dropdown-item" href="{{ route('guru.bantuan-guru') }}">
                                             <iconify-icon icon="solar:help-outline"
                                                  class="align-middle me-2 fs-18"></iconify-icon><span class="align-middle">Bantuan</span>
                                        </a>
                                   @elseif(auth()->user()->roles()->where('name', 'student')->exists())
                                        <a class="dropdown-item" href="{{ route('murid.pengaturan') }}">
                                             <iconify-icon icon="solar:settings-outline"
                                                  class="align-middle me-2 fs-18"></iconify-icon><span class="align-middle">Pengaturan</span>
                                        </a>
                                        <a class="dropdown-item" href="{{ route('murid.bantuan') }}">
                                             <iconify-icon icon="solar:help-outline"
                                                  class="align-middle me-2 fs-18"></iconify-icon><span class="align-middle">Bantuan</span>
                                        </a>
                                   @endif
                              @endauth

                              <div class="dropdown-divider my-1"></div>

                              <form method="POST" action="{{ route('logout') }}">
                                   @csrf
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let notificationPollInterval = null;
    
    // Load notifications on page load
    loadNotifications();
    loadNotificationCount();
    
    // Poll for new notifications every 30 seconds
    notificationPollInterval = setInterval(() => {
        loadNotificationCount();
        loadNotifications(true); // Silent refresh
    }, 30000);
    
    // Load notification count
    function loadNotificationCount() {
        fetch('{{ route("api.notifications.unread-count") }}')
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notificationBadge');
                const count = document.getElementById('notificationCount');
                
                if (data.count > 0) {
                    badge.style.display = 'block';
                    count.textContent = data.count > 99 ? '99+' : data.count;
                } else {
                    badge.style.display = 'none';
                }
                
                // Show popup if there are new unread notifications
                if (data.count > 0) {
                    checkAndShowNewNotifications();
                }
            })
            .catch(error => console.error('Error loading notification count:', error));
    }
    
    // Load notifications list
    function loadNotifications(silent = false) {
        const dropdown = document.getElementById('notificationDropdown');
        const isOpen = dropdown?.getAttribute('aria-expanded') === 'true';
        
        // Only refresh if dropdown is open or if silent mode
        if (!isOpen && !silent) return;
        
        fetch('{{ route("api.notifications.recent") }}?limit=10')
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
                    <a href="#" class="list-group-item list-group-item-action ${!notif.is_read ? 'bg-light' : ''}" data-id="${notif.id}">
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
                            if (notifId) {
                                markAsRead(notifId);
                            }
                        }
                    });
                });
            })
            .catch(error => console.error('Error loading notifications:', error));
    }
    
    // Mark notification as read
    window.markAsRead = function(notifId) {
        fetch(`{{ route("api.notifications.mark-read", ":id") }}`.replace(':id', notifId), {
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
        fetch('{{ route("api.notifications.mark-all-read") }}', {
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
    
    // Check for new notifications and show popup
    function checkAndShowNewNotifications() {
        fetch('{{ route("api.notifications.recent") }}?limit=1&unread_only=1')
            .then(response => response.json())
            .then(notifications => {
                if (notifications.length > 0) {
                    const latest = notifications[0];
                    // Check if already shown (using sessionStorage)
                    const shownKey = `notif_shown_${latest.id}`;
                    if (!sessionStorage.getItem(shownKey)) {
                        showNotificationPopup(latest);
                        sessionStorage.setItem(shownKey, 'true');
                    }
                }
            })
            .catch(error => console.error('Error checking new notifications:', error));
    }
    
    // Show notification popup
    function showNotificationPopup(notification) {
        // Create popup element
        const popup = document.createElement('div');
        popup.className = 'notification-popup alert alert-info alert-dismissible fade show position-fixed';
        popup.style.cssText = 'top: 80px; right: 20px; z-index: 9999; max-width: 400px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';
        popup.innerHTML = `
            <div class="d-flex align-items-start">
                <iconify-icon icon="solar:bell-bold" class="fs-24 me-2"></iconify-icon>
                <div class="flex-grow-1">
                    <strong>${notification.title}</strong>
                    <p class="mb-0 small">${notification.message}</p>
                    <small class="text-muted">${notification.created_at}</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
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
@endpush