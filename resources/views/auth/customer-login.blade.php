<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Pistis</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="text-center mb-4">
                <a href="{{ route('home') }}" class="navbar-brand" style="display:inline-flex;font-size:1.6rem;margin-bottom:24px;">
                    <span class="brand-icon">P</span> PISTIS
                </a>
            </div>
            <h2 class="auth-title">Welcome back</h2>
            <p class="auth-subtitle">Sign in to your account to continue shopping</p>

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('customer.login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="remember"> <span style="font-size:0.85rem;color:var(--text-secondary);">Remember me</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100">Sign In</button>
            </form>

            <p class="text-center mt-4" style="font-size:0.9rem;color:var(--text-muted);">
                Don't have an account? <a href="{{ route('customer.register') }}">Create one</a>
            </p>
        </div>
    </div>
</body>
</html>



