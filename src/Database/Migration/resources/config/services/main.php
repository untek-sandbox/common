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
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autowire()->autoconfigure();

    $services
        ->load('Untek\Database\Migration\\', __DIR__ . '/../../..')
        ->exclude([
            __DIR__ . '/../../../{resources,Domain,Application/Commands,Application/Queries,Application/Validators}',
            __DIR__ . '/../../../**/*{Event.php,Helper.php,Message.php,Task.php,Relation.php,Schema.php,Normalizer.php}',
            __DIR__ . '/../../../**/{Dto,Enums}',
            __DIR__ . '/../../../**/Persistence/Memory/Repository/*',
        ]);

    $services->set(SchemaRepository::class);

    $services->set(SourceRepository::class)
    ->args([
        param('database.migration.config_path')
    ]);

    /*$services->set(HistoryRepository::class, HistoryRepository::class)
        ->args([
            service(EntityManagerInterface::class),
            service(Manager::class),
            service(ContainerInterface::class),
        ]);

    $services->set(MigrationService::class, MigrationService::class)
        ->args([
            service(SourceRepository::class),
            service(HistoryRepository::class),
        ]);*/
};