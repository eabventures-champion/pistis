<?php

namespace App\Services\Payments;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveGateway
{
    private string $secretKey;
    private string $publicKey;
    private string $baseUrl = 'https://api.flutterwave.com/v3';

    public function __construct()
    {
        $this->secretKey = config('services.flutterwave.secret_key', '');
        $this->publicKey = config('services.flutterwave.public_key', '');
    }

    /**
     * Initialize a Flutterwave payment
     */
    public function initialize(Order $order, string $callbackUrl): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->secretKey}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/payments", [
                'tx_ref' => $order->order_number,
                'amount' => $order->total,
                'currency' => strtoupper(\App\Models\Setting::get('currency_code', 'USD')),

                'redirect_url' => $callbackUrl,
                'customer' => [
                    'email' => $order->customer_email,
                    'name' => $order->customer_name,
                ],
                'meta' => [
                    'order_id' => $order->id,
                ],
                'customizations' => [
                    'title' => 'Pistis Store',
                    'description' => 'Payment for order ' . $order->order_number,
                ],
            ]);

            $data = $response->json();

            if (($data['status'] ?? '') === 'success') {
                return [
                    'success' => true,
                    'payment_url' => $data['data']['link'],
                    'reference' => $order->order_number,
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Failed to initialize payment',
            ];
        } catch (\Exception $e) {
            Log::error('Flutterwave Initialize Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Payment service unavailable'];
        }
    }

    /**
     * Verify a Flutterwave transaction
     */
    public function verify(string $reference): array
    {
        try {
            // First get the transaction ID from the reference
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->secretKey}",
            ])->get("{$this->baseUrl}/transactions/verify_by_reference", [
                'tx_ref' => $reference,
            ]);

            $data = $response->json();

            if (($data['status'] ?? '') === 'success' && ($data['data']['status'] ?? '') === 'successful') {
                return [
                    'success' => true,
                    'reference' => $data['data']['tx_ref'],
                    'amount' => $data['data']['amount'],
                    'gateway_response' => 'successful',
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Verification failed',
            ];
        } catch (\Exception $e) {
            Log::error('Flutterwave Verify Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Could not verify payment'];
        }
    }

    public function getName(): string
    {
        return 'flutterwave';
    }
}
