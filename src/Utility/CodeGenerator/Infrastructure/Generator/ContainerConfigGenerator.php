<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Generator;

use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use function Symfony\Component\String\u;

class ContainerConfigGenerator
{

    public function __construct(protected GenerateResultCollection $collection, private string $namespace)
    {
    }

    public function generate(string $abstractClassName, string $concreteClassName, array $args = null): void
    {
        $codeForAppend = $this->generateDefinition($abstractClassName, $concreteClassName, $args);
        $configFile = ComposerHelper::getPsr4Path($this->namespace) . '/resources/config/services/main.php';
        $templateFile = __DIR__ . '/../../resources/templates/container-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($this->collection, $configFile, $templateFile);
        if (!$configGenerator->hasCode($concreteClassName)) {
            $code = $configGenerator->appendCode($codeForAppend);
        }
        $this->collection->add(new FileResult($configFile, $code));
    }

    private function generateDefinition(string $abstractClassName, string $concreteClassName, array $args = null): string {
        $codeForAppend = '    $services->set(\\' . $abstractClassName . '::class, \\' . $concreteClassName . '::class)';
        if ($args) {
            $argsCode = '';
            foreach ($args as $arg) {
                $argsCode .= "\t\t\t";
                if($this->isClass($arg)) {
                    $className = u($arg)->ensureStart('\\')->toString();
                    $argsCode .= "service({$className}::class)";
                } else {
                    $argsCode .= "{$arg}";
                }
                $argsCode .= ",\n";
            }
            $codeForAppend .= '
        ->args([
            ' . trim($argsCode) . '
        ])';
        }
        $codeForAppend .= ';';
        return $codeForAppend;
    }

    public function isClass(string $name): bool
    {
        $isMatch = preg_match('/^[a-zA-Z_\x80-\xff\\\][a-zA-Z0-9_\x80-\xff\\\]*$/i', $name);
        return is_string($name) && ($isMatch || class_exists($name));
    }
}
