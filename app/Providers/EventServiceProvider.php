<?php

namespace App\Providers;

use App\Events\BetReceiveEvent;
use App\Events\BetEvent;
use App\Events\WinEvent;
use App\Listeners\BetReceiveListener;
use App\Listeners\BetListener;
use App\Listeners\WinListener;
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

        BetReceiveEvent::class => [
            BetReceiveListener::class
        ],

        BetEvent::class => [
            BetListener::class,
        ],
        WinEvent::class => [
            WinListener::class,
        ],

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
