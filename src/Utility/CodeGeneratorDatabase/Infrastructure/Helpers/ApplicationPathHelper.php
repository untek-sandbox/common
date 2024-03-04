<?php

namespace Untek\Utility\CodeGeneratorDatabase\Infrastructure\Helpers;

use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;

class ApplicationPathHelper
{

    public static function getInterfaceClassName(object $command): string
    {
        return $command->getNamespace() . '\\Application\\Services\\' . $command->getModelName() . 'RepositoryInterface';
    }

    public static function getModelClass(object $command): string
    {
        return $command->getNamespace() . '\\Domain\\Model\\' . $command->getModelName();
    }

    public static function getSeedClass(object $command): string
    {
        return $command->getNamespace() . '\\Infrastructure\\Persistence\\Seeds\\' . $command->getModelName() . 'Seed';
    }

    public static function getNormalizerClass(object $command): string
    {
        return $command->getNamespace() . '\\Infrastructure\\Persistence\\Normalizer\\' . $command->getModelName() . 'Normalizer';
    }

    public static function getRelationClass(object $command): string
    {
        return $command->getNamespace() . '\\Infrastructure\\Persistence\\Relation\\' . $command->getModelName() . 'Relation';
    }

    public static function getRepositoryClass(object $command, string $driver): string
    {
        $driverName = Inflector::camelize($driver);
        return $command->getNamespace() . '\\Infrastructure\\Persistence\\' . $driverName . '\\Repository\\' . $command->getModelName() . 'Repository';
    }
}