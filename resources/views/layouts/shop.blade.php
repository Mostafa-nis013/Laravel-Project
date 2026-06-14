<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shop') — Velour</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:      #0c0c0e;
            --surface: #141417;
            --surface2:#1c1c21;
            --border:  #2a2a32;
            --accent:  #e8c87a;
            --text:    #f0ede8;
            --muted:   #7a7880;
            --danger:  #e05252;
            --success: #52c07a;
            --radius:  10px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Header ── */
        .site-header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px;
            height: 66px;
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .site-logo {
            font-family: 'DM Serif Display', serif;
            font-size: 1.6rem;
            color: var(--accent);
            text-decoration: none;
            letter-spacing: -0.5px;
            flex-shrink: 0;
        }

        /* Search bar */
        .header-search {
            flex: 1;
            max-width: 480px;
            position: relative;
        }

        .header-search input {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 50px;
            color: var(--text);
            padding: 9px 18px 9px 42px;
            font-size: 0.875rem;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: border-color 0.15s;
        }

        .header-search input:focus { border-color: var(--accent); }
        .header-search input::placeholder { color: var(--muted); }

        .header-search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 0.9rem;
            pointer-events: none;
        }

        .header-nav {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-left: auto;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: var(--radius);
            transition: all 0.15s;
        }

        .nav-link:hover { background: var(--surface2); color: var(--text); }
        .nav-link.active { color: var(--accent); }

        /* Cart icon button */
        .cart-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            background: var(--accent);
            color: #0c0c0e;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: var(--radius);
            transition: background 0.15s;
            position: relative;
        }

        .cart-btn:hover { background: #f0d488; }

        .cart-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            background: #0c0c0e;
            color: var(--accent);
            border-radius: 50%;
            font-size: 0.72rem;
            font-weight: 700;
        }

        /* User menu */
        .user-menu {
            position: relative;
        }

        .user-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: none;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--muted);
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.875rem;
            transition: all 0.15s;
        }

        .user-btn:hover { border-color: var(--accent); color: var(--text); }

        .user-avatar-sm {
            width: 26px; height: 26px;
            border-radius: 50%;
            background: rgba(232,200,122,0.15);
            border: 1px solid rgba(232,200,122,0.3);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.72rem; font-weight: 700; color: var(--accent);
        }

        .user-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 200px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 12px 40px rgba(0,0,0,0.4);
            z-index: 200;
            overflow: hidden;
        }

        .user-dropdown.open { display: block; }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 16px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.875rem;
            border-bottom: 1px solid var(--border);
            transition: background 0.12s;
        }

        .dropdown-item:hover { background: var(--surface2); color: var(--text); }
        .dropdown-item:last-child { border-bottom: none; }

        /* Category nav bar */
        .category-bar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        .category-bar-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px;
            display: flex;
            gap: 4px;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .category-bar-inner::-webkit-scrollbar { display: none; }

        .cat-link {
            display: inline-flex;
            align-items: center;
            padding: 10px 16px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 500;
            white-space: nowrap;
            border-bottom: 2px solid transparent;
            transition: all 0.15s;
        }

        .cat-link:hover { color: var(--text); }
        .cat-link.active { color: var(--accent); border-bottom-color: var(--accent); }

        /* ── Page content ── */
        .page-content {
            flex: 1;
            max-width: 1280px;
            margin: 0 auto;
            width: 100%;
            padding: 32px;
        }

        /* ── Flash alerts ── */
        .flash {
            padding: 13px 18px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.2s ease;
        }
        @keyframes fadeIn { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }
        .flash-success { background: rgba(82,192,122,0.1); border: 1px solid rgba(82,192,122,0.25); color: var(--success); }
        .flash-error   { background: rgba(224,82,82,0.1);  border: 1px solid rgba(224,82,82,0.25);  color: var(--danger); }

        /* ── Footer ── */
        .site-footer {
            background: var(--surface);
            border-top: 1px solid var(--border);
            padding: 40px 32px;
            margin-top: auto;
        }

        .footer-inner {
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
        }

        .footer-brand h3 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.4rem;
            color: var(--accent);
            margin-bottom: 10px;
        }

        .footer-brand p { color: var(--muted); font-size: 0.875rem; line-height: 1.6; }

        .footer-col h4 {
            font-size: 0.72rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--muted);
            font-weight: 700;
            margin-bottom: 14px;
        }

        .footer-col a {
            display: block;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.875rem;
            margin-bottom: 8px;
            transition: color 0.15s;
        }

        .footer-col a:hover { color: var(--accent); }

        .footer-bottom {
            max-width: 1280px;
            margin: 24px auto 0;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            font-size: 0.78rem;
            color: var(--muted);
        }

        /* ── Shared components ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 22px;
            border-radius: var(--radius);
            font-size: 0.9rem;
            font-weight: 600;
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
        .btn-danger    { background: rgba(224,82,82,0.12); color: var(--danger); border: 1px solid rgba(224,82,82,0.3); }
        .btn-danger:hover { background: rgba(224,82,82,0.2); }
        .btn-sm { padding: 7px 14px; font-size: 0.82rem; }
        .btn-block { width: 100%; justify-content: center; }

        /* Badges */
        .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 600; }
        .badge-sale   { background: rgba(224,82,82,0.12);   color: var(--danger); }
        .badge-new    { background: rgba(82,130,224,0.12);  color: #7aabf0; }
        .badge-out    { background: rgba(122,120,128,0.12); color: var(--muted); }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .header-inner { padding: 0 16px; gap: 12px; }
            .header-search { display: none; }
            .page-content { padding: 20px 16px; }
            .footer-inner { grid-template-columns: 1fr 1fr; gap: 24px; }
            .footer-bottom { flex-direction: column; gap: 6px; }
        }
    </style>
    @stack('styles')
