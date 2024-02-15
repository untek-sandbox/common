<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators;

use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;

class ContainerConfigBusImportGenerator
{

    public function generate(GenerateApplicationCommand $command): GenerateResultCollection
    {
        $handlerClassName = ApplicationPathHelper::getHandlerClassName($command);
        $path = ComposerHelper::getPsr4Path($command->getNamespace());
        $relative = GeneratorFileHelper::fileNameTotoRelative($path);
        $modulePath = $relative . '/resources/config/command-bus.php';
        $codeForAppend = '    $configLoader->boot(__DIR__ . \'/../' . $modulePath . '\');';
        $fileName = __DIR__ . '/../../../../../../../../config/command-bus.php';
        $templateFile = __DIR__ . '/../../resources/templates/command-bus-load-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($fileName, $templateFile);
        $resultCollection = new GenerateResultCollection();
        if (!$configGenerator->hasCode($modulePath)) {
            $code = $configGenerator->appendCode($codeForAppend);
            $resultCollection->add(new FileResult($fileName, $code));
        }
        return $resultCollection;
    }
}