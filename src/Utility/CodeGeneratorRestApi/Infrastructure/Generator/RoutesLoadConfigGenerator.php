<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generator;

use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;

class RoutesLoadConfigGenerator
{

    public function __construct(private string $namespace)
    {
    }

    public function generate(string $modulePath, string $prefix = null): string {
        $codeForAppend = '    $routes
        ->import(__DIR__ . \'/../../../'.$modulePath.'\')
        ->prefix(\''.$prefix.'\');';
        $configFile = __DIR__ . '/../../../../../../../../context/rest-api/config/routes.php';
        $templateFile = __DIR__ . '/../../resources/templates/routes-load-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);
        if(!$configGenerator->hasCode($modulePath)) {
            $configGenerator->appendCode($codeForAppend);
        }
        return $configFile;
    }
}