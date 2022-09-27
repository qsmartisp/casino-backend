<?php

namespace App\Http\Controllers;

use App\Enums\Verification\Type;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\SelfExclusionRequest;
use App\Http\Requests\VerificationRequestRequest;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\SuccessResource;
use App\Models\User;
use App\Services\Local\Repositories\WalletRepository;
use App\Models\VerificationRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use JsonSerializable;

class ProfileController extends Controller
{
    public function __construct(
        protected WalletRepository $walletRepository,
    ) {
    }

    public function show(): JsonSerializable
    {
        /** @var User $user */
        $user = auth()->user();
        $user->wallets = $this->walletRepository->list($user);

        return new ProfileResource($user);
    }

    public function update(ProfileRequest $request): JsonSerializable
    {
        /** @var User $user */
        $user = auth()->user();

        $user->fill($request->validated());
        $user->save();

        return new SuccessResource();
    }

    /**
     * Self-exclusion user.
     *
     * @param SelfExclusionRequest $request
     *
     * @return JsonSerializable
     */
    public function sendSelfExclusion(SelfExclusionRequest $request): JsonSerializable
    {
        $data = $request->validated();

        /** @var User $user */
        $user = auth()->user();

        if (Hash::check($request->input('password'), $user->password)) {
            $user->self_exclusion_until = Carbon::now()->addSeconds($data['duration']);
            $user->save();

            return new SuccessResource();
        }

        return new SuccessResource([
            'success' => false,
            'message' => 'Password does not match.',
        ]);
    }

    /**
     * Verification request
     *
     * @param VerificationRequestRequest $request
     *
     * @return JsonSerializable
     */
    public function sendVerificationRequest(VerificationRequestRequest $request): JsonSerializable
    {
        $data = $request->validated();

        /** @var User $user */
        $user = auth()->user();

        $verificationRequest = $this->createVerificationRequest($user->id, $data);

        $user->verification_status = $verificationRequest->status;
        $user->save();

        return new SuccessResource();
    }

    /**
     * @param int $userId
     * @param array $data
     *
     * @return VerificationRequest
     */
    private function createVerificationRequest(int $userId, array $data): VerificationRequest
    {
        $verificationRequest = new VerificationRequest();
        $verificationRequest->user_id = $userId;
        $verificationRequest->status = Type::Waiting->value;
        $verificationRequest->save();

        $verificationRequest->files()->attach($data['files_ids']);

        return $verificationRequest;
    }
}
