<?php

namespace App\Models;

use Bavix\Wallet\Models\Wallet as BavixWallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string|null $meta
 *
 * @property-read null|string metaAddress
 * @property-read null|string metaEstchangeClientId
 */
class Wallet extends BavixWallet
{
    use HasFactory;

    public function getMetaAddressAttribute()
    {
        return $this->meta['address'] ?? null;
    }

    public function getMetaEstchangeClientIdAttribute()
    {
        return $this->meta['estchange_client_id'] ?? null;
    }
}
