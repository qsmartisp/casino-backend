<?php

namespace App\Services\Fundist;

use App\Services\Fundist\Responses\AddUserResponse;
use App\Services\Fundist\Responses\AvailableGamesResponse;
use App\Services\Fundist\Responses\GameCategoriesResponse;
use App\Services\Fundist\Responses\GameDetailsResponse;
use App\Services\Fundist\Responses\GameFullListResponse;
use App\Services\Fundist\Responses\GetBalanceResponse;
use App\Services\Fundist\Responses\SetBalanceResponse;
use App\Services\Fundist\Responses\UserAuthHTMLResponse;
use App\Services\Fundist\Responses\WithdrawBalanceResponse;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class FundistService
{
    public function __construct(
        protected SenderInterface $sender,
    ) {
    }

    protected function sender(): Sender
    {
        return $this->sender;
    }

    /**
     * @return AvailableGamesResponse
     *
     * @throws GuzzleException
     * @throws JsonException
     * @throws UnknownProperties
     */
    public function getAvailableGames(): AvailableGamesResponse
    {
        return new AvailableGamesResponse(
            $this->sender()->send('Game/List')
        );
    }

    /**
     * @return GameCategoriesResponse
     *
     * @throws GuzzleException
     * @throws JsonException
     * @throws UnknownProperties
     */
    public function getGameCategories(): GameCategoriesResponse
    {
        return new GameCategoriesResponse(
            $this->sender()->send('Game/Categories')
        );
    }

    /**
     * @return GameFullListResponse
     *
     * @throws GuzzleException
     * @throws JsonException
     * @throws UnknownProperties
     */
    public function getGameFullList(): GameFullListResponse
    {
        return new GameFullListResponse(
            $this->sender()->send('Game/FullList')
        );
    }

    /**
     * @param int $systemId
     * @param string $login
     *
     * @return GetBalanceResponse
     *
     * @throws GuzzleException
     * @throws JsonException
     * @throws UnknownProperties
     */
    public function getBalance(int $systemId, string $login): GetBalanceResponse
    {
        return new GetBalanceResponse(
            $this->sender()->send(
                apiUrl: 'Balance/Get',
                params: [
                    'login' => $login,
                    'system' => $systemId,
                ],
            )
        );
    }

    /**
     * @param int $gameId
     *
     * @return GameDetailsResponse
     *
     * @throws GuzzleException
     * @throws JsonException
     * @throws UnknownProperties
     */
    public function getGameDetails(int $gameId): GameDetailsResponse
    {
        return new GameDetailsResponse(
            $this->sender()->send(
                apiUrl: 'Stats/GameDetails',
                params: [
                    'gameId' => $gameId,
                ],
            )
        );
    }

    /**
     * @param int $systemId
     * @param string $login
     *
     * @return WithdrawBalanceResponse
     *
     * @throws GuzzleException
     * @throws JsonException
     * @throws UnknownProperties
     */
    public function withdrawBalance(int $systemId, string $login): WithdrawBalanceResponse
    {
        return new WithdrawBalanceResponse(
            $this->sender()->send(
                apiUrl: 'Balance/Withdraw',
                params: [
                    'login' => $login,
                    'system' => $systemId,
                ],
            )
        );
    }

    /**
     * @param int $systemId
     * @param string $login
     * @param float $amount
     * @param string $currency
     *
     * @return SetBalanceResponse
     *
     * @throws GuzzleException
     * @throws JsonException
     * @throws UnknownProperties
     */
    public function setBalance(
        int $systemId,
        string $login,
        float $amount,
        string $currency,
    ): SetBalanceResponse {
        return new SetBalanceResponse(
            $this->sender()->send(
                apiUrl: 'Balance/Set',
                params: [
                    'login' => $login,
                    'system' => $systemId,
                    'amount' => $amount,
                    'currency' => $currency,
                ],
            )
        );
    }

    /**
     * @param string $login
     * @param string $password
     * @param string $currency
     * @param string $language
     * @param string $reg_ip
     * @param string|null $gender
     * @param string|null $country
     * @param string|null $birthday
     * @param string|null $nick
     * @param string|null $timezone
     * @param string|null $name
     * @param string|null $lastName
     * @param string|null $phone
     * @param string|null $alternativePhone
     * @param string|null $city
     * @param string|null $address
     * @param string|null $email
     * @param string|null $affiliateId
     *
     * @return AddUserResponse
     *
     * @throws GuzzleException
     * @throws JsonException
     * @throws UnknownProperties
     */
    public function addUser(
        string $login,
        string $password,
        string $currency,
        string $language,
        string $reg_ip,
        ?string $gender = null,
        ?string $country = null,
        ?string $birthday = null,
        ?string $nick = null,
        ?string $timezone = null,
        ?string $name = null,
        ?string $lastName = null,
        ?string $phone = null,
        ?string $alternativePhone = null,
        ?string $city = null,
        ?string $address = null,
        ?string $email = null,
        ?string $affiliateId = null,
    ): AddUserResponse {
        return new AddUserResponse(
            $this->sender()->send(
                apiUrl: 'User/Add',
                params: [
                    'login' => $login,
                    'password' => $password,
                    'currency' => $currency,
                    'language' => $language,
                    'reg_ip' => $reg_ip,
                    'gender' => $gender,
                    'country' => $country,
                    'date_of_birth' => $birthday,
                    'nick' => $nick,
                    'timezone' => $timezone,
                    'name' => $name,
                    'last_name' => $lastName,
                    'phone' => $phone,
                    'alternative_phone' => $alternativePhone,
                    'city' => $city,
                    'address' => $address,
                    'email' => $email,
                    'affiliate_id' => $affiliateId,
                ],
            )
        );
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     * @throws UnknownProperties
     */
    public function runGame(
        int $systemId,
        string $login,
        string $password,
        string $page,
        string $userIp,
        ?bool $demo,
        ?string $gameExtra = null,
        ?string $country = null,
        ?string $currency = null,
        ?string $language = null,
        ?string $nick = null,
        ?string $timezone = null,
        ?bool $userAutoCreate = null,
        ?bool $universalLaunch = null,
        ?bool $isMobile = null,
    ): UserAuthHTMLResponse {
        return new UserAuthHTMLResponse(
            $this->sender()->send(
                apiUrl: 'User/AuthHTML',
                params: [
                    'login' => $login,
                    'password' => $password,
                    'system' => $systemId,
                    'page' => $page,
                    'userIp' => $userIp,
                    'demo' => $demo,
                    'gameExtra' => $gameExtra,
                    'country' => $country,
                    'currency' => $currency,
                    'language' => $language,
                    'nick' => $nick,
                    'timezone' => $timezone,
                    'userAutoCreate' => $userAutoCreate,
                    'universalLaunch' => $universalLaunch,
                    'isMobile' => $isMobile,
                ],
            )
        );
    }
}
