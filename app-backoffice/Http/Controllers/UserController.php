<?php

namespace AppBackoffice\Http\Controllers;

use App\Models\User;
use AppBackoffice\Http\Resources\UserResource;
use AppBackoffice\Services\Local\Repositories\UserRepository;
use Illuminate\Http\Request;
use JsonSerializable;

class UserController extends Controller
{
    public function __construct(
        protected UserRepository $repository,
    ) {
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonSerializable
    {
        return UserResource::collection(
            $this->repository
                ->filterUsers($request->email)
                ->simplePaginate($request->input('per_page', config('users.index_pagination', 18))),
        );
    }

}
