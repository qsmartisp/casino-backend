<?php

namespace App\Models;

use App\Models\Binance\BinanceRate;
use App\Models\Estchange\EstchangeRate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 *
 * @property-read string $slug
 * @property-read EstchangeRate $estchangeRates
 * @property-read BinanceRate $binanceRate
 *
 * @method Builder|static byCode(?string $code)
 */
class Currency extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * @return HasMany
     */
    public function estchangeRates(): HasMany
    {
        return $this->hasMany(EstchangeRate::class, 'currency', 'code');
    }

    /**
     * @return HasOne
     */
    public function binanceRate(): HasOne
    {
        return $this->hasOne(BinanceRate::class, 'currency', 'code');
    }

    public function getSlugAttribute(): string
    {
        return strtolower($this->code);
    }

    public function scopeByCode(Builder $query, ?string $code): Builder
    {
        if (isset($code)) {
            $query->where('code', $code);
        }

        return $query;
    }
}
