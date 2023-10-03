<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $orderStatus;

    /**
     * Create a new message instance.
     *
     * @param string $orderStatus
     * @return void
     */
    public function __construct($orderStatus)
    {
        $this->orderStatus = $orderStatus;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.order_status_changed')
            ->subject('Order Status Changed');
    }
}
