<?php

namespace App\Http\Middleware;

use App\Enums\Log\Webhook\Slug;
use App\Http\Requests\Webhook\BGamingRequest;
use App\Http\Requests\Webhook\CoinbaseRequest;
use App\Http\Requests\Webhook\EstchangeRequest;
use App\Http\Requests\Webhook\SimplepayRequest;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;

class WebhookRequestAndResponseLog
{
    protected static array $exceptKeys = [
        'webhook_slug',
        'dto',
    ];

    public function __construct(
        protected LoggerInterface $logger,
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param BGamingRequest|CoinbaseRequest|EstchangeRequest|SimplepayRequest $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @param string $slug
     *
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $slug)
    {
        $request->merge(['webhook_slug' => $slug]);

        $slug = $this->getSlug($request->webhook_slug);
        $title = "[{$slug}::Webhook::Request] [signature={$request->dto->signature}]" . PHP_EOL;

        $this->logger->debug($title, $request->except(static::$exceptKeys));

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * @param BGamingRequest|CoinbaseRequest|EstchangeRequest|SimplepayRequest $request
     * @param JsonResponse|Response $response
     *
     * @return void
     */
    public function terminate($request, $response)
    {
        $slug = $this->getSlug($request->webhook_slug);

        $responseData = $response->getData(true);

        Arr::forget($responseData, static::$exceptKeys);

        $this->logger->debug("[{$slug}::Webhook::Response]" . PHP_EOL, $responseData);
    }

    private function getSlug(string $slug): string
    {
        return Str::ucfirst(Slug::from($slug)->value);
    }
}
