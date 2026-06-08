<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Velour Admin</title>

    {{-- Google Fonts --}}
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
            --sidebar-w: 260px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

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
        }

        .sidebar-logo {
            padding: 28px 24px 20px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-logo h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.6rem;
            color: var(--accent);
            letter-spacing: -0.5px;
        }

        .sidebar-logo span {
            font-size: 0.7rem;
            color: var(--muted);
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 500;
        }

        .sidebar-nav {
            padding: 16px 0;
            flex: 1;
            overflow-y: auto;
        }

        .nav-section {
            padding: 8px 16px 4px;
            font-size: 0.65rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--muted);
            font-weight: 600;
            margin-top: 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 0;
            margin: 1px 8px;
            border-radius: var(--radius);
            transition: all 0.15s;
        }

        .nav-item:hover { background: var(--surface2); color: var(--text); }
        .nav-item.active { background: rgba(232,200,122,0.12); color: var(--accent); }
        .nav-item.active .nav-icon { color: var(--accent); }
        .nav-icon { width: 18px; text-align: center; opacity: 0.8; }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
            font-size: 0.8rem;
            color: var(--muted);
        }

        /* ── Main ── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-size: 1rem;
            font-weight: 500;
            color: var(--text);
        }

        .topbar-actions { display: flex; gap: 12px; align-items: center; }

        .content {
            padding: 32px;
            flex: 1;
        }

        /* ── Components ── */

        /* Alerts */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success { background: rgba(82,192,122,0.12); border: 1px solid rgba(82,192,122,0.3); color: var(--success); }
        .alert-error   { background: rgba(224,82,82,0.12);  border: 1px solid rgba(224,82,82,0.3);  color: var(--danger); }

        /* Cards */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
        }
        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-title {
            font-size: 1rem;
            font-weight: 600;
        }
        .card-body { padding: 24px; }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
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
        .btn-icon      { padding: 8px; width: 34px; height: 34px; justify-content: center; }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .badge-green  { background: rgba(82,192,122,0.15);  color: var(--success); }
        .badge-red    { background: rgba(224,82,82,0.15);   color: var(--danger); }
        .badge-yellow { background: rgba(232,200,122,0.15); color: var(--accent); }
        .badge-blue   { background: rgba(82,130,224,0.15);  color: var(--info); }
        .badge-purple { background: rgba(150,100,220,0.15); color: #a070e0; }
        .badge-gray   { background: rgba(122,120,128,0.15); color: var(--muted); }

        /* Tables */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th {
            text-align: left;
            padding: 12px 16px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--muted);
            border-bottom: 1px solid var(--border);
        }
        td {
            padding: 14px 16px;
            font-size: 0.875rem;
            border-bottom: 1px solid rgba(42,42,50,0.6);
            vertical-align: middle;
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        /* Forms */
        .form-group { margin-bottom: 20px; }
        label {
            display: block;
            margin-bottom: 7px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #b8b4c0;
        }
        label .required { color: var(--accent); margin-left: 2px; }
        input[type=text],
        input[type=email],
        input[type=number],
        input[type=tel],
        textarea,
        select {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text);
            padding: 10px 14px;
            font-size: 0.9rem;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.15s;
            outline: none;
        }
        input:focus, textarea:focus, select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(232,200,122,0.08);
        }
        textarea { resize: vertical; min-height: 100px; }
        select option { background: var(--surface2); }

        .form-error { color: var(--danger); font-size: 0.8rem; margin-top: 5px; }

        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-check input[type=checkbox] {
            width: 18px; height: 18px;
            accent-color: var(--accent);
            cursor: pointer;
        }
        .form-check label { margin: 0; cursor: pointer; }

        /* Stat cards */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; margin-bottom: 28px; }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
        }
        .stat-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); margin-bottom: 10px; }
        .stat-value { font-family: 'DM Serif Display', serif; font-size: 2rem; color: var(--text); }
        .stat-sub   { font-size: 0.78rem; color: var(--muted); margin-top: 4px; }

        /* Pagination */
        .pagination { display: flex; gap: 6px; align-items: center; padding: 20px 24px; border-top: 1px solid var(--border); }
        .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px; height: 34px;
            padding: 0 10px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            color: var(--muted);
            background: var(--surface2);
            border: 1px solid var(--border);
            transition: all 0.15s;
        }
        .page-link:hover { border-color: var(--accent); color: var(--accent); }
        .page-link.active { background: var(--accent); color: #0c0c0e; border-color: var(--accent); }

        /* Filters bar */
        .filters-bar {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .filters-bar input, .filters-bar select {
            width: auto;
            flex: 1;
            min-width: 200px;
        }

        /* Page header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
        }
        .page-header h2 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.8rem;
            color: var(--text);
        }
        .page-header p { color: var(--muted); font-size: 0.875rem; margin-top: 4px; }

        /* Grid */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }

        /* Product image thumb */
        .product-thumb {
            width: 42px; height: 42px;
            border-radius: 8px;
            background: var(--surface2);
            border: 1px solid var(--border);
            object-fit: cover;
            display: flex; align-items: center; justify-content: center;
            color: var(--muted); font-size: 1.2rem;
            overflow: hidden;
        }
        .product-thumb img { width: 100%; height: 100%; object-fit: cover; }

        /* Toast auto-dismiss */
        @keyframes slideIn { from { transform: translateX(20px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .alert { animation: slideIn 0.25s ease; }

        /* Responsive tweaks */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-wrap { margin-left: 0; }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <h1>Velour</h1>
        <span>Admin Panel</span>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Overview</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon">⊞</span> Dashboard
        </a>

        <div class="nav-section" style="margin-top:16px">Catalog</div>
        <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <span class="nav-icon">◈</span> Products
        </a>
        <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <span class="nav-icon">◉</span> Categories
        </a>

        <div class="nav-section" style="margin-top:16px">Sales</div>
        <a href="{{ route('orders.index') }}" class="nav-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <span class="nav-icon">◷</span> Orders
        </a>
    </nav>
    <div class="sidebar-footer">Velour eCommerce v1.0</div>
</aside>

<!-- Main -->
<div class="main-wrap">
    <header class="topbar">
        <span class="topbar-title">@yield('topbar-title', 'Dashboard')</span>
        <div class="topbar-actions">
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">+ New Product</a>
        </div>
    </header>

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
// Auto-dismiss alerts
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => {
        el.style.transition = 'opacity 0.4s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 400);
    });
}, 4000);

// Confirm delete
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', function(e) {
        if (!confirm(this.dataset.confirm || 'Are you sure?')) {
            e.preventDefault();
        }
    });
});
</script>
@stack('scripts')
</body>
</html>
