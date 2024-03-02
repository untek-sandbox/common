<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers;

use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Enums\TypeEnum;

class ApplicationPathHelper
{

    public static function getCommandValidatorClass(GenerateApplicationCommand $command): string
    {
        return $command->getNamespace() . '\\Application\\Validators\\' . $command->getCamelizeName() . 'Validator';
    }

    public static function getCommandClass(GenerateApplicationCommand $command): string
    {
        if ($command->getType() == TypeEnum::QUERY) {
            $directoy = 'Queries';
        } else {
            $directoy = 'Commands';
        }
        return $command->getNamespace() . '\\Application\\'.$directoy.'\\' . $command->getCamelizeName();
    }

    public static function getHandlerClassName(GenerateApplicationCommand $command): string
    {
        return $command->getNamespace() . '\\Application\\Handlers\\' . $command->getCamelizeName() . 'Handler';
    }
}