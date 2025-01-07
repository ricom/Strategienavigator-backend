<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use PhpParser\Node\Scalar\String_;

class SendEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private String $subj;
    private String $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $email_data)
    {
        $this->subj = $email_data['subj'];
        $this->body = $email_data['body'];
    }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->subj,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.email-template',
            with: [
                'body' => $this->body
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
