<?php

namespace App\Models\Sanctum;

use App\Models\Country;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property int access_token_id
 * @property int refresh_token_id
 * @property int country_id
 * @property string ip
 * @property string browser
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 *
 * @property-read PersonalAccessToken accessToken
 * @property-read PersonalAccessToken refreshToken
 * @property-read Country country
 */
class Session extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'access_token_id',
        'refresh_token_id',
        'country_id',
        'ip',
        'browser',
    ];

    public function accessToken(): HasOne
    {
        return $this->hasOne(PersonalAccessToken::class, 'id', 'access_token_id');
    }

    public function refreshToken(): HasOne
    {
        return $this->hasOne(PersonalAccessToken::class, 'id', 'refresh_token_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
