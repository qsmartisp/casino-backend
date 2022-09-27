<?php

namespace App\Models;

use App\Enums\Game\Status as GameStatus;
use App\Models\Fundist\Game\Config;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property int id
 * @property int provider_id
 * @property int aggregator_id
 * @property string external_id
 * @property string slug
 * @property string name
 * @property bool active
 * @property string|array meta
 * @property bool is_desktop
 * @property bool is_mobile
 * @property null|int min_bet
 * @property null|int max_bet
 * @property GameStatus|string status
 * @property bool has_demo
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Config config
 * @property Provider provider
 * @property Aggregator aggregator
 *
 * @property Collection|File[] images
 */
class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'provider_id',
        'aggregator_id',
        'external_id',
        'meta',

        'is_desktop',
        'is_mobile',
        'min_bet',
        'max_bet',
        'status',
        'has_demo',
    ];

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(
            File::class,
            'file_identity',
            'identity_id',
            'file_id',
        )->where('identity_type', '=', Game::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function aggregator(): BelongsTo
    {
        return $this->belongsTo(Aggregator::class);
    }

    public function config(): BelongsTo
    {
        return $this->belongsTo(Config::class, 'slug');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
