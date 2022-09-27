<?php

namespace App\Services\Local\Repositories;

use App\Models\Fundist\Webhook;
use App\Services\Local\Repository;
use Illuminate\Database\Eloquent\Builder;

class WebhookRepository extends Repository
{
    public function query(): Webhook|Builder
    {
        return Webhook::query();
    }

    public function findByHmac(string $hmac): ?Webhook
    {
        return $this->query()->where('hmac', $hmac)->first();
    }

    public function findByTid(string $tid): ?Webhook
    {
        return $this->query()->where('tid', $tid)->first();
    }
}
