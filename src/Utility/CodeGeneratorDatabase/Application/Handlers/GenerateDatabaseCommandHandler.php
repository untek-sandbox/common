<?php

namespace Untek\Utility\CodeGeneratorDatabase\Application\Handlers;

use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorHelper;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGeneratorDatabase\Application\Validators\GenerateDatabaseCommandValidator;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators\ContainerConfigGenerator;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators\EloquentRepositoryGenerator;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators\MigrationGenerator;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators\ModelGenerator;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators\NormalizerGenerator;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators\RepositoryGenerator;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators\RepositoryInterfaceGenerator;

class GenerateDatabaseCommandHandler
{

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    /**
     * @param GenerateDatabaseCommand $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(GenerateDatabaseCommand $command)
    {
        $validator = new GenerateDatabaseCommandValidator();
        $validator->validate($command);

        $generators = [
            new RepositoryInterfaceGenerator($this->collection),
            new NormalizerGenerator($this->collection),
            new EloquentRepositoryGenerator($this->collection),
            new ModelGenerator($this->collection),
            new ContainerConfigGenerator($this->collection),
            new MigrationGenerator($this->collection),
        ];

        $collection = GeneratorHelper::generate($generators, $command);
        GeneratorHelper::dump($this->collection);

        return $collection;
    }
}