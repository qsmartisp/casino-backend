<?php

namespace App\Models\BGaming\User;

use App\Models\User;
use App\Services\BGaming\Credential as BGamingCredential;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int user_id
 * @property string login
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property User user
 */
class Credential extends Model implements BGamingCredential
{
    use HasFactory;

    protected $table = 'bgaming_user_credentials';

    protected $fillable = [
        'user_id',
        'login',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getLogin(): string
    {
        return $this->login;
    }
}
