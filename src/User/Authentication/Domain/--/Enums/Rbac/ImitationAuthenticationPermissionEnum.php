<?php

namespace Untek\User\Authentication\Domain\Enums\Rbac;

use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Enum\Interfaces\GetLabelsInterface;

DeprecateHelper::hardThrow();

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
