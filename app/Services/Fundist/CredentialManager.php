<?php

namespace App\Services\Fundist;

use App\Models\User;
use App\Services\Fundist\Exceptions\CantCreateUserException;
use App\Services\Local\Repositories\Fundist\User\CredentialRepository;

class CredentialManager
{
    public function __construct(
        protected CredentialGenerator $credentialGenerator,
        protected CredentialRepository $credentialRepository,
        protected DemoCredential $demoCredential,
        protected FundistService $fundistService,
    ) {
    }

    public function getDemoCredential(): Credential
    {
        return $this->demoCredential;
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function getCredential(string $ip, User $user): Credential
    {
        return $this->credentialRepository->findByUser($user)
            ?? $this->createCredential($user, $ip);
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    private function createCredential(User $user, string $ip): Credential
    {
        $login = $this->credentialGenerator->generateGameLogin($user->id);
        $password = $this->credentialGenerator->generateGamePassword();

        $fundistUser = $this->fundistService->addUser(
            login: $login,
            password: $password,
            currency: $user->currency->code,
            language: 'en', // todo
            reg_ip: $ip,
        );

        if ($fundistUser->isCreated() === false) {
            throw new CantCreateUserException($fundistUser);
        }

        return $this->credentialRepository->createByUserInfo($user, $login, $password);
    }
}
