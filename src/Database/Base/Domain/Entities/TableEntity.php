<?php

namespace Untek\Database\Base\Domain\Entities;

use Untek\Core\Collection\Interfaces\Enumerable;

class TableEntity
{

    protected $name;
    protected $schemaName;
    protected $dbName;
    protected $columns;
    protected $relations;
    protected $schema;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getSchemaName()
    {
        return $this->schemaName;
    }

    public function setSchemaName($schemaName): void
    {
        $this->schemaName = $schemaName;
    }

    public function getDbName()
    {
        return $this->dbName;
    }

    public function setDbName($dbName): void
    {
        $this->dbName = $dbName;
    }

    /**
     * @return Enumerable | ColumnEntity[]
     */
    public function getColumns(): Enumerable
    {
        return $this->columns;
    }

    public function setColumns(Enumerable $columns): void
    {
        $this->columns = $columns;
    }

    /**
     * @return Enumerable | RelationEntity[]
     */
    public function getRelations(): ?Enumerable
    {
        return $this->relations;
    }

    public function setRelations(?Enumerable $relations): void
    {
        $this->relations = $relations;
    }

    public function getSchema(): SchemaEntity
    {
        return $this->schema;
    }

    public function setSchema(SchemaEntity $schema): void
    {
        $this->schema = $schema;
    }

}