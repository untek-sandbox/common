<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Factories;

use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;

class GenerateApplicationCommandFactory
{

    public static function create($namespace, $type, $entityName, $properties): GenerateApplicationCommand {
        $command = new GenerateApplicationCommand();
        $command->setNamespace($namespace);
        $command->setType($type);
        $command->setName($entityName);
        $command->setProperties($properties);

        return $command;
    }
}