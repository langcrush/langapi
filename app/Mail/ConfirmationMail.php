<?php

namespace App\Mail;

use App\Models\Confirmation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $url;

    /**
     * Create a new message instance.
     */
    public function __construct(public Confirmation $confirmation, public User $user)
    {
        $this->url = env("APP_URL", 'http://localhost').'/api/v1/confirm?token='.$confirmation->token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->confirmation->email)
            ->subject('Confirm the email')
            ->markdown('emails.confirmation.sent')
            ->text('emails.confirmation.sent_plain')
            ->tag('confirmation');
    }
}
