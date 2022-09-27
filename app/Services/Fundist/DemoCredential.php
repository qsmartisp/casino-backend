<?php

namespace App\Services\Fundist;

class DemoCredential implements Credential
{
    public function __construct(
        protected string $login,
        protected string $password,
    ) {
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
