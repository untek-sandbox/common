<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Generator;

use Untek\Core\Code\Helpers\ComposerHelper;

class CommandBusLoadConfigGenerator
{

    public function __construct(private string $namespace)
    {
    }

    public function generate(string $modulePath): string {
        $codeForAppend = '    $configLoader->boot(__DIR__ . \'/../'.$modulePath.'\');';
        $configFile = __DIR__ . '/../../../../../../../../config/command-bus.php';
        $templateFile = __DIR__ . '/../../resources/templates/command-bus-load-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);
        if(!$configGenerator->hasCode($modulePath)) {
            $configGenerator->appendCode($codeForAppend);
        }
        return $configFile;
    }
}