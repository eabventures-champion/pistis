<?php

namespace App\Mail;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $invitedUser;
    public ?User $inviter;
    public string $acceptUrl;
    public string $storeName;

    public function __construct(User $invitedUser, ?User $inviter = null)
    {
        $this->invitedUser = $invitedUser;
        $this->inviter = $inviter;
        $this->acceptUrl = route('admin.invitations.accept', ['token' => $invitedUser->invitation_token]);
        $this->storeName = Setting::get('store_name', 'Pistis');
    }

    public function build()
    {
        return $this->subject("You've been invited to manage {$this->storeName} [Role: {$this->invitedUser->role_title}]")
                    ->view('emails.admin-invitation');
    }
}
