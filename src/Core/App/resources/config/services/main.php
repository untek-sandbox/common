<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Core\App\DependencyInjection\ContainerFactory;
use Untek\Core\App\Services\ControllerAccessChecker;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autowire();

    $services->set(ControllerAccessChecker::class);
    $services
        ->set(ContainerInterface::class)
        ->factory([ContainerFactory::class, 'get']);
};