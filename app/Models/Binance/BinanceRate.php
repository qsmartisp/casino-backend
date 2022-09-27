<?php

namespace App\Models\Binance;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $currency
 * @property string $symbol
 * @property float|null $rate
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 */
class BinanceRate extends Model
{
    protected $fillable = [
        'currency',
        'symbol',
        'rate',
    ];


}
