<?php

namespace App\Http\Controllers\Deposit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deposit\EstchangeRequest;
use App\Http\Resources\Estchange\WalletAddressResource;
use App\Models\User;
use App\Services\Estchange\Tunnel\Gateway\EstchangeHelper;
use App\Services\Estchange\Tunnel\Gateway\EstchangeService;
use App\Services\Estchange\Tunnel\Gateway\Exceptions\CantGetAddressException;
use App\Services\Local\Repositories\CurrencyRepository;
use App\Services\Local\Repositories\WalletRepository;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use JsonSerializable;

class EstchangeController extends Controller
{
    /**
     * @throws GuzzleException
     * @throws JsonException
     * @throws \Bavix\Wallet\Internal\Exceptions\ExceptionInterface
     */
    public function create(
        EstchangeRequest $request,
        WalletRepository $walletRepository,
        CurrencyRepository $currencyRepository,
        EstchangeService $estchange,
    ): JsonSerializable {
        /** @var User $user */
        $user = $request->user();
        $coin = $request->coin;
        $currency = $currencyRepository->findByCode($request->currency);
        $wallet = $user->getWalletOrFail(mb_strtolower($currency->code));
        $clientId = EstchangeHelper::generateClientId(
            $user->id,
            $coin,
            $wallet->name,
        );

        $estchangeWallet = $estchange->createWallet(
            clientId: $clientId,
            cryptoCurrency: $coin,
            currency: EstchangeHelper::isCryptoCurrency($wallet->name)
                ? null
                : $wallet->name,
        );

        if (is_null($estchangeWallet->getAddress())) {
            throw new CantGetAddressException($estchangeWallet);
        }

        $walletRepository->setEstchangeData(
            $wallet->id,
            $estchangeWallet->getAddress(),
            $clientId,
        );

        return new WalletAddressResource($wallet->fresh());
    }
}
