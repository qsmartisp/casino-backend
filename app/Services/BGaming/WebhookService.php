<?php

namespace App\Services\BGaming;

use App\Enums\Transaction\System;
use App\Events\BetEvent;
use App\Events\WinEvent;
use App\Services\BGaming\DTO\Webhook\ActionDto;
use App\Services\BGaming\DTO\Webhook\RequestDto;
use App\Services\BGaming\DTO\Webhook\ResponseParamsDTO;
use App\Services\BGaming\Exceptions\BGamingException;
use App\Services\BGaming\Responses\Webhook\ErrorResponse;
use App\Services\BGaming\Responses\Webhook\RoundResponse;
use Bavix\Wallet\Internal\Service\DatabaseServiceInterface;
use Bavix\Wallet\Models\Transaction;
use Bavix\Wallet\Models\Wallet;

class WebhookService
{
    public function __construct(
        protected DatabaseServiceInterface $databaseService,
    ) {
    }

    /**
     * @throws \Exception
     * @throws \Bavix\Wallet\Internal\Exceptions\ExceptionInterface
     */
    public function handlePlay(RequestDto $dto, Wallet $wallet, Wallet $walletCp): WebhookResponse
    {
        if (!$dto->params->finished && empty($dto->params->actions) && !isset($dto->params->gameId)) {
            return new RoundResponse(
                new ResponseParamsDTO(
                    balance: $wallet->balance,
                )
            );
        }

        $transactions = [];

        if ($dto->params->finished) {
            foreach ($dto->params->actions as $action) {
                $transaction = match ($action->action) {
                    'bet' => $this->handleBet($action, $wallet, $walletCp),
                    'win' => $this->handleWin($action, $wallet, $walletCp),
                };

                $transactions[] = [
                    'actionId' => $action->actionId,
                    'txId' => $transaction->uuid,
                    'processedAt' => $transaction->created_at,
                ];
            }
        }

        return new RoundResponse(
            new ResponseParamsDTO(
                balance: $wallet->balance,
                gameId: $dto->params->gameId,
                transactions: $transactions,
            )
        );
    }

    /**
     * @throws \Bavix\Wallet\Internal\Exceptions\ExceptionInterface
     */
    private function handleBet(ActionDto $dto, Wallet $wallet, Wallet $walletCp): Transaction
    {
        return $this->databaseService->transaction(static function () use ($dto, $wallet, $walletCp) {
            $transaction = $wallet->withdraw($dto->amount, [
                // todo
            ]);

            event(new BetEvent(System::BGaming, $walletCp, $dto->amount));

            return $transaction;
        });
    }

    /**
     * @throws \Bavix\Wallet\Internal\Exceptions\ExceptionInterface
     */
    private function handleWin(ActionDto $dto, Wallet $wallet, Wallet $walletCp): Transaction
    {
        return $this->databaseService->transaction(static function () use ($dto, $wallet, $walletCp) {
            $transaction = $wallet->deposit($dto->amount, [
                // todo
            ]);

            event(new WinEvent(System::BGaming, $walletCp, $dto->amount));

            return $transaction;
        });
    }

    public function handleRollback(RequestDto $dto, Wallet $wallet): WebhookResponse
    {
        // TODO
    }

    public function handleFreespins(RequestDto $dto, Wallet $wallet): WebhookResponse
    {
        // TODO
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function handleError(RequestDto $dto, BGamingException $exception): WebhookResponse
    {
        return new ErrorResponse(
            new ResponseParamsDTO(
                error: $exception->getPrevious()?->getMessage()
                ?? $exception->getMessage()
            )
        );
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function handleException(RequestDto $dto, \Exception $e): WebhookResponse
    {
        return new ErrorResponse(
            new ResponseParamsDTO(
                error: $e->getPrevious()?->getMessage()
                ?? $e->getMessage()
            )
        );
    }
}
