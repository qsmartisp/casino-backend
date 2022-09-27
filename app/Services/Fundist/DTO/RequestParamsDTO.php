<?php

namespace App\Services\Fundist\DTO;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

class RequestParamsDTO extends DataTransferObject
{
    public string $tid;

    public string $apiUrl;

    #[MapFrom('params.system')]
    public ?int $system;

    #[MapFrom('params.login')]
    public ?string $login;

    #[MapFrom('params.password')]
    public ?string $password;

    #[MapFrom('params.currency')]
    public ?string $currency;

    #[MapFrom('params.language')]
    public ?string $language;

    #[MapFrom('params.reg_ip')]
    public ?string $regIp;

    #[MapFrom('params.gender')]
    public ?string $gender;

    #[MapFrom('params.country')]
    public ?string $country;

    #[MapFrom('params.date_of_birth')]
    public ?string $dateOfBirth;

    #[MapFrom('params.nick')]
    public ?string $nick;

    #[MapFrom('params.timezone')]
    public ?string $timezone;

    #[MapFrom('params.name')]
    public ?string $name;

    #[MapFrom('params.last_name')]
    public ?string $lastName;

    #[MapFrom('params.phone')]
    public ?string $phone;

    #[MapFrom('params.alternative_phone')]
    public ?string $alternativePhone;

    #[MapFrom('params.city')]
    public ?string $city;

    #[MapFrom('params.address')]
    public ?string $address;

    #[MapFrom('params.email')]
    public ?string $email;

    #[MapFrom('params.affiliate_id')]
    public ?int $affiliateId;

    #[MapFrom('params.amount')]
    public ?float $amount;

    #[MapFrom('params.page')]
    public ?string $page;

    #[MapFrom('params.userIp')]
    public ?string $userIp;

    #[MapFrom('params.gameExtra')]
    public ?string $gameExtra;

    #[MapFrom('params.userAutoCreate')]
    public ?bool $userAutoCreate;

    #[MapFrom('params.universalLaunch')]
    public ?bool $universalLaunch;

    #[MapFrom('params.demo')]
    public ?bool $demo;

    #[MapFrom('params.isMobile')]
    public ?bool $isMobile;

    #[MapFrom('params.gameId')]
    public ?string $gameId;
}
