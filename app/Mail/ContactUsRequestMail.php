<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class ContactUsRequestMail extends Mailable implements ShouldQueue
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
     * @return ContactUsRequestMail
     */

    public function build(): ContactUsRequestMail
    {
        return $this->markdown('mail.ContactUsRequestMail')
            ->subject($this->subject)  // Use $this->subject here
            ->with(['template' => $this->template]);
    }
}
