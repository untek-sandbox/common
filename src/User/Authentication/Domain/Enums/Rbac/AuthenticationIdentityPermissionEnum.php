<?php

namespace Untek\User\Authentication\Domain\Enums\Rbac;

use Untek\Core\Enum\Interfaces\GetLabelsInterface;

class AuthenticationIdentityPermissionEnum implements GetLabelsInterface
{

    const GET_MY_IDENTITY = 'oAuthenticationGetMyIdentity';

    public static function getLabels()
    {
        return [
            self::GET_MY_IDENTITY => 'Получить инфо моего аккаунта',
        ];
    }
}
