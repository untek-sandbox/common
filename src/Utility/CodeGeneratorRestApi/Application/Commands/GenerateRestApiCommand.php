<?php

namespace Untek\Utility\CodeGeneratorRestApi\Application\Commands;

class GenerateRestApiCommand
{

    private string $namespace;
    private string $commandClass;
    private string $uri;
    private string $httpMethod;

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
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

}