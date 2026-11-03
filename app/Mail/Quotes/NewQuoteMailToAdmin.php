<?php

namespace App\Mail\Quotes;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewQuoteMailToAdmin extends Mailable
{
    use Queueable, SerializesModels;
    public $submittedRequest;

    /**
     * Create a new message instance.
     */
    public function __construct($submittedRequest)
    {
        $this->submittedRequest = $submittedRequest;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $appName = config('app.name');
        $title = t('notifications.email.newQuoteMailToAdmin.title');

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
            markdown: 'utilities.notifications.emails.quotes.newQuoteMailToAdmin',
            with: [
                'submittedRequest' => $this->submittedRequest,
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
