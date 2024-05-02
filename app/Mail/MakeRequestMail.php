<?php

namespace App\Mail;

use App\Models\MakeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MakeRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public MakeRequest $makeRequest;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($makeRequest)
    {
        $this->makeRequest = $makeRequest;
    }


    public function build()
    {
        return $this->subject('WSA [Make Request] - New Entry')->view('mail.MakeRequestMail');
    }
}
