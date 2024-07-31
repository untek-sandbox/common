<?php

use Doctrine\DBAL\Connection;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Database\Base\Domain\Libs\Dependency;
use Untek\Database\Base\Domain\Repositories\Eloquent\SchemaRepository;
use Untek\Database\Eloquent\Domain\Capsule\Manager;
use Untek\Database\Seed\Application\Handlers\GetTablesQueryHandler;
use Untek\Database\Seed\Application\Handlers\ImportSeedCommandHandler;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();

    $services->set(Dependency::class, Dependency::class)
        ->args([
            service(SchemaRepository::class),
        ]);

//    $seedDirectory = getenv('SEED_DIRECTORY');
//    $seedDirectory = '/app/tests/fixtures/seeds';
    $seedDirectory = getenv('SEED_DIRECTORY') ?: $_SERVER['SEED_DIRECTORY'];
    
    $services->set(ImportSeedCommandHandler::class, ImportSeedCommandHandler::class)
        ->args([
            service(Dependency::class),
            service(Connection::class),
            $seedDirectory,
        ])
        ->tag('cqrs.handler');
    
    $services->set(GetTablesQueryHandler::class, GetTablesQueryHandler::class)
        ->args([
            service(Connection::class),
            [
                'eq_migration',
            ],
        ])
        ->tag('cqrs.handler');

    $services->set(\Untek\Database\Seed\Application\Handlers\ExportSeedCommandHandler::class, \Untek\Database\Seed\Application\Handlers\ExportSeedCommandHandler::class)
        ->args([
            service(Manager::class),
            $seedDirectory,
        ])
        ->tag('cqrs.handler');
};