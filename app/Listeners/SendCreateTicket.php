<?php

namespace App\Listeners;

use App\Events\TicketCreate;
use App\Mail\CreateTicket;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCreateTicket
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
     * @param  \App\Events\TicketCreate  $event
     * @return void
     */
    public function handle(TicketCreate $event)
    {
        Mail::to('jgreenorigin@gmail.com')->send(new CreateTicket($event->ticket));
    }
}
