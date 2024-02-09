<?php

namespace Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Helpers\ApplicationPathHelper;

class NormalizerGenerator
{

    public function generate(GenerateDatabaseCommand $command): GenerateResult
    {
//        $repositoryDriver = 'eloquent';
//        $modelClassName = ApplicationPathHelper::getModelClass($command);
        $className = ApplicationPathHelper::getNormalizerClass($command);
//        $interfaceClassName = ApplicationPathHelper::getInterfaceClassName($command);

        $params = [
            'tableName' => $command->getTableName(),
//            'interfaceClassName' => $interfaceClassName,
//            'modelClassName' => $modelClassName,
        ];
        $template = __DIR__ . '/../../resources/templates/normalizer.php';

        $fileGenerator = new FileGenerator();
        $fileName = $fileGenerator->generatePhpClass($className, $template, $params);

        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        $generateResult = new GenerateResult();
        $generateResult->setFileName($fileName);
        return $generateResult;
    }
}