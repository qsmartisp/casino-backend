<?php

namespace App\Models;

use Bavix\Wallet\Models\Transaction as BavixTransaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string|null $meta
 * @property Carbon|string $created_at
 *
 * @property-read null|string metaSystem
 * @property-read null|string metaStatus
 * @property-read null|string metaCoin
 * @property-read null|string metaAddress
 * @property-read null|string metaEstchangeCoin
 * @property-read null|string metaEstchangeTransactionId
 */
class Transaction extends BavixTransaction
{
    use HasFactory;

    public function getMetaSystemAttribute()
    {
        return $this->meta['system'] ?? null;
    }

    public function getMetaStatusAttribute()
    {
        return $this->meta['status'] ?? null;
    }

    public function getMetaCoinAttribute()
    {
        return $this->meta['coin'] ?? null;
    }

    public function getMetaEstchangeTransactionIdAttribute()
    {
        return $this->meta['estchange_transaction_id'] ?? null;
    }

    public function getMetaAddressAttribute()
    {
        return $this->meta['address'] ?? null;
    }
}
