<?php

namespace App\Services\Local\Repositories;

use App\Models\GameLog;
use App\Services\Local\Repository;
use Illuminate\Database\Eloquent\Builder;

class GameLogRepository extends Repository
{
    public function query(): GameLog|Builder
    {
        return GameLog::query();
    }
}
