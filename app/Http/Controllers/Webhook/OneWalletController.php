<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fundist\WebhookRequest;
use App\Http\Resources\Fundist\WebhookResource;
use App\Services\Fundist\WebhookService;
use App\Services\Local\Repositories\Fundist\User\CredentialRepository;
use App\Services\Local\Repositories\GameHistoryRepository;
use App\Services\Local\Repositories\GameLogRepository;
use App\Services\Local\Repositories\WebhookRepository;
use Bavix\Wallet\Internal\Service\DatabaseServiceInterface;
use Psr\Log\LoggerInterface;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class OneWalletController extends Controller
{
    /**
     * @throws UnknownProperties
     */
    public function __invoke(
        WebhookRequest $request,
        WebhookService $webhookService,
        CredentialRepository $credentialRepository,
        WebhookRepository $webhookRepository,
        GameLogRepository $gameLogRepository,
        GameHistoryRepository $gameHistoryRepository,
        DatabaseServiceInterface $databaseService,
        LoggerInterface $logger,
    ): WebhookResource {
        $logger->debug("[OneWallet::Webhook::Request]" . PHP_EOL, $request->all());

        $response = $webhookService->handle(
            $request,
            $credentialRepository,
            $webhookRepository,
            $databaseService,
            $gameLogRepository,
            $gameHistoryRepository,
            $logger,
        );

        $logger->debug("[OneWallet::Webhook::Response]" . PHP_EOL, $response->toArray());

        return new WebhookResource($response->toArray());
    }
}
