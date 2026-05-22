<?php

namespace App\Mail;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $complaint;
    public $notificationType;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Complaint $complaint, string $notificationType = 'new')
    {
        $this->user = $user;
        $this->complaint = $complaint;
        $this->notificationType = $notificationType; // 'new' or 'assigned'
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->notificationType === 'assigned'
            ? 'Complaint Assigned to You: ' . $this->complaint->reference_number
            : 'New Complaint Created: ' . $this->complaint->reference_number;

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.complaint_notification',
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
