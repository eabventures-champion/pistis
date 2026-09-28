<?php

namespace App\Services\Payments;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackGateway
{
    private string $secretKey;
    private string $publicKey;
    private string $baseUrl = 'https://api.paystack.co';

    public function __construct()
    {
        $this->secretKey = config('services.paystack.secret_key', '');
        $this->publicKey = config('services.paystack.public_key', '');
    }

    /**
     * Initialize a Paystack transaction
     */
    public function initialize(Order $order, string $callbackUrl): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->secretKey}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/transaction/initialize", [
                'email' => $order->customer_email,
                'amount' => (int) ($order->total * 100), // Paystack uses kobo
                'reference' => $order->order_number,
                'callback_url' => $callbackUrl,
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ],
            ]);

            $data = $response->json();

            if ($data['status'] ?? false) {
                return [
                    'success' => true,
                    'payment_url' => $data['data']['authorization_url'],
                    'reference' => $data['data']['reference'],
                    'access_code' => $data['data']['access_code'],
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Failed to initialize payment',
            ];
        } catch (\Exception $e) {
            Log::error('Paystack Initialize Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Payment service unavailable'];
        }
    }

    /**
     * Verify a Paystack transaction
     */
    public function verify(string $reference): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->secretKey}",
            ])->get("{$this->baseUrl}/transaction/verify/{$reference}");

            $data = $response->json();

            if (($data['status'] ?? false) && ($data['data']['status'] ?? '') === 'success') {
                return [
                    'success' => true,
                    'reference' => $data['data']['reference'],
                    'amount' => $data['data']['amount'] / 100,
                    'gateway_response' => $data['data']['gateway_response'],
                ];
            }

            return [
                'success' => false,
                'message' => $data['data']['gateway_response'] ?? 'Verification failed',
            ];
        } catch (\Exception $e) {
            Log::error('Paystack Verify Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Could not verify payment'];
        }
    }

    public function getName(): string
    {
        return 'paystack';
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }
}
