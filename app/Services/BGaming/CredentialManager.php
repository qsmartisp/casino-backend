<?php

namespace App\Services\BGaming;

use App\Models\User;
use App\Services\Local\Repositories\BGaming\User\CredentialRepository;

class CredentialManager
{
    public function __construct(
        protected CredentialGenerator $credentialGenerator,
        protected CredentialRepository $credentialRepository,
        protected BGamingService $bgamingService,
    ) {
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function getCredential(User $user): Credential
    {
        return $this->credentialRepository->findByUser($user)
            ?? $this->createCredential($user);
    }

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    private function createCredential(User $user): Credential
    {
        return $this->credentialRepository->createByUserInfo(
            $user,
            $this->credentialGenerator->generateGameLogin($user->id),
        );
    }
}
