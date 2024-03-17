<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\FrameworkPlugin\HttpErrorHandle\Presentation\Http\Site\Controllers\HttpErrorController;
use Untek\FrameworkPlugin\HttpErrorHandle\Infrastructure\Subscribers\HttpHandleSubscriber;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();

    $services->set(HttpErrorController::class, HttpErrorController::class)
        ->args(
            [
                service(LoggerInterface::class)
            ]
        );
    $services->set(HttpHandleSubscriber::class, HttpHandleSubscriber::class)
        ->args(
            [
                service(ContainerInterface::class),
            ]
        )
        ->call('setRestApiErrorControllerClass', [HttpErrorController::class])
        ->tag('kernel.event_subscriber');
};