<?php

namespace Untek\Utility\CodeGeneratorApplication\Application\Handlers;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators\CommandGenerator;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators\CommandHandlerGenerator;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators\CommandValidatorGenerator;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators\ContainerConfigBusGenerator;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators\ContainerConfigBusImportGenerator;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators\ContainerConfigGenerator;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators\ContainerConfigImportGenerator;

class GenerateApplicationCommandHandler
{

    public function __invoke(GenerateApplicationCommand $command)
    {
        $collection = new GenerateResultCollection();

        $generators = [
            new CommandGenerator(),
            new CommandHandlerGenerator(),
            new CommandValidatorGenerator(),
            new ContainerConfigGenerator(),
            new ContainerConfigImportGenerator(),
            new ContainerConfigBusGenerator(),
            new ContainerConfigBusImportGenerator(),
        ];

        foreach ($generators as $generator) {
            $resultCollection = $generator->generate($command);
            $collection->merge($resultCollection);
        }

        $files = [];
        $fs = new Filesystem();
        foreach ($collection->getAll() as $result) {
            $fs->dumpFile($result->getFileName(), $result->getCode());
            $files[] = GeneratorFileHelper::fileNameTotoRelative(realpath($result->getFileName()));
        }
        return $collection;
    }
}