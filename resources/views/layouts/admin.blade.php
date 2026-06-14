<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Velour Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:        #0c0c0e;
            --surface:   #141417;
            --surface2:  #1c1c21;
            --border:    #2a2a32;
            --accent:    #e8c87a;
            --accent-dim:#b89a4a;
            --text:      #f0ede8;
            --muted:     #7a7880;
            --danger:    #e05252;
            --success:   #52c07a;
            --info:      #5282e0;
            --warning:   #e0a452;
            --radius:    10px;
            --sidebar-w: 256px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ── Overlay (mobile) ── */
        #sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 90;
            backdrop-filter: blur(2px);
        }

        #sidebar-overlay.show { display: block; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            transition: transform 0.25s ease;
        }

        .sidebar-logo {
            padding: 22px 20px 18px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-logo h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.5rem;
            color: var(--accent);
            letter-spacing: -0.5px;
        }

        .sidebar-logo span {
            font-size: 0.65rem;
            color: var(--muted);
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .sidebar-close {
            display: none;
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            font-size: 1.2rem;
            padding: 4px;
            border-radius: 6px;
        }

        .sidebar-close:hover { color: var(--text); background: var(--surface2); }

        .sidebar-nav {
            padding: 12px 0;
            flex: 1;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--border) transparent;
        }

        .nav-section {
            padding: 10px 16px 4px;
            font-size: 0.62rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--muted);
            font-weight: 600;
            margin-top: 4px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            margin: 1px 8px;
            border-radius: var(--radius);
            transition: all 0.12s;
            position: relative;
        }

        .nav-item:hover { background: var(--surface2); color: var(--text); }
        .nav-item.active { background: rgba(232,200,122,0.12); color: var(--accent); }
        .nav-item.active .nav-icon { color: var(--accent); }

        .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
            opacity: 0.75;
        }

        .nav-item.active .nav-icon { opacity: 1; }

        .nav-badge {
            margin-left: auto;
            background: rgba(224,82,82,0.2);
            color: var(--danger);
            font-size: 0.65rem;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        .nav-badge.yellow {
            background: rgba(232,200,122,0.15);
            color: var(--accent);
        }

        /* ── Sidebar footer ── */
        .sidebar-footer {
            padding: 12px 12px 14px;
            border-top: 1px solid var(--border);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 10px;
            border-radius: var(--radius);
            text-decoration: none;
            transition: background 0.12s;
            margin-bottom: 8px;
        }

        .user-card:hover { background: var(--surface2); }

        .user-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(232,200,122,0.3), rgba(232,200,122,0.1));
            border: 1px solid rgba(232,200,122,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            color: var(--accent);
            font-weight: 700;
            flex-shrink: 0;
        }

        .user-info { min-width: 0; flex: 1; }

        .user-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-roles {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
            margin-top: 2px;
        }

        .user-role-tag {
            font-size: 0.62rem;
            background: rgba(232,200,122,0.12);
            color: var(--accent);
            padding: 1px 6px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-signout {
            width: 100%;
            background: none;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 8px 12px;
            color: var(--muted);
            font-size: 0.8rem;
            cursor: pointer;
            text-align: left;
            font-family: inherit;
            transition: all 0.15s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-signout:hover { border-color: var(--danger); color: var(--danger); }

        /* ── Main wrap ── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
        }

        /* ── Topbar ── */
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            height: 58px;
            display: flex;
            align-items: center;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-hamburger {
            display: none;
            background: none;
            border: 1px solid var(--border);
            border-radius: 8px;
            width: 34px; height: 34px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--muted);
            font-size: 1rem;
            flex-shrink: 0;
        }

        .topbar-hamburger:hover { border-color: var(--accent); color: var(--accent); }

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            color: var(--muted);
            flex: 1;
            min-width: 0;
        }

        .breadcrumb a { color: var(--muted); text-decoration: none; }
        .breadcrumb a:hover { color: var(--accent); }
        .breadcrumb .crumb-sep { color: var(--border); }
        .breadcrumb .crumb-current { color: var(--text); font-weight: 500; }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        /* Notification bell */
        .notif-btn {
            position: relative;
            width: 34px; height: 34px;
            background: none;
            border: 1px solid var(--border);
            border-radius: 8px;
            cursor: pointer;
            color: var(--muted);
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
        }

        .notif-btn:hover { border-color: var(--accent); color: var(--accent); }

        .notif-dot {
            position: absolute;
            top: 5px; right: 5px;
            width: 7px; height: 7px;
            background: var(--danger);
            border-radius: 50%;
            border: 1.5px solid var(--surface);
        }

        /* Notification dropdown */
        .notif-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 320px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 12px 40px rgba(0,0,0,0.5);
            z-index: 200;
        }

        .notif-dropdown.open { display: block; }

        .notif-header {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            font-size: 0.82rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .notif-item {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            gap: 10px;
            align-items: flex-start;
            transition: background 0.12s;
        }

        .notif-item:hover { background: var(--surface2); }
        .notif-item:last-child { border-bottom: none; }

        .notif-icon {
            width: 28px; height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .notif-text { font-size: 0.8rem; line-height: 1.4; color: var(--text); }
        .notif-time { font-size: 0.72rem; color: var(--muted); margin-top: 2px; }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.15s;
            white-space: nowrap;
        }

        .btn-primary   { background: var(--accent); color: #0c0c0e; }
        .btn-primary:hover { background: #f0d488; }
        .btn-secondary { background: var(--surface2); color: var(--text); border: 1px solid var(--border); }
        .btn-secondary:hover { border-color: var(--accent); color: var(--accent); }
        .btn-danger    { background: rgba(224,82,82,0.15); color: var(--danger); border: 1px solid rgba(224,82,82,0.3); }
        .btn-danger:hover { background: rgba(224,82,82,0.25); }
        .btn-success   { background: rgba(82,192,122,0.15); color: var(--success); border: 1px solid rgba(82,192,122,0.3); }
        .btn-sm        { padding: 6px 12px; font-size: 0.8rem; }
        .btn-icon      { padding: 0; width: 34px; height: 34px; justify-content: center; }

        /* ── Content ── */
        .content { padding: 28px 32px; flex: 1; }

        /* ── Alerts ── */
        .alert {
            padding: 13px 18px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.2s ease;
        }
        @keyframes slideIn { from { transform: translateY(-6px); opacity:0; } to { transform:translateY(0); opacity:1; } }
        .alert-success { background: rgba(82,192,122,0.1); border: 1px solid rgba(82,192,122,0.25); color: var(--success); }
        .alert-error   { background: rgba(224,82,82,0.1);  border: 1px solid rgba(224,82,82,0.25);  color: var(--danger); }

        /* ── Cards ── */
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); }
        .card-header { padding: 18px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .card-title  { font-size: 0.95rem; font-weight: 600; }
        .card-body   { padding: 22px; }

        /* ── Badges ── */
        .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 0.73rem; font-weight: 600; }
        .badge-green  { background: rgba(82,192,122,0.12);  color: var(--success); }
        .badge-red    { background: rgba(224,82,82,0.12);   color: var(--danger); }
        .badge-yellow { background: rgba(232,200,122,0.12); color: var(--accent); }
        .badge-blue   { background: rgba(82,130,224,0.12);  color: var(--info); }
        .badge-purple { background: rgba(150,100,220,0.12); color: #a878e8; }
        .badge-gray   { background: rgba(122,120,128,0.12); color: var(--muted); }

        /* ── Tables ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align:left; padding:11px 16px; font-size:0.72rem; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:var(--muted); border-bottom:1px solid var(--border); }
        td { padding:13px 16px; font-size:0.875rem; border-bottom:1px solid rgba(42,42,50,0.5); vertical-align:middle; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:rgba(255,255,255,0.015); }

        /* ── Forms ── */
        .form-group { margin-bottom: 18px; }
        label { display:block; margin-bottom:6px; font-size:0.83rem; font-weight:500; color:#b0acb8; }
        label .required { color:var(--accent); margin-left:2px; }
        input[type=text], input[type=email], input[type=number], input[type=tel], input[type=password], textarea, select {
            width:100%; background:var(--surface2); border:1px solid var(--border); border-radius:var(--radius);
            color:var(--text); padding:10px 14px; font-size:0.875rem; font-family:'DM Sans',sans-serif;
            transition:border-color 0.15s; outline:none;
        }
        input:focus, textarea:focus, select:focus { border-color:var(--accent); box-shadow:0 0 0 3px rgba(232,200,122,0.08); }
        textarea { resize:vertical; min-height:100px; }
        select option { background:var(--surface2); }
        .form-error { color:var(--danger); font-size:0.78rem; margin-top:5px; }
        .form-check { display:flex; align-items:center; gap:10px; }
        .form-check input[type=checkbox] { width:17px; height:17px; accent-color:var(--accent); cursor:pointer; flex-shrink:0; }
        .form-check label { margin:0; cursor:pointer; font-size:0.875rem; color:var(--text); }
        .form-hint { font-size:0.78rem; color:var(--muted); margin-top:5px; }

        /* ── Stat cards ── */
        .stat-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(190px,1fr)); gap:14px; margin-bottom:24px; }
        .stat-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); padding:18px 20px; position:relative; overflow:hidden; }
        .stat-card::before { content:''; position:absolute; top:-30px; right:-30px; width:90px; height:90px; border-radius:50%; opacity:0.05; }
        .stat-label { font-size:0.72rem; text-transform:uppercase; letter-spacing:1.2px; color:var(--muted); margin-bottom:10px; font-weight:600; }
        .stat-value { font-family:'DM Serif Display',serif; font-size:1.9rem; color:var(--text); line-height:1; }
        .stat-sub   { font-size:0.75rem; color:var(--muted); margin-top:6px; }
        .stat-trend { font-size:0.75rem; margin-top:4px; }
        .stat-trend.up   { color:var(--success); }
        .stat-trend.down { color:var(--danger); }

        /* ── Pagination ── */
        .pagination { display:flex; gap:5px; align-items:center; padding:16px 22px; border-top:1px solid var(--border); }
        .page-link { display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; padding:0 9px; border-radius:7px; font-size:0.82rem; font-weight:500; text-decoration:none; color:var(--muted); background:var(--surface2); border:1px solid var(--border); transition:all 0.15s; }
        .page-link:hover { border-color:var(--accent); color:var(--accent); }
        .page-link.active { background:var(--accent); color:#0c0c0e; border-color:var(--accent); }

        /* ── Filters bar ── */
        .filters-bar { display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-bottom:18px; }
        .filters-bar input, .filters-bar select { width:auto; flex:1; min-width:180px; }

        /* ── Page header ── */
        .page-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:22px; gap:16px; }
        .page-header h2 { font-family:'DM Serif Display',serif; font-size:1.75rem; color:var(--text); }
        .page-header p { color:var(--muted); font-size:0.85rem; margin-top:3px; }
        .page-header-actions { display:flex; gap:8px; align-items:center; flex-shrink:0; }

        /* ── Grid helpers ── */
        .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:18px; }
        .grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:18px; }
        .grid-4 { display:grid; grid-template-columns:repeat(4,1fr); gap:18px; }

        /* ── Image thumb ── */
        .product-thumb { width:40px; height:40px; border-radius:8px; background:var(--surface2); border:1px solid var(--border); display:flex; align-items:center; justify-content:center; color:var(--muted); font-size:1.1rem; overflow:hidden; flex-shrink:0; }
        .product-thumb img { width:100%; height:100%; object-fit:cover; }

        /* ── Section tabs ── */
        .tab-bar { display:flex; gap:2px; border-bottom:1px solid var(--border); margin-bottom:24px; }
        .tab-btn { padding:10px 18px; font-size:0.875rem; font-weight:500; color:var(--muted); background:none; border:none; cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-1px; font-family:inherit; transition:all 0.15s; }
        .tab-btn:hover { color:var(--text); }
        .tab-btn.active { color:var(--accent); border-bottom-color:var(--accent); }
        .tab-panel { display:none; }
        .tab-panel.active { display:block; }

        /* ── Toggle switch ── */
        .toggle-wrap { display:flex; align-items:center; justify-content:space-between; padding:13px 0; border-bottom:1px solid var(--border); }
        .toggle-wrap:last-child { border-bottom:none; }
        .toggle-label { font-size:0.875rem; font-weight:500; }
        .toggle-hint  { font-size:0.78rem; color:var(--muted); margin-top:2px; }
        .toggle { position:relative; width:40px; height:22px; flex-shrink:0; }
        .toggle input { opacity:0; width:0; height:0; }
        .toggle-slider { position:absolute; inset:0; border-radius:22px; background:var(--border); cursor:pointer; transition:background 0.2s; }
        .toggle-slider::before { content:''; position:absolute; left:3px; top:3px; width:16px; height:16px; border-radius:50%; background:#fff; transition:transform 0.2s; }
        .toggle input:checked + .toggle-slider { background:var(--accent); }
        .toggle input:checked + .toggle-slider::before { transform:translateX(18px); }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            .sidebar { transform:translateX(-100%); }
            .sidebar.open { transform:translateX(0); }
            .sidebar-close { display:flex; }
            .main-wrap { margin-left:0; }
            .topbar-hamburger { display:flex; }
            .grid-2, .grid-3, .grid-4 { grid-template-columns:1fr; }
            .content { padding:20px 16px; }
            .topbar { padding:0 16px; }
        }
    </style>
    @stack('styles')
