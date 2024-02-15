<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;

class CommandGenerator
{

    private CodeGenerator $codeGenerator;

    public function __construct(protected GenerateResultCollection $collection)
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
        $this->collection->add(new FileResult($fileName, $code));
        return new GenerateResultCollection([

        ]);
    }
}