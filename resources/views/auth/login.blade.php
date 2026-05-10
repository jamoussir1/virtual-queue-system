<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Virtual Queue System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #1A2B5E 0%, #0891B2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1rem; }
        .auth-card { background: #fff; border-radius: 16px; padding: 2.5rem; width: 100%; max-width: 420px; box-shadow: 0 20px 60px rgba(0,0,0,.25); }
        .auth-logo { text-align: center; margin-bottom: 1.75rem; }
        .auth-logo .icon { font-size: 2.5rem; }
        .auth-logo h1 { font-size: 1.4rem; font-weight: 700; color: #1A2B5E; margin-top: .5rem; }
        .auth-logo p { font-size: .85rem; color: #64748B; margin-top: .2rem; }
        .form-group { margin-bottom: 1rem; }
        .form-label { display: block; font-size: .85rem; font-weight: 600; color: #334155; margin-bottom: .35rem; }
        .form-control { width: 100%; padding: .6rem .9rem; border: 1.5px solid #E2E8F0; border-radius: 8px; font-size: .9rem; font-family: inherit; transition: border-color .15s, box-shadow .15s; }
        .form-control:focus { outline: none; border-color: #2563EB; box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
        .btn { width: 100%; padding: .7rem; border-radius: 8px; font-size: .95rem; font-weight: 600; cursor: pointer; border: none; margin-top: .5rem; background: #2563EB; color: #fff; transition: opacity .15s; }
        .btn:hover { opacity: .88; }
        .error { color: #DC2626; font-size: .8rem; margin-top: .25rem; }
        .alert { background: #FEF2F2; border-left: 4px solid #DC2626; padding: .65rem .9rem; border-radius: 6px; color: #991B1B; font-size: .85rem; margin-bottom: 1rem; }
        .auth-footer { text-align: center; margin-top: 1.25rem; font-size: .85rem; color: #64748B; }
        .auth-footer a { color: #2563EB; font-weight: 600; text-decoration: none; }
        .demo-box { background: #EFF6FF; border-radius: 8px; padding: .75rem 1rem; margin-top: 1rem; font-size: .78rem; color: #1E40AF; }
        .demo-box strong { display: block; margin-bottom: .35rem; }
        .demo-row { display: flex; justify-content: space-between; padding: .15rem 0; }
    </style>
</head>
<body>
<div class="auth-card">
    <div class="auth-logo">
        <div class="icon">🎟</div>
        <h1>VirtualQueue</h1>
        <p>ESPRIT School of Business — 2 LBC-BIS</p>
    </div>

    @if($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
        </div>
        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn">Sign In →</button>
    </form>

    <div class="auth-footer">
        Don't have an account? <a href="{{ route('register') }}">Register</a>
    </div>

    <div class="demo-box">
        <strong>🔑 Demo Accounts (password: <code>password</code>)</strong>
        <div class="demo-row"><span>Admin:</span><span>admin@esprit.tn</span></div>
        <div class="demo-row"><span>Agent:</span><span>sami@esprit.tn</span></div>
        <div class="demo-row"><span>Customer:</span><span>ines@mail.com</span></div>
    </div>
</div>
</body>
</html>
