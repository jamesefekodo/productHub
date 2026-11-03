<?php

namespace App\Mail\Quotes;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Mpdf\Mpdf;

class QuoteMailToUser extends Mailable
{
    use SerializesModels;
    public $realQuote;

    /**
     * Create a new message instance.
     */
    public function __construct($realQuote)
    {
        $this->realQuote = $realQuote;
    }

    /**
     * Get the message envelope.
     */

    public function envelope(): Envelope
    {
        $appName = config('app.name');
        $title = t('notifications.email.newQuoteGeneratedAndSent.title');

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
            markdown: 'utilities.notifications.emails.quotes.sendGeneratedQuoteToCustomer',
            with: [
                'quote' => $this->realQuote
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
        $tempDir = storage_path('app/mpdf');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $mpdf = new Mpdf([
            'tempDir' => $tempDir,
        ]);

        $data = [
            'quote' => $this->realQuote,
        ];

        $html = view('utilities.printFiles.quotes.printQuote', compact('data'))->render();

        $mpdf->WriteHTML($html);

        $pdfContent = $mpdf->Output('', 'S');

        return [
            Attachment::fromData(
                fn() => $pdfContent,
                'quote-' . $this->realQuote->quote_number . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
