<?php

namespace App\Mail;

use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class RefundProcessedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $refund;
    public $proofPath;

    /**
     * Create a new message instance.
     */
    public function __construct(Refund $refund, $proofPath = null)
    {
        $this->refund = $refund;
        $this->proofPath = $proofPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notifikasi Proses Refund Anda - Tangwin Barbershop',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.refund_processed',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        if ($this->proofPath && file_exists(storage_path('app/public/' . $this->proofPath))) {
            $attachments[] = Attachment::fromPath(storage_path('app/public/' . $this->proofPath))
                ->as('bukti_transfer_refund.jpg')
                ->withMime('image/jpeg');
        }

        return $attachments;
    }
}
