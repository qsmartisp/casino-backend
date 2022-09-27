<?php

namespace App\Http\Middleware;

use App\Http\Requests\Webhook\EstchangeRequest;
use App\Services\Estchange\Tunnel\Gateway\Repositories\WebhookRepository;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StoreEstchangeWebhook
{
    public function __construct(
        protected WebhookRepository $webhookRepository,
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param EstchangeRequest $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     *
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $this->webhookRepository->store(
            $this->getWebhookId($request),
            $request->dto->toArray(),
        );

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * @param EstchangeRequest $request
     * @param JsonResponse|Response $response
     *
     * @return void
     */
    public function terminate($request, $response)
    {
        // not failed
        $this->webhookRepository->addResponseData(
            $this->getWebhookId($request),
            (array)$response->getData(),
        );
    }

    private function getWebhookId(Request $request): ?string
    {
        return $request->dto->transactionId ?? $request->dto->withdrawalId; // todo
    }
}
