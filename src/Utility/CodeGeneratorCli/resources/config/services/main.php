<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(\Untek\Utility\CodeGeneratorCli\Application\Handlers\GenerateCliCommandHandler::class, \Untek\Utility\CodeGeneratorCli\Application\Handlers\GenerateCliCommandHandler::class);
};
