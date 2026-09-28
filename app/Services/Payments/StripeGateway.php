<?php

namespace App\Services\Payments;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StripeGateway
{
    private string $secretKey;
    private string $publicKey;
    private string $baseUrl = 'https://api.stripe.com/v1';

    public function __construct()
    {
        $this->secretKey = config('services.stripe.secret_key', '');
        $this->publicKey = config('services.stripe.public_key', '');
    }

    /**
     * Initialize a Stripe Checkout Session
     */
    public function initialize(Order $order, string $callbackUrl): array
    {
        try {
            $lineItems = [];
            foreach ($order->items as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => strtolower(\App\Models\Setting::get('currency_code', 'USD')),

                        'product_data' => [
                            'name' => $item->product_name,
                        ],
                        'unit_amount' => (int) ($item->price * 100),
                    ],
                    'quantity' => $item->quantity,
                ];
            }

            $response = Http::withBasicAuth($this->secretKey, '')
                ->asForm()
                ->post("{$this->baseUrl}/checkout/sessions", [
                    'payment_method_types' => ['card'],
                    'line_items' => $lineItems,
                    'mode' => 'payment',
                    'success_url' => $callbackUrl . '?reference=' . $order->order_number . '&status=success',
                    'cancel_url' => $callbackUrl . '?reference=' . $order->order_number . '&status=cancelled',
                    'client_reference_id' => $order->order_number,
                    'customer_email' => $order->customer_email,
                    'metadata' => [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                    ],
                ]);

            $data = $response->json();

            if (isset($data['url'])) {
                return [
                    'success' => true,
                    'payment_url' => $data['url'],
                    'reference' => $order->order_number,
                    'session_id' => $data['id'],
                ];
            }

            return [
                'success' => false,
                'message' => $data['error']['message'] ?? 'Failed to create checkout session',
            ];
        } catch (\Exception $e) {
            Log::error('Stripe Initialize Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Payment service unavailable'];
        }
    }

    /**
     * Verify a Stripe payment by order reference
     */
    public function verify(string $reference): array
    {
        try {
            // Look up session by client_reference_id
            $response = Http::withBasicAuth($this->secretKey, '')
                ->get("{$this->baseUrl}/checkout/sessions", [
                    'limit' => 1,
                    'client_reference_id' => $reference
                ]);

            $data = $response->json();
            $sessions = $data['data'] ?? [];

            if (!empty($sessions) && $sessions[0]['payment_status'] === 'paid') {
                return [
                    'success' => true,
                    'reference' => $reference,
                    'amount' => $sessions[0]['amount_total'] / 100,
                    'gateway_response' => 'paid',
                ];
            }

            return [
                'success' => false,
                'message' => 'Payment not completed',
            ];
        } catch (\Exception $e) {
            Log::error('Stripe Verify Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Could not verify payment'];
        }
    }

    public function getName(): string
    {
        return 'stripe';
    }
}
