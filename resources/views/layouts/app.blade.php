<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'CMS') — LaravelCMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
</head>
<body>

<!-- ── Sidebar ──────────────────────────────────────────────────────────────── -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <span class="logo-icon">⚡</span>
            <span class="logo-text">LaravelCMS</span>
        </div>
        <button class="sidebar-close" id="sidebarClose">✕</button>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span><span>Dashboard</span>
        </a>

        <a href="{{ route('articles.index') }}" class="nav-item {{ request()->routeIs('articles.*') ? 'active' : '' }}">
            <span class="nav-icon">📝</span><span>Articles</span>
        </a>

        @if(auth()->user()->hasPermission('manage_categories'))
        <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <span class="nav-icon">🏷️</span><span>Categories</span>
        </a>
        @endif

        @if(auth()->user()->hasPermission('manage_users'))
        <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <span class="nav-icon">👥</span><span>Users</span>
        </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="user-details">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <span class="role-badge {{ auth()->user()->getRoleBadgeColor() }}">
                    {{ auth()->user()->getRoleLabel() }}
                </span>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn" title="Logout">⏏</button>
        </form>
    </div>
</aside>

<!-- ── Overlay ───────────────────────────────────────────────────────────────── -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ── Main ─────────────────────────────────────────────────────────────────── -->
<main class="main-content" id="mainContent">
    <header class="topbar">
        <button class="menu-toggle" id="menuToggle">☰</button>
        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
        <div class="topbar-actions">@yield('topbar-actions')</div>
    </header>

    <!-- Flash messages -->
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            <span>✅</span> {{ session('success') }}
            <button class="alert-close">✕</button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error" role="alert">
            <span>❌</span> {{ session('error') }}
            <button class="alert-close">✕</button>
        </div>
    @endif

    <div class="page-body">
        @yield('content')
    </div>
</main>

<!-- ── Global Modal ──────────────────────────────────────────────────────────── -->
<div class="modal-backdrop" id="deleteModalBackdrop">
    <div class="modal">
        <div class="modal-header">
            <h3>⚠️ Confirm Delete</h3>
            <button class="modal-close" id="deleteModalClose">✕</button>
        </div>
        <p id="deleteModalMessage">Are you sure you want to delete this item?</p>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="deleteModalCancel">Cancel</button>
            <form id="deleteModalForm" method="POST" style="display:inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
