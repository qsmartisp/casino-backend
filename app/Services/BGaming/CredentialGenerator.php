<?php

namespace App\Services\BGaming;

class CredentialGenerator
{
    public function __construct(
        protected string $prefix,
        protected string $delim,
    ) {
    }

    public function generateGameLogin(
        int $userId,
        ?string $prefix = null,
        ?string $delim = null,
    ): string {
        $prefix = $prefix ? : $this->prefix;
        $delim = $delim ? : $this->delim;

        return $prefix . $delim . $this->randomString() . $delim . $userId;
    }

    protected function randomString(int $length = 5): string
    {
        return substr(str_shuffle(MD5(microtime())), 0, $length);
    }
}
