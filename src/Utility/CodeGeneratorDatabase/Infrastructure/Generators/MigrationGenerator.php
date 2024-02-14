<?php

namespace Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\PackageHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;

class MigrationGenerator
{

    private CodeGenerator $codeGenerator;
    private Filesystem $fs;


    public function __construct()
    {
        $this->codeGenerator = new CodeGenerator();
        $this->fs = new Filesystem();

    }

    public function generate(GenerateDatabaseCommand $command): GenerateResultCollection
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

        $code = $this->codeGenerator->generatePhpCode($template, $params);

        $resultCollection = new GenerateResultCollection();

        $resultCollection->add(new GenerateResult($fileName, $code));

        $importResultCollection = (new MigrationConfigGenerator($command->getNamespace(), getenv('MIGRATION_CONFIG_FILE')))->generate();
        $resultCollection->merge($importResultCollection);

        foreach ($resultCollection->getAll() as $result) {
            $this->fs->dumpFile($result->getFileName(), $result->getCode());
        }
//        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        return $resultCollection;
    }
}