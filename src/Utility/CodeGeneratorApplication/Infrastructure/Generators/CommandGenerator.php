<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;

class CommandGenerator
{

    public function generate(GenerateApplicationCommand $command): GenerateResult
    {
        $commandClassName = ApplicationPathHelper::getCommandClass($command);

        $params = [
            'properties' => ApplicationHelper::prepareProperties($command),
        ];
        $template = __DIR__ . '/../../resources/templates/command.tpl.php';

        $fileGenerator = new FileGenerator();
        $fileName = $fileGenerator->generatePhpClass($commandClassName, $template, $params);

        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        $generateResult = new GenerateResult();
        $generateResult->setFileName($fileName);
        return $generateResult;
    }
}