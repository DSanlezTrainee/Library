<?php

namespace App\Mail;

use App\Models\Requisition;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class NewRequisitionUserNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Requisition $requisition)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Book Requisition Confirmation: #' . $this->requisition->sequential_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Preparar o caminho da imagem e verificar se existe
        $coverImagePath = null;

        if ($this->requisition->book?->cover_image) {
            $fullUrl = $this->requisition->book->cover_image;
            $filename = basename(parse_url($fullUrl, PHP_URL_PATH));
            $imagePath = storage_path('app/public/covers/' . $filename);

            if (file_exists($imagePath)) {
                $coverImagePath = $imagePath;
            }
        }

        return new Content(
            view: 'emails.requisitions.user-notification',
            with: [
                'requisition' => $this->requisition,
                'coverImagePath' => $coverImagePath,
            ],
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
