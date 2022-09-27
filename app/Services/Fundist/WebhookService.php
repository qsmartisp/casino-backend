<?php

namespace App\Services\Fundist;

use App\Events\BetReceiveEvent;
use App\Models\GameHistory;
use App\Services\Fundist\DTO\Webhook\RequestParamsDTO;
use App\Services\Fundist\DTO\Webhook\ResponseParamsDTO;
use App\Services\Fundist\Exceptions\IncorrectHMACException;
use App\Services\Fundist\Exceptions\InvalidCurrencyException;
use App\Services\Fundist\Exceptions\TransactionParameterMismatchException;
use App\Services\Fundist\Responses\Webhook\BalanceResponse;
use App\Services\Fundist\Responses\Webhook\CreditResponse;
use App\Services\Fundist\Responses\Webhook\DebitResponse;
use App\Services\Fundist\Responses\Webhook\ErrorResponse;
use App\Services\Fundist\Responses\Webhook\PingResponse;
use App\Services\Local\Repositories\Fundist\User\CredentialRepository;
use App\Services\Local\Repositories\GameHistoryRepository;
use App\Services\Local\Repositories\GameLogRepository;
use App\Services\Local\Repositories\WebhookRepository;
use App\Services\WalletExchangeService;
use Bavix\Wallet\Internal\Exceptions\ExceptionInterface;
use Bavix\Wallet\Internal\Service\DatabaseServiceInterface;
use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Throwable;

class WebhookService
{
    public function __construct(
        protected Responder $responder,
        protected WalletExchangeService $walletExchangeService,
    ) {
    }

    /**
     * @throws UnknownProperties
     */
    public function handle(
        Request $request,
        CredentialRepository $credentialRepository,
        WebhookRepository $webhookRepository,
        DatabaseServiceInterface $databaseService,
        GameLogRepository $gameLogRepository,
        GameHistoryRepository $gameHistoryRepository,
        LoggerInterface $logger,
    ): WebhookResponse {
        try {
            return $this->process(
                $request,
                $credentialRepository,
                $webhookRepository,
                $databaseService,
                $gameLogRepository,
                $gameHistoryRepository,
            );
        } catch (Throwable $e) {
            // todo: add logs
            $logger->error('[Webhook::ERROR]', [
                'exception' => $e,
            ]);

            return $this->handleExceptions($e);
        }
    }

    /**
     * @throws ExceptionInterface
     */
    protected function getBalance(
        RequestParamsDTO $requestDto,
        CredentialRepository $credentialRepository,
        DatabaseServiceInterface $databaseService,
        GameLogRepository $gameLogRepository,
        GameHistoryRepository $gameHistoryRepository,
    ): string {
        $credential = $credentialRepository->findByLogin($requestDto->userid);

        return $databaseService->transaction(function () use (
            $credential,
            $requestDto,
            $gameLogRepository,
            $gameHistoryRepository,
        ) {
            $wallet = $credential->user->getWallet(mb_strtolower($requestDto->currency));
            [$gameId, $gameName] = explode('|', $requestDto->i_extparam);

            if (is_null($wallet)) {
                throw new InvalidCurrencyException();
            }

            // todo: Game implements Product

            $balanceBefore = $wallet->balanceFloat;
            $balanceAmount = 0;

            switch ($requestDto->type) {
                case 'credit':
                    $wallet->depositFloat((float)$requestDto->amount);
                    $balanceAmount = $requestDto->amount;
                    break;
                case 'debit':
                    $wallet->withdrawFloat((float)$requestDto->amount);
                    $balanceAmount = $requestDto->amount * (-1);
                    BetReceiveEvent::dispatch($credential->user, $this->walletExchangeService->getCpAmount($requestDto->currency, (float)$requestDto->amount));
                    break;
                default:
                    break;
            }

            if ($requestDto->type === 'credit' || $requestDto->type === 'debit') {
                $gameLogRepository->store([
                    'user_id' => $credential->user->id,
                    'game_id' => $gameId,
                    'game_name' => $gameName,
                    'currency_id' => 1, // todo: remove column from gamelogs Table
                    'currency_code' => $requestDto->currency,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $wallet->balanceFloat,
                    'balance_amount' => $balanceAmount,
                    'bet' => (float)$requestDto->amount,
                    'cp' => $requestDto->type === 'debit' ? $this->walletExchangeService->getCpAmount($requestDto->currency, (float)$requestDto->amount) : 0,
                ]);

                $gameHistory = GameHistory::query()
                    ->where('spin_id', $requestDto->i_gameid)
                    ->first();

                if ($gameHistory) {
                    $gameHistoryRepository->update($gameHistory, [
                        'balance_before' => $balanceBefore,
                        'balance_after' => $wallet->balanceFloat,
                        'balance_amount' => $requestDto->type === 'debit' ?
                            $gameHistory->balance_amount + $balanceAmount :
                            $balanceAmount,
                        'bet' => $requestDto->type === 'debit' ?
                            $gameHistory->bet + (float)$requestDto->amount :
                            $gameHistory->bet,
                        'cp' => $requestDto->type === 'debit' ?
                            $gameHistory->cp + $this->walletExchangeService->getCpAmount($requestDto->currency, (float)$requestDto->amount) :
                            $gameHistory->cp,
                    ]);
                } else {
                    $gameHistoryRepository->store([
                        'user_id' => $credential->user->id,
                        'game_id' => $gameId,
                        'name' => $gameName,
                        'spin_id' => $requestDto->i_gameid,
                        'currency_code' => $requestDto->currency,
                        'balance_before' => $balanceBefore,
                        'balance_after' => $wallet->balanceFloat,
                        'balance_amount' => $balanceAmount,
                        'bet' => (float)$requestDto->amount,
                        'cp' => $requestDto->type === 'debit' ?
                            $this->walletExchangeService->getCpAmount($requestDto->currency, (float)$requestDto->amount) :
                            0,

                    ]);
                }
            }

            return number_format($wallet->balanceFloat, 2, '.', '');
        });
    }

