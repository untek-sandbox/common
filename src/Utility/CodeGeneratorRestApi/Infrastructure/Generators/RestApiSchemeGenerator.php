<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators;

use Illuminate\Support\Str;
use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Helpers\ApplicationPathHelper;

class RestApiSchemeGenerator
{

    private CodeGenerator $codeGenerator;
    private Filesystem $fs;


    public function __construct()
    {
        $this->codeGenerator = new CodeGenerator();
        $this->fs = new Filesystem();

    }

    public function generate(GenerateRestApiCommand $command): GenerateResultCollection
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

        $code = $this->codeGenerator->generatePhpClassCode($schemaClassName, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($schemaClassName);

//        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        return new GenerateResultCollection([
            new GenerateResult($fileName, $code)
        ]);
    }
}