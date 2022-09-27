<?php

namespace App\Providers;

use App\Services\Deposit\ContextService;
use Illuminate\Support\ServiceProvider;

class DepositServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(ContextService::class);

        $this->app->when(ContextService::class)->needs('$prefix')->give(fn() => $this->getIdPrefix());
        $this->app->when(ContextService::class)->needs('$delim')->give(fn() => $this->getIdDelim());
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

    protected function getIdPrefix(): string
    {
        return config('deposit.prefix');
    }

    private function getIdDelim(): string
    {
        return config('deposit.delim');
    }
}
