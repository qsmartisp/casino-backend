<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property string title
 * @property string prize
 *
 * @property-read Collection|Level[] levels
 * @property-read int $min_cp
 * @property-read int $max_cp
 */
class Status extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'prize',
    ];

    /**
     * @return HasMany
     */
    public function levels(): HasMany
    {
        return $this->hasMany(Level::class);
    }

    /**
     * @return int
     */
    public function getMinCpAttribute(): int
    {
        return $this->levels->min('cp');
    }

    /**
     * @return int
     */
    public function getMaxCpAttribute(): int
    {
        return $this->levels->max('cp');
    }
}
