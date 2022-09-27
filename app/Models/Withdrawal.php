<?php

namespace App\Models;

use App\Enums\Transaction\System;
use App\Enums\Withdrawal\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int transaction_id
 * @property Status|string status
 * @property null|string external_withdrawal_id
 * @property null|string tx_id
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Transaction transaction
 */
class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'external_withdrawal_id',
        'status',
        'tx_id',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function getSystemAttribute(): ?string
    {
        return $this->transaction->metaSystem;
    }

    public function setSystemAttribute($value): void
    {
        $this->transaction->meta['system'] = $value;
    }

    public function getAddressAttribute(): ?string
    {
        return $this->transaction->meta['address'] ?? null;
    }

    public function setAddressAttribute($value): void
    {
        $this->transaction->meta['address'] = $value;
    }

    public function getCoinAttribute(): ?string
    {
        return $this->transaction->meta['coin'] ?? null;
    }

    public function setCoinAttribute($value): void
    {
        $this->transaction->meta['coin'] = $value;
    }

    public function getTIDAttribute(): ?string
    {
        return $this->transaction->meta[$this->getTransactionIdKeyBySystem()] ?? null;
    }

    public function setTIDAttribute($value): void
    {
        $this->transaction->meta[$this->getTransactionIdKeyBySystem()] = $value;
    }

    private function getTransactionIdKeyBySystem()
    {
        return match ($this->transaction->metaSystem) {
            System::Estchange->value => 'estchange_transaction_id',
            System::Coinbase->value => 'coinbase_transaction_id',
        };
    }

    public function payable()
    {
        return $this->transaction->payable();
    }
}
