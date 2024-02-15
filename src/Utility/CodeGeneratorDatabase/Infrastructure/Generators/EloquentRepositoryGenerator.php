<?php

namespace Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Helpers\ApplicationPathHelper;

class EloquentRepositoryGenerator
{

    private CodeGenerator $codeGenerator;

    public function __construct()
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateDatabaseCommand $command): GenerateResultCollection
    {
        $repositoryDriver = $command->getRepositoryDriver();
        $modelClassName = ApplicationPathHelper::getModelClass($command);
        $className = ApplicationPathHelper::getRepositoryClass($command, $repositoryDriver);
        $normalizerClassName = ApplicationPathHelper::getNormalizerClass($command);
        $interfaceClassName = ApplicationPathHelper::getInterfaceClassName($command);
        $params = [
            'tableName' => $command->getTableName(),
            'interfaceClassName' => $interfaceClassName,
            'modelClassName' => $modelClassName,
            'normalizerClassName' => $normalizerClassName,
        ];
        $template = __DIR__ . '/../../resources/templates/' . $repositoryDriver . '-repository.php';
        $code = $this->codeGenerator->generatePhpClassCode($className, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($className);
        return new GenerateResultCollection([
            new GenerateResult($fileName, $code)
        ]);
    }
}