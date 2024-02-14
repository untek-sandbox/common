<?php

namespace Untek\Utility\CodeGeneratorRestApi\Application\Handlers;

use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
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
        $files = [];

        $validator = new GenerateRestApiCommandValidator();
        $validator->validate($command);

        $resultCollection = (new ControllerGenerator())->generate($command);
        foreach ($resultCollection->getAll() as $result) {
            $files[] = $result->getFileName();
        }

        $resultCollection = (new RestApiSchemeGenerator())->generate($command);
        foreach ($resultCollection->getAll() as $result) {
            $files[] = $result->getFileName();
        }

        $resultCollection = (new ControllerTestGenerator())->generate($command);
        foreach ($resultCollection->getAll() as $result) {
            $files[] = $result->getFileName();
        }

        $resultCollection = (new ContainerConfigGenerator())->generate($command);
        foreach ($resultCollection->getAll() as $result) {
            $files[] = $result->getFileName();
        }

        $resultCollection = (new RoutConfigGenerator())->generate($command);
        foreach ($resultCollection->getAll() as $result) {
            $files[] = $result->getFileName();
        }

        $resultCollection = (new RoutConfigImportGenerator())->generate($command);
        foreach ($resultCollection->getAll() as $result) {
            $files[] = $result->getFileName();
        }

        $files[] = 'Endpoint: ' . $command->getHttpMethod() . ' rest-api/v' . $command->getVersion() . '/' . $command->getUri();

        return $files;
    }
}