<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Resources\Auth\SignupResource;
use App\Services\Local\Repositories\UserRepository;
use App\Services\Local\Repositories\WalletRepository;
use Bavix\Wallet\Internal\Exceptions\ExceptionInterface;
use Bavix\Wallet\Internal\Service\DatabaseServiceInterface;
use Illuminate\Support\Facades\Hash;
use JsonSerializable;

class SignupController extends Controller
{
    /**
     * @throws ExceptionInterface
     */
    public function __invoke(
        SignupRequest $request,
        DatabaseServiceInterface $databaseService,
        UserRepository $userRepository,
        WalletRepository $walletRepository,
    ): JsonSerializable {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        $user = null;

        $databaseService->transaction(static function () use (
            $userRepository,
            $walletRepository,
            &$user,
            $data,
        ) {
            /** @var \App\Models\User $user */
            $user = $userRepository->store($data);

            $wallet = $walletRepository->createWallet($user, $user->currency);
            $walletRepository->createWalletCp($user);

            // todo: remove on prod
            $wallet->depositFloat(100);
        });

        return new SignupResource($user);
    }
}
