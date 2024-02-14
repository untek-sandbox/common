<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;

class ContainerConfigBusGenerator
{

    private CodeGenerator $codeGenerator;
    private Filesystem $fs;


    public function __construct()
    {
        $this->codeGenerator = new CodeGenerator();
        $this->fs = new Filesystem();

    }

    public function generate(GenerateApplicationCommand $command): GenerateResultCollection
    {
        $handlerClassName = ApplicationPathHelper::getHandlerClassName($command);
        $commandClassName = ApplicationPathHelper::getCommandClass($command);

        $fileName = ComposerHelper::getPsr4Path($command->getNamespace()) . '/resources/config/command-bus.php';
        $templateFile = __DIR__ . '/../../resources/templates/command-bus-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($fileName, $templateFile);

        $code = null;
        if(!$configGenerator->hasCode($handlerClassName)) {
            $controllerDefinition =
                '    $configurator->define(\\' . $commandClassName . '::class, \\' . $handlerClassName . '::class);';
            $code = $configGenerator->appendCode($controllerDefinition);
        }

//        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

//        $generateResult = new GenerateResult();
//        $generateResult->setFileName($fileName);
//        return $generateResult;
        $resultCollection = new GenerateResultCollection();
        if($code) {
            $this->fs->dumpFile($fileName, $code);
            $resultCollection->add(new GenerateResult($fileName, $code));
        }

        return $resultCollection;
    }

}