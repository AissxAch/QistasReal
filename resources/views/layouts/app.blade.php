<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قسطاس — @yield('title', 'لوحة التحكم')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* ======= RESET & BASE ======= */
        *, *::before, *::after { box-sizing: border-box; }
        [x-cloak] { display: none !important; }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f0f4fa;
            background-image:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(28, 91, 184, 0.07) 0%, transparent 60%);
            min-height: 100vh;
        }

        /* ======= SIDEBAR ======= */
        .sidebar {
            width: 260px;
            background: #fff;
            border-left: 1px solid rgba(15, 45, 98, 0.06);
            box-shadow: -1px 0 20px rgba(15, 45, 98, 0.04);
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: sticky;
            top: 0;
            overflow: hidden;
        }

        .sidebar-logo {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(15, 45, 98, 0.06);
            display: flex;
            align-items: center;
        }
        .sidebar-logo img {
            height: 100%;
            width: auto;
            object-fit: contain;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 1.25rem 0.875rem;
            display: flex;
            flex-direction: column;
            gap: 1.75rem;
        }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

        .nav-group-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            padding: 0 0.625rem;
            margin-bottom: 0.375rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.75rem;
            border-radius: 10px;
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
            position: relative;
        }
        .nav-link:hover {
            background: #f1f5fb;
            color: #1c5bb8;
        }
        .nav-link.active {
            background: linear-gradient(135deg, #eef3fb, #e8effa);
            color: #1c5bb8;
            font-weight: 700;
            box-shadow: 0 1px 4px rgba(28, 91, 184, 0.08);
        }
        .nav-link.active::before {
            content: '';
            position: absolute;
            right: 0;
            top: 25%;
            height: 50%;
            width: 3px;
            background: #1c5bb8;
            border-radius: 2px 0 0 2px;
        }
        .nav-link-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            flex-shrink: 0;
            background: transparent;
            transition: background 0.15s ease;
        }
        .nav-link:hover .nav-link-icon,
        .nav-link.active .nav-link-icon {
            background: rgba(28, 91, 184, 0.1);
        }

        /* User footer */
        .sidebar-user {
            padding: 1rem 1.125rem;
            border-top: 1px solid rgba(15, 45, 98, 0.06);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #0f2d62, #1c5bb8);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.875rem;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(15, 45, 98, 0.2);
        }
        .user-name {
            font-size: 0.8125rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
        }
        .user-email {
            font-size: 0.7rem;
            color: #94a3b8;
            margin-top: 2px;
        }
        .logout-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            color: #94a3b8;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: all 0.15s ease;
            margin-right: auto;
        }
        .logout-btn:hover {
            background: #fee2e2;
            color: #ef4444;
        }

        /* ======= TOPBAR ======= */
        .topbar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(15, 45, 98, 0.06);
            position: sticky;
            top: 0;
            z-index: 40;
        }
        .topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.875rem 1.75rem;
            gap: 1rem;
        }

        /* Search */
        .search-wrapper {
            position: relative;
            flex: 1;
            max-width: 380px;
        }
        .search-input {
            width: 100%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.5625rem 2.5rem 0.5625rem 1rem;
            font-size: 0.8125rem;
            color: #1e293b;
            outline: none;
            font-family: 'Cairo', sans-serif;
            transition: all 0.2s ease;
        }
        .search-input:focus {
            border-color: #1c5bb8;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(28, 91, 184, 0.1);
        }
        .search-icon {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.75rem;
            pointer-events: none;
        }

        /* Action buttons */
        .topbar-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
            cursor: pointer;
            transition: all 0.15s ease;
            position: relative;
        }
        .topbar-btn:hover {
            background: #fff;
            border-color: #cbd5e1;
            color: #1c5bb8;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .notif-dot {
            position: absolute;
            top: 8px;
            left: 8px;
            width: 7px;
            height: 7px;
            background: #f59e0b;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        /* Quick actions dropdown */
        .quick-action-menu {
            position: absolute;
            left: 0;
            top: calc(100% + 8px);
            width: 220px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(15, 45, 98, 0.12);
            padding: 0.5rem;
            z-index: 50;
        }
        .quick-action-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.875rem;
            border-radius: 9px;
            font-size: 0.8125rem;
            color: #374151;
            text-decoration: none;
            transition: background 0.15s ease;
        }
        .quick-action-item:hover { background: #f8fafc; }
        .quick-action-icon {
            width: 28px;
            height: 28px;
            background: #eef3fb;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1c5bb8;
            font-size: 0.75rem;
        }

        /* Notifications dropdown */
        .notif-menu {
            position: absolute;
            left: 0;
            top: calc(100% + 8px);
            width: 320px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(15, 45, 98, 0.12);
            overflow: hidden;
            z-index: 50;
        }
        .notif-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.125rem;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Mobile sidebar overlay */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 45, 98, 0.3);
            backdrop-filter: blur(2px);
            z-index: 49;
        }
        .sidebar-mobile {
            position: fixed;
            inset-y: 0;
            right: 0;
            z-index: 50;
            width: 260px;
        }

        /* ======= PAGE TRANSITIONS ======= */
        .page-content {
            animation: fadeInUp 0.25s ease both;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ======= SCROLLBAR ======= */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body>
@php
    $subscriptionLocked = (bool) ($subscriptionAccessLocked ?? false);
    $allowFullNavigation = auth()->check() && (! $subscriptionLocked || auth()->user()->isAdmin());
@endphp

<div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

    {{-- ===== MOBILE OVERLAY ===== --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="sidebar-overlay lg:hidden"></div>

    {{-- ===== SIDEBAR (DESKTOP — RTL right side) ===== --}}
    <aside class="sidebar hidden lg:flex flex-col">
        {{-- Logo --}}
        <div class="sidebar-logo">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/fulllogo.png') }}" alt="قسطاس" class="h-10 w-auto object-contain">
            </a>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">
            @if($allowFullNavigation)
                {{-- Main --}}
                <div>
                    <div class="nav-group-label">الرئيسية</div>
                    <div class="space-y-0.5 mt-1">
                        <a href="{{ route('dashboard') }}"
                           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <span class="nav-link-icon"><i class="fas fa-chart-line"></i></span>
                            <span>لوحة التحكم</span>
                        </a>
                    </div>
                </div>

                {{-- Operations --}}
                <div>
                    <div class="nav-group-label">التشغيل</div>
                    <div class="space-y-0.5 mt-1">
                        <a href="{{ route('cases.index') }}"
                           class="nav-link {{ request()->routeIs('cases.*') ? 'active' : '' }}">
                            <span class="nav-link-icon"><i class="fas fa-scale-balanced"></i></span>
                            <span>القضايا</span>
                        </a>
                        <a href="{{ route('clients.index') }}"
                           class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                            <span class="nav-link-icon"><i class="fas fa-users"></i></span>
                            <span>العملاء</span>
                        </a>
                        <a href="{{ route('sessions.index') }}"
                           class="nav-link {{ request()->routeIs('sessions.*') ? 'active' : '' }}">
                            <span class="nav-link-icon"><i class="fas fa-calendar-days"></i></span>
                            <span>الجلسات</span>
                        </a>
                        <a href="{{ route('calendar') }}"
                           class="nav-link {{ request()->routeIs('calendar') ? 'active' : '' }}">
                            <span class="nav-link-icon"><i class="fas fa-calendar-week"></i></span>
                            <span>التقويم</span>
                        </a>
                        <a href="{{ route('tasks.index') }}"
                           class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                            <span class="nav-link-icon"><i class="fas fa-list-check"></i></span>
                            <span>المهام</span>
                        </a>
                    </div>
                </div>

                @if(Auth::user()->isOwner())
                    {{-- Management --}}
                    <div>
                        <div class="nav-group-label">الإدارة</div>
                        <div class="space-y-0.5 mt-1">
                            <a href="{{ route('team.index') }}"
                               class="nav-link {{ request()->routeIs('team.*') ? 'active' : '' }}">
                                <span class="nav-link-icon"><i class="fas fa-user-group"></i></span>
                                <span>الفريق</span>
                            </a>
                            <a href="{{ route('settings.firm') }}"
                               class="nav-link {{ request()->routeIs('settings.firm*') ? 'active' : '' }}">
                                <span class="nav-link-icon"><i class="fas fa-building"></i></span>
                                <span>إدارة المكتب</span>
                            </a>
                            <a href="{{ route('logs.index') }}"
                               class="nav-link {{ request()->routeIs('logs.*') ? 'active' : '' }}">
                                <span class="nav-link-icon"><i class="fas fa-clipboard-list"></i></span>
                                <span>السجلات</span>
                            </a>
                        </div>
                    </div>
                @endif
            @else
                <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800 leading-6">
                    تم تجميد الأقسام التشغيلية مؤقتًا حتى تسوية الاشتراك.
                </div>
            @endif

            @if(Auth::user()->isAdmin())
                <div>
                    <div class="nav-group-label">الإدارة العامة</div>
                    <div class="space-y-0.5 mt-1">
                        <a href="{{ route('admin.subscriptions.index') }}"
                           class="nav-link {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                            <span class="nav-link-icon"><i class="fas fa-building-shield"></i></span>
                            <span>اشتراكات المنصة</span>
                        </a>
                    </div>
                </div>
            @endif

            @if($allowFullNavigation || Auth::user()->isAdmin())
                {{-- Settings --}}
                <div>
                    <div class="nav-group-label">الإعدادات</div>
                    <div class="space-y-0.5 mt-1">
                        <a href="{{ route('subscription') }}"
                                  class="nav-link {{ request()->routeIs('subscription*') ? 'active' : '' }}">
                            <span class="nav-link-icon"><i class="fas fa-credit-card"></i></span>
                            <span>الاشتراك</span>
                        </a>
                        <a href="{{ route('settings.profile') }}"
                           class="nav-link {{ request()->routeIs('settings.profile*') ? 'active' : '' }}">
                            <span class="nav-link-icon"><i class="fas fa-user-circle"></i></span>
                            <span>الملف الشخصي</span>
                        </a>
                    </div>
                </div>
            @endif
        </nav>

        {{-- User --}}
        <div class="sidebar-user">
            <x-user-avatar :user="Auth::user()" size="sm" />
            <div class="flex-1 min-w-0">
                <div class="user-name truncate">{{ Auth::user()->name }}</div>
                <div class="user-email truncate">{{ Auth::user()->email }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn" title="تسجيل الخروج">
                    <i class="fas fa-sign-out-alt text-sm"></i>
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MOBILE SIDEBAR ===== --}}
    <aside x-show="sidebarOpen" x-cloak
           x-transition:enter="transition ease-out duration-250"
           x-transition:enter-start="translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="translate-x-full"
           class="sidebar sidebar-mobile lg:hidden flex flex-col">
        {{-- Logo --}}
        <div class="sidebar-logo justify-between">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/logo.png') }}" alt="قسطاس" class="h-9 w-auto object-contain">
            </a>
            <button @click="sidebarOpen = false" class="text-gray-400 hover:text-gray-600 p-1">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            @if($allowFullNavigation)
                <div>
                    <div class="nav-group-label">الرئيسية</div>
                    <div class="space-y-0.5 mt-1">
                        <a href="{{ route('dashboard') }}" @click="sidebarOpen = false"
                           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <span class="nav-link-icon"><i class="fas fa-chart-line"></i></span>
                            <span>لوحة التحكم</span>
                        </a>
                    </div>
                </div>

                <div>
                    <div class="nav-group-label">التشغيل</div>
                    <div class="space-y-0.5 mt-1">
                        <a href="{{ route('cases.index') }}" @click="sidebarOpen = false"
                           class="nav-link {{ request()->routeIs('cases.*') ? 'active' : '' }}">
                            <span class="nav-link-icon"><i class="fas fa-scale-balanced"></i></span>
                            <span>القضايا</span>
                        </a>
                        <a href="{{ route('clients.index') }}" @click="sidebarOpen = false"
                           class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                            <span class="nav-link-icon"><i class="fas fa-users"></i></span>
                            <span>العملاء</span>
                        </a>
                        <a href="{{ route('sessions.index') }}" @click="sidebarOpen = false"
                           class="nav-link {{ request()->routeIs('sessions.*') ? 'active' : '' }}">
                            <span class="nav-link-icon"><i class="fas fa-calendar-days"></i></span>
                            <span>الجلسات</span>
                        </a>
                        <a href="{{ route('calendar') }}" @click="sidebarOpen = false"
                           class="nav-link {{ request()->routeIs('calendar') ? 'active' : '' }}">
                            <span class="nav-link-icon"><i class="fas fa-calendar-week"></i></span>
                            <span>التقويم</span>
                        </a>
                        <a href="{{ route('tasks.index') }}" @click="sidebarOpen = false"
                           class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                            <span class="nav-link-icon"><i class="fas fa-list-check"></i></span>
                            <span>المهام</span>
                        </a>
                    </div>
                </div>
                @if(Auth::user()->isOwner())
                    <div>
                        <div class="nav-group-label">الإدارة</div>
                        <div class="space-y-0.5 mt-1">
                            <a href="{{ route('team.index') }}" @click="sidebarOpen = false"
                               class="nav-link {{ request()->routeIs('team.*') ? 'active' : '' }}">
                                <span class="nav-link-icon"><i class="fas fa-user-group"></i></span>
                                <span>الفريق</span>
                            </a>
                            <a href="{{ route('settings.firm') }}" @click="sidebarOpen = false"
                               class="nav-link {{ request()->routeIs('settings.firm*') ? 'active' : '' }}">
                                <span class="nav-link-icon"><i class="fas fa-building"></i></span>
                                <span>إدارة المكتب</span>
                            </a>
                            <a href="{{ route('logs.index') }}" @click="sidebarOpen = false"
                               class="nav-link {{ request()->routeIs('logs.*') ? 'active' : '' }}">
                                <span class="nav-link-icon"><i class="fas fa-clipboard-list"></i></span>
                                <span>السجلات</span>
                            </a>
                        </div>
                    </div>
                @endif
            @else
                <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800 leading-6">
                    الوصول محصور حالياً في الاشتراك والإعدادات فقط.
                </div>
            @endif
            @if(Auth::user()->isAdmin())
                <div>
                    <div class="nav-group-label">الإدارة العامة</div>
                    <div class="space-y-0.5 mt-1">
                        <a href="{{ route('admin.subscriptions.index') }}" @click="sidebarOpen = false"
                           class="nav-link {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                            <span class="nav-link-icon"><i class="fas fa-building-shield"></i></span>
                            <span>اشتراكات المنصة</span>
                        </a>
                    </div>
                </div>
            @endif
            @if($allowFullNavigation || Auth::user()->isAdmin())
                <div>
                    <div class="nav-group-label">الإعدادات</div>
                    <div class="space-y-0.5 mt-1">
                        <a href="{{ route('subscription') }}" @click="sidebarOpen = false"
                                  class="nav-link {{ request()->routeIs('subscription*') ? 'active' : '' }}">
                            <span class="nav-link-icon"><i class="fas fa-credit-card"></i></span>
                            <span>الاشتراك</span>
                        </a>
                        <a href="{{ route('settings.profile') }}" @click="sidebarOpen = false"
                           class="nav-link {{ request()->routeIs('settings.profile*') ? 'active' : '' }}">
                            <span class="nav-link-icon"><i class="fas fa-user-circle"></i></span>
                            <span>الملف الشخصي</span>
                        </a>
                    </div>
                </div>
            @endif
        </nav>

        <div class="sidebar-user">
            <x-user-avatar :user="Auth::user()" size="sm" />
            <div class="flex-1 min-w-0">
                <div class="user-name truncate">{{ Auth::user()->name }}</div>
                <div class="user-email truncate">{{ Auth::user()->email }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt text-sm"></i>
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN AREA ===== --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-auto">

        {{-- Topbar --}}
        <header class="topbar">
            <div class="topbar-inner">

                {{-- Mobile menu toggle --}}
                <button @click="sidebarOpen = true" class="topbar-btn lg:hidden">
                    <i class="fas fa-bars text-sm"></i>
                </button>

                {{-- Page Title (mobile) --}}
                <div class="flex items-center gap-2 lg:hidden">
                    <img src="{{ asset('assets/logo.png') }}" alt="قسطاس" class="h-8 w-auto object-contain">
                </div>

                {{-- Search --}}
                @if($allowFullNavigation)
                    <form method="GET" action="{{ route('search.index') }}" class="search-wrapper hidden md:block">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="q" value="{{ request('q') }}" class="search-input" placeholder="ابحث في القضايا، العملاء، المهام...">
                    </form>
                @endif

                {{-- Actions --}}
                <div class="flex items-center gap-2 mr-auto">

                    {{-- Quick Add --}}
                    @if($allowFullNavigation)
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="topbar-btn">
                                <i class="fas fa-plus text-sm"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak class="quick-action-menu">
                                <p class="text-xs font-semibold text-gray-400 px-3 py-2 uppercase tracking-wider">إضافة سريعة</p>
                                <a href="{{ route('cases.create') }}" class="quick-action-item">
                                    <span class="quick-action-icon"><i class="fas fa-scale-balanced"></i></span>
                                    قضية جديدة
                                </a>
                                <a href="{{ route('clients.create') }}" class="quick-action-item">
                                    <span class="quick-action-icon"><i class="fas fa-user-plus"></i></span>
                                    عميل جديد
                                </a>
                                <a href="{{ route('tasks.create') }}" class="quick-action-item">
                                    <span class="quick-action-icon"><i class="fas fa-list-check"></i></span>
                                    مهمة جديدة
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Notifications --}}
                    <div x-data="{ open: false }" class="relative">
                        @php
                            $recentNotifications = auth()->check()
                                ? \App\Services\NotificationService::getUnread(auth()->user(), 10)
                                : collect();
                        @endphp
                        <button @click="open = !open" class="topbar-btn">
                            <i class="fas fa-bell text-sm"></i>
                            <span class="notif-dot" x-show="{{ $unreadNotifCount ?? 0 }} > 0"></span>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="notif-menu">
                            <div class="notif-header">
                                <span class="font-bold text-gray-800 text-sm">الإشعارات</span>
                                <span class="text-xs bg-amber-100 text-amber-700 font-semibold px-2 py-0.5 rounded-full">{{ $unreadNotifCount ?? 0 }}</span>
                            </div>

                            @if($recentNotifications->isNotEmpty())
                                <div class="max-h-72 overflow-y-auto divide-y divide-gray-100">
                                    @foreach($recentNotifications as $notification)
                                        <div class="px-4 py-3 hover:bg-gray-50 transition">
                                            <p class="text-sm font-semibold text-gray-800">{{ $notification->title }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ \Illuminate\Support\Str::limit($notification->body ?? '', 80) }}</p>
                                            <p class="text-[11px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                @if($allowFullNavigation)
                                    <div class="p-3 border-t border-gray-100 flex items-center justify-between gap-2">
                                        <form method="POST" action="{{ route('notifications.mark-read') }}">
                                            @csrf
                                            <button type="submit" class="text-xs text-[#1c5bb8] font-semibold hover:underline">تحديد الكل كمقروء</button>
                                        </form>
                                        <a href="{{ route('notifications.index') }}" class="text-xs text-gray-500 hover:text-gray-700">عرض الكل</a>
                                    </div>
                                @endif
                            @else
                                <div class="py-8 text-center text-gray-400">
                                    <i class="fas fa-bell-slash text-2xl mb-2 block opacity-40"></i>
                                    <p class="text-xs">لا توجد إشعارات جديدة</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- User (desktop) --}}
                    <div x-data="{ open: false }" class="relative hidden lg:block">
                        <button @click="open = !open" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl hover:bg-gray-100 transition">
                            <x-user-avatar :user="Auth::user()" size="xs" class="gap-0" />
                            <div class="text-right hidden xl:block">
                                <p class="text-xs font-bold text-gray-800 leading-tight">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-400 leading-tight">محامي</p>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-xs transition" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute left-0 top-full mt-2 w-48 bg-white border border-gray-100 rounded-2xl shadow-xl overflow-hidden z-50 py-1">
                            <a href="{{ route('settings.profile') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <i class="fas fa-user-circle w-4 text-gray-400"></i>
                                الملف الشخصي
                            </a>
                            @if(Auth::user()->isOwner())
                            <a href="{{ route('settings.firm') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <i class="fas fa-building w-4 text-gray-400"></i>
                                إعدادات المكتب
                            </a>
                            @endif
                            <a href="{{ route('subscription') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <i class="fas fa-credit-card w-4 text-gray-400"></i>
                                الاشتراك
                            </a>
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.subscriptions.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                    <i class="fas fa-building-shield w-4 text-gray-400"></i>
                                    اشتراكات المنصة
                                </a>
                            @endif
                            <div class="my-1 border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2.5 w-full text-right px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 transition">
                                    <i class="fas fa-sign-out-alt w-4"></i>
                                    تسجيل الخروج
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 p-6 lg:p-8 page-content">
            @if($subscriptionLocked && auth()->check() && !auth()->user()->isAdmin())
                <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800">
                    <div class="font-extrabold mb-1">تنبيه اشتراك</div>
                    <div>تم تقييد الوصول حالياً. انتقل إلى <a href="{{ route('access.locked') }}" class="font-extrabold underline">صفحة حالة الوصول</a> لمعرفة الإجراء المطلوب.</div>
                </div>
            @endif
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>