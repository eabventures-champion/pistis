<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerOrderShippedNotification extends Mailable
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
            subject: "Your Order #{$this->order->order_number} Has Been Dispatched — {$this->storeName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-order-shipped',
            with: [
                'order' => $this->order,
                'items' => $this->order->items,
                'storeName' => $this->storeName,
                'currencySymbol' => $this->currencySymbol,
                'storeEmail' => $this->storeEmail,
                'trackingNumber' => $this->order->tracking_number,
                'trackingCarrier' => $this->order->tracking_carrier ?? 'Australia Post',
                'trackingUrl' => $this->order->tracking_url,
                'orderSuccessUrl' => route('checkout.success', $this->order),
                'accountOrdersUrl' => route('account.orders'),
            ],
        );
    }
}
