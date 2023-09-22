<?php

namespace App\Listeners;

use App\Mail\UpdateTicket;
use App\Events\TicketUpdate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUpdateTicket
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TicketUpdate  $event
     * @return void
     */
    public function handle(TicketUpdate $event)
    {
        Mail::to('jgreenorigin@gmail.com')->send(new UpdateTicket($event->ticket,$event->message));
    }
}
