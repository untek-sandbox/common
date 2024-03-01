<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers;

use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Enums\TypeEnum;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;

class ApplicationPathHelper
{

    public static function getRepositoryInterfaceClassName(GenerateDatabaseCommand $command): string
    {
        return $command->getNamespace() . '\\Application\\Services\\' . Inflector::camelize($command->getTableName()) . 'RepositoryInterface';
    }

    public static function getCommandValidatorClass(GenerateApplicationCommand $command): string
    {
        $camelizeName = Inflector::camelize($command->getName());
        $camelizeUnitName = $camelizeName . Inflector::camelize($command->getType());
        $validatorClassName = $command->getNamespace() . '\\Application\\Validators\\' . $camelizeUnitName . 'Validator';
        return $validatorClassName;
    }

    public static function getCommandClass(GenerateApplicationCommand $command): string
    {
        $camelizeName = Inflector::camelize($command->getName());
        $camelizeUnitName = $camelizeName . Inflector::camelize($command->getType());
        $commandClassName = $command->getNamespace() . '\\Application\\Commands\\' . $camelizeUnitName;
        if ($command->getType() == TypeEnum::QUERY) {
            $commandClassName = $command->getNamespace() . '\\Application\\Queries\\' . $camelizeUnitName;
        }
        return $commandClassName;
    }

    public static function getHandlerClassName(GenerateApplicationCommand $command): string
    {
        $camelizeName = Inflector::camelize($command->getName());
        $camelizeUnitName = $camelizeName . Inflector::camelize($command->getType());
        $handlerClassName = $command->getNamespace() . '\\Application\\Handlers\\' . $camelizeUnitName . 'Handler';
        return $handlerClassName;
    }
}