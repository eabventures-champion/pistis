<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Pistis</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ file_exists(public_path('css/app.css')) ? filemtime(public_path('css/app.css')) : time() }}">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="text-center mb-4">
                <a href="{{ route('home') }}" class="navbar-brand" style="display:inline-flex;font-size:1.6rem;margin-bottom:24px;align-items:center;gap:12px;">
                    @if(!empty($store_logo))
                        <img src="{{ $store_logo }}" alt="{{ $store_name ?? 'PISTIS' }}" style="max-height:36px; width:auto; object-fit:contain;">
                    @else
                        <span class="brand-icon">P</span>
                    @endif
                    @if(empty($store_hide_brand_text))
                        <span>{{ $store_name ?? 'PISTIS' }}</span>
                    @endif
                </a>
            </div>
            <h2 class="auth-title">Create account</h2>
            <p class="auth-subtitle">Join Pistis to start shopping</p>

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('customer.register') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100">Create Account</button>
            </form>

            <p class="text-center mt-4" style="font-size:0.9rem;color:var(--text-muted);">
                Already have an account? <a href="{{ route('customer.login') }}">Sign in</a>
            </p>
        </div>
    </div>
</body>
</html>



