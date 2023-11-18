<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generator;

use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;

class RoutesConfigGenerator
{

    public function __construct()
    {
    }

    public function generate(string $configFile, string $controllerClassName, GenerateRestApiCommand $command): string {
        $templateFile = __DIR__ . '/../../resources/templates/route-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);
        if(!$configGenerator->hasCode($controllerClassName)) {
            $routeName = $command->getHttpMethod() . '_' . $command->getUri();
            $controllerDefinition =
                '    $routes
        ->add(\'' . $routeName . '\', \'/' . $command->getUri() . '\')
        ->controller(\\' . $controllerClassName . '::class)
        ->methods([\'' . $command->getHttpMethod() . '\']);';
            $configGenerator->appendCode($controllerDefinition);
        }
        return $configFile;
    }
}