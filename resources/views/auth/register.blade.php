<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — Velour</title>
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
            bottom: -100px; right: -100px;
            width: 450px; height: 450px;
            background: radial-gradient(circle, rgba(232,200,122,0.05) 0%, transparent 70%);
            pointer-events: none;
        }

        .brand h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            color: var(--accent);
        }

        .brand p {
            font-size: 0.82rem;
            color: var(--muted);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 4px;
        }

        .left-tagline {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .left-tagline h2 {
            font-family: 'DM Serif Display', serif;
            font-size: 2.4rem;
            line-height: 1.2;
            margin-bottom: 16px;
        }

        .left-tagline h2 em { font-style: italic; color: var(--accent); }

        .left-tagline p {
            color: var(--muted);
            font-size: 0.93rem;
            line-height: 1.6;
            max-width: 340px;
        }

        .info-list {
            margin-top: 28px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 0.875rem;
            color: var(--muted);
        }

        .info-item-icon {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(232,200,122,0.1);
            border: 1px solid rgba(232,200,122,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .left-footer { font-size: 0.78rem; color: var(--muted); }

        /* Right */
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
            margin-bottom: 28px;
        }

        .form-group { margin-bottom: 16px; }

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

        /* Password strength */
        .pw-strength {
            display: flex;
            gap: 4px;
            margin-top: 8px;
        }

        .pw-bar {
            height: 3px;
            flex: 1;
            border-radius: 2px;
            background: var(--border);
            transition: background 0.25s;
        }

        .pw-hint {
            font-size: 0.75rem;
            color: var(--muted);
            margin-top: 5px;
        }

        .btn-register {
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
            margin-top: 6px;
            margin-bottom: 18px;
        }

        .btn-register:hover { background: #f0d488; }

        .terms {
            font-size: 0.78rem;
            color: var(--muted);
            text-align: center;
            margin-bottom: 18px;
            line-height: 1.5;
        }

        .auth-footer {
            text-align: center;
            font-size: 0.85rem;
            color: var(--muted);
        }

        .auth-footer a {
            color: var(--accent);
            text-decoration: none;
        }

        .role-note {
            margin-top: 20px;
            padding: 12px 14px;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.8rem;
            color: var(--muted);
            line-height: 1.5;
        }

        .role-note strong { color: var(--text); }

        @media (max-width: 768px) {
            body { grid-template-columns: 1fr; }
            .left-panel { display: none; }
            .right-panel { padding: 32px 24px; }
        }
    </style>
</head>
<body>

<div class="left-panel">
    <div class="brand">
        <h1>Velour</h1>
        <p>Admin Panel</p>
    </div>

    <div class="left-tagline">
        <h2>Join the<br><em>team</em> today.</h2>
        <p>Create your account and get started. An administrator can upgrade your role to grant additional access.</p>

        <div class="info-list">
            <div class="info-item">
                <div class="info-item-icon">✓</div>
                <div>Instant account creation — no email verification needed in dev mode.</div>
            </div>
            <div class="info-item">
                <div class="info-item-icon">◎</div>
                <div>New accounts start with the <strong style="color:var(--text)">User</strong> role. Admins can assign additional roles.</div>
            </div>
            <div class="info-item">
                <div class="info-item-icon">⬡</div>
                <div>Only <strong style="color:var(--accent)">Admins</strong> can access the full admin panel and manage users.</div>
            </div>
        </div>
    </div>

    <div class="left-footer">Velour eCommerce v1.0 &nbsp;·&nbsp; Built with Laravel</div>
</div>

<div class="right-panel">
    <div class="auth-box">
        <h2>Create account</h2>
        <p class="subtitle">Fill in your details to get started.</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name">Full name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    autocomplete="name"
                    autofocus
                    placeholder="Jane Smith"
                    class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                >
                @error('name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    placeholder="jane@example.com"
                    class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                >
                @error('email')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    placeholder="Min. 8 chars, mixed case & number"
                    class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                    oninput="checkStrength(this.value)"
                >
                <div class="pw-strength">
                    <div class="pw-bar" id="bar1"></div>
                    <div class="pw-bar" id="bar2"></div>
                    <div class="pw-bar" id="bar3"></div>
                    <div class="pw-bar" id="bar4"></div>
                </div>
                <div class="pw-hint" id="pw-label">Enter a password</div>
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    autocomplete="new-password"
                    placeholder="Repeat your password"
                >
            </div>

            <button type="submit" class="btn-register">Create Account</button>

            <div class="terms">
                By creating an account you agree to our terms of service and privacy policy.
            </div>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="{{ route('login') }}">Sign in</a>
        </div>

        <div class="role-note">
            ℹ New accounts are assigned the <strong>User</strong> role automatically. Contact an <strong>Administrator</strong> to get elevated access (Editor, Moderator, or Admin).
        </div>
    </div>
</div>

<script>
function checkStrength(pw) {
    const bars  = [1,2,3,4].map(i => document.getElementById('bar' + i));
    const label = document.getElementById('pw-label');
    const colors = { weak: '#e05252', fair: '#e0a452', good: '#5282e0', strong: '#52c07a' };

    let score = 0;
    if (pw.length >= 8)              score++;
    if (/[A-Z]/.test(pw))           score++;
    if (/[0-9]/.test(pw))           score++;
    if (/[^a-zA-Z0-9]/.test(pw))   score++;

    const levels = ['', 'Weak', 'Fair', 'Good', 'Strong'];
    const colorKeys = ['', 'weak', 'fair', 'good', 'strong'];

    bars.forEach((b, i) => {
        b.style.background = i < score ? colors[colorKeys[score]] : 'var(--border)';
    });

    label.textContent   = pw.length ? levels[score] || 'Too short' : 'Enter a password';
    label.style.color   = score > 0 ? colors[colorKeys[score]] : 'var(--muted)';
}
</script>
</body>
</html>
