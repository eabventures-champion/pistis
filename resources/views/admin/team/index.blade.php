@extends('layouts.admin')

@section('title', 'Team & Permissions')

@section('content')
<style>
    .table td, .table th {
        vertical-align: middle !important;
    }
    .team-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 9999px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        white-space: nowrap !important;
    }
    .team-badge-super {
        background: #09090b;
        color: #ffffff;
    }
    .team-badge-manager {
        background: #e0e7ff;
        color: #3730a3;
        border: 1px solid #c7d2fe;
    }
    .team-badge-editor {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    .team-badge-fulfillment {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }
    .team-badge-marketing {
        background: #fce7f3;
        color: #9d174d;
        border: 1px solid #fbcfe8;
    }
    .team-badge-custom {
        background: #f4f4f5;
        color: #27272a;
        border: 1px solid #e4e4e7;
    }

    .perm-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.72rem;
        background: #f4f4f5;
        color: #3f3f46;
        border: 1px solid #e4e4e7;
        margin: 2px 2px;
        white-space: nowrap !important;
    }

    .action-icon-btn {
        background: #ffffff;
        border: 1px solid #e4e4e7;
        border-radius: 6px;
        padding: 6px 12px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.78rem;
        font-weight: 500;
        color: #27272a;
        transition: all 0.15s;
        white-space: nowrap !important;
    }
    .action-icon-btn:hover {
        background: #f4f4f5;
        border-color: #d4d4d8;
    }
    .action-icon-btn.btn-danger-soft {
        color: #b91c1c;
        border-color: #fecaca;
        background: #fff5f5;
    }
    .action-icon-btn.btn-danger-soft:hover {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fca5a5;
    }

    .admin-actions-wrap {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap !important;
        justify-content: flex-end;
    }

    /* Modal styling */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 20px;
    }
    .modal-overlay.is-active {
        display: flex;
    }
    .modal-card {
        background: #ffffff;
        border-radius: 8px;
        width: 100%;
        max-width: 680px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
    }
</style>

<div class="admin-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div>
        <h1 style="margin:0 0 6px 0;letter-spacing:0.04em;">ADMINISTRATIVE TEAM & ROLES</h1>
        <p class="text-muted" style="font-size:0.85rem;margin:0;">
            Invite administrators, assign roles, and restrict sidebar menu access with granular permissions.
        </p>
    </div>

    <div>
        <button type="button" class="btn btn-primary" onclick="openInviteModal()" style="display:inline-flex;align-items:center;gap:6px;font-size:0.85rem;">
            <span>+ Invite New Admin</span>
        </button>
    </div>
</div>

