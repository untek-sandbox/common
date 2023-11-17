<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Generator;

use Untek\Core\Code\Helpers\ComposerHelper;

class ContainerLoadConfigGenerator
{

    public function __construct(private string $namespace)
    {
    }

    public function generate(string $modulePath): string {
        $codeForAppend = '$loader->load(__DIR__ . \'/../'.$modulePath.'\');';
        $configFile = __DIR__ . '/../../../../../../../../config/container.php';
        $templateFile = __DIR__ . '/../../resources/templates/container-load-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);
        if(!$configGenerator->hasCode($modulePath)) {
            $configGenerator->appendCode($codeForAppend);
        }
        return $configFile;
    }
}