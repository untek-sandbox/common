<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators;

use Illuminate\Support\Str;
use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Helpers\ApplicationPathHelper;

class ControllerGenerator
{

    private CodeGenerator $codeGenerator;
    private Filesystem $fs;
    private FileGenerator $fileGenerator;

    public function __construct()
    {
        $this->codeGenerator = new CodeGenerator();
        $this->fs = new Filesystem();
        $this->fileGenerator = new FileGenerator();
    }

    public function generate(GenerateRestApiCommand $command): GenerateResult
    {
        $commandFullClassName = $command->getCommandClass();
//        $commandFullClassName = Str::up($command->getCommandClass());
//        dd($commandFullClassName);
        $commandClassName = ClassHelper::getClassOfClassName($command->getCommandClass());
        $commandClassName = Inflector::camelize($commandClassName);
        
        $controllerClassName = ApplicationPathHelper::getControllerClassName($command);
        $schemaClassName = ApplicationPathHelper::getRestApiSchemaClassName($command);

        $params = [
            'commandClassName' => $commandClassName,
            'commandFullClassName' => $commandFullClassName,
            'schemaClassName' => $schemaClassName,
        ];
        $template = __DIR__ . '/../../resources/templates/rest-api-controller.tpl.php';

//        $fileGenerator = new FileGenerator();
//        $fileName = $fileGenerator->generatePhpClass($controllerClassName, $template, $params);

        $code = $this->codeGenerator->generatePhpClassCode($controllerClassName, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($controllerClassName);
        $this->fs->dumpFile($fileName, $code);

//        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        $generateResult = new GenerateResult();
        $generateResult->setFileName($fileName);
        return $generateResult;
    }
}