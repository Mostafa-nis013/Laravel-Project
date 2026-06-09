<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home — Velour</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0c0c0e; --surface: #141417; --border: #2a2a32;
            --accent: #e8c87a; --text: #f0ede8; --muted: #7a7880;
            --radius: 10px;
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

        header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 40px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            font-family: 'DM Serif Display', serif;
            font-size: 1.5rem;
            color: var(--accent);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 0.875rem;
        }

        .user-name { color: var(--muted); }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-ghost {
            background: none;
            border: 1px solid var(--border);
            color: var(--muted);
        }

        .btn-ghost:hover { border-color: var(--accent); color: var(--accent); }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            text-align: center;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            background: rgba(82,192,122,0.1);
            border: 1px solid rgba(82,192,122,0.3);
            color: #7ad4a0;
            margin-bottom: 28px;
        }

        h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 3.5rem;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        h1 em { font-style: italic; color: var(--accent); }

        p {
            font-size: 1rem;
            color: var(--muted);
            max-width: 520px;
            line-height: 1.7;
            margin-bottom: 36px;
        }

        .info-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 24px 32px;
            max-width: 480px;
            text-align: left;
        }

        .info-card h3 {
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 14px;
            font-weight: 600;
        }

        .role-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            border-bottom: 1px solid var(--border);
            font-size: 0.875rem;
        }

        .role-row:last-child { border-bottom: none; }

        .dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .role-row-label { color: var(--text); font-weight: 500; flex: 1; }
        .role-row-desc  { color: var(--muted); font-size: 0.8rem; }

        form button {
            background: none;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 8px 16px;
            color: var(--muted);
            font-size: 0.875rem;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.15s;
        }

        form button:hover { border-color: #e05252; color: #e05252; }
    </style>
</head>
<body>

<header>
    <div class="logo">Velour</div>
    <div class="header-right">
        <span class="user-name">{{ auth()->user()->name }}</span>
        @if(auth()->user()->isAdmin() || auth()->user()->hasRole(['editor','moderator']))
            <a href="{{ route('dashboard') }}" class="btn btn-ghost">Admin Panel →</a>
        @endif
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit">Sign out</button>
        </form>
    </div>
</header>

<main>
    <div class="role-badge">
        ○ {{ auth()->user()->role_label }}
    </div>

    <h1>Welcome,<br><em>{{ auth()->user()->name }}</em>.</h1>

    <p>
        You're logged in with the <strong>{{ auth()->user()->role_label }}</strong> role.
        This is the customer-facing storefront area. The full shop experience will be built in the next phase of this project.
    </p>

    <div class="info-card">
        <h3>Role Permissions Overview</h3>

        <div class="role-row">
            <div class="dot" style="background:#e8c87a"></div>
            <span class="role-row-label">Administrator</span>
            <span class="role-row-desc">Full admin access + user management</span>
        </div>
        <div class="role-row">
            <div class="dot" style="background:#7aabf0"></div>
            <span class="role-row-label">Editor</span>
            <span class="role-row-desc">Products, categories & orders</span>
        </div>
        <div class="role-row">
            <div class="dot" style="background:#c090f0"></div>
            <span class="role-row-label">Moderator</span>
            <span class="role-row-desc">Orders & catalog view</span>
        </div>
        <div class="role-row">
            <div class="dot" style="background:#7ad4a0"></div>
            <span class="role-row-label">User</span>
            <span class="role-row-desc">Storefront access only (you are here)</span>
        </div>
    </div>
</main>

</body>
</html>
