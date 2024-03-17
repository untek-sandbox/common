<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Untek\FrameworkPlugin\RestApiAuthentication\Infrastructure\Subscribers\RestApiAuthenticationSubscriber;
use Untek\User\Authentication\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();

    $services->set(RestApiAuthenticationSubscriber::class, RestApiAuthenticationSubscriber::class)
        ->args(
            [
                service(IdentityRepositoryInterface::class),
                service(TokenServiceInterface::class),
//                service(UserProviderInterface::class),
                service(TokenStorageInterface::class),
//                service(AuthorizationCheckerInterface::class),
//                'Authorization-Token',
            ]
        )
        ->tag('kernel.event_subscriber');
};