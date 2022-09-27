<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameHistoryResource;
use App\Models\GameHistory;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use JsonSerializable;

class GameHistoryController extends Controller
{
    public function index(Request $request): JsonSerializable
    {
        $user = $request->user();

        /** @var GameHistory|Builder $query */
        $query = GameHistory::query();

        $gameHistories = $query
            ->hasUserId($user->id)
            ->orderBy('updated_at', 'DESC')
            ->simplePaginate('20');

        return GameHistoryResource::collection($gameHistories);
    }

}