    /**
     * @throws ExceptionInterface
     * @throws UnknownProperties
     */
    protected function process(
        Request $request,
        CredentialRepository $credentialRepository,
        WebhookRepository $webhookRepository,
        DatabaseServiceInterface $databaseService,
        GameLogRepository $gameLogRepository,
        GameHistoryRepository $gameHistoryRepository,
    ): WebhookResponse {
        $requestDto = new RequestParamsDTO(
            request: $request->validated(),
        );

        $hmac = $this->responder->makeHmac($requestDto);

        if ($hmac !== $requestDto->hmac) {
            throw new IncorrectHMACException($requestDto->toArray());
        }

        $repeat = false;
        $balance = null;
        $webhook = $webhookRepository->findByHmac($requestDto->hmac);

        if ($webhook) {
            if (
                $requestDto->type !== 'balance'
                && $webhook->request_data->toArray() === $request->toArray()
            ) {
                $balance = $webhook->response_data->get('balance');
                $repeat = true;
            }
        } else {
            if ($requestDto->tid) {
                $webhook = $webhookRepository->findByTid($requestDto->tid);
            }

            if ($webhook) {
                if (
                    $webhook->tid === $requestDto->tid
                    && (
                        $webhook->type !== $requestDto->type
                        || $webhook->userid !== $requestDto->userid
                        || $webhook->currency !== $requestDto->currency
                        || $webhook->amount !== $requestDto->amount
                    )
                ) {
                    throw new TransactionParameterMismatchException();
                }
            } else {
                $webhook = $webhookRepository->store([
                    'hmac' => $requestDto->hmac,
                    'type' => $requestDto->type,
                    'tid' => $requestDto->tid ?? null,
                    'subtype' => $requestDto->subtype ?? null,
                    'currency' => $requestDto->currency ?? null,
                    'amount' => $requestDto->amount ?? null,
                    'userid' => $requestDto->userid ?? null,
                    'i_gameid' => $requestDto->i_gameid ?? null,
                    'i_actionid' => $requestDto->i_actionid ?? null,
                    'request_data' => $request->toArray(),
                    'response_data' => null,
                ]);
            }
        }

        if (
            is_null($balance)
            && $repeat === false
            && in_array($requestDto->type, [
                'balance',
                'debit',
                'credit',
            ])
        ) {
            $balance = $this->getBalance(
                $requestDto,
                $credentialRepository,
                $databaseService,
                $gameLogRepository,
                $gameHistoryRepository,
            );
        }

        $responseDto = match ($requestDto->type) {
            'ping' => new ResponseParamsDTO(),
            'balance' => new ResponseParamsDTO(balance: $balance),
            'credit', 'debit' => new ResponseParamsDTO(balance: $balance, tid: $requestDto->tid),
        };

        $responseDto = $responseDto->clone(
            hmac: $this->responder->makeHmac($responseDto),
        );

        $webhookRepository->update($webhook, [
            'response_data' => $responseDto->toArray(),
        ]);

        return match ($requestDto->type) {
            'ping' => new PingResponse($responseDto),
            'balance' => new BalanceResponse($responseDto),
            'credit' => new CreditResponse($responseDto),
            'debit' => new DebitResponse($responseDto),
        };
    }

    /**
     * @throws UnknownProperties
     */
    protected function handleExceptions(Throwable $e): WebhookResponse
    {
        $extra = null;

        if ($e instanceof ExceptionInterface && $e->getPrevious()) {
            $e = $e->getPrevious();
        }

        if ($e instanceof IncorrectHMACException) {
            $extra = $e->getExtra();
        }

        $responseDto = new ResponseParamsDTO(error: $e->getMessage());

        $responseDto = $responseDto->except('status')->clone(
            hmac: $this->responder->makeHmac($responseDto->except('status')),
            extra: $extra,
        );

        return new ErrorResponse($responseDto);
    }
}
