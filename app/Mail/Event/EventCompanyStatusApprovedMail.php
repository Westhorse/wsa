<?php

namespace App\Mail\Event;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Modules\Conference\Entities\Order;
use Modules\User\Entities\User;

class EventCompanyStatusApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $amount;
    public $total;
    public $roomPrice;
    public $roomCount;
    public $template;
    public $subject;
    public $delegatesData;
    public $spousesData;
    public $sponsorshipItemsData;

    /** $orderMounts
     * Create a new message instance.
     *template  ,
     * @return void
     */
    public function __construct($template, $subject, $roomCount, $amount, $total, $roomPrice, $delegatesData, $spousesData, $sponsorshipItemsData)
    {
        $this->amount = $amount;
        $this->total = $total;
        $this->template = $template;
        $this->subject = $subject;
        $this->roomPrice = $roomPrice;
        $this->roomCount = $roomCount;
        $this->delegatesData = $delegatesData ?? [];
        $this->spousesData = $spousesData ?? [];
        $this->sponsorshipItemsData = $sponsorshipItemsData ?? [];
    }
    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */

    public function build()
    {
        return $this->markdown('mail.event.EventCompanyStatusApprovedMail')
            ->from('info@wsa-network.com', DB::table('setting_events')->where('name','mail_from_name')->value('value'))
            ->subject($this->subject)
            ->with([
                'amount' => $this->amount,
                'template' => $this->template,
                'total' => $this->total,
                'roomPrice' => $this->roomPrice,
                'roomCount' => $this->roomCount,
                'delegatesData' => $this->delegatesData,
                'sponsorshipItemsData' => $this->sponsorshipItemsData,
                'spousesData' => $this->spousesData
            ]);
    }
}
