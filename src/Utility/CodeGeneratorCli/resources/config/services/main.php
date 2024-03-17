<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();

    $services->set(\Untek\Utility\CodeGeneratorCli\Application\Handlers\GenerateCliCommandHandler::class, \Untek\Utility\CodeGeneratorCli\Application\Handlers\GenerateCliCommandHandler::class)
        ->args([
            service(GenerateResultCollection::class)
        ]);
};
