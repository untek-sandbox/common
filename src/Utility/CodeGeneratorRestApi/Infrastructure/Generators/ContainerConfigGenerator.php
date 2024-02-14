<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Helpers\ApplicationPathHelper;

class ContainerConfigGenerator
{

    public function generate(GenerateRestApiCommand $command): GenerateResultCollection
    {
        $controllerClassName = ApplicationPathHelper::getControllerClassName($command);

        $args = [
            'service(\Untek\Model\Cqrs\Application\Services\CommandBusInterface::class)',
            'service(\Symfony\Component\Routing\Generator\UrlGeneratorInterface::class)'
        ];
        $consoleConfigGenerator = new \Untek\Utility\CodeGenerator\Infrastructure\Generator\ContainerConfigGenerator($command->getNamespace());
        return $consoleConfigGenerator->generate($controllerClassName, $controllerClassName, $args);

//        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        /*return new GenerateResultCollection([
            new GenerateResult($fileName)
        ]);*/
    }
}