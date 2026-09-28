<?php

namespace App\Services\Payments;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalGateway
{
    private string $clientId;
    private string $clientSecret;
    private string $baseUrl;
    private string $mode;

    public function __construct()
    {
        $this->clientId = config('services.paypal.client_id', '');
        $this->clientSecret = config('services.paypal.client_secret', '');
        $this->mode = config('services.paypal.mode', 'sandbox');
        $this->baseUrl = $this->mode === 'production' 
            ? 'https://api-m.paypal.com' 
            : 'https://api-m.sandbox.paypal.com';
    }

    /**
     * Get PayPal access token
     */
    private function getAccessToken(): ?string
    {
        try {
            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->asForm()
                ->post("{$this->baseUrl}/v1/oauth2/token", [
                    'grant_type' => 'client_credentials'
                ]);

            if ($response->successful()) {
                return $response->json()['access_token'];
            }

            Log::error('PayPal Auth Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('PayPal Auth Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Initialize a PayPal Checkout Session
     */
    public function initialize(Order $order, string $callbackUrl): array
    {
        try {
            $token = $this->getAccessToken();
            if (!$token) {
                if ($this->mode === 'sandbox' && ($this->clientId === 'test' || $this->clientId === 'sb' || empty($this->clientSecret))) {
                    return [
                        'success' => true,
                        'payment_url' => route('checkout.success', $order),
                        'reference' => $order->order_number,
                        'gateway_order_id' => 'SANDBOX-' . $order->order_number,
                    ];
                }
                return ['success' => false, 'message' => 'Failed to authenticate with PayPal'];
            }

            $purchaseUnits = [
                [
                    'reference_id' => $order->order_number,
                    'amount' => [
                        'currency_code' => strtoupper(\App\Models\Setting::get('currency_code', 'USD')),
                        'value' => number_format($order->total, 2, '.', '')
                    ],
                    'items' => $order->items->map(function ($item) {
                        return [
                            'name' => $item->product_name,
                            'unit_amount' => [
                                'currency_code' => strtoupper(\App\Models\Setting::get('currency_code', 'USD')),
                                'value' => number_format($item->price, 2, '.', '')
                            ],
                            'quantity' => (string) $item->quantity
                        ];
                    })->toArray()

                ]
            ];

            $response = Http::withToken($token)
                ->post("{$this->baseUrl}/v2/checkout/orders", [
                    'intent' => 'CAPTURE',
                    'purchase_units' => $purchaseUnits,
                    'application_context' => [
                        'return_url' => $callbackUrl . '?reference=' . $order->order_number . '&status=success',
                        'cancel_url' => $callbackUrl . '?reference=' . $order->order_number . '&status=cancelled',
                        'brand_name' => config('app.name'),
                        'user_action' => 'PAY_NOW'
                    ]
                ]);

            $data = $response->json();

            if ($response->successful() && isset($data['id'])) {
                $approveLink = collect($data['links'])->firstWhere('rel', 'approve')['href'] ?? null;
                
                return [
                    'success' => true,
                    'payment_url' => $approveLink,
                    'reference' => $order->order_number,
                    'gateway_order_id' => $data['id'],
                ];
            }

            Log::error('PayPal Order Creation Error: ' . $response->body());
            return [
                'success' => false,
                'message' => $data['message'] ?? 'Failed to create PayPal order',
            ];
        } catch (\Exception $e) {
            Log::error('PayPal Initialize Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Payment service unavailable'];
        }
    }

    /**
     * Verify a PayPal payment
     */
    public function verify(string $reference): array
    {
        try {
            $orderId = request()->input('token'); 

            if (!$orderId) {
                return ['success' => false, 'message' => 'PayPal order ID not provided'];
            }

            return $this->captureOrder($orderId);
        } catch (\Exception $e) {
            Log::error('PayPal Verify Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Could not verify payment'];
        }
    }

    /**
     * Capture a PayPal order (Used by JS SDK)
     */
    public function captureOrder(string $orderId): array
    {
        try {
            if ($this->mode === 'sandbox' && (str_starts_with($orderId, 'SANDBOX-') || $this->clientId === 'test' || $this->clientId === 'sb')) {
                return [
                    'success' => true,
                    'reference' => $orderId,
                    'gateway_response' => 'paid',
                    'data' => ['status' => 'COMPLETED']
                ];
            }

            $token = $this->getAccessToken();
            if (!$token) {
                return ['success' => false, 'message' => 'Failed to authenticate with PayPal'];
            }

            $response = Http::withToken($token)
                ->post("{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture");

            $data = $response->json();

            if ($response->successful() && (isset($data['status']) && $data['status'] === 'COMPLETED')) {
                return [
                    'success' => true,
                    'reference' => $data['purchase_units'][0]['reference_id'] ?? $orderId,
                    'gateway_response' => 'paid',
                    'data' => $data
                ];
            }

            Log::error('PayPal Capture Error: ' . json_encode($data));
            return [
                'success' => false,
                'message' => $data['message'] ?? 'Payment not completed',
            ];
        } catch (\Exception $e) {
            Log::error('PayPal Capture Exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Payment capture failed'];
        }
    }


    public function getName(): string
    {
        return 'paypal';
    }
}
