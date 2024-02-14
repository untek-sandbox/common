<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;

class CommandGenerator
{

    private CodeGenerator $codeGenerator;

    public function __construct()
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateApplicationCommand $command): GenerateResultCollection
    {
        $commandClassName = ApplicationPathHelper::getCommandClass($command);

        $params = [
            'properties' => ApplicationHelper::prepareProperties($command),
        ];
        $template = __DIR__ . '/../../resources/templates/command.tpl.php';

        $code = $this->codeGenerator->generatePhpClassCode($commandClassName, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($commandClassName);

//        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        return new GenerateResultCollection([
            new GenerateResult($fileName, $code)
        ]);
    }
}