</head>
<body>

<div id="sidebar-overlay"></div>

<!-- ── Sidebar ── -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div>
            <h1>Velour</h1>
            <span>Admin Panel</span>
        </div>
        <button class="sidebar-close" id="sidebar-close" aria-label="Close sidebar">✕</button>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Overview</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon">⊞</span> Dashboard
        </a>

        <div class="nav-section" style="margin-top:8px">Catalog</div>
        <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <span class="nav-icon">◈</span> Products
        </a>
        <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <span class="nav-icon">◉</span> Categories
        </a>

        <div class="nav-section" style="margin-top:8px">Sales</div>
        <a href="{{ route('orders.index') }}" class="nav-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <span class="nav-icon">◷</span> Orders
            @php $pending = \App\Models\Order::where('status','pending')->count(); @endphp
            @if($pending > 0)
                <span class="nav-badge">{{ $pending }}</span>
            @endif
        </a>
        <a href="{{ route('admin.reports') }}" class="nav-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
            <span class="nav-icon">◱</span> Reports
        </a>

        @if(auth()->user()->isAdmin())
        <div class="nav-section" style="margin-top:8px">People</div>
        <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <span class="nav-icon">◎</span> Users
        </a>
        @endif

        <div class="nav-section" style="margin-top:8px">System</div>
        <a href="{{ route('admin.activity') }}" class="nav-item {{ request()->routeIs('admin.activity') ? 'active' : '' }}">
            <span class="nav-icon">◳</span> Activity Log
        </a>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.settings') }}" class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <span class="nav-icon">⊙</span> Settings
        </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <a href="{{ route('admin.profile') }}" class="user-card">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-roles">
                    @foreach(auth()->user()->roles as $r)
                        <span class="user-role-tag">{{ $r->label }}</span>
                    @endforeach
                </div>
            </div>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-signout">
                <span>⎋</span> Sign out
            </button>
        </form>
    </div>
