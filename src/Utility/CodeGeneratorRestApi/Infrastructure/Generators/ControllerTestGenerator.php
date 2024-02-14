<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Helpers\ApplicationPathHelper;

class ControllerTestGenerator
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
        $controllerTestClassName = ApplicationPathHelper::getControllerTestClassName($command);
        $params = [
            'endpoint' => '/v' . $command->getVersion() . '/' . $command->getUri(),
            'method' => $command->getHttpMethod(),
        ];
        $template = __DIR__ . '/../../resources/templates/rest-api-controller-test.tpl.php';

//        $fileGenerator = new FileGenerator();
//        $fileName = $fileGenerator->generatePhpClass($controllerTestClassName, $template, $params);

        $code = $this->codeGenerator->generatePhpClassCode($controllerTestClassName, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($controllerTestClassName);
        $this->fs->dumpFile($fileName, $code);

//        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        $generateResult = new GenerateResult();
        $generateResult->setFileName($fileName);
        return $generateResult;
    }
}