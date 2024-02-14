<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;

class ContainerConfigGenerator
{

    private CodeGenerator $codeGenerator;
    private Filesystem $fs;


    public function __construct(private string $namespace)
    {
        $this->codeGenerator = new CodeGenerator();
        $this->fs = new Filesystem();

    }

    public function generate(string $abstractClassName, string $concreteClassName, array $args = null): GenerateResultCollection {
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
//        dd($concreteClassName);
        if(!$configGenerator->hasCode($concreteClassName)) {
            $code = $configGenerator->appendCode($codeForAppend);
            $this->fs->dumpFile($configFile, $code);
        }

        return new GenerateResultCollection([
            new GenerateResult($configFile, $code)
        ]);
    }

}