<?php

use Untek\Kaz\Iin\Domain\Enums\Rbac\IinPermissionEnum;
use Untek\User\Rbac\Domain\Enums\Rbac\SystemRoleEnum;

return [
    'roleEnums' => [
        SystemRoleEnum::class,
    ],
    'permissionEnums' => [
        IinPermissionEnum::class,
    ],
    'inheritance' => [
        SystemRoleEnum::GUEST => [
            IinPermissionEnum::GET_INFO,
        ],
        SystemRoleEnum::USER => [

        ],
    ],
];
