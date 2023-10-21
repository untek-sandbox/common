<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Utility\CodeGeneratorApplication\Application\Handlers\GenerateApplicationCommandHandler;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(GenerateApplicationCommandHandler::class, GenerateApplicationCommandHandler::class);
};