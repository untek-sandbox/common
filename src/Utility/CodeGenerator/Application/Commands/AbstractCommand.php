<?php

namespace Untek\Utility\CodeGenerator\Application\Commands;

use Untek\Core\Enum\Helpers\EnumHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGenerator\Application\Enums\CrudTypeEnum;
use Untek\Utility\CodeGeneratorApplication\Application\Enums\TypeEnum;

abstract class AbstractCommand
{

//    private string $type;
    private ?string $crudType = null;
    private array $templates = [];
    private string $namespace;
//    private string $name;

    private string $commandName;
    private string $commandType;

    /*public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->setCommandType($type);
        $this->type = $type;
    }*/

    /*public function getCrudType(): ?string
    {
        return $this->crudType;
    }

    public function setCrudType(?string $crudType): void
    {
        EnumHelper::validate(CrudTypeEnum::class, $crudType);
        $this->crudType = $crudType;
    }*/

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

    /*public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->setCommandName($name);
        $this->name = $name;
    }*/

    public function getCamelizeName(): string
    {
        $camelizeName = Inflector::camelize($this->getCommandName());
        return $camelizeName . Inflector::camelize($this->getCommandType());
    }

    public function getCommandName(): string
    {
        return $this->commandName;
    }

    public function setCommandName(string $commandName): void
    {
        $this->commandName = $commandName;
    }

    public function getCommandType(): string
    {
        return $this->commandType;
    }

    public function setCommandType(string $commandType): void
    {
        EnumHelper::validate(TypeEnum::class, $commandType);
        $this->commandType = $commandType;
    }
}