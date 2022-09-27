<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Http\Requests\Webhook\CoinbaseRequest;
use App\Http\Resources\Coinbase\WebhookResource;
use App\Services\Coinbase\WebhookService;
use Bavix\Wallet\Internal\Exceptions\ExceptionInterface;

class CoinbaseController extends Controller
{
    /**
     * @throws ExceptionInterface
     */
    public function __invoke(
        CoinbaseRequest $request,
        WebhookService $webhookService,
    ): WebhookResource {
        $response = $webhookService->handle($request->dto);

        return new WebhookResource($response->toArray());
    }
}
