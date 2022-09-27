<?php

namespace App\Services\BGaming\DTO;

use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\DataTransferObject;

class UserDto extends DataTransferObject
{
    public string $id;

    #[MapTo('external_id')]
    public ?string $externalId; // todo

    public string $email;

    public string $firstname;

    public string $lastname;

    public string $nickname;

    public string $city;

    #[MapTo('date_of_birth')]
    public string $dateOfBirth;

    #[MapTo('registered_at')]
    public string $registeredAt;

    public string $gender;

    public string $country;
}
