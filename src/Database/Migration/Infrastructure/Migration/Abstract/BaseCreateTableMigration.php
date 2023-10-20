<?php

namespace Untek\Database\Migration\Infrastructure\Migration\Abstract;

use Untek\Database\Migration\Infrastructure\Migration\Enums\ForeignActionEnum;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Untek\Database\Base\Domain\Enums\DbDriverEnum;
use Untek\Database\Base\Domain\Helpers\SqlHelper;
use Untek\Database\Base\Domain\Traits\TableNameTrait;
use Untek\Database\Migration\Infrastructure\Migration\Interfaces\MigrationInterface;

abstract class BaseCreateTableMigration extends BaseMigration implements MigrationInterface
{

    use TableNameTrait;

    protected $blueprint;
    protected $tableComment = '';

    public function tableStructure(Blueprint $table): void
    {

    }

    public function tableSchema()//: Closure
    {
        return function (Blueprint $table) {
            $this->blueprint = $table;
            $this->tableStructure($table);
        };
    }

    public function connectionName()
    {
        return 'default';
    }

    public function isInOneDatabase(string $tableName): bool
    {
        return $this->getCapsule()->isInOneDatabase($this->tableName(), $tableName);
    }

    public function addForeign(Blueprint $table, string $foreignColumns, string $onTable, string $referencesColumns = 'id', string $onDelete = ForeignActionEnum::CASCADE, string $onUpdate = ForeignActionEnum::CASCADE)
    {
        if ($this->isInOneDatabase($onTable)) {
            $table
                ->foreign($foreignColumns)
                ->references($referencesColumns)
                ->on($this->encodeTableName($onTable))
                ->onDelete($onDelete)
                ->onUpdate($onUpdate);
        }
    }

    public function up(Builder $schema)
    {
        $isHasSchema = SqlHelper::isHasSchemaInTableName($this->tableNameAlias());
        if ($isHasSchema) {
            $schemaName = SqlHelper::extractSchemaFormTableName($this->tableNameAlias());
            $this->getConnection()->statement('CREATE SCHEMA IF NOT EXISTS "' . $schemaName . '";');
        }

        if(method_exists($this, 'tableStructure')) {
            $closure = function (Blueprint $table) {
                $this->tableStructure($table);
            };
        } else {
            // TODO: избавиться от метода tableSchema
            $closure = $this->tableSchema();
        }

        $schema->create($this->tableNameAlias(), $closure);

        if ($this->tableComment) {
            $this->addTableComment($schema);
        }
    }

    public function down(Builder $schema)
    {
        $schema->dropIfExists($this->tableNameAlias());
    }

    private function addTableComment(Builder $schema)
    {
        $connection = $this->getConnection();
        $driver = $connection->getConfig('driver');
        $table = $this->tableNameAlias();
        $quotedTableName = SqlHelper::generateRawTableName($table);
        $tableComment = $this->tableComment;
        $sql = '';
        if ($driver == DbDriverEnum::MYSQL) {
            $sql = "ALTER TABLE {$table} COMMENT '{$tableComment}';";
        }
        if ($driver == DbDriverEnum::PGSQL) {
            $sql = "COMMENT ON TABLE {$quotedTableName} IS '{$tableComment}';";
        }
        if ($sql) {
            $this->runSqlQuery($schema, $sql);
        }
    }
}