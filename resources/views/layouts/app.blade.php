<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ResourceHQ') - Smart Resource Booking System</title>
    
    <!-- Standard Amazon Font Stack -->
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        :root {
            --primary: #131921;       /* Amazon Navy */
            --primary-light: #232f3e; /* Amazon Sub-nav Navy */
            --accent: #f3a847;        /* Amazon Orange Accent */
            --accent-hover: #f0c14b;
            --accent-button: #febd69;
            --accent-button-hover: #f3a847;
            
            --bg-app: #eaeded;
            --surface: #ffffff;
            
            --text-main: #0f1111;
            --text-muted: #565959;
            --border-light: #d5d9d9;
            
            --success-bg: #f0fdf4; --success-text: #15803d; --success-border: #bbf7d0;
            --warning-bg: #fffbeb; --warning-text: #b45309; --warning-border: #fde68a;
            --danger-bg: #fef2f2;  --danger-text: #b91c1c;  --danger-border: #fecaca;
            --info-bg: #eff6ff;    --info-text: #1d4ed8;    --info-border: #bfdbfe;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: "Amazon Ember", Arial, sans-serif;
        }

        body {
            background-color: var(--bg-app);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* -----------------------
           MAIN LAYOUT
        ------------------------ */
        .wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
        }

        /* Top Header */
        .top-header {
            height: 60px;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            padding: 0 20px;
            gap: 20px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: white;
            padding: 8px;
            border: 1px solid transparent;
            border-radius: 2px;
            margin-right: 12px;
        }
        .brand-logo:hover {
            border-color: white;
        }

        .brand-icon {
            font-weight: 800;
            font-size: 24px;
            color: var(--accent);
            line-height: 1;
        }

        .brand-text {
            color: white;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .header-search {
            flex: 1;
            display: flex;
            align-items: center;
            height: 40px;
            border-radius: 4px;
            overflow: hidden;
            background: white;
            max-width: 800px;
        }
        
        .header-search:focus-within {
            box-shadow: 0 0 0 3px #f90, inset 0 0 0 1px #fff;
        }

        .header-search input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            height: 100%;
            font-size: 15px;
            padding: 0 12px;
            color: var(--text-main);
        }

        .header-search button {
            width: 45px;
            height: 100%;
            background: var(--accent-button);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #333;
            transition: background 0.15s;
        }
        .header-search button:hover {
            background: var(--accent-button-hover);
        }
        .header-search button svg {
            width: 20px;
            height: 20px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: white;
            text-decoration: none;
            padding: 8px 10px;
            border: 1px solid transparent;
            border-radius: 2px;
            cursor: pointer;
            position: relative;
        }
        .nav-item:hover {
            border-color: white;
        }
        .nav-line-1 { font-size: 12px; line-height: 14px; color: #ccc; }
        .nav-line-2 { font-size: 14px; font-weight: 700; line-height: 15px; display: flex; align-items: center; gap: 4px; }
        
        .notification-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            background: #f3a847;
            color: #111;
            font-size: 11px;
            font-weight: 700;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Sub Navigation */
        .sub-nav {
            background: var(--primary-light);
            color: white;
            height: 39px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            gap: 10px;
            overflow-x: auto;
        }
        .sub-nav-link {
            color: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 6px 8px;
            border: 1px solid transparent;
            border-radius: 2px;
            white-space: nowrap;
        }
        .sub-nav-link:hover, .sub-nav-link.active {
            border-color: white;
        }

        /* -----------------------
           PAGE CONTENT
        ------------------------ */
        .page-content {
            padding: 24px 20px;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
            flex: 1;
        }

        /* Standardized Card */
        .card {
            background: var(--surface);
            border-radius: 8px;
            border: 1px solid var(--border-light);
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
            padding: 24px;
            margin-bottom: 24px;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-light);
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-main);
        }

        /* Data Tables */
        .table-container {
            border: 1px solid var(--border-light);
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 14px;
        }

        .data-table th {
            background: #f8fafc;
            padding: 12px 16px;
            font-weight: 600;
            color: var(--text-main);
            border-bottom: 1px solid var(--border-light);
        }

        .data-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-light);
            color: var(--text-main);
            vertical-align: middle;
        }

        .data-table tbody tr:hover {
            background: #f8fafc;
        }
        
        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Status Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            line-height: 1.5;
            text-transform: capitalize;
        }

        .badge-pending { background: var(--warning-bg); color: var(--warning-text); border: 1px solid var(--warning-border); }
        .badge-approved { background: var(--success-bg); color: var(--success-text); border: 1px solid var(--success-border); }
        .badge-rejected { background: var(--danger-bg); color: var(--danger-text); border: 1px solid var(--danger-border); }
        .badge-cancelled { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.15s;
            text-decoration: none;
            line-height: 1.2;
            box-shadow: 0 2px 5px rgba(213,217,217,0.5);
        }

        .btn-primary { 
            background: #ffd814; 
            border-color: #fcd200; 
            color: #0f1111; 
        }
        .btn-primary:hover { 
            background: #f7ca00; 
            border-color: #f2c200;
        }
        .btn-outline { 
            background: white; 
            border-color: var(--border-light); 
            color: var(--text-main); 
        }
        .btn-outline:hover { 
            background: #f7fafa; 
        }
        
        /* Typography overrides for headings */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            color: var(--text-main);
        }

        /* Inputs */
        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--border-light);
            border-radius: 4px;
            font-size: 14px;
            color: var(--text-main);
            background: white;
            transition: box-shadow 0.15s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(243, 168, 71, 0.4);
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 6px;
        }
    </style>