</aside>

<!-- ── Main ── -->
<div class="main-wrap">

    <!-- Topbar -->
    <header class="topbar">
        <button class="topbar-hamburger" id="topbar-hamburger" aria-label="Open menu">☰</button>

        <!-- Breadcrumb -->
        <nav class="breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('dashboard') }}">Velour</a>
            @hasSection('breadcrumb')
                <span class="crumb-sep">›</span>
                @yield('breadcrumb')
            @else
                <span class="crumb-sep">›</span>
                <span class="crumb-current">@yield('topbar-title', 'Dashboard')</span>
            @endif
        </nav>

        <!-- Global search -->
        <div style="position:relative;margin-left:auto" id="global-search-wrap">
            <div style="display:flex;align-items:center;background:var(--surface2);border:1px solid var(--border);border-radius:50px;padding:6px 14px 6px 10px;gap:7px;transition:border-color 0.15s" id="global-search-box">
                <span style="color:var(--muted);font-size:0.85rem;flex-shrink:0">🔍</span>
                <input type="text" id="global-search-input" placeholder="Search products, orders…"
                    style="background:none;border:none;outline:none;color:var(--text);font-family:'DM Sans',sans-serif;font-size:0.82rem;width:180px"
                    autocomplete="off">
            </div>
            <div id="global-search-results" style="display:none;position:absolute;top:calc(100% + 8px);left:0;right:0;min-width:300px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:0 12px 40px rgba(0,0,0,0.5);z-index:300;overflow:hidden">
                <div id="search-results-inner" style="max-height:360px;overflow-y:auto"></div>
            </div>
        </div>

        <div class="topbar-right" style="margin-left:12px">
            <!-- Notification bell -->
            <div style="position:relative">
                <button class="notif-btn" id="notif-btn" aria-label="Notifications">
                    🔔
                    @php
                        $lowStock  = \App\Models\Product::where('stock', '<=', 5)->where('stock', '>', 0)->count();
                        $outStock  = \App\Models\Product::where('stock', 0)->count();
                        $pendingOrd= \App\Models\Order::where('status','pending')->count();
                        $totalNotif= $lowStock + $outStock + $pendingOrd;
                    @endphp
                    @if($totalNotif > 0)
                        <span class="notif-dot"></span>
                    @endif
                </button>

                <div class="notif-dropdown" id="notif-dropdown">
                    <div class="notif-header">
                        <span>Notifications</span>
                        <span class="badge badge-red">{{ $totalNotif }}</span>
                    </div>

                    @if($pendingOrd > 0)
                    <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="notif-item" style="text-decoration:none">
                        <div class="notif-icon" style="background:rgba(232,200,122,0.12);color:var(--accent)">◷</div>
                        <div>
                            <div class="notif-text">{{ $pendingOrd }} pending order{{ $pendingOrd > 1 ? 's' : '' }} need attention</div>
                            <div class="notif-time">Requires action</div>
                        </div>
                    </a>
                    @endif

                    @if($lowStock > 0)
                    <a href="{{ route('products.index', ['status' => 'active']) }}" class="notif-item" style="text-decoration:none">
                        <div class="notif-icon" style="background:rgba(224,160,82,0.12);color:var(--warning)">◈</div>
                        <div>
                            <div class="notif-text">{{ $lowStock }} product{{ $lowStock > 1 ? 's' : '' }} running low on stock</div>
                            <div class="notif-time">5 or fewer units remaining</div>
                        </div>
                    </a>
                    @endif

                    @if($outStock > 0)
                    <a href="{{ route('products.index') }}" class="notif-item" style="text-decoration:none">
                        <div class="notif-icon" style="background:rgba(224,82,82,0.12);color:var(--danger)">◈</div>
                        <div>
                            <div class="notif-text">{{ $outStock }} product{{ $outStock > 1 ? 's' : '' }} out of stock</div>
                            <div class="notif-time">Restock required</div>
                        </div>
                    </a>
                    @endif

                    @if($totalNotif === 0)
                    <div class="notif-item" style="justify-content:center;color:var(--muted);font-size:0.82rem;padding:20px">
                        All clear — no alerts!
                    </div>
                    @endif
                </div>
            </div>

            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">+ New Product</a>
        </div>
    </header>

    <!-- Page content -->
    <main class="content">
        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">✕ {{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</div>

