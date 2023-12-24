<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators;

use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Helpers\ApplicationPathHelper;

class ControllerGenerator
{

    public function generate(GenerateRestApiCommand $command): GenerateResult
    {
        $commandClassName = ClassHelper::getClassOfClassName($command->getCommandClass());
        $controllerClassName = ApplicationPathHelper::getControllerClassName($command);

        $params = [
            'commandClassName' => $commandClassName,
            'commandFullClassName' => $command->getCommandClass(),
        ];
        $template = __DIR__ . '/../../resources/templates/rest-api-controller.tpl.php';

        $fileGenerator = new FileGenerator();
        $fileName = $fileGenerator->generatePhpClass($controllerClassName, $template, $params);

        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        $generateResult = new GenerateResult();
        $generateResult->setFileName($fileName);
        return $generateResult;
    }
}