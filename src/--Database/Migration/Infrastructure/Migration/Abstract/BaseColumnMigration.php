<?php

namespace Untek\Database\Migration\Infrastructure\Migration\Abstract;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Untek\Database\Base\Domain\Helpers\SqlHelper;

abstract class BaseColumnMigration extends BaseCreateTableMigration
{

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

        $schema->table($this->tableNameAlias(), $closure);
    }
}
