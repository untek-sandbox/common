<?php

namespace Untek\Utility\CodeGeneratorDatabase\Infrastructure\Helpers;

use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;

class ApplicationPathHelper
{

    public static function getInterfaceClassName(GenerateDatabaseCommand $command): string {
        return $command->getNamespace() . '\\Application\\Services\\' . Inflector::camelize($command->getTableName()) . 'RepositoryInterface';
    }

    public static function getModelClass(GenerateDatabaseCommand $command): string {
        return $command->getNamespace() . '\\Domain\\Model\\' . Inflector::camelize($command->getTableName());
    }

    public static function getRepositoryClass(GenerateDatabaseCommand $command): string {
        return $command->getNamespace() . '\\Infrastructure\\Persistence\\Doctrine\\Repository\\' . Inflector::camelize($command->getTableName()) . 'Repository';
    }
}