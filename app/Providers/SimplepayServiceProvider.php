<?php

namespace App\Providers;

use App\Services\Simplepay\SimplepayService;
use App\Services\Simplepay\SignatureService;
use App\Services\Simplepay\Interfaces\Sender as SenderInterface;
use App\Services\Simplepay\Sender;
use App\Services\Simplepay\WebhookService;
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

class SimplepayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(SignatureService::class);

        $this->app->when(SignatureService::class)->needs('$apiSecretKey')->give(fn() => $this->getApiSecretKey());
        $this->app->when(SignatureService::class)->needs('$signingSecretKey')->give(fn() => $this->getWebhookSigningSecretKey());

        $this->app->singleton(SenderInterface::class, Sender::class);

        $this->app->when(Sender::class)->needs(ClientInterface::class)->give(fn() => $this->getClient());
        $this->app->when(Sender::class)->needs(LoggerInterface::class)->give(fn() => $this->getLogger());

        $this->app->singleton(SimplepayService::class);

        $this->app->when(SimplepayService::class)->needs('$qrCodeServiceId')->give(fn() => $this->getQrCodeServiceId());
        $this->app->when(SimplepayService::class)->needs('$apiKey')->give(fn() => $this->getApiKey());

        $this->app->singleton(WebhookService::class);
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
        return config('simplepay.log.prefix');
    }

    protected function getLogMiddlewareTemplate(): string
    {
        return config('simplepay.log.template');
    }

    protected function getLogMiddlewareLevel(): string
    {
        return config('simplepay.log.level');
    }

    protected function getLogMiddlewareFormatter(): MessageFormatterInterface
    {
        $formatterTemplate = $this->getLogMiddlewarePrefix() . $this->getLogMiddlewareTemplate();

        return new MessageFormatter($formatterTemplate);
    }

    protected function getQrCodeServiceId(): string
    {
        return config('simplepay.services.qrcode.service_id');
    }

    protected function getBaseUrl(): string
    {
        return config('simplepay.urls.base');
    }

    private function getApiKey(): string
    {
        return config('simplepay.credentials.api.key');
    }

    protected function getApiSecretKey(): string
    {
        return config('simplepay.credentials.api.secret_key');
    }

    protected function getWebhookSigningSecretKey(): string
    {
        return config('simplepay.credentials.webhook.signing_secret_key');
    }
}
