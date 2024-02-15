<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators;

use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;

class ContainerConfigBusGenerator
{

    public function generate(GenerateApplicationCommand $command): GenerateResultCollection
    {
        $handlerClassName = ApplicationPathHelper::getHandlerClassName($command);
        $commandClassName = ApplicationPathHelper::getCommandClass($command);
        $fileName = ComposerHelper::getPsr4Path($command->getNamespace()) . '/resources/config/command-bus.php';
        $templateFile = __DIR__ . '/../../resources/templates/command-bus-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($fileName, $templateFile);
        $resultCollection = new GenerateResultCollection();
        if (!$configGenerator->hasCode($handlerClassName)) {
            $controllerDefinition =
                '    $configurator->define(\\' . $commandClassName . '::class, \\' . $handlerClassName . '::class);';
            $code = $configGenerator->appendCode($controllerDefinition);
            $resultCollection->add(new GenerateResult($fileName, $code));
        }
        return $resultCollection;
    }
}