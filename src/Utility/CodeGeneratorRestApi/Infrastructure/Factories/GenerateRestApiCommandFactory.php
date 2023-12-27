<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Factories;

use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGeneratorApplication\Application\Helpers\TypeHelper;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;

class GenerateRestApiCommandFactory
{

    public static function create($namespace, $type, $entityName, $uri, $method, $apiVersion = null): GenerateRestApiCommand {
        $apiVersion = $apiVersion ? $apiVersion : getenv('REST_API_VERSION');
        
        $commandClassName = TypeHelper::generateCommandClassName($namespace, $type, Inflector::camelize($entityName));
        
        $moduleName = ClassHelper::getClassOfClassName($namespace);

        $command = new GenerateRestApiCommand();
        $command->setNamespace($namespace);
        $command->setModuleName($moduleName);
        $command->setCommandClass($commandClassName);
        $command->setUri($uri);
        $command->setHttpMethod($method);
        $command->setVersion($apiVersion);
        return $command;
    }
}