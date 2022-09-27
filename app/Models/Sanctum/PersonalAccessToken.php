<?php

namespace App\Models\Sanctum;

use App\Enums\PersonalAccessToken\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

/**
 * @property int id
 * @property string tokenable_type
 * @property int tokenable_id
 * @property string name
 * @property string token
 * @property string abilities
 *
 * @property-read string status
 * @property-read Carbon expires_at
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 *
 * @property Session session
 * @property User tokenable
 */
class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use HasFactory;
    use SoftDeletes;

    protected $appends = [
        'status',
        'expires_at',
    ];

    public function getStatusAttribute(): Status
    {
        if ($this->trashed() || $this->expires_at->lte(now())) {
            return Status::Expired;
        }

        return Status::Active;
    }

    public function getExpiresAtAttribute(): Carbon
    {
        return $this->created_at->addMinutes(config('sanctum.expiration'));
    }

    public function session(): HasOne
    {
        return $this->hasOne(Session::class, 'access_token_id');
    }
}
