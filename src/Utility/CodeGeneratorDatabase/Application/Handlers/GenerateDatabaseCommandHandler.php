<?php

namespace Untek\Utility\CodeGeneratorDatabase\Application\Handlers;

use Laminas\Code\Generator\PropertyGenerator;
use Laminas\Code\Generator\TypeGenerator;
use Untek\Core\Code\Helpers\PackageHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators\CommandGenerator;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGeneratorDatabase\Application\Helpers\CommandHelper;
use Untek\Utility\CodeGeneratorDatabase\Application\Validators\GenerateDatabaseCommandValidator;
//use Untek\Utility\CodeGenerator\Infrastructure\Generator\ContainerConfigGenerator;
use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators\DoctrineRepositoryGenerator;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators\EloquentRepositoryGenerator;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators\MigrationGenerator;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators\ModelGenerator;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators\NormalizerGenerator;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators\RepositoryGenerator;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators\RepositoryInterfaceGenerator;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Helpers\ApplicationPathHelper;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators\ContainerConfigGenerator;

class GenerateDatabaseCommandHandler
{

    /**
     * @param GenerateDatabaseCommand $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(GenerateDatabaseCommand $command)
    {
        $validator = new GenerateDatabaseCommandValidator();
        $validator->validate($command);

        $collection = new GenerateResultCollection();

        $resultCollection = (new RepositoryInterfaceGenerator())->generate($command);
        $collection->merge($resultCollection);

        $resultCollection = (new NormalizerGenerator())->generate($command);
        $collection->merge($resultCollection);

        $resultCollection = (new EloquentRepositoryGenerator())->generate($command);
        $collection->merge($resultCollection);

        $resultCollection = (new ModelGenerator())->generate($command);
        $collection->merge($resultCollection);

        $resultCollection = (new ContainerConfigGenerator())->generate($command);
        $collection->merge($resultCollection);

        $resultCollection = (new MigrationGenerator())->generate($command);
        $collection->merge($resultCollection);

        $files = [];
        $fs = new Filesystem();
        foreach ($collection->getAll() as $result) {
            $fs->dumpFile($result->getFileName(), $result->getCode());
            $files[] = GeneratorFileHelper::fileNameTotoRelative(realpath($result->getFileName()));
        }

        return $files;
    }

    /*private function getInterfaceClassName(GenerateDatabaseCommand $command): string {
        return $command->getNamespace() . '\\Application\\Services\\' . Inflector::camelize($command->getTableName()) . 'RepositoryInterface';
    }*/

    /*private function getModelClass(GenerateDatabaseCommand $command): string {
        return $command->getNamespace() . '\\Domain\\Model\\' . Inflector::camelize($command->getTableName());
    }*/

    /*private function getRepositoryClass(GenerateDatabaseCommand $command): string {
        return $command->getNamespace() . '\\Infrastructure\\Persistence\\Doctrine\\Repository\\' . Inflector::camelize($command->getTableName()) . 'Repository';
    }*/

    private function generateContainerConfig(GenerateDatabaseCommand $command): string
    {
        return GeneratorFileHelper::fileNameTotoRelative($configFile);
    }

    private function generateRepositoryInterface(GenerateDatabaseCommand $command): string {
        

        return GeneratorFileHelper::fileNameTotoRelative($fileName);
    }

    private function generateModelClass(GenerateDatabaseCommand $command): string {
        

        return GeneratorFileHelper::fileNameTotoRelative($fileName);
    }

    private function generateRepository(GenerateDatabaseCommand $command): string {
        

        return GeneratorFileHelper::fileNameTotoRelative($fileName);
    }

    private function generateMigration(GenerateDatabaseCommand $command): string {
        
        return GeneratorFileHelper::fileNameTotoRelative($fileName);
    }

    /*private function prepareProperties(GenerateDatabaseCommand $command): array {
        $properties = [];
        foreach ($command->getProperties() as &$commandAttribute) {
            $name = Inflector::variablize($commandAttribute['name']);
            $propertyGenerator = new PropertyGenerator($name, '', PropertyGenerator::FLAG_PRIVATE, TypeGenerator::fromTypeString($commandAttribute['type']));
            $propertyGenerator->omitDefaultValue();
            $properties[] = $propertyGenerator;
        }
        return $properties;
    }*/

    /*private function fileNameTotoRelative(string $filename): string {
        $fs = new Filesystem();
        $fileName = $fs->makePathRelative(realpath($filename), getenv('ROOT_DIRECTORY'));
        return rtrim($fileName, '/');
    }*/
}