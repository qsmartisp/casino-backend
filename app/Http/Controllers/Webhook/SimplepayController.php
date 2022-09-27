<?php

namespace App\Http\Controllers\Webhook;

use App\Enums\Simplepay\Webhook\Method;
use App\Http\Controllers\Controller;
use App\Http\Requests\Webhook\SimplepayRequest;
use App\Http\Resources\Simplepay\WebhookResource;
use App\Services\Deposit\ContextService;
use App\Services\Local\Repositories\DepositRepository;
use App\Services\Simplepay\Enums\Webhook\Status;
use App\Services\Simplepay\Exceptions\DepositNotFound;
use App\Services\Simplepay\Exceptions\SignatureNotValid;
use App\Services\Simplepay\Exceptions\SimplepayException;
use App\Services\Simplepay\Exceptions\WalletNotFound;
use App\Services\Simplepay\Exceptions\WrongAmount;
use App\Services\Simplepay\SignatureService;
use App\Services\Simplepay\WebhookService;

class SimplepayController extends Controller
{
    /**
     * @throws \JsonException
     * @throws \Exception
     */
    public function __invoke(
        SimplepayRequest $request,

        WebhookService $webhookService,
        SignatureService $signatureService,

        DepositRepository $depositRepository,
        ContextService $depositContextService,
    ): WebhookResource {
        $now = now();

        try {
            $strForHashing = $signatureService->prepareData(
                $this->getParamsInRightOrder($request->keys(), $request->dto->params->toArray())
            );

            if ($request->dto->signature !== $signatureService->signatureForWebhook($strForHashing)) {
                throw new SignatureNotValid();
            }

            $deposit = $depositRepository->getById(
                $depositContextService->idFromContext($request->dto->params->order)
            );

            if (null === $deposit) {
                throw new DepositNotFound();
            }

            $depositAmount = number_format(
                $deposit->transaction->amountFloat,
                2,
                '.',
                '',
            );

            if ($depositAmount !== $request->dto->params->amount) {
                throw new WrongAmount();
            }

            $wallet = $deposit->payable->getWallet(
                mb_strtolower($request->dto->params->data?->currency)
            );

            if (null === $wallet) {
                throw new WalletNotFound();
            }

            $walletCp = $deposit->payable->getWallet('cp');

            if (null === $walletCp) {
                // todo: create cp wallet
            }

            $result = match ($request->dto->params->method) {
                Method::Check->value,
                => $webhookService->handleCheck($now),

                Method::Pay->value,
                => $webhookService->handlePay(
                    $now,
                    fn() => $depositRepository->finish(
                        $deposit,
                        $request->dto->params->id,
                    ),
                ),

                Method::PaymentCharged->value,
                Method::PaymentRefunded->value,
                Method::PaymentRejected->value,
                Method::PaymentReversed->value,
                Method::PaymentVoided->value,
                => $webhookService->handlePayment($now),

                Method::TransferFailed->value,
                Method::TransferPaid->value,
                Method::TransferRejected->value,
                Method::TransferReversed->value,
                => $webhookService->handleTransfer($now),
            };
        } catch (SignatureNotValid $exception) {
            $result = $webhookService->handleError($now, $exception, Status::FailHash);
        } catch (SimplepayException $exception) {
            $result = $webhookService->handleError($now, $exception);
        } catch (\Throwable $exception) {
            $result = $webhookService->handleException($now, $exception);
        }

        $response = $result->toArray();

        return new WebhookResource([
            'response' => $response,
            'hash' => $signatureService->signatureForWebhook(
                json_encode($response, JSON_THROW_ON_ERROR),
            ),
        ]);
    }

    /**
     * @throws \JsonException
     */
    private function getParamsInRightOrder(array $keys, array $dtoParams): array
    {
        $params = [];

        foreach ($keys as $key) {
            if (isset($dtoParams[$key])) {
                $params[$key] = in_array($key, ['data', 'attributes'])
                    ? json_encode($dtoParams[$key], JSON_THROW_ON_ERROR)
                    : $dtoParams[$key];
            }
        }

        return $params;
    }
}
