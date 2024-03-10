<?php

namespace Untek\Utility\CodeGenerator\Application\Traits;

trait CommandNamespaceTrait
{

    private string $namespace;

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }
}