@extends('layouts.admin')

@section('title', 'Admin Profile & Security')

@section('content')
<style>
    .profile-grid {
        display: grid;
        grid-template-columns: 360px 1fr;
        gap: 24px;
        align-items: flex-start;
    }
    @media (max-width: 992px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }
    }
    .profile-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #09090b;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 700;
        margin: 0 auto 16px auto;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .profile-meta-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f4f4f5;
        font-size: 0.85rem;
    }
    .profile-meta-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .profile-meta-label {
        color: #71717a;
        font-size: 0.8rem;
    }
    .profile-meta-val {
        font-weight: 600;
        color: #18181b;
        text-align: right;
    }
    .password-field-wrapper {
        position: relative;
    }
    .password-toggle-btn {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #71717a;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .password-toggle-btn:hover {
        color: #18181b;
    }
    .perm-check-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 10px;
        border-radius: 6px;
        background: #f4f4f5;
        font-size: 0.8rem;
        color: #27272a;
    }
</style>

<div class="admin-header" style="margin-bottom:24px;">
    <div>
        <h1 style="margin:0 0 6px 0;letter-spacing:0.04em;">ADMINISTRATOR PROFILE & SECURITY</h1>
        <p class="text-muted" style="font-size:0.85rem;margin:0;">
            Manage your personal credentials, update your password, and review account permissions.
        </p>
    </div>
</div>

