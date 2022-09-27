<?php

namespace App\Providers;

use App\Http\Controllers\Webhook\CoinbaseController;
use App\Services\Coinbase\CoinbaseApiService;
use App\Services\Coinbase\Repositories\WebhookRepository;
use App\Services\Coinbase\Sender;
use App\Services\Coinbase\SenderInterface;
use App\Services\Coinbase\WebhookService;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\MessageFormatterInterface;
use GuzzleHttp\Middleware;
use Illuminate\Cache\CacheManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class CoinbaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->when(CoinbaseController::class)
            ->needs(LoggerInterface::class)
            ->give(fn() => $this->getLogger());

        $this->app->singleton(SenderInterface::class, Sender::class);

        $this->app->when(Sender::class)
            ->needs(ClientInterface::class)
            ->give(fn() => $this->getClient());
        $this->app->when(Sender::class)
            ->needs('$apiKey')
            ->give(fn() => $this->getApiKey());
        $this->app->when(Sender::class)
            ->needs('$apiVersion')
            ->give(fn() => $this->getApiVersion());

        $this->app->singleton(CoinbaseApiService::class);
        $this->app->singleton(WebhookRepository::class);
        $this->app->singleton(WebhookService::class);

        $this->app->when(WebhookService::class)
            ->needs(LoggerInterface::class)
            ->give(fn() => $this->getLogger());
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
            'base_uri' => config('coinbase.base_url'),
            'http_errors' => false,
        ];
    }

    protected function getHandler(): HandlerStack
    {
        $stack = HandlerStack::create();

        $mapResponse = $this->getMapResponseMiddleware();
        $logRequestAndResponse = $this->getLogMiddleware();

        $stack->push($mapResponse);
        $stack->push($logRequestAndResponse);

        return $stack;
    }

    protected function getMapResponseMiddleware(): callable
    {
        return Middleware::mapResponse(static function (ResponseInterface $response) {
            $response->getBody()->rewind();

            return $response;
        });
    }

    protected function getLogMiddleware(): callable
    {
        return Middleware::log(
            $this->getLogger(),
            $this->getLogMiddlewareFormatter(),
            $this->getLogMiddlewareLevel(),
        );
    }

    protected function getLogger(): LoggerInterface
    {
        return Log::getLogger();
    }

    protected function getCache(): CacheManager
    {
        return app('cache');
    }

    protected function getLogMiddlewarePrefix(): string
    {
        return config('coinbase.log.prefix');
    }

    protected function getLogMiddlewareTemplate(): string
    {
        return config('coinbase.log.template');
    }

    protected function getLogMiddlewareLevel(): string
    {
        return config('coinbase.log.level');
    }

    protected function getLogMiddlewareFormatter(): MessageFormatterInterface
    {
        $formatterTemplate = $this->getLogMiddlewarePrefix() . $this->getLogMiddlewareTemplate();

        return new MessageFormatter($formatterTemplate);
    }

    protected function getApiKey(): string
    {
        return config('coinbase.api_key');
    }

    protected function getHmacSecret(): string
    {
        return config('coinbase.hmac_secret');
    }

    protected function getApiVersion(): string
    {
        return config('coinbase.api_version');
    }
}
