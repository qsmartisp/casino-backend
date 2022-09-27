<?php

namespace App\Providers;

use App\Services\Estchange\Tunnel\Gateway\EstchangeService;
use App\Services\Estchange\Tunnel\Gateway\Repositories\WebhookRepository;
use App\Services\Estchange\Tunnel\Gateway\Sender;
use App\Services\Estchange\Tunnel\Gateway\SenderInterface;
use App\Services\Estchange\Tunnel\Gateway\WebhookService;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\MessageFormatterInterface;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class EstchangeServiceProvider extends ServiceProvider
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
        $this->app->when(Sender::class)
            ->needs('$apiKey')
            ->give(fn() => $this->getApiKey());

        $this->app->singleton(EstchangeService::class);
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
            'base_uri' => config('estchange.base_url'),
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

    protected function getLogMiddlewarePrefix(): string
    {
        return config('estchange.log.prefix');
    }

    protected function getLogMiddlewareTemplate(): string
    {
        return config('estchange.log.template');
    }

    protected function getLogMiddlewareLevel(): string
    {
        return config('estchange.log.level');
    }

    protected function getLogMiddlewareFormatter(): MessageFormatterInterface
    {
        $formatterTemplate = $this->getLogMiddlewarePrefix() . $this->getLogMiddlewareTemplate();

        return new MessageFormatter($formatterTemplate);
    }

    protected function getApiKey(): string
    {
        return config('estchange.api_key');
    }
}
