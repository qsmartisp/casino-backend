<?php

namespace App\Models\Fundist\User;

use App\Models\Currency;
use App\Models\User;
use App\Services\Fundist\Credential as FundistCredential;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int user_id
 * @property int currency_id
 * @property string login
 * @property string password
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property User user
 * @property Currency currency
 */
class Credential extends Model implements FundistCredential
{
    use HasFactory;

    protected $table = 'fundist_user_credentials';

    protected $fillable = [
        'user_id',
        'currency_id',
        'login',
        'password',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
