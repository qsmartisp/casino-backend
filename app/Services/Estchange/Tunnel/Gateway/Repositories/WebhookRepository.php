<?php

namespace App\Services\Estchange\Tunnel\Gateway\Repositories;

use App\Models\Estchange\Tunnel\Gateway\Webhook;
use App\Services\Estchange\Tunnel\Gateway\Repository;
use Illuminate\Database\Eloquent\Builder;

class WebhookRepository extends Repository
{
    public function __construct(
        protected Webhook $model,
    ) {
    }

    public function store(string $id, array $data): Webhook
    {
        return $this->query()->create([
            'webhook_id' => $id,
            'request_data' => $data,
        ]);
    }

    public function addResponseData(string $id, array $data): bool
    {
        return $this->query()
            ->where('webhook_id', $id)
            ->update(['response_data' => $data]);
    }

    protected function query(): Builder|Webhook
    {
        return $this->model->newQuery();
    }
}
