<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Untek\Component\Web\TwBootstrap\Widgets\UserNavbarMenu\UserNavbarMenuWidget;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(UserNavbarMenuWidget::class, UserNavbarMenuWidget::class)
        ->args(
            [
                service(TokenStorageInterface::class)
            ]
        );
};