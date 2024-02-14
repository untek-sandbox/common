<?php

namespace Untek\Utility\CodeGeneratorDatabase\Application\Handlers;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;
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

    /**
     * @param GenerateDatabaseCommand $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(GenerateDatabaseCommand $command)
    {
        $validator = new GenerateDatabaseCommandValidator();
        $validator->validate($command);

        $collection = new GenerateResultCollection();

        $generators = [
            new RepositoryInterfaceGenerator(),
            new NormalizerGenerator(),
            new EloquentRepositoryGenerator(),
            new ModelGenerator(),
            new ContainerConfigGenerator(),
            new MigrationGenerator(),
        ];

        foreach ($generators as $generator) {
            $resultCollection = $generator->generate($command);
            $collection->merge($resultCollection);
        }

        $fs = new Filesystem();
        foreach ($collection->getAll() as $result) {
            $fs->dumpFile($result->getFileName(), $result->getCode());
        }

        return $collection;
    }
}