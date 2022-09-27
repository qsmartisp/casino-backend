<?php

namespace App\Http\Middleware;

use App\Http\Requests\Webhook\BGamingRequest;
use App\Services\Local\Repositories\BGaming\WebhookRepository;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StoreBGamingWebhook
{
    public function __construct(
        protected WebhookRepository $webhookRepository,
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param BGamingRequest $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     *
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // todo: check on repeat

        $this->webhookRepository->store(
            $this->getWebhookId($request),
            $request->dto->toArray(),
        );

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * @param BGamingRequest $request
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

    private function getWebhookId($request)
    {
        return '';// todo: unique webhook id
    }
}
