<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page Not Found</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg:#0c0c0e; --surface:#141417; --border:#2a2a32; --accent:#e8c87a; --text:#f0ede8; --muted:#7a7880; }
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
            font-size: 9rem;
            color: var(--accent);
            opacity: 0.12;
            line-height: 1;
            margin-bottom: 4px;
            letter-spacing: -4px;
        }
        h1 { font-family: 'DM Serif Display', serif; font-size: 2.2rem; margin-bottom: 14px; }
        p  { color: var(--muted); font-size: 1rem; max-width: 420px; line-height: 1.6; margin-bottom: 32px; }
        .actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 10px 22px; border-radius: 10px; font-size: 0.9rem;
            font-weight: 600; font-family: 'DM Sans', sans-serif;
            text-decoration: none; cursor: pointer; border: none; transition: all 0.15s;
        }
        .btn-primary  { background: var(--accent); color: #0c0c0e; }
        .btn-primary:hover { background: #f0d488; }
        .btn-secondary { background: var(--surface); border: 1px solid var(--border); color: var(--muted); }
        .btn-secondary:hover { border-color: var(--accent); color: var(--accent); }
    </style>
</head>
<body>
    <div class="code">404</div>
    <h1>Page Not Found</h1>
    <p>The page you're looking for doesn't exist or may have been moved. Let's get you back on track.</p>
    <div class="actions">
        <a href="javascript:history.back()" class="btn btn-secondary">← Go Back</a>
        <a href="{{ url('/') }}" class="btn btn-primary">Go to Shop</a>
        @auth
            @if(auth()->user()->isAdmin() || auth()->user()->hasRole(['editor','moderator']))
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Admin Panel</a>
            @endif
        @endauth
    </div>
</body>
</html>
