<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators;

use Illuminate\Support\Str;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Helpers\ApplicationPathHelper;

class RestApiSchemeGenerator
{

    public function generate(GenerateRestApiCommand $command): GenerateResult
    {
        $commandFullClassName = $command->getCommandClass();
//        $commandFullClassName = Str::up($command->getCommandClass());
//        dd($commandFullClassName);
        $commandClassName = ClassHelper::getClassOfClassName($command->getCommandClass());
        $commandClassName = Inflector::camelize($commandClassName);
        
        $schemaClassName = ApplicationPathHelper::getRestApiSchemaClassName($command);
//dd($schemaClassName);

        $params = [
            'commandClassName' => $commandClassName,
            'commandFullClassName' => $commandFullClassName,
        ];
        $template = __DIR__ . '/../../resources/templates/rest-api-schema.tpl.php';

        $fileGenerator = new FileGenerator();
        $fileName = $fileGenerator->generatePhpClass($schemaClassName, $template, $params);

        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        $generateResult = new GenerateResult();
        $generateResult->setFileName($fileName);
        return $generateResult;
    }
}