<script>
// ── Sidebar toggle ────────────────────────────────────────────────────────────
const sidebar  = document.getElementById('sidebar');
const overlay  = document.getElementById('sidebar-overlay');
const openBtn  = document.getElementById('topbar-hamburger');
const closeBtn = document.getElementById('sidebar-close');

function openSidebar()  { sidebar.classList.add('open'); overlay.classList.add('show'); }
function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('show'); }

openBtn?.addEventListener('click', openSidebar);
closeBtn?.addEventListener('click', closeSidebar);
overlay?.addEventListener('click', closeSidebar);

// ── Notification dropdown ─────────────────────────────────────────────────────
const notifBtn = document.getElementById('notif-btn');
const notifDD  = document.getElementById('notif-dropdown');

notifBtn?.addEventListener('click', e => {
    e.stopPropagation();
    notifDD.classList.toggle('open');
});

document.addEventListener('click', () => notifDD?.classList.remove('open'));

// ── Alert auto-dismiss ────────────────────────────────────────────────────────
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => {
        el.style.transition = 'opacity 0.4s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 400);
    });
}, 4500);

// ── Confirm-on-delete ─────────────────────────────────────────────────────────
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', function(e) {
        if (!confirm(this.dataset.confirm || 'Are you sure?')) e.preventDefault();
    });
});

