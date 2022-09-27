<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Http\Requests\Webhook\EstchangeRequest;
use App\Http\Resources\Coinbase\WebhookResource;
use App\Services\Estchange\Tunnel\Gateway\WebhookService;

class EstchangeController extends Controller
{
    /**
     * @throws \Bavix\Wallet\Internal\Exceptions\ExceptionInterface
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function __invoke(EstchangeRequest $request, WebhookService $webhookService): WebhookResource
    {
        return new WebhookResource($webhookService->handle($request->dto)->toArray());
    }
}
