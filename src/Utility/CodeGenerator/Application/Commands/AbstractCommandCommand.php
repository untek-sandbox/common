<?php

namespace Untek\Utility\CodeGenerator\Application\Commands;

use Untek\Core\Enum\Helpers\EnumHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGeneratorApplication\Application\Enums\TypeEnum;

abstract class AbstractCommandCommand extends AbstractCommand
{

    private string $commandName;
    private string $commandType;

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