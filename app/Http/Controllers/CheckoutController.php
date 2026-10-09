<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\AusPostService;
use App\Services\CartService;
use App\Services\OrderNotificationService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private PaymentService $paymentService,
        private AusPostService $ausPostService,
    ) {}

    public function index()
    {
        $cart = $this->cartService->getCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $customer = auth('customer')->user();

        if ($customer && ($customer->isDisabled() || $customer->isArchived())) {
            auth('customer')->logout();
            $msg = $customer->isDisabled()
                ? 'Your account has been disabled. Purchases cannot be processed for this account.'
                : 'Your account is archived. Purchases cannot be processed for this account.';
            return redirect()->route('shop.index')->with('error', $msg);
        }

        $savedShipping = session('checkout_shipping', []);
        if ($customer && empty($savedShipping['postal_code'])) {
            $savedShipping['postal_code'] = $customer->postal_code;
        }

        $initialCountry = $savedShipping['country'] ?? $customer->country ?? 'Australia';
        $initialPostalCode = $savedShipping['postal_code'] ?? $customer->postal_code ?? '';
        $cartWeight = $this->cartService->getCartWeight();

        // Calculate initial available rates via AusPostService
        $shippingRates = $this->ausPostService->getRates($initialCountry, $initialPostalCode, $cartWeight);
        $selectedRateCode = session('checkout_shipping_service_code', $shippingRates[0]['code'] ?? null);
        $selectedRate = null;
        foreach ($shippingRates as $rate) {
            if ($rate['code'] === $selectedRateCode) {
                $selectedRate = $rate;
                break;
            }
        }
        if (!$selectedRate && !empty($shippingRates)) {
            $selectedRate = $shippingRates[0];
        }

        $initialShippingCost = $selectedRate['cost'] ?? 0.0;
        session(['checkout_shipping_cost' => $initialShippingCost]);
        if ($selectedRate) {
            session([
                'checkout_shipping_service' => $selectedRate['name'],
                'checkout_shipping_service_code' => $selectedRate['code'],
            ]);
        }

        $totals = $this->cartService->getCartTotals($initialShippingCost);
        
        $paypalClientId = config('services.paypal.client_id');
        $currencyCode = strtoupper(\App\Models\Setting::get('currency_code', 'USD'));
        $currency_symbol = \App\Models\Setting::get('currency_symbol', '$');

        return view('checkout.index', compact(
            'cart', 'totals', 'customer', 'savedShipping', 'paypalClientId', 
            'currencyCode', 'currency_symbol', 'shippingRates', 'selectedRate', 'cartWeight'
        ));
    }

    /**
     * AJAX: Calculate AusPost shipping rates based on address and cart weight
     */
    public function calculateShippingRates(Request $request)
    {
        $validated = $request->validate([
            'country' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'service_code' => 'nullable|string|max:100',
        ]);

        $cart = $this->cartService->getCart();
        if ($cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.'
            ], 400);
        }

        $weight = $this->cartService->getCartWeight();
        $country = $validated['country'];
        $postcode = $validated['postal_code'] ?? null;

        $rates = $this->ausPostService->getRates($country, $postcode, $weight);

        if (empty($rates)) {
            return response()->json([
                'success' => false,
                'message' => 'No shipping rates found for this destination.'
            ], 422);
        }

        $selectedServiceCode = $validated['service_code'] ?? null;
        $selectedRate = null;

        if ($selectedServiceCode) {
            foreach ($rates as $rate) {
                if ($rate['code'] === $selectedServiceCode) {
                    $selectedRate = $rate;
                    break;
                }
            }
        }

        if (!$selectedRate) {
            $selectedRate = $rates[0];
        }

        // Store selected shipping rate in session
        session([
            'checkout_shipping_cost' => $selectedRate['cost'],
            'checkout_shipping_service' => $selectedRate['name'],
            'checkout_shipping_service_code' => $selectedRate['code'],
            'checkout_shipping_country' => $country,
            'checkout_shipping_postal_code' => $postcode,
        ]);

        $totals = $this->cartService->getCartTotals($selectedRate['cost']);
        $currencySymbol = \App\Models\Setting::get('currency_symbol', '$');

        return response()->json([
            'success' => true,
            'rates' => $rates,
            'selected_rate' => $selectedRate,
            'weight_kg' => $weight,
            'subtotal' => $totals['subtotal'],
            'shipping' => $totals['shipping'],
            'total' => $totals['total'],
            'formatted_subtotal' => $currencySymbol . number_format($totals['subtotal'], 2),
            'formatted_shipping' => $currencySymbol . number_format($totals['shipping'], 2),
            'formatted_total' => $currencySymbol . number_format($totals['total'], 2),
        ]);
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'postal_code' => 'required|string|max:20',
            'shipping_service_code' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $cart = $this->cartService->getCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $email = strtolower(trim($validated['email']));

        // Crucial Check: Ensure email associated with a disabled or archived account CANNOT complete a purchase
        $targetCustomer = \App\Models\Customer::where('email', $email)->first();
        if ($targetCustomer && $targetCustomer->isDisabled()) {
            $errorMsg = 'This customer account has been disabled. Purchases cannot be processed for this email address. Please contact customer support.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 403);
            }
            return back()->with('error', $errorMsg)->withInput();
        }

        if ($targetCustomer && $targetCustomer->isArchived()) {
            $errorMsg = 'This customer account is archived. Purchases cannot be processed for this email address. Please contact customer support.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 403);
            }
            return back()->with('error', $errorMsg)->withInput();
        }

        $shippingCost = (float) session('checkout_shipping_cost', 0);
        $shippingServiceName = session('checkout_shipping_service', 'Australia Post Standard');

        if (!empty($validated['shipping_service_code'])) {
            $rates = $this->ausPostService->getRates($validated['country'], $validated['postal_code'], $this->cartService->getCartWeight());
            foreach ($rates as $r) {
                if ($r['code'] === $validated['shipping_service_code']) {
                    $shippingCost = $r['cost'];
                    $shippingServiceName = $r['name'];
                    break;
                }
            }
        }

        $totals = $this->cartService->getCartTotals($shippingCost);

        // Premium Seamless Customer Account Management
        $customer = auth('customer')->user();

        if (!$customer) {
            $customer = \App\Models\Customer::where('email', $validated['email'])->first();

            if (!$customer) {
                // Auto-create customer account
                $customer = \App\Models\Customer::create([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'city' => $validated['city'],
                    'state' => $validated['state'],
                    'country' => $validated['country'],
                    'postal_code' => $validated['postal_code'],
                    'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(32)),
                ]);
            } else {
                // Update existing customer info
                $customer->update([
                    'first_name' => $customer->first_name ?: $validated['first_name'],
                    'last_name' => $customer->last_name ?: $validated['last_name'],
                    'phone' => $validated['phone'] ?: $customer->phone,
                    'address' => $validated['address'] ?: $customer->address,
                    'city' => $validated['city'] ?: $customer->city,
                    'state' => $validated['state'] ?: $customer->state,
                    'country' => $validated['country'] ?: $customer->country,
                    'postal_code' => $validated['postal_code'] ?: $customer->postal_code,
                ]);
            }

            // Seamlessly authenticate the customer for future visits
            \Illuminate\Support\Facades\Auth::guard('customer')->login($customer, true);
        } else {
            // Update customer's latest shipping info
            $customer->update([
                'phone' => $validated['phone'] ?: $customer->phone,
                'address' => $validated['address'] ?: $customer->address,
                'city' => $validated['city'] ?: $customer->city,
                'state' => $validated['state'] ?: $customer->state,
                'country' => $validated['country'] ?: $customer->country,
                'postal_code' => $validated['postal_code'] ?: $customer->postal_code,
            ]);
        }

        // Store in session for immediate express checkout
        session(['checkout_shipping' => $validated]);

        // Create order linked to customer
        $order = Order::create([
            'customer_id' => $customer->id,
            'customer_email' => $validated['email'],
            'customer_name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'subtotal' => $totals['subtotal'],
            'tax' => $totals['tax'],
            'shipping_cost' => $totals['shipping'],
            'total' => $totals['total'],
            'status' => 'pending',
            'payment_status' => 'pending',
            'shipping_address' => [
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'country' => $validated['country'],
                'postal_code' => $validated['postal_code'],
                'phone' => $validated['phone'],
                'carrier' => 'Australia Post',
                'service' => $shippingServiceName,
            ],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Create order items
        foreach ($cart->items as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'product_name' => $cartItem->product->name,
                'product_sku' => $cartItem->product->sku,
                'color' => $cartItem->color,
                'size' => $cartItem->size,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
                'total' => $cartItem->price * $cartItem->quantity,
            ]);

            // Reduce stock
            $cartItem->product->decrement('stock_quantity', $cartItem->quantity);
        }

        // Initialize payment
        $callbackUrl = route('payment.callback');
        $payment = $this->paymentService->initializePayment($order, $callbackUrl);

        if ($payment['success']) {
            $order->update([
                'payment_method' => $this->paymentService->gateway()->getName(),
                'payment_reference' => $payment['reference'] ?? $order->order_number,
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'redirect_url' => $payment['payment_url']
                ]);
            }

            return redirect($payment['payment_url']);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => $payment['message'] ?? 'Could not initialize payment.'], 400);
        }

        return back()->with('error', $payment['message'] ?? 'Could not initialize payment. Please try again.');

    }

    public function success(Order $order)
    {
        // Notify both customer and administrator upon order confirmation
        OrderNotificationService::sendOrderPlacedNotifications($order);

        return view('checkout.success', compact('order'));
    }

    /**
     * AJAX: Create PayPal Order
     */
    public function createPayPalOrder(Order $order)
    {
        $emailCustomer = \App\Models\Customer::where('email', $order->customer_email)->first();
        if (($order->customer && $order->customer->isDisabled()) || ($emailCustomer && $emailCustomer->isDisabled())) {
            return response()->json(['error' => 'This customer account has been disabled. Purchases cannot be processed.'], 403);
        }

        if (($order->customer && $order->customer->isArchived()) || ($emailCustomer && $emailCustomer->isArchived())) {
            return response()->json(['error' => 'This customer account is archived. Purchases cannot be processed.'], 403);
        }

        $gateway = $this->paymentService->gateway('paypal');
        $result = $gateway->initialize($order, route('payment.callback'));

        if ($result['success']) {
            return response()->json(['id' => $result['gateway_order_id']]);
        }

        return response()->json(['error' => $result['message']], 400);
    }

    /**
     * AJAX: Capture PayPal Order
     */
    public function capturePayPalOrder(Request $request, Order $order)
    {
        $orderId = $request->input('paypal_order_id');
        $gateway = $this->paymentService->gateway('paypal');
        $result = $gateway->captureOrder($orderId);

        if ($result['success']) {
            $order->update([
                'status' => 'processing',
                'payment_status' => 'paid',
                'payment_method' => 'paypal',
                'payment_reference' => $result['reference'],
            ]);

            // Dispatch order notifications to both customer and administrator
            OrderNotificationService::sendOrderPlacedNotifications($order);

            // Clear cart
            $this->cartService->clearCart();

            // Sync inventory to Shopify for each product
            foreach ($order->items as $item) {
                if ($item->product && $item->product->shopify_sync_enabled) {
                    \App\Jobs\SyncInventoryToShopify::dispatch($item->product);
                }
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['error' => $result['message']], 400);
    }
}

