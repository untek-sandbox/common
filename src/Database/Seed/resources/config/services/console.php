<?php

use Doctrine\DBAL\Connection;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Database\Base\Domain\Libs\Dependency;
use Untek\Database\Base\Domain\Repositories\Eloquent\SchemaRepository;
use Untek\Database\Eloquent\Domain\Capsule\Manager;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use Untek\Database\Seed\Presentation\Cli\Commands\ImportSeedCliCommand;
use Untek\Model\Cqrs\Application\Services\CommandBusInterface;
use Untek\Database\Seed\Application\Handlers\GetTablesQueryHandler;
use Untek\Database\Seed\Presentation\Cli\Commands\ExportSeedCliCommand;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();
    
    /*$services->set(ImportSeedCliCommand::class, ImportSeedCliCommand::class)
        ->args([
            service(CommandBusInterface::class),
        ])
        ->tag('console.command');

    $services->set(ExportSeedCliCommand::class, ExportSeedCliCommand::class)
        ->args([
            service(CommandBusInterface::class),
        ])
        ->tag('console.command');*/
};