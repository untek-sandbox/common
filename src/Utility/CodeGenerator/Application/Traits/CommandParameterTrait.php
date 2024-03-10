<?php

namespace Untek\Utility\CodeGenerator\Application\Traits;

trait CommandParameterTrait
{

    private array $parameters = [];

    public function getParameter(string $generatorClass, string $key)
    {
        return $this->parameters[$generatorClass][$key] ?? null;
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }
}