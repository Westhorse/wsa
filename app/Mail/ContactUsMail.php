<?php

namespace App\Mail;

use App\Models\ContactUs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUsMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public ContactUs $contactUs;

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
     * @return ContactUsMail
     */
    public function build(): ContactUsMail
    {
        return $this->subject('WSA - Application Contact Us')->view('mail.ContactUsMail');
    }
}
