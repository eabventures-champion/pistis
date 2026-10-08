<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $storeName;
    public string $currencySymbol;
    public string $storeEmail;

    public function __construct(Order $order)
    {
        $this->order = $order->loadMissing(['items.product', 'customer']);
        $this->storeName = Setting::get('store_name', 'Pistis');
        $this->currencySymbol = Setting::get('currency_symbol', '$');
        $this->storeEmail = Setting::get('store_email', 'hello@pistis.com');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Order Confirmation: Your Order #{$this->order->order_number} Has Been Placed — {$this->storeName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-order-notification',
            with: [
                'order' => $this->order,
                'items' => $this->order->items,
                'storeName' => $this->storeName,
                'currencySymbol' => $this->currencySymbol,
                'storeEmail' => $this->storeEmail,
                'orderSuccessUrl' => route('checkout.success', $this->order),
                'accountOrdersUrl' => route('account.orders'),
            ],
        );
    }
}
