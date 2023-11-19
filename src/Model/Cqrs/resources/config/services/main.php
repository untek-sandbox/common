<?php

use Forecast\Map\ModuleExample\RestApi\Application\Services\FooService;
use Forecast\Map\ModuleExample\RestApi\Domain\Interfaces\Services\FooServiceInterface;
use Forecast\Map\ModuleExample\RestApi\Presentation\Http\RestApi\Controllers\AuthenticationController;
use Forecast\Map\ModuleExample\RestApi\Presentation\Http\RestApi\Controllers\AuthorizationController;
use Forecast\Map\ModuleExample\RestApi\Presentation\Http\RestApi\Controllers\FooController;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Component\App\Services\ControllerAccessChecker;
use Untek\Model\Cqrs\CommandBus;
use Untek\Model\Cqrs\CommandBusConfigurator;
use Untek\Model\Cqrs\CommandBusConfiguratorInterface;
use Untek\Model\Cqrs\CommandBusInterface;
use Psr\Container\ContainerInterface;
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