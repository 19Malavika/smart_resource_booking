<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ResourceHQ') - Smart Resource Booking System</title>
    
    <!-- SEO & Open Graph Meta Tags -->
    <meta name="description" content="ResourceHQ is a smart resource booking system designed to streamline internal operations and resource management.">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="@yield('title', 'ResourceHQ') - Smart Resource Booking System">
    <meta property="og:description" content="Manage and book resources efficiently with ResourceHQ.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
    
    <!-- Favicon Placeholder -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <meta name="view-transition" content="same-origin">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/js/app.ts'])

    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        
        /* Premium Micro-Animations */
        .reveal-up {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-up.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        .hover-lift {
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-50 flex min-h-screen overflow-x-hidden antialiased">

    <div class="flex w-full min-h-screen">
        
        <!-- Sidebar -->
        <aside class="w-[260px] bg-zinc-950 border-r border-zinc-800 flex flex-col fixed inset-y-0 left-0 z-50">
            <div class="h-16 flex items-center px-6 border-b border-zinc-800">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 no-underline text-zinc-50 font-bold text-base tracking-tight">
                    <div class="bg-zinc-50 text-zinc-950 w-7 h-7 flex items-center justify-center rounded-md text-sm">R</div>
                    <span>ResourceHQ</span>
                </a>
            </div>

            @auth
            <nav class="p-6 pt-6 flex flex-col gap-1 flex-1 overflow-y-auto">
                @php
                    $navItemClass = "flex items-center gap-3 py-2.5 px-4 no-underline font-medium text-sm rounded-lg transition-colors duration-200 ";
                    $inactiveClass = "text-zinc-400 hover:bg-zinc-900 hover:text-zinc-50 [&>svg]:opacity-80";
                    $activeClass = "bg-zinc-800 text-zinc-50 [&>svg]:opacity-100";
                @endphp
                @if(auth()->user()->isResourceManager() && !auth()->user()->isAdmin())
                    <div class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mx-4 mt-3 mb-1">Resource Manager</div>
                    
                    <a href="{{ route('resources.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('resources.*') ? $activeClass : $inactiveClass }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        Manage Assigned Resources
                    </a>
                    
                    <a href="{{ route('bookings.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('bookings.*') ? $activeClass : $inactiveClass }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        View Booking Requests
                    </a>
                    
                    <a href="{{ route('utilization.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('utilization.*') ? $activeClass : $inactiveClass }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Resource Utilization
                    </a>
                @else
                    <div class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mx-4 mt-3 mb-1">General</div>
                    
                    <a href="{{ route('dashboard') }}" class="{{ $navItemClass }} {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('resources.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('resources.*') ? $activeClass : $inactiveClass }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Browse Resources
                    </a>
                    <a href="{{ route('bookings.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('bookings.*') ? $activeClass : $inactiveClass }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        My Bookings
                    </a>

                    
                    @if(auth()->user()->isAdmin())
                    <div class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider mx-4 mt-6 mb-1">Admin Panel</div>
                    
                    <a href="{{ route('dashboard') }}" class="{{ $navItemClass }} {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('admin.users.*') ? $activeClass : $inactiveClass }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Users
                    </a>
                    
                    <a href="{{ route('admin.departments.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('admin.departments.*') ? $activeClass : $inactiveClass }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Departments
                    </a>
                    
                    <a href="{{ route('admin.resources.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('admin.resources.*') ? $activeClass : $inactiveClass }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        Resources
                    </a>
                    
                    <a href="{{ route('admin.categories.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('admin.categories.*') ? $activeClass : $inactiveClass }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        Resource Categories
                    </a>
                    
                    <a href="{{ route('admin.bookings.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('admin.bookings.*') ? $activeClass : $inactiveClass }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        All Bookings
                    </a>
                    
                    <a href="{{ route('admin.maintenance.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('admin.maintenance.*') ? $activeClass : $inactiveClass }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Maintenance
                    </a>

                    <a href="{{ route('utilization.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('utilization.*') ? $activeClass : $inactiveClass }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Resource Utilization
                    </a>
                    
                    <a href="{{ route('admin.reports.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('admin.reports.*') ? $activeClass : $inactiveClass }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Reports
                    </a>
                    
                    <a href="{{ route('admin.audit-logs.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('admin.audit-logs.*') ? $activeClass : $inactiveClass }}">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Audit Logs
                    </a>
                    @endif
                @endif
            </nav>
            @endauth
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-[260px] flex flex-col min-h-screen">
            <!-- Top Header -->
            <header class="h-16 bg-zinc-950/80 backdrop-blur-md border-b border-zinc-800 flex items-center justify-between px-8 sticky top-0 z-40">
                <div class="flex items-center bg-zinc-900 border border-zinc-800 rounded-lg px-3 h-9 w-80 transition-colors focus-within:border-zinc-700 focus-within:bg-zinc-950 focus-within:ring-2 focus-within:ring-zinc-800/50">
                    <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" placeholder="Search..." class="w-full bg-transparent border-none outline-none pl-2 text-[13px] text-zinc-50 placeholder-zinc-500">
                </div>

                @auth
                <div class="flex items-center gap-4">
                    <!-- Notifications -->
                    <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                        <button class="w-8 h-8 rounded-md flex items-center justify-center text-zinc-400 hover:bg-zinc-900 hover:text-zinc-50 transition-colors relative" @click="open = !open">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <div class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-400 rounded-full border-2 border-zinc-950"></div>
                            @endif
                        </button>
                        
                        <!-- Dropdown -->
                        <div x-show="open" style="display: none;" class="absolute right-0 top-[calc(100%+8px)] w-80 bg-zinc-950 border border-zinc-800 rounded-xl shadow-xl shadow-black/50 z-50 p-1">
                            <div class="p-3 border-b border-zinc-800 font-semibold text-[13px]">
                                Notifications
                            </div>
                            <div class="max-h-[300px] overflow-y-auto">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <div class="p-3 border-b border-zinc-800 last:border-0">
                                        <div class="text-[13px] font-medium text-zinc-50 mb-1">
                                            {{ $notification->data['message'] ?? 'New Notification' }}
                                        </div>
                                        <div class="text-[11px] text-zinc-500 flex justify-between">
                                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                                            <form method="POST" action="{{ route('notifications.mark-as-read', $notification->id) }}" class="m-0">
                                                @csrf
                                                <button type="submit" class="text-zinc-50 bg-transparent border-none cursor-pointer text-[11px] font-medium">Mark Read</button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-6 text-center text-zinc-500 text-[13px]">
                                        No new notifications.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="w-px h-6 bg-zinc-800 mx-1"></div>

                    <!-- User Profile -->
                    <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                        <div class="flex items-center gap-2.5 cursor-pointer py-1 px-2 rounded-lg transition-colors hover:bg-zinc-900" @click="open = !open">
                            <div class="w-8 h-8 rounded-full bg-zinc-800 border border-zinc-700 text-zinc-50 flex items-center justify-center text-[13px] font-semibold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[13px] font-semibold text-zinc-50 leading-none">{{ auth()->user()->name }}</span>
                                <span class="text-[11px] text-zinc-400 mt-1 leading-none">{{ str_replace('_', ' ', auth()->user()->role) }}</span>
                            </div>
                        </div>
                        
                        <div x-show="open" style="display: none;" class="absolute right-0 top-[calc(100%+8px)] w-56 bg-zinc-950 border border-zinc-800 rounded-xl shadow-xl shadow-black/50 z-50 p-1">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-2 text-zinc-400 no-underline text-[13px] font-medium rounded-md transition-colors hover:bg-zinc-900 hover:text-zinc-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Account Settings
                            </a>
                            <div class="border-t border-zinc-800 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 p-2 border-none bg-transparent cursor-pointer text-zinc-400 text-[13px] font-medium rounded-md transition-colors hover:bg-zinc-900 hover:text-zinc-50 text-left">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endauth
            </header>

            <!-- Page Content -->
            <div class="p-8 flex-1">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Toast Notifications -->
    <div class="fixed bottom-6 right-6 z-[100] flex flex-col gap-3" id="toast-container">
        @if(session('success'))
            <div class="toast bg-zinc-950 rounded-lg shadow-lg p-4 flex items-start gap-3 border border-zinc-800 border-l-4 border-l-emerald-400 min-w-[300px]">
                <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <div class="text-[13px] font-semibold text-zinc-50 mb-0.5">Success</div>
                    <div class="text-[13px] text-zinc-400">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="toast bg-zinc-950 rounded-lg shadow-lg p-4 flex items-start gap-3 border border-zinc-800 border-l-4 border-l-red-400 min-w-[300px]">
                <svg class="w-5 h-5 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <div class="text-[13px] font-semibold text-zinc-50 mb-0.5">Error</div>
                    <div class="text-[13px] text-zinc-400">{{ session('error') }}</div>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Toast Animations
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                toast.animate([
                    { transform: 'translateX(100%)', opacity: 0 },
                    { transform: 'translateX(0)', opacity: 1 }
                ], { duration: 300, easing: 'cubic-bezier(0.16, 1, 0.3, 1)', fill: 'forwards' });

                setTimeout(() => {
                    toast.style.transition = 'all 0.3s ease-out';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    setTimeout(() => toast.remove(), 300);
                }, 4000);
            });

            // Intersection Observer for Reveal Animations
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('is-visible');
                        }, index * 100); // Stagger effect
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.05, rootMargin: "0px 0px -20px 0px" });

            document.querySelectorAll('.reveal-up').forEach(el => observer.observe(el));
        });

        // Function to dynamically show toast
        window.showToast = function(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const colorClass = type === 'success' ? 'emerald' : 'red';
            const icon = type === 'success' 
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';

            toast.className = `toast bg-zinc-950 rounded-lg shadow-lg p-4 flex items-start gap-3 border border-zinc-800 border-l-4 border-l-${colorClass}-400 min-w-[300px]`;
            toast.innerHTML = `
                <svg class="w-5 h-5 text-${colorClass}-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">${icon}</svg>
                <div>
                    <div class="text-[13px] font-semibold text-zinc-50 mb-0.5">${type === 'success' ? 'Success' : 'Notification'}</div>
                    <div class="text-[13px] text-zinc-400">${message}</div>
                </div>
            `;
            container.appendChild(toast);
            
            toast.animate([
                { transform: 'translateX(100%)', opacity: 0 },
                { transform: 'translateX(0)', opacity: 1 }
            ], { duration: 300, easing: 'cubic-bezier(0.16, 1, 0.3, 1)', fill: 'forwards' });

            setTimeout(() => {
                toast.style.transition = 'all 0.3s ease-out';
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        };
    </script>
    
    @auth
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Use Reverb/Pusher directly for real-time notifications
        var pusher = new Pusher('{{ env("REVERB_APP_KEY") }}', {
            wsHost: window.location.hostname,
            wsPort: {{ env("REVERB_PORT", 8080) }},
            forceTLS: false,
            disableStats: true,
            cluster: 'mt1', // Ignored by reverb, but required by pusher-js
        });

        var channel = pusher.subscribe('private-App.Models.User.{{ auth()->id() }}');
        channel.bind('BookingStatusUpdated', function(data) {
            window.showToast(data.message, 'success');
        });
    </script>
    @endauth

    @yield('scripts')
</body>
</html>