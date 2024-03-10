<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Helpers;

use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Application\Helpers\CommandHelper;

class RestApiPathHelper
{

    public static function getRestApiSchemaClass(GenerateRestApiCommand $command): string
    {
        $commandFullClassName = ApplicationPathHelper::getCommandClass($command);
        $commandClassName = ClassHelper::getClassOfClassName($commandFullClassName);
        $commandClassName = Inflector::camelize($commandClassName);
//        $endCommandClassName = CommandHelper::getType($command->getCommandClass());
        $pureCommandClassName = substr($commandClassName, 0, 0 - strlen($command->getCommandType()));
        return $command->getNamespace() . '\\Presentation\\Http\\RestApi\\Schema\\' . $pureCommandClassName . 'Schema';
    }

    public static function getControllerClass(GenerateRestApiCommand $command): string
    {
        $commandFullClassName = ApplicationPathHelper::getCommandClass($command);
        $commandClassName = ClassHelper::getClassOfClassName($commandFullClassName);
        $commandClassName = Inflector::camelize($commandClassName);
//        $endCommandClassName = CommandHelper::getType($command->getCommandClass());
        $pureCommandClassName = substr($commandClassName, 0, 0 - strlen($command->getCommandType()));
        return $command->getNamespace() . '\\Presentation\\Http\\RestApi\\Controllers\\' . $pureCommandClassName . 'Controller';
    }

    public static function getControllerTestClass(GenerateRestApiCommand $command): string
    {
        $commandFullClassName = ApplicationPathHelper::getCommandClass($command);
        $commandClassName = ClassHelper::getClassOfClassName($commandFullClassName);
        $commandClassName = Inflector::camelize($commandClassName);
//        $endCommandClassName = CommandHelper::getType($command->getCommandClass());
        $pureCommandClassName = substr($commandClassName, 0, 0 - strlen($command->getCommandType()));
        return 'Tests\\RestApi\\'.$command->getModuleName().'\\' . $pureCommandClassName . 'Test';
    }
}