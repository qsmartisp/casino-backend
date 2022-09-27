<?php

namespace App\Http\Controllers\Deposit;

use App\Enums\Transaction\Status;
use App\Enums\Transaction\System;
use App\Http\Controllers\Controller;
use App\Http\Requests\Deposit\CoinbaseRequest;
use App\Http\Resources\Coinbase\ChargeResource;
use App\Models\User;
use App\Services\Coinbase\CoinbaseApiService;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use JsonSerializable;

class CoinbaseController extends Controller
{
    /**
     * @throws GuzzleException
     * @throws JsonException
     * @throws \Bavix\Wallet\Internal\Exceptions\ExceptionInterface
     */
    public function start(
        CoinbaseRequest $request,
        CoinbaseApiService $coinbaseApi,
    ): JsonSerializable {
        /** @var User $user */
        $user = $request->user();

        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $wallet = $user->getWalletOrFail(mb_strtolower($currency));

        $response = $coinbaseApi->createCharge(
            amount: $amount,
            currency: $currency,
            name: 'Transfer #' . substr(str_shuffle(MD5(microtime())), 0, 10),
            description: 'Transfer money from your crypto wallet to CryptoBoss wallet', // todo
            metadata: [
                'user_id' => $user->id,
            ],
        );

        $wallet->depositFloat(
            amount: $amount,
            meta: [
                'system' => System::Coinbase,
                'status' => Status::NEW,
            ],
            confirmed: false,
        );

        return new ChargeResource($response);
    }
}
