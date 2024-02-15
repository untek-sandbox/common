<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Helpers\ApplicationPathHelper;

class ControllerTestGenerator
{

    private CodeGenerator $codeGenerator;

    public function __construct()
    {
        $this->codeGenerator = new CodeGenerator();
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
        return new GenerateResultCollection([
            new FileResult($fileName, $code)
        ]);
    }
}