<?php

namespace App\Models;

use App\Enums\File\Type;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $name
 * @property string $original_name
 * @property string $extension
 * @property string $mime_type
 * @property int $size
 * @property string $path
 *
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Type $typeEnum
 * @property-read User $user
 * @property-read User $game
 */
class File extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'identity_type',
        'identity_id',
        'type',
        'name',
        'original_name',
        'extension',
        'mime_type',
        'size',
        'path',
    ];

    public function getTypeEnumAttribute(): Type
    {
        return Type::from($this->type);
    }

    public function user(): BelongsTo
    {
        return $this
            ->belongsTo(User::class, 'identity_id')
            ->where('identity_type', '=', User::class);
    }

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(
            Game::class,
            'file_identity',
            'file_id',
            'identity_id',
        );
    }
}
