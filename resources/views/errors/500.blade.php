<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Server Error</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg:#0c0c0e; --surface:#141417; --border:#2a2a32; --accent:#e8c87a; --text:#f0ede8; --muted:#7a7880; --danger:#e05252; }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg); color: var(--text);
            min-height: 100vh; display: flex; flex-direction: column;
            align-items: center; justify-content: center; padding: 40px; text-align: center;
        }
        .code { font-family: 'DM Serif Display', serif; font-size: 9rem; color: var(--danger); opacity: 0.12; line-height: 1; margin-bottom: 4px; letter-spacing: -4px; }
        h1 { font-family: 'DM Serif Display', serif; font-size: 2.2rem; margin-bottom: 14px; }
        p  { color: var(--muted); font-size: 1rem; max-width: 440px; line-height: 1.6; margin-bottom: 32px; }
        .actions { display: flex; gap: 12px; justify-content: center; }
        .btn { display: inline-flex; align-items: center; gap: 7px; padding: 10px 22px; border-radius: 10px; font-size: 0.9rem; font-weight: 600; font-family: 'DM Sans', sans-serif; text-decoration: none; cursor: pointer; border: none; transition: all 0.15s; }
        .btn-primary  { background: var(--accent); color: #0c0c0e; }
        .btn-primary:hover { background: #f0d488; }
        .btn-secondary { background: var(--surface); border: 1px solid var(--border); color: var(--muted); }
        .btn-secondary:hover { border-color: var(--accent); color: var(--accent); }
    </style>
</head>
<body>
    <div class="code">500</div>
    <h1>Something Went Wrong</h1>
    <p>An unexpected error occurred on our end. Our team has been notified. Please try again in a moment.</p>
    <div class="actions">
        <a href="javascript:location.reload()" class="btn btn-secondary">Try Again</a>
        <a href="{{ url('/') }}" class="btn btn-primary">Back to Shop</a>
    </div>
</body>
</html>
