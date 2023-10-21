<?php

use Untek\Model\Cqrs\CommandBusInterface;
use Untek\Utility\CodeGenerator\Application\Handlers\GenerateApplicationCommandHandler;
use Untek\Utility\CodeGenerator\Application\Handlers\GenerateRestApiCommandHandler;
use Untek\Utility\CodeGenerator\Presentation\Cli\Commands\GenerateApplicationCliCommand;
use Untek\Utility\CodeGenerator\Presentation\Cli\Commands\GenerateRestApiCliCommand;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Utility\CodeGenerator\Presentation\Cli\Commands\GenerateCliCommand;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(GenerateApplicationCommandHandler::class, GenerateApplicationCommandHandler::class);
    /*$services->set(GenerateCliCommand::class, GenerateCliCommand::class)
        ->args([
            service(CommandBusInterface::class),
            'code-generator:generate-application',
        ]);*/


    $services->set(GenerateRestApiCommandHandler::class, GenerateRestApiCommandHandler::class);
    $services->set(GenerateRestApiCliCommand::class, GenerateRestApiCliCommand::class)
        ->args([
            service(CommandBusInterface::class),
            'code-generator:generate-rest-api',
        ]);
};