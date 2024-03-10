<?php

namespace Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators;

use Untek\Core\Code\Helpers\PackageHelper;
use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;

class MigrationGenerator
{

    private CodeGenerator $codeGenerator;
    private string $template = __DIR__ . '/../../resources/templates/migration.tpl.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateDatabaseCommand $command): void
    {
        $time = date('Y_m_d_His');
        $className = 'm_' . $time . '_create_' . $command->getTableName() . '_table';
        $fileName = PackageHelper::pathByNamespace($command->getNamespace()) . '/resources/migrations/' . $className . '.php';
        $params = [
            'tableName' => $command->getTableName(),
            'className' => $className,
            'properties' => ApplicationHelper::prepareProperties($command),
        ];
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $code = $this->codeGenerator->generatePhpCode($template, $params);
        $this->collection->add(new FileResult($fileName, $code));
        (new MigrationConfigGenerator($this->collection, $command->getNamespace(), getenv('MIGRATION_CONFIG_FILE')))->generate($command);
    }
}