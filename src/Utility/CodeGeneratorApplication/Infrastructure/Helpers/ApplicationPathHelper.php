<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers;

use Untek\Utility\CodeGenerator\Application\Commands\AbstractCommandCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Enums\TypeEnum;

class ApplicationPathHelper
{

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
        return $command->getNamespace() . '\\Application\\'.$directoy.'\\' . $command->getCamelizeName();
    }

    public static function getHandlerClassName(AbstractCommandCommand $command): string
    {
        return $command->getNamespace() . '\\Application\\Handlers\\' . $command->getCamelizeName() . 'Handler';
    }
}