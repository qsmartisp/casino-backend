<?php

namespace App\Http\Middleware;

use App\Http\Requests\Webhook\SimplepayRequest;
use App\Services\Local\Repositories\Simplepay\WebhookRepository;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StoreSimplepayWebhook
{
    public function __construct(
        protected WebhookRepository $webhookRepository,
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param SimplepayRequest $request
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
     * @param SimplepayRequest $request
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

    private function getWebhookId($request): ?string
    {
        return $request->input('hash'); // todo
    }
}
