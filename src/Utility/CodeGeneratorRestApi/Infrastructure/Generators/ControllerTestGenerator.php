<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Helpers\ApplicationPathHelper;

class ControllerTestGenerator
{

    private CodeGenerator $codeGenerator;
    private Filesystem $fs;


    public function __construct()
    {
        $this->codeGenerator = new CodeGenerator();
        $this->fs = new Filesystem();

    }

    public function generate(GenerateRestApiCommand $command): GenerateResultCollection
    {
        $controllerTestClassName = ApplicationPathHelper::getControllerTestClassName($command);
        $params = [
            'endpoint' => '/v' . $command->getVersion() . '/' . $command->getUri(),
            'method' => $command->getHttpMethod(),
        ];
        $template = __DIR__ . '/../../resources/templates/rest-api-controller-test.tpl.php';

        $code = $this->codeGenerator->generatePhpClassCode($controllerTestClassName, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($controllerTestClassName);

//        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        return new GenerateResultCollection([
            new GenerateResult($fileName, $code)
        ]);
    }
}