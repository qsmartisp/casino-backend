<?php

namespace App\Listeners;

use App\Events\BetReceiveEvent;
use App\Services\WalletExchangeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BetReceiveListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        protected WalletExchangeService $walletExchangeService,
    ) {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\BetReceiveEvent  $event
     * @return void
     */
    public function handle(BetReceiveEvent $event)
    {
        $cpWallet = $event->user->getWallet('cp');
        $cpWallet->depositFloat($event->amount);
    }
}
