<?php

namespace App\Mail;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EditorialCampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $emailSubject;
    public string $headline;
    public ?string $preheader;
    public string $messageContent;
    public ?string $ctaText;
    public ?string $ctaUrl;
    public ?string $bannerUrl;
    public string $storeName;
    public string $storeUrl;
    public string $recipientEmail;

    public function __construct(
        string $emailSubject,
        string $headline,
        string $messageContent,
        ?string $preheader = null,
        ?string $ctaText = null,
        ?string $ctaUrl = null,
        ?string $bannerUrl = null,
        string $recipientEmail = ''
    ) {
        $this->emailSubject = $emailSubject;
        $this->headline = $headline;
        $this->messageContent = $messageContent;
        $this->preheader = $preheader;
        $this->ctaText = $ctaText;
        $this->ctaUrl = $ctaUrl;
        $this->bannerUrl = $bannerUrl;
        $this->recipientEmail = $recipientEmail;
        $this->storeName = Setting::get('store_name', 'Pistis');
        $this->storeUrl = config('app.url', url('/'));
    }

    public function build()
    {
        return $this->subject($this->emailSubject)
                    ->view('emails.editorial-campaign');
    }
}
