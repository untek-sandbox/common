<?php

namespace Untek\Utility\CodeGeneratorCli\Infrastructure\Factories;

use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Helpers\TypeHelper;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;
use Untek\Utility\CodeGeneratorCli\Application\Commands\GenerateCliCommand;

class CliCommandFactory
{

    public static function create($namespace, $commandType, $commandName, $properties, $cliCommand): GenerateCliCommand
    {
        $moduleName = ClassHelper::getClassOfClassName($namespace);
        $commandClassName = ApplicationPathHelper::generateCommandClass($namespace, $commandType, $commandName);

        return new GenerateCliCommand(
            $namespace,
            $moduleName,
            $commandClassName,
            $cliCommand,
            $properties,
        );
    }
}