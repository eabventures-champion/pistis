<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InnerCircleWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public NewsletterSubscriber $subscriber;
    public string $storeName;
    public string $storeUrl;

    public function __construct(NewsletterSubscriber $subscriber)
    {
        $this->subscriber = $subscriber;
        $this->storeName = Setting::get('store_name', 'Pistis');
        $this->storeUrl = config('app.url', url('/'));
    }

    public function build()
    {
        return $this->subject("Welcome to the {$this->storeName} Inner Circle — Editorial Access")
                    ->view('emails.inner-circle-welcome');
    }
}
