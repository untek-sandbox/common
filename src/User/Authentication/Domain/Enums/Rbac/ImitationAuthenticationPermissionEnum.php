<?php

namespace Untek\User\Authentication\Domain\Enums\Rbac;

use Untek\Core\Enum\Interfaces\GetLabelsInterface;

class ImitationAuthenticationPermissionEnum implements GetLabelsInterface
{

    public const IMITATION = 'oUserImitationImitation';

    public static function getLabels()
    {
        return [
            self::IMITATION => 'Пользователь. Имитация аутентификации',
        ];
    }
}
