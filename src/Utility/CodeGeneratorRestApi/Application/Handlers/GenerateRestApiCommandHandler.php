<?php

namespace Untek\Utility\CodeGeneratorRestApi\Application\Handlers;

use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Application\Dto\InfoResult;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorHelper;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators\ContainerConfigImportGenerator;
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

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    /**
     * @param GenerateRestApiCommand $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(GenerateRestApiCommand $command)
    {
        $validator = new GenerateRestApiCommandValidator();
        $validator->validate($command);

        $generators = [
            new ControllerGenerator($this->collection),
            new RestApiSchemeGenerator($this->collection),
            new ControllerTestGenerator($this->collection),
            new ContainerConfigGenerator($this->collection),
            new ContainerConfigImportGenerator($this->collection, '/resources/config/services/rest-api.php', __DIR__ . '/../../../../../../../../config/rest-api/container.php'),
            new RoutConfigGenerator($this->collection),
            new RoutConfigImportGenerator($this->collection),
        ];

        GeneratorHelper::generate($generators, $command);
//        GeneratorHelper::dump($this->collection);

        $uri = '/rest-api/v' . $command->getVersion() . '/' . $command->getUri();
        $endpoint = $command->getHttpMethod() . ' ' . $uri;
        $this->collection->add(new InfoResult('API endpoint', $endpoint));
    }
}