<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $email1;
    public $user;
    public $otp;

    /**
     * Create a new message instance.
     */
    public function __construct($email1, $user, $otp)
    {
        $this->email1 = $email1;
        $this->user = $user;
        $this->otp = $otp;
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $appName = config('app.name');
        $title = t('notifications.email.userMailVerification.title');

        return new Envelope(
            subject: $title . '-' . $appName
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'utilities.notifications.emails.auth.userMailVerificationEmail',
            with: [
                'user' => $this->user,
                'email1' => $this->email1,
                'otp' => $this->otp,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
