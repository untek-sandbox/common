<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers;

use Laminas\Code\Generator\PropertyGenerator;
use Laminas\Code\Generator\TypeGenerator;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGenerator\Application\Commands\AbstractCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;

class ApplicationHelper
{

    public static function prepareProperties(AbstractCommand $command): array {
        $properties = [];
        foreach ($command->getProperties() as &$commandAttribute) {
            $name = Inflector::variablize($commandAttribute['name']);
            $propertyGenerator = new PropertyGenerator($name, '', PropertyGenerator::FLAG_PRIVATE, TypeGenerator::fromTypeString($commandAttribute['type']));
            $propertyGenerator->omitDefaultValue();
            $properties[] = $propertyGenerator;
        }
        return $properties;
    }
}