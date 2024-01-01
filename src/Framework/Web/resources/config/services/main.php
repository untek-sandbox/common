<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Component\App\Services\ControllerAccessChecker;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();
    $parameters = $configurator->parameters();

    $services->set(ControllerAccessChecker::class, ControllerAccessChecker::class)
        ->args([
            service(ContainerInterface::class),
        ]);
};