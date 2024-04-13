<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers;

use Untek\Utility\CodeGenerator\Application\Commands\AbstractCommandCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Enums\TypeEnum;
use Untek\Utility\CodeGeneratorApplication\Application\Helpers\TypeHelper;

class ApplicationPathHelper
{

    public static function generateCommandClass(string $namespace, string $type, string $commandName): string
    {
        $commandClass = TypeHelper::generateCommandName($type, $commandName);
        return $namespace . '\\Application\\' . $commandClass;
    }

    public static function getCommandValidatorClass(AbstractCommandCommand $command): string
    {
        return $command->getNamespace() . '\\Application\\Validators\\' . $command->getCamelizeName() . 'Validator';
    }

    public static function getCommandClass(AbstractCommandCommand $command): string
    {
        if ($command->getCommandType() == TypeEnum::QUERY) {
            $directoy = 'Queries';
        } else {
            $directoy = 'Commands';
        }
        return $command->getNamespace() . '\\Application\\' . $directoy . '\\' . $command->getCamelizeName();
    }

    public static function getHandlerClass(AbstractCommandCommand $command): string
    {
        return $command->getNamespace() . '\\Application\\Handlers\\' . $command->getCamelizeName() . 'Handler';
    }
}