<?php

namespace App\Http\Controllers;

use App\Jobs\SyncInventoryToShopify;
use App\Models\Order;
use App\Services\CartService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private CartService $cartService,
    ) {}

    public function callback(Request $request)
    {
        $reference = $request->input('reference') ?? $request->input('trxref') ?? $request->input('tx_ref');

        if (!$reference) {
            return redirect()->route('shop.index')->with('error', 'Invalid payment reference.');
        }

        $order = Order::where('order_number', $reference)
            ->orWhere('payment_reference', $reference)
            ->first();

        if (!$order) {
            return redirect()->route('shop.index')->with('error', 'Order not found.');
        }

        // Verify payment
        $result = $this->paymentService->verifyPayment($reference);

        if ($result['success']) {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
            ]);

            // Clear the cart
            $this->cartService->clearCart();

            // Sync inventory to Shopify for each product
            foreach ($order->items as $item) {
                if ($item->product && $item->product->shopify_sync_enabled) {
                    SyncInventoryToShopify::dispatch($item->product);
                }
            }

            return redirect()->route('checkout.success', $order)
                ->with('success', 'Payment successful! Your order has been placed.');
        }

        $order->update(['payment_status' => 'failed']);

        return redirect()->route('checkout.index')
            ->with('error', 'Payment verification failed: ' . ($result['message'] ?? 'Unknown error'));
    }
}
