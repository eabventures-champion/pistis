<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Confirmed — {{ $order->order_number }}</title>
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
                                Luxury Store Notification · New Order
                            </div>
                        </td>
                    </tr>

                    <!-- Order Confirmed Hero Banner -->
                    <tr>
                        <td style="padding: 32px 40px 20px; text-align: center;">
                            <div style="display: inline-block; padding: 5px 14px; background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 20px; font-size: 12px; font-weight: 600; color: #166534; letter-spacing: 0.04em; text-transform: uppercase; margin-bottom: 16px;">
                                ✓ Order Confirmed & Paid
                            </div>
                            <h1 style="margin: 0 0 8px; font-size: 24px; font-weight: 700; color: #171717; letter-spacing: -0.01em;">
                                Order #{{ $order->order_number }}
                            </h1>
                            <p style="margin: 0; font-size: 14px; color: #737373;">
                                Placed on {{ $order->created_at->format('M d, Y · h:i A') }}
                            </p>
                        </td>
                    </tr>

                    <!-- Total Amount Callout Card -->
                    <tr>
                        <td style="padding: 0 40px 24px;">
                            <div style="background-color: #fafafa; border: 1px solid #e5e5e5; border-radius: 4px; padding: 20px 24px; text-align: center;">
                                <div style="font-size: 11px; font-weight: 600; letter-spacing: 0.15em; text-transform: uppercase; color: #737373; margin-bottom: 4px;">
                                    Total Order Value
                                </div>
                                <div style="font-size: 32px; font-weight: 800; color: #171717; letter-spacing: -0.02em;">
                                    {{ $currencySymbol }}{{ number_format($order->total, 2) }}
                                </div>
                                <div style="margin-top: 8px; font-size: 12px; color: #525252;">
                                    <span style="display: inline-block; padding: 2px 8px; background-color: #e5e7eb; border-radius: 10px; font-weight: 500;">
                                        Payment: {{ ucfirst($order->payment_method ?? 'Online Payment') }}
                                    </span>
                                    <span style="display: inline-block; margin-left: 6px; padding: 2px 8px; background-color: #dbeafe; color: #1e40af; border-radius: 10px; font-weight: 500;">
                                        Status: {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Customer & Shipping Information -->
                    <tr>
                        <td style="padding: 0 40px 28px;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td width="50%" valign="top" style="padding-right: 12px;">
                                        <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #737373; margin-bottom: 8px; border-bottom: 1px solid #eee; padding-bottom: 4px;">
                                            Customer Details
                                        </div>
                                        <div style="font-size: 14px; font-weight: 600; color: #171717; margin-bottom: 3px;">
                                            {{ $order->customer_name }}
                                        </div>
                                        <div style="font-size: 13px; color: #525252; margin-bottom: 3px;">
                                            <a href="mailto:{{ $order->customer_email }}" style="color: #171717; text-decoration: underline;">
                                                {{ $order->customer_email }}
                                            </a>
                                        </div>
                                        @if(!empty($order->shipping_address['phone']))
                                            <div style="font-size: 13px; color: #525252;">
                                                Tel: {{ $order->shipping_address['phone'] }}
                                            </div>
                                        @endif
                                    </td>
                                    <td width="50%" valign="top" style="padding-left: 12px;">
                                        <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #737373; margin-bottom: 8px; border-bottom: 1px solid #eee; padding-bottom: 4px;">
                                            Shipping Destination
                                        </div>
                                        @if(is_array($order->shipping_address))
                                            <div style="font-size: 13px; line-height: 1.5; color: #171717;">
                                                {{ $order->shipping_address['address'] ?? '' }}<br>
                                                {{ $order->shipping_address['city'] ?? '' }}{{ !empty($order->shipping_address['state']) ? ', ' . $order->shipping_address['state'] : '' }}<br>
                                                {{ $order->shipping_address['country'] ?? '' }}
                                            </div>
                                        @else
                                            <div style="font-size: 13px; color: #737373;">Standard Delivery</div>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            @if(!empty($order->notes))
                                <div style="margin-top: 16px; background-color: #fffbeb; border: 1px solid #fef3c7; border-radius: 4px; padding: 12px 16px; font-size: 12px; color: #92400e;">
                                    <strong>Customer Notes:</strong> "{{ $order->notes }}"
                                </div>
                            @endif
                        </td>
                    </tr>

                    <!-- Order Items Table -->
                    <tr>
                        <td style="padding: 0 40px 24px;">
                            <div style="font-size: 11px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #737373; margin-bottom: 12px; border-bottom: 1px solid #eee; padding-bottom: 4px;">
                                Ordered Items ({{ $order->items->sum('quantity') }})
                            </div>
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px; border-collapse: collapse;">
                                <thead>
                                    <tr style="border-bottom: 2px solid #e5e5e5; text-align: left;">
                                        <th style="padding: 8px 0; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #737373;">Item</th>
                                        <th style="padding: 8px 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #737373; text-align: center;">Qty</th>
                                        <th style="padding: 8px 0; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #737373; text-align: right;">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                        <tr style="border-bottom: 1px solid #f0f0f0;">
                                            <td style="padding: 12px 0;">
                                                <div style="font-weight: 600; color: #171717; font-size: 14px;">
                                                    {{ $item->product_name }}
                                                </div>
                                                <div style="font-size: 11px; color: #737373; margin-top: 3px;">
                                                    @if($item->size)
                                                        <span style="display: inline-block; background-color: #f3f4f6; border-radius: 3px; padding: 1px 6px; margin-right: 4px;">
                                                            Size: <strong>{{ $item->size }}</strong>
                                                        </span>
                                                    @endif
                                                    @if($item->color)
                                                        <span style="display: inline-block; background-color: #f3f4f6; border-radius: 3px; padding: 1px 6px; margin-right: 4px;">
                                                            Color: <strong>{{ $item->color }}</strong>
                                                        </span>
                                                    @endif
                                                    @if($item->product_sku)
                                                        <span style="color: #a3a3a3;">SKU: {{ $item->product_sku }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td style="padding: 12px; text-align: center; color: #171717; font-weight: 600;">
                                                × {{ $item->quantity }}
                                            </td>
                                            <td style="padding: 12px 0; text-align: right; color: #171717; font-weight: 600;">
                                                {{ $currencySymbol }}{{ number_format($item->total, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <!-- Financial Summary Breakdown -->
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px; line-height: 1.8;">
                                <tr>
                                    <td style="color: #737373;">Subtotal</td>
                                    <td align="right" style="color: #171717; font-weight: 500;">{{ $currencySymbol }}{{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td style="color: #737373;">Shipping</td>
                                    <td align="right" style="color: #171717; font-weight: 500;">
                                        {{ $order->shipping_cost > 0 ? $currencySymbol . number_format($order->shipping_cost, 2) : 'Free Shipping' }}
                                    </td>
                                </tr>
                                @if($order->tax > 0)
                                    <tr>
                                        <td style="color: #737373;">Tax</td>
                                        <td align="right" style="color: #171717; font-weight: 500;">{{ $currencySymbol }}{{ number_format($order->tax, 2) }}</td>
                                    </tr>
                                @endif
                                <tr style="border-top: 1px solid #171717; font-size: 15px;">
                                    <td style="padding-top: 10px; font-weight: 700; color: #171717;">Grand Total</td>
                                    <td align="right" style="padding-top: 10px; font-weight: 800; color: #171717; font-size: 18px;">
                                        {{ $currencySymbol }}{{ number_format($order->total, 2) }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Admin CTA Button -->
                    <tr>
                        <td align="center" style="padding: 0 40px 40px;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="background-color: #000000; border-radius: 2px;">
                                        <a href="{{ $adminOrderUrl }}" target="_blank" style="display: inline-block; padding: 16px 36px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 12px; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: #ffffff; text-decoration: none;">
                                            View Order in Admin Dashboard →
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer Note -->
                    <tr>
                        <td style="background-color: #fafafa; border-top: 1px solid #eee; padding: 24px 40px; text-align: center; font-size: 11px; color: #888888; line-height: 1.6;">
                            This automated luxury order notification was dispatched directly to the configured Store Email (<strong>{{ $storeEmail }}</strong>).<br>
                            © {{ date('Y') }} {{ $storeName }}. All rights reserved.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
