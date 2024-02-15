<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators;

use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;

class ContainerConfigGenerator
{

    public function generate(GenerateApplicationCommand $command): GenerateResultCollection
    {
        $handlerClassName = ApplicationPathHelper::getHandlerClassName($command);
        $consoleConfigGenerator = new \Untek\Utility\CodeGenerator\Infrastructure\Generator\ContainerConfigGenerator($command->getNamespace());
        return $consoleConfigGenerator->generate($handlerClassName, $handlerClassName);
    }
}