@if(session('success'))
    <div style="background:#dcfce7;color:#15803d;padding:12px 18px;border-radius:6px;margin-bottom:20px;font-size:0.85rem;border:1px solid #bbf7d0;">
        ✓ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background:#fee2e2;color:#b91c1c;padding:12px 18px;border-radius:6px;margin-bottom:20px;font-size:0.85rem;border:1px solid #fecaca;">
        ✕ {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div style="background:#fee2e2;color:#b91c1c;padding:12px 18px;border-radius:6px;margin-bottom:20px;font-size:0.85rem;border:1px solid #fecaca;">
        <ul style="margin:0;padding-left:18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Top KPI Cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;margin-bottom:24px;">
    <div class="card" style="padding:18px 20px;">
        <span class="text-muted" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.06em;font-weight:600;">Total Administrators</span>
        <div style="font-size:1.75rem;font-weight:700;margin-top:6px;color:var(--text-primary);">{{ $counts['total'] }}</div>
        <span class="text-muted" style="font-size:0.75rem;margin-top:4px;display:block;">All staff & managers</span>
    </div>
    <div class="card" style="padding:18px 20px;">
        <span class="text-muted" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.06em;font-weight:600;">Active Staff</span>
        <div style="font-size:1.75rem;font-weight:700;margin-top:6px;color:#15803d;">{{ $counts['active'] }}</div>
        <span class="text-muted" style="font-size:0.75rem;margin-top:4px;display:block;">Active dashboard logins</span>
    </div>
    <div class="card" style="padding:18px 20px;">
        <span class="text-muted" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.06em;font-weight:600;">Pending Invitations</span>
        <div style="font-size:1.75rem;font-weight:700;margin-top:6px;color:#d97706;">{{ $counts['pending'] }}</div>
        <span class="text-muted" style="font-size:0.75rem;margin-top:4px;display:block;">Awaiting password setup</span>
    </div>
    <div class="card" style="padding:18px 20px;">
        <span class="text-muted" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.06em;font-weight:600;">Suspended Access</span>
        <div style="font-size:1.75rem;font-weight:700;margin-top:6px;color:#dc2626;">{{ $counts['suspended'] }}</div>
        <span class="text-muted" style="font-size:0.75rem;margin-top:4px;display:block;">Logins temporarily blocked</span>
    </div>
</div>

{{-- Team Table --}}
<div class="card">
    <div class="table-responsive">
        <table class="table" style="width:100%;margin:0;">
            <thead>
                <tr style="border-bottom:1px solid #f4f4f5;background:#fafafa;font-size:0.72rem;letter-spacing:0.06em;text-transform:uppercase;color:#71717a;">
                    <th style="padding:14px 20px;text-align:left;vertical-align:middle;">Administrator</th>
                    <th style="padding:14px 16px;text-align:left;vertical-align:middle;white-space:nowrap;">Role</th>
                    <th style="padding:14px 16px;text-align:left;vertical-align:middle;white-space:nowrap;">Permitted Sidebar Menus</th>
                    <th style="padding:14px 16px;text-align:left;vertical-align:middle;white-space:nowrap;">Status</th>
                    <th style="padding:14px 20px;text-align:right;vertical-align:middle;white-space:nowrap;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $admin)
                    <tr style="border-bottom:1px solid #f4f4f5;font-size:0.85rem;">
                        <td style="padding:16px 20px;vertical-align:middle;">
                            <div style="display:flex;align-items:center;gap:12px;">
                                <div style="width:38px;height:38px;border-radius:50%;background:#09090b;color:#ffffff;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0;">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:6px;">
                                        {{ $admin->name }}
                                        @if($admin->id === auth()->id())
                                            <span style="font-size:0.65rem;background:#f4f4f5;color:#525252;padding:1px 6px;border-radius:4px;font-weight:600;">YOU</span>
                                        @endif
                                    </div>
                                    <div class="text-muted" style="font-size:0.78rem;">{{ $admin->email }}</div>
                                </div>
                            </div>
                        </td>

                        <td style="padding:16px 16px;vertical-align:middle;white-space:nowrap;">
                            @if($admin->isSuperAdmin())
                                <span class="team-badge team-badge-super">👑 Super Admin</span>
                            @elseif($admin->role === 'store_manager')
                                <span class="team-badge team-badge-manager">Store Manager</span>
                            @elseif($admin->role === 'catalog_editor')
                                <span class="team-badge team-badge-editor">Catalog Editor</span>
                            @elseif($admin->role === 'sales_fulfillment')
                                <span class="team-badge team-badge-fulfillment">Order Fulfillment</span>
                            @elseif($admin->role === 'marketing_lookbook')
                                <span class="team-badge team-badge-marketing">Marketing Lookbook</span>
                            @else
                                <span class="team-badge team-badge-custom">Custom Role</span>
                            @endif
                        </td>

                        <td style="padding:16px 16px;vertical-align:middle;white-space:nowrap;">
                            @if($admin->isSuperAdmin())
                                <span style="font-size:0.78rem;color:#15803d;font-weight:600;white-space:nowrap;">✓ Full Unrestricted Access (All Menus)</span>
                            @else
                                <div style="display:flex;flex-wrap:wrap;gap:4px;max-width:340px;">
                                    @foreach($admin->permissions ?? [] as $permKey)
                                        @if(isset($permissions[$permKey]))
                                            <span class="perm-pill" title="{{ $permissions[$permKey]['desc'] }}">
                                                {{ $permissions[$permKey]['icon'] }} {{ $permissions[$permKey]['label'] }}
                                            </span>
                                        @endif
                                    @endforeach
                                    @if(empty($admin->permissions))
                                        <span class="text-muted" style="font-size:0.75rem;">Dashboard only</span>
                                    @endif
                                </div>
                            @endif
                        </td>

                        <td style="padding:16px 16px;vertical-align:middle;white-space:nowrap;">
                            @if($admin->isSuspended())
                                <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:12px;background:#fee2e2;color:#b91c1c;font-size:0.74rem;font-weight:600;white-space:nowrap;">
                                    <span style="width:6px;height:6px;border-radius:50%;background:#dc2626;"></span>
                                    Suspended
                                </span>
                            @elseif($admin->isPendingInvitation())
                                <div style="white-space:nowrap;">
                                    <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:12px;background:#fef3c7;color:#92400e;font-size:0.74rem;font-weight:600;white-space:nowrap;">
                                        <span style="width:6px;height:6px;border-radius:50%;background:#d97706;"></span>
                                        Invited (Pending)
                                    </span>
                                    @if($admin->invitation_expires_at)
                                        <div class="text-muted" style="font-size:0.7rem;margin-top:2px;">
                                            Expires {{ $admin->invitation_expires_at->diffForHumans() }}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:12px;background:#dcfce7;color:#15803d;font-size:0.74rem;font-weight:600;white-space:nowrap;">
                                    <span style="width:6px;height:6px;border-radius:50%;background:#16a34a;"></span>
                                    Active
                                </span>
                            @endif
                        </td>

                        <td style="padding:16px 20px;text-align:right;vertical-align:middle;white-space:nowrap;">
                            <div class="admin-actions-wrap">
                                {{-- Edit Role/Permissions --}}
                                <button type="button" class="action-icon-btn" 
                                        onclick="openEditModal({{ json_encode([
                                            'id' => $admin->id,
                                            'name' => $admin->name,
                                            'email' => $admin->email,
                                            'role' => $admin->role,
                                            'is_super_admin' => $admin->is_super_admin,
                                            'permissions' => $admin->permissions ?? [],
                                        ]) }})"
                                        title="Edit Role & Permissions">
                                    ✏️ Edit
                                </button>

                                {{-- Pending Invitation Actions --}}
                                @if($admin->isPendingInvitation())
                                    <button type="button" class="action-icon-btn" 
                                            onclick="copyInviteLink('{{ route('admin.invitations.accept', $admin->invitation_token) }}')" 
                                            title="Copy invitation link directly">
                                        🔗 Copy Link
                                    </button>

                                    <form action="{{ route('admin.team.resend-invite', $admin) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" class="action-icon-btn" title="Resend invitation email">
                                            ✉️ Resend
                                        </button>
                                    </form>
                                @endif

                                {{-- Suspend / Restore (Only for non-self and non-primary) --}}
                                @if($admin->id !== auth()->id() && (!$admin->isSuperAdmin() || \App\Models\User::where('is_super_admin', true)->count() > 1))
                                    <form action="{{ route('admin.team.toggle-status', $admin) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" class="action-icon-btn" title="{{ $admin->isSuspended() ? 'Reactivate administrator' : 'Suspend administrator' }}">
                                            @if($admin->isSuspended())
                                                <span style="color:#16a34a;">Restore</span>
                                            @else
                                                <span style="color:#d97706;">Suspend</span>
                                            @endif
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.team.destroy', $admin) }}" method="POST" style="margin:0;" onsubmit="return confirm('Remove administrator {{ $admin->name }}? All dashboard access will be permanently revoked.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-icon-btn btn-danger-soft" title="Delete administrator">
                                            ✕
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ─── Modal 1: Invite New Administrator ─────────────────────────── --}}
<div class="modal-overlay" id="invite-modal" onclick="closeInviteModal(event)">
    <div class="modal-card" onclick="event.stopPropagation()">
        <div style="padding:20px 24px;border-bottom:1px solid #f4f4f5;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <h3 style="margin:0;font-size:1.1rem;letter-spacing:0.02em;">Invite Administrator</h3>
                <span class="text-muted" style="font-size:0.8rem;">Send an invitation email with assigned dashboard permissions</span>
            </div>
            <button type="button" onclick="closeInviteModal()" style="background:none;border:none;font-size:1.2rem;cursor:pointer;">✕</button>
        </div>

        <form action="{{ route('admin.team.store') }}" method="POST" style="padding:24px;">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px;">
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#3f3f46;margin-bottom:6px;">
                        Full Name <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Marcus Vance" required
                           style="width:100%;padding:9px 12px;border:1px solid #d4d4d8;border-radius:6px;font-size:0.88rem;">
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#3f3f46;margin-bottom:6px;">
                        Email Address <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="email" name="email" class="form-control" placeholder="colleague@pistiscollections.com.au" required
                           style="width:100%;padding:9px 12px;border:1px solid #d4d4d8;border-radius:6px;font-size:0.88rem;">
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#3f3f46;margin-bottom:6px;">
                    Role Preset <span style="color:#ef4444;">*</span>
                </label>
                <select name="role" id="invite-role-select" onchange="handleRolePresetChange('invite', this.value)"
                        style="width:100%;padding:10px 12px;border:1px solid #d4d4d8;border-radius:6px;font-size:0.88rem;background:#ffffff;">
                    @foreach($roles as $key => $role)
                        <option value="{{ $key }}" {{ $key === 'store_manager' ? 'selected' : '' }}>
                            {{ $role['name'] }} — {{ $role['desc'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom:24px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                    <label style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#3f3f46;margin:0;">
                        Sidebar Menu Access & Permissions
                    </label>
                    <span id="invite-perm-notice" class="text-muted" style="font-size:0.75rem;">Preset automatically checked</span>
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(260px, 1fr));gap:10px;background:#fafafa;padding:16px;border-radius:6px;border:1px solid #e4e4e7;">
                    @foreach($permissions as $permKey => $perm)
                        <label style="display:flex;align-items:flex-start;gap:8px;font-size:0.82rem;cursor:pointer;padding:6px;border-radius:4px;background:#ffffff;border:1px solid #f4f4f5;">
                            <input type="checkbox" name="permissions[]" value="{{ $permKey }}" class="invite-perm-checkbox"
                                   id="invite-perm-{{ $permKey }}" style="margin-top:2px;">
                            <div>
                                <div style="font-weight:600;color:#18181b;">{{ $perm['icon'] }} {{ $perm['label'] }}</div>
                                <div class="text-muted" style="font-size:0.72rem;line-height:1.3;">{{ $perm['desc'] }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;">
                <button type="button" onclick="closeInviteModal()" class="btn btn-secondary" style="font-size:0.85rem;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="font-size:0.85rem;padding:9px 20px;">Send Invitation Email →</button>
            </div>
        </form>
    </div>
</div>

{{-- ─── Modal 2: Edit Role & Permissions ─────────────────────────── --}}
<div class="modal-overlay" id="edit-modal" onclick="closeEditModal(event)">
    <div class="modal-card" onclick="event.stopPropagation()">
        <div style="padding:20px 24px;border-bottom:1px solid #f4f4f5;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <h3 style="margin:0;font-size:1.1rem;letter-spacing:0.02em;">Edit Administrator Role & Permissions</h3>
                <span id="edit-admin-email-text" class="text-muted" style="font-size:0.8rem;"></span>
            </div>
            <button type="button" onclick="closeEditModal()" style="background:none;border:none;font-size:1.2rem;cursor:pointer;">✕</button>
        </div>

        <form id="edit-admin-form" method="POST" style="padding:24px;">
            @csrf
            @method('PATCH')

            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#3f3f46;margin-bottom:6px;">
                    Full Name <span style="color:#ef4444;">*</span>
                </label>
                <input type="text" name="name" id="edit-name-input" class="form-control" required
                       style="width:100%;padding:9px 12px;border:1px solid #d4d4d8;border-radius:6px;font-size:0.88rem;">
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#3f3f46;margin-bottom:6px;">
                    Role Preset <span style="color:#ef4444;">*</span>
                </label>
                <select name="role" id="edit-role-select" onchange="handleRolePresetChange('edit', this.value)"
                        style="width:100%;padding:10px 12px;border:1px solid #d4d4d8;border-radius:6px;font-size:0.88rem;background:#ffffff;">
                    @foreach($roles as $key => $role)
                        <option value="{{ $key }}">
                            {{ $role['name'] }} — {{ $role['desc'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom:24px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                    <label style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#3f3f46;margin:0;">
                        Sidebar Menu Access & Permissions
                    </label>
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(260px, 1fr));gap:10px;background:#fafafa;padding:16px;border-radius:6px;border:1px solid #e4e4e7;">
                    @foreach($permissions as $permKey => $perm)
                        <label style="display:flex;align-items:flex-start;gap:8px;font-size:0.82rem;cursor:pointer;padding:6px;border-radius:4px;background:#ffffff;border:1px solid #f4f4f5;">
                            <input type="checkbox" name="permissions[]" value="{{ $permKey }}" class="edit-perm-checkbox"
                                   id="edit-perm-{{ $permKey }}" style="margin-top:2px;">
                            <div>
                                <div style="font-weight:600;color:#18181b;">{{ $perm['icon'] }} {{ $perm['label'] }}</div>
                                <div class="text-muted" style="font-size:0.72rem;line-height:1.3;">{{ $perm['desc'] }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;">
                <button type="button" onclick="closeEditModal()" class="btn btn-secondary" style="font-size:0.85rem;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="font-size:0.85rem;padding:9px 20px;">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
const rolePresets = @json($roles);

function handleRolePresetChange(prefix, role) {
    const checkboxes = document.querySelectorAll(`.${prefix}-perm-checkbox`);
    const isSuper = (role === 'super_admin');

    if (isSuper) {
        checkboxes.forEach(cb => {
            cb.checked = true;
            cb.disabled = true;
        });
        const notice = document.getElementById(`${prefix}-perm-notice`);
        if (notice) notice.textContent = 'Super Admin has full unrestricted access to all menus';
        return;
    }

    checkboxes.forEach(cb => {
        cb.disabled = false;
    });

    if (role === 'custom') {
        const notice = document.getElementById(`${prefix}-perm-notice`);
        if (notice) notice.textContent = 'Select custom menu permissions below';
        return;
    }

    const presetPerms = (rolePresets[role] && rolePresets[role].permissions) ? rolePresets[role].permissions : [];
    checkboxes.forEach(cb => {
        cb.checked = presetPerms.includes(cb.value);
    });

    const notice = document.getElementById(`${prefix}-perm-notice`);
    if (notice) notice.textContent = 'Preset permissions applied';
}

function openInviteModal() {
    document.getElementById('invite-modal').classList.add('is-active');
    handleRolePresetChange('invite', document.getElementById('invite-role-select').value);
}

function closeInviteModal(e) {
    if (e && e.target !== e.currentTarget && e.target.tagName !== 'BUTTON') return;
    document.getElementById('invite-modal').classList.remove('is-active');
}

function openEditModal(admin) {
    document.getElementById('edit-admin-email-text').textContent = admin.email;
    document.getElementById('edit-name-input').value = admin.name;
    document.getElementById('edit-role-select').value = admin.role;
    document.getElementById('edit-admin-form').action = `/admin/team/${admin.id}`;

    const checkboxes = document.querySelectorAll('.edit-perm-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = false;
        cb.disabled = false;
    });

    if (admin.is_super_admin || admin.role === 'super_admin') {
        checkboxes.forEach(cb => {
            cb.checked = true;
            cb.disabled = true;
        });
    } else {
        const userPerms = admin.permissions || [];
        checkboxes.forEach(cb => {
            cb.checked = userPerms.includes(cb.value);
        });
    }

    document.getElementById('edit-modal').classList.add('is-active');
}

function closeEditModal(e) {
    if (e && e.target !== e.currentTarget && e.target.tagName !== 'BUTTON') return;
    document.getElementById('edit-modal').classList.remove('is-active');
}

function copyInviteLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        alert('Invitation link copied to clipboard!\n\n' + url);
    }).catch(() => {
        prompt('Copy this invitation link:', url);
    });
}
</script>
@endsection