// ── Tab system ────────────────────────────────────────────────────────────────
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const group = btn.closest('[data-tabs]') || btn.parentElement.parentElement;
        group.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        group.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        const target = btn.dataset.tab;
        document.getElementById(target)?.classList.add('active');
    });
});

// ── Global search ─────────────────────────────────────────────────────────────
(function() {
    const input   = document.getElementById('global-search-input');
    const box     = document.getElementById('global-search-box');
    const results = document.getElementById('global-search-results');
    const inner   = document.getElementById('search-results-inner');
    if (!input) return;

    const typeColors = {
        product:  { icon: '◈', bg: 'rgba(232,200,122,0.1)',  color: '#e8c87a' },
        order:    { icon: '◷', bg: 'rgba(82,130,224,0.1)',   color: '#7aabf0' },
        user:     { icon: '◎', bg: 'rgba(150,100,220,0.1)',  color: '#c090f0' },
        category: { icon: '◉', bg: 'rgba(82,192,122,0.1)',   color: '#52c07a' },
    };

    let debounce;

    input.addEventListener('focus', () => {
        box.style.borderColor = 'var(--accent)';
        if (inner.innerHTML) results.style.display = 'block';
    });

    input.addEventListener('input', () => {
        clearTimeout(debounce);
        const q = input.value.trim();
        if (q.length < 2) { results.style.display = 'none'; return; }

        debounce = setTimeout(async () => {
            inner.innerHTML = '<div style="padding:14px 16px;color:var(--muted);font-size:0.82rem">Searching…</div>';
            results.style.display = 'block';

            try {
                const res  = await fetch('/admin/search?q=' + encodeURIComponent(q), {
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' }
                });
                const data = await res.json();

                if (!data.results.length) {
                    inner.innerHTML = '<div style="padding:20px 16px;color:var(--muted);font-size:0.82rem;text-align:center">No results for "<strong style=\'color:var(--text)\'>' + q + '</strong>"</div>';
                    return;
                }

                // Group by type
                const groups = {};
                data.results.forEach(r => { (groups[r.type] = groups[r.type] || []).push(r); });

                let html = '';
                Object.entries(groups).forEach(([type, items]) => {
                    const tc = typeColors[type] || { bg: 'var(--surface2)', color: 'var(--muted)' };
                    const label = type.charAt(0).toUpperCase() + type.slice(1) + 's';
                    html += '<div style="padding:8px 14px 4px;font-size:0.65rem;letter-spacing:1.5px;text-transform:uppercase;color:var(--muted);font-weight:700;border-top:1px solid var(--border)">' + label + '</div>';
                    items.forEach(item => {
                        html += '<a href="' + item.url + '" style="display:flex;align-items:center;gap:12px;padding:10px 14px;text-decoration:none;transition:background 0.1s;border-bottom:1px solid rgba(42,42,50,0.4)" onmouseover="this.style.background=\'var(--surface2)\'" onmouseout="this.style.background=\'\'">'+
                            '<div style="width:32px;height:32px;border-radius:8px;background:' + tc.bg + ';color:' + tc.color + ';display:flex;align-items:center;justify-content:center;font-size:0.9rem;flex-shrink:0">' + tc.icon + '</div>'+
                            '<div style="flex:1;min-width:0">'+
                                '<div style="font-size:0.85rem;font-weight:500;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">' + item.label + '</div>'+
                                '<div style="font-size:0.73rem;color:var(--muted);margin-top:1px">' + item.sublabel + '</div>'+
                            '</div>'+
                            '<div style="text-align:right;flex-shrink:0">'+
                                '<div style="font-size:0.78rem;color:var(--accent);font-weight:600">' + (item.meta || '') + '</div>'+
                                (item.badge ? '<div style="font-size:0.65rem;color:var(--muted);margin-top:2px">' + item.badge + '</div>' : '')+
                            '</div>'+
                        '</a>';
                    });
                });

                inner.innerHTML = html;
            } catch(e) {
                inner.innerHTML = '<div style="padding:14px 16px;color:var(--danger);font-size:0.82rem">Search error. Please try again.</div>';
            }
        }, 280);
    });

    // Close on outside click
    document.addEventListener('click', e => {
        if (!document.getElementById('global-search-wrap')?.contains(e.target)) {
            results.style.display = 'none';
            box.style.borderColor = 'var(--border)';
        }
    });

    // Keyboard: Escape closes, Enter follows first result
    input.addEventListener('keydown', e => {
        if (e.key === 'Escape') { results.style.display = 'none'; input.blur(); }
        if (e.key === 'Enter') {
            const first = inner.querySelector('a');
            if (first) { window.location = first.href; }
        }
    });

    // Cmd/Ctrl+K to focus search
    document.addEventListener('keydown', e => {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            input.focus();
            input.select();
        }
    });
})();
</script>
@stack('scripts')
</body>
</html>
