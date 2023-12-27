<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Helpers;

use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Application\Helpers\CommandHelper;

class ApplicationPathHelper
{

    public static function getControllerClassName(GenerateRestApiCommand $command): string
    {
        $commandClassName = ClassHelper::getClassOfClassName($command->getCommandClass());
        $commandClassName = Inflector::camelize($commandClassName);
        $endCommandClassName = CommandHelper::getType($command->getCommandClass());
        $pureCommandClassName = substr($commandClassName, 0, 0 - strlen($endCommandClassName));
        return $command->getNamespace() . '\\Presentation\\Http\\RestApi\\Controllers\\' . $pureCommandClassName . 'Controller';
    }

    public static function getControllerTestClassName(GenerateRestApiCommand $command): string
    {
        $commandClassName = ClassHelper::getClassOfClassName($command->getCommandClass());
        $commandClassName = Inflector::camelize($commandClassName);
        $endCommandClassName = CommandHelper::getType($command->getCommandClass());
        $pureCommandClassName = substr($commandClassName, 0, 0 - strlen($endCommandClassName));
        return 'Tests\\RestApi\\'.$command->getModuleName().'\\' . $pureCommandClassName . 'Test';
    }
}