<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int user_id
 * @property string game_id
 * @property string name
 * @property string spin_id
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
 * @method Builder|static hasUserId(?int $userId)
 */
class GameHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'game_id',
        'name',
        'spin_id',
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


}
