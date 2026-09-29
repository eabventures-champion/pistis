<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Pistis</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ file_exists(public_path('css/app.css')) ? filemtime(public_path('css/app.css')) : time() }}">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="text-center mb-4">
                <div class="navbar-brand" style="display:inline-flex;font-size:1.6rem;margin-bottom:24px;align-items:center;gap:12px;">
                    @if(!empty($store_logo))
                        <img src="{{ $store_logo }}" alt="{{ $store_name ?? 'Pistis' }}" style="max-height:36px; width:auto; object-fit:contain;">
                    @else
                        <span class="brand-icon">P</span>
                    @endif
                    <span>Admin Panel</span>
                </div>
            </div>
            <h2 class="auth-title">Admin Login</h2>
            <p class="auth-subtitle">Enter your credentials to access the dashboard</p>

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST" id="adminLoginForm">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus autocomplete="username">
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password">
                        <button type="button" class="password-toggle-btn" id="togglePasswordBtn" aria-label="Show password" title="Show password">
                            <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg class="eye-off-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3.5 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                <line x1="2" y1="2" x2="22" y2="22"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-check" for="remember">
                        <input type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        <span style="font-size:0.85rem;color:var(--text-secondary);">Remember me</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100">Sign In</button>
            </form>

            <p class="text-center mt-4">
                <a href="{{ route('home') }}" style="font-size:0.85rem;color:var(--text-muted);">← Back to Store</a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Password toggle functionality
            const passwordInput = document.getElementById('password');
            const togglePasswordBtn = document.getElementById('togglePasswordBtn');
            const eyeIcon = togglePasswordBtn ? togglePasswordBtn.querySelector('.eye-icon') : null;
            const eyeOffIcon = togglePasswordBtn ? togglePasswordBtn.querySelector('.eye-off-icon') : null;

            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', function () {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    
                    if (isPassword) {
                        eyeIcon.style.display = 'none';
                        eyeOffIcon.style.display = 'inline-block';
                        togglePasswordBtn.setAttribute('aria-label', 'Hide password');
                        togglePasswordBtn.setAttribute('title', 'Hide password');
                    } else {
                        eyeIcon.style.display = 'inline-block';
                        eyeOffIcon.style.display = 'none';
                        togglePasswordBtn.setAttribute('aria-label', 'Show password');
                        togglePasswordBtn.setAttribute('title', 'Show password');
                    }
                    passwordInput.focus();
                });
            }

            // Remember Me persistence in localStorage for enhanced UX
            const loginForm = document.getElementById('adminLoginForm');
            const emailInput = document.getElementById('email');
            const rememberCheckbox = document.getElementById('remember');

            if (emailInput && rememberCheckbox) {
                // If the email field is empty (no server-side old input), check localStorage
                if (!emailInput.value.trim()) {
                    const savedEmail = localStorage.getItem('pistis_admin_remember_email');
                    if (savedEmail) {
                        emailInput.value = savedEmail;
                        rememberCheckbox.checked = true;
                        if (passwordInput) {
                            passwordInput.focus();
                        }
                    }
                }

                if (loginForm) {
                    loginForm.addEventListener('submit', function () {
                        if (rememberCheckbox.checked && emailInput.value.trim()) {
                            localStorage.setItem('pistis_admin_remember_email', emailInput.value.trim());
                        } else {
                            localStorage.removeItem('pistis_admin_remember_email');
                        }
                    });
                }
            }
        });
    </script>
</body>
</html>
