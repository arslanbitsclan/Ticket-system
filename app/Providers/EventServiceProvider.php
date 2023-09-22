<?php

namespace App\Providers;

use App\Events\AssignedUser;
use App\Events\CustomerCreatedFromDashboard;
use App\Events\TicketCreate;
use App\Events\TicketUpdate;
use App\Listeners\SendAssignedUser;
use App\Listeners\SendCreateTicket;
use App\Listeners\SendCustomerCreatedFromDashboard;
use App\Listeners\SendUpdateTicket;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TicketCreate::class => [
            SendCreateTicket::class,
        ],
        TicketUpdate::class => [
            SendUpdateTicket::class,
        ],
        AssignedUser::class => [
            SendAssignedUser::class,
        ],
        CustomerCreatedFromDashboard::class => [
            SendCustomerCreatedFromDashboard::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
