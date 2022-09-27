<?php

namespace App\Models;

use App\Enums\Game\Launch\Type;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int user_id
 * @property int game_id
 * @property int provider_id
 * @property int aggregator_id
 * @property string|Type type
 * @property string ip
 * @property string|array data
 * @property null|string|array meta
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property User user
 * @property Game game
 * @property Provider provider
 * @property Aggregator aggregator
 */
class Launch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'provider_id',
        'aggregator_id',
        'type',
        'ip',
        'data',
        'meta',
    ];

    protected $casts = [
        'data' => AsCollection::class,
        'meta' => AsCollection::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function aggregator(): BelongsTo
    {
        return $this->belongsTo(Aggregator::class);
    }
}
