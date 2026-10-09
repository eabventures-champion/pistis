<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Dispatched — {{ $order->order_number }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f7f7f8; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; color: #171717;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f7f7f8; padding: 40px 15px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="620" style="max-width: 620px; width: 100%; background-color: #ffffff; border: 1px solid #e5e5e5; border-radius: 4px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);">
                    
                    <!-- Top Brand Bar -->
                    <tr>
                        <td align="center" style="padding: 36px 40px 24px; border-bottom: 1px solid #f0f0f0; background-color: #000000; color: #ffffff;">
                            <div style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 28px; font-weight: 700; letter-spacing: 0.18em; text-transform: uppercase; color: #ffffff;">
                                {{ $storeName }}
                            </div>
                            <div style="font-size: 11px; letter-spacing: 0.25em; text-transform: uppercase; color: #a3a3a3; margin-top: 6px;">
                                Dispatch Notification · Courier Tracking
                            </div>
                        </td>
                    </tr>

                    <!-- Order Dispatched Hero Banner -->
                    <tr>
                        <td style="padding: 36px 40px 20px; text-align: center;">
                            <div style="display: inline-block; padding: 6px 16px; background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 20px; font-size: 12px; font-weight: 600; color: #1d4ed8; letter-spacing: 0.04em; text-transform: uppercase; margin-bottom: 16px;">
                                ✈ Order Has Departed Our Atelier
                            </div>
                            <h1 style="margin: 0 0 10px; font-size: 24px; font-weight: 700; color: #171717; letter-spacing: -0.01em;">
                                Your Package Is On Its Way
                            </h1>
                            <p style="margin: 0 auto; font-size: 14px; color: #525252; line-height: 1.6; max-width: 500px;">
                                Dear <strong>{{ $order->customer_name ? explode(' ', trim($order->customer_name))[0] : 'valued client' }}</strong>, 
                                your bespoke garments have been carefully inspected, packaged, and handed over to our courier partner for priority delivery.
                            </p>
                        </td>
                    </tr>

                    <!-- Reference Pill Banner -->
                    <tr>
                        <td align="center" style="padding: 0 40px 20px;">
                            <div style="display: inline-block; background-color: #f5f5f5; border: 1px solid #e5e5e5; border-radius: 4px; padding: 8px 16px;">
                                <span style="font-size: 11px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #737373; margin-right: 6px;">ORDER NUMBER:</span>
                                <span style="font-family: monospace, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto; font-size: 14px; font-weight: 700; color: #171717; letter-spacing: 0.05em;">{{ $order->order_number }}</span>
                            </div>
                        </td>
                    </tr>

                    <!-- Australia Post Tracking Highlight Box -->
                    <tr>
                        <td style="padding: 0 40px 28px;">
                            <div style="background-color: #0b0f19; border-radius: 6px; padding: 26px 28px; text-align: center; color: #ffffff;">
                                <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: #9ca3af; margin-bottom: 8px;">
                                    COURIER & SHIPMENT DETAILS
                                </div>

                                <div style="font-size: 16px; font-weight: 600; color: #ffffff; margin-bottom: 6px;">
                                    Carrier: <span style="color: #60a5fa;">{{ $trackingCarrier }}</span>
                                </div>

                                @if($trackingNumber)
                                    <div style="margin: 14px 0 20px; background-color: rgba(255, 255, 255, 0.07); border: 1px dashed rgba(255, 255, 255, 0.2); border-radius: 4px; padding: 12px 16px;">
                                        <div style="font-size: 11px; letter-spacing: 0.15em; text-transform: uppercase; color: #9ca3af; margin-bottom: 4px;">
                                            Consignment / Tracking Number
                                        </div>
                                        <div style="font-family: monospace, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto; font-size: 19px; font-weight: 700; color: #ffffff; letter-spacing: 0.1em;">
                                            {{ $trackingNumber }}
                                        </div>
                                    </div>

                                    @if($trackingUrl)
                                        <div style="margin-top: 18px;">
                                            <a href="{{ $trackingUrl }}" target="_blank" style="display: inline-block; background-color: #dc2626; color: #ffffff; font-size: 13px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; text-decoration: none; padding: 13px 28px; border-radius: 4px; box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);">
                                                Track With Australia Post &rarr;
                                            </a>
                                        </div>
                                        <p style="margin: 10px 0 0; font-size: 11px; color: #9ca3af;">
                                            Tracking updates may take 1-3 hours to synchronize on the Australia Post portal after initial scan.
                                        </p>
                                    @endif
                                @else
                                    <div style="margin: 14px 0; background-color: rgba(255, 255, 255, 0.07); border-radius: 4px; padding: 12px 16px; font-size: 13px; color: #d1d5db;">
                                        Your parcel is en route with our local distribution driver. Tracking updates will be reflected in your account dashboard.
                                    </div>
                                    <div style="margin-top: 14px;">
                                        <a href="{{ $accountOrdersUrl }}" target="_blank" style="display: inline-block; background-color: #ffffff; color: #111827; font-size: 13px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; text-decoration: none; padding: 12px 24px; border-radius: 4px;">
                                            View Order In Account &rarr;
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- Shipping Address & Order Summary Preview -->
                    <tr>
                        <td style="padding: 0 40px 24px;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td width="50%" valign="top" style="padding-right: 12px;">
                                        <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #737373; margin-bottom: 8px; border-bottom: 1px solid #eee; padding-bottom: 4px;">
                                            Destination Address
                                        </div>
                                        <div style="font-size: 13px; line-height: 1.5; color: #404040;">
                                            <strong>{{ $order->customer_name }}</strong><br>
                                            @if(!empty($order->shipping_address))
                                                @if(!empty($order->shipping_address['address']))
                                                    {{ $order->shipping_address['address'] }}<br>
                                                @elseif(!empty($order->shipping_address['address_line1']))
                                                    {{ $order->shipping_address['address_line1'] }}<br>
                                                @endif
                                                @if(!empty($order->shipping_address['address_line2']))
                                                    {{ $order->shipping_address['address_line2'] }}<br>
                                                @endif
                                                {{ $order->shipping_address['city'] ?? '' }}
                                                @if(!empty($order->shipping_address['state']))
                                                    , {{ $order->shipping_address['state'] }}
                                                @endif
                                                {{ $order->shipping_address['postal_code'] ?? '' }}<br>
                                                {{ $order->shipping_address['country'] ?? 'Australia' }}
                                            @else
                                                <em>Address on file</em>
                                            @endif
                                        </div>
                                    </td>
                                    <td width="50%" valign="top" style="padding-left: 12px;">
                                        <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #737373; margin-bottom: 8px; border-bottom: 1px solid #eee; padding-bottom: 4px;">
                                            Shipment Summary
                                        </div>
                                        <div style="font-size: 13px; line-height: 1.5; color: #404040;">
                                            <strong>Dispatched:</strong> {{ now()->format('d M Y, h:i A') }}<br>
                                            <strong>Items:</strong> {{ $order->items->sum('quantity') }} piece(s)<br>
                                            <strong>Total Value:</strong> {{ $currencySymbol }}{{ number_format($order->total, 2) }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Order Items List -->
                    <tr>
                        <td style="padding: 0 40px 28px;">
                            <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #737373; margin-bottom: 12px; border-bottom: 1px solid #eee; padding-bottom: 6px;">
                                Package Contents
                            </div>
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                @foreach($items as $item)
                                    <tr>
                                        <td style="padding: 10px 0; border-bottom: 1px solid #f5f5f5; font-size: 13px; color: #171717;">
                                            <div style="font-weight: 600;">{{ $item->product_name ?? $item->product?->name ?? 'Pistis Product' }}</div>
                                            <div style="font-size: 11px; color: #737373; margin-top: 2px;">
                                                Qty: {{ $item->quantity }}
                                                @if(!empty($item->options['size']))
                                                    · Size: {{ $item->options['size'] }}
                                                @endif
                                                @if(!empty($item->options['color']))
                                                    · Color: {{ $item->options['color'] }}
                                                @endif
                                            </div>
                                        </td>
                                        <td align="right" style="padding: 10px 0; border-bottom: 1px solid #f5f5f5; font-size: 13px; font-weight: 600; color: #171717; vertical-align: top;">
                                            {{ $currencySymbol }}{{ number_format($item->subtotal ?? ($item->price * $item->quantity), 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>

                    <!-- Action Buttons -->
                    <tr>
                        <td align="center" style="padding: 10px 40px 32px;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    @if($trackingUrl)
                                        <td style="padding-right: 12px;">
                                            <a href="{{ $trackingUrl }}" target="_blank" style="display: inline-block; background-color: #000000; color: #ffffff; font-size: 12px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; text-decoration: none; padding: 12px 22px; border-radius: 4px;">
                                                Track AusPost Shipment
                                            </a>
                                        </td>
                                    @endif
                                    <td>
                                        <a href="{{ $orderSuccessUrl }}" target="_blank" style="display: inline-block; background-color: #f5f5f5; border: 1px solid #e5e5e5; color: #171717; font-size: 12px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; text-decoration: none; padding: 12px 22px; border-radius: 4px;">
                                            View Receipt Online
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Help / Footer -->
                    <tr>
                        <td style="padding: 24px 40px; background-color: #fafafa; border-top: 1px solid #eeeeee; text-align: center;">
                            <div style="font-size: 12px; color: #737373; line-height: 1.6;">
                                Questions regarding your delivery? Contact our concierge team at <a href="mailto:{{ $storeEmail }}" style="color: #171717; text-decoration: underline;">{{ $storeEmail }}</a>.
                            </div>
                            <div style="font-size: 11px; color: #a3a3a3; margin-top: 12px; letter-spacing: 0.04em;">
                                &copy; {{ date('Y') }} {{ $storeName }}. All rights reserved.
                            </div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
