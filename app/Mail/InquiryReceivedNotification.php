<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryReceivedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Inquiry $inquiry)
    {
    }

    public function envelope(): Envelope
    {
        $subject = $this->inquiry->inquiry_type === Inquiry::TYPE_QUOTE
            ? "[Shiv Aaradhana Quote Request] #{$this->inquiry->reference_no} - {$this->inquiry->full_name} ({$this->inquiry->country})"
            : "[Shiv Aaradhana Inquiry] #{$this->inquiry->reference_no} - {$this->inquiry->full_name}";

        return new Envelope(
            subject: $subject,
            replyTo: [$this->inquiry->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inquiry-received',
        );
    }
}
