<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;

class CommandHandlerGenerator
{

    public function generate(GenerateApplicationCommand $command): GenerateResult
    {
        $handlerClassName = ApplicationPathHelper::getHandlerClassName($command);
        $commandClassName = ApplicationPathHelper::getCommandClass($command);
        $validatorClassName = ApplicationPathHelper::getCommandValidatorClass($command);

        $params = [
            'commandClassName' => $commandClassName,
            'validatorClassName' => $validatorClassName,
        ];
        $template = __DIR__ . '/../../resources/templates/handler.tpl.php';

        $fileGenerator = new FileGenerator();
        $fileName = $fileGenerator->generatePhpClass($handlerClassName, $template, $params);

        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        $generateResult = new GenerateResult();
        $generateResult->setFileName($fileName);

        return $generateResult;
    }
}