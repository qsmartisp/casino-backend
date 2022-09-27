<?php

namespace App\Services\Deposit;

class ContextService
{
    public function __construct(
        protected string $prefix,
        protected string $delim,
    ) {
    }

    public function addContextForId(
        int $id,
        ?string $prefix = null,
        ?string $delim = null,
    ): string {
        $prefix = $prefix ? : $this->prefix;
        $delim = $delim ? : $this->delim;

        return $prefix . $delim . $id;
    }

    public function idFromContext(
        string $context,
        ?string $delim = null,
    ): ?int {
        $delim = $delim ? : $this->delim;
        @[, $id] = explode($delim, $context);

        return $id;
    }
}
