<?php

namespace Untek\Utility\CodeGeneratorCrud\Infrastructure\Factories;

use Untek\Utility\CodeGeneratorApplication\Infrastructure\Factories\GenerateApplicationCommandFactory;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Factories\GenerateRestApiCommandFactory;

class GenerateCrudCommandsFactory
{

    public static function create(array $crud, $namespace, string $modelName = null): array
    {
        $commands = [];
        foreach ($crud as $item) {
            $type = $item['type'];
            $name = $item['name'];
            $uri = $item['uri'];
            $method = $item['method'];
            $properties = $item['properties'] ?? [];
            $parameters = $item['parameters'] ?? [];

            $commands[] = GenerateRestApiCommandFactory::create($namespace, $type, $name, $uri, $method, null, $parameters, $modelName);
            $commands[] = GenerateApplicationCommandFactory::create($namespace, $type, $name, $properties, $parameters, $modelName);
        }
        return $commands;
    }
}