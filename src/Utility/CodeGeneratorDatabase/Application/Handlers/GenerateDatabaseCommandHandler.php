<?php

namespace Untek\Utility\CodeGeneratorDatabase\Application\Handlers;

use Laminas\Code\Generator\PropertyGenerator;
use Laminas\Code\Generator\TypeGenerator;
use Untek\Core\Code\Helpers\PackageHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGeneratorDatabase\Application\Helpers\CommandHelper;
use Untek\Utility\CodeGeneratorDatabase\Application\Validators\GenerateDatabaseCommandValidator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\ContainerConfigGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;

class GenerateDatabaseCommandHandler
{

    /**
     * @param GenerateDatabaseCommand $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(GenerateDatabaseCommand $command)
    {
        $files = [];

        $validator = new GenerateDatabaseCommandValidator();
        $validator->validate($command);

        $files[] = $this->generateMigration($command);

        return $files;
    }

    private function prepareProperties(GenerateDatabaseCommand $command): array {
        $properties = [];
        foreach ($command->getProperties() as &$commandAttribute) {
            $name = Inflector::variablize($commandAttribute['name']);
            $propertyGenerator = new PropertyGenerator($name, '', PropertyGenerator::FLAG_PRIVATE, TypeGenerator::fromTypeString($commandAttribute['type']));
            $propertyGenerator->omitDefaultValue();
            $properties[] = $propertyGenerator;
        }
        return $properties;
    }

    private function generateMigration(GenerateDatabaseCommand $command): string {
        $time = date('Y_m_d_His');
        $className = 'm_' . $time . '_create_' . $command->getTableName() . '_table';
        $fileName = PackageHelper::pathByNamespace($command->getNamespace()) . '/Resources/migrations/' . $className . '.php';

        $params = [
            'tableName' => $command->getTableName(),
            'className' => $className,
            'properties' => $this->prepareProperties($command),
        ];
        $template = __DIR__ . '/../../Resources/templates/migration.tpl.php';

        $fileGenerator = new FileGenerator();
        $fileGenerator->generatePhpFile($fileName, $template, $params);
        return $fileName;
    }
}