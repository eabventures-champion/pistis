<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Pistis</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ file_exists(public_path('css/app.css')) ? filemtime(public_path('css/app.css')) : time() }}">
    <style>
        .auth-brand-link {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            gap: 12px;
            margin-bottom: 24px;
        }
        .auth-brand-logo {
            width: 80px;
            height: 80px;
            max-height: 80px;
            border-radius: 50%;
            aspect-ratio: 1 / 1;
            object-fit: contain;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            display: block;
            margin: 0 auto;
        }
        .auth-brand-logo:hover {
            transform: scale(1.06);
            box-shadow: 0 8px 26px rgba(0, 0, 0, 0.18);
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="text-center">
                <a href="{{ route('home') }}" class="auth-brand-link" title="{{ $store_name ?? 'PISTIS' }}">
                    @if(!empty($store_logo))
                        <img src="{{ $store_logo }}" alt="{{ $store_name ?? 'PISTIS' }}" class="auth-brand-logo">
                    @else
                        <span class="brand-icon" style="width:60px; height:60px; font-size:1.8rem; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; background:#000; color:#fff; font-family:'Cormorant Garamond', serif; font-weight:700;">P</span>
                    @endif
                    @if(empty($store_hide_brand_text))
                        <span style="font-family:'Inter', sans-serif; font-size:1.15rem; font-weight:800; letter-spacing:0.28em; color:#000000; text-transform:uppercase;">{{ $store_name ?? 'PISTIS' }}</span>
                    @endif
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



