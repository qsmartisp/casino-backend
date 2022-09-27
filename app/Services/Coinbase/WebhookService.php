<?php

namespace App\Services\Coinbase;

use App\Services\Coinbase\DTO\Webhook\RequestParamsDTO;
use App\Services\Coinbase\DTO\Webhook\ResponseParamsDTO;
use App\Services\Coinbase\Enums\EventType;
use App\Services\Coinbase\Responses\Webhook\ChargeResponse;
use App\Services\Local\Repositories\UserRepository;
use Bavix\Wallet\Internal\Exceptions\ExceptionInterface;
use Bavix\Wallet\Internal\Service\DatabaseServiceInterface;

class WebhookService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected DatabaseServiceInterface $databaseService,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function handle(RequestParamsDTO $requestDto): WebhookResponse
    {
        $user = $this->userRepository->findById($requestDto->userId ?? 1); // todo: remove on production

        $this->databaseService->transaction(static function () use ($requestDto, $user) {
            if ($requestDto->eventType === EventType::ChargeConfirmed->value) {
                $wallet = $user->getWalletOrFail(mb_strtolower($requestDto->currency));
                $wallet->depositFloat($requestDto->amount);
            }
        });

        return new ChargeResponse(new ResponseParamsDTO());
    }
}
