<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminOrderNotification extends Mailable
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
            subject: "New Order Confirmed: #{$this->order->order_number} ({$this->currencySymbol}" . number_format($this->order->total, 2) . ") — {$this->storeName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-order-notification',
            with: [
                'order' => $this->order,
                'items' => $this->order->items,
                'storeName' => $this->storeName,
                'currencySymbol' => $this->currencySymbol,
                'storeEmail' => $this->storeEmail,
                'adminOrderUrl' => route('admin.orders.show', $this->order),
            ],
        );
    }
}
