<?php

namespace App\Services;

use App\Mail\AdminOrderNotification;
use App\Mail\CustomerOrderNotification;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderNotificationService
{
    /**
     * Dispatch both customer confirmation and admin notification emails when an order is placed.
     */
    public static function sendOrderPlacedNotifications(Order $order): array
    {
        $customerSent = self::notifyCustomerOrderPlaced($order);
        $adminSent = self::notifyAdminOrderReceived($order);

        return [
            'customer' => $customerSent,
            'admin' => $adminSent,
        ];
    }

    /**
     * Send order placed confirmation email to the customer.
     * Signifies that the order has been sent/placed by the customer.
     */
    public static function notifyCustomerOrderPlaced(Order $order): bool
    {
        // Avoid duplicate notification if already sent
        if ($order->customer_notified_at) {
            return false;
        }

        $customerEmail = trim($order->customer_email ?? $order->customer?->email ?? '');

        if (empty($customerEmail)) {
            Log::warning("No customer email address available to send order confirmation for Order #{$order->order_number}");
            return false;
        }

        try {
            Mail::to($customerEmail)->send(new CustomerOrderNotification($order));
            $order->markCustomerNotified();
            Log::info("Customer order notification successfully dispatched to ({$customerEmail}) for Order #{$order->order_number}");
            return true;
        } catch (\Throwable $e) {
            Log::error("Failed to send order confirmation to customer ({$customerEmail}) for Order #{$order->order_number}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send order received notification email to the store administrator.
     * Signifies that the order has been received by the administrator.
     */
    public static function notifyAdminOrderReceived(Order $order): bool
    {
        // Avoid duplicate notification if already sent
        if ($order->admin_notified_at) {
            return false;
        }

        // 1. Prioritize Store Email from Settings
        $storeEmail = trim(Setting::get('store_email') ?? '');

        // 2. Fallback to Admin User email or mail config
        if (empty($storeEmail)) {
            $storeEmail = User::where('is_admin', true)->value('email') ?? config('mail.from.address');
        }

        if (empty($storeEmail)) {
            Log::warning("No store email or admin email configured to receive order notification for Order #{$order->order_number}");
            return false;
        }

        try {
            Mail::to($storeEmail)->send(new AdminOrderNotification($order));
            $order->markAdminNotified();
            Log::info("Admin order notification successfully dispatched to Store Email ({$storeEmail}) for Order #{$order->order_number}");
            return true;
        } catch (\Throwable $e) {
            Log::error("Failed to send order notification to Store Email ({$storeEmail}) for Order #{$order->order_number}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Backwards-compatible alias for notifyAdminOrderReceived.
     */
    public static function notifyStoreNewOrder(Order $order): bool
    {
        return self::notifyAdminOrderReceived($order);
    }
}
