<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use App\Services\Payments\PaystackGateway;
use App\Services\Payments\FlutterwaveGateway;
use App\Services\Payments\StripeGateway;
use App\Services\Payments\PayPalGateway;


class PaymentService
{
    /**
     * Get the active payment gateway instance
     */
    public function gateway(?string $name = null): PaystackGateway|FlutterwaveGateway|StripeGateway|PayPalGateway

    {
        $name = $name ?? Setting::get('active_payment_gateway', config('services.active_payment_gateway', 'paystack'));

        return match ($name) {
            'paystack' => new PaystackGateway(),
            'flutterwave' => new FlutterwaveGateway(),
            'stripe' => new StripeGateway(),
            'paypal' => new PayPalGateway(),

            default => new PaystackGateway(),
        };
    }

    /**
     * Initialize a payment
     */
    public function initializePayment(Order $order, string $callbackUrl): array
    {
        return $this->gateway()->initialize($order, $callbackUrl);
    }

    /**
     * Verify a payment
     */
    public function verifyPayment(string $reference): array
    {
        return $this->gateway()->verify($reference);
    }

    /**
     * Get available gateways
     */
    public function availableGateways(): array
    {
        return [
            'paypal' => 'PayPal',
        ];
    }

}
