<?php

namespace App\Providers;

use App\Services\Binance\BinanceService;
use App\Services\Binance\Sender;
use App\Services\Binance\SenderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\ServiceProvider;

class BinanceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(SenderInterface::class, Sender::class);

        $this->app->when(Sender::class)
            ->needs(ClientInterface::class)
            ->give(fn() => $this->getClient());

        $this->app->singleton(BinanceService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    protected function getClient(): ClientInterface
    {
        return new Client($this->getClientConfig());
    }

    protected function getClientConfig(): array
    {
        return [
            'handler' => $this->getHandler(),
            'base_uri' => config('binance.base_url'),
            'http_errors' => false,
        ];
    }

    protected function getHandler(): HandlerStack
    {
        $stack = HandlerStack::create();

        return $stack;
    }

}
