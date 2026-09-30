<?php

namespace App\Services;

use App\Mail\AdminOrderNotification;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderNotificationService
{
    /**
     * Send order confirmation notification email to the configured Store Email.
     */
    public static function notifyStoreNewOrder(Order $order): bool
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
}
