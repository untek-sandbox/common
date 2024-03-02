<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Factories;

use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;

class GenerateApplicationCommandFactory
{

    public static function create($namespace, $type, $entityName, $properties, array $parameters = [], string $modelName = null): GenerateApplicationCommand {
        $command = new GenerateApplicationCommand();
        $command->setNamespace($namespace);
        $command->setType($type);
        $command->setName($entityName);
        $command->setProperties($properties);
        $command->setParameters($parameters);
        $command->setModelName($modelName);
        return $command;
    }
}