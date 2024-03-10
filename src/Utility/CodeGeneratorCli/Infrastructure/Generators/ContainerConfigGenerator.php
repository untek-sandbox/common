<?php

namespace Untek\Utility\CodeGeneratorCli\Infrastructure\Generators;

use Untek\Model\Cqrs\Application\Services\CommandBusInterface;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorCli\Application\Commands\GenerateCliCommand;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Helpers\CliPathHelper;

class ContainerConfigGenerator
{

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    public function generate(GenerateCliCommand $command): void
    {
        $cliCommandClassName = CliPathHelper::getCliCommandClass($command);
        $args = [
            CommandBusInterface::class,
        ];
        $consoleConfigGenerator = new \Untek\Utility\CodeGenerator\Infrastructure\Generator\ContainerConfigGenerator($this->collection, $command->getNamespace());
        $consoleConfigGenerator->generate($cliCommandClassName, $cliCommandClassName, $args);
    }
}