<?php

namespace App\Providers;

use App\Services\Local\Repositories\Contracts\CountryRepository;
use App\Services\Local\Repositories\Contracts\CurrencyRepository;
use App\Services\Local\Repositories\Contracts\GameRepository;
use App\Services\Local\Repositories\Contracts\ProviderRepository;
use App\Services\Local\Repositories\Country\CachingCountryRepository;
use App\Services\Local\Repositories\Country\EloquentCountryRepository;
use App\Services\Local\Repositories\Currency\EloquentCurrencyRepository;
use App\Services\Local\Repositories\Currency\CachingCurrencyRepository;
use App\Services\Local\Repositories\Game\CachingGameRepository;
use App\Services\Local\Repositories\Game\EloquentGameRepository;
use App\Services\Local\Repositories\Provider\CachingProviderRepository;
use App\Services\Local\Repositories\Provider\EloquentProviderRepository;
use Illuminate\Cache\CacheManager;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(
            abstract: ProviderRepository::class,
            concrete: fn($app) => new CachingProviderRepository(
                new EloquentProviderRepository(),
                $app->get(CacheManager::class),
            )
        );

        $this->app->singleton(
            abstract: CountryRepository::class,
            concrete: fn($app) => new CachingCountryRepository(
                new EloquentCountryRepository(),
                $app->get(CacheManager::class),
            )
        );

        $this->app->singleton(
            abstract: CurrencyRepository::class,
            concrete: fn($app) => new CachingCurrencyRepository(
                new EloquentCurrencyRepository(),
                $app->get(CacheManager::class),
            )
        );

        $this->app->singleton(
            abstract: GameRepository::class,
            concrete: fn($app) => new CachingGameRepository(
                new EloquentGameRepository(),
                $app->get(CacheManager::class),
            )
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    public function provides()
    {
        return [
            ProviderRepository::class,
        ];
    }
}
