<?php

namespace Untek\Utility\CodeGeneratorApplication\Application\Helpers;

use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGeneratorApplication\Application\Enums\TypeEnum;

class TypeHelper
{

    public static function generateCommandName(string $type, string $commandName)
    {
        $commandName = Inflector::camelize($commandName);
        if ($type === TypeEnum::COMMAND) {
            $commandClass = 'Commands\\' . $commandName . 'Command';
        } elseif ($type === TypeEnum::QUERY) {
            $commandClass = 'Queries\\' . $commandName . 'Query';
        }
        return $commandClass;
    }
}