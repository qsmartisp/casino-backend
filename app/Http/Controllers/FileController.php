<?php

namespace App\Http\Controllers;

use App\Enums\File\Type;
use App\Http\Requests\FileRequest;
use App\Http\Resources\FileResource;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Database\Eloquent\Collection;
use JsonSerializable;

class FileController extends Controller
{
    /**
     * FileController constructor.
     *
     * @param FileService $fileService
     */
    public function __construct(
        protected FileService $fileService,
    ) {
    }

    /**
     * Get auth user file list.
     *
     * @return JsonSerializable
     */
    public function index(): JsonSerializable
    {
        /** @var User $user */
        $user = auth()->user();

        $lastVerificationRequest = $user
            ->verificationRequests()
            ->latest()
            ->first();

        return FileResource::collection($lastVerificationRequest->files ?? []);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FileRequest $request
     *
     * @return JsonSerializable
     */
    public function store(FileRequest $request): JsonSerializable
    {
        /** @var User $user */
        $user = auth()->user();

        if (!$request->hasfile('files')) {
            return abort(403, 'Request has not files.');
        }

        /** @var Collection $files */
        $files = $this->fileService->store(
            $request->file('files'),
            Type::from($request->input('type')),
        );

        $user->files()->attach(
            $files->pluck('id')->toArray(),
            ['identity_type' => User::class]
        );

        return FileResource::collection($files);
    }

}
