<?php

namespace App\Services\BGaming;

use App\Services\BGaming\DTO\RequestDto;
use App\Services\BGaming\Responses\LaunchOptionsResponse;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class BGamingService
{
    public function __construct(
        protected SenderInterface $sender,
        protected string $casinoId,
        protected string $returnUrl,
        protected string $depositUrl,
    ) {
    }

    protected function sender(): Sender
    {
        return $this->sender;
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     * @throws UnknownProperties
     */
    public function createSession(
        string $game,
        string $currency,
        string $locale,
        string $userIp,

        string $userId,
        string $externalId,
        string $email,
        string $firstname,
        string $lastname,
        string $nickname,
        string $city,
        string $dateOfBirth,
        string $registeredAt,
        string $gender,
        string $country,

        bool $isMobile = false,
        ?int $balance = null,
    ): LaunchOptionsResponse {
        return new LaunchOptionsResponse(
            $this->sender()->send(new RequestDto(
                url: 'sessions',
                params: [
                    'params' => [
                        'game' => $game,
                        'currency' => $currency,
                        'locale' => $locale,
                        'ip' => $userIp,
                        'clientType' => $isMobile ? 'mobile' : 'desktop',
                        'balance' => $balance,
                    ],
                    'user' => [
                        'userId' => $userId,
                        'externalId' => $externalId,
                        'email' => $email,
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'nickname' => $nickname,
                        'city' => $city,
                        'dateOfBirth' => $dateOfBirth,
                        'registeredAt' => $registeredAt,
                        'gender' => $gender,
                        'country' => $country,
                    ],
                    'urls' => [
                        'returnUrl' => $this->returnUrl,
                        'depositUrl' => $this->depositUrl,
                    ],
                    'casinoId' => $this->casinoId,
                ],
            ))
        );
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     * @throws UnknownProperties
     */
    public function demo(
        string $game,
        string $locale,
        string $userIp,
        bool $isMobile = false,
    ): LaunchOptionsResponse {
        return new LaunchOptionsResponse(
            $this->sender()->send(new RequestDto(
                url: 'demo',
                params: [
                    'params' => [
                        'game' => $game,
                        'locale' => $locale,
                        'ip' => $userIp,
                        'clientType' => $isMobile ? 'mobile' : 'desktop',
                    ],
                    'urls' => [
                        'returnUrl' => $this->returnUrl,
                        'depositUrl' => $this->depositUrl,
                    ],
                    'casinoId' => $this->casinoId,
                ],
            ))
        );
    }
}
