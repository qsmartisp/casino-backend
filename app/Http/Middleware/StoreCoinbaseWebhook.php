<?php

namespace App\Http\Middleware;

use App\Http\Requests\Webhook\CoinbaseRequest;
use App\Services\Coinbase\Repositories\WebhookRepository;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StoreCoinbaseWebhook
{
    public function __construct(
        protected WebhookRepository $webhookRepository,
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param CoinbaseRequest $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     *
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $this->webhookRepository->store(
            $request->dto->id,
            $request->dto->toArray(),
        );

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * @param CoinbaseRequest $request
     * @param JsonResponse|Response $response
     *
     * @return void
     */
    public function terminate($request, $response)
    {
        // not failed
        $this->webhookRepository->addResponseData(
            $request->dto->id,
            (array)$response->getData(),
        );
    }
}
