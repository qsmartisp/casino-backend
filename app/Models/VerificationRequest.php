<?php

namespace App\Models;

use App\Enums\File\Type;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $user_id
 * @property string $status
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User $user
 * @property-read Collection|File[] $files
 */
class VerificationRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function files(): BelongsToMany
    {
        return $this->belongsToMany(
            File::class,
            'file_verification_request',
            'verification_request_id',
            'file_id',
        );
    }

    public function primaryFiles(): BelongsToMany
    {
        return $this->files()->where('files.type', Type::Primary->value);
    }

    public function selfieFiles(): BelongsToMany
    {
        return $this->files()->where('files.type', Type::Selfie->value);
    }

    public function paymentFiles(): BelongsToMany
    {
        return $this->files()->where('files.type', Type::Payment->value);
    }

    public function addressFiles(): BelongsToMany
    {
        return $this->files()->where('files.type', Type::Address->value);
    }
}
