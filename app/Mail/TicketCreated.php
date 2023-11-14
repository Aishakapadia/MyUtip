<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //var_dump($this->data['ticket']->ticket_number);
        return $this
//            ->subject('CRONJOB - Induction Assigned to TM for ' . $this->data)
            ->subject('Ticket created '.$this->data['ticket']->ticket_number.' (myutip.com)')
            ->view('emails.ticket_created');
    }
}