@if(session('success'))
    <div style="background:#dcfce7;color:#15803d;padding:12px 18px;border-radius:6px;margin-bottom:20px;font-size:0.85rem;border:1px solid #bbf7d0;display:flex;align-items:center;gap:8px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if($errors->any())
    <div style="background:#fef2f2;color:#b91c1c;padding:14px 18px;border-radius:6px;margin-bottom:20px;font-size:0.85rem;border:1px solid #fecaca;">
        <div style="font-weight:600;margin-bottom:4px;">Please review the errors below:</div>
        <ul style="margin:0;padding-left:18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="profile-grid">
    {{-- Left Column: Identity & Permissions --}}
    <div style="display:flex;flex-direction:column;gap:24px;">
        {{-- Card: Account Overview --}}
        <div class="card" style="padding:24px;text-align:center;">
            <div class="profile-avatar-large">
                {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
            </div>
            <h2 style="margin:0 0 4px 0;font-size:1.2rem;letter-spacing:0.02em;">{{ $user->name }}</h2>
            <div class="text-muted" style="font-size:0.85rem;margin-bottom:14px;">{{ $user->email }}</div>

            <div style="display:inline-flex;align-items:center;gap:6px;margin-bottom:20px;">
                @if($user->isSuperAdmin())
                    <span style="background:#09090b;color:#ffffff;font-size:0.72rem;font-weight:700;padding:4px 12px;border-radius:9999px;letter-spacing:0.06em;">👑 SUPER ADMIN</span>
                @else
                    <span style="background:#f4f4f5;color:#27272a;font-size:0.72rem;font-weight:700;padding:4px 12px;border-radius:9999px;letter-spacing:0.06em;border:1px solid #e4e4e7;">{{ strtoupper($user->role_title) }}</span>
                @endif
                <span style="background:#dcfce7;color:#15803d;font-size:0.72rem;font-weight:600;padding:4px 10px;border-radius:9999px;">● Active</span>
            </div>

            <div style="text-align:left;border-top:1px solid #f4f4f5;padding-top:16px;">
                <div class="profile-meta-row">
                    <span class="profile-meta-label">Admin ID</span>
                    <span class="profile-meta-val">#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="profile-meta-row">
                    <span class="profile-meta-label">Role Preset</span>
                    <span class="profile-meta-val">{{ $user->role_title }}</span>
                </div>
                <div class="profile-meta-row">
                    <span class="profile-meta-label">Member Since</span>
                    <span class="profile-meta-val">{{ $user->created_at ? $user->created_at->format('M d, Y') : '—' }}</span>
                </div>
                <div class="profile-meta-row">
                    <span class="profile-meta-label">Last Updated</span>
                    <span class="profile-meta-val">{{ $user->updated_at ? $user->updated_at->diffForHumans() : '—' }}</span>
                </div>
            </div>
        </div>

        {{-- Card: Role & Permissions Summary --}}
        <div class="card" style="padding:24px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                <h3 style="margin:0;font-size:0.95rem;letter-spacing:0.03em;">Role & Permissions</h3>
                <span class="text-muted" style="font-size:0.75rem;">Access Scope</span>
            </div>

            <p class="text-muted" style="font-size:0.8rem;line-height:1.4;margin-bottom:16px;">
                {{ $roles[$user->role]['desc'] ?? 'Permissions configured by the Super Administrator.' }}
            </p>

            @if($user->isSuperAdmin())
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:6px;padding:12px 14px;color:#166534;font-size:0.82rem;display:flex;align-items:center;gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    <span><strong>Full Unrestricted Access:</strong> You have permission to manage all dashboard features, configuration, settings, and team administrators.</span>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:6px;">
                    @foreach($permissions as $permKey => $perm)
                        @php $hasPerm = $user->hasPermission($permKey); @endphp
                        <div class="perm-check-item" style="opacity: {{ $hasPerm ? '1' : '0.45' }}; {{ $hasPerm ? 'background:#f0fdf4;border:1px solid #dcfce7;color:#166534;' : 'background:#f4f4f5;border:1px solid #e4e4e7;text-decoration:line-through;' }}">
                            <span>{{ $perm['icon'] }}</span>
                            <span style="font-weight:500;flex:1;">{{ $perm['label'] }}</span>
                            @if($hasPerm)
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            @else
                                <span style="font-size:0.7rem;color:#71717a;text-decoration:none;">No access</span>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="text-muted" style="font-size:0.72rem;margin-top:12px;text-align:center;">
                    To modify assigned permissions, contact the Super Administrator.
                </div>
            @endif
        </div>
    </div>

    {{-- Right Column: Edit Forms --}}
    <div style="display:flex;flex-direction:column;gap:24px;">
        {{-- Section 1: Profile Information --}}
        <div class="card" style="padding:28px;">
            <div style="margin-bottom:20px;border-bottom:1px solid #f4f4f5;padding-bottom:14px;">
                <h3 style="margin:0 0 4px 0;font-size:1.05rem;letter-spacing:0.02em;">Profile Information</h3>
                <p class="text-muted" style="margin:0;font-size:0.8rem;">Update your name and primary email address used to log into the console.</p>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row" style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#3f3f46;margin-bottom:6px;">
                            Full Name <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required
                               style="width:100%;padding:10px 14px;border:1px solid #d4d4d8;border-radius:6px;font-size:0.9rem;">
                        @error('name')
                            <span class="text-danger" style="font-size:0.75rem;display:block;margin-top:4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#3f3f46;margin-bottom:6px;">
                            Email Address <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required
                               style="width:100%;padding:10px 14px;border:1px solid #d4d4d8;border-radius:6px;font-size:0.9rem;">
                        @error('email')
                            <span class="text-danger" style="font-size:0.75rem;display:block;margin-top:4px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary" style="padding:10px 24px;font-size:0.85rem;display:inline-flex;align-items:center;gap:8px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        <span>Save Profile Changes</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Section 2: Change Password --}}
        <div class="card" id="password-section" style="padding:28px;">
            <div style="margin-bottom:20px;border-bottom:1px solid #f4f4f5;padding-bottom:14px;">
                <h3 style="margin:0 0 4px 0;font-size:1.05rem;letter-spacing:0.02em;">Change Password</h3>
                <p class="text-muted" style="margin:0;font-size:0.8rem;">Ensure your account is using a long, random password to remain secure.</p>
            </div>

            <form action="{{ route('admin.profile.update-password') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom:18px;">
                    <label style="display:block;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#3f3f46;margin-bottom:6px;">
                        Current Password <span style="color:#ef4444;">*</span>
                    </label>
                    <div class="password-field-wrapper">
                        <input type="password" name="current_password" id="current_password_input" class="form-control" required
                               placeholder="Enter your current password"
                               style="width:100%;padding:10px 42px 10px 14px;border:1px solid #d4d4d8;border-radius:6px;font-size:0.9rem;">
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('current_password_input', this)" title="Show/hide password" aria-label="Toggle password visibility">
                            <svg class="eye-open" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            <svg class="eye-closed" style="display:none;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                        </button>
                    </div>
                    @error('current_password')
                        <span class="text-danger" style="font-size:0.75rem;display:block;margin-top:4px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row" style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#3f3f46;margin-bottom:6px;">
                            New Password <span style="color:#ef4444;">*</span>
                        </label>
                        <div class="password-field-wrapper">
                            <input type="password" name="password" id="new_password_input" class="form-control" required minlength="8"
                                   placeholder="Minimum 8 characters"
                                   style="width:100%;padding:10px 42px 10px 14px;border:1px solid #d4d4d8;border-radius:6px;font-size:0.9rem;">
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('new_password_input', this)" title="Show/hide password" aria-label="Toggle password visibility">
                                <svg class="eye-open" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <svg class="eye-closed" style="display:none;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-danger" style="font-size:0.75rem;display:block;margin-top:4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#3f3f46;margin-bottom:6px;">
                            Confirm New Password <span style="color:#ef4444;">*</span>
                        </label>
                        <div class="password-field-wrapper">
                            <input type="password" name="password_confirmation" id="password_confirmation_input" class="form-control" required minlength="8"
                                   placeholder="Re-type new password"
                                   style="width:100%;padding:10px 42px 10px 14px;border:1px solid #d4d4d8;border-radius:6px;font-size:0.9rem;">
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password_confirmation_input', this)" title="Show/hide password" aria-label="Toggle password visibility">
                                <svg class="eye-open" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <svg class="eye-closed" style="display:none;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div style="background:#fafafa;border:1px solid #e4e4e7;border-radius:6px;padding:12px 16px;margin-bottom:20px;font-size:0.78rem;color:#52525b;">
                    <div style="font-weight:600;color:#18181b;margin-bottom:4px;">Password Security Recommendations:</div>
                    <ul style="margin:0;padding-left:18px;line-height:1.5;">
                        <li>Use at least 8 characters with a blend of letters, numbers, and symbols.</li>
                        <li>Avoid reusing passwords from personal email or other services.</li>
                    </ul>
                </div>

                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary" style="padding:10px 24px;font-size:0.85rem;display:inline-flex;align-items:center;gap:8px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <span>Update Password</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Section 3: Browser Sessions & Security --}}
        <div class="card" style="padding:28px;">
            <div style="margin-bottom:16px;border-bottom:1px solid #f4f4f5;padding-bottom:14px;">
                <h3 style="margin:0 0 4px 0;font-size:1.05rem;letter-spacing:0.02em;">Browser Sessions & Device Security</h3>
                <p class="text-muted" style="margin:0;font-size:0.8rem;">Manage your active logins across other devices and browsers.</p>
            </div>

            <div style="display:flex;align-items:flex-start;gap:14px;background:#fafafa;padding:16px;border-radius:6px;border:1px solid #e4e4e7;margin-bottom:20px;">
                <div style="width:36px;height:36px;border-radius:50%;background:#e0e7ff;color:#3730a3;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                </div>
                <div>
                    <div style="font-weight:600;font-size:0.85rem;color:#18181b;">This Browser / Active Session</div>
                    <div class="text-muted" style="font-size:0.78rem;margin-top:2px;">
                        Logged in as <strong>{{ $user->email }}</strong> • Status: <span style="color:#16a34a;font-weight:600;">Active</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.profile.logout-other-devices') }}" method="POST" onsubmit="return confirm('Log out of all other devices and browser sessions?');">
                @csrf
                <div style="display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap;">
                    <div style="flex:1;min-width:240px;">
                        <label style="display:block;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#3f3f46;margin-bottom:6px;">
                            Confirm Password to Terminate Other Sessions
                        </label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Enter your password" required
                               style="width:100%;padding:9px 12px;border:1px solid #d4d4d8;border-radius:6px;font-size:0.85rem;">
                        @error('confirm_password')
                            <span class="text-danger" style="font-size:0.75rem;display:block;margin-top:4px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-secondary" style="font-size:0.85rem;padding:9px 16px;white-space:nowrap;">
                        Sign Out Other Devices
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;

    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';

    const eyeOpen = btn.querySelector('.eye-open');
    const eyeClosed = btn.querySelector('.eye-closed');
    if (eyeOpen && eyeClosed) {
        eyeOpen.style.display = isPassword ? 'none' : 'block';
        eyeClosed.style.display = isPassword ? 'block' : 'none';
    }
}
</script>
@endsection
