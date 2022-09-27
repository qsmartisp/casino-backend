<?php

namespace App\Services\Fundist;

use App\Services\Fundist\DTO\RequestParamsDTO;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class Sender implements SenderInterface
{
    public function __construct(
        protected ClientInterface $client,
        protected LoggerInterface $logger,
        protected string $key,
        protected string $pwd,
        protected string $ip,
    ) {
    }

    /**
     * @param string $apiUrl
     * @param array $params
     *
     * @return ResponseInterface|null
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public function send(string $apiUrl, array $params = []): null|ResponseInterface
    {
        $dto = new RequestParamsDTO(
            params: $params,
            apiUrl: $apiUrl,
            tid: $this->makeTID(),
        );

        $hash = $this->makeHash($dto);
        $url = $this->makeUrl($dto, $hash);

        try {
            return $this->client->request('post', $url);
        } catch (BadResponseException $exception) {
            abort(
                $exception->getResponse()->getStatusCode(),
                $exception->getResponse()->getBody()->getContents(),
            );

            return null;
        }
    }

    protected function makeTID(): string
    {
        return substr(str_shuffle(MD5(microtime())), 0, 10);
    }

    protected function makeHash(RequestParamsDTO $dto): string
    {
        $preHash = "$dto->apiUrl/{$this->ip}/{$dto->tid}/{$this->key}";

        if ($dto->system && $dto->apiUrl !== 'User/AuthHTML') {
            $preHash .= "/{$dto->system}";
        }

        if ($dto->amount) {
            $preHash .= "/{$dto->amount}";
        }

        if ($dto->login) {
            $preHash .= "/{$dto->login}";
        }

        if ($dto->password) {
            $preHash .= "/{$dto->password}";
        }

        if ($dto->currency) {
            $preHash .= "/{$dto->currency}";
        }

        if ($dto->gameId) {
            $preHash .= "/{$dto->gameId}";
        }

        if ($dto->system && $dto->apiUrl === 'User/AuthHTML') {
            $preHash .= "/{$dto->system}";
        }

        $preHash .= "/{$this->pwd}";

        $this->logger->debug('[Fundist::PreHashString]' . PHP_EOL, [
            'preHash' => $preHash,
        ]);

        return md5($preHash);
    }

    protected function makeUrl(RequestParamsDTO $dto, string $hash): string
    {
        $url = "System/Api/{$this->key}/$dto->apiUrl/?";

        if ($dto->login) {
            $url .= "&Login={$dto->login}";
        }

        if ($dto->password) {
            $url .= "&Password={$dto->password}";
        }

        if ($dto->currency) {
            $url .= "&Currency={$dto->currency}";
        }

        if ($dto->language) {
            $url .= "&Language={$dto->language}";
        }

        if ($dto->regIp) {
            $url .= "&RegistrationIP={$dto->regIp}";
        }

        if ($dto->gender) {
            $url .= "&Gender={$dto->gender}";
        }

        if ($dto->country) {
            $url .= "&Country={$dto->country}";
        }

        if ($dto->dateOfBirth) {
            $url .= "&DateOfBirth={$dto->dateOfBirth}";
        }

        if ($dto->timezone) {
            $url .= "&Timezone={$dto->timezone}";
        }

        if ($dto->name) {
            $url .= "&Name={$dto->name}";
        }

        if ($dto->lastName) {
            $url .= "&LastName={$dto->lastName}";
        }

        if ($dto->phone) {
            $url .= "&Phone={$dto->phone}";
        }

        if ($dto->alternativePhone) {
            $url .= "&AlternativePhone={$dto->alternativePhone}";
        }

        if ($dto->city) {
            $url .= "&City={$dto->city}";
        }

        if ($dto->address) {
            $url .= "&Address={$dto->address}";
        }

        if ($dto->email) {
            $url .= "&Email={$dto->email}";
        }

        if ($dto->affiliateId) {
            $url .= "&AffiliateID={$dto->affiliateId}";
        }

        if ($dto->nick) {
            $url .= "&Nick={$dto->nick}";
        }

        if ($dto->system) {
            $url .= "&System={$dto->system}";
        }

        if ($dto->amount) {
            $url .= "&Amount={$dto->amount}";
        }

        if ($dto->userIp) {
            $url .= "&UserIP={$dto->userIp}";
        }

        if ($dto->demo) {
            $url .= "&Demo={$dto->demo}";
        }

        if ($dto->gameExtra) {
            $url .= "&ExtParam={$dto->gameExtra}";
        }

        if ($dto->page) {
            $url .= "&Page={$dto->page}";
        }

        if ($dto->gameId) {
            $url .= "&GameID={$dto->gameId}";
        }

        $url .= "&TID={$dto->tid}&Hash={$hash}";

        return $url;
    }
}
