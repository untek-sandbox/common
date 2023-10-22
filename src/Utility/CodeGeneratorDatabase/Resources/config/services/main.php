<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Utility\CodeGeneratorDatabase\Application\Handlers\GenerateDatabaseCommandHandler;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(GenerateDatabaseCommandHandler::class, GenerateDatabaseCommandHandler::class);
};