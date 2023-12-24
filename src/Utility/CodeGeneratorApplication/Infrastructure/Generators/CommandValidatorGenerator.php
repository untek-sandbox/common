<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;

class CommandValidatorGenerator
{

    public function generate(GenerateApplicationCommand $command): GenerateResult
    {
        $commandClassName = ApplicationPathHelper::getCommandClass($command);
        $validatorClassName = ApplicationPathHelper::getCommandValidatorClass($command);

        $params = [
            'properties' => ApplicationHelper::prepareProperties($command),
            'commandClassName' => $commandClassName,
        ];
        $template = __DIR__ . '/../../resources/templates/validator.tpl.php';

        $fileGenerator = new FileGenerator();
        $fileName = $fileGenerator->generatePhpClass($validatorClassName, $template, $params);

        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        $generateResult = new GenerateResult();
        $generateResult->setFileName($fileName);

        return $generateResult;
    }
}