<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Donasi Panti') }} - Dashboard</title> 

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --primary-green: #10b981;
            --navbar-height: 60px;
        }
        .text-primary-green { color: var(--primary-green) !important; }
        .bg-primary-green { background-color: var(--primary-green) !important; }
        .btn-primary-custom {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            color: white;
            transition: all 0.3s ease;
        }
        .btn-primary-custom:hover {
            background-color: #059669;
            border-color: #059669;
            color: white;
        }
        .card-custom {
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        }
        
        .main-content {
            padding-top: calc(var(--navbar-height) + 20px); 
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .navbar-fixed {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1020;
            height: var(--navbar-height);
        }
        
        .navbar .nav-link.active-menu {
            border-bottom: 2px solid var(--primary-green);
            color: var(--primary-green) !important;
            font-weight: bold;
        }
        
        .navbar-nav .nav-item {
            margin-left: 0.5rem;
        }

        /* Notification Styles */
        .notification-dropdown {
            position: relative;
        }

        .notification-bell {
            position: relative;
            cursor: pointer;
            padding: 8px 12px;
            color: #6c757d;
            transition: color 0.3s;
        }

        .notification-bell:hover {
            color: var(--primary-green);
        }

        .notification-badge {
            position: absolute;
            top: 2px;
            right: 4px;
            background: #ef4444;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 2px 5px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        .notification-menu {
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

        .notification-menu.show {
            display: block;
        }

        .notification-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header h6 {
            margin: 0;
            font-weight: 600;
            color: #1f2937;
        }

        .mark-all-read {
            color: var(--primary-green);
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
        }

        .mark-all-read:hover {
            text-decoration: underline;
        }

        .notification-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 16px 20px;
            border-bottom: 1px solid #f3f4f6;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            display: block;
            color: inherit;
            position: relative;
        }

        .notification-item:hover {
            background: #f9fafb;
        }

        .notification-item.unread {
            background: #f0fdf4;
        }

        .notification-item.unread::before {
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

        .notification-content {
            display: flex;
            gap: 12px;
            position: relative;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notification-icon.success {
            background: #dcfce7;
            color: #16a34a;
        }

        .notification-icon.warning {
            background: #fef3c7;
            color: #d97706;
        }

        .notification-icon.info {
            background: #dbeafe;
            color: #2563eb;
        }

        .notification-icon.danger {
            background: #fee2e2;
            color: #dc2626;
        }

        .notification-text {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            font-size: 14px;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .notification-message {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 4px;
            line-height: 1.4;
        }

        .notification-time {
            font-size: 12px;
            color: #9ca3af;
        }

        .notification-empty {
            padding: 60px 20px;
            text-align: center;
            color: #9ca3af;
        }

        .notification-empty i {
            font-size: 48px;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        .notification-footer {
            padding: 12px 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }

        .notification-footer a {
            color: var(--primary-green);
            font-size: 14px;
            text-decoration: none;
            font-weight: 500;
        }

        .notification-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm navbar-fixed">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary-green" href="{{ url('/') }}">
                    <i class="fas fa-hand-holding-heart me-1"></i> Donasi Panti
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto"></ul>

                    <ul class="navbar-nav ms-auto">
                        @auth
                            <li class="nav-item d-none d-md-block">
                                <a class="nav-link {{ request()->routeIs('donatur.dashboard') ? 'active-menu' : '' }}" 
                                   href="{{ route('donatur.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                                </a>
                            </li>

                            <li class="nav-item d-none d-md-block">
                                <a class="nav-link {{ request()->routeIs('donatur.donasi.*') ? 'active-menu' : '' }}" 
                                   href="{{ route('donatur.donasi.index') }}">
                                    <i class="fas fa-hand-holding-heart me-1"></i> Donasi Aktif
                                </a>
                            </li>
                            
                            <li class="nav-item d-none d-md-block">
                                <a class="nav-link {{ request()->routeIs('donatur.riwayat') ? 'active-menu' : '' }}" 
                                   href="{{ route('donatur.riwayat') }}">
                                    <i class="fas fa-history me-1"></i> Riwayat Donasi
                                </a>
                            </li>

                            <!-- NOTIFIKASI DROPDOWN -->
                            <li class="nav-item notification-dropdown">
                                <div class="notification-bell" id="notificationBell">
                                    <i class="fas fa-bell fa-lg"></i>
                                    <span class="notification-badge" id="notificationCount" style="display: none;">0</span>
                                </div>
                                
                                <div class="notification-menu" id="notificationMenu">
                                    <div class="notification-header">
                                        <h6>Notifikasi</h6>
                                        <a href="#" class="mark-all-read" id="markAllRead">Tandai Sudah Dibaca</a>
                                    </div>
                                    
                                    <div class="notification-list" id="notificationList">
                                        <!-- Notifikasi akan dimuat via JavaScript -->
                                    </div>
                                    
                                    <div class="notification-footer">
                                        <a href="{{ route('notifications.index') }}">Lihat Semua Notifikasi</a>
                                    </div>
                                </div>
                            </li>
                            
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-user-circle"></i> Profil Saya
                                    </a>

                                    <div class="dropdown-divider d-md-none"></div>
                                    <a class="dropdown-item d-md-none" href="{{ route('donatur.dashboard') }}">Dashboard</a>
                                    <a class="dropdown-item d-md-none" href="{{ route('donatur.donasi.index') }}">Donasi Aktif</a>
                                    <a class="dropdown-item d-md-none" href="{{ route('donatur.riwayat') }}">Riwayat Donasi</a>
                                    
                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> Keluar
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @else
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link btn btn-primary-custom ms-2" href="{{ route('register') }}">Daftar</a>
                                </li>
                            @endif
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        
        <main class="main-content flex-grow-1 container">
            @yield('content')
        </main>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const bell = document.getElementById('notificationBell');
        const menu = document.getElementById('notificationMenu');
        const list = document.getElementById('notificationList');
        const badge = document.getElementById('notificationCount');
        const markAllBtn = document.getElementById('markAllRead');
        
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
                    <div class="notification-empty">
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
                    <a href="/notifications/${notif.id}/read" class="notification-item ${unreadClass}">
                        <div class="notification-content">
                            <div class="notification-icon ${iconClass}">
                                <i class="fas ${getIcon(notif.type)}"></i>
                            </div>
                            <div class="notification-text">
                                <div class="notification-title">${notif.title}</div>
                                <div class="notification-message">${notif.message}</div>
                                <div class="notification-time">${timeAgo}</div>
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
</body>
</html>