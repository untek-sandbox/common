<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Model\Cqrs\Infrastructure\Services\CommandBus;
use Untek\Model\Cqrs\Infrastructure\Services\CommandBusConfigurator;
use Untek\Model\Cqrs\Application\Services\CommandBusConfiguratorInterface;
use Untek\Model\Cqrs\Application\Services\CommandBusInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(CommandBusConfiguratorInterface::class, CommandBusConfigurator::class);

    $services->set(CommandBusInterface::class, CommandBus::class)
        ->args([
            service(ContainerInterface::class),
            service(CommandBusConfiguratorInterface::class),
        ]);
};