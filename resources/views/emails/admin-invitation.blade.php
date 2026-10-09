<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Invitation — {{ $storeName }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f7f7f8; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; color: #171717;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f7f7f8; padding: 40px 15px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="max-width: 600px; width: 100%; background-color: #ffffff; border: 1px solid #e5e5e5; border-radius: 4px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);">
                    
                    <!-- Top Brand Bar -->
                    <tr>
                        <td align="center" style="padding: 38px 40px 24px; border-bottom: 1px solid #f0f0f0; background-color: #000000; color: #ffffff;">
                            <div style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 28px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: #ffffff;">
                                {{ $storeName }}
                            </div>
                            <div style="font-size: 10px; letter-spacing: 0.28em; text-transform: uppercase; color: #a3a3a3; margin-top: 8px;">
                                Atelier Administration · Staff Portal
                            </div>
                        </td>
                    </tr>

                    <!-- Invitation Header -->
                    <tr>
                        <td style="padding: 36px 40px 20px; text-align: center;">
                            <div style="display: inline-block; padding: 5px 14px; background-color: #f4f4f5; border: 1px solid #e4e4e7; border-radius: 20px; font-size: 11px; font-weight: 600; color: #27272a; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 18px;">
                                👥 Staff Team Invitation
                            </div>
                            <h1 style="margin: 0 0 12px; font-family: 'Cormorant Garamond', Georgia, serif; font-size: 28px; font-weight: 600; color: #09090b; letter-spacing: 0.02em; line-height: 1.3;">
                                You’ve Been Invited to Manage {{ $storeName }}
                            </h1>
                            <p style="margin: 0 auto; font-size: 14px; color: #525252; line-height: 1.6; max-width: 480px;">
                                Hello <strong>{{ $invitedUser->name }}</strong>, you have been invited by 
                                <strong>{{ $inviter ? $inviter->name : 'the Super Administrator' }}</strong> 
                                to join the administrative team as a <strong>{{ $invitedUser->role_title }}</strong>.
                            </p>
                        </td>
                    </tr>

                    <!-- Role & Assigned Permissions Card -->
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <div style="background-color: #fafafa; border: 1px solid #e5e5e5; border-radius: 4px; padding: 24px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eaeaea; padding-bottom: 12px; margin-bottom: 14px;">
                                    <div>
                                        <div style="font-size: 10px; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: #737373;">
                                            Assigned Role
                                        </div>
                                        <div style="font-size: 16px; font-weight: 700; color: #09090b; margin-top: 2px;">
                                            {{ $invitedUser->role_title }}
                                        </div>
                                    </div>
                                    <div style="font-size: 11px; font-weight: 700; background: #000000; color: #ffffff; padding: 4px 10px; border-radius: 12px; letter-spacing: 0.05em;">
                                        {{ $invitedUser->role_badge }}
                                    </div>
                                </div>

                                <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #737373; margin-bottom: 8px;">
                                    Permitted Sections:
                                </div>
                                <div style="font-size: 12.5px; color: #3f3f46; line-height: 1.6;">
                                    @if($invitedUser->isSuperAdmin())
                                        <span>✓ Complete Unrestricted Administrative Access</span>
                                    @elseif(!empty($invitedUser->permissions))
                                        @foreach($invitedUser->permissions as $permKey)
                                            <span style="display: inline-block; background: #ffffff; border: 1px solid #e4e4e7; border-radius: 4px; padding: 3px 8px; margin: 2px 4px 2px 0; font-size: 11px; color: #27272a;">
                                                ✓ {{ \App\Models\User::AVAILABLE_PERMISSIONS[$permKey]['label'] ?? $permKey }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span>Dashboard overview</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- CTA Button -->
                    <tr>
                        <td align="center" style="padding: 0 40px 36px;">
                            <a href="{{ $acceptUrl }}" style="display: inline-block; background-color: #000000; color: #ffffff; text-decoration: none; padding: 14px 34px; font-size: 12px; font-weight: 600; letter-spacing: 0.15em; text-transform: uppercase; border-radius: 2px;">
                                Set Password & Access Dashboard →
                            </a>
                            <div style="margin-top: 14px; font-size: 11.5px; color: #888888;">
                                This invitation link will expire in 7 days.
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 40px; background-color: #fafafa; border-top: 1px solid #eeeeee; text-align: center; font-size: 11px; color: #888888; line-height: 1.6;">
                            <div>If you did not expect this invitation, you can safely ignore this email.</div>
                            <div style="margin-top: 8px;">&copy; {{ date('Y') }} {{ $storeName }} Collections. All rights reserved.</div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
