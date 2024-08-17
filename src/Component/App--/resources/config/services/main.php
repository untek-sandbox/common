<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Component\App\Services\ControllerAccessChecker;
use Untek\Core\App\Bootstrap\ContainerFactory;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autowire();

    $services->set(ControllerAccessChecker::class);
    $services
        ->set(ContainerInterface::class)
        ->factory([ContainerFactory::class, 'create']);
};