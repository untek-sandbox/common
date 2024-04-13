<?php

namespace Untek\Utility\CodeGeneratorRestApi\Application\Helpers;

use Untek\Core\Instance\Helpers\ClassHelper;

class CommandHelper
{

    public static function getType(string $commandClassName): string {
        $commandClassName = ClassHelper::getClassOfClassName($commandClassName);
        $endCommandClassName = null;
        if (str_ends_with($commandClassName, 'Command')) {
            $endCommandClassName = 'Command';
        } elseif (str_ends_with($commandClassName, 'Query')) {
            $endCommandClassName = 'Query';
        }
        return $endCommandClassName;
    }
}