<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Team Invitation — Pistis</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ file_exists(public_path('css/app.css')) ? filemtime(public_path('css/app.css')) : time() }}">
    <style>
        .password-input-wrapper {
            position: relative !important;
            display: flex !important;
            align-items: center !important;
            width: 100% !important;
        }
        .password-input-wrapper .form-control {
            padding-right: 44px !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        .password-toggle-btn {
            position: absolute !important;
            right: 12px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            background: transparent !important;
            border: none !important;
            padding: 6px !important;
            cursor: pointer !important;
            color: #8a8a8a !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 4px !important;
            outline: none !important;
        }
        .password-toggle-btn:hover {
            color: #000000 !important;
            background: rgba(0, 0, 0, 0.05) !important;
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card" style="max-width: 480px;">
            <div class="text-center mb-4">
                <div style="display:inline-flex;flex-direction:column;align-items:center;gap:12px;margin-bottom:16px;">
                    @if(!empty($store_logo))
                        <img src="{{ $store_logo }}" alt="{{ $store_name ?? 'Pistis' }}" style="width:68px; height:68px; border-radius:50%; aspect-ratio:1/1; object-fit:contain; box-shadow:0 4px 16px rgba(0,0,0,0.12);">
                    @else
                        <span class="brand-icon" style="width:52px; height:52px; font-size:1.5rem; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; background:#000; color:#fff;">P</span>
                    @endif
                    <span style="font-family:'Inter', sans-serif; font-size:0.8rem; font-weight:700; letter-spacing:0.2em; text-transform:uppercase; color:var(--text-secondary);">Administrative Team Invitation</span>
                </div>
            </div>

            <h2 class="auth-title">Complete Account Setup</h2>
            <p class="auth-subtitle">Welcome, <strong>{{ $user->name }}</strong>. Set a secure password to activate your access.</p>

            <div style="background:#f4f4f5; border:1px solid #e4e4e7; border-radius:6px; padding:14px 16px; margin-bottom:24px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                    <span style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.06em; color:#71717a; font-weight:700;">Assigned Role</span>
                    <span style="background:#09090b; color:#ffffff; font-size:0.7rem; font-weight:700; padding:3px 8px; border-radius:12px; letter-spacing:0.05em;">{{ $user->role_badge }}</span>
                </div>
                <div style="font-size:0.95rem; font-weight:700; color:#18181b; margin-bottom:6px;">{{ $user->role_title }}</div>
                <div style="font-size:0.78rem; color:#525252;">Email: <strong>{{ $user->email }}</strong></div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul style="margin:0; padding-left:18px; font-size:0.85rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.invitations.process', $token) }}">
                @csrf

                <div class="form-group mb-3">
                    <label for="password" class="form-label">Create Password</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Min. 8 characters" required autofocus minlength="8">
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password', this)" title="Show/Hide Password" aria-label="Toggle password visibility">
                            <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm your password" required minlength="8">
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password_confirmation', this)" title="Show/Hide Password" aria-label="Toggle password confirmation visibility">
                            <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="padding:12px; font-size:0.85rem; letter-spacing:0.1em; text-transform:uppercase;">
                    Activate Account & Access Dashboard →
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const openIcon = btn.querySelector('.eye-open');
            const closedIcon = btn.querySelector('.eye-closed');
            
            if (input.type === 'password') {
                input.type = 'text';
                openIcon.style.display = 'none';
                closedIcon.style.display = 'inline';
            } else {
                input.type = 'password';
                openIcon.style.display = 'inline';
                closedIcon.style.display = 'none';
            }
        }
    </script>
</body>
</html>
