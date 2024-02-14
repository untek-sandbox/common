<?php

namespace Untek\Utility\CodeGeneratorApplication\Application\Handlers;

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

        $resultCollection = (new CommandGenerator())->generate($command);
        $collection->merge($resultCollection);

        $resultCollection = (new CommandHandlerGenerator())->generate($command);
        $collection->merge($resultCollection);

        $resultCollection = (new CommandValidatorGenerator())->generate($command);
        $collection->merge($resultCollection);

        $resultCollection = (new ContainerConfigGenerator())->generate($command);
        $collection->merge($resultCollection);

        $resultCollection = (new ContainerConfigImportGenerator())->generate($command);
        $collection->merge($resultCollection);

        $resultCollection = (new ContainerConfigBusGenerator())->generate($command);
        $collection->merge($resultCollection);

        $resultCollection = (new ContainerConfigBusImportGenerator())->generate($command);
        $collection->merge($resultCollection);

        $files = [];
        foreach ($collection->getAll() as $result) {
            $files[] = $result->getFileName();
        }
        return $files;
    }
}