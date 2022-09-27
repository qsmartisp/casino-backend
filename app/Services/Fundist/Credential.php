<?php

namespace App\Services\Fundist;

interface Credential
{
    public function getLogin(): string;

    public function getPassword(): string;
}
