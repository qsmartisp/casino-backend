<?php

namespace App\Providers;

use App\Models\Game;
use App\Models\Sanctum\PersonalAccessToken;
use App\Models\User;
use App\Services\Local\Repositories\GameRepository;
use App\Services\Local\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Model;

//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(UserRepository::class);
        $this->app->when(UserRepository::class)->needs(Model::class)->give(fn() => new User());
        $this->app->singleton(GameRepository::class);
        $this->app->when(GameRepository::class)->needs(Model::class)->give(fn() => new Game());
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

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
