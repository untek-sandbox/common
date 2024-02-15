<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;

class CommandValidatorGenerator
{

    private CodeGenerator $codeGenerator;

    public function __construct()
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateApplicationCommand $command): GenerateResultCollection
    {
        $commandClassName = ApplicationPathHelper::getCommandClass($command);
        $validatorClassName = ApplicationPathHelper::getCommandValidatorClass($command);
        $params = [
            'properties' => ApplicationHelper::prepareProperties($command),
            'commandClassName' => $commandClassName,
        ];
        $template = __DIR__ . '/../../resources/templates/validator.tpl.php';
        $code = $this->codeGenerator->generatePhpClassCode($validatorClassName, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($validatorClassName);
        return new GenerateResultCollection([
            new FileResult($fileName, $code)
        ]);
    }
}