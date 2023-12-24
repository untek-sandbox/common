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

        $result = (new CommandGenerator())->generate($command);
        $files[] = $result->getFileName();

        $result = (new CommandHandlerGenerator())->generate($command);
        $files[] = $result->getFileName();

        $result = (new CommandValidatorGenerator())->generate($command);
        $files[] = $result->getFileName();

        $result = (new ContainerConfigGenerator())->generate($command);
        $files[] = $result->getFileName();

        $result = (new ContainerConfigImportGenerator())->generate($command);
        $files[] = $result->getFileName();

        $result = (new ContainerConfigBusGenerator())->generate($command);
        $files[] = $result->getFileName();

        $result = (new ContainerConfigBusImportGenerator())->generate($command);
        $files[] = $result->getFileName();

        return $files;
    }
}