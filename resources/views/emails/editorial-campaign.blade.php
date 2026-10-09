<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailSubject }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f7f7f8; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; color: #171717;">
    @if(!empty($preheader))
        <div style="display:none;font-size:1px;color:#f7f7f8;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;">
            {{ $preheader }}
        </div>
    @endif

    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f7f7f8; padding: 40px 15px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="620" style="max-width: 620px; width: 100%; background-color: #ffffff; border: 1px solid #e5e5e5; border-radius: 4px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);">
                    
                    <!-- Top Brand Bar -->
                    <tr>
                        <td align="center" style="padding: 38px 40px 24px; border-bottom: 1px solid #f0f0f0; background-color: #000000; color: #ffffff;">
                            <div style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 28px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: #ffffff;">
                                {{ $storeName }}
                            </div>
                            <div style="font-size: 10px; letter-spacing: 0.28em; text-transform: uppercase; color: #a3a3a3; margin-top: 8px;">
                                Editorial Lookbook · Inner Circle Private Release
                            </div>
                        </td>
                    </tr>

                    @if(!empty($bannerUrl))
                        <tr>
                            <td align="center" style="padding: 0; background-color: #000000;">
                                <img src="{{ $bannerUrl }}" alt="{{ $headline }}" style="width: 100%; max-width: 620px; height: auto; display: block;">
                            </td>
                        </tr>
                    @endif

                    <!-- Headline & Badge -->
                    <tr>
                        <td style="padding: 36px 40px 20px; text-align: center;">
                            <div style="display: inline-block; padding: 5px 14px; background-color: #f4f4f5; border: 1px solid #e4e4e7; border-radius: 20px; font-size: 11px; font-weight: 600; color: #27272a; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 16px;">
                                📖 Private Capsule Lookbook
                            </div>
                            <h1 style="margin: 0 0 14px; font-family: 'Cormorant Garamond', Georgia, serif; font-size: 28px; font-weight: 600; color: #09090b; letter-spacing: 0.02em; line-height: 1.3;">
                                {{ $headline }}
                            </h1>
                        </td>
                    </tr>

                    <!-- Editorial Content Body -->
                    <tr>
                        <td style="padding: 0 40px 32px; font-size: 14.5px; color: #404040; line-height: 1.75;">
                            <div style="white-space: pre-line;">{!! nl2br(e($messageContent)) !!}</div>
                        </td>
                    </tr>

                    @if(!empty($ctaUrl))
                        <!-- Call to Action -->
                        <tr>
                            <td align="center" style="padding: 0 40px 40px;">
                                <a href="{{ $ctaUrl }}" style="display: inline-block; background-color: #000000; color: #ffffff; text-decoration: none; padding: 14px 34px; font-size: 12px; font-weight: 600; letter-spacing: 0.16em; text-transform: uppercase; border-radius: 2px;">
                                    {{ $ctaText ?: 'Explore The Lookbook →' }}
                                </a>
                            </td>
                        </tr>
                    @endif

                    <!-- Footer Note -->
                    <tr>
                        <td style="padding: 24px 40px; background-color: #fafafa; border-top: 1px solid #eeeeee; text-align: center; font-size: 11px; color: #888888; line-height: 1.6;">
                            <div>You are receiving this private release as an enrolled member of the {{ $storeName }} Inner Circle ({{ $recipientEmail ?: 'VIP subscriber' }}).</div>
                            <div style="margin-top: 8px;">&copy; {{ date('Y') }} {{ $storeName }} Collections. All rights reserved.</div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
