<?php

namespace AppBackoffice\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        if (config('logging.enable_logging_all_queries', false)) {
            DB::listen(static function ($query) {
                Log::debug('DB_QUERY', [
                    $query->sql,
                    $query->bindings,
                    $query->time,
                ]);
            });
        }

    }
}
