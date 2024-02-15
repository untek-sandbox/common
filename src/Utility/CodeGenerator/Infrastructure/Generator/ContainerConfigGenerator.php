<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Generator;

use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;

class ContainerConfigGenerator
{

    public function __construct(private string $namespace)
    {
    }

    public function generate(string $abstractClassName, string $concreteClassName, array $args = null): GenerateResultCollection
    {
        $codeForAppend =
            '    $services->set(\\' . $abstractClassName . '::class, \\' . $concreteClassName . '::class)';

        if ($args) {
            $argsCode = implode(',' . PHP_EOL . "\t\t", $args);
            $codeForAppend .= '->args([
        ' . $argsCode . '
    ])';
        }
        $codeForAppend .= ';';
        $configFile = ComposerHelper::getPsr4Path($this->namespace) . '/resources/config/services/main.php';
        $templateFile = __DIR__ . '/../../resources/templates/container-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);
        if (!$configGenerator->hasCode($concreteClassName)) {
            $code = $configGenerator->appendCode($codeForAppend);
        }
        return new GenerateResultCollection([
            new FileResult($configFile, $code)
        ]);
    }
}