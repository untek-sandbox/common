<?php

namespace Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators;

use Untek\Core\Code\Helpers\PackageHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;

class MigrationGenerator
{

    public function generate(GenerateDatabaseCommand $command): GenerateResult
    {
        $time = date('Y_m_d_His');
        $className = 'm_' . $time . '_create_' . $command->getTableName() . '_table';
        $fileName = PackageHelper::pathByNamespace($command->getNamespace()) . '/resources/migrations/' . $className . '.php';

        $params = [
            'tableName' => $command->getTableName(),
            'className' => $className,
            'properties' => ApplicationHelper::prepareProperties($command),
        ];
        $template = __DIR__ . '/../../resources/templates/migration.tpl.php';

        $fileGenerator = new FileGenerator();
        $fileGenerator->generatePhpFile($fileName, $template, $params);

        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        $generateResult = new GenerateResult();
        $generateResult->setFileName($fileName);
        return $generateResult;
    }
}