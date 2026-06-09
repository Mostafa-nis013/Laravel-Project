<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Velour</title>
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
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* ── Left panel ── */
        .left-panel {
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -120px; left: -120px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(232,200,122,0.06) 0%, transparent 70%);
            pointer-events: none;
        }

        .brand h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            color: var(--accent);
            margin-bottom: 6px;
        }

        .brand p {
            font-size: 0.82rem;
            color: var(--muted);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .left-tagline {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .left-tagline h2 {
            font-family: 'DM Serif Display', serif;
            font-size: 2.6rem;
            line-height: 1.2;
            color: var(--text);
            margin-bottom: 16px;
        }

        .left-tagline h2 em {
            font-style: italic;
            color: var(--accent);
        }

        .left-tagline p {
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.6;
            max-width: 360px;
        }

        .role-pills {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 28px;
        }

        .role-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 500;
            border: 1px solid var(--border);
            color: var(--muted);
        }

        .role-pill.admin     { border-color: rgba(232,200,122,0.4); color: var(--accent); background: rgba(232,200,122,0.06); }
        .role-pill.editor    { border-color: rgba(82,130,224,0.4);  color: #7aabf0;       background: rgba(82,130,224,0.06); }
        .role-pill.moderator { border-color: rgba(150,100,220,0.4); color: #c090f0;       background: rgba(150,100,220,0.06); }
        .role-pill.user      { border-color: rgba(82,192,122,0.4);  color: #7ad4a0;       background: rgba(82,192,122,0.06); }

        .left-footer {
            font-size: 0.78rem;
            color: var(--muted);
        }

        /* ── Right panel (form) ── */
        .right-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
        }

        .auth-box {
            width: 100%;
            max-width: 400px;
        }

        .auth-box h2 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.9rem;
            margin-bottom: 6px;
        }

        .auth-box .subtitle {
            color: var(--muted);
            font-size: 0.9rem;
            margin-bottom: 32px;
        }

        .alert-error {
            background: rgba(224,82,82,0.1);
            border: 1px solid rgba(224,82,82,0.3);
            border-radius: var(--radius);
            padding: 12px 16px;
            color: var(--danger);
            font-size: 0.875rem;
            margin-bottom: 20px;
        }

        .alert-info {
            background: rgba(82,192,122,0.1);
            border: 1px solid rgba(82,192,122,0.3);
            border-radius: var(--radius);
            padding: 12px 16px;
            color: var(--success);
            font-size: 0.875rem;
            margin-bottom: 20px;
        }

        .form-group { margin-bottom: 18px; }

        label {
            display: block;
            font-size: 0.83rem;
            font-weight: 500;
            color: #b0acb8;
            margin-bottom: 7px;
        }

        input[type=email],
        input[type=password],
        input[type=text] {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text);
            padding: 11px 14px;
            font-size: 0.92rem;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(232,200,122,0.1);
        }

        input.is-invalid { border-color: var(--danger); }

        .field-error {
            color: var(--danger);
            font-size: 0.78rem;
            margin-top: 5px;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: var(--muted);
            cursor: pointer;
        }

        .form-check input[type=checkbox] {
            width: 16px; height: 16px;
            accent-color: var(--accent);
        }

        .forgot-link {
            font-size: 0.83rem;
            color: var(--muted);
            text-decoration: none;
            transition: color 0.15s;
        }

        .forgot-link:hover { color: var(--accent); }

        .btn-login {
            width: 100%;
            background: var(--accent);
            color: #0c0c0e;
            border: none;
            border-radius: var(--radius);
            padding: 13px;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: background 0.15s;
            margin-bottom: 20px;
        }

        .btn-login:hover { background: #f0d488; }

        .auth-footer {
            text-align: center;
            font-size: 0.85rem;
            color: var(--muted);
        }

        .auth-footer a {
            color: var(--accent);
            text-decoration: none;
        }

        /* Demo credentials */
        .demo-creds {
            margin-top: 28px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .demo-creds-header {
            padding: 9px 14px;
            background: var(--surface2);
            font-size: 0.72rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--muted);
            font-weight: 600;
        }

        .demo-row {
            display: flex;
            align-items: center;
            padding: 9px 14px;
            border-top: 1px solid var(--border);
            gap: 10px;
            cursor: pointer;
            transition: background 0.12s;
        }

        .demo-row:hover { background: var(--surface2); }

        .demo-role-badge {
            font-size: 0.68rem;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 600;
            flex-shrink: 0;
            width: 72px;
            text-align: center;
        }

        .demo-email {
            font-size: 0.8rem;
            color: var(--muted);
            font-family: monospace;
            flex: 1;
        }

        .demo-hint {
            font-size: 0.7rem;
            color: rgba(122,120,128,0.6);
        }

        @media (max-width: 768px) {
            body { grid-template-columns: 1fr; }
            .left-panel { display: none; }
            .right-panel { padding: 32px 24px; }
        }
    </style>
</head>
<body>

<!-- Left brand panel -->
<div class="left-panel">
    <div class="brand">
        <h1>Velour</h1>
        <p>Admin Panel</p>
    </div>

    <div class="left-tagline">
        <h2>Your store,<br><em>beautifully</em> managed.</h2>
        <p>A complete eCommerce admin system with role-based access control. Each role grants a different level of access to your store.</p>

        <div class="role-pills">
            <div class="role-pill admin">⬡ Administrator — full access</div>
            <div class="role-pill editor">◈ Editor — catalog & orders</div>
            <div class="role-pill moderator">◉ Moderator — orders & view</div>
            <div class="role-pill user">○ User — storefront only</div>
        </div>
    </div>

    <div class="left-footer">Velour eCommerce v1.0 &nbsp;·&nbsp; Built with Laravel</div>
</div>

<!-- Right form panel -->
<div class="right-panel">
    <div class="auth-box">
        <h2>Welcome back</h2>
        <p class="subtitle">Sign in to your account to continue.</p>

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert-info">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    autofocus
                    placeholder="you@example.com"
                    class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                >
                @error('email')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div style="position:relative">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                        style="padding-right:44px"
                    >
                    <button type="button" id="toggle-pw" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--muted);cursor:pointer;font-size:1rem;padding:4px" title="Show/hide password">👁</button>
                </div>
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <label class="form-check">
                    <input type="checkbox" name="remember"> Remember me
                </label>
            </div>

            <button type="submit" class="btn-login">Sign In</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="{{ route('register') }}">Create one</a>
        </div>

        <!-- Demo credentials -->
        <div class="demo-creds">
            <div class="demo-creds-header">Demo accounts — click to fill</div>

            <div class="demo-row" onclick="fill('admin@velour.com')">
                <span class="demo-role-badge" style="background:rgba(232,200,122,0.12);color:var(--accent)">Admin</span>
                <span class="demo-email">admin@velour.com</span>
                <span class="demo-hint">Full access</span>
            </div>
            <div class="demo-row" onclick="fill('editor@velour.com')">
                <span class="demo-role-badge" style="background:rgba(82,130,224,0.12);color:#7aabf0">Editor</span>
                <span class="demo-email">editor@velour.com</span>
                <span class="demo-hint">Catalog + orders</span>
            </div>
            <div class="demo-row" onclick="fill('moderator@velour.com')">
                <span class="demo-role-badge" style="background:rgba(150,100,220,0.12);color:#c090f0">Moderator</span>
                <span class="demo-email">moderator@velour.com</span>
                <span class="demo-hint">Orders & view</span>
            </div>
            <div class="demo-row" onclick="fill('user@velour.com')">
                <span class="demo-role-badge" style="background:rgba(82,192,122,0.12);color:#7ad4a0">User</span>
                <span class="demo-email">user@velour.com</span>
                <span class="demo-hint">Storefront only</span>
            </div>
        </div>
    </div>
</div>

<script>
function fill(email) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = 'password';
}

document.getElementById('toggle-pw').addEventListener('click', function() {
    const pw = document.getElementById('password');
    pw.type = pw.type === 'password' ? 'text' : 'password';
    this.textContent = pw.type === 'password' ? '👁' : '🙈';
});
</script>
</body>
</html>
