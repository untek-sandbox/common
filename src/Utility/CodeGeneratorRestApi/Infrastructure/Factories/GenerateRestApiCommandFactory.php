<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Factories;

use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGeneratorApplication\Application\Helpers\TypeHelper;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Application\Helpers\CommandHelper;

class GenerateRestApiCommandFactory
{

    public static function create($namespace, $commandType, $commandName, $uri, $method, $apiVersion = null, array $parameters = [], string $modelName = null, array $properties = []): GenerateRestApiCommand {
        $apiVersion = $apiVersion ? $apiVersion : getenv('REST_API_VERSION');
        
//        $commandClassName = ApplicationPathHelper::generateCommandClass($namespace, $commandType, $commandName);
        
        $moduleName = ClassHelper::getClassOfClassName($namespace);

        $command = new GenerateRestApiCommand();
        $command->setNamespace($namespace);
        $command->setModuleName($moduleName);
//        $command->setCommandClass($commandClassName);
        $command->setUri($uri);
        $command->setHttpMethod($method);
        $command->setVersion($apiVersion);
        $command->setParameters($parameters);
        $command->setModelName($modelName);
        $command->setProperties($properties);

//        $commandType = CommandHelper::getType($command->getCommandClass());
//        dd(strtolower($commandType), $commandType);

        $command->setCommandName($commandName);
        $command->setCommandType($commandType);
        return $command;
    }
}