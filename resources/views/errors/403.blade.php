<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Access Denied</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg:#0c0c0e; --surface:#141417; --border:#2a2a32; --accent:#e8c87a; --text:#f0ede8; --muted:#7a7880; --danger:#e05252; }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            text-align: center;
        }

        .code {
            font-family: 'DM Serif Display', serif;
            font-size: 8rem;
            color: var(--danger);
            opacity: 0.2;
            line-height: 1;
            margin-bottom: 8px;
        }

        h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 2.2rem;
            margin-bottom: 14px;
        }

        p {
            color: var(--muted);
            font-size: 1rem;
            max-width: 420px;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .actions { display: flex; gap: 12px; justify-content: center; }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.15s;
        }

        .btn-primary { background: var(--accent); color: #0c0c0e; }
        .btn-primary:hover { background: #f0d488; }
        .btn-secondary { background: var(--surface); border: 1px solid var(--border); color: var(--muted); }
        .btn-secondary:hover { border-color: var(--accent); color: var(--accent); }

        .role-info {
            margin-top: 28px;
            padding: 14px 20px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 0.82rem;
            color: var(--muted);
        }

        .role-info strong { color: var(--accent); }
    </style>
</head>
<body>
    <div class="code">403</div>
    <h1>Access Denied</h1>
    <p>You don't have permission to view this page. This area requires a higher role level than your current account has.</p>

    <div class="actions">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">← Go Back</a>
        @auth
            @if(auth()->user()->isAdmin() || auth()->user()->hasRole(['editor','moderator']))
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Admin Dashboard</a>
            @else
                <a href="{{ route('home') }}" class="btn btn-primary">Go to Home</a>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn btn-primary">Sign In</a>
        @endauth
    </div>

    @auth
    <div class="role-info">
        You are signed in as <strong>{{ auth()->user()->name }}</strong> with the
        <strong>{{ auth()->user()->role_label }}</strong> role.
        Contact an Administrator to request elevated access.
    </div>
    @endauth
</body>
</html>
