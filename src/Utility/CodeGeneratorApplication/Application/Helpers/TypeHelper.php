<?php

namespace Untek\Utility\CodeGeneratorApplication\Application\Helpers;

use Untek\Utility\CodeGeneratorApplication\Application\Enums\TypeEnum;

class TypeHelper
{

    public static function generateCommandClassName(string $namespace, string $type, string $entityName) {
        $commandClass = TypeHelper::generateCommandName($type, $entityName);
        return $namespace . '\\Application\\' . $commandClass;
    }

    public static function generateCommandName(string $type, string $entityName) {
        if($type === TypeEnum::COMMAND) {
            $commandClass = 'Commands\\' . $entityName . 'Command';
        } elseif($type === TypeEnum::QUERY) {
            $commandClass = 'Queries\\' . $entityName . 'Query';
        }
        return $commandClass;
    }
}