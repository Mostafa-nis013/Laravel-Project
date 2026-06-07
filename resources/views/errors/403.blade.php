<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>403 – Forbidden</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap" rel="stylesheet" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .error-box { text-align: center; padding: 40px; max-width: 420px; }
        .error-code { font-size: 6rem; font-weight: 800; color: #e0e7ff; line-height: 1; }
        .error-title { font-size: 1.5rem; font-weight: 700; margin: 8px 0; }
        .error-msg { color: #64748b; margin-bottom: 24px; }
        a { display: inline-block; background: #6366f1; color: #fff; padding: 10px 24px; border-radius: 8px; font-weight: 600; text-decoration: none; }
    </style>
</head>
<body>
    <div class="error-box">
        <div class="error-code">403</div>
        <h1 class="error-title">Access Denied</h1>
        <p class="error-msg">You don't have permission to view this page.</p>
        @auth
            <a href="{{ route('dashboard') }}">← Back to Dashboard</a>
        @else
            <a href="{{ route('login') }}">← Back to Login</a>
        @endauth
    </div>
</body>
</html>
