<?php

namespace Untek\Utility\CodeGenerator\Application\Commands;

use Untek\Core\Enum\Helpers\EnumHelper;
use Untek\Utility\CodeGenerator\Application\Enums\CrudTypeEnum;

abstract class AbstractCommand
{

    private string $type;
    private ?string $crudType = null;
    private array $templates = [];
    private string $namespace;
    private string $name;
    
    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getCrudType(): ?string
    {
        return $this->crudType;
    }

    public function setCrudType(?string $crudType): void
    {
        EnumHelper::validate(CrudTypeEnum::class, $crudType);
        $this->crudType = $crudType;
    }

    public function getParameter(string $generatorClass, string $key)
    {
        return $this->templates[$generatorClass][$key] ?? null;
    }

    public function setParameters(array $parameters): void
    {
        $this->templates = $parameters;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}