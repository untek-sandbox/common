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

        $files[] = $this->generateRepositoryInterface($command);
        $files[] = $this->generateModelClass($command);
        $files[] = $this->generateRepository($command);
        $files[] = $this->generateContainerConfig($command);
        $files[] = $this->generateMigration($command);

        return $files;
    }

    private function getInterfaceClassName(GenerateDatabaseCommand $command): string {
        return $command->getNamespace() . '\\Application\\Services\\' . Inflector::camelize($command->getTableName()) . 'RepositoryInterface';
    }

    private function getModelClass(GenerateDatabaseCommand $command): string {
        return $command->getNamespace() . '\\Domain\\Model\\' . Inflector::camelize($command->getTableName());
    }

    private function getRepositoryClass(GenerateDatabaseCommand $command): string {
        return $command->getNamespace() . '\\Infrastructure\\Persistence\\Doctrine\\Repository\\' . Inflector::camelize($command->getTableName()) . 'Repository';
    }

    private function generateContainerConfig(GenerateDatabaseCommand $command): string
    {
        $repositoryClassName = $this->getRepositoryClass($command);
        $repositoryInterfaceClassName = $this->getInterfaceClassName($command);

        $handlerDefinition =
            '    $services->set(\\' . $repositoryInterfaceClassName . '::class, \\' . $repositoryClassName . '::class)
        ->args([
            service(\Doctrine\DBAL\Connection::class),
        ]);';
        $consoleConfigGenerator = new ContainerConfigGenerator($command->getNamespace());
        $configFile = $consoleConfigGenerator->generate($handlerDefinition, $repositoryInterfaceClassName);

        return $configFile;
    }

    private function generateRepositoryInterface(GenerateDatabaseCommand $command): string {
        $className = $this->getInterfaceClassName($command);
//        dd($className);

        $params = [
            'tableName' => $command->getTableName(),
        ];
        $template = __DIR__ . '/../../resources/templates/repository-interface.php';

        $fileGenerator = new FileGenerator();
        return $fileGenerator->generatePhpClass($className, $template, $params);
    }

    private function generateModelClass(GenerateDatabaseCommand $command): string {
        $className = $this->getModelClass($command);

        $params = [
            'properties' => $this->prepareProperties($command),
        ];
        $template = __DIR__ . '/../../resources/templates/model.tpl.php';

        $fileGenerator = new FileGenerator();
        return $fileGenerator->generatePhpClass($className, $template, $params);
    }

    private function generateRepository(GenerateDatabaseCommand $command): string {
        $modelClassName = $this->getModelClass($command);
        $className = $this->getRepositoryClass($command);
        $interfaceClassName = $this->getInterfaceClassName($command);

        $params = [
            'tableName' => $command->getTableName(),
            'interfaceClassName' => $interfaceClassName,
            'modelClassName' => $modelClassName,
        ];
        $template = __DIR__ . '/../../resources/templates/repository.php';

        $fileGenerator = new FileGenerator();
        return $fileGenerator->generatePhpClass($className, $template, $params);
    }

    private function generateMigration(GenerateDatabaseCommand $command): string {
        $time = date('Y_m_d_His');
        $className = 'm_' . $time . '_create_' . $command->getTableName() . '_table';
        $fileName = PackageHelper::pathByNamespace($command->getNamespace()) . '/resources/migrations/' . $className . '.php';

        $params = [
            'tableName' => $command->getTableName(),
            'className' => $className,
            'properties' => $this->prepareProperties($command),
        ];
        $template = __DIR__ . '/../../resources/templates/migration.tpl.php';

        $fileGenerator = new FileGenerator();
        $fileGenerator->generatePhpFile($fileName, $template, $params);
        return $fileName;
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
}