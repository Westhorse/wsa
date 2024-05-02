<?php

namespace App\Mail\Event;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class InvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public Order $order;
    public $amount;
    public $total;
    public $roomPrice;
    public $roomCount;
    public $subject;
    public $delegatesData;
    public $spousesData;
    public $sponsorshipItemsData;
    /** $orderMounts
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order,$roomCount,$amount,$total,$roomPrice, $subject, $delegatesData, $spousesData, $sponsorshipItemsData)
    {
        $this->order = $order;
        $this->amount = $amount;
        $this->total = $total;
        $this->roomPrice = $roomPrice;
        $this->roomCount = $roomCount;
        $this->subject = $subject;
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
        return $this->markdown('mail.event.InvoiceMail')
            ->subject($this->subject)  // Use $this->subject here
            ->from('info@wsa-network.com', DB::table('setting_events')->where('name','mail_from_name')->value('value'))
            ->with([
            'amount' => $this->amount ,
            'total' => $this->total ,
            'roomPrice' => $this->roomPrice ,
            'roomCount' => $this->roomCount ,
            'delegatesData' => $this->delegatesData,
            'sponsorshipItemsData' => $this->sponsorshipItemsData,
            'spousesData' => $this->spousesData
        ]);
    }
}
