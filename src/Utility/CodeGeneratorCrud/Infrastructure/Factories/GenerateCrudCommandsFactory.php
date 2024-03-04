<?php

namespace Untek\Utility\CodeGeneratorCrud\Infrastructure\Factories;

use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Utility\CodeGenerator\Application\Enums\CrudTypeEnum;
use Untek\Utility\CodeGeneratorApplication\Application\Enums\TypeEnum;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Factories\GenerateApplicationCommandFactory;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators\CommandGenerator;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators\CommandHandlerGenerator;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators\CommandValidatorGenerator;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Factories\GenerateRestApiCommandFactory;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators\ControllerGenerator;

class GenerateCrudCommandsFactory
{

    public static function create(array $crud, string $namespace, string $modelName = null): array
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

            $commands[] = GenerateRestApiCommandFactory::create($namespace, $type, $name, $uri, $method, null, $parameters, $modelName, $properties);
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
            'list' => [
                'type' => TypeEnum::QUERY,
                'name' => "Get{$modelName}List",
                'uri' => $uriPrefix,
                'method' => 'GET',
                'crudType' => CrudTypeEnum::LIST,
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
            'create' => [
                'type' => TypeEnum::COMMAND,
                'name' => "Create{$modelName}",
                'uri' => $uriPrefix,
                'method' => 'POST',
                'crudType' => CrudTypeEnum::CREATE,
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
                /*'properties' => [
                    [
                        'name' => 'parent_id',
                        'type' => 'int',
                    ],
                    [
                        'name' => 'title',
                        'type' => 'array',
                    ],
                ],*/
            ],
            'one' => [
                'type' => TypeEnum::QUERY,
                'name' => "Get{$modelName}ById",
                'uri' => $uriPrefix . '/{id}',
                'method' => 'GET',
                'crudType' => CrudTypeEnum::ONE,
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
            'update' => [
                'type' => TypeEnum::COMMAND,
                'name' => "Update{$modelName}ById",
                'uri' => $uriPrefix . '/{id}',
                'method' => 'PUT',
                'crudType' => CrudTypeEnum::UPDATE,
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
            'delete' => [
                'type' => TypeEnum::COMMAND,
                'name' => "Delete{$modelName}ById",
                'uri' => $uriPrefix . '/{id}',
                'method' => 'DELETE',
                'crudType' => CrudTypeEnum::DELETE,
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