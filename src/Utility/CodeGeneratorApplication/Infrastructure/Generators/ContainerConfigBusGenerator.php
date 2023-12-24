<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators;

use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;

class ContainerConfigBusGenerator
{

    public function generate(GenerateApplicationCommand $command): GenerateResult
    {
        $handlerClassName = ApplicationPathHelper::getHandlerClassName($command);
        $commandClassName = ApplicationPathHelper::getCommandClass($command);

        $fileName = ComposerHelper::getPsr4Path($command->getNamespace()) . '/resources/config/command-bus.php';
        $templateFile = __DIR__ . '/../../resources/templates/command-bus-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($fileName, $templateFile);

        if(!$configGenerator->hasCode($handlerClassName)) {
            $controllerDefinition =
                '    $configurator->define(\\' . $commandClassName . '::class, \\' . $handlerClassName . '::class);';
            $configGenerator->appendCode($controllerDefinition);
        }

        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        $generateResult = new GenerateResult();
        $generateResult->setFileName($fileName);
        return $generateResult;
    }
}