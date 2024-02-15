<?php

namespace Untek\Utility\CodeGeneratorCli\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorCli\Application\Commands\GenerateCliCommand;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Helpers\ApplicationPathHelper;

class ContainerConfigGenerator
{

    public function generate(GenerateCliCommand $command): GenerateResultCollection
    {
        $cliCommandClassName = ApplicationPathHelper::getControllerClassName($command);
        $args = [
            'service(\Untek\Model\Cqrs\Application\Services\CommandBusInterface::class)'
        ];
        $consoleConfigGenerator = new \Untek\Utility\CodeGenerator\Infrastructure\Generator\ContainerConfigGenerator($command->getNamespace());
        return $consoleConfigGenerator->generate($cliCommandClassName, $cliCommandClassName, $args);
    }
}