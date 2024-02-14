<?php

namespace Untek\Utility\CodeGeneratorApplication\Application\Handlers;

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
        $files = [];

        $resultCollection = (new CommandGenerator())->generate($command);
        foreach ($resultCollection->getAll() as $result) {
            $files[] = $result->getFileName();
        }

        $resultCollection = (new CommandHandlerGenerator())->generate($command);
        foreach ($resultCollection->getAll() as $result) {
            $files[] = $result->getFileName();
        }

        $resultCollection = (new CommandValidatorGenerator())->generate($command);
        foreach ($resultCollection->getAll() as $result) {
            $files[] = $result->getFileName();
        }

        $resultCollection = (new ContainerConfigGenerator())->generate($command);
        foreach ($resultCollection->getAll() as $result) {
            $files[] = $result->getFileName();
        }

        $resultCollection = (new ContainerConfigImportGenerator())->generate($command);
        foreach ($resultCollection->getAll() as $result) {
            $files[] = $result->getFileName();
        }

        $resultCollection = (new ContainerConfigBusGenerator())->generate($command);
        foreach ($resultCollection->getAll() as $result) {
            $files[] = $result->getFileName();
        }

        $resultCollection = (new ContainerConfigBusImportGenerator())->generate($command);
        foreach ($resultCollection->getAll() as $result) {
            $files[] = $result->getFileName();
        }

        return $files;
    }
}