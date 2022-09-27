<?php

namespace App\Models;

use App\Models\Sanctum\PersonalAccessToken;
use App\Models\Sanctum\Session;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Interfaces\WalletFloat;
use Bavix\Wallet\Traits\HasWalletFloat;
use Bavix\Wallet\Traits\HasWallets;
use Carbon\Carbon;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $nickname
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $gender
 * @property int $country_id
 * @property string|null $city
 * @property string|null $address
 * @property string|null $postal_code
 * @property string|null $phone
 * @property bool $subscription_by_email
 * @property bool $subscription_by_sms
 * @property int $default_currency_id
 *
 * @property Carbon|null disabled_at
 * @property string verification_status
 * @property Carbon|null self_exclusion_until
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $date_of_birth
 * @property Carbon|null $phone_verified_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read string $cpBalance
 * @property-read Level $level
 * @property-read Country $country
 * @property-read Currency $currency
 * @property-read Collection|Session[] $sessions
 * @property-read Collection|File[] $files
 * @property-read bool $is_disabled
 * @property-read VerificationRequest $verificationRequest
 */
class User extends Authenticatable implements Wallet, WalletFloat
{
    use HasWalletFloat;
    use HasWallets;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use CanResetPassword;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'nickname',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'country_id',
        'city',
        'address',
        'postal_code',
        'phone',
        'subscription_by_email',
        'subscription_by_sms',
        'default_currency_id',
        'email_verified_at',
        'disabled_at',
        'verification_status',
        'verified_at',
        'self_exclusion_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'disabled_at' => 'datetime',
        'verified_at' => 'datetime',
        'self_exclusion_until' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'default_currency_id', 'id');
    }

    public function sessions(): HasManyThrough
    {
        return $this->hasManyThrough(
            Session::class,
            PersonalAccessToken::class,
            'tokenable_id',
            'access_token_id',
        );
    }

    public function verificationRequests(): HasMany
    {
        return $this->hasMany(
            VerificationRequest::class
        );
    }

    /**
     * Get the refresh token currently associated with the user.
     *
     * @return \Laravel\Sanctum\Contracts\HasAbilities
     */
    public function currentRfreshToken()
    {
        /** @var PersonalAccessToken $personalAccessToken */
        $personalAccessToken = PersonalAccessToken::query()
            ->leftJoinWhere('sessions', 'access_token_id', '=', $this->currentAccessToken()->id)
            ->firstOrFail();

        return $personalAccessToken;
    }

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(
            File::class,
            'file_identity',
            'identity_id',
            'file_id',
        )->where('identity_type', __CLASS__);
    }

    public function getIsDisabledAttribute(): bool
    {
        return !is_null($this->disabled_at);
    }

    public function getLevelAttribute(): Level
    {
        return Level::getLevelByCp($this->cpBalance);
    }

    public function getCpBalanceAttribute(): string
    {
        return $this->getWallet('cp')->balanceFloat;
    }
}
