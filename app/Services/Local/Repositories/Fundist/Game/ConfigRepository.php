<?php

namespace App\Services\Local\Repositories\Fundist\Game;

use App\Models\Fundist\Game\Config;
use App\Services\Local\Repository;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method Config firstOrCreate(array $attributes = [], array $values = [])
 */
class ConfigRepository extends Repository
{
    public function query(): Config|Builder
    {
        return Config::query();
    }
}
