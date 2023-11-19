<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Untek\FrameworkPlugin\RestApiAuthentication\Infrastructure\Subscribers\RestApiAuthenticationSubscriber;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(RestApiAuthenticationSubscriber::class, RestApiAuthenticationSubscriber::class)
        ->args(
            [
                service(UserProviderInterface::class),
                service(TokenStorageInterface::class),
                service(AuthorizationCheckerInterface::class),
                'Authorization-Token',
            ]
        );
};