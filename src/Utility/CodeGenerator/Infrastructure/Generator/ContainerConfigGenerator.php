<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Generator;

use Untek\Core\Code\Helpers\ComposerHelper;

class ContainerConfigGenerator
{

    public function __construct(private string $namespace)
    {
    }

    public function generate(string $codeForAppend, string $codeForCheck = null): string {
        $configFile = ComposerHelper::getPsr4Path($this->namespace) . '/resources/config/services/main.php';
        $templateFile = __DIR__ . '/../../resources/templates/container-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);
        if(!$configGenerator->hasCode($codeForCheck) || !$codeForCheck) {
            $configGenerator->appendCode($codeForAppend);
        }
        return $configFile;
    }
}