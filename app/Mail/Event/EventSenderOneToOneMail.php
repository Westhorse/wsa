<?php

namespace App\Mail\Event;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class EventSenderOneToOneMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $template;
    public $subject;

    /**
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
     * @return \Illuminate\Mail\Mailables\Envelope
     */

    public function build()
    {
        return $this->markdown('mail.event.SenderOneToOneMail')
            ->from('info@wsa-network.com', DB::table('setting_events')->where('name','mail_from_name')->value('value'))
            ->subject($this->subject)  // Use $this->subject here
            ->with(['template' => $this->template]);
    }
}
