<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Donasi Panti')</title>
    
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin_layout.css') }}" rel="stylesheet"> 
    
    <style>
        /* Notification Styles for Admin */
        .notification-bell-admin {
            position: relative;
            cursor: pointer;
            color: #6c757d;
            transition: color 0.3s;
            font-size: 1.25rem;
        }

        .notification-bell-admin:hover {
            color: var(--primary-green);
        }

        .notification-badge-admin {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 2px 5px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        .notification-dropdown-admin {
            position: relative;
        }

        .notification-menu-admin {
            position: absolute;
            top: 100%;
            right: 0;
            width: 380px;
            max-height: 500px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            display: none;
            z-index: 1000;
            margin-top: 10px;
        }

        .notification-menu-admin.show {
            display: block;
        }

        .notification-header-admin {
            padding: 16px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header-admin h6 {
            margin: 0;
            font-weight: 600;
            color: #1f2937;
        }

        .mark-all-read-admin {
            color: var(--primary-green);
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
        }

        .mark-all-read-admin:hover {
            text-decoration: underline;
        }

        .notification-list-admin {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item-admin {
            padding: 16px 20px;
            border-bottom: 1px solid #f3f4f6;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            display: block;
            color: inherit;
            position: relative;
        }

        .notification-item-admin:hover {
            background: #f9fafb;
        }

        .notification-item-admin.unread {
            background: #f0fdf4;
        }

        .notification-item-admin.unread::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            background: var(--primary-green);
            border-radius: 50%;
        }

        .notification-content-admin {
            display: flex;
            gap: 12px;
        }

        .notification-icon-admin {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notification-icon-admin.success {
            background: #dcfce7;
            color: #16a34a;
        }

        .notification-icon-admin.warning {
            background: #fef3c7;
            color: #d97706;
        }

        .notification-icon-admin.info {
            background: #dbeafe;
            color: #2563eb;
        }

        .notification-icon-admin.danger {
            background: #fee2e2;
            color: #dc2626;
        }

        .notification-text-admin {
            flex: 1;
        }

        .notification-title-admin {
            font-weight: 600;
            font-size: 14px;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .notification-message-admin {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 4px;
            line-height: 1.4;
        }

        .notification-time-admin {
            font-size: 12px;
            color: #9ca3af;
        }

        .notification-empty-admin {
            padding: 60px 20px;
            text-align: center;
            color: #9ca3af;
        }

        .notification-empty-admin i {
            font-size: 48px;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        .notification-footer-admin {
            padding: 12px 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }

        .notification-footer-admin a {
            color: var(--primary-green);
            font-size: 14px;
            text-decoration: none;
            font-weight: 500;
        }

        .notification-footer-admin a:hover {
            text-decoration: underline;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div id="app">
        <div class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <i class="fas fa-hands-helping fa-2x text-white mb-2"></i>
                <h3>Donasi Panti</h3>
                <p>Admin Panel</p>
            </div>

            <div class="sidebar-menu">
                <div class="sidebar-menu-item">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-link @if(Request::is('admin/dashboard') || Request::is('admin')) active @endif">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
                <div class="sidebar-menu-item">
                    <a href="/admin/kebutuhan" class="sidebar-menu-link @if(Request::is('admin/kebutuhan*')) active @endif">
                        <i class="fas fa-list-ul"></i>
                        <span>Data Kebutuhan</span>
                    </a>
                </div>
                <div class="sidebar-menu-item">
                    <a href="/admin/donasi" class="sidebar-menu-link @if(Request::is('admin/donasi*')) active @endif">
                        <i class="fas fa-hand-holding-usd"></i>
                        <span>Data Donasi</span>
                    </a>
                </div>
                <div class="sidebar-menu-item">
                    <a href="/admin/donatur" class="sidebar-menu-link @if(Request::is('admin/donatur*')) active @endif">
                        <i class="fas fa-users"></i>
                        <span>Data Donatur</span>
                    </a>
                </div>
                <div class="sidebar-divider"></div>
                <div class="sidebar-menu-item">
                    <a href="/admin/laporan" class="sidebar-menu-link @if(Request::is('admin/laporan*')) active @endif">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan</span>
                    </a>
                </div>
                <div class="sidebar-menu-item">
                    <a href="/admin/settings" class="sidebar-menu-link @if(Request::is('admin/settings*')) active @endif">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                </div>
                <div class="sidebar-divider"></div>
                <div class="sidebar-menu-item">
                    <a href="{{ route('logout') }}" class="sidebar-menu-link" 
                        onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <div class="main-content">
            <!-- Topbar dengan Notifikasi -->
            <div class="topbar">
                <div class="topbar-left">
                    <button class="mobile-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h5>@yield('title', 'Dashboard Admin')</h5>
                </div>
                <div class="topbar-right">
                    <!-- Notifikasi Bell -->
                    <div class="notification-dropdown-admin">
                        <div class="notification-bell-admin" id="notificationBellAdmin">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge-admin" id="notificationCountAdmin" style="display: none;">0</span>
                        </div>
                        
                        <div class="notification-menu-admin" id="notificationMenuAdmin">
                            <div class="notification-header-admin">
                                <h6>Notifikasi</h6>
                                <a href="#" class="mark-all-read-admin" id="markAllReadAdmin">Tandai Sudah Dibaca</a>
                            </div>
                            
                            <div class="notification-list-admin" id="notificationListAdmin">
                                <!-- Notifikasi akan dimuat via JavaScript -->
                            </div>
                            
                            <div class="notification-footer-admin">
                                <a href="{{ route('notifications.index') }}">Lihat Semua Notifikasi</a>
                            </div>
                        </div>
                    </div>

                    <div class="user-profile">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-info">
                            <div class="name">{{ Auth::user()->name ?? 'Admin Panti' }}</div>
                            <div class="role">Administrator</div>
                        </div>
                        <i class="fas fa-chevron-down" style="color: #6c757d; font-size: 0.8rem;"></i>
                    </div>
                </div>
            </div>

            <main>
                @yield('content')
            </main>

            <footer class="footer-custom text-center mt-5 p-3">
                 <div class="container-fluid">
                     <p class="mb-0 text-muted">&copy; {{ date('Y') }} <a href="{{ url('/') }}">Sistem Donasi Panti</a>. Dibuat dengan <i class="fas fa-heart text-danger"></i></p>
                 </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        // Notification Script for Admin
        document.addEventListener('DOMContentLoaded', function() {
            const bell = document.getElementById('notificationBellAdmin');
            const menu = document.getElementById('notificationMenuAdmin');
            const list = document.getElementById('notificationListAdmin');
            const badge = document.getElementById('notificationCountAdmin');
            const markAllBtn = document.getElementById('markAllReadAdmin');
            
            if (!bell) return;

            bell.addEventListener('click', function(e) {
                e.stopPropagation();
                menu.classList.toggle('show');
                loadNotifications();
            });
            
            document.addEventListener('click', function(e) {
                if (!menu.contains(e.target) && !bell.contains(e.target)) {
                    menu.classList.remove('show');
                }
            });
            
            function loadNotifications() {
                fetch('/notifications/unread')
                    .then(response => response.json())
                    .then(data => {
                        updateBadge(data.unread_count);
                        renderNotifications(data.notifications);
                    })
                    .catch(error => console.error('Error:', error));
            }
            
            function updateBadge(count) {
                if (count > 0) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.style.display = 'block';
                } else {
                    badge.style.display = 'none';
                }
            }
            
            function renderNotifications(notifications) {
                if (notifications.length === 0) {
                    list.innerHTML = `
                        <div class="notification-empty-admin">
                            <i class="fas fa-bell-slash"></i>
                            <p>Tidak ada notifikasi baru</p>
                        </div>
                    `;
                    return;
                }
                
                list.innerHTML = notifications.map(notif => {
                    const iconClass = getIconClass(notif.type);
                    const timeAgo = formatTimeAgo(notif.created_at);
                    const unreadClass = !notif.is_read ? 'unread' : '';
                    
                    return `
                        <a href="/notifications/${notif.id}/read" class="notification-item-admin ${unreadClass}">
                            <div class="notification-content-admin">
                                <div class="notification-icon-admin ${iconClass}">
                                    <i class="fas ${getIcon(notif.type)}"></i>
                                </div>
                                <div class="notification-text-admin">
                                    <div class="notification-title-admin">${notif.title}</div>
                                    <div class="notification-message-admin">${notif.message}</div>
                                    <div class="notification-time-admin">${timeAgo}</div>
                                </div>
                            </div>
                        </a>
                    `;
                }).join('');
            }
            
            function getIconClass(type) {
                const types = {
                    'donasi_baru': 'info',
                    'donasi_berhasil': 'success',
                    'donasi_ditolak': 'danger',
                    'donasi_menunggu': 'warning',
                    'kebutuhan_baru': 'info'
                };
                return types[type] || 'info';
            }
            
            function getIcon(type) {
                const icons = {
                    'donasi_baru': 'fa-hand-holding-usd',
                    'donasi_berhasil': 'fa-check-circle',
                    'donasi_ditolak': 'fa-times-circle',
                    'donasi_menunggu': 'fa-clock',
                    'kebutuhan_baru': 'fa-bullhorn'
                };
                return icons[type] || 'fa-bell';
            }
            
            function formatTimeAgo(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const seconds = Math.floor((now - date) / 1000);
                
                const intervals = {
                    tahun: 31536000,
                    bulan: 2592000,
                    minggu: 604800,
                    hari: 86400,
                    jam: 3600,
                    menit: 60
                };
                
                for (let [unit, secondsInUnit] of Object.entries(intervals)) {
                    const interval = Math.floor(seconds / secondsInUnit);
                    if (interval >= 1) {
                        return `${interval} ${unit} lalu`;
                    }
                }
                
                return 'Baru saja';
            }
            
            if (markAllBtn) {
                markAllBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    fetch('/notifications/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        loadNotifications();
                    })
                    .catch(error => console.error('Error:', error));
                });
            }
            
            loadNotifications();
            setInterval(loadNotifications, 30000);
        });
    </script>
    
    @stack('scripts')
</body>
</html>