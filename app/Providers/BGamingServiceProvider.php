<?php

namespace App\Providers;

use App\Http\Controllers\Webhook\BGamingController;
use App\Services\BGaming\BGamingService;
use App\Services\BGaming\CredentialGenerator;
use App\Services\BGaming\SignatureService;
use App\Services\BGaming\Sender;
use App\Services\BGaming\SenderInterface;
use App\Services\BGaming\WebhookService;
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

class BGamingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->when(BGamingController::class)->needs(LoggerInterface::class)->give(fn() => $this->getLogger());

        $this->app->singleton(SignatureService::class);

        $this->app->when(SignatureService::class)->needs('$authToken')->give(fn() => $this->getAuthToken());

        $this->app->singleton(SenderInterface::class, Sender::class);

        $this->app->when(Sender::class)->needs(ClientInterface::class)->give(fn() => $this->getClient());
        $this->app->when(Sender::class)->needs(LoggerInterface::class)->give(fn() => $this->getLogger());

        $this->app->singleton(BGamingService::class);

        $this->app->when(BGamingService::class)->needs('$casinoId')->give(fn() => $this->getCasinoId());
        $this->app->when(BGamingService::class)->needs('$returnUrl')->give(fn() => $this->getReturnUrl());
        $this->app->when(BGamingService::class)->needs('$depositUrl')->give(fn() => $this->getDepositUrl());

        $this->app->singleton(WebhookService::class);

        $this->app->singleton(CredentialGenerator::class);

        $this->app->when(CredentialGenerator::class)->needs('$prefix')->give(fn() => $this->getCredentialLoginPrefix());
        $this->app->when(CredentialGenerator::class)->needs('$delim')->give(fn() => $this->getCredentialLoginDelim());
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
            'base_uri' => $this->getBaseUrl(),
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
        return config('bgaming.log.prefix');
    }

    protected function getLogMiddlewareTemplate(): string
    {
        return config('bgaming.log.template');
    }

    protected function getLogMiddlewareLevel(): string
    {
        return config('bgaming.log.level');
    }

    protected function getLogMiddlewareFormatter(): MessageFormatterInterface
    {
        $formatterTemplate = $this->getLogMiddlewarePrefix() . $this->getLogMiddlewareTemplate();

        return new MessageFormatter($formatterTemplate);
    }

    protected function getAuthToken(): string
    {
        return config('bgaming.auth.token');
    }

    protected function getCasinoId(): string
    {
        return config('bgaming.casino.id');
    }

    protected function getReturnUrl(): string
    {
        return config('bgaming.urls.return');
    }

    protected function getDepositUrl(): string
    {
        return config('bgaming.urls.deposit');
    }

    protected function getBaseUrl(): string
    {
        return config('bgaming.urls.base');
    }

    protected function getCredentialLoginPrefix(): string
    {
        return config('bgaming.login.prefix');
    }

    protected function getCredentialLoginDelim(): string
    {
        return config('bgaming.login.delim');
    }
}
