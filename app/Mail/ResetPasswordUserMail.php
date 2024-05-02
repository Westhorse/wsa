<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordUserMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $template;
    public $subject;
    /**$subject
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($template, $subject)
    {
        $this->template = $template;
        $this->subject = $subject;
    }

    /**
     * Get the message envelope.
     *
     * @return ResetPasswordUserMail
     */

    public function build(): ResetPasswordUserMail
    {
        return $this->markdown('mail.ResetPasswordUserMail')
            ->subject($this->subject)  // Use $this->subject here
            ->with(['template' => $this->template]);
    }
}
