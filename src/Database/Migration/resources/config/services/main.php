<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Database\Base\Domain\Repositories\Eloquent\SchemaRepository;
use Untek\Database\Eloquent\Domain\Capsule\Manager;
use Untek\Database\Migration\Infrastructure\Persistence\Eloquent\Repository\HistoryRepository;
use Untek\Database\Migration\Infrastructure\Persistence\FileSystem\Repository\SourceRepository;
use Untek\Database\Migration\Application\Services\MigrationService;
use Untek\Database\Migration\Presentation\Cli\Commands\DownCommand;
use Untek\Database\Migration\Presentation\Cli\Commands\UpCommand;
use Untek\Model\EntityManager\Interfaces\EntityManagerConfiguratorInterface;
use Untek\Model\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Model\EntityManager\Libs\EntityManager;
use Untek\Model\EntityManager\Libs\EntityManagerConfigurator;
use Doctrine\DBAL\Connection;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(DownCommand::class, DownCommand::class)
        ->args([
            service(MigrationService::class)
        ]);


    $services->set(UpCommand::class, UpCommand::class)
        ->args([
            service(MigrationService::class)
        ]);

    $services->set(SchemaRepository::class, SchemaRepository::class)
        ->args([
            service(Connection::class)
        ]);

    $services->set(SourceRepository::class, SourceRepository::class);

    $services->set(EntityManagerConfiguratorInterface::class, EntityManagerConfigurator::class);

    $services->set(EntityManagerInterface::class, EntityManager::class)
        ->args([
            service(ContainerInterface::class),
            service(EntityManagerConfiguratorInterface::class),
        ]);


    $services->set(HistoryRepository::class, HistoryRepository::class)
        ->args([
            service(EntityManagerInterface::class),
            service(Manager::class),
            service(ContainerInterface::class),
        ]);

    $services->set(MigrationService::class, MigrationService::class)
        ->args([
            service(SourceRepository::class),
            service(HistoryRepository::class),
        ]);
};