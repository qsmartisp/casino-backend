<?php

namespace App\Models;

use App\Enums\Deposit\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int id
 * @property int transaction_id
 * @property null|string external_transaction_id
 * @property string currency
 * @property null|float fee
 * @property Status|string status
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Transaction transaction
 * @property User payable
 */
class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'external_transaction_id',
        'status',
        'currency',
        'fee',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function payable(): MorphTo
    {
        return $this->transaction->payable();
    }
}
