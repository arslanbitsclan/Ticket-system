<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\CreatedCustomerFromDashboard;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\CustomerCreatedFromDashboard;

class SendCustomerCreatedFromDashboard
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
     * @param  \App\Events\CustomerCreatedFromDashboard  $event
     * @return void
     */
    public function handle(CustomerCreatedFromDashboard $event)
    {
        Mail::to('jgreenorigin@gmail.com')->send(new CreatedCustomerFromDashboard($event->ticket, $event->password));
    }
}
