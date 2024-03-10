<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators;

use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Helpers\ApplicationPathHelper;

class RestApiSchemeGenerator
{

    private CodeGenerator $codeGenerator;

    public function __construct(protected GenerateResultCollection $collection)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateRestApiCommand $command): void
    {
        if($command->getParameter(self::class, 'skip') == true) {
            return;
        }
        $commandFullClassName = $command->getCommandClass();
        $commandClassName = ClassHelper::getClassOfClassName($command->getCommandClass());
        $commandClassName = Inflector::camelize($commandClassName);
        $schemaClassName = ApplicationPathHelper::getRestApiSchemaClassName($command);
        $modelClassName = \Untek\Utility\CodeGeneratorDatabase\Infrastructure\Helpers\DatabasePathHelper::getModelClass($command);
        $params = [
            'commandClassName' => $commandClassName,
            'commandFullClassName' => $commandFullClassName,
            'modelClassName' => $modelClassName,
            'properties' => $command->getProperties(),
        ];
        $template = __DIR__ . '/../../resources/templates/rest-api-schema.tpl.php';
        $code = $this->codeGenerator->generatePhpClassCode($schemaClassName, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($schemaClassName);
        $this->collection->add(new FileResult($fileName, $code));
    }
}