<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Http\Requests\Webhook\BGamingRequest;
use App\Http\Resources\BGaming\WebhookResource;
use App\Services\BGaming\Exceptions\BGamingException;
use App\Services\BGaming\Exceptions\CurrencyNotFoundException;
use App\Services\BGaming\Exceptions\UserNotFoundException;
use App\Services\BGaming\SignatureService;
use App\Services\BGaming\WebhookService;
use App\Services\Local\Repositories\BGaming\User\CredentialRepository;
use Bavix\Wallet\Internal\Exceptions\ExceptionInterface;

class BGamingController extends Controller
{
    /**
     * @throws \JsonException
     * @throws \Exception
     */
    public function __invoke(
        string $requestType,
        BGamingRequest $request,
        WebhookService $webhookService,
        SignatureService $signatureService,
        CredentialRepository $credentialRepository,
    ): WebhookResource {
        if ($request->dto->signature !== $signatureService->signature($request->dto->params->toString())) {
            //abort(403, 'Signature mismatch');
        }

        $credential = $credentialRepository->findByLogin($request->dto->params->userId);

        if (null === $credential) {
            throw new UserNotFoundException();
        }

        $wallet = $credential->user->getWallet(mb_strtolower($request->dto->params->currency));

        if (null === $wallet) {
            throw new CurrencyNotFoundException();
        }

        $walletCp = $credential->user->getWallet('cp');

        if (null === $walletCp) {
            // todo: create cp wallet
        }

        try {
            $result = match ($requestType) {
                'play' => $webhookService->handlePlay($request->dto, $wallet, $walletCp),
                'rollback' => $webhookService->handleRollback($request->dto, $wallet),
                'freespins' => $webhookService->handleFreespins($request->dto, $wallet),
            };
        } catch (BGamingException $exception) {
            $result = $webhookService->handleError($request->dto, $exception);
        } catch (ExceptionInterface $e) {
            // todo
            $result = $webhookService->handleException($request->dto, $e);
        }

        return new WebhookResource($result->toArray());
    }
}
