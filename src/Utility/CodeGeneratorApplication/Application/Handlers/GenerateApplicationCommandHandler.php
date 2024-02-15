<?php

namespace Untek\Utility\CodeGeneratorApplication\Application\Handlers;

use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
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

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    public function __invoke(GenerateApplicationCommand $command)
    {
        $generators = [
            new CommandGenerator($this->collection),
            new CommandHandlerGenerator($this->collection),
            new CommandValidatorGenerator($this->collection),
            new ContainerConfigGenerator($this->collection),
            new ContainerConfigImportGenerator($this->collection),
            new ContainerConfigBusGenerator($this->collection),
            new ContainerConfigBusImportGenerator($this->collection),
        ];

        $collection = GeneratorHelper::generate($generators, $command);
        GeneratorHelper::dump($this->collection);

        return $collection;
    }
}