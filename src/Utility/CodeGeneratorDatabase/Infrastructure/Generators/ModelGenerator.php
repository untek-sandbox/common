<?php

namespace Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Helpers\ApplicationPathHelper;

class ModelGenerator
{

    private CodeGenerator $codeGenerator;

    public function __construct()
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateDatabaseCommand $command): GenerateResultCollection
    {
        $className = ApplicationPathHelper::getModelClass($command);
        $params = [
            'properties' => ApplicationHelper::prepareProperties($command),
        ];
        $template = __DIR__ . '/../../resources/templates/model.tpl.php';
        $code = $this->codeGenerator->generatePhpClassCode($className, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($className);
        return new GenerateResultCollection([
            new FileResult($fileName, $code)
        ]);
    }
}