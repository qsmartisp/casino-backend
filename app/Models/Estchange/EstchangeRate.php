<?php

namespace App\Models\Estchange;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $currency
 * @property string $coin
 * @property string $coin_name
 * @property float|null $rate
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 */
class EstchangeRate extends Model
{
    protected $fillable = [
        'currency',
        'coin',
        'coin_name',
        'rate',
    ];


}
