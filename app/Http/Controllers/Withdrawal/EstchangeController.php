<?php

namespace App\Http\Controllers\Withdrawal;

use App\Enums\Transaction\Status as TransactionStatus;
use App\Enums\Transaction\System;
use App\Http\Controllers\Controller;
use App\Http\Requests\Withdrawal\EstchangeRequest;
use App\Http\Resources\WithdrawalEstchangeResource;
use App\Http\Resources\WithdrawalResource;
use App\Models\User;
use App\Services\Local\Dto\Withdrawal\EstchangeRequestDto;
use App\Services\Local\Repositories\CurrencyRepository;
use App\Services\Local\Repositories\WithdrawalRepository;
use Bavix\Wallet\Internal\Exceptions\ExceptionInterface;
use Bavix\Wallet\Internal\Service\DatabaseServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class EstchangeController extends Controller
{
    /**
     * @throws ExceptionInterface
     * @throws UnknownProperties
     */
    public function store(
        EstchangeRequest $request,
        WithdrawalRepository $withdrawalRepository,
        DatabaseServiceInterface $databaseService,
    ): JsonResource {
        /** @var User $user */
        $user = auth()->user();
        $withdrawal = null;
        $dto = new EstchangeRequestDto(request: $request->validated());
        $wallet = $user->getWalletOrFail(mb_strtolower($dto->currency));

        $databaseService->transaction(static function () use (
            &$withdrawal,
            $withdrawalRepository,
            $wallet,
            $dto,
        ) {
            $transaction = $wallet->withdrawFloat(
                amount: $dto->amount,
                meta: [
                    'system' => System::Estchange,
                    'status' => TransactionStatus::NEW,
                    'address' => $dto->address,
                    'coin' => $dto->coin,
                ],
            );

            $withdrawal = $withdrawalRepository->init($transaction->getKey());
        });

        return new WithdrawalResource($withdrawal);
    }

    /**
     * @param string $code
     * @return JsonSerializable
     */
    public function getRates(string $code, CurrencyRepository $currencyRepository): JsonSerializable
    {
        $currency = $currencyRepository->findByCode($code);

        return WithdrawalEstchangeResource::make($currency);
    }

}
