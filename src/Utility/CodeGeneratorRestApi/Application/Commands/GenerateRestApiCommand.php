<?php

namespace Untek\Utility\CodeGeneratorRestApi\Application\Commands;

class GenerateRestApiCommand
{

    private string $namespace;
    private string $moduleName;
    private string $commandClass;
    private string $uri;
    private string $httpMethod;
    private string $version;
    private array $templates = [];

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): void
    {
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

    public function getUri(): string
    {
        return $this->uri;
    }

    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    public function setHttpMethod(string $httpMethod): void
    {
        $this->httpMethod = $httpMethod;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function getParameter(string $generatorClass, string $key)
    {
        return $this->templates[$generatorClass][$key] ?? null;
    }

    public function setParameters(array $parameters): void
    {
        $this->templates = $parameters;
    }
}