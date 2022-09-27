<?php

namespace App\Providers;

use App\Services\Game\Integrations\BGamingIntegration;
use App\Services\Game\Integrations\FundistIntegration;
use App\Services\Game\LauncherManager;
use App\Services\Local\Repositories\Fundist\User\CredentialRepository as FundistCredentialRepository;
use App\Services\Local\Repositories\BGaming\User\CredentialRepository as BGamingCredentialRepository;
use App\Services\Local\Repositories\LaunchRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class GameServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(LaunchRepository::class);
        $this->app->singleton(FundistCredentialRepository::class);
        $this->app->singleton(BGamingCredentialRepository::class);
        $this->app->singleton(FundistIntegration::class);
        $this->app->singleton(BGamingIntegration::class);

        $this->app->singleton(LauncherManager::class, static function (Application $app) {
            return new LauncherManager(...[
                FundistIntegration::class => $app->make(FundistIntegration::class),
                BGamingIntegration::class => $app->make(BGamingIntegration::class),
            ]);
        });
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
}
