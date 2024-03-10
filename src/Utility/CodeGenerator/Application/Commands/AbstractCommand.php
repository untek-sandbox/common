<?php

namespace Untek\Utility\CodeGenerator\Application\Commands;

abstract class AbstractCommand
{

    private array $parameters = [];
    private string $namespace;

    public function getParameter(string $generatorClass, string $key)
    {
        return $this->parameters[$generatorClass][$key] ?? null;
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }
}