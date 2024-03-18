<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Model\Cqrs\Application\Services\CommandBusInterface;
use Untek\Utility\CodeGenerator\Presentation\Cli\Commands\GenerateCodeCommand;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();

    $services->set(GenerateResultCollection::class, GenerateResultCollection::class);

    $services->set(GenerateCodeCommand::class, GenerateCodeCommand::class)
    ->args([
        'code-generator:generate',
        service(CommandBusInterface::class),
        [],
    ])
        ->tag('console.command');
};