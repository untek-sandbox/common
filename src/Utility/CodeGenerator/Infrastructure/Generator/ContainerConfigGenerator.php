<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Generator;

use Untek\Core\Code\Helpers\ComposerHelper;

class ContainerConfigGenerator
{

    public function __construct(private string $namespace)
    {
    }

    public function generate(string $abstractClassName, string $concreteClassName, array $args = null): string {
        $codeForAppend =
            '    $services->set(\\' . $abstractClassName . '::class, \\' . $concreteClassName . '::class)';

        if($args) {
            $argsCode = implode(',' . PHP_EOL . "\t\t", $args);
            $codeForAppend .= '->args([
        '.$argsCode.'
    ])';
        }
        $codeForAppend .= ';';


        $configFile = ComposerHelper::getPsr4Path($this->namespace) . '/resources/config/services/main.php';
        $templateFile = __DIR__ . '/../../resources/templates/container-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);
        if(!$configGenerator->hasCode($concreteClassName)) {
            $configGenerator->appendCode($codeForAppend);
        }
        return $configFile;
    }
}