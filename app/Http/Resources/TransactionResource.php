<?php

namespace App\Http\Resources;

use App\Enums\Transaction\System;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Transaction
 */
class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'type' => $this->type,
            'system_title' => System::tryFrom($this->metaSystem)->name
                ?? "unknown ({$this->metaSystem})",
            'status' => $this->metaStatus,
            'sum' => number_format($this->amountFloat, 2, '.', ''),
            'currency_code' => mb_strtoupper($this->wallet->slug),
            'created_at' => $this->created_at,
        ];
    }
}
