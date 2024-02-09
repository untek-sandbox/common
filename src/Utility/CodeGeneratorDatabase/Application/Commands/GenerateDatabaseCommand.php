<?php

namespace Untek\Utility\CodeGeneratorDatabase\Application\Commands;

use Untek\Utility\CodeGenerator\Application\Commands\AbstractCommand;

class GenerateDatabaseCommand extends AbstractCommand
{

    private string $namespace;
    private string $tableName;
    private array $properties;
    private string $repositoryDriver = 'eloquent';

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function setTableName(string $tableName): void
    {
        $this->tableName = $tableName;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function setProperties(array $properties): void
    {
        $this->properties = $properties;
    }

    public function getRepositoryDriver(): string
    {
        return $this->repositoryDriver;
    }

    public function setRepositoryDriver(string $repositoryDriver): void
    {
        $this->repositoryDriver = $repositoryDriver;
    }

}