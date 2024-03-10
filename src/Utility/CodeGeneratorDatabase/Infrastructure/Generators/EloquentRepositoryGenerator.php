<?php

namespace Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Helpers\DatabasePathHelper;

class EloquentRepositoryGenerator
{

    private CodeGenerator $codeGenerator;

    public function __construct(protected GenerateResultCollection $collection)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateDatabaseCommand $command): void
    {
        $repositoryDriver = $command->getRepositoryDriver();
        $modelClassName = DatabasePathHelper::getModelClass($command);
        $className = DatabasePathHelper::getRepositoryClass($command, $repositoryDriver);
        $normalizerClassName = DatabasePathHelper::getNormalizerClass($command);
        $interfaceClassName = DatabasePathHelper::getRepositoryInterface($command);
        $relationClassName = DatabasePathHelper::getRelationClass($command);
        $params = [
            'tableName' => $command->getTableName(),
            'interfaceClassName' => $interfaceClassName,
            'modelClassName' => $modelClassName,
            'normalizerClassName' => $normalizerClassName,
            'relationClassName' => $relationClassName,
        ];
        $template = __DIR__ . '/../../resources/templates/' . $repositoryDriver . '-repository.php';
        $code = $this->codeGenerator->generatePhpClassCode($className, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($className);
        $this->collection->add(new FileResult($fileName, $code));
    }
}