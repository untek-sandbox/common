<?php

namespace Untek\Utility\CodeGeneratorApplication\Application\Commands;

use Untek\Utility\CodeGenerator\Application\Commands\AbstractCommand;

class GenerateApplicationCommand extends AbstractCommand
{

    private array $properties;

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function setProperties(array $properties): void
    {
        $this->properties = $properties;
    }
}