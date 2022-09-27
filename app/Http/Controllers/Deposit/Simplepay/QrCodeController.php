<?php

namespace App\Http\Controllers\Deposit\Simplepay;

use App\Enums\Transaction\Status as TransactionStatus;
use App\Enums\Transaction\System;
use App\Http\Controllers\Controller;
use App\Http\Requests\Deposit\Simplepay\QrCodeRequest;
use App\Http\Resources\Simplepay\QrCodeResource;
use App\Services\Deposit\ContextService;
use App\Services\Local\Helpers\UserHelper;
use App\Services\Local\Repositories\DepositRepository;
use App\Services\Simplepay\SimplepayService;
use Bavix\Wallet\Internal\Service\DatabaseServiceInterface;
use JsonSerializable;

class QrCodeController extends Controller
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @throws \Bavix\Wallet\Internal\Exceptions\ExceptionInterface
     */
    public function create(
        QrCodeRequest $request,
        SimplepayService $simplepay,
        ContextService $depositContextService,
        DepositRepository $depositRepository,
        DatabaseServiceInterface $databaseService,
    ): JsonSerializable {
        $currency = $request->currency;
        $amount = $request->amount;
        $wallet = UserHelper::user()->getWalletOrFail(mb_strtolower($currency));
        $deposit = null;

        $databaseService->transaction(static function () use (
            &$deposit,
            $amount,
            $currency,
            $wallet,
            $depositRepository,
        ) {
            $transaction = $wallet->depositFloat(
                amount: $amount,
                meta: [
                    'system' => System::Simplepay,
                    'status' => TransactionStatus::NEW,
                ],
                confirmed: false,
            );
            $deposit = $depositRepository->init($transaction->getKey(), $currency);
        });

        $qrCodeResponse = $simplepay->makeQrCode(
            amount: number_format((float)$amount, 2, '.', ''),
            order: $depositContextService->addContextForId($deposit->id),
            timestamp: now()->timestamp,
            meta: [
                // todo: add info for webhooks
                'currency' => $currency,
            ],
        );

        return new QrCodeResource($qrCodeResponse);
    }
}
