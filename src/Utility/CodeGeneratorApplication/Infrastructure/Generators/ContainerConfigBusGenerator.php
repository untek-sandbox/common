<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators;

use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;

class ContainerConfigBusGenerator
{

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    public function generate(GenerateApplicationCommand $command): void
    {
        $handlerClassName = ApplicationPathHelper::getHandlerClassName($command);
        $commandClassName = ApplicationPathHelper::getCommandClass($command);
        $fileName = ComposerHelper::getPsr4Path($command->getNamespace()) . '/resources/config/command-bus.php';
        $templateFile = __DIR__ . '/../../resources/templates/command-bus-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($this->collection, $fileName, $templateFile);
        if (!$configGenerator->hasCode($handlerClassName)) {
            $controllerDefinition =
                '    $configurator->define(\\' . $commandClassName . '::class, \\' . $handlerClassName . '::class);';
            $code = $configGenerator->appendCode($controllerDefinition);
            $this->collection->add(new FileResult($fileName, $code));
        }
    }
}