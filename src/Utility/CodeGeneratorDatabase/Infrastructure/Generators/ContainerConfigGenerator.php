<?php

namespace Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Helpers\ApplicationPathHelper;

class ContainerConfigGenerator
{

    public function generate(GenerateDatabaseCommand $command): GenerateResultCollection
    {
        $repositoryClassName = ApplicationPathHelper::getRepositoryClass($command, $command->getRepositoryDriver());
        $repositoryInterfaceClassName = ApplicationPathHelper::getInterfaceClassName($command);

        $args = [
            'service(\Illuminate\Database\Capsule\Manager::class)'
        ];
        $consoleConfigGenerator = new \Untek\Utility\CodeGenerator\Infrastructure\Generator\ContainerConfigGenerator($command->getNamespace());

        return $consoleConfigGenerator->generate($repositoryInterfaceClassName, $repositoryClassName, $args);

//        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        /*return new GenerateResultCollection([
            new GenerateResult($fileName)
        ]);*/
    }
}