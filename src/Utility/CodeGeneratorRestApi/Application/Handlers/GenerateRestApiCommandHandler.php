<?php

namespace Untek\Utility\CodeGeneratorRestApi\Application\Handlers;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Application\Validators\GenerateRestApiCommandValidator;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators\ContainerConfigGenerator;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators\ControllerGenerator;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators\ControllerTestGenerator;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators\RestApiSchemeGenerator;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators\RoutConfigGenerator;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators\RoutConfigImportGenerator;

class GenerateRestApiCommandHandler
{

    /**
     * @param GenerateRestApiCommand $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(GenerateRestApiCommand $command)
    {
        $validator = new GenerateRestApiCommandValidator();
        $validator->validate($command);

        $collection = new GenerateResultCollection();

        $resultCollection = (new ControllerGenerator())->generate($command);
        $collection->merge($resultCollection);

        $resultCollection = (new RestApiSchemeGenerator())->generate($command);
        $collection->merge($resultCollection);

        $resultCollection = (new ControllerTestGenerator())->generate($command);
        $collection->merge($resultCollection);

        $resultCollection = (new ContainerConfigGenerator())->generate($command);
        $collection->merge($resultCollection);

        $resultCollection = (new RoutConfigGenerator())->generate($command);
        $collection->merge($resultCollection);

        $resultCollection = (new RoutConfigImportGenerator())->generate($command);
        $collection->merge($resultCollection);

        $files = [];
        $fs = new Filesystem();
        foreach ($collection->getAll() as $result) {
            $fs->dumpFile($result->getFileName(), $result->getCode());
            $files[] = GeneratorFileHelper::fileNameTotoRelative(realpath($result->getFileName()));
        }

        $files[] = 'Endpoint: ' . $command->getHttpMethod() . ' rest-api/v' . $command->getVersion() . '/' . $command->getUri();

        return $files;
    }
}