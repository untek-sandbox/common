<?php

use Doctrine\DBAL\Connection;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Database\Base\Domain\Libs\Dependency;
use Untek\Database\Base\Domain\Repositories\Eloquent\SchemaRepository;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use Untek\Database\Seed\Presentation\Cli\Commands\ImportSeedCliCommand;
use Untek\Database\Seed\Application\Handlers\ImportSeedCommandHandler;
use Untek\Model\Cqrs\Application\Services\CommandBusInterface;
use Untek\Database\Seed\Application\Handlers\GetTablesQueryHandler;
use Untek\Database\Seed\Presentation\Cli\Commands\ExportSeedCliCommand;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(Dependency::class, Dependency::class)
        ->args([
            service(SchemaRepository::class),
        ]);
    
    $services->set(ImportSeedCommandHandler::class, ImportSeedCommandHandler::class)
    ->args([
        service(Dependency::class),
        service(Connection::class),
        getenv('SEED_DIRECTORY'),
//        __DIR__ . '/../../../../../../../../../resources/seeds',
    ]);
    
    $services->set(ImportSeedCliCommand::class, ImportSeedCliCommand::class)
    ->args([
        service(CommandBusInterface::class),
    ]);

    $services->set(ExportSeedCliCommand::class, ExportSeedCliCommand::class)
        ->args([
            service(CommandBusInterface::class),
        ]);

    $services->set(GetTablesQueryHandler::class, GetTablesQueryHandler::class)
    ->args([
        service(Connection::class),
        [
            'eq_migration',
        ],
    ]);

    $services->set(\Untek\Database\Seed\Application\Handlers\ExportSeedCommandHandler::class, \Untek\Database\Seed\Application\Handlers\ExportSeedCommandHandler::class);
};