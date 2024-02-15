<?php

namespace Untek\Utility\CodeGeneratorRestApi\Application\Handlers;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
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

        $generators = [
            new ControllerGenerator(),
            new RestApiSchemeGenerator(),
            new ControllerTestGenerator(),
            new ContainerConfigGenerator(),
            new RoutConfigGenerator(),
            new RoutConfigImportGenerator(),
        ];

        $collection = GeneratorHelper::generate($generators, $command);

        $fs = new Filesystem();
        foreach ($collection->getAll() as $result) {
            $fs->dumpFile($result->getFileName(), $result->getCode());
        }

        $endpoint = 'Endpoint: ' . $command->getHttpMethod() . ' rest-api/v' . $command->getVersion() . '/' . $command->getUri();
        $collection->add(new GenerateResult($endpoint, ''));

        return $collection;
    }
}