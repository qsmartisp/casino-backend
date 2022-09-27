<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int user_id
 * @property int game_id
 * @property string game_name
 * @property int currency_id
 * @property string currency_code
 * @property float balance_before
 * @property float balance_after
 * @property float balance_amount
 * @property float|null bet
 * @property float|null cp
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Game game
 *
 * @method Builder|static hasUserId(?int $userId)
 */
class GameLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'game_id',
        'game_name',
        'currency_id',
        'currency_code',
        'balance_before',
        'balance_after',
        'balance_amount',
        'bet',
        'cp',
    ];

    public function scopeHasUserId(Builder $query, ?int $userId): Builder
    {
        if (isset($userId)) {
            $query->where('user_id', $userId);
        }

        return $query;
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
