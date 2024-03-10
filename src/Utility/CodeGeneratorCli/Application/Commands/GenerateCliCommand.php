<?php

namespace Untek\Utility\CodeGeneratorCli\Application\Commands;

use Untek\Utility\CodeGenerator\Application\Commands\AbstractCommand;
use Untek\Utility\CodeGenerator\Application\Traits\CommandParameterTrait;

class GenerateCliCommand //extends AbstractCommand
{

    use CommandParameterTrait;

    public function __construct(
        private string $namespace,
        private string $moduleName,
        private string $commandClass,
        private string $cliCommand,
        private array $properties = [],
        array $parameters = [],
    )
    {
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return $this->moduleName;
    }

    /**
     * @param string $moduleName
     */
    public function setModuleName(string $moduleName): void
    {
        $this->moduleName = $moduleName;
    }

    /**
     * @return string
     */
    public function getCommandClass(): string
    {
        return $this->commandClass;
    }

    /**
     * @param string $commandClass
     */
    public function setCommandClass(string $commandClass): void
    {
        $this->commandClass = $commandClass;
    }

    /**
     * @return string
     */
    public function getCliCommand(): string
    {
        return $this->cliCommand;
    }

    /**
     * @param string $cliCommand
     */
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