</head>
<body>

<header class="site-header">
    <div class="header-inner">
        <a href="{{ route('shop.index') }}" class="site-logo">Velour</a>

        <!-- Search -->
        <form action="{{ route('shop.index') }}" method="GET" class="header-search">
            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
            <span class="header-search-icon">🔍</span>
            <input type="text" name="search" placeholder="Search products…" value="{{ request('search') }}">
        </form>

        <nav class="header-nav">
            @auth
                <!-- Cart -->
                <a href="{{ route('shop.cart') }}" class="cart-btn">
                    🛒
                    @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                    @if($cartCount > 0)
                        <span class="cart-count">{{ $cartCount > 9 ? '9+' : $cartCount }}</span>
                    @endif
                </a>

                <!-- User menu -->
                <div class="user-menu">
                    <button class="user-btn" id="user-menu-btn">
                        <div class="user-avatar-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        {{ Str::limit(auth()->user()->name, 14) }}
                        <span style="font-size:0.7rem">▾</span>
                    </button>
                    <div class="user-dropdown" id="user-dropdown">
                        <a href="{{ route('shop.orders') }}" class="dropdown-item">📦 My Orders</a>
                        @if(auth()->user()->isAdmin() || auth()->user()->hasRole(['editor','moderator']))
                            <a href="{{ route('dashboard') }}" class="dropdown-item">⊞ Admin Panel</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item" style="width:100%;background:none;border:none;cursor:pointer;font-family:inherit">⎋ Sign Out</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="nav-link">Sign in</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
            @endauth
        </nav>
    </div>

    <!-- Category strip -->
    <div class="category-bar">
        <div class="category-bar-inner">
            <a href="{{ route('shop.index') }}" class="cat-link {{ !request('category') ? 'active' : '' }}">All Products</a>
            @foreach(\App\Models\Category::active()->orderBy('sort_order')->get() as $cat)
                <a href="{{ route('shop.index', ['category' => $cat->slug]) }}"
                   class="cat-link {{ request('category') == $cat->slug ? 'active' : '' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>
</header>

<div class="page-content">
    @if(session('cart_success'))
        <div class="flash flash-success">✓ {{ session('cart_success') }}</div>
    @endif
    @if(session('cart_error'))
        <div class="flash flash-error">✕ {{ session('cart_error') }}</div>
    @endif
    @if(session('success'))
        <div class="flash flash-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash-error">✕ {{ session('error') }}</div>
    @endif

    @yield('content')
</div>

<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <h3>Velour</h3>
            <p>A curated selection of quality products, delivered fast. Shop with confidence.</p>
        </div>
        <div class="footer-col">
            <h4>Shop</h4>
            <a href="{{ route('shop.index') }}">All Products</a>
            @foreach(\App\Models\Category::active()->limit(4)->get() as $cat)
                <a href="{{ route('shop.index', ['category' => $cat->slug]) }}">{{ $cat->name }}</a>
            @endforeach
        </div>
        <div class="footer-col">
            <h4>Account</h4>
            @auth
                <a href="{{ route('shop.orders') }}">My Orders</a>
                <a href="{{ route('shop.cart') }}">Cart</a>
            @else
                <a href="{{ route('login') }}">Sign In</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>
        <div class="footer-col">
            <h4>Info</h4>
            <a href="#">About Us</a>
            <a href="#">Contact</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Returns</a>
        </div>
    </div>
    <div class="footer-bottom">
        <span>© {{ date('Y') }} Velour. All rights reserved.</span>
        <span>Built with Laravel</span>
    </div>
</footer>

<script>
// User dropdown toggle
const userBtn = document.getElementById('user-menu-btn');
const userDD  = document.getElementById('user-dropdown');
userBtn?.addEventListener('click', e => { e.stopPropagation(); userDD.classList.toggle('open'); });
document.addEventListener('click', () => userDD?.classList.remove('open'));

// Auto-dismiss flash messages
setTimeout(() => {
    document.querySelectorAll('.flash').forEach(el => {
        el.style.transition = 'opacity 0.4s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 400);
    });
}, 4000);
</script>
@stack('scripts')
</body>
</html>
