<?php

namespace App\Listeners;

use App\Events\AssignedUser;
use App\Mail\AssignedUserTicket;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAssignedUser
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
     * @param  \App\Events\AssignedUser  $event
     * @return void
     */
    public function handle(AssignedUser $event)
    {
        Mail::to('jgreenorigin@gmail.com')->send(new AssignedUserTicket($event->ticket));
    }
}
