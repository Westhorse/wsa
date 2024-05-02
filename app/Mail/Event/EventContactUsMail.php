<?php

namespace App\Mail\Event;

use App\Models\EventContactUs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Modules\ContactUs\Entities\ContactUs;

class EventContactUsMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public EventContactUs $contactUs;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contactUs)
    {
         $this->contactUs = $contactUs;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function build()
    {
        return $this->subject('WSA Conference Istanbul 2024 - Application Contact Us - New Entry')
        ->from('info@wsa-network.com', DB::table('setting_events')->where('name','mail_from_name')->value('value'))
        ->view('mail.ContactUsMail');
    }
}
