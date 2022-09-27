<?php

namespace App\Providers;

use App\Http\Controllers\Webhook\OneWalletController;
use App\Services\Fundist\CredentialGenerator;
use App\Services\Fundist\CredentialManager;
use App\Services\Fundist\DemoCredential;
use App\Services\Fundist\FundistService;
use App\Services\Fundist\Responder;
use App\Services\Fundist\Sender;
use App\Services\Fundist\SenderInterface;
use App\Services\Fundist\WebhookService;
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

class FundistServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->when(OneWalletController::class)->needs(LoggerInterface::class)->give(fn() => $this->getLogger());

        $this->app->singleton(SenderInterface::class, Sender::class);
        $this->app->when(Sender::class)->needs(ClientInterface::class)->give(fn() => $this->getClient());
        $this->app->when(Sender::class)->needs(LoggerInterface::class)->give(fn() => $this->getLogger());
        $this->app->when(Sender::class)->needs('$key')->give(fn() => $this->getKey());
        $this->app->when(Sender::class)->needs('$pwd')->give(fn() => $this->getPwd());
        $this->app->when(Sender::class)->needs('$ip')->give(fn() => $this->getIP());

        $this->app->singleton(FundistService::class);
        $this->app->singleton(WebhookService::class);

        $this->app->singleton(Responder::class);
        $this->app->when(Responder::class)->needs('$hmacSecret')->give(fn() => $this->getHmacSecret());

        $this->app->singleton(CredentialGenerator::class);
        $this->app->when(CredentialGenerator::class)->needs('$prefix')->give(fn() => $this->getCredentialLoginPrefix());
        $this->app->when(CredentialGenerator::class)->needs('$delim')->give(fn() => $this->getCredentialLoginDelim());

        $this->app->singleton(CredentialManager::class);

        $this->app->singleton(DemoCredential::class);
        $this->app->when(DemoCredential::class)->needs('$login')->give(fn() => $this->getDemoLogin());
        $this->app->when(DemoCredential::class)->needs('$password')->give(fn() => $this->getDemoPassword());
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

    protected function getIP(): string
    {
        return config('fundist.ip');
    }

    protected function getPwd(): string
    {
        return config('fundist.pwd');
    }

    protected function getKey(): string
    {
        return config('fundist.key');
    }

    protected function getClient(): ClientInterface
    {
        return new Client($this->getClientConfig());
    }

    protected function getClientConfig(): array
    {
        return [
            'handler' => $this->getHandler(),
            'base_uri' => config('fundist.base_url'),
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
        return config('fundist.log.prefix');
    }

    protected function getLogMiddlewareTemplate(): string
    {
        return config('fundist.log.template');
    }

    protected function getLogMiddlewareLevel(): string
    {
        return config('fundist.log.level');
    }

    protected function getLogMiddlewareFormatter(): MessageFormatterInterface
    {
        $formatterTemplate = $this->getLogMiddlewarePrefix() . $this->getLogMiddlewareTemplate();

        return new MessageFormatter($formatterTemplate);
    }

    protected function getHmacSecret(): string
    {
        return config('fundist.hmac_secret');
    }

    protected function getCredentialLoginPrefix(): string
    {
        return config('fundist.login.prefix');
    }

    protected function getCredentialLoginDelim(): string
    {
        return config('fundist.login.delim');
    }

    protected function getDemoLogin(): string
    {
        return config('fundist.demo.login');
    }

    protected function getDemoPassword(): string
    {
        return config('fundist.demo.password');
    }
}