</head>
<body>

    <!-- Main Layout Wrapper -->
    <div class="wrapper">
        
        <!-- Top Navbar (Amazon Navy) -->
        <header class="top-header">
            <a href="{{ route('dashboard') }}" class="brand-logo">
                <div class="brand-icon">R</div>
                <div class="brand-text">ResourceHQ</div>
            </a>

            <!-- Search Bar -->
            <div class="header-search">
                <input type="text" placeholder="Search resources, departments...">
                <button type="button">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </div>

            @auth
            <div class="header-right">
                <!-- Notifications -->
                <div x-data="{ open: false }" @click.outside="open = false" style="position: relative;">
                    <div class="nav-item" @click="open = !open">
                        <div class="nav-line-1">Returns</div>
                        <div class="nav-line-2">& Alerts <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="12" height="12" style="margin-top: 2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <div class="notification-badge">{{ auth()->user()->unreadNotifications->count() }}</div>
                        @endif
                    </div>
                    
                    <!-- Notification Dropdown -->
                    <div x-show="open" style="display: none; position: absolute; right: 0; top: 46px; width: 340px; background: white; border: 1px solid var(--border-light); border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 50; overflow: hidden;">
                        <div style="padding: 12px 16px; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center; background: #f8fafc; color: #111;">
                            <div style="font-weight: 700; font-size: 14px;">Notifications</div>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                                    @csrf
                                    <button type="submit" style="font-size: 12px; color: #007185; background: none; border: none; cursor: pointer;">Mark all read</button>
                                </form>
                            @endif
                        </div>
                        <div style="max-height: 300px; overflow-y: auto;">
                            @forelse(auth()->user()->unreadNotifications as $notification)
                                <div style="padding: 12px 16px; border-bottom: 1px solid var(--border-light); display: flex; gap: 12px; align-items: flex-start; background: #fff;">
                                    <div style="flex: 1;">
                                        <div style="font-size: 13px; color: var(--text-main); line-height: 1.4; margin-bottom: 4px;">
                                            {{ $notification->data['message'] ?? 'New Notification' }}
                                        </div>
                                        <div style="font-size: 11px; color: var(--text-muted); display: flex; justify-content: space-between;">
                                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                                            <form method="POST" action="{{ route('notifications.mark-as-read', $notification->id) }}">
                                                @csrf
                                                <button type="submit" style="color: #007185; background: none; border: none; cursor: pointer; font-size: 11px;">Mark Read</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div style="padding: 24px 16px; text-align: center; color: var(--text-muted); font-size: 13px;">
                                    No new alerts.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- User Profile -->
                <div x-data="{ open: false }" @click.outside="open = false" style="position: relative;">
                    <div class="nav-item" @click="open = !open">
                        <div class="nav-line-1">Hello, {{ auth()->user()->name }}</div>
                        <div class="nav-line-2">Account & Lists <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="12" height="12" style="margin-top: 2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                    
                    <!-- Account Dropdown -->
                    <div x-show="open" style="display: none; position: absolute; right: 0; top: 46px; width: 240px; background: white; border: 1px solid var(--border-light); border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 50; padding: 16px;">
                        <div style="font-weight: 700; font-size: 16px; margin-bottom: 12px; color: #111;">Your Account</div>
                        <a href="{{ route('profile.edit') }}" style="display: block; color: #007185; text-decoration: none; font-size: 14px; margin-bottom: 12px;">Edit Profile</a>
                        <a href="{{ route('bookings.index') }}" style="display: block; color: #007185; text-decoration: none; font-size: 14px; margin-bottom: 16px;">Your Bookings</a>
                        
                        <div style="border-top: 1px solid var(--border-light); padding-top: 12px;">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" style="color: #007185; background: none; border: none; cursor: pointer; font-size: 14px; padding: 0;">Sign Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endauth
        </header>

        <!-- Sub Navigation (Amazon Lighter Navy) -->
        @auth
        <nav class="sub-nav">
            <a href="{{ route('dashboard') }}" class="sub-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('bookings.index') }}" class="sub-nav-link {{ request()->routeIs('bookings.index') ? 'active' : '' }}">My Bookings</a>
            <a href="{{ route('calendar') }}" class="sub-nav-link {{ request()->routeIs('calendar') ? 'active' : '' }}">Calendar</a>
            
            @if(auth()->user()->isResourceManager() || auth()->user()->isAdmin())
                <span style="color: #ccc; margin: 0 4px;">|</span>
                <a href="{{ route('bookings.index', ['status' => 'PENDING']) }}" class="sub-nav-link">Booking Requests</a>
            @endif

            @if(auth()->user()->isAdmin())
                <span style="color: #ccc; margin: 0 4px;">|</span>
                <a href="{{ route('admin.employees.index') }}" class="sub-nav-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">Directory</a>
                <a href="{{ route('admin.departments.index') }}" class="sub-nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">Departments</a>
                <a href="{{ route('resource-categories.index') }}" class="sub-nav-link {{ request()->routeIs('resource-categories.*') ? 'active' : '' }}">Categories</a>
                <a href="{{ route('resources.index') }}" class="sub-nav-link {{ request()->routeIs('resources.*') ? 'active' : '' }}">Resources</a>
            @endif
        </nav>
        @endauth

        <!-- Page Content -->
        <main class="page-content">
            @yield('content')
        </main>
    </div>

    <!-- Toast Notifications -->
    <style>
        .toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 100;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .toast {
            background: white;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            border-left: 4px solid var(--border-light);
            min-width: 300px;
            animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        
        .toast-success { border-left-color: #067d62; }
        .toast-error { border-left-color: #c40000; }

        .toast-icon { width: 24px; height: 24px; flex-shrink: 0; }
        .toast-success .toast-icon { color: #067d62; }
        .toast-error .toast-icon { color: #c40000; }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>

    <div class="toast-container" id="toast-container">
        @if(session('success'))
            <div class="toast toast-success" id="session-toast-success">
                <svg class="toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <div style="font-size: 14px; font-weight: 700; color: #111; margin-bottom: 2px;">Success</div>
                    <div style="font-size: 13px; color: #565959;">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="toast toast-error" id="session-toast-error">
                <svg class="toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <div style="font-size: 14px; font-weight: 700; color: #111; margin-bottom: 2px;">Error</div>
                    <div style="font-size: 13px; color: #565959;">{{ session('error') }}</div>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    toast.style.transition = 'all 0.3s ease-out';
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>
