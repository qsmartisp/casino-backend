<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int status_id
 * @property int number
 * @property int prize_amount
 * @property string prize_type
 * @property int cp
 *
 * @property-read Status $status
 */
class Level extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'status_id',
        'number',
        'prize_amount',
        'prize_type',
        'cp',
    ];

    /**
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * @param float $cp
     * @return Level
     */
    public static function getLevelByCp(float $cp): Level
    {
        return self::query()
            ->where('cp', '<=', $cp)
            ->orderBy('cp', 'ASC')
            ->get()
            ->last();
    }
}
