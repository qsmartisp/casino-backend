<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GeoIp2\Database\Reader;

class IpGeolocateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Reader::class, function () {
            return new Reader($this->getMmdbPath());
        });

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    protected function getMmdbPath(): string
    {
        return config('ipGeolocate.mmdb_path');
    }
}
