<?php

namespace App\Services\Estchange\Tunnel\Gateway;

use App\Enums\Transaction\Status;
use App\Enums\Transaction\System;
use App\Services\Estchange\Tunnel\Gateway\DTO\Webhook\RequestParamsDTO;
use App\Services\Estchange\Tunnel\Gateway\DTO\Webhook\ResponseParamsDTO;
use App\Services\Estchange\Tunnel\Gateway\Enums\Type;
use App\Services\Estchange\Tunnel\Gateway\Responses\Webhook\ChargeResponse;
use App\Services\Local\Repositories\WalletRepository;
use App\Services\Local\Repositories\WithdrawalRepository;
use Bavix\Wallet\Internal\Exceptions\ExceptionInterface;
use Bavix\Wallet\Internal\Service\DatabaseServiceInterface;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class WebhookService
{
    public function __construct(
        protected WalletRepository $walletRepository,
        protected WithdrawalRepository $withdrawalRepository,
        protected DatabaseServiceInterface $databaseService,
    ) {
    }

    /**
     * @throws ExceptionInterface
     * @throws UnknownProperties
     */
    public function handle(RequestParamsDTO $requestDto): WebhookResponse
    {
        $status = 'OK';

        if (in_array($requestDto->type, [
            Type::AutoconvertationDeposit->value,
            Type::Transaction->value,
        ], true)) {
            $wallet = $this->walletRepository
                ->getByAddress($requestDto->address);

            if ($wallet) {
                $this->databaseService->transaction(static function () use ($requestDto, $wallet) {
                    $transaction = $wallet->walletTransactions()
                        ->where('meta->estchange_transaction_id', $requestDto->transactionId)
                        ->first();

                    if (is_null($transaction) && $requestDto->type === Type::Transaction->value) {
                        $wallet->depositFloat(
                            amount: $requestDto->revenueAmount,
                            meta: [
                                'system' => System::Estchange,
                                'status' => Status::COMPLETED,
                                'address' => $requestDto->address,
                                'estchange_transaction_id' => $requestDto->transactionId,
                            ],
                        );
                    } elseif ($transaction !== null && $requestDto->type === Type::AutoconvertationDeposit->value) {
                        $transaction->update([
                            'meta->estchange_txid' => $requestDto->txId,
                        ]);
                    }
                });
            } else {
                $status = 'Wallet not found';
            }
        }

        if ($requestDto->type === Type::Autowithdrawal->value) {
            $withdrawal = $this->withdrawalRepository->getByExternalWithdrawalId($requestDto->withdrawalId);

            if ($withdrawal) {
                $this->databaseService->transaction(function () use ($withdrawal, $requestDto) {
                    $this->withdrawalRepository->finish($withdrawal, $requestDto->hash);
                });

                $status = 'Withdrawal success';
            } else {
                $status = 'Withdrawal failed';
            }
        }

        return new ChargeResponse(new ResponseParamsDTO(status: $status));
    }
}
