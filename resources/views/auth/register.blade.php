<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Virtual Queue System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #1A2B5E 0%, #0891B2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1rem; }
        .auth-card { background: #fff; border-radius: 16px; padding: 2.5rem; width: 100%; max-width: 440px; box-shadow: 0 20px 60px rgba(0,0,0,.25); }
        .auth-logo { text-align: center; margin-bottom: 1.75rem; }
        .auth-logo .icon { font-size: 2.5rem; }
        .auth-logo h1 { font-size: 1.4rem; font-weight: 700; color: #1A2B5E; margin-top: .5rem; }
        .auth-logo p { font-size: .85rem; color: #64748B; }
        .form-group { margin-bottom: 1rem; }
        .form-label { display: block; font-size: .85rem; font-weight: 600; color: #334155; margin-bottom: .35rem; }
        .form-control { width: 100%; padding: .6rem .9rem; border: 1.5px solid #E2E8F0; border-radius: 8px; font-size: .9rem; font-family: inherit; transition: border-color .15s; }
        .form-control:focus { outline: none; border-color: #2563EB; box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
        .form-control.error-field { border-color: #DC2626; }
        .err { color: #DC2626; font-size: .78rem; margin-top: .2rem; }
        .btn { width: 100%; padding: .7rem; border-radius: 8px; font-size: .95rem; font-weight: 600; cursor: pointer; border: none; margin-top: .5rem; background: #2563EB; color: #fff; transition: opacity .15s; }
        .btn:hover { opacity: .88; }
        .auth-footer { text-align: center; margin-top: 1.25rem; font-size: .85rem; color: #64748B; }
        .auth-footer a { color: #2563EB; font-weight: 600; text-decoration: none; }
    </style>
</head>
<body>
<div class="auth-card">
    <div class="auth-logo">
        <div class="icon">🎟</div>
        <h1>Create Account</h1>
        <p>Join the Virtual Queue System</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control @error('name') error-field @enderror" value="{{ old('name') }}" placeholder="Your full name" required>
            @error('name')<div class="err">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control @error('email') error-field @enderror" value="{{ old('email') }}" placeholder="you@example.com" required>
            @error('email')<div class="err">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control @error('password') error-field @enderror" placeholder="Min. 8 characters" required>
            @error('password')<div class="err">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
        </div>
        <button type="submit" class="btn">Create Account →</button>
    </form>

    <div class="auth-footer">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
    </div>
</div>
</body>
</html>
