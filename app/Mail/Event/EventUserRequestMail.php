<?php

namespace App\Mail\Event;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Modules\User\Entities\User;

class EventUserRequestMail extends Mailable implements ShouldQueue
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
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.event.EventApplicationMail')
            ->from('info@wsa-network.com', DB::table('setting_events')->where('name','mail_from_name')->value('value'))
            ->subject($this->subject)
            ->with(['template' => $this->template]);
    }
}
