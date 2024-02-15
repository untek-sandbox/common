<?php

namespace Untek\Utility\CodeGeneratorApplication\Application\Handlers;

use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
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
        $generators = [
            new CommandGenerator(),
            new CommandHandlerGenerator(),
            new CommandValidatorGenerator(),
            new ContainerConfigGenerator(),
            new ContainerConfigImportGenerator(),
            new ContainerConfigBusGenerator(),
            new ContainerConfigBusImportGenerator(),
        ];

        $collection = GeneratorHelper::generate($generators, $command);
        GeneratorHelper::dump($collection);

        return $collection;
    }
}