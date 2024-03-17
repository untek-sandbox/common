<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Utility\CodeGeneratorApplication\Application\Handlers\GenerateApplicationCommandHandler;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();

    $services->set(GenerateApplicationCommandHandler::class, GenerateApplicationCommandHandler::class)
    ->args([
        service(GenerateResultCollection::class)
    ]);
};