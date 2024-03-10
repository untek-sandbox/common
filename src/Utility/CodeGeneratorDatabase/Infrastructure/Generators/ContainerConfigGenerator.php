<?php

namespace Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Helpers\DatabasePathHelper;
use Illuminate\Database\Capsule\Manager;

class ContainerConfigGenerator
{

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    public function generate(GenerateDatabaseCommand $command): void
    {
        $repositoryClassName = DatabasePathHelper::getRepositoryClass($command, $command->getRepositoryDriver());
        $repositoryInterfaceClassName = DatabasePathHelper::getRepositoryInterface($command);
        $args = [
            Manager::class,
        ];
        $consoleConfigGenerator = new \Untek\Utility\CodeGenerator\Infrastructure\Generator\ContainerConfigGenerator($this->collection, $command->getNamespace());
        $consoleConfigGenerator->generate($repositoryInterfaceClassName, $repositoryClassName, $args);
    }
}