<?php

namespace Untek\Database\Migration\Infrastructure\Persistence\Eloquent\Repository;

use Untek\Core\Container\Libs\Container;
use Illuminate\Database\Schema\Blueprint;
use Psr\Container\ContainerInterface;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Model\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Database\Eloquent\Domain\Base\BaseEloquentRepository;
use Untek\Database\Eloquent\Domain\Capsule\Manager;
use Untek\Database\Migration\Domain\Model\Migration;
use Untek\Database\Migration\Infrastructure\Migration\Interfaces\MigrationInterface;

class HistoryRepository extends BaseEloquentRepository
{

    const MIGRATION_TABLE_NAME = 'eq_migration';

    protected $tableName = self::MIGRATION_TABLE_NAME;
    protected ContainerInterface $container;

    public function getEntityClass(): string
    {
        return Migration::class;
    }

    public function __construct(
        EntityManagerInterface $em,
        Manager $capsule,
        ContainerInterface $container
    )
    {
        parent::__construct($em, $capsule);
        $this->container = $container;
    }

    public static function filterVersion(array $sourceCollection, array $historyCollection)
    {
        /**
         * @var Migration[] $historyCollection
         * @var Migration[] $sourceCollection
         */

        $sourceVersionArray = ArrayHelper::getColumn($sourceCollection, 'version');
        $historyVersionArray = ArrayHelper::getColumn($historyCollection, 'version');

        $diff = array_diff($sourceVersionArray, $historyVersionArray);

        foreach ($sourceCollection as $key => $migrationEntity) {
            if ( ! in_array($migrationEntity->version, $diff)) {
                unset($sourceCollection[$key]);
            }
        }
        return $sourceCollection;
    }

    private function insert($version, $connectionName = 'default')
    {
//        $tableAlias = $this->getCapsule()->getAlias();
//        $targetTableName = $tableAlias->encode($connectionName, self::MIGRATION_TABLE_NAME);

        $targetTableName = $this->encodeTableName(self::MIGRATION_TABLE_NAME, $connectionName);
        //$queryBuilder = $this->getQueryBuilder();
        $queryBuilder = $this->getCapsule()->getQueryBuilderByConnectionName($connectionName, $this->tableNameAlias());
        $queryBuilder->insert([
            'version' => $version,
            'executed_at' => new \DateTime(),
        ]);
    }

    private function delete($version, $connectionName = 'default')
    {
//        $tableAlias = $this->getCapsule()->getAlias();
//        $targetTableName = $tableAlias->encode($connectionName, self::MIGRATION_TABLE_NAME);

//        $targetTableName = $this->encodeTableName(self::MIGRATION_TABLE_NAME, $connectionName);
        //$queryBuilder = $this->getQueryBuilder();
        $queryBuilder = $this->getCapsule()->getQueryBuilderByConnectionName($connectionName, $this->tableNameAlias());
        $queryBuilder->where('version', $version);
        $queryBuilder->delete();
    }

    public function upMigration(string $class)
    {
        $migration = $this->createMigrationClass($class);

        $tableName = $migration->getTableName();
        $schema = $this->getCapsule()->getSchemaByTableName($tableName);

        //$connection = $migration->getConnection();
        $connection = $schema->getConnection();

        $connectionName = $connection->getConfig('name');

        // todo: begin transaction
        $connection->beginTransaction();
        $this->forgeMigrationTable($connectionName);
        $migration->up($schema);
        $version = ClassHelper::getClassOfClassName($class);
        $this->insert($version, $connection->getConfig('name'));
        $connection->commit();
        // todo: end transaction
    }

    public function downMigration(string $class)
    {
        $migration = $this->createMigrationClass($class);
        //$schema = $this->getSchema();

        $tableName = $migration->getTableName();
        $schema = $this->getCapsule()->getSchemaByTableName($tableName);

        $connection = $schema->getConnection();
        // todo: begin transaction
        $connection->beginTransaction();
        $migration->down($schema);
        $version = ClassHelper::getClassOfClassName($class);
        self::delete($version);
        $connection->commit();
        // todo: end transaction
    }

    private function createMigrationClass(string $class): MigrationInterface {
        //$migration = new $class($this->getCapsule());
        $capsule = $this->container->get(\Illuminate\Database\Capsule\Manager::class);
        return new $class($capsule);
//        return $this->container->get($class);
//        ClassHelper::isInstanceOf($migration, MigrationInterface::class);
//        return $migration;
    }

    public function findAll($connectionName = 'default')
    {

        $connections = $this->getCapsule()->getConnectionNames();

        $collection = [];
        //dd($connections);
        foreach ($connections as $connectionName) {
//            $this->forgeMigrationTable($connectionName);
            $queryBuilder = $this->getCapsule()->getQueryBuilderByConnectionName($connectionName, $this->tableNameAlias());
            try {
                $array = $queryBuilder->get()->toArray();
                foreach ($array as $item) {
                    $entityClass = $this->getEntityClass();
                    $entity = new $entityClass;
                    $entity->version = $item->version;
                    //$entity->className = $className;
                    $collection[] = $entity;
                }
            } catch (\Throwable $e) {}
        }

        return $collection;
    }

    private function forgeMigrationTable($connectionName = 'default')
    {
//        $schema = $this->getCapsule()->getSchemaByConnectionName($connectionName);
        //$schema = $this->getSchema($connectionName);
//        $tableAlias = $this->getCapsule()->getAlias();

        $targetTableName = $this->encodeTableName(self::MIGRATION_TABLE_NAME, $connectionName);

//        $targetTableName = $tableAlias->encode($connectionName, self::MIGRATION_TABLE_NAME);
        $hasTable = $this->getSchema($connectionName)->hasTable($targetTableName);

        if ($hasTable) {
            return;
        }
        $this->createMigrationTable($connectionName);
    }

    private function createMigrationTable($connectionName = 'default')
    {
        $tableSchema = function (Blueprint $table) {
            $table->string('version')->primary();
            $table->timestamp('executed_at');
        };
//        $schema = $this->getCapsule()->getSchemaByConnectionName($connectionName);

//        $tableAlias = $this->getCapsule()->getAlias();
//        $targetTableName = $tableAlias->encode($connectionName, self::MIGRATION_TABLE_NAME);

        $targetTableName = $this->encodeTableName(self::MIGRATION_TABLE_NAME, $connectionName);

        $this->getSchema($connectionName)->create($targetTableName, $tableSchema);
    }

}