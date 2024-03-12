<?php

use Untek\User\Authentication\Domain\Enums\Rbac\AuthenticationPermissionEnum;
use Untek\User\Authentication\Domain\Enums\Rbac\ImitationAuthenticationPermissionEnum;
use Untek\User\Rbac\Domain\Enums\Rbac\SystemRoleEnum;
use Untek\User\Authentication\Domain\Enums\Rbac\AuthenticationIdentityPermissionEnum;

//return [
//    'roleEnums' => [
//
//    ],
//    'permissionEnums' => [
//        AuthenticationPermissionEnum::class,
//        ImitationAuthenticationPermissionEnum::class,
//    ],
//    'inheritance' => [
//        SystemRoleEnum::GUEST => [
//            AuthenticationPermissionEnum::AUTHENTICATION_WEB_LOGIN,
//            AuthenticationPermissionEnum::AUTHENTICATION_GET_TOKEN_BY_PASSWORD,
//            AuthenticationIdentityPermissionEnum::GET_MY_IDENTITY,
//        ],
//        SystemRoleEnum::USER => [
//            AuthenticationPermissionEnum::AUTHENTICATION_WEB_LOGOUT,
//        ],
//        SystemRoleEnum::ADMINISTRATOR => [
//            ImitationAuthenticationPermissionEnum::IMITATION,
//        ],
//    ],
//];
