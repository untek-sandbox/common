<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Untek\FrameworkPlugin\HttpAuthentication\Application\Services\WebAuthentication;
use Untek\FrameworkPlugin\HttpAuthentication\Infrastructure\Subscribers\WebAuthenticationSubscriber;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();

    $services->set(WebAuthenticationSubscriber::class, WebAuthenticationSubscriber::class)
        ->args(
            [
                service(WebAuthentication::class),
                service(UserProviderInterface::class),
                service(TokenStorageInterface::class),
                service(AuthorizationCheckerInterface::class),
            ]
        );
};