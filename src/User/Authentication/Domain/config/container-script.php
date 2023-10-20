<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\Security\Core\User\ChainUserProvider;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Untek\User\Authentication\Domain\Enums\CredentialTypeEnum;
use Untek\User\Authentication\Domain\Interfaces\Services\AuthServiceInterface;
use Untek\User\Authentication\Domain\Services\AuthService;
use Untek\User\Authentication\Domain\UserProviders\ApiTokenUserProvider;
use Untek\User\Authentication\Domain\UserProviders\CredentialsUserProvider;

return [
    'singletons' => [
//        UserProviderInterface::class => \Untek\User\Authentication\Domain\UserProviders\ApiTokenUserProvider::class,
        UserProviderInterface::class => function (ContainerInterface $container) {
            /** @var CredentialsUserProvider $credentialsUserProvider */
            $credentialsUserProvider = $container->get(CredentialsUserProvider::class);
            $credentialsUserProvider->setTypes(
                [
                    CredentialTypeEnum::LOGIN,
                    CredentialTypeEnum::EMAIL,
                    CredentialTypeEnum::PHONE
                ]
            );
            $providers = [];
            $providers[] = $container->get(ApiTokenUserProvider::class);
            $providers[] = $credentialsUserProvider;

            return new ChainUserProvider($providers);
        },

        AuthServiceInterface::class => function (ContainerInterface $container) {
            /** @var AuthService $authService */
            $authService = $container->get(AuthService::class);
//            $authService->addSubscriber(SymfonyAuthenticationIdentitySubscriber::class);
            /*$authService->addSubscriber(
                [
                    'class' => \Untek\User\Authentication\Domain\Subscribers\AuthenticationAttemptSubscriber::class,
                    'action' => 'authorization',
                    // todo: вынести в настройки
                    'attemptCount' => 3,
                    'lifeTime' => 10,
//                'lifeTime' => TimeEnum::SECOND_PER_MINUTE * 30,
                ]
            );*/
            return $authService;
        },
    ],
];
