<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Utility\CodeGeneratorRestApi\Application\Handlers\GenerateRestApiCommandHandler;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(GenerateRestApiCommandHandler::class, GenerateRestApiCommandHandler::class)
        ->args([
            service(GenerateResultCollection::class)
        ]);
};