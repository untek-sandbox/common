<?php

namespace Untek\Utility\CodeGeneratorApplication\Application\Commands;

use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGenerator\Application\Commands\AbstractCommand;

class GenerateApplicationCommand extends AbstractCommand
{

    private array $properties;
    private ?string $modelName = null;

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function setProperties(array $properties): void
    {
        $this->properties = $properties;
    }

    public function getModelName(): ?string
    {
        return $this->modelName;
    }

    public function setModelName(?string $modelName): void
    {
        $this->modelName = $modelName;
    }
}