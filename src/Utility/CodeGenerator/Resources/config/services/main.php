<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Utility\CodeGeneratorApplication\Application\Handlers\GenerateApplicationCommandHandler;
use Untek\Utility\CodeGeneratorRestApi\Application\Handlers\GenerateRestApiCommandHandler;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(GenerateApplicationCommandHandler::class, GenerateApplicationCommandHandler::class);
    $services->set(GenerateRestApiCommandHandler::class, GenerateRestApiCommandHandler::class);
};