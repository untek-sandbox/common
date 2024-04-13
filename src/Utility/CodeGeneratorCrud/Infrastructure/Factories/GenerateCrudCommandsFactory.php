<?php

namespace Untek\Utility\CodeGeneratorCrud\Infrastructure\Factories;

use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Enums\TypeEnum;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Factories\GenerateApplicationCommandFactory;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators\CommandGenerator;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators\CommandHandlerGenerator;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators\CommandValidatorGenerator;
use Untek\Utility\CodeGeneratorCrud\Infrastructure\Enums\CrudOperationEnum;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Factories\GenerateRestApiCommandFactory;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators\ControllerGenerator;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators\RestApiSchemeGenerator;

class GenerateCrudCommandsFactory
{

    public static function create(array $crud, string $namespace, string $modelName = null, array $dbProperties): array
    {
//        $crud = self::prepareDefinition($crud);
        $commands = [];
        foreach ($crud as $item) {
            $type = $item['type'];
            $name = $item['name'];
            $uri = $item['uri'];
            $method = $item['method'];
            $properties = $item['properties'] ?? [];
            $parameters = $item['parameters'] ?? [];

            $commands[] = GenerateRestApiCommandFactory::create($namespace, $type, $name, $uri, $method, null, $parameters, $modelName, $dbProperties);
            $commands[] = GenerateApplicationCommandFactory::create($namespace, $type, $name, $properties, $parameters, $modelName);
        }
        return $commands;
    }

    public static function prepareDefinition(
        array $crud,
        string $namespace,
        string $modelName = null,
        string $uriPrefix,
    ): array
    {
        $repositoryInterface = $namespace . '\\Application\\Services\\' . $modelName . 'RepositoryInterface';
        $crudTemplate = [
            CrudOperationEnum::LIST => [
                'type' => TypeEnum::QUERY,
                'name' => "Get{$modelName}List",
                'uri' => $uriPrefix,
                'method' => 'GET',
                'parameters' => [
                    CommandHandlerGenerator::class => [
                        'template' => __DIR__ . '/../../../CodeGeneratorCrud/resources/templates/handler/get-list-handler.tpl.php',
                        'constructArguments' => [
                            $repositoryInterface,
                        ],
                    ],
                    CommandValidatorGenerator::class => [
                        'template' => __DIR__ . '/../../../CodeGeneratorCrud/resources/templates/validator/get-list-query-validator.tpl.php',
                    ],
                    CommandGenerator::class => [
                        'template' => __DIR__ . '/../../../CodeGeneratorCrud/resources/templates/command/get-list-query.tpl.php',
                    ],
                    ControllerGenerator::class => [
                        'template' => __DIR__ . '/../../../CodeGeneratorCrud/resources/templates/rest-api-controller/rest-api-controller-list.tpl.php',
                    ],
                ],
                'properties' => [],
            ],
            CrudOperationEnum::CREATE => [
                'type' => TypeEnum::COMMAND,
                'name' => "Create{$modelName}",
                'uri' => $uriPrefix,
                'method' => 'POST',
                'parameters' => [
                    CommandHandlerGenerator::class => [
                        'template' => __DIR__ . '/../../../CodeGeneratorCrud/resources/templates/handler/create-handler.tpl.php',
                        'constructArguments' => [
                            $repositoryInterface,
                        ],
                    ],
                    ControllerGenerator::class => [
                        'template' => __DIR__ . '/../../../CodeGeneratorCrud/resources/templates/rest-api-controller/rest-api-controller-create.tpl.php',
                    ],
                ],
            ],
            CrudOperationEnum::ONE => [
                'type' => TypeEnum::QUERY,
                'name' => "Get{$modelName}ById",
                'uri' => $uriPrefix . '/{id}',
                'method' => 'GET',
                'properties' => [
                    [
                        'name' => 'id',
                        'type' => 'int',
                    ],
                    [
                        'name' => 'expand',
                        'type' => 'array',
                        'defaultValue' => [],
//                'required' => false,
                    ],
                ],
                'parameters' => [
                    CommandHandlerGenerator::class => [
                        'template' => __DIR__ . '/../../../CodeGeneratorCrud/resources/templates/handler/get-one-handler.tpl.php',
                        'constructArguments' => [
                            $repositoryInterface,
                        ],
                    ],
                    CommandValidatorGenerator::class => [
                        'template' => __DIR__ . '/../../../CodeGeneratorCrud/resources/templates/validator/get-one-query-validator.tpl.php',
                    ],
                    CommandGenerator::class => [
                        'template' => __DIR__ . '/../../../CodeGeneratorCrud/resources/templates/command/get-by-id-query.tpl.php',
                    ],
                    ControllerGenerator::class => [
                        'template' => __DIR__ . '/../../../CodeGeneratorCrud/resources/templates/rest-api-controller/rest-api-controller-one.tpl.php',
                    ],
                ],
            ],
            CrudOperationEnum::UPDATE => [
                'type' => TypeEnum::COMMAND,
                'name' => "Update{$modelName}ById",
                'uri' => $uriPrefix . '/{id}',
                'method' => 'PUT',
                'parameters' => [
                    CommandHandlerGenerator::class => [
                        'template' => __DIR__ . '/../../../CodeGeneratorCrud/resources/templates/handler/update-handler.tpl.php',
                        'constructArguments' => [
                            $repositoryInterface,
                        ],
                    ],
                    ControllerGenerator::class => [
                        'template' => __DIR__ . '/../../../CodeGeneratorCrud/resources/templates/rest-api-controller/rest-api-controller-update.tpl.php',
                    ],
                    RestApiSchemeGenerator::class => [
                        'skip' => true,
                    ],
                ],
                'properties' => [
                    [
                        'name' => 'id',
                        'type' => 'int',
                    ],
                    /*[
                        'name' => 'parent_id',
                        'type' => 'int',
                    ],
                    [
                        'name' => 'title',
                        'type' => 'array',
                    ],*/
                ],
            ],
            CrudOperationEnum::DELETE => [
                'type' => TypeEnum::COMMAND,
                'name' => "Delete{$modelName}ById",
                'uri' => $uriPrefix . '/{id}',
                'method' => 'DELETE',
                'parameters' => [
                    CommandHandlerGenerator::class => [
                        'template' => __DIR__ . '/../../../CodeGeneratorCrud/resources/templates/handler/delete-handler.tpl.php',
                        'constructArguments' => [
                            $repositoryInterface,
                        ],
                    ],
                    CommandValidatorGenerator::class => [
                        'template' => __DIR__ . '/../../../CodeGeneratorCrud/resources/templates/validator/delete-command-validator.tpl.php',
                    ],
                    ControllerGenerator::class => [
                        'template' => __DIR__ . '/../../../CodeGeneratorCrud/resources/templates/rest-api-controller/rest-api-controller-delete.tpl.php',
                    ],
                    RestApiSchemeGenerator::class => [
                        'skip' => true,
                    ],
                ],
                'properties' => [
                    [
                        'name' => 'id',
                        'type' => 'int',
                    ],
                ],
            ],
        ];
        return ArrayHelper::merge($crudTemplate, $crud);
    }
}