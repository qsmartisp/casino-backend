<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Models\User;
use Illuminate\Http\Request;
use JsonSerializable;

class TransactionController extends Controller
{
    public function index(Request $request): JsonSerializable
    {
        /** @var User $user */
        $user = $request->user();

        $transactions = $user
            ->transactions()
            ->whereNotNull('meta->system')
            ->orderByDesc('created_at')
            ->simplePaginate(20);

        return TransactionResource::collection($transactions);
    }
}
