<?php

namespace Untek\Utility\CodeGeneratorCli\Application\Commands;

use Untek\Utility\CodeGenerator\Application\Commands\AbstractCommand;
use Untek\Utility\CodeGenerator\Application\Traits\CommandNamespaceTrait;
use Untek\Utility\CodeGenerator\Application\Traits\CommandParameterTrait;

class GenerateCliCommand
{

    use CommandParameterTrait;
    use CommandNamespaceTrait;

    public function __construct(
        string $namespace,
        private string $moduleName,
        private string $commandClass,
        private string $cliCommand,
        private array $properties = [],
        array $parameters = [],
    )
    {
        $this->parameters = $parameters;
        $this->namespace = $namespace;
    }

    public function getModuleName(): string
    {
        return $this->moduleName;
    }

    public function setModuleName(string $moduleName): void
    {
        $this->moduleName = $moduleName;
    }

    public function getCommandClass(): string
    {
        return $this->commandClass;
    }

    public function setCommandClass(string $commandClass): void
    {
        $this->commandClass = $commandClass;
    }

    public function getCliCommand(): string
    {
        return $this->cliCommand;
    }

    public function setCliCommand(string $cliCommand): void
    {
        $this->cliCommand = $cliCommand;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function setProperties(array $properties): void
    {
        $this->properties = $properties;
    }
}