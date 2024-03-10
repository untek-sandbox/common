<?php

namespace Untek\Utility\CodeGeneratorDatabase\Application\Commands;

use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGenerator\Application\Commands\AbstractCommand;
use Untek\Utility\CodeGenerator\Application\Traits\CommandParameterTrait;

class GenerateDatabaseCommand extends AbstractCommand
{

    use CommandParameterTrait;

    private string $namespace;
    private string $tableName;
    private ?string $modelName = null;
    private array $properties;
    private string $repositoryDriver = 'eloquent';

    public function __construct(
        string $namespace = null,
        string $tableName = null,
        array $properties = null,
        string $modelName = null,
    )
    {
        if($namespace) {
            $this->namespace = $namespace;
        }
        if($tableName) {
            $this->tableName = $tableName;
        }
        if($properties) {
            $this->properties = $properties;
        }
        if($modelName) {
            $this->modelName = $modelName;
        }
    }

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

    public function getModelName(): ?string
    {
        if($this->modelName) {
            return $this->modelName;
        }
        return Inflector::camelize($this->getTableName());
    }

    public function setModelName(?string $modelName): void
    {
        $this->modelName = $modelName;
    }
}