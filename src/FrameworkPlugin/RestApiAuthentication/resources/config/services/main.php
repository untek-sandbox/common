<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\FrameworkPlugin\RestApiAuthentication\Infrastructure\Subscribers\RestApiAuthenticationSubscriber;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autowire()->autoconfigure();

    $services->set(RestApiAuthenticationSubscriber::class);
};