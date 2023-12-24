<?php

namespace Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Helpers\ApplicationPathHelper;

class ModelGenerator
{

    public function generate(GenerateDatabaseCommand $command): GenerateResult
    {
        $className = ApplicationPathHelper::getModelClass($command);

        $params = [
            'properties' => ApplicationHelper::prepareProperties($command),
        ];
        $template = __DIR__ . '/../../resources/templates/model.tpl.php';

        $fileGenerator = new FileGenerator();
        $fileName = $fileGenerator->generatePhpClass($className, $template, $params);

        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        $generateResult = new GenerateResult();
        $generateResult->setFileName($fileName);
        return $generateResult;
    }
}