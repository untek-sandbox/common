<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Component\Cqrs\Application\Services\CommandBusConfiguratorInterface;
use Untek\Component\Cqrs\Application\Services\CommandBusInterface;
use Untek\Component\Cqrs\Infrastructure\Services\CommandBus;
use Untek\Component\Cqrs\Infrastructure\Services\CommandBusConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();

    $services->set(CommandBusConfiguratorInterface::class, CommandBusConfigurator::class);

    $services->set(CommandBusInterface::class, CommandBus::class)
        ->args([
            service(ContainerInterface::class),
            service(CommandBusConfiguratorInterface::class),
        ]);
};