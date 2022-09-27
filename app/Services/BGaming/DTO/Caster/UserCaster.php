<?php

namespace App\Services\BGaming\DTO\Caster;

use App\Services\BGaming\DTO\UserDto;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UserCaster implements Caster
{
    /**
     * @param array|mixed $value
     *
     * @return mixed
     *
     * @throws UnknownProperties
     */
    public function cast(mixed $value): UserDto
    {
        return new UserDto(
            id: $value['userId'] ?? '',
            firstname: $value['firstname'] ?? '',
            lastname: $value['lastname'] ?? '',
            email: $value['email'] ?? '',
            externalId: $value['externalId'] ?? '',
            nickname: $value['nickname'] ?? '',
            city: $value['city'] ?? '',
            dateOfBirth: $value['dateOfBirth'] ?? '',
            registeredAt: $value['registeredAt'] ?? '',
            gender: $value['gender'] ?? '',
            country: $value['country'] ?? '',
        );
    }
}
