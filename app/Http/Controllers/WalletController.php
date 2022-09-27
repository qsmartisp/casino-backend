<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletRequest;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\WalletResource;
use App\Services\Local\Repositories\CurrencyRepository;
use App\Services\Local\Repositories\WalletRepository;
use Illuminate\Http\Request;
use JsonSerializable;

class WalletController extends Controller
{
    public function __construct(
        protected WalletRepository $walletRepository,
        protected CurrencyRepository $currencyRepository,
    ) {
    }

    public function index(Request $request): JsonSerializable
    {
        return WalletResource::collection($this->walletRepository->list($request->user()));
    }

    public function store(WalletRequest $request): JsonSerializable
    {
        $user = $request->user();

        if ($this->walletRepository->isSlugExist($user, $request->code)) {
            // todo: why not just finished and not return 200 OK ????
            abort(400, "Wallet {$request->code} already exist.");
        }

        $currency = $this->currencyRepository->findByCode($request->code);

        $this->walletRepository->createWallet($user, $currency);

        return new SuccessResource();
    }

    public function setDefault(WalletRequest $request): JsonSerializable
    {
        $user = $request->user();

        if (!$this->walletRepository->isSlugExist($user, $request->code)) {
            // todo: why not 404 ?
            abort(400, "The user does not have a {$request->code} wallet.");
        }

        $currency = $this->currencyRepository->findByCode($request->code);

        if ($user->default_currency_id === $currency->id) {
            // todo: why not just finished and not return 200 OK ????
            abort(400, "The user already has {$request->code} wallet as default.");
        }

        $this->currencyRepository->setDefault($user, $currency);

        return new SuccessResource();
    }
}
